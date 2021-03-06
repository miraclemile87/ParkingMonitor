$(document).ready(function(){

	$("#spn-uname-availability").click(function(){
		var uname = $(".uname-class").val();
		var urlUserFetch = "psCheckUser.php?uname=" + uname;

		$.ajax({
			url : urlUserFetch,
			type : "GET",
			success : function(htmldata){
				//alert(htmldata);
				if(htmldata == 0)
					$("#spn-uname-availability-message").html("<i style='color:green'>Available</i>");
				else
					$("#spn-uname-availability-message").html("<i style='color:red'>Not Available</i>");
			},
			error : function(data) {
				//console.log(data);
				//alert(data);
			}
		});
	});

	$(".spnAddDetails_class").click(function(){

		$("#tempDiv").html("<form id='frmSubmitForm' action='psAddDetails.php'><input type='hidden' name='gid' value='" + $(this).attr('id').replace("spnAddDetails_","") + "'/><input type='hidden' name='gnm' value='" + $(this).attr('name').replace("spnAddDetails_","") + "'/></form>");

		$("#frmSubmitForm").submit();
	});

	if($(".country-opt-class").val() != -1 && $(".country-opt-class").val() != ""){
		//$(".country-opt-class").trigger("change");
		//alert($(".country-opt-class").val());
		//$(".country-opt-class").val($(".country-opt-class").val()).change();
		onChangeCountry($(".country-opt-class").val());
	}

	$(".spn_delete_class").click(function(){
		var urlDelete = "psDeleteDetails.php?" + $(this).parent("#deleteGridData").attr("class");

		//alert();

		$.ajax({
			url : urlDelete,
			type : "GET",
			success : function(htmldata){

				//alert("in here");

				var msgDescription = '<div  style="margin-bottom: 6px; margin-top: 12px" class="alert alert-info fade in">';
					msgDescription += '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
				  	msgDescription += '<strong>' + htmldata + '</strong> Refreshing ..';
					msgDescription += '</div>';
					msgDescription += '<script>$(".alert").fadeTo(1000, 500).slideUp(500, function(){';
				    msgDescription += '$("#success-alert").alert("close");location.reload();';
					msgDescription += '});</script>';

				//console.log(htmldata);
				//alert($("#div-status-message").length + " and " + msgDescription);
				$("#div-status-message").html(msgDescription);
			},
			error : function(data) {
				//console.log(data);
				//alert(data);
			}
		});
	});

	

	function updateState(){
		$(".state-class-has-value").each(function(){
			//alert();
			var elementClass = $(this).attr('class').split(" ");
			//alert(elementClass);
			$.each(elementClass, function(indx, item){
				if(item.indexOf("state-class-value") != -1){
					value = item.replace("state-class-value-","");
					//alert($(".state-class-has-value").children("option [value=" + value + "]").length);
					//alert(value);
					//$(".state-class-has-value").children("option [value=" + value + "]").attr("selected", "selected");
					$(".state-class-has-value").val(value);
					onChangeState(value);
					return false;
				}
			});
		});
	}

	function updateCity(){
		$(".city-class-has-value").each(function(){
			//alert();
			var elementClass = $(this).attr('class').split(" ");
			//alert(elementClass);
			$.each(elementClass, function(indx, item){
				if(item.indexOf("city-class-value") != -1){
					value = item.replace("city-class-value-","");
					//alert($(".city-class-has-value").children("option [value=" + value + "]").length);
					//salert(value);
					//$(".state-class-has-value").children("option [value=" + value + "]").attr("selected", "selected");
					$(".city-class-has-value").val(value);
					//onChangeState(value);
					return false;
				}
			});
		});
	}

	$(".spnEditDetails_class").click(function(){
		//alert($(this).attr('id').replace("spnEditDetails_",""));

		$("#tempDiv").html("<form id='frmSubmitForm' action='psViewDetail.php'><input type='hidden' name='gid' value='" + $(this).attr('id').replace("spnEditDetails_","") + "'/><input type='hidden' name='gnm' value='" + $(this).attr('name').replace("spnEditDetails_","") + "'/></form>");
		$("#frmSubmitForm").submit();
	});

	$(".country-opt-class").change(function(){
		countryVal=$(this).val();
		onChangeCountry(countryVal);
	});

	function onChangeCountry(countryVal){
		$.ajax({
			url : "psStateData.php",
			type : "POST",
			data: "CNTRY="+countryVal,
			success : function(htmldata){
				//console.log(htmldata);
				$(".state-opt-class").html(htmldata);
				updateState();
			},
			error : function(data) {
				//console.log(data);
			}
		});
	}

	$(".state-opt-class").change(function(){
		stateVal=$(this).val();
		onChangeState(stateVal);		
	});

	function onChangeState(stateVal){
		$.ajax({
			url : "psCityData.php",
			type : "POST",
			data: "STATE=" + stateVal,
			success : function(htmldata){
				//console.log(htmldata);
				$(".city-opt-class").html(htmldata);
				updateCity();
			},
			error : function(data) {
				//console.log(data);
			}
		});
	}
});