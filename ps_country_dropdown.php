<form class="navbar-form navbar-left">
	<select class="main-head-country-opt-class">
		<?php
			$cntryQuery = 'select id value, concat(sortname, ";",name) text from ps_countries';
			$optQueryResult = mysql_query($cntryQuery, $conn);

			if(! $optQueryResult ) {
				die('Could not select option details: ' . mysql_error());
			}

			if(mysql_num_rows($optQueryResult) == 0){
				die('Something went wrong : ' . mysql_error());
			}

			while($optDetailsDBRow = mysql_fetch_assoc($optQueryResult)){
				echo "<option value='" . $optDetailsDBRow['value'] ."' data-image='images/msdropdown/icons/blank.gif' data-imagecss='flag " . explode(";",$optDetailsDBRow['text'])[0] . "' data-title='" . explode(";",$optDetailsDBRow['text'])[1] . "'>" . explode(";",$optDetailsDBRow['text'])[1] . "</option>";
			}
		?>
	</select>
</form>