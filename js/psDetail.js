$(document).ready(function(){
	$(".spnAddDetails_class").click(function(){

		$("#tempDiv").html("<form id='frmSubmitForm' action='psAddDetails.php'><input type='hidden' name='gid' value='" + $(this).attr('id').replace("spnAddDetails_","") + "'/><input type='hidden' name='gnm' value='" + $(this).attr('name').replace("spnAddDetails_","") + "'/></form>");

		$("#frmSubmitForm").submit();
	});

	$(".spnEditDetails_class").click(function(){
		//alert($(this).attr('id').replace("spnEditDetails_",""));

		$("#tempDiv").html("<form id='frmSubmitForm' action='psViewDetail.php'><input type='hidden' name='gid' value='" + $(this).attr('id').replace("spnEditDetails_","") + "'/><input type='hidden' name='gnm' value='" + $(this).attr('name').replace("spnEditDetails_","") + "'/></form>");
		$("#frmSubmitForm").submit();
	});

	$(".country-opt-class").change(function(){
		countryVal=$(this).val();
		$.ajax({
			url : "psStateData.php",
			type : "POST",
			data: "CNTRY="+countryVal,
			success : function(htmldata){
				//console.log(htmldata);
				$(".state-opt-class").html(htmldata);
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