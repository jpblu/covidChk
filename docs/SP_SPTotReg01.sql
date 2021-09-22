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
 * @Example			CALL SPTotRg01;
 */

DROP PROCEDURE IF EXISTS SPTotRg01;

DELIMITER $$

CREATE PROCEDURE SPTotRg01()
BEGIN

	##SELECT MAX(DATA) INTO @lastdbdate FROM covid19_regioni
	##Verifica Dati su Ultimi 15 giorni
	SET @lastdbdate = (SELECT MAX(DATA) FROM covid19_regioni);
	
	SET @enddate = @lastdbdate;
	SET @startdate = DATE_ADD(CAST(@lastdbdate AS DATE), INTERVAL -14 DAY);
	
	##Totale nuovi casi su Regione e percentuale rispetto al totale (15 giorni)
	SET @tot = (SELECT SUM(nuovi_positivi) FROM covid19_regioni WHERE DATA BETWEEN @startdate AND @enddate);
	
	SELECT ROW_NUMBER() OVER (ORDER BY SUM(nuovi_positivi) DESC) AS n, denominazione_regione, SUM(nuovi_positivi) AS tot_positivi, ROUND((SUM(nuovi_positivi) * 100 / @tot),2) AS percentualetot FROM covid19_regioni 
	WHERE DATA BETWEEN @startdate AND @enddate
	GROUP BY denominazione_regione
	ORDER BY 3 DESC;

END$$

DELIMITER ;