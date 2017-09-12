<?php
   $dbhost = 'localhost';
   $dbuser = 'userQC2';
   $dbpass = 'jjqHxbBofcm27ujj';
   $dbname = 'ps_parkingspace';

   $conn = mysql_connect($dbhost, $dbuser, $dbpass);

	if(! $conn ) {
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($dbname);

	//session_start();
?>