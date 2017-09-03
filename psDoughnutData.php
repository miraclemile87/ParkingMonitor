<?php
	include("ps_valid_session.php");
	include("ps_config_session.php");

	$qry_doughnutData = "SELECT company.company_ID, building.building_ID, `BUILDING_NO`, `COMPANY_NAME`, CONCAT(city.name ,', ', state.name , ', ' , country.name) COMPANY_LOCATION, `COMPANY_LANDMARK`, ifnull(`BUILDING_PARKING_SLOTS_C`,0) COMMON_PARKINGS_TOTAL, ifnull(`BUILDING_PARKING_SLOTS_C`,0) - ifnull(`PARKING_COUNT_C`, 0) COMMON_PARKINGS_AVAILABLE, ifnull(`PARKING_COUNT_C`, 0) COMMON_PARKINGS_BOOKED, ifnull(`BUILDING_PARKING_SLOTS_F`, 0) FEMALE_PARKINGS_TOTAL, ifnull(`BUILDING_PARKING_SLOTS_F`, 0) - ifnull(`PARKING_COUNT_F`,0) FEMALE_PARKINGS_AVAILABLE, ifnull(`PARKING_COUNT_F`,0) FEMALE_PARKINGS_BOOKED FROM `ps_parkingspace_buildings` building join ps_parkingspace_companies_buildings comp_bldg on comp_bldg.BUILDING_ID = building.BUILDING_ID join ps_parkingspace_companies company on company.COMPANY_ID = comp_bldg.COMPANY_ID LEFT OUTER JOIN ps_cities city on city.id = company.company_city LEFT OUTER join ps_states state on state.id = company.COMPANY_STATE LEFT OUTER JOIN ps_countries country on country.id = company.COMPANY_COUNTRY LEFT OUTER join ps_parkingspace_building_parkings current on building.BUILDING_ID = current.BUILDING_ID and current.PARKING_BUSINESS_DATE = date(now()) and ((building.START_DATE >= date(now()) and building.END_DATE is null )or (date(now()) between building.start_date and building.END_DATE))  order by BUILDING_NO";

		$dbReturnResult = mysql_query($qry_doughnutData, $conn);

		if(!$dbReturnResult){
			die("Something went wrong");
		}

		$data = array();

		while($dbReturnResultRow = mysql_fetch_assoc($dbReturnResult)){
			$data[] = $dbReturnResultRow;
		}

		$jsonData = json_encode($data);

		echo json_encode($data);
?>