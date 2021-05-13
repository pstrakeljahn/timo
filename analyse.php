<html>
<header>
<title>Auswertung</title>
</header>
<body>
<?php
include "settings.php";

$analyseData = new Analyser();
$arrAnalyse = $analyseData->analyse();
echo '<h1>Auswertung der Daten, min Lütt :*</h1>';
echo '<div style="float:right; margin-right: 50px;"><a href="analyse.php?csvExport=true">Export CSV-File</a></div>';

if (isset($_GET['csvExport']))
{
    $analyseData->createCSV();
}

foreach ($arrAnalyse as $key => $analyse)
{
    echo '<h2><b><u>' . $key . "</b></u></h2>";
    echo '
    <table border="3">
        <thead>
            <th style="width:250px;">proz. Anteil</th>
            <th style="width:400px;">';
    $title = 'Stamm';
    if ($analyseData::SHOWDNASEQUENZ)
    {
        $title = $title . ' und DNS-Sequenz';
    }
    echo $title . '</th>
        </thead>
    ';
    foreach ($analyse as $key => $percent)
    {
        $test = explode('__', $key);
        $name = '';
        for ($i = 1;$i < count($test);$i++)
        {
            $name = $name . ' ' . $test[$i - 1];
        }
        $dna = $test[count($test) - 1];
        echo '
        <tr>
            <td style="padding-left:15px;"><b>' . round($percent, $analyseData::DECIMALS) . ' %<b></td>
            <td>' . substr($name, 1);
        if ($analyseData::SHOWDNASEQUENZ)
        {
            echo '<br>' . $dna;
        }
        echo '</td></tr>';
    }
    echo '
    </table><br><br>
    ';
}

?>
</body>
</html>
