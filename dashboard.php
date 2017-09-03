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
		<script src="js/ps_company_data.js"></script>
	</head>
	<body style="font-family:Cambria">
		<?php
			include("ps_main_header.php");
		?>
		<div class="row">
			<div class="col-md-10 col-md-offset-1" id="div_dashboardPanel">
				<div class="panel panel-primary" style="box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.15);">
					<div class="panel-heading">
                    	<h3 class="panel-title">Dashboard</h3>
                	</div>
                	<div style='margin: 15px' align='center'>
						<form role="form">
							<div class="row" id="div_dashboardOptions">
								<div class="col-md-3" id="divCountry">
									<select id="selCountry" class="form-control">
										<option>Select Country</option>
										<?php				
											$qry_cntry='select id value, name text from ps_countries';
											$addDetailsDBRReturnResult = mysql_query( $qry_cntry, $conn );

											if(! $addDetailsDBRReturnResult ) {
												die('Could not select grid details: ' . mysql_error());
											}

											if(mysql_num_rows($addDetailsDBRReturnResult) == 0){
												die('Something went wrong : ' . mysql_error());
											}
											
											while($addDetailsDBRow = mysql_fetch_assoc($addDetailsDBRReturnResult)){
												?>
												<option value="<?php echo $addDetailsDBRow['value']; ?>"><?php echo $addDetailsDBRow['text']; ?></option>
												<?php
											}
										?>
									</select>
								</div>
								<div class="col-md-3 form-group" id="div_Company">
									<select id="selParking" class="form-control">
										<option>Select Country First</option>
									</select>
								</div>
								<div class="col-md-3" id="div_Submit">
									<input type="button" id="btnDoughnutButton" class="btn btn-primary btnSimple" value="GO"</input>
								</div>								
							</div>
						</form>
					</div>
					<?php
						//include("psDoughnutData.php");
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
										<div id="doughnutDataTable">
					 					</div>
									</div>
								</span>
							</div>
							<div class="tab-pane" id="2">
								<span>
									<!--<?php echo $jsonData; ?>-->
									<div style='width:"50%", height:"50%"' id="psParkingCanvasFDiv">
										<canvas id="psParkingCanvasF">
										</canvas>
										<div id="doughnutDataTableF">
										</div>
									</div>
								</span>
							</div>
						</div>
					 </div>
					 <!--<div id="doughnutDataTable">
					 </div>-->
				</div>
			</div>
		</div>
		<div id='tempDiv'>
		</div>
	</body>
</html>