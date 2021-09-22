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
 * @Date		  	2020-03-20
 * @Update		  	2021-09-22
 */
 
require("lib/config.php");
require("lib/logs.php");

//mysqlimport --ignore-lines=1 --fields-terminated-by=, --verbose --local --delete -u USR --password=PWS DBN /home/slepu/public_html/andreafusco.net/covid19chk/file/covid19_province.csv

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

echo "Fatto!<br>";
 
//Cancello la tabella covid19_province
echo "Svuoto le tabelle...           ";
$mysqli = new mysqli(GlobalConfig::$dbconnect['host'], GlobalConfig::$dbconnect['user'], GlobalConfig::$dbconnect['pswd'], GlobalConfig::$dbconnect['name']);
		
if ($mysqli->connect_errno) {
	echo "Errore in connessione al DB: ".$mysqli->connect_errno;
	exit();
}

$mysqli->query("TRUNCATE TABLE covid19_province;");
$mysqli->query("TRUNCATE TABLE covid19_regioni;");
$mysqli->query("TRUNCATE TABLE covid19_vaccini;");

echo "OK!<br>";
 
//Ricarico la tabella covid19_province
echo "Carico i nuovi dati...         ";

if (!$mysqli->query("LOAD DATA LOCAL INFILE 'file/covid19_province.csv' INTO TABLE covid19_province FIELDS TERMINATED BY ',' IGNORE 1 LINES")) {
    echo $mysqli->error;
	exit();
} else {
	echo "Province OK!<br>";
}

if (!$mysqli->query("LOAD DATA LOCAL INFILE 'file/covid19_regioni.csv' INTO TABLE covid19_regioni FIELDS TERMINATED BY ',' IGNORE 1 LINES")) {
    echo $mysqli->error;
	exit();
} else {
	echo "Regioni OK!<br>";
}

if (!$mysqli->query("LOAD DATA LOCAL INFILE 'file/covid19_vaccini.csv' INTO TABLE covid19_vaccini FIELDS TERMINATED BY ',' IGNORE 1 LINES")) {
    echo $mysqli->error;
	exit();
} else {
	echo "Regioni OK!<br>";
}

//Formattazione Date
echo "Formattazione campi data...    ";

$mysqli->query("UPDATE covid19_province SET DATA = SUBSTRING(DATA,1,10);");
$mysqli->query("UPDATE covid19_regioni SET DATA = SUBSTRING(DATA,1,10);");

echo "OK!<br>";

$logtxt = "CSV Update OK";
Logs::writeBatchLog("logs/cronLog.txt", $text);
 
?>