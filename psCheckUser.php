<?php
	// TD: need to include the session
	include("ps_valid_session.php");
	include("ps_config_session.php");

	$uname = "";

	if(isset($_GET['uname'])){
		$uname =$_GET['uname'];
	}else
		echo "Error: Something went wrong whilst getting the user. Retry after refreshing.";

	$qyr_userdeTailS = "SELECT count(*) user_count FROM `ps_parkingspace_users` WHERE user_name =  '" . $uname . "'";

	//echo $qyr_userdeTailS;
	
	$returnGridDetailsResult = mysql_query( $qyr_userdeTailS, $conn );

	if(mysql_num_rows($returnGridDetailsResult) != 1){
		echo 'Error: Fetch was unsuccessful.';
	}

	$returnGridDetailsResultDBRow = mysql_fetch_assoc($returnGridDetailsResult);

	echo $returnGridDetailsResultDBRow["user_count"];

?>