<?php
	include("ps_valid_session.php");
	include("ps_config_session.php");

	//echo $_POST['CNTRY'];

	if(isset($_POST['CNTRY'])){

		$qry_companySql = "SELECT company.company_id value, CONCAT( company.COMPANY_NAME , ', ' , company.company_city, ', ' , company.company_state) text FROM `ps_parkingspace_companies_buildings` company_building join vw_ps_parkingspace_companies company on company.company_id = company_building.company_id join ps_parkingspace_companies companies on companies.company_id = company_building.company_id join ps_parkingspace_buildings building on building.building_id = company_building.building_id where companies.company_country = '" . $_POST['CNTRY'] . "' and ((now() between company_building.start_date and company_building.end_date) or (now() >= company_building.start_date and company_building.end_date is null)) group by company.company_id , CONCAT( company.COMPANY_NAME , ', ' , company.company_city, ', ' , company.company_state) order by 2";

		//echo '<script>console.log(" . $qry_companySql . ")</script>';

		$dbReturnResult = mysql_query($qry_companySql, $conn);

		if(!$dbReturnResult){
			die("Something went wrong");
		}

		if(mysql_num_rows($dbReturnResult) == 0){
			echo '<option value="">Parking not registered</option>';
		}else{
			echo "<option value=''>Select Parking</option>";
			while($dbReturnResultRow = mysql_fetch_assoc($dbReturnResult)){
				//$data[] = $dbReturnResultRow;
				echo "<option value='" . $dbReturnResultRow['value'] . "'>" . $dbReturnResultRow['text'] . "</option>";
			}		
		}
	}else{
		die ("Something went wrong : cnTR");
	}

	exit;
?>