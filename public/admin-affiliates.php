<?php 
$admin_required = TRUE;
require_once '../lib/bootstrap.php';

class Affiliates extends Template {
    private function make_country_selector($details) {
        $db = new Database();
        $rows = $db->get_rows('countries', 'name');
        echo '<select id="details_'.$details[0].'" class="detailsfield">';
        foreach($rows as $row) {
            echo '<option value="'.$row[0].'">'.$row[1].'</option>';
        }
        echo '</select>';

        if($details[3])
            echo ' *';
    }

    protected function make_data_field($details) {
        if($details[0] == 'country') {
            $this->make_country_selector($details);
        } else if($details[0] == 'wizard_complete'
                || $details[0] == 'administrator'
                || $details[0] == 'default_commission') {
            $this->make_checkbox_selector($details);
        } else {
            parent::make_data_field($details);
        }
    }
}

$template = new Affiliates('admin-affiliates');

$template->set('fields', new Editor(
    Database::$affiliate_fields, Database::$affiliate_headings,
    Database::$affiliate_sizes));

$affiliates = __('Affiliates');
$template->set('title', "$affiliate_programme_name: $affiliates");
$template->set('commission_percent', $commission_percent);
$template->set('commission_fixed', $commission_fixed);
$template->set('commission_parent_percent', $commission_parent_percent);
$template->set('commission_parent_fixed', $commission_parent_fixed);
$template->render();
