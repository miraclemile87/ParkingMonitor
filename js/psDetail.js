$(document).ready(function(){
	$(".spnAddDetails_class").click(function(){

		$("#tempDiv").html("<form id='frmSubmitForm' action='psAddDetails.php'><input type='hidden' name='gid' value='" + $(this).attr('id').replace("spnAddDetails_","") + "'/><input type='hidden' name='gnm' value='" + $(this).attr('name').replace("spnAddDetails_","") + "'/></form>");

		$("#frmSubmitForm").submit();
	});

	if($(".country-opt-class").val() != -1 && $(".country-opt-class").val() != ""){
		$(".country-opt-class").trigger("change");
		alert($(".country-opt-class").val());
		//$(".country-opt-class").val($(".country-opt-class").val()).change();
	}

	function updateState(){
		$(".state-class-has-value").each(function(){
			alert();
			var elementClass = $(this).attr('class').split(" ");
			alert(elementClass);
			$.each(elementClass, function(indx, item){
				if(item.indexOf("state-class-value") != -1){
					value = item.replace("state-class-value-","");
					//alert($(this).children("option [value=" + value + "]").length);
					$(this).children("option [value=" + value + "]").attr("selected", "selected");
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
		alert();
		countryVal=$(this).val();
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
	});

	$(".state-opt-class").change(function(){
		stateVal=$(this).val();
		$.ajax({
			url : "psCityData.php",
			type : "POST",
			data: "STATE=" + stateVal,
			success : function(htmldata){
				//console.log(htmldata);
				$(".city-opt-class").html(htmldata);
			},
			error : function(data) {
				//console.log(data);
			}
		});
	});
});