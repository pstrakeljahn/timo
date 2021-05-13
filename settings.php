<?php
include "classes/parseCSV.php";
include "classes/buildOutput.php";

use BuildOutput\BuildOutputClass;

class Analyser
{
    // EINSTELLPARAMETER
    // *****************
    // FILEPATH: Hier musst du den Dateinamen angeben. Die csv muss im gleichen Ordner liegen, wie diese Datei!
    // DATAPOINTS: Gibt an wie viele Stämme relevant sind. Die anderen werden rausgerechnet und diese Anzahl sind dann 100%.
    // SINGLE: Du kannst die Datenpunkte entweder alle einzeln bestimmen (true) oder kumulieren (false).
    // MERGESIZE: Wenn du kumulieren willst musst du angeben wie viele Datensätze zu einer Messung ghören.
    // DECIMALS: Stellt die Nachkommastellen ein.
    // SHOWDNASEQUENZ: Zeigt die DNA Sequenz im Browser an. Sonst nur in der csv
    const FILEPATH = "Gattung_Salami_TB.csv";
    const DATAPOINTS = 6;
    const SINGLE = false;
    const MERGESIZE = 6;
    const DECIMALS = 3;
    const SHOWDNASEQUENZ = false;

    public function analyse()
    {
        // Starte Analyse
        $analyse = new BuildOutputClass();
        return $analyse->getCSV(self::FILEPATH, self::DATAPOINTS, self::SINGLE, self::MERGESIZE);
    }

    public function createCSV()
    {
        $arrAnalyse = $this->analyse();
        $fileContent = [];

        foreach ($arrAnalyse as $key => $value1)
        {
            foreach ($value1 as $key2 => $value2)
            {
                $fileContent[] = [$key, round($value2, self::DECIMALS) , $key2];
            }
        }

        date_default_timezone_set("Europe/Berlin");
        $timestamp = time();
        $formatedTime = date("Ymd-Hi", $timestamp);

        $fp = fopen('data_' . $formatedTime . '.csv', 'w');

        foreach ($fileContent as $fields)
        {
            fputcsv($fp, $fields, ';');
        }

        fclose($fp);
    }
}

