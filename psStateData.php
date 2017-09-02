<?php
	include("ps_valid_session.php");
	include("ps_config_session.php");

	if(isset($_POST['CNTRY'])){

		$qry_doughnutData = "select id value, name text from ps_states where country_id = " . $_POST['CNTRY'];

		//echo $qry_doughnutData;

		$dbReturnResult = mysql_query($qry_doughnutData, $conn);

		if(!$dbReturnResult){
			die("Something went wrong");
		}

		if(mysql_num_rows($dbReturnResult) == 0){
			echo '<option value="">State not available</option>';
		}else{
			echo "<option value=''>Select State</option>";
			while($dbReturnResultRow = mysql_fetch_assoc($dbReturnResult)){
				//$data[] = $dbReturnResultRow;
				echo "<option value='" . $dbReturnResultRow['value'] . "'>" . $dbReturnResultRow['text'] . "</option>";
			}		
		}
	}else{
		die ("Something went wrong : cnTR");
	}
?>