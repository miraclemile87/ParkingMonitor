<?php
	// TD: need to include the session
	include("ps_valid_session.php");
	include("ps_config_session.php");

	//session_start();

	$gridId="";
	$qry_refIdValue = "-1";
	$menuLabel = "";

	$rid = "A";
	if(isset($_GET['gid'])){
		$gridId = str_rot13($_GET['gid']);
	}else
		die("Something went wrong. Retry after refreshing.");

	if(isset($_GET['rid']) && $_GET['rid'] == true){
		$rid="P";

		//echo $refId;

		$qry_refIdTableNameQuery = "SELECT `PS_PARKINGSPACE_GRID_ID`,`PS_PARKINGSPACE_GRID_NAME`,`PS_PARKINGSPACE_GRID_TABLE_NAME`,`PS_PARKINGSPACE_GRID_REF_ID`,`PS_PARKINGSPACE_GRID_MENU_LABEL`, `PS_PARKINGSPACE_GRID_REF_ID_INITIATOR` FROM `vw_ps_parkingspace_grid` WHERE `PS_PARKINGSPACE_GRID_ID` = " . $gridId;
			$refIdDetailsReturnResult = mysql_query( $qry_refIdTableNameQuery, $conn );

		if(mysql_num_rows($refIdDetailsReturnResult) != 1){
			die('Something went wrong : ' . mysql_error());
		}
		
		while($refIdDetailsDBRow = mysql_fetch_assoc($refIdDetailsReturnResult)){
			$menuLabel = " (" . $refIdDetailsDBRow["PS_PARKINGSPACE_GRID_MENU_LABEL"] . ")";
			$qry_refIdValue = $refIdDetailsDBRow['PS_PARKINGSPACE_GRID_REF_ID'];
		}
	}

	$addDetailsStatus = "";
	$actionDetails = "A";

	if(isset($_GET["actn"]))
		$actionDetails=$_GET["actn"];

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		if(session_status() === PHP_SESSION_ACTIVE){

		//if(ISSET($_SESSION['PS_GRID_ID'])){
			//die ("Something went wrong. Addition unsuccessful!!");

			$gridTableName = "";
			$qry_insertDetailsQuery = "";
			$qry_updateDetailsQuery = "";
			
			$qry_updateColumnValues = array();
			$qry_updateColumnID = array();

			$qry_insertDetailsColumns = array();
			$qry_insertDetailsValues = array();
			
			$qry_insertTableNameQuery = "SELECT `PS_PARKINGSPACE_GRID_ID`,`PS_PARKINGSPACE_GRID_NAME`,`PS_PARKINGSPACE_GRID_TABLE_NAME`,`PS_PARKINGSPACE_GRID_REF_ID`,`PS_PARKINGSPACE_GRID_REF_MENU_LABEL`, `PS_PARKINGSPACE_GRID_REF_ID_INITIATOR` FROM `vw_ps_parkingspace_grid` WHERE `PS_PARKINGSPACE_GRID_ID` = " . $_SESSION['PS_GRID_ID'];
			$insertDetailsDBRReturnResult = mysql_query( $qry_insertTableNameQuery, $conn );

			if(mysql_num_rows($insertDetailsDBRReturnResult) != 1){
				die('Something went wrong : ' . mysql_error());
			}
			
			while($insertDetailsDBRow = mysql_fetch_assoc($insertDetailsDBRReturnResult)){
				$gridTableName = $insertDetailsDBRow['PS_PARKINGSPACE_GRID_TABLE_NAME'];
				if(isset($_GET['rid']) && $_GET['rid'] == true)
					$qry_refIdValue = $insertDetailsDBRow['PS_PARKINGSPACE_GRID_REF_ID'];
				if($insertDetailsDBRow['PS_PARKINGSPACE_GRID_REF_ID_INITIATOR'] == 1){
					$menuLabel = $insertDetailsDBRow["PS_PARKINGSPACE_GRID_REF_MENU_LABEL"];
				}
				//echo "qry_refIdValue = " . $qry_refIdValue;
			}

			if($actionDetails == "E"){
				$qry_editDetailsQuery = "update $gridTableName ";

				foreach ($_POST as $param_name => $param_value) {
					$columnName = str_replace("tpass_","",str_replace("elmName_", "", str_replace("hidden_", "", $param_name)));
					if(substr($param_name, 0, strlen("hidden_elmName_")) == "hidden_elmName_"){
						$columnValue = "'" . $param_value . "'";

						$qry_updateColumnID[] = $columnName . "=" . $columnValue;
					}else{
						if(substr($param_name, 0, strlen("tpass_")) == "tpass_")
							$columnValue = "'" . strrev(password_hash($param_value, PASSWORD_DEFAULT)) . "'";
						else
							$columnValue = "'" . $param_value . "'";

						$qry_updateColumnValues[] = $columnName . "=" . $columnValue; 
					}
				}

				$qry_updateDetailsQuery = $qry_editDetailsQuery . " set " . implode("," , $qry_updateColumnValues) . " where " . implode(" and ", $qry_updateColumnID);

				//echo $qry_updateDetailsQuery;

				if(mysql_query($qry_updateDetailsQuery) == TRUE){
				//if(true){
					$addDetailsStatus = '<div  style="margin-bottom: 0px; margin-top: 12px" class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>SUCCESS!</strong> Updated Successfully!
							</div><script>$(".alert").fadeTo(2000, 500).slideUp(500, function(){
							    $("#success-alert").alert("close");
							});</script>';

					$actionDetails = "A";
				}else{
					$addDetailsStatus =  '<div style="margin-bottom: 0px; margin-top: 12px"x class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error: </strong> ' . mysql_error() . '</div>';
				}
			}else{
				$qry_insertDetailsQuery = "insert into $gridTableName ";	

				foreach ($_POST as $param_name => $param_value) {
					//echo "hi " . $param_name . "; ";
					$qry_insertDetailsColumns[] = str_replace("tpass_","",str_replace("elmName_", "", $param_name));
					if(substr($param_name, 0, strlen("tpass_")) == "tpass_")
						$qry_insertDetailsValues[] = "'" . strrev(password_hash($param_value, PASSWORD_DEFAULT)) . "'";
					else
						$qry_insertDetailsValues[] = "'" . $param_value . "'";
				}

				$qry_insertDetailsQuery	= $qry_insertDetailsQuery . "(" . implode(",", $qry_insertDetailsColumns) . ")" . " values (" . implode(",", $qry_insertDetailsValues) . ")" ;

				//echo $qry_insertDetailsQuery;

				if(mysql_query($qry_insertDetailsQuery) == TRUE){
					//echo "Successfully inserted";
					if($qry_refIdValue != -1){		
						//echo " in here with ". $qry_refIdValue;
						//echo 
						$url = "psAddDetails.php?gid=" . $qry_refIdValue . "&gnm=" . str_rot13('Set Up');
						if(isset($_GET['rid']) && $_GET['rid'] == true){
							$url = $url . "&rid=true";
						}
						header("location: " . $url);
							//exit();
					}else{
						$addDetailsStatus = '<div  style="margin-bottom: 0px; margin-top: 12px" class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>SUCCESS!</strong> Added Successfully!
							</div><script>$(".alert").fadeTo(2000, 500).slideUp(500, function(){
							    $("#success-alert").alert("close");
							});</script>';
					}
				}else{

					$addDetailsStatus =  '<div style="margin-bottom: 0px; margin-top: 12px"x class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error: </strong> ' ;
					if(mysql_errno() == 1062)
						$addDetailsStatus = $addDetailsStatus . "Link already exists.";	
					else
						$addDetailsStatus = $addDetailsStatus . mysql_error();
					 $addDetailsStatus = $addDetailsStatus . '</div>';
				}
			}
		}
	}	
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
		<!--<link rel="stylesheet" href="css/flag-sprite/flags.min.css">-->
		<link rel="stylesheet" href="css/main.css">
		<script src="lib/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css">
		<script src="js/psDetail.js"></script>
	</head>
	<body>
		<?php
			include("ps_main_header.php");
		?>
		<!--
			TD: Need to add the parking space grid id manually
		-->
		<div id="div_addDetails">
			<div class="row">
				<div class="col-md-4 col-md-offset-4" id="div_loginPanel">
					<span id="spnStatus"><?php echo $addDetailsStatus; ?></span>
					<!-- TD: Need to add the required add label -->
					<h3 id="h1_loginHeader"><?php 
						if(isset($_GET['gnm']))
							echo str_rot13($_GET['gnm']); if($menuLabel != "") echo $menuLabel;
							?>
							<span id="spnEditDetails_<?php echo $gridId; ?>"  name="spnEditDetails_<?php echo $_GET['gnm']; ?>" class="spnDetails_class spnEditDetails_class spn_action_class"><span class="glyphicon glyphicon glyphicon-th-list"></span></span>
					</h3>
					<hr/>
					<form role="form" name="frm_AddDetails" action="" method="post">
						<div class="form-group">
							<?php

								if($actionDetails == "E"){
									$qry_editDetails = "Select PS_PARKINGSPACE_GRID_TABLE_NAME, PS_PARKINGSPACE_GRID_UNIQUE_QUERY_COLUMN from ps_parkingspace_grid where PS_PARKINGSPACE_GRID_ID = " . $gridId;

									//echo $qry_editDetails;

									$editDetailsDBReturnResult = mysql_query($qry_editDetails, $conn);

									if((!$editDetailsDBReturnResult) || mysql_num_rows($editDetailsDBReturnResult) == 0){
										die("Could not prepare for edit");
									}

									$editTableName = "";
									$editUniqueQueryColumn = "";

									while($editDetailDBRow = mysql_fetch_assoc($editDetailsDBReturnResult)){
										$editTableName = $editDetailDBRow["PS_PARKINGSPACE_GRID_TABLE_NAME"];
										$editUniqueQueryColumn = $editDetailDBRow["PS_PARKINGSPACE_GRID_UNIQUE_QUERY_COLUMN"];
									}

									$editDataQuery = "select * from " . $editTableName . " where " . $editUniqueQueryColumn . " = " . $_GET["uid"];

									//echo $editDataQuery;

									$editDataDBRowReturnResult = mysql_query($editDataQuery, $conn);

									if(!($editDataDBRowReturnResult) || mysql_num_rows($editDataDBRowReturnResult) != 1){
										die ("Unable to fetch detailed data");
									}

									$editDataDBRow = mysql_fetch_assoc($editDataDBRowReturnResult);
								}

								$qry_fetchDetails = "SELECT `PS_PARKINGSPACE_GRID_ID`, `PS_PARKINGSPACE_GRID_COLUMN_ID`, `PS_PARKINGSPACE_GRID_COLUMN_NAME`, `PS_PARKINGSPACE_GRID_COLUMN_HEADER`, `INPUT_RESTRICTION_CODE`,`INPUT_RESTRICTION_ERROR_MSG`, `PS_PARKINGSPACE_GRID_COLUMN_REQUIRED`, `PS_PARKINGSPACE_GRID_COLUMN_QUERY`, `PS_PARKINGSPACE_GRID_COLUMN_ENABLED`, `PS_PARKINGSPACE_GRID_COLUMN_MESSAGE`, `PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH`, `PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH`, `START_DATE`, `END_DATE`, `MODIFICATION_DATE` FROM `ps_parkingspace_grid_details` grid join `ps_parkingspace_input_restriction` restriction on restriction.INPUT_RESTRICTION_CODE = grid.`PS_PARKINGSPACE_GRID_COLUMN _RESTRICTION_CODE` where PS_PARKINGSPACE_GRID_ID = " . $gridId . " ORDER BY PS_PARKINGSPACE_GRID_COLUMN_ID";
								
								//echo $qry_fetchDetails;

								$addDetailsDBRReturnResult = mysql_query( $qry_fetchDetails, $conn );

								if(! $addDetailsDBRReturnResult ) {
									die('Could not select grid details: ' . mysql_error());
								}

								if(mysql_num_rows($addDetailsDBRReturnResult) == 0){
									die('Something went wrong : ' . mysql_error());
								}
								
								//$showResultCounter = 1;
								while($addDetailsDBRow = mysql_fetch_assoc($addDetailsDBRReturnResult)){
									if(!ISSET($_SESSION['PS_GRID_ID']) || $_SESSION['PS_GRID_ID'] != $addDetailsDBRow['PS_PARKINGSPACE_GRID_ID']){
										//echo " not set setting ";
										$_SESSION['PS_GRID_ID'] = $addDetailsDBRow['PS_PARKINGSPACE_GRID_ID'];
										//echo 'set as ' . $_SESSION['PS_GRID_ID'] . ' coz ' . $addDetailsDBRow['PS_PARKINGSPACE_GRID_ID'];
									}
									/*if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_ID'] == mysql_num_rows($addDetailsDBRReturnResult)){
										$showResultCounter++;
									}
									if($showResultCounter != $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_ID'])
										continue;*/
									$isRequired="";
									$isRequiredLabel="";
									$isEnabled="";
									if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_REQUIRED'] == 1){
										$isRequired = "required";
										$isRequiredLabel="*";
									}
									if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_ENABLED'] != 1){
										$isEnabled = "readonly";
									}

								?>
									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] != "HIDE"){
		    						?>
			    						<label style="font-family:Cambria; color: #337ab7" for="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>"><?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?></label>
			    							<span style="color:brown"><?php echo $isRequiredLabel; ?></span>
			    					<?php
			    						}
			    					?>

		    						<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "HIDE" && $actionDetails == "E"){
		    						?>
			    						<input <?php 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH'] != -1)
			    								echo "minlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH']; 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'] != -1)
			    								echo "maxlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'];
			    							?> type="hidden" class="hidden_field form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="hidden_elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>" 
			    								value='<?php if($actionDetails == "E"){echo $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]];}?>' />
		    						<?php
		    							}
									?>

		    						<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "TEXT"){
		    						?>
			    						<input <?php 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH'] != -1)
			    								echo "minlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH']; 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'] != -1)
			    								echo "maxlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'];
			    							?> type="text" pattern="[a-zA-Z ]+" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>" 
			    								value='<?php if($actionDetails == "E"){echo $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]];}?>' />
		    						<?php
		    							}
									?>

									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "TPASS"){
		    								include("ps_fnc.php");
		    								$tPassValue = getStrRandom(rand(6,9));
		    						?>
			    						<input <?php 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH'] != -1)
			    								echo "minlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH']; 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'] != -1)
			    								echo "maxlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'];
			    							?> type="text" pattern="[a-zA-Z0-9-?%$#@]+" class="form-control" id="tpass_elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="tpass_elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> value='<?php if($actionDetails == "E"){echo $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]];}else{echo $tPassValue;}?>'/>
		    						<?php
		    							}
									?>

									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "EMAIL"){
		    						?>
			    						<input <?php 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH'] != -1)
			    								echo "minlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH']; 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'] != -1)
			    								echo "maxlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'];
			    							?> type="email" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?> " value='<?php if($actionDetails == "E"){echo $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]];} ?>'/>
		    						<?php
		    							}
									?>

									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "NUM"){
		    						?>
			    						<input <?php 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH'] != -1)
			    								echo "minlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH']; 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'] != -1)
			    								echo "maxlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'];
			    							?> type="number" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>" value='<?php if($actionDetails == "E"){echo $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]];} ?>'/>
		    						<?php
		    							}
									?>
									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "ALPHA"){
		    						?>
		    							<!-- TD: pattern="[a-zA-Z0-9/s]+" -->
			    						<input <?php 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH'] != -1)
			    								echo "minlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH']; 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'] != -1)
			    								echo "maxlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'];
			    							?> type="text"  oninvalid="setCustomValidity('<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_ERROR_MSG'] ?>')" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>" value='<?php if($actionDetails == "E"){echo $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]];} ?>'/>
		    						<?php
		    							}
									?>
									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "NSALPHA"){
		    						?>
			    						<input <?php 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH'] != -1)
			    								echo "minlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH']; 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'] != -1)
			    								echo "maxlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'];
			    							?> type="text" pattern="[a-zA-Z0-9]+" oninvalid="setCustomValidity('<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_ERROR_MSG'] ?>')" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>" value='<?php if($actionDetails == "E"){echo $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]];} ?>'/>
		    						<?php
		    							}
									?>
									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "BTEXT"){
		    						?>
			    						<textarea <?php 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH'] != -1)
			    								echo "minlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH']; 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'] != -1)
			    								echo "maxlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'];
			    							?> rows="4s" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>"><?php if($actionDetails == "E"){echo $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]];} ?></textarea>
		    						<?php
		    							}
									?>
									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "HCOMB"){
		    						?>
			    						<select class="form-control tHourClass" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>">
			    							<?php
			    								$tHour=0;
			    								for($tHour=0; $tHour <= 24; $tHour++){
			    									?>
			    									<option value="<?php echo $tHour; ?>" <?php if($actionDetails == "E" && $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]] == $tHour){echo "selected";}?>><?php echo $tHour; ?></option>
			    									<?php
			    								}
			    							?>
			    						</select>
		    						<?php
		    							}
									?>
									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "MCOMB"){
		    						?>
			    						<select class="form-control tMinuteClass" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>">
			    							<?php
			    								$tMinute=0;
			    								for($tMinute=0; $tMinute <= 60; $tMinute++){
			    									?>
			    									<option value="<?php echo $tMinute; ?>" <?php if($actionDetails == "E" && $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]] == $tMinute){echo "selected";}?>><?php echo $tMinute; ?></option>
			    									<?php
			    								}
			    							?>
			    						</select>
		    						<?php
		    							}
									?>

									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "CNTROPT"){
		    						?>
			    						<select class="country-opt-class form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>">
			    							<option value="">Select Country</option>
			    						 	<?php
			    								$optQueryResult = mysql_query($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_QUERY'], $conn);

			    								if(! $optQueryResult ) {
													die('Could not select option details: ' . mysql_error());
												}

												if(mysql_num_rows($optQueryResult) == 0){
													die('Something went wrong : ' . mysql_error());
												}

												while($optDetailsDBRow = mysql_fetch_assoc($optQueryResult)){
													echo "<option value='" . $optDetailsDBRow['value'] ."'";
													if($actionDetails == "E" && $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]] == $optDetailsDBRow['value']){echo "selected";}
													echo ">" . explode(";",$optDetailsDBRow['text'])[1] . "</option>";
												}
			    							?>
			    						</select>
		    						<?php
		    							}
		    						?>

		    						<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "STOPT"){
		    						?>
			    						<select class="<?php if($actionDetails == "E"){echo "state-class-has-value state-class-value-" . $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]]; }?> state-opt-class form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>">
			    							<option value='-1'>Select Country first</option>
			    						</select>
		    						<?php
		    							}
		    						?>

		    						<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "CITYOPT"){
		    						?>
			    						<select class="<?php if($actionDetails == "E"){echo "city-class-has-value city-class-value-" . $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]]; }?> city-opt-class form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>">
			    							<option value='-1'>Select State first</option>
			    						</select>
		    						<?php
		    							}
		    						?>

									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "OPT" || $addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "UOPT" ){
		    						?>
			    						<select class="form-control <?php if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "UOPT") echo "user-opt-class"; ?>" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>">
			    							<?php
			    								$optQueryResult = mysql_query(str_replace("#CURRENT_USER_ID#",$_SESSION['USER_ID'], $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_QUERY']), $conn);

			    								if(! $optQueryResult ) {
													die('Could not select option details: ' . mysql_error());
												}

												if(mysql_num_rows($optQueryResult) == 0){
													//die('Something went wrong : ' . mysql_error());
													echo "<option value=''>Nothing to select</option>";
												}

												if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "UOPT"){
													echo "<option value = " . $_SESSION['USER_ID'] . ">". $_SESSION['USER_NAME'] . "</option>";
												}else{
													while($optDetailsDBRow = mysql_fetch_assoc($optQueryResult)){
														echo "<option value='" . $optDetailsDBRow['value'] ."'";
														if(($actionDetails == "E" && $editDataDBRow[$addDetailsDBRow["PS_PARKINGSPACE_GRID_COLUMN_NAME"]] == $optDetailsDBRow['value']) || ($_SESSION['USER_ID'] == $optDetailsDBRow['value'])){echo "selected";}
														echo ">" . $optDetailsDBRow['text']. "</option>";
													}
												}
			    							?>
			    						</select>
		    						<?php
		    							}
		    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MESSAGE']){
									?>

									<label id="lblMessage"><?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MESSAGE']; ?></label>
									<br/>
								<?php
										}
								//$showResultCounter++;
									}
								?>
								<br/>
								<div style="text-align:center" id="divButtons">
									<input type="submit" class="btn btn-lg btn-primary btnSimple" value="<?php if($actionDetails == 'A'){ if($rid == "P") {if($qry_refIdValue == -1){echo "DONE"; }else{echo "ADD & NEXT";}}else{echo 'ADD';}} else {echo 'UPDATE'; }?>">
									</input>							
								</div>
	    					</div>
    				</form>
				</div>
			</div>		
		</div>
		<div id="tempDiv">
		</div>
	</body>
</html>