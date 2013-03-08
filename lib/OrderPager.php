<?php 

class OrderPager extends Pager {
    const entries_per_page = 20;

    public function __construct($table, $fields, $column_heads,
            $full_fields = '') {
        $this->table = $table;
        $this->fields = preg_split('/\s*,\s*/',
            preg_replace('/\\*/', '', $fields));

        if($full_fields == '') {
            $this->full_fields = $this->fields;
        } else {
            $this->full_fields = preg_split('/\s*,\s*/',
                preg_replace('/\\*/', '', $full_fields));
        }

        $this->column_heads = preg_split('/\s*,\s*/', $column_heads);
        $this->db = new Database();
        $this->date_format = '%b %d %Y %H:%M:%S';
        $this->affiliate_restriction = 'affiliate = :affiliate or ' .
            'parent = :affiliate and ';
        $this->date_restriction = 'date_entered > :start and ' .
            'date_entered < :end + interval 1 day and ';
        $this->id_restriction = '';
        $this->id = '';
        $this->order_by = 'date_entered';
        $this->admin_mode = FALSE;
    }

    public function download() {
        header('Content-Type: text/plain');
        $tab = '';
        foreach($this->full_fields as $field) {
            if ($field == 'parent'){
                echo $tab . 'sec_tier';
            } else {
            echo $tab . $field;
            }
            $tab = "\t";
        }
        echo "\n";

        $stmt = $this->get_data_query(0, 1000000000);
        $rows = $this->execute($stmt, $this->get_restrictions());

        foreach($rows as $row) {
            $tab = '';
            foreach($this->full_fields as $field) {
            if ($field == 'parent') {
                if ($row['parent'] == $_SESSION['affiliate_id'])
                echo $tab . "Yes";
                else
                echo $tab . "No";
        
            }else if ($field == 'commission' && $row['parent'] == $_SESSION['affiliate_id']) {
                echo $tab . $row['parent_commission'];
            }else {
                echo $tab . $row[$field];
            }
            $tab = "\t";
            }
            echo "\n";
        }
    }

    public function json($page_number) {
        $result = array();
        $html = '';

        $html .= '<tr>';
        foreach($this->column_heads as $field)
        {
            $html .= "<th>$field</th>";
        }

        if($this->editable)
        {
            $html .= '<td>&nbsp;</td>';
        }
        $html .= '</tr>';

        $stmt = $this->get_data_query(
            self::entries_per_page * $page_number, self::entries_per_page);

        $rows = $this->execute($stmt, $this->get_restrictions());

        foreach($rows as $row) {
            $html .= '<tr>';
            if ($row['parent'] != $_SESSION['affiliate_id']){

            foreach($this->fields as $field) {
                if($field == 'date_entered') {
                    $date = strtotime($row[$field]);
                    $date = strftime($this->date_format, $date);
                    $html .= "  <td>$date</td>";

                 if($this->editable) {
                     $html .= '<td>';
                     $html .= "<img id='edit_" . $row[0] . "' class='edit' " .
                       "src='images/edit.png'/>";
                     $html .= "<img id='delete_" . $row[0] . "' class='delete' " .
                        "src='images/remove.png'/>";
                     $html .= '</td>';
                   }

                } else if ($field == 'parent') {
                $html .= "<td>" . "No" . "</td>";


                }else {
                    $html .= "<td>" . $row[$field] . "</td>";
                }
            }
        } else if ($row['parent'] == $_SESSION['affiliate_id']) {

             foreach($this->fields as $field) {
                if($field == 'date_entered') {
                    $date = strtotime($row[$field]);
                    $date = strftime($this->date_format, $date);
                    $html .= "  <td>$date</td>";

                 if($this->editable) {
                     $html .= '<td>';
                     $html .= "<img id='edit_" . $row[0] . "' class='edit' " .
                       "src='images/edit.png'/>";
                     $html .= "<img id='delete_" . $row[0] . "' class='delete' " .
                        "src='images/remove.png'/>";
                     $html .= '</td>';
                   }

                } else if ($field == 'parent') {
                $html .= "<td>" . "Yes" . "</td>";


                }else if ($field == 'commission') {
                $html .= "<td>" . $row['parent_commission'] . "</td>";


                }else {
                    $html .= "<td>" . $row[$field] . "</td>";
                }
            }
            }
            $html .= '</tr>';

        }

        $result['html'] = $html;

        $stmt = $this->get_count_query();
        $rows = $this->execute($stmt, $this->get_restrictions());
        $pages = (int) ceil($rows[0][0] / self::entries_per_page);
        $result['pages'] = $pages == 0 ? 1 : $pages;

        echo json_encode($result);
    }

    public function json_single($id) {
        $result = array();

        $this->affiliate_restriction = '';
        $this->date_restriction = '';
        $this->id_restriction = $this->fields[0] . ' = :id and ';
        $this->id = $id;

        $stmt = $this->get_data_query(0, 1000000000);
        $rows = $this->execute($stmt, $this->get_restrictions());

        foreach($this->full_fields as $field)
            $result[$field] = $rows[0][$field];

        echo json_encode($result);
    }

}
