/**
 * Covid-19 CSV Data Loader
 * Copyright © 2020, Andrea Fusco
 *
 * Licensed under Creative Commons By-Nc-Nd
 * Redistributions of files must retain the above copyright notice.
 *
 * @Copyright     	Copyright © 2020, Andrea Fusco
 * @License       	Creative Commons By-Nc-Nd (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @File		  	servercheck.js
 * @Description	  	Libreria JS
 * @Version		  	1.0.0
 * @Date		  	2018-07-13
*/
 
$(document).ready(function() {
	
	$.ajax({
		type: 'GET',
		url: 'lib/serverlist_toajax.php',
		success: function(data) {
			serverlist = jQuery.parseJSON(data);
			var siperton = 0;
			var sipertoff = 0;
			
			//Per ciascun server effettuo il Ping e verifico sia online
			for(var k in serverlist) {
				
			   //console.log("Pre-Ajax: "+serverlist[k]);
			   $.ajax({
					type: "POST",
					url: "lib/ping.php",
					data: { hostname: serverlist[k] }
				})
				.done(function(data) {
					result = jQuery.parseJSON(data);
					if (result.code == 'Online') { 
						$('.ping_result_'+result.host).addClass('text-success');
						siperton++;
						$("#siperton").html("Online "+siperton);
					} else { 
						$('.ping_result_'+result.host).addClass('text-danger');
						sipertoff++;
						$("#sipertoff").html("Offline "+sipertoff);
					}
					$('.ping_result_'+result.host).html(result.code);
				})
				
			}			
			
		}
		
	})
	
	$.ajax({
		type: 'GET',
		url: 'lib/serverlist2_toajax.php',
		success: function(data) {
			serverlist = jQuery.parseJSON(data);
			var cezanneon = 0;
			var cezanneoff = 0;
			
			//Per ciascun server effettuo il Ping e verifico sia online
			for(var k in serverlist) {
				
			   //console.log("Pre-Ajax: "+serverlist[k]);
			   $.ajax({
					type: "POST",
					url: "lib/ping.php",
					data: { hostname: serverlist[k] }
				})
				.done(function(data) {
					result = jQuery.parseJSON(data);
					if (result.code == 'Online') { 
						$('.ping_result_'+result.host).addClass('text-success'); 
						cezanneon++;
						$("#cezanneon").html("Online "+cezanneon);
					} else { 
						$('.ping_result_'+result.host).addClass('text-danger'); 
						cezanneoff++;
						$("#cezanneoff").html("Offline "+cezanneoff);
					}
					$('.ping_result_'+result.host).html(result.code);
				})
				
			}
							
		}
		
	})
	
	$.ajax({
		type: 'GET',
		url: 'lib/serverlist3_toajax.php',
		success: function(data) {
			serverlist = jQuery.parseJSON(data);
			var talentiaon = 0;
			var talentiaoff = 0;
			
			//Per ciascun server effettuo il Ping e verifico sia online
			for(var k in serverlist) {
				
			   //console.log("Pre-Ajax: "+serverlist[k]);
			   $.ajax({
					type: "POST",
					url: "lib/ping.php",
					data: { hostname: serverlist[k] }
				})
				.done(function(data) {
					result = jQuery.parseJSON(data);
					if (result.code == 'Online') { 
						$('.ping_result_'+result.host).addClass('text-success'); 
						talentiaon++;
						$("#talentiaon").html("Online "+talentiaon);
					} else { 
						$('.ping_result_'+result.host).addClass('text-danger'); 
						talentiaoff++;
						$("#talentiaoff").html("Offline "+talentiaoff);
					}
					$('.ping_result_'+result.host).html(result.code);
				})
				
			}
							
		}
		
	})
	
	$("#page_refresh").on("click",function() {
		window.location.reload();
	});
	
});