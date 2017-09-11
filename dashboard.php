<?php
	// TD: need to include the session
	//include("ps_valid_session.php");
	include("ps_config_session.php");
	session_start();

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
		<script src="lib/bootbox.min.js"></script>
		<script src="js/ps_doughnut.js"></script>
		<script src="js/ps_company_data.js"></script>
	</head>
	<body style="font-family:Cambria">
		<?php
			include("ps_main_header.php");
		?>
		<div class="row rowClass">
			<div class="col-md-10 col-md-offset-1" id="div_dashboardPanel">
				<div class="panel panel-primary" style="box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.15);">
					<div class="panel-heading">
                    	<h3 id='panelTitle' class="panel-title">Dashboard</h3>
                    	<!--<h6 class="spn-date-class" id="span_dateText"></h6>-->
                	</div>
                	<div style='margin: 15px' align='center'>
						<form role="form">
							<div class="row" id="div_dashboardOptions">
								<div class="col-md-3" id="divCountry">
									<select id="selCountry" class="form-control selectDBClass" required>
										<option value="">Select Country</option>
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
									<select id="selCompany" class="form-control selectDBClass" required>
										<option value="" >Select Country First</option>
									</select>
								</div>
								<div class="col-md-3" id="div_Submit">
									<input type="button" id="btnDoughnutButton" class="btn btn-primary" value="GO"</input>
									<!--<span id="btnDoughnutRefreshButton" class="btn btn-lg"><span class="glyphicon glyphicon-refresh" style="font-size: 24px; color: green"></span>Refresh</span>-->
									 <button id="btnDoughnutRefreshButton" type="button" style="display:none" class="btn btn-success">
							          	<span class="glyphicon glyphicon-refresh"></span> Refresh
							        </button>							        
								</div>	

							</div>
						</form>
					</div>
					<div class="noDataDisplay">
						<div style="margin: 1%; font-size: 14px">
							<!--<div class="row" id="div_row_description">
								<div class="col-md-4 bg-info div-empty-content">
									<h3>Simple</h3>
								</div>
								<div class="col-md-4 bg-warning div-empty-content">
									<h3>Effective</h3>
								</div>
								<div class="col-md-4 bg-success div-empty-content">
									<h3>Useful</h3>
								</div>
							</div>-->
							<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
							    <!--<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>-->
							    <strong>What it does?</strong> <br/>Parking space tries to provide a set up which can be utilized to register the parking space, so that it can be updated when booked and emptied.
							</div>

							<div class="alert alert-info fade in alert-dismissable">
							    <!--<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>-->
							    <strong>How to begin?</strong><br/>If you are a user who'd like to check if a parking space is available in one of the <b>reistered</b> buildings, go straight ahead, select country, company and hit Go!!
							</div>

							<div class="alert alert-warning fade in alert-dismissable">
							    <!--<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>-->
							    <strong>How do I register my company?</strong><br/> We'll be up shortly with an option to register as an admin so that you can set up your own parking circle.
							</div>
						</div>
					</div>
                	<div id="div_mainTab" style="display: none">
						<ul class="nav nav-tabs">
							<li class="active">
								<a  href="#1" data-toggle="tab">Common</a>
							</li>
							<li><a href="#2" data-toggle="tab">Female</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane tab-content-pane-class active" id="1">
								<span>
									<div style='width:"50%", height:"50%"' id="psParkingCanvasDiv">
										<canvas id="psParkingCanvas">
										</canvas>
										<div id="doughnutDataTable">
					 					</div>
									</div>
								</span>
							</div>
							<div class="tab-pane tab-content-pane-class" id="2">
								<span>
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