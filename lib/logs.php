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
 * @File		  	logs.php
 * @Description	  	ServerStatusLib
 * @Version		  	1.1.0
 * @Created		  	2021-09-22
 */


class Logs { 

	//Scrive una stringa nel file di log indicando l'orario 

		public static function writeBatchLog($filepath, $text) {
			$log = fopen($filepath, 'a+');
			$head = date("Y-m-d H:i:s");
			$record = $head." -> ".$text." ".PHP_EOL;
			fwrite($log, $record);
			fclose($log);
		}
	
}
	
?>