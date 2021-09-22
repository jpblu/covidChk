/**
 * Covid-19 CSV Data Loader
 * Copyright © 2020, Andrea Fusco
 *
 * Licensed under Creative Commons By-Nc-Nd
 * Redistributions of files must retain the above copyright notice.
 *
 * @Copyright     	Copyright © 2020, Andrea Fusco
 * @License       	Creative Commons By-Nc-Nd (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @File		  	cv19Monitor.js
 * @Description	  	CV-19 Monitor
 * @Version		  	1.1.0
 * @Created		  	2020-03-24
 * @Updated		  	2021-05-24
 */
 
$(document).ready(function() {
	
	var stcodprov = $("#codprov").val();
	getCV19Monitor(stcodprov);
	getCV19Increment(stcodprov);
	getCV193DayInc(stcodprov);
	
	//Index - Last X Days Download Graph - Incremento Totale per Provincia
	function getCV19Monitor(prov) { 
		$.ajax({
			type:  'POST',
			url:   'lib/getCV19MonitorData.php',
			data: { prov : prov }
		})
		.done(function(json)	{				
			var jobj = JSON.parse(json);
			
			jlabel = [];
			jdata = [];
			
			//console.log(jobj);
			
			$.each(jobj, function(key,value) {
				jlabel.push(value.data);
				jdata.push(value.totale_casi);
			});				
							
			var ctx = document.getElementById("XDaysChart");
			var XDaysChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: jlabel,
					datasets: [{
						data: jdata,
						lineTension: 0,
						backgroundColor: 'transparent',
						borderColor: '#007bff',
						borderWidth: 2,
						pointBackgroundColor: '#007bff'
					}]
				},
				options: {
					plugins: {
						title: {
							display: true,
							text: 'Incremento Totale Nuove Infezioni Covid-19 per Provincia (dal 24/2)'
						},
						legend: {
							display: false,
						}
					},
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: false
							}
						}]
					}					
				}
			});

		});
	}
	
	//Index - Last X Increment Download Graph - Andamento Giornaliero per Provincia
	function getCV19Increment(prov) { 
		$.ajax({
			type:  'POST',
			url:   'lib/getCV19IncrementData.php',
			data: { prov : prov }
		})
		.done(function(json)	{				
			var jobj = JSON.parse(json);
			
			jlabel = [];
			jdata = [];
			
			//console.log(jobj);
			
			$.each(jobj, function(key,value) {
				jlabel.push(value.data);
				jdata.push(value.incremento);
			});				
							
			var ctx = document.getElementById("XIncrementChart");
			var XIncrementChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: jlabel,
					datasets: [{
						data: jdata,
						backgroundColor: '#FFC300',
						borderColor: '#FFC300',
						borderWidth: 2,
						pointBackgroundColor: '#007bff'
					}]
				},
				options: {
					plugins: {						
						title: {
							display: true,
							text: 'Andamento Giornaliero Nuove Infezioni Covid-19 per Provincia (dal 24/2)'
						},
						legend: {
							display: false,
						}
					},
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: false
							}
						}]
					}					
				}
			});

		});
	}
	
	//Index - Last X 3Day Increment Download Graph - Andamento 3 Giorni per Provincia
	function getCV193DayInc(prov) { 
		$.ajax({
			type:  'POST',
			url:   'lib/getCV19Inc3DayData.php',
			data: { prov : prov }
		})
		.done(function(json)	{				
			var jobj = JSON.parse(json);
			
			jlabel = [];
			jdata = [];
			
			//console.log(jobj);
			
			$.each(jobj, function(key,value) {
				jlabel.push(value.data);
				jdata.push(value.dayincr);
			});				
							
			var ctx = document.getElementById("XIncrement3DayChart");
			var XIncrementChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: jlabel,
					datasets: [{
						data: jdata,
						lineTension: 0,
						backgroundColor: 'transparent',
						borderColor: '#E74C3C',
						borderWidth: 2,
						pointBackgroundColor: '#E74C3C'
					}]
				},
				options: {
					plugins: {
						title: {
							display: true,
							text: 'Andamento Medio su 3 giorni Nuove Infezioni Covid-19 per Provincia (dal 24/2)'
						},
						legend: {
							display: false,
						}
					},
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: false
							}
						}]
					},					
				}
			});								

		});
	}
	
	$("#page_refresh").on("click",function() {
		window.location.reload();
	});
	
	$("#codprov").on("change", function() {
		var codprov = $("#codprov").val();
		window.location.href='index.php?prov='+codprov
	});

});
