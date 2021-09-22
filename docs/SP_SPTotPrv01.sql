/**
 * Office Manager
 * Copyright © 2019, Andrea Fusco (http://www.andreafusco.net)
 *
 * Licensed under Creative Commons By-Nc-Nd
 * Redistributions of files must retain the above copyright notice.
 *
 * @Copyright     Copyright © 2019, Andrea Fusco (http://www.andreafusco.net)
 * @License       Creative Commons By-Nc-Nd (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @Description	Stored Procedure Stat STM16
 * @Version		  	1.0.0
 * @Created		 	2020-01-29
 * @Example			CALL SPTotPrv01;
 */

DROP PROCEDURE IF EXISTS SPTotPrv01;

DELIMITER $$

CREATE PROCEDURE SPTotPrv01()
BEGIN

	##SELECT MAX(DATA) INTO @lastdbdate FROM covid19_regioni
	##Verifica Dati su Ultimi 15 giorni
	SET @lastdbdate = (SELECT MAX(DATA) FROM covid19_regioni);
	
	SET @enddate = @lastdbdate;
	SET @startdate = DATE_ADD(CAST(@lastdbdate AS DATE), INTERVAL -14 DAY);
	
	##Totale nuovi casi su Provincia e percentuale rispetto al totale (15 giorni)
	SET @tot = (SELECT SUM(nuovi_positivi) FROM covid19_regioni WHERE DATA BETWEEN @startdate AND @enddate);
	
	SELECT ROW_NUMBER() OVER (ORDER BY lastday.totale_casi - firstday.totale_casi DESC) AS n, firstday.denominazione_provincia, firstday.denominazione_regione, lastday.totale_casi - firstday.totale_casi AS incremento, ROUND(((lastday.totale_casi - firstday.totale_casi) * 100 / @tot),2) AS percentualetot FROM 
	(SELECT * FROM covid19_province WHERE DATA = @startdate AND codice_provincia < 800) AS firstday
	INNER JOIN (SELECT * FROM covid19_province WHERE DATA = @enddate AND codice_provincia < 800) AS lastday
	ON firstday.codice_provincia = lastday.codice_provincia
	ORDER BY lastday.totale_casi - firstday.totale_casi DESC;

END$$

DELIMITER ;