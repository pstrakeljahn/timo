<html>
<header></header>
<body>
<?php
include "settings.php";

$analyseData = new Analyser();
$arrAnalyse = $analyseData->analyse();
echo '<h1>Auswertung der Daten, min Lütt :*</h1>';
foreach($arrAnalyse as $key => $analyse){
    echo '<h2><b><u>' . $key . "</b></u></h2>";
    echo '
    <table border="3" width="100">
        <thead>
            <th width="250">proz. Anteil</th>
            <th>Stamm und DNS-Sequenz</th>
        </thead>
    ';
    foreach($analyse as $key => $percent){
        $test = explode('__', $key);
        $name = '';
        for($i = 1; $i < count($test); $i++){
            $name = $name . ' ' . $test[$i-1];
        }
        $dna = $test[count($test)-1];
        echo '
        <tr>
            <td><b>' . round($percent, $analyseData::DECIMALS) . '<b></td>
            <td>'. substr($name,1) . '<br>' . $dna . '<td>
        </tr>';
    }
    echo '
    </table><br><br>
    ';
}

?>
</body>
</html>