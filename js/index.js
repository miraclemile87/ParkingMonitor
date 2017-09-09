$(document).ready(function(){

	$("#txtLogInUserName").focus();

	$("#btnDashboard").click(function(){
		window.location="dashboard.php";
	});

	if($(".change_password").length == 1){
		$(".password-class").focus();
	}

	$(".password-class, .change_password, .repeat_password").bind('cut copy paste', function (e) {
        e.preventDefault();
    });
   
    $(".password-class, .change_password, .repeat_password").on("contextmenu",function(e){
        return false;
    });

	$("#btn_submit").attr("disabled", "true");

	$(".password-class, .change_password, .repeat_password").bind("blur keypress keydown keyup click", function(){
		if($(".change_password").length == 1){
			if($(".password-class").val().length < 6 || $(".change_password").val().length < 6 || $(".repeat_password").val().length < 6){
				$("#btn_submit").attr("disabled", "true");			
			}
			else
				$("#btn_submit").removeAttr("disabled");
		}else{
			if($(".password-class").val().length < 6)
				$("#btn_submit").attr("disabled", "true");			
			else
				$("#btn_submit").removeAttr("disabled");
		}
	});
	

	$(".change_password").bind("blur keypress keydown keyup click", function(){
		if($(".repeat_password").val() != null && $(".repeat_password").val() != ""){
			check_changed_password();
		}
	});

	$(".repeat_password").bind("blur keypress keydown keyup click", function(){
		if($(".change_password").val() != null && $(".change_password").val() != ""){
			check_changed_password();
		}
	});

	$("#frm_LogIn").submit(function(){
		if($(".change_password").length == 1){
			if($(".repeat_password").length == 1){
				check_changed_password();
			}
		}
	});

	function check_changed_password(){
		if($(".change_password").val() != $(".repeat_password").val()){
			console.log($(".change_password").val() + " and " +  $(".repeat_password").val());
			$("#div_password_match_msg").html("<span style='color:red'>Passwords do not match</span>");
			if($(".change_password").val().length <6 ){
				$("#btn_submit").attr("disabled", "true");
				return false;
			}else{
				if($(".repeat_password").val().length <6){
					$("#btn_submit").attr("disabled", "true");
					return false;
				}else{
					//$("#div_password_match_msg").html("<span style='color:red'>Passwords do not match</span>");
					$("#btn_submit").attr("disabled", "true");
					return false;
				}	
			}
		}else{
			if($(".change_password").val().length < 6 || $(".repeat_password").val().length < 6){
				$("#btn_submit").attr("disabled", "true");
				return false;
			}else{
				$("#btn_submit").removeAttr("disabled");
				$("#div_password_match_msg").html("");
				return true;
			}
		}
	}


});