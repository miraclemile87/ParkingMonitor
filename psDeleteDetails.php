<?php
	// TD: need to include the session
	include("ps_valid_session.php");
	include("ps_config_session.php");

	//session_start();

	$gridId = "";
	$uid = "";

	if(isset($_GET['gid'])){
		$gridId = str_rot13($_GET['gid']);
	}else
		echo "Error: Something went wrong whilst getting the record to delete. Retry after refreshing.";

	if(isset($_GET["uid"])){
		$uid = $_GET["uid"];
	}else
		echo "Error: Something went wrong whilst fetching the exact record. Retry after refreshing.";

	$qry_deleteGridDetails = "SELECT `PS_PARKINGSPACE_GRID_ID`,`PS_PARKINGSPACE_GRID_NAME`,`PS_PARKINGSPACE_GRID_TABLE_NAME`,`PS_PARKINGSPACE_GRID_REF_ID`,`PS_PARKINGSPACE_GRID_MENU_LABEL`, `PS_PARKINGSPACE_GRID_UNIQUE_QUERY_COLUMN` FROM `vw_ps_parkingspace_grid` WHERE `PS_PARKINGSPACE_GRID_ID` = " . $gridId;
	
	$returnGridDetailsResult = mysql_query( $qry_deleteGridDetails, $conn );

	if(mysql_num_rows($returnGridDetailsResult) != 1){
		echo 'Error: Deletion was unsuccessful due to fetch failure.';
	}

	$returnGridDetailsResultDBRow = mysql_fetch_assoc($returnGridDetailsResult);

	$gridTableDeleteQuery = "update " . $returnGridDetailsResultDBRow["PS_PARKINGSPACE_GRID_TABLE_NAME"] . " set END_DATE = now(), MODIFICATION_DATE = now(), MODIFIED_BY = " . $_SESSION['USER_ID'] . " where " . $returnGridDetailsResultDBRow["PS_PARKINGSPACE_GRID_UNIQUE_QUERY_COLUMN"] . " = '" . $uid . "'";

	$dbDeleteResult = mysql_query($gridTableDeleteQuery, $conn);

	if(!$dbDeleteResult){
		echo "Error: Something went wrong while deletion" . mysql_error();
	}

	echo "Record Deleted successfully!!"

?>