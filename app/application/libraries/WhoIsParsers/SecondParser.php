<?php

require_once ("InterfaceParser.php");

class SecondParser implements InterfaceParser
{
    public function parse($data){
        $map = [
            'Registrant',
            'Admin Contact',
            'Technical Contacts',
            'Registrar',
            'Nameservers',
        ];

        $cat = 'Common';

        if(is_string($data)){
            $data = str_replace(array("\r", "<<<", ">>>"), "", $data);
            $rows = explode("\n", $data);
            $parsed = array();
            foreach($rows as $row){
                if (in_array(trim($row), $map)) {
                    $cat = $row;
                    continue;
                }
                if(
                    (strstr($row, '://') and substr_count($row, ":") > 1) or
                    (!strstr($row, '://') and substr_count($row, ":") > 0) or
                    ($cat === 'Nameservers')
                )
                {
                    if (
                        strlen($row) <= 100 and
                        preg_match('/[\s]{0,}(.*?)[\s]{0,}:(.*?)$/sim', $row, $result)
                    )
                    {
                        $key = trim($result[1]);
                        $val = trim($result[2]);

                        if(!isset($parsed[$cat][$key])){
                            $parsed[$cat][$key] = trim($val);
                        }
                    } else {
                        if(!isset($parsed[$cat])) {
                            $parsed[$cat] = trim($row);
                        }
                    }
                }
            }

            return [
                'Tech_Email' => $parsed['Technical Contacts']['Email'] ?? '',
                'Admin_Email' => $parsed['Admin Contact']['Email'] ?? '',
                'Name_Server' => $parsed['Nameservers'] ?? '',
                'Creation_Date' => $parsed['Common']['Created'] ?? '',
                'Updated_Date' => $parsed['Common']['Last Update'] ?? '',
                'Registrar_Registration_Expiration_Date' => $parsed['Common']['Expire Date'] ?? '',
                'Registrar_URL' => $parsed['Registrar']['Web'] ?? '',
                'Registrar' => $parsed['Registrar']['Organization'] ?? '',
                'Registrant_Name' => $parsed['Registrant']['Name'] ?? '',
                'Registrant_Organization' => $parsed['Registrant']['Organization'] ?? '',
                'Registrant_Street' => $parsed['Registrant']['Address'] ?? '',
                'Registrant_Email' => $parsed['Registrant']['Email'] ?? '',
                'Registrant_Phone' => $parsed['Registrant']['Phone'] ?? '',
                'Admin_Name' => $parsed['Admin Contact']['Name'] ?? '',
                'Admin_Street' => $parsed['Admin Contact']['Address'] ?? '',
                'Admin_Phone' => $parsed['Admin Contact']['Phone'] ?? '',
            ];
        }
        else{
            return false;
        }
    }
}