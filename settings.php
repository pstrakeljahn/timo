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

    const FILEPATH = "Gattung_Salami_TB.csv";
    const DATAPOINTS = 6;
    const SINGLE = false;
    const MERGESIZE = 6;
    const DECIMALS = 3;

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