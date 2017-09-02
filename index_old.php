<?php
	include("ps_config_session.php");
	$error = "";
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$myusername = $_POST['txtLogInUserName'];
		$mypassword_orig = $_POST['txtLogInPassword'];
		
		$mypassword = strrev(password_hash($mypassword, PASSWORD_DEFAULT));
		
		$sql = "SELECT user_id, user_role, user_given_name FROM ps_parking_space_users WHERE user_name = '$myusername' and user_password = '$mypassword'";
		$sql = "SELECT user_id, user_role, user_given_name FROM ps_parking_space_users WHERE user_name = '$myusername' and user_password = '$mypassword'";

		mysql_select_db($dbname);
		$retval = mysql_query( $sql, $conn );

		if(! $retval ) {
			die('Could not select parking spcae users: ' . mysql_error());
		}

		$row = mysql_fetch_array($retval,MYSQL_NUM);
		$count = mysql_num_rows($retval);

		if($count == 1) {
			session_start();
			$_SESSION['LOGIN_USER'] = $myusername;
			$_SESSION['USER_GIVE_NAME'] = $row[2];
			$_SESSION['USER_ROLE'] = $row[1];
			$_SESSION['USER_ID'] = $row[0];
			$_SESSION['USER_NAME'] = $myusername;
			header("location: parkingspaces.php");
		}else {
			$error = "**Invalid Login or Password";
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
		<link rel="stylesheet" href="css/main.css">
		<script src="lib/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
	</head>
	<body class="fluid-container">
		<?php
			include("ps_main_header.php");
		?>
		<div id="div_mainBody">
			<div class="row">
				<div class="col-sm-2"></div>
				<div class="col-sm-4"></div>
				<div class="col-sm-4" id="div_loginPanel">
					<h3 id="h1_loginHeader">Login to your account</h3>
					<hr/>
					<form role="form" name="frm_LogIn" action="" method="post">
    					<div class="form-group">
    						<label style="font-family:Cambria; color: #337ab7" for="txtLogInUserName">Username: </label>
    						<input type="text" class="form-control" id="txtLogInUserName" name="txtLogInUserName" required placeholder="Enter Username" />
    					</div>
    					<div class="form-group">
    						<label style="font-family:Cambria; color: #337ab7" for="txtLogInPassword">Password: </label>
    						<input type="password" class="form-control" id="txtLogInPassword" name="txtLogInPassword" required placeholder="Enter Password"></input>
    					</div>
						<label id="lblError" style="font-family:Cambria; color:red"><?php echo "$error" ?></label><br/>
						<div style="text-align:center" id="divButtons">
							<input type="submit" class="btn btn-primary btnSimple" value="LOGIN"</input>
							<input id="btn_resetLogin" style="margin-left:2%" type="reset" value="RESET" class="btn btn-warning"></input>
						</div>
    				</form>
				</div>
				<div class="col-sm-2"></div>
			</div>
		</div>
	</body>
</html>
</html>