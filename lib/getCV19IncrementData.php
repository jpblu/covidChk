<?php
/**
 * Covid-19 CSV Data Loader
 * Copyright © 2020, Andrea Fusco
 *
 * Licensed by Andrea Fusco
 * Redistributions of files must retain the above copyright notice.
 *
 * @Copyright     	Copyright © 2020, Andrea Fusco
 * @License       	Andrea Fusco
 * @File		  	getCV19IncrementData.php
 * @Description	  	CV-19 Monitor Launch
 * @Version		  	1.2.0
 * @Created		  	2020-03-24
 * @Updated		  	2022-01-28
 */
 
include('config.php');
include('sstat.php');

if (isset($_POST['prov']) && isset($_POST['startdate']) && isset($_POST['enddate'])) {
	//Parametri OK
	$results =  SStats::getCV19Increment($_POST['prov'],$_POST['startdate'],$_POST['enddate']);
	
	echo json_encode($results);
	
} else {
	echo "403 Forbidden";
}
 
 ?>