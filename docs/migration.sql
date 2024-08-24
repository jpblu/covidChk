-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Ago 24, 2024 alle 07:56
-- Versione del server: 10.6.18-MariaDB
-- Versione PHP: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `covidchk`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `covid19_province`
--

CREATE TABLE `covid19_province` (
  `data` varchar(20) NOT NULL,
  `stato` varchar(3) NOT NULL,
  `codice_regione` varchar(2) NOT NULL,
  `denominazione_regione` varchar(21) DEFAULT NULL,
  `codice_provincia` varchar(3) NOT NULL,
  `denominazione_provincia` varchar(36) DEFAULT NULL,
  `sigla_provincia` varchar(2) DEFAULT NULL,
  `lat` varchar(100) DEFAULT NULL,
  `lng` varchar(100) DEFAULT NULL,
  `totale_casi` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `covid19_regioni`
--

CREATE TABLE `covid19_regioni` (
  `data` varchar(20) NOT NULL,
  `stato` varchar(3) NOT NULL,
  `codice_regione` varchar(2) NOT NULL,
  `denominazione_regione` varchar(21) DEFAULT NULL,
  `lat` varchar(100) DEFAULT NULL,
  `lng` varchar(100) DEFAULT NULL,
  `ricoverati_con_sintomi` int(5) DEFAULT NULL,
  `terapia_intensiva` int(4) DEFAULT NULL,
  `totale_ospedalizzati` int(5) DEFAULT NULL,
  `isolamento_domiciliare` int(5) DEFAULT NULL,
  `totale_positivi` int(5) DEFAULT NULL,
  `variazione_totale_positivi` int(5) DEFAULT NULL,
  `nuovi_positivi` int(4) DEFAULT NULL,
  `dimessi_guariti` int(5) DEFAULT NULL,
  `deceduti` int(5) DEFAULT NULL,
  `casi_da_sospetto_diagnostico` text DEFAULT NULL,
  `casi_da_screening` text DEFAULT NULL,
  `totale_casi` int(6) DEFAULT NULL,
  `tamponi` int(7) DEFAULT NULL,
  `casi_testati` text DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `covid19_vaccini`
--

CREATE TABLE `covid19_vaccini` (
  `area` varchar(3) NOT NULL,
  `dosi_somministrate` int(7) DEFAULT NULL,
  `dosi_consegnate` int(7) DEFAULT NULL,
  `percentuale_somministrazione` decimal(3,1) DEFAULT NULL,
  `ultimo_aggiornamento` varchar(10) DEFAULT NULL,
  `codice_NUTS1` varchar(3) DEFAULT NULL,
  `codice_NUTS2` varchar(4) DEFAULT NULL,
  `codice_regione_ISTAT` int(2) DEFAULT NULL,
  `nome_area` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `covid19_province`
--
ALTER TABLE `covid19_province`
  ADD PRIMARY KEY (`data`,`stato`,`codice_regione`,`codice_provincia`);

--
-- Indici per le tabelle `covid19_regioni`
--
ALTER TABLE `covid19_regioni`
  ADD PRIMARY KEY (`data`,`stato`,`codice_regione`);

--
-- Indici per le tabelle `covid19_vaccini`
--
ALTER TABLE `covid19_vaccini`
  ADD PRIMARY KEY (`area`);
COMMIT;
