<?php
	include("ps_config_session.php");
	//session_start();
?>
<nav class="navbar">
	<div class="container-fluid well well-sm" style="box-shadow: 0 3px 3px 0 rgba(50,50,50,0.4);background:white">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar" style="background-color: #2a637d"></span>
            	<span class="icon-bar" style="background-color: #2a637d"></span>
            	<span class="icon-bar" style="background-color: #2a637d"></span>
			</button>
			<a class="navbar-brand header-class" href="dashboard.php" style="font-size: 24px">Parking <span class="glyphicon glyphicon-road"></span> Space</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<!--<li><a href="#">Help</a></li>-->
			<!--<ul class="nav nav-pills navbar-right">-->
				<!-- TD: active not working? -->
				<li><a href="dashboard.php">DASHBOARD</a></li>
				<?php
					//echo 'hi ' . $_SESSION["USER_ROLE"];
					if(isset($_SESSION["USER_ROLE"])){
						//echo $_SESSION["USER_ROLE"];
						if($_SESSION["USER_ROLE"] == 1 or $_SESSION["USER_ROLE"] == 2){
							?>
							<li><a href="accdet.php">MY ACCOUNT</a></li>
							<?php		
						}
					}
				?>
				<li><a href="#">HELP</a></li>
				<li>
				<?php if(isset($_SESSION["USER_ROLE"])){
					?>
					<a id="a_login" class="btnSimple" href="logoutsession.php" style="color: white; margin-right: 6px; margin-left: 6px; font-size: 14px"><span class="glyphicon glyphicon-log-out"></span> LOGOUT</a>
					<?php
					}else{
					?>
					<a id="a_login" class="btnSimple" href="index.php" style="color: white; margin-right: 6px; margin-left: 6px; font-size: 14px"><span class="glyphicon glyphicon-log-in"></span> LOGIN</a>
					<?php
					}
				?>
				</li>
			</ul>
		</div>
	</div>
</nav>
<script>
	/*$(document).ready(function(){
		$(".main-head-country-opt-class").msDropdown();
	});*/
</script>