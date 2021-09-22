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
 * @File		  	getCV19MonitorData.php
 * @Description	  	CV-19 Monitor Launch
 * @Version		  	1.0.0
 * @Created		  	20120-03-20
 */
 
include('config.php');
include('sstat.php');

if (isset($_POST['prov'])) {
	//Parametri OK
	echo SStats::getCV19Monitor($_POST['prov']);
	
} else {
	echo "403 Forbidden";
}
 
 ?>