$(document).ready(function(){
	$(".logo_container_class").click(function(){
		//alert($(this).attr('id').replace("div_id_",""));

		var refId=$(this).attr('id').replace("div_id_","");
		var refIdValue = false;

		var actionPg = "psViewDetail.php";

		if($(this).children(".initiator-class").length){
			var refIdElemClass = $(this).children(".initiator-class");
			var elementClass = refIdElemClass.attr('class').split(" ");
			//alert(elementClass);
			$.each(elementClass, function(indx, item){
				if(item.indexOf("rid-class_") != -1){
					value = item.replace("rid-class_","");
					refIdValue = true;
					refId=value;
					actionPg = "psAddDetails.php"
				}
			});
				
		}

		//alert(refIdValue);

		$("#tempDiv").html("<form id='frmSubmitForm' action="+actionPg+"><input type='hidden' name='gid' value='" + refId + "'/><input type='hidden' name='gnm' value='" + $(this).attr('name').replace("div_name_","") + "'/><input type='hidden' name='rid' value='" + refIdValue + "'/></form>");

		$("#frmSubmitForm").submit();
	});
});