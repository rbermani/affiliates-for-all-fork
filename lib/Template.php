<?php 

class Template {
    private $file, $show_menu, $variables, $admin;

    public static function get_ajax_key() {
        // This key is quoted in AJAX requests to protect against CSRF attacks.
        // We could use the session ID on its own, but hashing it provides
        // "defence in depth": if someone manages to find out the AJAX key,
        // they at least can't determine the session cookie.
        return sha1(session_id());
    }

    public static function check_ajax_key() {
        if($_GET['csrfkey'] != Template::get_ajax_key()) {
            echo 'CSRF check failed.';
            exit();
        }
    }

    public function __construct($file) {
        global $admin_required;

        $this->file = $file;
        $this->show_menu = true;
        $this->variables = array();
        $this->admin = isset($admin_required);

        try {
            $db = new Database();
            $result = $db->get_pdo()->query('select count(*) from banners');
            if($result) {
                $result = $result->fetch();
                $this->offer_banners = $result[0] > 0;
            } else {
                $this->set('reason',
                    'This may be because the table definitions have not been ' .
                    'loaded into the database.');
                $this->fatal('Unable to read the banners table.');
            }
        } catch(PDOException $ex) {
            $this->set('reason',
                'This probably means that the database details in ' .
                'config.inc are incorrect.');

            $this->fatal($ex->getMessage());
        }

        $this->set('key', Template::get_ajax_key());
    }

    private function fatal($msg) {
        global $affiliate_programme_name;

        $this->file = 'fatal';
        $this->suppress_menu();
        $this->set('title', "$affiliate_programme_name: Database Error");
        $this->set('message', $msg);
        $this->render();
        exit();
    }

    public function suppress_menu() {
        $this->show_menu = false;
    }

    public function set($key, $value) {
        $this->variables[$key] = $value;
    }

    public function get($key) {
        return $this->variables[$key];
    }
    protected function make_date_selector($details, $time) {
        echo '<div id="details_'.$details[0].'" ' .
            'class="detailsdate detailsfield">';

        echo '<input class="date" type="text" size="12"> ';

        if($time) {
            echo '<input class="hours" type="text" size="2">:';
            echo '<input class="minutes" type="text" size="2">:';
            echo '<input class="seconds" type="text" size="2">';
        }

        if($details[3])
            echo ' *';

        echo '</div>';
    }

    protected function make_checkbox_selector($details) {
        echo '<input id="details_'.$details[0].'" class="detailsfield" ' .
            'type="checkbox">';

        if($details[3])
            echo ' *';
    }

    protected function make_data_field($details) {
        echo '<input id="details_'.$details[0].'" class="detailsfield" ' .
            'type="text" size="'.$details[2].'">';

        if($details[3])
            echo ' *';
    }

    public function render() {
        // PHP oddity: when an array is cast to an object, its keys become
        // the object's members.  This is very convenient because the templates
        // can then use the $v->foo syntax rather than the more clumsy
        // $v['foo'].

        $v = (object) $this->variables;

        require 'head.phtml';
        require $this->file . '.phtml';
        require 'tail.phtml';
    }
}
