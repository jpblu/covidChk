<?php
/**
 * Covid-19 CSV Data Loader
 * Copyright © 2020, Andrea Fusco
 *
 * Licensed under Creative Commons By-Nc-Nd
 * Redistributions of files must retain the above copyright notice.
 *
 * @Copyright     	Copyright © 2020, Andrea Fusco
 * @License       	Creative Commons By-Nc-Nd (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @File		  	updated.php
 * @Description	  	Refresh Data DB from PcM-DpC (https://github.com/pcm-dpc/COVID-19)
 * @Version		  	1.1.0
 * @Created		  	2020-03-20
 * @Updated		  	2021-09-22
 */
 
require("lib/config.php");
require("lib/logs.php");

//Effettuo Upload del file
echo "Carico i file...              ";
unlink('file/covid19_province.csv');
unlink('file/covid19_regioni.csv');
 
$url = 'https://github.com/pcm-dpc/COVID-19/raw/master/dati-province/dpc-covid19-ita-province.csv';
$img = 'file/covid19_province.csv';
file_put_contents($img, file_get_contents($url));

$url = 'https://github.com/pcm-dpc/COVID-19/raw/master/dati-regioni/dpc-covid19-ita-regioni.csv';
$img = 'file/covid19_regioni.csv';
file_put_contents($img, file_get_contents($url));

$url = 'https://github.com/italia/covid19-opendata-vaccini/raw/master/dati/vaccini-summary-latest.csv';
$img = 'file/covid19_vaccini.csv';
file_put_contents($img, file_get_contents($url));

echo "OK!<br>";

//Cancello la tabella covid19_province
echo "Svuoto le tabella...           ";
$mysqli = new mysqli(GlobalConfig::$dbconnect['host'], GlobalConfig::$dbconnect['user'], GlobalConfig::$dbconnect['pswd'], GlobalConfig::$dbconnect['name']);
		
if ($mysqli->connect_errno) {
	echo "Errore in connessione al DB: ".$mysqli->connect_errno;
	exit();
}

$mysqli->query("TRUNCATE TABLE covid19_province");
$mysqli->query("TRUNCATE TABLE covid19_regioni;");
$mysqli->query("TRUNCATE TABLE covid19_vaccini;");

echo "OK!<br>";
 
//Ricarico la tabella covid19_province, covid19_regioni e covid19_vaccini
echo "Carico i nuovi dati...         ";

$csv = array_map('str_getcsv', file('file/covid19_province.csv'));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv); # remove column header

//print_r($csv);

foreach($csv as $key => $value) {
	if (!$mysqli->query('INSERT covid19_province VALUES("'.$csv[$key]['data'].'","'.$csv[$key]['stato'].'","'.$csv[$key]['codice_regione'].'","'.$csv[$key]['denominazione_regione'].'","'.$csv[$key]['codice_provincia'].'","'.htmlentities($csv[$key]['denominazione_provincia']).'","'.$csv[$key]['sigla_provincia'].'","'.$csv[$key]['lat'].'","'.$csv[$key]['long'].'","'.$csv[$key]['totale_casi'].'")')) {
	    echo $mysqli->error;
		exit();
	}
}

$csv = array_map('str_getcsv', file('file/covid19_regioni.csv'));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv); # remove column header

//print_r($csv);

foreach($csv as $key => $value) {
	if (!$mysqli->query('INSERT covid19_regioni VALUES("'.$csv[$key]['data'].'","'.$csv[$key]['stato'].'","'.$csv[$key]['codice_regione'].'","'.htmlentities($csv[$key]['denominazione_regione']).'","'.$csv[$key]['lat'].'","'.$csv[$key]['long'].'","'.$csv[$key]['ricoverati_con_sintomi'].'","'.$csv[$key]['terapia_intensiva'].'","'.$csv[$key]['totale_ospedalizzati'].'","'.$csv[$key]['isolamento_domiciliare'].'","'.$csv[$key]['totale_positivi'].'","'.$csv[$key]['variazione_totale_positivi'].'","'.$csv[$key]['nuovi_positivi'].'","'.$csv[$key]['dimessi_guariti'].'","'.$csv[$key]['deceduti'].'","'.$csv[$key]['casi_da_sospetto_diagnostico'].'","'.$csv[$key]['casi_da_screening'].'","'.$csv[$key]['totale_casi'].'","'.$csv[$key]['tamponi'].'","'.$csv[$key]['casi_testati'].'","'.htmlentities($csv[$key]['note']).'")')) {
	    echo $mysqli->error;
		exit();
	}
}

$csv = array_map('str_getcsv', file('file/covid19_vaccini.csv'));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv); # remove column header

//print_r($csv);

foreach($csv as $key => $value) {
	if (!$mysqli->query('INSERT covid19_vaccini VALUES("'.$csv[$key]['area'].'","'.$csv[$key]['dosi_somministrate'].'","'.$csv[$key]['dosi_consegnate'].'","'.htmlentities($csv[$key]['percentuale_somministrazione']).'","'.$csv[$key]['ultimo_aggiornamento'].'","'.$csv[$key]['codice_NUTS1'].'","'.$csv[$key]['codice_NUTS2'].'","'.$csv[$key]['codice_regione_ISTAT'].'","'.$csv[$key]['nome_area'].'")')) {
	    echo $mysqli->error;
		exit();
	}
}

echo "OK!<br>";

//Formattazione Date
echo "Formattazione campi data...    ";

$mysqli->query("UPDATE covid19_province SET DATA = SUBSTRING(DATA,1,10);");
$mysqli->query("UPDATE covid19_regioni SET DATA = SUBSTRING(DATA,1,10);");

echo "OK!<br>";

$logtxt = "CSV Update OK - Manual Launch";
Logs::writeBatchLog("logs/cronLog.txt", $logtxt);
 
?>