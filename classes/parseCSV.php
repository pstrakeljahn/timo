<?php

namespace ParseCSV;

class CSVServiceClass {

    protected $csvSyntax;

    public function __construct()
    {
        return $this;
    }

    public function CSVtoArray($filename = '', $delimiter = ',') {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $headers = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, null, $delimiter, '"')) !== false) {
                if (!$headers) {
                    $headers = $row;
                    array_walk($headers, 'trim');
                    $headers = array_unique($headers);
                } else {
                    for ($i = 0, $j = count($headers); $i < $j;  ++$i) {
                        $row[$i] = trim($row[$i]);
                        if (empty($row[$i]) && !isset($data[trim($headers[$i])])) {
                            $data[trim($headers[$i])] = array();
                        } else {
                            $data[trim($headers[$i])][] = stripcslashes($row[$i]);
                        }
                    }
                }
            }
            fclose($handle);
        }
        return $data;
    }
}
