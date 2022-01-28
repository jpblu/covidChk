<!doctype html>
<html>
<head>

	<?	include('lib/config.php');
		include('lib/sstat.php');
	?>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="PN Server Status">
	<meta name="author" content="CR00753">

	<title>COVID-19 CSV Data Loader</title>

	<!-- Bootstrap core CSS -->
	<link href="css/bootstrap.min.css?v=461" rel="stylesheet">

	<!-- Font-Awesome Icons -->
	<link href="css/fontawesome.css" rel="stylesheet">

	<!-- JQuery Core -->
	<script src="js/jquery-3.6.0.min.js"></script>

	<!-- Bootstrap core JavaScript -->
	<script src="js/bootstrap.min.js?v=461"></script>
	
	<!-- Custom styles for this template -->
	<link href="css/offcanvas.css" rel="stylesheet">
	<script src="js/offcanvas.js"></script>
	<link href="css/datatables.min.css" rel="stylesheet">
	<script src="js/datatables.min.js"></script>
	<script src="js/cv19lists.js?v=110"></script>

</head>

<body class="bg-light">

	<nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
	
		<div class="collapse navbar-collapse" id="navbarsExampleDefault">
	
			<a class="navbar-brand" href="#">Covid-19 CSV Data Loader</a>
			<a class="btn btn-primary my-2 my-sm-0 ml-auto mr-2" href="index.php">Grafici di Incremento per Provincia</a>
			
		</div>
		
	</nav>

	<main role="main" class="container">
	
		<? $start = date('Y-m-d', strtotime('-14 day', time()));
		   $end = date('Y-m-d');
		   $newtot = SStats::getCV19TotNaz01($start,$end); ?>
	
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Totale Incremento Positivi dal <?=$start; ?> al <?=$end; ?>: <b><? echo number_format($newtot,0,'.','.'); ?></b></li>
			</ol>
		</nav>
	
		<div class="row mt-3 mb-3">
		
			<?	$reglist = SStats::getCV19SPTotReg01(); ?>
					
			<div class="col-12 mb-2">
			
			    <div class="card">
			        
			        <div class="card-header">        				
        				<div class="font-weight-bold">Totale nuovi casi su Regione e percentuale rispetto al totale (ultimi 15 giorni)</div>
			        </div>
				
					<div class="card-body">
					
						<table class="table table-bordered datatable">
							<thead>
								<tr>
									<th>N</th>
									<th>Regione</th>
									<th>Tot Positivi</th>
									<th>% su Totale Nazionale</th>
								</tr>
							</thead>
							<tbody>
						<? 	foreach($reglist as $key => $value) {	?>
								<tr>
									<td><?=$reglist[$key]['n']; ?></td>
									<td><?=$reglist[$key]['denominazione_regione']; ?></td>
									<td><? echo number_format($reglist[$key]['tot_positivi'],0,'.','.'); ?></td>
									<td><?=$reglist[$key]['percentualetot']; ?> %</td>
								</tr>
						<?	}	?>
							</tbody>
						</table>

					</div>
					
				</div>
				
			</div>
			
			<div class="col-12">
			
				<div class="card">
				
					<?	$provlist = SStats::getCV19SPTotPrv01(); ?>
			        
			        <div class="card-header">
        				<div class="font-weight-bold">Totale nuovi casi su Provincia e percentuale rispetto al totale (ultimi 15 giorni)</div>
			        </div>
				
					<div class="card-body">
					
						<table class="table table-bordered datatable">
							<thead>
								<tr>
									<th>N</th>
									<th>Provincia</th>
									<th>Regione</th>
									<th>Tot Positivi</th>
									<th>% su Totale Nazionale</th>
								</tr>
							</thead>
							<tbody>
						<? 	foreach($provlist as $key => $value) {	?>
								<tr>
									<td><?=$provlist[$key]['n']; ?></td>
									<td><?=$provlist[$key]['denominazione_provincia']; ?></td>
									<td><?=$provlist[$key]['denominazione_regione']; ?></td>
									<td><? echo number_format($provlist[$key]['incremento'],0,'.','.'); ?></td>
									<td><?=$provlist[$key]['percentualetot']; ?> %</td>
								</tr>
						<?	}	?>
							</tbody>
						</table>

					</div>
					
				</div>
			
			</div>
			
		</div>
		
	</main>
	
	<footer class="w-100 text-center my-3">Dati ufficiali forniti dal <a href="https://github.com/pcm-dpc/COVID-19">Dipartimento della Protezione Civile</a> &copy; 2020 AndreaFusco.net <br><small>Aggiornamento Giornaliero alle 18:40</small></footer>
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-3022323-6"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-3022323-6');
	</script>


</body>
</html>
