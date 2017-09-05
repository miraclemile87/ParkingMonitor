<?php
	include("ps_config_session.php");
	
	$error = "";
	$success = "";
	$showChangePassword = "N";
	$globalUserName = "-";

	//echo strrev(password_hash("gautam", PASSWORD_DEFAULT));

	//if(password_verify('gautam', strrev('yyk0vUlGvJyf9QqNCLvt4zkKJcA1k2l.uvKh83feeGuuKgxVXw7O/$01$y2$'))){
	//	echo "true";
	//}
	//else
		//echo "false";

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$myusername = $_POST['txtLogInUserName'];
		$globalUserName = $myusername;
		$mypassword_orig = $_POST['txtLogInPassword'];

		$mypassword = strrev(password_hash($mypassword_orig, PASSWORD_DEFAULT));

		if(isset($_POST['txtLogInChangePassword'])){
			if(isset($_POST['txtLogInRepeatPassword'])){

				if($_POST['txtLogInChangePassword'] != $_POST['txtLogInRepeatPassword']){
					$error = "**2 new passwords do not match";
				}else{
					$sql = "SELECT user_id, user_role, user_given_name, user_password, user_temp_password, case when now() > USER_TEMP_PASSWORD_EXPIRATION_DATE then 1 else 0 end user_temp_password_expired from ps_parkingspace_users WHERE user_name = '" . $myusername. "'";
			
				mysql_select_db($dbname);
				$retval = mysql_query( $sql, $conn );

				if(! $retval ) {
					die('Could not select parking spcae users: ' . mysql_error());
				}

				$row = mysql_fetch_array($retval,MYSQL_NUM);
				$count = mysql_num_rows($retval);

				if($count == 1) {
					if(password_verify($mypassword_orig, strrev($row[4]))){

						$mynewpassword = strrev(password_hash($_POST['txtLogInRepeatPassword'], PASSWORD_DEFAULT));

						$updateUserSql = "update ps_parkingspace_users set user_temp_password = null, USER_TEMP_PASSWORD_EXPIRATION_DATE = null, modification_date = now(), user_password = '" . $mynewpassword . "' where user_name = '" . $myusername. "'";

						mysql_select_db($dbname);
						$updateRetval = mysql_query( $updateUserSql, $conn );

						if(!$updateRetval){
							die ("Password was not changed." . $updateUserSql);
							$error = "Error in changing the password";
						}

						$success = "Password changed successfully!!";
					}else{
						$showChangePassword = "Y";
						$error = "**Current Temporary password doesn't match";	
					}
				}else{
					$showChangePassword = "N";
					$error = "**Current Temporary password doesn't match";
				}
				}
			}
		}else{
		
			/*$sql = "SELECT user_id, user_role, user_given_name, case when user_password = '" . $mypassword . "' then 1 else 2 end user_type FROM ps_parkingspace_users WHERE user_name = '" . $myusername. "' and (user_password = '". $mypassword . "' or user_temp_password = '". $mypassword . "')";*/

			$sql = "SELECT user_id, user_role, user_given_name, user_password, user_temp_password, case when now() > USER_TEMP_PASSWORD_EXPIRATION_DATE then 1 else 0 end user_temp_password_expired from ps_parkingspace_users WHERE user_name = '" . $myusername. "'";
			
			mysql_select_db($dbname);
			$retval = mysql_query( $sql, $conn );

			if(! $retval ) {
				die('Could not select parking spcae users: ' . mysql_error());
			}

			$row = mysql_fetch_array($retval,MYSQL_NUM);
			$count = mysql_num_rows($retval);

			if($count == 1) {
				if($row[3] == null){
					if(password_verify($mypassword_orig, strrev($row[4]))){
						if($row[5] == 1){
							$error = "**Temporary password has expired";
						}else{
							$showChangePassword = "Y";	
						}
					}else{
						$showChangePassword = "N";
						$error = "**Temporary password doesn't match";
					}
				}else{
					if(password_verify($mypassword_orig, strrev($row[3]))){
						session_start();
						$_SESSION['LOGIN_USER'] = $myusername;
						$_SESSION['USER_GIVE_NAME'] = $row[2];
						$_SESSION['USER_ROLE'] = $row[1];
						$_SESSION['USER_ID'] = $row[0];
						$_SESSION['USER_NAME'] = $myusername;
						header("location: dashboard.php");
					}else{
						$showChangePassword = "N";
						$error = "**Invalid Login or Password";
					}
				}
			}else {
				$showChangePassword = "N";
				$error = "**Invalid Login or Password";
			}
		}
	}
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Parking Monitor</title>
		<script src="lib/jquery-1.12.0.min.js"></script>
		<link rel="stylesheet" href="lib/bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="lib/bootstrap-3.3.6-dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/main.css">
		<script src="lib/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
		<script src="js/index.js"></script>
	</head>
	<body class="fluid-container">
		<?php
			include("ps_main_header.php");
		?>
		<div id="div_mainBody">
			<div class="row row-class">
				<div class="col-md-4 col-md-offset-4">
					<div id="div_dashboard">
						<!--<i class="fa fa-dashboard" style="font-size:60px;color:lightblue;text-shadow:2px 2px 4px #000000;"></i>-->
						<button class="btn btn-primary" id="btnDashboard" style="width: 90%; font-size:24px">Parking Dashboard <i class="fa fa-dashboard"></i></button>
						<h5 class="dashboard-header-class"><span  class="line-center">NO LOG IN TO VIEW THE PARKINGS</span></h5>
					</div>
					<div id="div_seperator">
						OR
					</div>
					<div id="div_loginPanel">
						<h3 id="h1_loginHeader">Login</h3>						
						<hr/>
						<form role="form" id="frm_LogIn" name="frm_LogIn" action="" method="post">
	    					<div class="form-group">
	    						<label style="font-family:Cambria; color: #337ab7" for="txtLogInUserName">Username: </label>
	    						<input type="text" class="form-control" id="txtLogInUserName" name="txtLogInUserName" required placeholder="Enter Username" value="<?php if($globalUserName != '-') echo $globalUserName;  ?>"/>
	    					</div>
	    					<div class="form-group">
	    						<label style="font-family:Cambria; color: #337ab7" for="txtLogInPassword">Password (min 6 characters): </label>
	    						<input type="password" class="password-class form-control" id="txtLogInPassword" name="txtLogInPassword" required placeholder="Enter Password"></input>
	    					</div>
	    					<?php 
	    						if($showChangePassword == "Y"){
	    						?>
		    					<div class="form-group">
		    						<label style="font-family:Cambria; color: #337ab7" for="txtLogInChangePassword">Change Password( min 6 characters): </label>
		    						<input type="password" class="change_password form-control" id="txtLogInChangePassword" name="txtLogInChangePassword" required placeholder="Change Password"></input>
		    					</div>
		    					<div class="form-group">
		    						<label style="font-family:Cambria; color: #337ab7" for="txtLogInRepeatPassword">Repeat Password( min 6 characters): </label>
		    						<input type="password" class="repeat_password form-control" id="txtLogInRepeatPassword" name="txtLogInRepeatPassword" required placeholder="Repeat Password"></input>
		    						<div id="div_password_match_msg"></div>
		    					</div>
		    					<?php
		    					}
		    				?>
							<label id="lblError" style="font-family:Cambria; color:red"><?php echo "$error" ?></label><br/>
							<label id="lblError" style="font-family:Cambria; color:green"><?php echo "$success" ?></label><br/>
							<div style="text-align:center" id="divButtons">
								<input id="btn_submit" type="submit" style="margin-left:2%" class="btn btn-primary" value="LOGIN"</input>
								<input id="btn_resetLogin" style="margin-left:2%" type="reset" value="RESET" class="btn btn-warning"></input>
							</div>
	    				</form>
    				</div>
				</div>
				<div class="col-sm-2"></div>
			</div>
		</div>
	</body>
</html>
</html>