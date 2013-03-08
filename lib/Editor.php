<?php 

class Editor {
    private $fields, $headings, $sizes;

    public function __construct($fields, $headings, $sizes) {
        $this->fields = preg_split('/\s*,\s*/', $fields);
        $this->headings = preg_split('/\s*,\s*/', $headings);
        $this->sizes = preg_split('/\s*,\s*/', $sizes);
    }

    public function get_field_descriptions() {
        $result = array();
        $fieldcount = count($this->fields);

        for($i = 0; $i < $fieldcount; $i++) {
            $name = $this->fields[$i];

            $required = strpos($name, '*') !== FALSE;
            
            $name = rtrim($name, '*');
            
            $field = array($name, $this->headings[$i], $this->sizes[$i],
                $required);

            array_push($result, $field);
        }
        
        return $result;
    }
}
