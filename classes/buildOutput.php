<?php

namespace BuildOutput;

use ParseCSV\CSVServiceClass;

class BuildOutputClass
{
    public function getCSV($filepath, $datapoints, $single, $mergeSize)
    {
        $csvHelper = new CSVServiceClass();
        $arrCSV = $csvHelper->CSVtoArray($filepath, ";");
        if (!$arrCSV) {
            return "Datei nicht gefunden";
        }
        $i = 0;
        $arrExport = [];

        foreach ($arrCSV as $key => $CSV) {
            // Die erste Spalte sind die Namen, daher übersprungen. Benutzen um an anderes Array zu joinen
            if (!$i) {
                // Die Namen werden ein wenig verschönert
                $names = [];
                foreach ($CSV as $key) {
                    $exploded = explode("__", $key);
                    $keyShort =
                        count($exploded) > 2
                            ? $exploded[0] . " " . $exploded[1]
                            : "unknown";
                    array_push($names, $keyShort);
                }
                $i++;
                continue;
            }

            // Falls der Key nur aus 2 Zeichen besteht. Kommt eine führende null dazu. Aus Sortiergründen
            if (strlen($key) < 3) {
                $key = "0" . $key;
            }

            // Es muss hier zwischen single true/flase unterschieden werden
            if (!$single) {
                if (!isset($datapoints_new)) {
                    $datapoints_new = $datapoints;
                }
                $datapoints = count($names);
            }

            // Nur die obersten 5 Einträge werden behalten
            $_arrWithNames = array_combine($names, $CSV);
            if (arsort($_arrWithNames)) {
                $arrExport[$key] = array_slice($_arrWithNames, 0, $datapoints);
            }
        }

        // Sortieren nach Key
        if (ksort($arrExport)) {
            if (!$single) {
                return $this->cumulateData(
                    $this->convertToPercent($arrExport),
                    $mergeSize,
                    $datapoints_new
                );
            } else {
                return $this->convertToPercent($arrExport);
            }
        } else {
            return "Da lief was schief!";
        }
    }

    public function convertToPercent($array)
    {
        $arrExport_new = [];

        foreach ($array as $key => $entry) {
            $all = array_sum(array_values($entry));
            $count = [];
            foreach (array_values($entry) as $value) {
                if ($value === "") {
                    $value = 0;
                }
                // Ausgabe in Prozent
                array_push($count, ($value / $all) * 100);
            }

            $arrExport_new[$key] = array_combine(array_keys($entry), $count);
        }
        return $arrExport_new;
    }

    public function cumulateData($array, $mergeSize, $datapoints_new)
    {
        $keys = [];
        for ($i = 1; $i <= count($array); $i++) {
            $key = $i < 10 ? "0" . $i . "S" : $i . "S";
            ksort($array[$key]);
            if (!count($keys)) {
                // Einmaliges Sammeln der Keys
                $keys = array_keys($array[$key]);
            }
        }

        while (count($array) >= $mergeSize) {
            $slicedArray = array_slice($array, 0, $mergeSize);
            $start = array_keys($slicedArray)[0];
            $end = array_keys($slicedArray)[$mergeSize - 1];

            // Bisschen unperformant. Du liest aber auch wohl nicht Milliarden an Datenpunkten ein!
            foreach ($slicedArray as $singleArray) {
                $k = 0;
                while ($k < count($singleArray)) {
                    $run = array_values($singleArray);
                    if (!isset($res[$k])) {
                        $res[$k] = 0;
                    }
                    $res[$k] += $run[$k];
                    $k++;
                }
            }
            $data = array_combine($keys, $res);
            if (arsort($data)) {
                $data = array_slice($data, 0, $datapoints_new);
            }
            $output_pre[$start . " to " . $end] = $data;
            $array = array_slice($array, $mergeSize);
        }

        // Für den Fall, dass die Anzahl der zusammengehörenden Datenpunkte nicht durch die Gesamtzhal teilbar ist ohne Rest
        if (count($array)) {
            $start = array_keys($slicedArray)[0];
            $end = array_keys($slicedArray)[count($array) - 1];

            foreach ($array as $singleArray) {
                $k = 0;
                while ($k < count($singleArray)) {
                    $run = array_values($singleArray);
                    if (!isset($res[$k])) {
                        $res[$k] = 0;
                    }
                    $res[$k] += $run[$k];
                    $k++;
                }
            }
            $data = array_combine($keys, $res);
            if (arsort($data)) {
                $data = array_slice($data, 0, $datapoints_new);
            }
            $output_pre[$start . " to " . $end] = $data;
        }

        return $this->convertToPercent($output_pre);
    }
}
