<?php
	// TD: need to include the session
	include("ps_valid_session.php");
	include("ps_config_session.php");

	$colorArray = array("#337ab7","#f0ad4e","#5bc0de");
	//$colorFGArray = array(#3c763d);
	$colorArrayIterator=0;
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Parking Dashboard
		</title>
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
			<div class="row rowClass">
			<?php
				$fetchDBQuery = "SELECT `BUILDING_NO`, `COMPANY_NAME`, CONCAT(`COMPANY_CITY` ,', ', `COMPANY_STATE` , ', ' , `COMPANY_COUNTRY`) COMPANY_LOCATION, `COMPANY_LANDMARK`, ifnull(`BUILDING_PARKING_SLOTS_C`,0) - ifnull(`PARKING_COUNT_C`, 0) COMMON_PARKINGS, ifnull(`BUILDING_PARKING_SLOTS_F`, 0) - ifnull(`PARKING_COUNT_F`,0) FEMALE_PARKINGS FROM `ps_parkingspace_buildings` building join ps_parkingspace_companies_buildings comp_bldg on comp_bldg.BUILDING_ID = building.BUILDING_ID join ps_parkingspace_companies company on company.COMPANY_ID = comp_bldg.COMPANY_ID LEFT OUTER join ps_parkingspace_building_parkings current on building.BUILDING_ID = current.BUILDING_ID and current.PARKING_BUSINESS_DATE = date(now()) and ((building.START_DATE >= date(now()) and building.END_DATE is null )or (date(now()) between building.start_date and building.END_DATE)) order by BUILDING_NO";

				$dbReturnResult = mysql_query($fetchDBQuery, $conn);

				if(!$dbReturnResult){
					die("Something went wrong");
				}

				while($dbReturnResultRow = mysql_fetch_assoc($dbReturnResult)){
					?>
					<div class="col-md-3 div_loginPanel">
						<!-- TD: Need to add the required add label -->
						<table class="tblClass div_dbrd_class blg_ctnr_class">
							<tr>
								<td rowspan="2">
									<div class="div_dbrd_class ps_class">
										<?php echo "<span class='bldg_class' style='color:white; background: " . $colorArray[$colorArrayIterator] . "'>" . $dbReturnResultRow["BUILDING_NO"] . "</span>"; ?>
									</div>
								</td>
								<td>
									<div class="div_dbrd_class comman_ps_class">
										<?php 
											if($dbReturnResultRow["COMMON_PARKINGS"] > 0){
												echo "<span class='available-parking-class'>C" . $dbReturnResultRow["COMMON_PARKINGS"] . "</span"; 
											}else{
												echo "<span class='full-parking-class'>C" . $dbReturnResultRow["COMMON_PARKINGS"] . "</span"; 
											}
										?>
									</div>
								</td>
							</tr>
							<tr  colspan="2">
								<td>
									<div class="div_dbrd_class fml_ps_class">
										<?php 
											if($dbReturnResultRow["FEMALE_PARKINGS"] > 0){
												echo "<span class='available-parking-class'>F" . $dbReturnResultRow["FEMALE_PARKINGS"] . "</span"; 
											}else{
												echo "<span class='full-parking-class'>F" . $dbReturnResultRow["FEMALE_PARKINGS"] . "</span"; 
											}
										?>
									</div>
								</td>
							</tr>
							<tr class='addr-class'>
								<td colspan="2">
									<strong><?php echo $dbReturnResultRow["COMPANY_NAME"]; ?><br/>
									<?php echo $dbReturnResultRow["COMPANY_LOCATION"]; ?><br/></strong>
									<em><?php echo $dbReturnResultRow["COMPANY_LANDMARK"]; ?></em>
								</td>
							</tr>
						</table>
					</div>
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