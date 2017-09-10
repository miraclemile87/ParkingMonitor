<?php
	include("ps_config_session.php");
	session_start();
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
		<title>Parking Space</title>
		<script src="lib/jquery-1.12.0.min.js"></script>
		<script src="js/msdropdown/jquery.dd.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="css/msdropdown/dd.css" />
		<link rel="stylesheet" type="text/css" href="css/msdropdown/skin2.css" />
		<link rel="stylesheet" type="text/css" href="css/msdropdown/flags.css" />
		<link rel="stylesheet" href="lib/bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="lib/bootstrap-3.3.6-dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/main.css">
		<script src="lib/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
		<script src="js/main.js"></script>
	</head>
	<body class="fluid-container body-class">
		<?php
			include("ps_main_header.php");
		?>
		<div id="div_mainBody">
			<div class="row rowClass">
				<?php
					$qry_fetchMenu="SELECT `PS_PARKINGSPACE_GRID_ID`, `PS_PARKINGSPACE_GRID_REF_ID`, `PS_PARKINGSPACE_GRID_REF_ID_INITIATOR`, `PS_PARKINGSPACE_GRID_MENU_LABEL`, `PS_PARKINGSPACE_GRID_GLYPH_1`, `PS_PARKINGSPACE_GRID_GLYPH_2`,	`PS_PARKINGSPACE_GRID_DESCRIPTION`, `PS_PARKINGSPACE_GRID_IS_LEAF`, `PS_PARKINGSPACE_GRID_BG`, `PS_PARKINGSPACE_GRID_FG` FROM `ps_parkingspace_grid` where `PS_PARKINGSPACE_GRID_MENU_LEVEL` = 1";
					$menuQueryResult = mysql_query( $qry_fetchMenu, $conn );

					if(! $menuQueryResult ) {
						die('Could not select menu details: ' . mysql_error());
					}

					if(mysql_num_rows($menuQueryResult) == 0){
						die('Something went wrong : ' . mysql_error());
					}

					while($menuDetailsDBRow = mysql_fetch_assoc($menuQueryResult)){
				?>
				<div class="col-md-4 logo_container_class" name= "div_name_<?php echo str_rot13($menuDetailsDBRow["PS_PARKINGSPACE_GRID_MENU_LABEL"]); ?>" id="div_id_<?php echo $menuDetailsDBRow["PS_PARKINGSPACE_GRID_ID"]; ?>">
					<div class="logo-content <?php if($menuDetailsDBRow['PS_PARKINGSPACE_GRID_REF_ID_INITIATOR'] == 1){echo "initiator-class rid-class_" . $menuDetailsDBRow['PS_PARKINGSPACE_GRID_REF_ID'];}?>">
				      <span class="logo-class <?php echo $menuDetailsDBRow["PS_PARKINGSPACE_GRID_GLYPH_1"]; ?>" 
				      	<?php if (!empty($menuDetailsDBRow['PS_PARKINGSPACE_GRID_BG'])) echo " style='color:" . $menuDetailsDBRow['PS_PARKINGSPACE_GRID_BG'] . "'"; 
				      	?>
				      	></span>
				      <?php
				      	if(!empty($menuDetailsDBRow["PS_PARKINGSPACE_GRID_GLYPH_2"])){
				      		?>
				      		<span class="logo-class <?php echo $menuDetailsDBRow["PS_PARKINGSPACE_GRID_GLYPH_2"]; ?>"></span>
				      		<?php
				      	}
				      ?>
				      <h3 class="logo-title"><?php echo $menuDetailsDBRow["PS_PARKINGSPACE_GRID_MENU_LABEL"]; ?></h3>
				      <p class="logo-description"><?php echo $menuDetailsDBRow["PS_PARKINGSPACE_GRID_DESCRIPTION"]; ?></p>
				     </div>
			    </div>
			    <?php
					}
				?>	
			</div>
		</div>
		<div id="tempDiv">
		</div>
		<?php
			include("psFooter.php");
		?>
	</body>
</html>
</html>