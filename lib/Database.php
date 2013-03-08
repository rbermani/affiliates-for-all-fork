<?php 

class Database {
    public static $order_fields = 'id*, affiliate*, parent, status*,
        customer_id, customer_name, customer_email, total*, commission*,
        parent_commission, date_entered*, affiliate_data';
    public static $order_headings = 'Order Number, Affiliate, Parent, Status,
        Cust ID, Cust Name, Cust Email, Total, Comm, Par Comm, Order Date,
        Campaign Data';
    public static $order_sizes = '15, 8, 8, 8, 10, 20, 20, 6, 6, 6, 10, 20';

    public static $payment_fields = 'id, affiliate*, amount*, date_entered*';
    public static $payment_headings = 'Payment Number, Affiliate, Amount,
        Payment Date';
    public static $payment_sizes = '6, 8, 6, 10';

    public static $affiliate_fields = 'id, local_username*, local_password*,
        parent_id, title, first_name, last_name, email,
        address1, address2, address3, address4, postcode, country, phone,
        paypal, default_commission*, commission_percent, commission_fixed,
        commission_parent_percent, commission_parent_fixed,wizard_complete*,
        administrator*,password_reset,confirm_code,reset_stamp,login_retries';
    public static $affiliate_headings = 'Affiliate Number, Username, Password,
        Parent Number, Title, First Name, Last Name, Email,
        Address 1, Address 2, Address 3, Address 4, Post/Zip Code, Country,
        Phone,
        Paypal, Default Commission, Commission Percentage, Fixed Commission,
        Parent Commission Percent, Parent Commission Percentage,
        Wizard Complete, Administrator, Password Reset, Confirmation Code, Reset Stamp, Login Retries';
    public static $affiliate_sizes = '6, 20, 20, 6, 4, 10, 10, 20,
        10, 10, 10, 10, 10, 20,
        15, 20, 1, 5, 5, 5, 5,
        1, 1';

    public static $affiliate_short_fields = 'id, local_username,
        title, first_name, last_name, email';
    public static $affiliate_short_headings = 'Affiliate Number, Username,
        Title, First Name, Last Name, Email';

    private static $triggers = array();

    private $db;

    public static function format_currency($currency) {
        return sprintf('%2.2f', $currency);
    }

    public static function format_date($date) {
        $matches = array();
        $result = preg_match('/^([0-9]{4})([0-9]{2})([0-9]{2})$/',
            $date, $matches);

        // This shouldn't happen, but if someone tampers with the query string,
        // we shouldn't just crash:
        if($result < 1)
            return '2008-01-01';

        return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
    }

    public static function format_date_time($date) {
        $matches = array();
        $result = preg_match('/^([0-9]{8})([0-9]{2})([0-9]{2})([0-9]{2})$/',
            $date, $matches);

        return Database::format_date($matches[1]) . ' ' .
            $matches[2] . ':' . $matches[3] . ':' . $matches[4];
    }

    public static function register_trigger(&$trigger) {
        array_push(Database::$triggers, $trigger);
    }

    public function __construct() {
        $this->db = $this->get_connection();
    }

    private function get_connection() {
        global $database_dsn, $database_username, $database_password;
	return new PDO($database_dsn, $database_username, $database_password,
	    array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
    }

    public function get_pdo() {
        return $this->db;
    }

    public function get_rows($table, $sort) {
        $stmt = $this->db->query("select * from $table order by $sort");
        return $stmt->fetchAll();
    }

    public function get_rows_by_key($table, $field, $value) {
        $stmt = $this->db->prepare(
            "select * from $table where $field = :value");

        $stmt->execute(array('value' => $value));
        return $stmt->fetchAll();
    }

    public function get_row_by_key($table, $field, $value) {
        $rows = $this->get_rows_by_key($table, $field, $value);

        if(count($rows) == 0)
            return null;

        return $rows[0];
    }

    public function check_password_by_key($table, $user_id, $password, $secret_key) {
        $stmt = $this->db->query(
        "select * from $table where id=$user_id AND local_password=AES_ENCRYPT('$password','$secret_key')");
        return $stmt;
        

    }
    public function insert($table, $values) {
        $key_list = '';
        $value_list = '';
        $comma = '';

        foreach(Database::$triggers as $trigger) {
            $trigger->insert($this, $table, $values);
        }

        foreach(array_keys($values) as $key) {
            $key_list = "$key_list$comma$key";
            $value_list = "$value_list$comma:$key";
            $comma = ', ';
        }

        $insert = "insert into $table ($key_list) values ($value_list)";
        $stmt = $this->db->prepare($insert);
        return $stmt->execute($values);
    }

    public function update_password($table, $id, $password, $secret_key) {
        $stmt = $this->db->query(
            "update $table set local_password=AES_ENCRYPT('$password','$secret_key') where id=$id");
        
        return $stmt->execute();
    }

    public function update_by_key($table, $field, $value, $values) {
        $updates = '';
        $comma = '';

        foreach(Database::$triggers as $trigger) {
            $trigger->update($this, $table, $field, $value, $values);
        }

        foreach(array_keys($values) as $key) {
            $updates = "$updates$comma$key=:$key";
            $comma = ', ';
        }

        $update_list = $values;
        $update_list['value'] = $value;

        $stmt = $this->db->prepare(
            "update $table set $updates where $field = :value");

        return $stmt->execute($update_list);
    }

    public function delete_by_key($table, $field, $value) {
        $stmt = $this->db->prepare(
            "delete from $table where $field = :value");

        $stmt->execute(array('value' => $value));
    }
}
