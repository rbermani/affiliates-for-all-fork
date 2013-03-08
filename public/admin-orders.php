<?php 

$admin_required = TRUE;
require_once '../lib/bootstrap.php';

class Orders extends Template {
    private function make_status_selector($details) {
        $db = new Database();
        $rows = $db->get_rows('order_status', 'id');
        echo '<select id="details_'.$details[0].'" class="detailsfield">';
        foreach($rows as $row) {
            echo '<option value="'.$row[0].'">'.$row[0].'</option>';
        }
        echo '</select>';

        if($details[3])
            echo ' *';
    }

    protected function make_data_field($details) {
        if($details[0] == 'status') {
            echo $this->make_status_selector($details);
        } else if($details[0] == 'date_entered') {
            echo $this->make_date_selector($details, True);
        } else {
            parent::make_data_field($details);
        }
    }
}

$template = new Orders('admin-orders');

$template->set('fields', new Editor(
    Database::$order_fields, Database::$order_headings,
    Database::$order_sizes));

$allorders = __('All Orders');
$template->set('title', "$affiliate_programme_name: $allorders");
$template->render();
