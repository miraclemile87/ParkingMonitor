$(document).ready(function(){
	$(".logo_container_class").click(function(){
		//alert($(this).attr('id').replace("div_id_",""));

		$("#tempDiv").html("<form id='frmSubmitForm' action='psViewDetail.php'><input type='hidden' name='gid' value='" + $(this).attr('id').replace("div_id_","") + "'/><input type='hidden' name='gnm' value='" + $(this).attr('name').replace("div_name_","") + "'/></form>");

		$("#frmSubmitForm").submit();
	});
});