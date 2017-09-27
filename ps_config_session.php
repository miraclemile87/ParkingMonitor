<?php
   $dbhost = 'mysql://mysql:3306/';
   $dbuser = 'adminBlSbkcT';
   $dbpass = 'Lw3gBDAED8pG';
   $dbname = 'ps_parkingspace';

   $conn = mysql_connect($dbhost, $dbuser, $dbpass);

	if(! $conn ) {
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($dbname);

	//session_start();
?>