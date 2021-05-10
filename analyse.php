<?php
include "classes/parseCSV.php";
include "classes/buildOutput.php";

use BuildOutput\BuildOutputClass;

class Analyser
{
    const FILEPATH = "Gattung_Salami_TB.csv";

    public function analyse()
    {
        // Starte Analyse
        $analyse = new BuildOutputClass();
        return $analyse->getCSV(self::FILEPATH);
    }
}

// Behelfsmäßiges instanziieren
$test = new Analyser();
var_dump($test->analyse());
