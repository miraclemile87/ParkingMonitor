<?php
	// TD: need to include the session
	include("ps_valid_session.php");
	include("ps_config_session.php");

	//$colorArray = array("#337ab7","#f0ad4e","#5bc0de");

	//#76C175
	//$colorFGArray = array(#3c763d);
	$colorArrayIterator=0;

	
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Parking Dashboard
		</title>
		<script src="lib/Chart.min.js"></script>
		<script src="lib/jquery-1.12.0.min.js"></script>
		<script src="js/msdropdown/jquery.dd.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="css/msdropdown/dd.css" />
		<link rel="stylesheet" type="text/css" href="css/msdropdown/skin2.css" />
		<link rel="stylesheet" type="text/css" href="css/msdropdown/flags.css" />
		<link rel="stylesheet" href="lib/bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="lib/bootstrap-3.3.6-dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/dashboard.css">
		<link rel="stylesheet" href="css/main.css">
		<script src="lib/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
		<script src="js/ps_doughnut.js"></script>
	</head>
	<body>
		<?php
			include("ps_main_header.php");
		?>
		<div class="row">
			<div class="col-md-10 col-md-offset-1" id="div_dashboardPanel">
				<div class="panel panel-primary" style="box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.15);">
					<div class="panel-heading">
                    	<h3 class="panel-title">Dashboard</h3>
                	</div>
					<div style="margin:auto" id="div_dashboardOptions">
						<?php
							include("ps_country_dropdown.php");
						?>
					</div>
					<br/>
					<br/>
					<?php
						include("psDoughnutData.php");
					?>
                	<div id="div_mainTab">
						<ul class="nav nav-tabs">
							<li class="active">
								<a  href="#1" data-toggle="tab">Common</a>
							</li>
							<li><a href="#2" data-toggle="tab">Female</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane active tab-content-pane-class" id="1">
								<span>
									<!--<?php echo $jsonData; ?>-->
									<div style='width:"50%", height:"50%"' id="psParkingCanvasDiv">
										<canvas id="psParkingCanvas">
										</canvas>
									</div>
								</span>
							</div>
							<div class="tab-pane" id="2">
								<h3>Notice the gap between the content and tab after applying a background color</h3>
							</div>
						</div>
					 </div>
					 <div id="doughnutDataTable">
					 </div>
				</div>
			</div>
		</div>
		<div id='tempDiv'>
		</div>
	</body>
</html>