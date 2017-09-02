<?php
	include("ps_valid_session.php");
	include("ps_config_session.php");

	if(isset($_POST['STATE'])){

		$qry_doughnutData = "select id value, name text from ps_cities where state_id = " . $_POST['STATE'];

		$dbReturnResult = mysql_query($qry_doughnutData, $conn);

		if(!$dbReturnResult){
			die("Something went wrong");
		}

		if(mysql_num_rows($dbReturnResult) == 0){
			echo '<option value="">City not available</option>';
		}else{
			echo "<option value=''>Select City</option>";
			while($dbReturnResultRow = mysql_fetch_assoc($dbReturnResult)){
				//$data[] = $dbReturnResultRow;
				echo "<option value='" . $dbReturnResultRow['value'] . "'>" . $dbReturnResultRow['text'] . "</option>";
			}		
		}

		//echo json_encode($data);
	}else{
		die ("Something went wrong : sTATe");
	}
?>