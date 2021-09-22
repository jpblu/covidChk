<?php
/**
 * Covid-19 CSV Data Loader
 * Copyright © 2018, Andrea Fusco (CR00753)
 *
 * Licensed by Cedacri S.p.a.
 * Redistributions of files must retain the above copyright notice.
 *
 * @Copyright     	Copyright © 2018, Andrea Fusco (CR00753)
 * @License       	Cedacri S.p.a.
 * @File		  	getCV19IncrementData.php
 * @Description	  	CV-19 Monitor Launch
 * @Version		  	1.0.0
 * @Created		  	20120-03-24
 */
 
include('config.php');
include('sstat.php');

if (isset($_POST['prov'])) {
	//Parametri OK
	$results =  SStats::getCV19Increment($_POST['prov']);
	
	echo json_encode($results);
	
} else {
	echo "403 Forbidden";
}
 
 ?>