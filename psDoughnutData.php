<?php
	header('Content-Type: application/json');

	//include("ps_valid_session.php");
	include("ps_config_session.php");

	session_start();

	$userId = -99;

	if(isset($_SESSION['USER_ID']))
		$userId = $_SESSION['USER_ID'];

	$qry_doughnutData = "SELECT company.company_ID, case when ifnull(manager.BUILDING_GUARD_ID,0) = 0 then 'N' else 'Y' end IS_PM,building.building_ID, comp_bldg.COMPANY_BUILDING_ID, `BUILDING_NO`, `COMPANY_NAME`, CONCAT(city.name, ', ', state.name, ', ', country.name) COMPANY_LOCATION, `COMPANY_LANDMARK`, concat(COMMON_OPENING_HOURS , ' - ', COMMON_CLOSING_HOURS) COMMON_TIMING,case when DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') between COMMON_OPENING_HOURS and COMMON_CLOSING_HOURS THEN case when ifnull(`BUILDING_PARKING_SLOTS_F`, 0) <> 0 and DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') not between FEMALE_OPENING_HOURS and FEMALE_CLOSING_HOURS THEN ifnull(`BUILDING_PARKING_SLOTS_C`, 0) + ifnull(`BUILDING_PARKING_SLOTS_F`, 0)	else ifnull(`BUILDING_PARKING_SLOTS_C`, 0) end else	0 end COMMON_PARKINGS_TOTAL, case when DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') between COMMON_OPENING_HOURS and COMMON_CLOSING_HOURS THEN	case when ifnull(`BUILDING_PARKING_SLOTS_F`, 0) <> 0 and DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') not between FEMALE_OPENING_HOURS and FEMALE_CLOSING_HOURS THEN (ifnull(`BUILDING_PARKING_SLOTS_C`, 0) + ifnull(`BUILDING_PARKING_SLOTS_F`, 0)) - (ifnull(`PARKING_COUNT_C`, 0) + ifnull(`PARKING_COUNT_F`, 0)) else ifnull(`BUILDING_PARKING_SLOTS_C`, 0) - ifnull(`PARKING_COUNT_C`, 0) end else 0 end COMMON_PARKINGS_AVAILABLE, case when DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') between COMMON_OPENING_HOURS and COMMON_CLOSING_HOURS THEN case when  ifnull(`BUILDING_PARKING_SLOTS_F`, 0) <> 0 and DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') not between FEMALE_OPENING_HOURS and  FEMALE_CLOSING_HOURS THEN ifnull(`PARKING_COUNT_F`, 0) + ifnull(`PARKING_COUNT_C`, 0) else ifnull(`PARKING_COUNT_C`, 0) end else 0 end COMMON_PARKINGS_BOOKED, case when DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') between COMMON_OPENING_HOURS and COMMON_CLOSING_HOURS THEN case when  ifnull(`BUILDING_PARKING_SLOTS_F`, 0) <> 0 then case when DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') not between FEMALE_OPENING_HOURS and FEMALE_CLOSING_HOURS THEN 'FEMALE PARKING INCLUDED' else 'FEMALE PARKING SEPERATE' end else 'NO FEMALE PARKING' end else 'COMMON PARKING CLOSED' end COMMON_PARKINGS_REMARK, concat(FEMALE_OPENING_HOURS , ' - ', FEMALE_CLOSING_HOURS) FEMALE_TIMING, case when ifnull(`BUILDING_PARKING_SLOTS_F`, 0) <> 0 and DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') between FEMALE_OPENING_HOURS and FEMALE_CLOSING_HOURS THEN ifnull(`BUILDING_PARKING_SLOTS_F`, 0) else 0 end FEMALE_PARKINGS_TOTAL, case when ifnull(`BUILDING_PARKING_SLOTS_F`, 0) <> 0 and DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') between FEMALE_OPENING_HOURS and FEMALE_CLOSING_HOURS THEN	ifnull(`BUILDING_PARKING_SLOTS_F`, 0) - ifnull(`PARKING_COUNT_F`, 0) else 0	end FEMALE_PARKINGS_AVAILABLE, case when ifnull(`BUILDING_PARKING_SLOTS_F`, 0) <> 0 and DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') between FEMALE_OPENING_HOURS and FEMALE_CLOSING_HOURS THEN ifnull(`PARKING_COUNT_F`, 0) else 0 end  FEMALE_PARKINGS_BOOKED, case when ifnull(`BUILDING_PARKING_SLOTS_F`, 0) <> 0 then case when DATE_FORMAT(CONVERT_TZ(now(),'-04:00','+05:30'), '%H:%i') between FEMALE_OPENING_HOURS and FEMALE_CLOSING_HOURS THEN 'FEMALE PARKING AVAILABLE' else 'FEMALE PARKING TIMING CLOSED' end else 'NO FEMALE PARKING' end  FEMALE_PARKINGS_REMARK FROM `vw_ps_parkingspace_buildings` building join  ps_parkingspace_companies_buildings comp_bldg on comp_bldg.BUILDING_ID = building.BUILDING_ID join ps_parkingspace_companies company on company.COMPANY_ID = comp_bldg.COMPANY_ID LEFT OUTER JOIN ps_cities city on city.id = company.company_city LEFT OUTER join ps_states state on state.id = company.COMPANY_STATE LEFT OUTER JOIN ps_countries country on country.id = company.COMPANY_COUNTRY LEFT OUTER join ps_parkingspace_building_parkings current on  comp_bldg.COMPANY_BUILDING_ID = current.COMPANY_BUILDING_ID and current.BUSINESS_DATE = date(CONVERT_TZ(now(),'-04:00','+05:30')) and ((date(building.START_DATE) <= date(CONVERT_TZ(now(),'-04:00','+05:30')) and date(building.END_DATE) is null ) or (date(CONVERT_TZ(now(),'-04:00','+05:30')) between date(building.start_date) and date(building.END_DATE))) LEFT OUTER JOIN ps_parkingspace_buildings_managers manager on manager.BUILDING_GUARD_BUILDING_ID = comp_bldg.BUILDING_ID and manager.BUILDING_GUARD_USER_ID = " . $userId . " where comp_bldg.COMPANY_ID = " . $_GET['cmpn'] . " order by BUILDING_NO";

	/*$qry_doughnutData = "SELECT company.company_ID, building.building_ID, comp_bldg.COMPANY_BUILDING_ID, `BUILDING_NO`, `COMPANY_NAME`, CONCAT(city.name ,', ', state.name , ', ' , country.name) COMPANY_LOCATION, `COMPANY_LANDMARK`, ifnull(`BUILDING_PARKING_SLOTS_C`,0) COMMON_PARKINGS_TOTAL, ifnull(`BUILDING_PARKING_SLOTS_C`,0) - ifnull(`PARKING_COUNT_C`, 0) COMMON_PARKINGS_AVAILABLE, ifnull(`PARKING_COUNT_C`, 0) COMMON_PARKINGS_BOOKED, ifnull(`BUILDING_PARKING_SLOTS_F`, 0) FEMALE_PARKINGS_TOTAL, ifnull(`BUILDING_PARKING_SLOTS_F`, 0) - ifnull(`PARKING_COUNT_F`,0) FEMALE_PARKINGS_AVAILABLE, ifnull(`PARKING_COUNT_F`,0) FEMALE_PARKINGS_BOOKED FROM `ps_parkingspace_buildings` building join ps_parkingspace_companies_buildings comp_bldg on comp_bldg.BUILDING_ID = building.BUILDING_ID join ps_parkingspace_companies company on company.COMPANY_ID = comp_bldg.COMPANY_ID LEFT OUTER JOIN ps_cities city on city.id = company.company_city LEFT OUTER join ps_states state on state.id = company.COMPANY_STATE LEFT OUTER JOIN ps_countries country on country.id = company.COMPANY_COUNTRY LEFT OUTER join ps_parkingspace_building_parkings current on comp_bldg.COMPANY_BUILDING_ID = current.COMPANY_BUILDING_ID and current.BUSINESS_DATE = date(CONVERT_TZ(now(),'-04:00','+05:30')) and ((date(building.START_DATE) <= date(CONVERT_TZ(now(),'-04:00','+05:30')) and date(building.END_DATE) is null )or (date(CONVERT_TZ(now(),'-04:00','+05:30')) between date(building.start_date) and date(building.END_DATE))) where comp_bldg.COMPANY_ID = " . $_GET['cmpn'] . " order by BUILDING_NO";*/

		//echo $qry_doughnutData;

		$dbReturnResult = mysql_query($qry_doughnutData, $conn);

		if(!$dbReturnResult){
			die("Something went wrong" .mysql_error());
		}

		$data = array();

		while($dbReturnResultRow = mysql_fetch_assoc($dbReturnResult)){
			$data[] = $dbReturnResultRow;
		}

		$jsonData = json_encode($data);

		echo json_encode($data);
?>