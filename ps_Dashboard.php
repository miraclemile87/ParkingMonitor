<?php
	// TD: need to include the session
	include("ps_valid_session.php");
	include("ps_config_session.php");

	$colorArray = array("#337ab7","#f0ad4e","#5bc0de");
	//$colorFGArray = array(#3c763d);
	$colorArrayIterator=0;

	$parkingDoughnutBldgInfo = array();
	$parkingDoughnutCommonParking = array();
	$parkingDoughnutFemaleParking = array();
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Parking Dashboard
		</title>
		<script src="lib/Chart.min.js"></script>
		<script src="lib/jquery-1.12.0.min.js"></script>
		<link rel="stylesheet" href="lib/bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="lib/bootstrap-3.3.6-dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/dashboard.css">
		<script src="lib/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
		<script src="js/psDetail.js"></script>
	</head>
	<body>
		<?php
			include("ps_main_header.php");
		?>
		<!--
			TD: Need to add the parking space grid id manually
		-->
		<h3 id="h1_loginHeader">
			DASHBOARD
		</h3>
		<div id="div_vieWdetails">
			<?php
				$fetchDBQuery = "SELECT `BUILDING_NO`, `COMPANY_NAME`, CONCAT(`COMPANY_CITY` ,', ', `COMPANY_STATE` , ', ' , `COMPANY_COUNTRY`) COMPANY_LOCATION, `COMPANY_LANDMARK`, ifnull(`BUILDING_PARKING_SLOTS_C`,0) - ifnull(`PARKING_COUNT_C`, 0) COMMON_PARKINGS, ifnull(`BUILDING_PARKING_SLOTS_F`, 0) - ifnull(`PARKING_COUNT_F`,0) FEMALE_PARKINGS FROM `ps_parkingspace_buildings` building join ps_parkingspace_companies_buildings comp_bldg on comp_bldg.BUILDING_ID = building.BUILDING_ID join ps_parkingspace_companies company on company.COMPANY_ID = comp_bldg.COMPANY_ID LEFT OUTER join ps_parkingspace_building_parkings current on building.BUILDING_ID = current.BUILDING_ID and current.PARKING_BUSINESS_DATE = date(now()) and ((building.START_DATE >= date(now()) and building.END_DATE is null )or (date(now()) between building.start_date and building.END_DATE)) order by BUILDING_NO";

				$dbReturnResult = mysql_query($fetchDBQuery, $conn);

				if(!$dbReturnResult){
					die("Something went wrong");
				}

				while($dbReturnResultRow = mysql_fetch_assoc($dbReturnResult)){
					//$parkingDoughnutBldgInfo.push("bldgNo" => $dbReturnResultRow["BUILDING_NO"]);
					//$parkingDoughnutCommonParking.push("avaliableSlots" => $dbReturnResultRow["COMMON_PARKINGS"]);
					//$parkingDoughnutFemaleParking.push("avaliableSlots" => #dbReturnResultRow["FEMALE_PARKINGS"]);
					?>
					<canvas id="parkingCanvas_<?php echo $dbReturnResultRow["BUILDING_NO"]; ?>" width="400" height="400"></canvas>
					<script>
						$(document).ready(function(){
							var parkingCanvasContext = document.getElementById("parkingCanvas_<?php echo $dbReturnResultRow['BUILDING_NO']; ?>").getContext("2d");
							var parkingData = "<?php echo $dbReturnResultRow["COMMON_PARKINGS"]; ?>, <?php echo $dbReturnResultRow["FEMALE_PARKINGS"]; ?>";
							var psChart = new Chart(parkingCanvasContext,{
							    type: 'doughnut',
							    data : {
								    datasets: [{
								        data: [parkingData]
								    }],
								    labels: [
								        'COMMON',
								        'FEMALE'
								    ]
								}
							});
						});
					</script>
					<?php
						if($colorArrayIterator == (sizeof($colorArray)-1))
							$colorArrayIterator=0;
						else
							$colorArrayIterator=$colorArrayIterator+1;
					}
				?>
			</div>		
		</div>
		
		<div id='tempDiv'>
		</div>
	</body>
</html>