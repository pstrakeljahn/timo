<?php

namespace BuildOutput;

use ParseCSV\CSVServiceClass;

class BuildOutputClass {

    public function getCSV($filepath)
    {
        $csvHelper = new CSVServiceClass();
        $arrCSV = $csvHelper->CSVtoArray($filepath, ";");
        $i = 0;
        $arrExport = [];

        foreach ($arrCSV as $key => $CSV) {
            // Die erste Spalte sind die Namen, daher übersprungen. Benutzen um an anderes Array zu joinen
            if (!$i) {
                // Die Namen werden ein wenig verschönert
                $names = [];
                foreach($CSV as $key){
                    $exploded = explode("__",$key);
                    array_push($names, $exploded[0] . " " . $exploded[1]);
                }
                $i++;
                continue;
            }

            // Falls der Key nur aus 2 Zeichen besteht. Kommt eine führende null dazu. Aus Sortiergründen
            if(strlen($key) < 3){
                $key = "0" . $key;
            }

            // Nur die obersten 5 Einträge werden behalten
            $_arrWithNames = array_combine($names, $CSV);
            if (arsort($_arrWithNames)) {
                $arrExport[$key] = array_slice($_arrWithNames, 0, 5);
            }
        }
        
        // Sortueren nach Key
        ksort($arrExport);
        return $this->convertToPercent($arrExport);
    }

    public function convertToPercent($array){
        $arrExport_new = [];
        
        foreach($array as $key => $entry){
            $key = $key;
            $entry = $entry;
            $test = array_sum(array_values($entry));
            $count = []; 
            foreach(array_values($entry) as $value){
                // Ausgabe in Prozent
                array_push($count, $value/$test*100);
            }
            $arrExport_new[$key] = array_combine(array_keys($entry), $count);


        }
        return $arrExport_new;
    }
}
