<?php	
	if(isset($_SESSION)){
		echo "already started";
		//echo session_status();
	}
	else{
		session_start();
		//echo "starting session";
		//echo session_status();
	}

//echo "-------";
	//echo $_SESSION['login_user'];
	
	if(!isset($_SESSION['LOGIN_USER'])){
		header("location:index.php");
	}
?>