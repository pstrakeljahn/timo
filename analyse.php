<?php
include "classes/parseCSV.php";
include "classes/buildOutput.php";

use BuildOutput\BuildOutputClass;

class Analyser
{
    // EINSTELLPARAMETER
    const FILEPATH = "Gattung_Salami_TB.csv";
    const DATAPOINTS = 6;
    const SINGLE = false;
    const MERGESIZE = 6;

    public function analyse()
    {
        // Starte Analyse
        $analyse = new BuildOutputClass();
        return $analyse->getCSV(
            self::FILEPATH,
            self::DATAPOINTS,
            self::SINGLE,
            self::MERGESIZE
        );
    }
}

// Behelfsmäßiges instanziieren
$test = new Analyser();
var_dump($test->analyse());
