/**
 * Covid-19 CSV Data Loader
 * Copyright © 2020, Andrea Fusco
 *
 * Licensed under Creative Commons By-Nc-Nd
 * Redistributions of files must retain the above copyright notice.
 *
 * @Copyright     	Copyright © 2020, Andrea Fusco
 * @License       	Creative Commons By-Nc-Nd (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @File		  	lists.js
 * @Description	  	CV-19 Monitor
 * @Version		  	1.0.2
 * @Created		  	2020-10-29
 */
 
$(document).ready(function() {
	
	//Carico le DataTable
    $('.datatable').DataTable({
		"lengthMenu": [ 10, 25, 50, 75 ],
		"bSort": false,
		"oLanguage": {
			"sEmptyTable":     "Nessun dato presente nella tabella",
			"sInfo":           "Vista da _START_ a _END_ di _TOTAL_ elementi",
			"sInfoEmpty":      "Vista da 0 a 0 di 0 elementi",
			"sInfoFiltered":   "(filtrati da _MAX_ elementi totali)",
			"sInfoPostFix":    "",
			"sInfoThousands":  ".",
			"sLengthMenu":     "Visualizza _MENU_ elementi",
			"sLoadingRecords": "Caricamento...",
			"sProcessing":     "Elaborazione...",
			"sSearch":         "Cerca",
			"sZeroRecords":    "La ricerca non ha portato alcun risultato.",
			"oPaginate": {
				"sFirst":      "Inizio",
				"sPrevious":   "Precedente",
				"sNext":       "Successivo",
				"sLast":       "Fine"
			},
			"oAria": {
				"sSortAscending":  ": attiva per ordinare la colonna in ordine crescente",
				"sSortDescending": ": attiva per ordinare la colonna in ordine decrescente"
			}
		}
	});

});
