<?php 

require_once 'Database.php';
require_once 'Trigger.php';

class Notification extends Trigger {
    private static $url, $insert, $update, $signoff;

    private function split_words($line) {
        return preg_split('/\s*,\s*/', preg_replace('/\\*/', '', $line));
    }

    private function send($to, $subject, $content) {
        global $administrator_email_address;

        ini_set('sendmail_from', $administrator_email_address);
        $headers = "From: $administrator_email_address\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit\n";

        mail($to, $subject, $content, $headers,
            '-f '.$administrator_email_address);
    }

    private function send_update($affiliate, $subject, $content) {
        global $notification_email_address;

        if($notification_email_address != '')
            $this->send($notification_email_address, $subject, $content);

        $db = new Database();
        $record = $db->get_row_by_key('affiliates', 'id', $affiliate);
        if($record['email_update'])
            $this->send($record['email'], $subject, $content);
    }

    public function insert($db, $table, $fields) {
        global $order_fields_available, $order_fields_headings;

        if($table != 'orders')
            return;

        // Affiliate will see a change notification ('shipped -> refunded') for
        // a refund, so we don't need to email separately about the new record
        // which reverses the payment.
        if($fields['status'] == 'refund')
            return;

        $content = Notification::$insert;
        $content .= "\n\n";

        $order_fields = $this->split_words($order_fields_available);
        $order_headings = $this->split_words($order_fields_headings);
        for($i = 0; $i < count($order_fields); $i++) {
	    if(isset($fields[$order_fields[$i]])) {
		$content .= $order_headings[$i] . ': ';
		$new_value = $fields[$order_fields[$i]];
		if($order_fields[$i] == 'date_entered') {
		    if(isset($fields['date_entered'])) {
			$new_value = Database::format_date_time($new_value);
		    } else {
			$new_value = strftime('%Y-%m-%d %H:%M:%S');
		    }
		} else if($order_fields[$i] == 'total' ||
			$order_fields[$i] == 'commission') {
		    $new_value = sprintf('%2.2f', $new_value);
		}

		$content .= "$new_value\n";
	    }
        }

        $content .= "\n";
        $content .= Notification::$signoff;

        $this->send_update($fields['affiliate'], '[affiliate] New Order',
            $content);
    }

    public function update($db, $table, $field, $value, $change) {
        global $order_fields_available, $order_fields_headings;

        if($table != 'orders')
            return;

        $content = Notification::$update;
        $content .= "\n\n";

        $record = $db->get_row_by_key($table, $field, $value);
        $order_fields = $this->split_words($order_fields_available);
        $order_headings = $this->split_words($order_fields_headings);
        for($i = 0; $i < count($order_fields); $i++) {
            $content .= $order_headings[$i] . ': ';
            if(isset($change[$order_fields[$i]])) {
                $new_value = $change[$order_fields[$i]];
                if($order_fields[$i] == 'date_entered')
                    $new_value = Database::format_date_time($new_value);
            } else {
                $new_value = $record[$order_fields[$i]];
            }

            if($new_value != $record[$order_fields[$i]]) {
                $content .= $record[$order_fields[$i]] . ' -> ' .
                    $new_value . "\n";
            } else {
                $content .= $record[$order_fields[$i]] . "\n";
            }
        }

        $content .= "\n";
        $content .= Notification::$signoff;

        $affiliate = $record['affiliate'];
        if(isset($change['affiliate']))
            $affiliate = $change['affiliate'];

        $this->send_update($affiliate, '[affiliate] Order Changed', $content);
    }

    public static function register() {
        global $https, $store_home;

        $trigger = new Notification();
        Database::register_trigger($trigger);

        $dir = dirname($_SERVER['PHP_SELF']);
        if($dir != '/') $dir .= '/';
        $protocol = $https ? 'https' : 'http';
        $url = "$protocol://${_SERVER['HTTP_HOST']}$dir";

        Notification::$insert = <<<end
Your affiliate account has been credited with a new order.  The details are as
follows:
end;

        Notification::$update = <<<end
An order in your affiliate account has been changed.  The details are as
follows:
end;

        Notification::$signoff = <<<end
You are receiving this message because you signed up to be an affiliate of
$store_home .

If you no longer wish to receive these emails, you can turn them off by
visiting $url and clicking ‘Your Account’.
This will take you to a page where you can set your email preferences.

If you have any questions, please feel free to contact us at any time.
However, this email was sent from an address which is not monitored, so a reply
will not reach us.

Thank you for being a member of our affiliate programme.
end;

    }
}

Notification::register();
