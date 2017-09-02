<?php
	// TD: need to include the session
	include("ps_valid_session.php");
	include("ps_config_session.php");

	$gridId="";
	if(isset($_GET['gid'])){
		$gridId = str_rot13($_GET['gid']);
	}else
		die("Something went wrong. Retry after refreshing.");

	$addDetailsStatus = "";

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		if(session_status() === PHP_SESSION_ACTIVE){

		//if(ISSET($_SESSION['PS_GRID_ID'])){
			//die ("Something went wrong. Addition unsuccessful!!");
		
			$gridTableName = "";
			$qry_insertDetailsQuery = "";
			$qry_insertDetailsColumns = array();
			$qry_insertDetailsValues = array();
			
			$qry_insertTableNameQuery = "SELECT `PS_PARKINGSPACE_GRID_ID`,`PS_PARKINGSPACE_GRID_NAME`,`PS_PARKINGSPACE_GRID_TABLE_NAME` FROM `ps_parkingspace_grid` WHERE `PS_PARKINGSPACE_GRID_ID` = " . $_SESSION['PS_GRID_ID'];
			$insertDetailsDBRReturnResult = mysql_query( $qry_insertTableNameQuery, $conn );

			if(mysql_num_rows($insertDetailsDBRReturnResult) != 1){
				die('Something went wrong : ' . mysql_error());
			}
			
			while($insertDetailsDBRow = mysql_fetch_assoc($insertDetailsDBRReturnResult)){
				$gridTableName = $insertDetailsDBRow['PS_PARKINGSPACE_GRID_TABLE_NAME'];
			}

			$qry_insertDetailsQuery = "insert into $gridTableName ";

			foreach ($_POST as $param_name => $param_value) {
				$qry_insertDetailsColumns[] = str_replace("tpass_","",str_replace("elmName_", "", $param_name));
				if(substr($param_value, 0, strlen("tpass_")) == "tpass_")
					$qry_insertDetailsValues[] = "'" . strrev(password_hash($param_value, PASSWORD_DEFAULT)) . "'";
				else
					$qry_insertDetailsValues[] = "'" . $param_value . "'";
			}

			$qry_insertDetailsQuery	= $qry_insertDetailsQuery . "(" . implode(",", $qry_insertDetailsColumns) . ")" . " values (" . implode(",", $qry_insertDetailsValues) . ")" ;

			//echo $qry_insertDetailsQuery;

			if(mysql_query($qry_insertDetailsQuery) == TRUE){
			//if(true){
				$addDetailsStatus = '<div  style="margin-bottom: 0px; margin-top: 12px" class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						  <strong>SUCCESS!</strong> Added Successfully!
						</div><script>$(".alert").fadeTo(2000, 500).slideUp(500, function(){
						    $("#success-alert").alert("close");
						});</script>';
			}else{
				$addDetailsStatus =  '<div style="margin-bottom: 0px; margin-top: 12px"x class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error: </strong> ' . mysql_error() . '</div>';
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
							echo str_rot13($_GET['gnm']); 
							?>
							<span id="spnEditDetails_<?php echo $gridId; ?>"  name="spnEditDetails_<?php echo $_GET['gnm']; ?>" class="spnDetails_class spnEditDetails_class spn_action_class"><span class="glyphicon glyphicon glyphicon-th-list"></span></span>
					</h3>
					<hr/>
					<form role="form" name="frm_AddDetails" action="" method="post">
						<div class="form-group">
							<?php
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
		    						<label style="font-family:Cambria; color: #337ab7" for="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>"><?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?></label>
		    							<span style="color:brown"><?php echo $isRequiredLabel; ?></span>
		    						<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "TEXT"){
		    						?>
			    						<input <?php 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH'] != -1)
			    								echo "minlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH']; 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'] != -1)
			    								echo "maxlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'];
			    							?> type="text" pattern="[a-zA-Z]+" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>" />
		    						<?php
		    							}
									?>

									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "TPASS"){
		    								include("ps_fnc.php");
		    								$tPassValue = getStrRandom(rand(9,15));
		    						?>
			    						<input <?php 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH'] != -1)
			    								echo "minlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MIN_LENGTH']; 
			    							if($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'] != -1)
			    								echo "maxlength=" . $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_MAX_LENGTH'];
			    							?> type="text" pattern="[a-zA-Z0-9-?%$#@]+" class="form-control" id="tpass_elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="tpass_elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> value="<?php echo $tPassValue; ?>" />
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
			    							?> type="email" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>" />
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
			    							?> type="number" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>" />
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
			    							?> type="text"  oninvalid="setCustomValidity('<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_ERROR_MSG'] ?>')" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>" />
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
			    							?> type="text" pattern="[a-zA-Z0-9]+" oninvalid="setCustomValidity('<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_ERROR_MSG'] ?>')" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>" />
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
			    							?> rows="4s" class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>"></textarea>
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
			    									<option value="<?php echo $tHour; ?>"><?php echo $tHour; ?></option>
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
			    									<option value="<?php echo $tMinute; ?>"><?php echo $tMinute; ?></option>
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
													echo "<option value='" . $optDetailsDBRow['value'] ."'>" . explode(";",$optDetailsDBRow['text'])[1] . "</option>";
												}
			    							?>
			    						</select>
		    						<?php
		    							}
		    						?>

		    						<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "STOPT"){
		    						?>
			    						<select class=" state-opt-class form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>">
			    							<option value='-1'>Select Country first</option>
			    						</select>
		    						<?php
		    							}
		    						?>

		    						<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "CITYOPT"){
		    						?>
			    						<select class=" city-opt-class form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>">
			    							<option value='-1'>Select State first</option>
			    						</select>
		    						<?php
		    							}
		    						?>

									<?php
		    							if($addDetailsDBRow['INPUT_RESTRICTION_CODE'] == "OPT"){
		    						?>
			    						<select class="form-control" id="elmId_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" name="elmName_<?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_NAME']; ?>" <?php echo $isRequired; ?> <?php echo $isEnabled; ?> placeholder="Enter <?php echo $addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_HEADER']; ?>">
			    							<?php
			    								$optQueryResult = mysql_query($addDetailsDBRow['PS_PARKINGSPACE_GRID_COLUMN_QUERY'], $conn);

			    								if(! $optQueryResult ) {
													die('Could not select option details: ' . mysql_error());
												}

												if(mysql_num_rows($optQueryResult) == 0){
													die('Something went wrong : ' . mysql_error());
												}

												while($optDetailsDBRow = mysql_fetch_assoc($optQueryResult)){
													echo "<option value='" . $optDetailsDBRow['value'] ."'>" . $optDetailsDBRow['text']. "</option>";
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
									<input type="submit" class="btn btn-primary btnSimple" value="ADD"</input>		
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