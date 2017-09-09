<?php
	// TD: need to include the session
	include("ps_valid_session.php");
	include("ps_config_session.php");

	$gridId="";
	if(isset($_GET['gid'])){
		$gridId = $_GET['gid'];
	}else
		die("Something went wrong. Retry after refreshing.");
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
			<?php 
				if(isset($_GET['gnm']))
					echo str_rot13($_GET['gnm']); 
			?>
		</title>
		<script src="lib/jquery-1.12.0.min.js"></script>
		<script src="js/msdropdown/jquery.dd.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="css/msdropdown/dd.css" />
		<link rel="stylesheet" type="text/css" href="css/msdropdown/skin2.css" />
		<link rel="stylesheet" type="text/css" href="css/msdropdown/flags.css" />
		<link rel="stylesheet" href="lib/bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="lib/bootstrap-3.3.6-dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/psDetail.css">
		<link rel="stylesheet" href="css/main.css">
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
		<div id="div_vieWdetails">
			<div class="row">
				<div class="col-md-10 col-md-offset-1" id="div_loginPanel">
					<!-- TD: Need to add the required add label -->
					<h3 id="h1_loginHeader"><?php 
						if(isset($_GET['gnm']))
							echo str_rot13($_GET['gnm']); 
							?>
							<span id="spnAddDetails_<?php echo $gridId; ?>"  name="spnAddDetails_<?php echo $_GET['gnm']; ?>" class="spnDetails_class spnAddDetails_class spn_action_class"><span class="glyphicon glyphicon-plus-sign"></span></span>
					</h3>
					<hr/>
					<form role="form" name="frm_ViewDetails" action="" method="post">
						<div class="form-group">
							<table class="table table-striped tblViewDetail">
							<?php
								$qry_fetchDetails = "SELECT `PS_PARKINGSPACE_GRID_ID`, `PS_PARKINGSPACE_GRID_NAME`, `PS_PARKINGSPACE_GRID_TABLE_NAME`, `PS_PARKINGSPACE_GRID_HEADER`, `PS_PARKINGSPACE_GRID_QUERY`,	`PS_PARKINGSPACE_GRID_QUERY_HEADER`, `PS_PARKINGSPACE_GRID_PID`, `PS_PARKINGSPACE_GRID_HID`, `PS_PARKINGSPACE_GRID_DANGER_ALERT_COL`, `PS_PARKINGSPACE_GRID_DANGER_ALERT_COL_VALUE`, `PS_PARKINGSPACE_GRID_IS_EDITABLE`, `PS_PARKINGSPACE_GRID_IS_DELETABLE` FROM `ps_parkingspace_grid` where PS_PARKINGSPACE_GRID_ID = " . $gridId;
								
								//echo $qry_fetchDetails;

								$viewDetailsDBRReturnResult = mysql_query( $qry_fetchDetails, $conn );

								if(! $viewDetailsDBRReturnResult ) {
									die('Could not select grid details: ' . mysql_error());
								}

								if(mysql_num_rows($viewDetailsDBRReturnResult) == 0){
									die('Something went wrong : ' . mysql_error());
								}

								$viewDetailsDBRReturnResultRow = mysql_fetch_assoc($viewDetailsDBRReturnResult);

								//echo $viewDetailsDBRReturnResultRow["PS_PARKINGSPACE_GRID_QUERY"];
								
								//$showResultCounter = 1;
								if(!empty($viewDetailsDBRReturnResultRow["PS_PARKINGSPACE_GRID_QUERY"])){
									$viewSpecificDetailsDBReturnResult = mysql_query(str_replace("#CURRENT_USER_ID#",$_SESSION['USER_ID'], $viewDetailsDBRReturnResultRow["PS_PARKINGSPACE_GRID_QUERY"]), $conn);

									if(! $viewSpecificDetailsDBReturnResult){
										die('Could not get query details.');
									}

									//echo $viewDetailsDBRReturnResultRow["PS_PARKINGSPACE_GRID_QUERY_HEADER"];

									$colHeaders = explode("|", $viewDetailsDBRReturnResultRow["PS_PARKINGSPACE_GRID_QUERY_HEADER"]);
									$colPID = explode("|", $viewDetailsDBRReturnResultRow["PS_PARKINGSPACE_GRID_PID"]);
									$colHID= explode("|", $viewDetailsDBRReturnResultRow["PS_PARKINGSPACE_GRID_HID"]);


									?>
									<thead>
										<tr>
										<?php
										foreach ($colHeaders as $colHeaderValue) {
											?>
											<th><?php echo $colHeaderValue; ?></th>
											<?php	
										}
										?>
										</tr>
									</thead>
									<tbody>
										<?php
										while($viewDetailRow = mysql_fetch_array($viewSpecificDetailsDBReturnResult, MYSQL_NUM)){
											//echo implode(" " , $viewDetailRow);
											$uniqueId="";
											?>
											<tr>
												<?php
													foreach ($viewDetailRow as $index => $rowDataValue) {
														$idx=$index + 1;
														if(in_array($idx, $colPID)){
															$uniqueId = $rowDataValue;
															if(!in_array($idx, $colHID)){
																echo "<td class='class_td_" . $index . "'>" . $rowDataValue . "</td>";
															}else{
																echo "<td style='display: none' class='class_td_" . $index . "'>" . $rowDataValue . "</td>";
															}
														}else{
															echo "<td>" . $rowDataValue . "</td>";
														}
													}
												?>
												<?php if($viewDetailsDBRReturnResultRow['PS_PARKINGSPACE_GRID_IS_EDITABLE'] != 0){
													?>
													<td><a href="<?php echo "psAddDetails.php?gid=" . $viewDetailsDBRReturnResultRow["PS_PARKINGSPACE_GRID_ID"] . "&gnm=" . str_rot13($viewDetailsDBRReturnResultRow["PS_PARKINGSPACE_GRID_HEADER"]) . "&actn=E&uid=" . $uniqueId; ?>"><span class="spn_edit_class spn_action_class glyphicon glyphicon-edit"></span></a></td>
													<?php
												}
												?>
												<?php if($viewDetailsDBRReturnResultRow['PS_PARKINGSPACE_GRID_IS_DELETABLE'] != 0){
													?>
													<td><span class="spn_delete_class spn_action_class glyphicon glyphicon-remove-sign"></span></td>
												<?php
													}
												?>
											</tr>
											<?php
										}									
								}else{
									die("No data to show");
								}	
							?>
									</tbody>
							</table>
	    				</div>
    				</form>
				</div>
			</div>		
		</div>
		<div id='tempDiv'>
		</div>
	</body>
</html>