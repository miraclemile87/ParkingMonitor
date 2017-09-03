<?php
	include("ps_valid_session.php");
	include("ps_config_session.php");

	if(isset($_POST['CMPNY_BLDG_ID']) && isset($_POST["mORf"]) && isset($_POST["aORd"])){

		$qryCount = "select count(*) from ps_parkingspace_building_parkings where company_building_id = " . $_POST['CMPNY_BLDG_ID'] . " and parking_business_date = date(now())";

		$dbCountReturnResult = mysql_query($qryCount, $conn);

		if(!$dbCountReturnResult){
			die("Something went wrong");
		}

		if(mysql_num_rows($dbCountReturnResult) == 0){
			$sqlInsert = "insert into ps_parkingspace_building_parkings (`COMPANY_BUILDING_ID`,`PARKING_BUSINESS_DATE`,`PARKING_COUNT_C`,`PARKING_COUNT_F`,`MODIFICATION_DATE` FROM `ps_parkingspace_building_parkings`) values ('" . $_POST['CMPNY_BLDG_ID'] . "', date(now()), 0, 0, now())"; 

			$dbInsReturnResult = mysql_query($sqlInsert, $conn);

			if(!$dbInsReturnResult){
				die("Something went wrong");
			}
		}


		$qry_companySql = "update ps_parkingspace_building_parkings set ";
		if($_POST["aORd"] == "A"){
			if($_POST["mORf"] == "M"){
				$qry_companySql = $qry_companySql .  "parking_count_c =  (parking_count_c + 1), ";
				$qry_companySql = $qry_companySql .  "parking_count_f =  (parking_count_f), ";
			}
			else{
				$qry_companySql = $qry_companySql .  "parking_count_c = (parking_count_c), ";
				$qry_companySql = $qry_companySql .  "parking_count_f = (parking_count_f + 1), ";
			}
		}else{
			if($_POST["mORf"] == "M"){
				$qry_companySql = $qry_companySql .  "parking_count_c = (parking_count_c - 1), ";
				$qry_companySql = $qry_companySql .  "parking_count_f = (parking_count_f), ";
			}
			else{
				$qry_companySql = $qry_companySql .  "parking_count_c = (parking_count_c), ";
				$qry_companySql = $qry_companySql .  "parking_count_f = (parking_count_f - 1), ";
			}
		}
		
		$qry_companySql = $qry_companySql .' last_modification_date = now() where company_building_id = ' . $_POST['CMPNY_BLDG_ID'] . ' and business_date = date(now())';

		//echo $qry_companySql;

		//echo '<script>console.log(" . $qry_companySql . ")</script>';

		$dbReturnResult = mysql_query($qry_companySql, $conn);

		if(!$dbReturnResult){
			die("Something went wrong");
		}

		
		$sqlInsertDetails = "insert into ps_parkingspace_building_parking_details (`COMPANY_BUILDING_ID`,`PARKING_ENTRY_TIME`,`PARKING_STATUS`,`PARKING_TYPE`, `USER_ID`,`LAST_MODIFICATION_DATE`) values (" . $_POST['CMPNY_BLDG_ID']. ", now(), '" . $_POST['aORd']. "', '" . $_POST['mORf']. "', " . $_SESSION['USER_ID'] . " , now())";

		$dbInsDetailsReturnResult = mysql_query($sqlInsertDetails, $conn);

		if(!$dbInsDetailsReturnResult){
			die("Something went wrong");
		}
		
	}else{
		die ("Something went wrong : cnTR");
	}
?>