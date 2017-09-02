<?php	
	if(isset($_SESSION)){
		//echo "already started";
		//echo session_status();
	}
	else{
		session_start();
		//echo "starting session";
		//echo session_status();
	}
	
	if(!isset($_SESSION['login_user'])){
		//header("location:index.php");
		
	}
?>