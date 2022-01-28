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
 * @File		  	sstat.php
 * @Description	  	ServerStatusLib
 * @Version		  	1.2.0
 * @Created		  	2020-03-20
 * @Updated		  	2022-01-28
 */

class SStats { 

	//Recupera la Lista dei giorni

	public static function getCV19Monitor($prov,$startdate,$enddate){
		$mysqli = new mysqli(GlobalConfig::$dbconnect['host'], GlobalConfig::$dbconnect['user'], GlobalConfig::$dbconnect['pswd'], GlobalConfig::$dbconnect['name']);
		
		if ($mysqli->connect_errno) {
			echo "Errore in connessione al DB: ".$mysqli->connect_errno;
			exit();
		}
		
		$mysqli->query("SET NAMES 'utf8'");
		
		$stmt = $mysqli->prepare('SELECT data, totale_casi FROM covid19_province WHERE codice_provincia = ? AND data BETWEEN ? AND ? ORDER BY DATA');
		$stmt->bind_param('sss', $prov,$startdate,$enddate);
		$stmt->execute();
		
		$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);	
		
		return json_encode($results);
		
	}
	
	//Recupera la Tabella degli Incrementi giornalieri
	
	public static function getCV19Table($prov,$startdate,$enddate){
		$mysqli = new mysqli(GlobalConfig::$dbconnect['host'], GlobalConfig::$dbconnect['user'], GlobalConfig::$dbconnect['pswd'], GlobalConfig::$dbconnect['name']);
		
		if ($mysqli->connect_errno) {
			echo "Errore in connessione al DB: ".$mysqli->connect_errno;
			exit();
		}
		
		$mysqli->query("SET NAMES 'utf8'");
		
		$stmt = $mysqli->prepare('SELECT codice_regione, data, incremento, totale_casi, ROUND(CASE WHEN ROW_NUMBER() OVER (PARTITION BY codice_provincia ORDER BY data DESC) THEN SUM(incremento) OVER (PARTITION BY codice_provincia ORDER BY data ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) END / 3) AS dayincr FROM (SELECT t2.codice_regione, t2.codice_provincia, t2.data, t2.totale_casi, GREATEST(0,t2.totale_casi - t1.totale_casi) AS incremento FROM covid19_province t1 INNER JOIN covid19_province t2 ON CAST(t1.data AS DATETIME) =  DATE_ADD(CAST(t2.data AS DATETIME), INTERVAL -1 DAY)  AND t1.codice_provincia = t2.codice_provincia WHERE t1.codice_provincia = ? AND t1.data BETWEEN ? AND ?) A ORDER BY data DESC');
		$stmt->bind_param('sss', $prov,$startdate,$enddate);
		$stmt->execute();
		
		$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);	
		
		return $results;
		
	}
	
	//Recupera la Tabella della Media incrementi ogni 3 Giorni
	
	public static function getCV193Day($prov,$startdate,$enddate){
		$mysqli = new mysqli(GlobalConfig::$dbconnect['host'], GlobalConfig::$dbconnect['user'], GlobalConfig::$dbconnect['pswd'], GlobalConfig::$dbconnect['name']);
		
		if ($mysqli->connect_errno) {
			echo "Errore in connessione al DB: ".$mysqli->connect_errno;
			exit();
		}
		
		$mysqli->query("SET NAMES 'utf8'");
		
		$stmt = $mysqli->prepare('SELECT data, ROUND(CASE WHEN ROW_NUMBER() OVER (PARTITION BY codice_provincia ORDER BY data DESC) THEN SUM(incremento) OVER (PARTITION BY codice_provincia ORDER BY data ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) END / 3) AS dayincr FROM (SELECT t2.codice_provincia, t2.data, GREATEST(0,t2.totale_casi - t1.totale_casi) AS incremento FROM covid19_province t1 INNER JOIN covid19_province t2 ON CAST(t1.data AS DATETIME) =  DATE_ADD(CAST(t2.data AS DATETIME), INTERVAL -1 DAY)  AND t1.codice_provincia = t2.codice_provincia WHERE t1.codice_provincia = ? AND t1.data BETWEEN ? AND ?) A ORDER BY data');
		$stmt->bind_param('sss',$prov,$startdate,$enddate);
		$stmt->execute();
		
		$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);	
		
		return $results;
		
	}
	
	//Recupera la Tabella degli Andamenti giornalieri (Crescente)
	
	public static function getCV19Increment($prov,$startdate,$enddate){
		$mysqli = new mysqli(GlobalConfig::$dbconnect['host'], GlobalConfig::$dbconnect['user'], GlobalConfig::$dbconnect['pswd'], GlobalConfig::$dbconnect['name']);
		
		if ($mysqli->connect_errno) {
			echo "Errore in connessione al DB: ".$mysqli->connect_errno;
			exit();
		}
		
		$mysqli->query("SET NAMES 'utf8'");
		
		$stmt = $mysqli->prepare('SELECT t2.data, GREATEST(0,t2.totale_casi - t1.totale_casi) AS incremento FROM covid19_province t1 INNER JOIN covid19_province t2 ON CAST(t1.data AS DATETIME) =  DATE_ADD(CAST(t2.data AS DATETIME), INTERVAL -1 DAY)  AND t1.codice_provincia = t2.codice_provincia WHERE t1.codice_provincia = ? AND t1.data BETWEEN ? AND ?');
		$stmt->bind_param('sss',$prov,$startdate,$enddate);
		$stmt->execute();
		
		$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);	
		
		return $results;
		
	}
	
	//Recupera la Tabella della Percentuale Positivi su Tamponi effettuati per Regione
	
	public static function getCV19PercTmp($regione){
		$mysqli = new mysqli(GlobalConfig::$dbconnect['host'], GlobalConfig::$dbconnect['user'], GlobalConfig::$dbconnect['pswd'], GlobalConfig::$dbconnect['name']);
		
		if ($mysqli->connect_errno) {
			echo "Errore in connessione al DB: ".$mysqli->connect_errno;
			exit();
		}
		
		$mysqli->query("SET NAMES 'utf8'");
		
		$stmt = $mysqli->prepare('SELECT t2.data, t2.totale_casi, GREATEST(0,t2.tamponi - t1.tamponi) AS tamponi_day, t2.nuovi_positivi, ROUND(t2.nuovi_positivi * 100 / GREATEST(0,t2.tamponi - t1.tamponi),2) AS perc_new FROM covid19_regioni t1 INNER JOIN covid19_regioni t2 ON CAST(t1.data AS DATETIME) =  DATE_ADD(CAST(t2.data AS DATETIME), INTERVAL -1 DAY) AND t1.codice_regione = t2.codice_regione WHERE t1.codice_regione = ? ORDER BY data DESC LIMIT 14;'); 
		$stmt->bind_param('s', $regione);
		$stmt->execute();
		
		$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);	
		
		return $results;
		
	}
	
	//Recupera i Dati sui Vaccini effettuati per Regione
	
	public static function getCV19Vacc($regione){
		$mysqli = new mysqli(GlobalConfig::$dbconnect['host'], GlobalConfig::$dbconnect['user'], GlobalConfig::$dbconnect['pswd'], GlobalConfig::$dbconnect['name']);
		
		if ($mysqli->connect_errno) {
			echo "Errore in connessione al DB: ".$mysqli->connect_errno;
			exit();
		}
		
		$mysqli->query("SET NAMES 'utf8'");
		
		$stmt = $mysqli->prepare('SELECT ultimo_aggiornamento, dosi_somministrate, dosi_consegnate, percentuale_somministrazione, nome_area FROM covid19_vaccini WHERE codice_regione_ISTAT = ?'); 
		$stmt->bind_param('s', $regione);
		$stmt->execute();
		
		$results = $stmt->get_result()->fetch_assoc();
		
		return $results;
		
	}
	
	//Recupera il Totale nuovi casi su Regione e percentuale rispetto al totale (15 giorni)
	
	public static function getCV19SPTotReg01(){
		$mysqli = new mysqli(GlobalConfig::$dbconnect['host'], GlobalConfig::$dbconnect['user'], GlobalConfig::$dbconnect['pswd'], GlobalConfig::$dbconnect['name']);
		
		if ($mysqli->connect_errno) {
			echo "Errore in connessione al DB: ".$mysqli->connect_errno;
			exit();
		}
		
		$mysqli->query("SET NAMES 'utf8'");
		
		$stmt = $mysqli->prepare('CALL SPTotRg01;'); 
		$stmt->execute();
		
		$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);	
		
		return $results;
		
	}
	
	//Recupera il Totale nuovi casi su Provincia e percentuale rispetto al totale (15 giorni)
	
	public static function getCV19SPTotPrv01(){
		$mysqli = new mysqli(GlobalConfig::$dbconnect['host'], GlobalConfig::$dbconnect['user'], GlobalConfig::$dbconnect['pswd'], GlobalConfig::$dbconnect['name']);
		
		if ($mysqli->connect_errno) {
			echo "Errore in connessione al DB: ".$mysqli->connect_errno;
			exit();
		}
		
		$mysqli->query("SET NAMES 'utf8'");
		
		$stmt = $mysqli->prepare('CALL SPTotPrv01;'); 
		$stmt->execute();
		
		$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);	
		
		return $results;
		
	}
	
	//Recupera il Totale nuovi casi Nazionali (ultimi 15 giorni)
	
	public static function getCV19TotNaz01($start,$end){
		$mysqli = new mysqli(GlobalConfig::$dbconnect['host'], GlobalConfig::$dbconnect['user'], GlobalConfig::$dbconnect['pswd'], GlobalConfig::$dbconnect['name']);
		
		if ($mysqli->connect_errno) {
			echo "Errore in connessione al DB: ".$mysqli->connect_errno;
			exit();
		}
		
		$mysqli->query("SET NAMES 'utf8'");
		$stmt = $mysqli->prepare('SELECT SUM(nuovi_positivi) AS new FROM covid19_regioni WHERE DATA BETWEEN ? AND ?'); 
		$stmt->bind_param('ss', $start, $end);
		$stmt->execute();
		
		$results = $stmt->get_result()->fetch_assoc();
		
		return $results['new'];
		
	}
		
}
?>