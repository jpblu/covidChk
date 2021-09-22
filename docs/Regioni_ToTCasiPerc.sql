##SELECT MAX(DATA) INTO @lastdbdate FROM covid19_regioni
##Verifica Dati su Ultimi 15 giorni
SELECT MAX(DATA) INTO @lastdbdate FROM covid19_regioni;

SET @enddate = @lastdbdate;
SET @startdate = DATE_ADD(CAST(@lastdbdate AS DATE), INTERVAL -14 DAY);

##Totale nuovi casi su Regione e percentuale rispetto al totale (15 giorni)
SELECT SUM(nuovi_positivi) INTO @tot FROM covid19_regioni WHERE DATA BETWEEN @startdate AND @enddate;
SELECT @tot AS 'Totale Nuovi Positivi';

SELECT ROW_NUMBER() OVER (ORDER BY SUM(nuovi_positivi) DESC) AS n, denominazione_regione, SUM(nuovi_positivi) AS tot_positivi, ROUND((SUM(nuovi_positivi) * 100 / @tot),2) AS '% su Tot' FROM covid19_regioni 
WHERE DATA BETWEEN @startdate AND @enddate
GROUP BY denominazione_regione
ORDER BY 3 DESC;

##Totale nuovi casi su Provincia e percentuale rispetto al totale (15 giorni)
##SELECT SUM(nuovi_positivi) INTO @tot FROM covid19_regioni WHERE DATA BETWEEN @startdate AND @enddate;
SELECT MAX(DATA) INTO @lastdbdate FROM covid19_regioni;

SELECT ROW_NUMBER() OVER (ORDER BY lastday.totale_casi - firstday.totale_casi DESC) AS n, firstday.denominazione_provincia, firstday.denominazione_regione, lastday.totale_casi - firstday.totale_casi AS incremento, ROUND(((lastday.totale_casi - firstday.totale_casi) * 100 / @tot),2) AS '% su Tot'  FROM 
(SELECT * FROM covid19_province WHERE DATA = @startdate AND codice_provincia < 800) AS firstday
INNER JOIN (SELECT * FROM covid19_province WHERE DATA = @enddate AND codice_provincia < 800) AS lastday
ON firstday.codice_provincia = lastday.codice_provincia
ORDER BY lastday.totale_casi - firstday.totale_casi DESC;
