<?php
	include("ps_config_session.php");
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
			<a class="navbar-brand header-class" href="index.php" style="font-size: 24px">Parking <span class="glyphicon glyphicon-road"></span> Space</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<!--<li><a href="#">Help</a></li>-->
			<!--<ul class="nav nav-pills navbar-right">-->
				<!-- TD: active not working? -->
				<?php
					$qry_fetchMenu="SELECT `PS_PARKINGSPACE_GRID_ID`, `PS_PARKINGSPACE_GRID_MENU_LABEL` , `PS_PARKINGSPACE_GRID_IS_LEAF` FROM `ps_parkingspace_grid` where `PS_PARKINGSPACE_GRID_MENU_LEVEL` = 0";
					$menuQueryResult = mysql_query( $qry_fetchMenu, $conn );

					if(! $menuQueryResult ) {
						die('Could not select menu details: ' . mysql_error());
					}

					if(mysql_num_rows($menuQueryResult) == 0){
						die('Something went wrong : ' . mysql_error());
					}

					while($menuDetailsDBRow = mysql_fetch_assoc($menuQueryResult)){
						if($menuDetailsDBRow['PS_PARKINGSPACE_GRID_IS_LEAF'] == 1){
						?>
							<li><a href="psAddDetails.php?gid=<?php echo str_rot13($menuDetailsDBRow['PS_PARKINGSPACE_GRID_ID']); ?>&gnm=<?php echo str_rot13("Add " . $menuDetailsDBRow['PS_PARKINGSPACE_GRID_MENU_LABEL']); ?>"><?php echo $menuDetailsDBRow['PS_PARKINGSPACE_GRID_MENU_LABEL']; ?></a></li>
						<?php
							}else{
							?>
								<li class="dropdown ">
									<a href="#" id="submenuDD_<?php echo $menuDetailsDBRow['PS_PARKINGSPACE_GRID_ID']; ?>" data-toggle="dropdown" class="dropdown-toggle" role="button">
										<?php echo $menuDetailsDBRow['PS_PARKINGSPACE_GRID_MENU_LABEL']; ?> 
										<b class="caret"></b>
									</a>
				            		<ul role="menu" class="dropdown-menu" aria-labelledby="drop1">
			                			<?php
				                			$qry_fetchSubMenu="SELECT `PS_PARKINGSPACE_GRID_ID`, `PS_PARKINGSPACE_GRID_MENU_LABEL` , `PS_PARKINGSPACE_GRID_IS_LEAF` FROM `ps_parkingspace_grid` where `PS_PARKINGSPACE_GRID_MENU_PARENT_ID` = " . $menuDetailsDBRow['PS_PARKINGSPACE_GRID_ID'];
											
											$subMenuQueryResult = mysql_query( $qry_fetchSubMenu, $conn );

											if(! $subMenuQueryResult ) {
												die('Could not select menu details: ' . mysql_error());
											}

											if(mysql_num_rows($subMenuQueryResult) == 0){
												die('No sub menus : ' . mysql_error());
											}

											while($subMenuDetailsDBRow = mysql_fetch_assoc($subMenuQueryResult)){
												?>
												<li role="presentation">
													<a href="psAddDetails.php?gid=<?php echo str_rot13($subMenuDetailsDBRow['PS_PARKINGSPACE_GRID_ID']); ?>&gnm=<?php echo str_rot13("Link " . $subMenuDetailsDBRow['PS_PARKINGSPACE_GRID_MENU_LABEL']); ?>" style="color: #2a637d" role="menuitem"><?php echo $subMenuDetailsDBRow['PS_PARKINGSPACE_GRID_MENU_LABEL']; ?>
													</a>
												</li>
												<?php
											}	
			                			?>
				            		</ul>
			        			</li>
			        		<?php
								}
						}
					?>
				
				<li><a href="#">Help</a></li>
				<li>
					<a id="a_login" class="btnSimple" href="#" style="color: white; margin-right: 6px; margin-left: 6px">LOGIN</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
<script>
	$(document).ready(function(){
		$(".main-head-country-opt-class").msDropdown();
	});
</script>