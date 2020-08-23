<?php

require_once ("InterfaceParser.php");

class CommonParser implements InterfaceParser
{
    public function parse($data)
    {
        $new_data = preg_split("/\r\n|\n|\r/", $data);
        $empty_array = array();
        foreach ($new_data as $key => $value) {

            preg_match('@([a-zA-Z\s]+):\s(.*)@mui', $value, $matches);
            if (isset($matches[1]) && $matches[2]) {
                $empty_array[$matches[1]]  =  $matches[2];
            }

        }

        $keys = str_replace( ' ', '_', array_keys( $empty_array ) );
        $results = array_combine( $keys, array_values( $empty_array ) );

        return $results;
    }
}