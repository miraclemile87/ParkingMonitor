$("document").ready(function(){
	$("#selCountry").change(function(){
		countryVal=$(this).val();
		//alert(countryVal + " or " + $(this).text());
		$.ajax({
			url : "ps_parkingData.php",
			type : "POST",
			data: "CNTRY="+countryVal,
			success : function(htmldata){
				$("#selCompany").html(htmldata);
			},
			error : function(data) {
				console.log(data);
			}
		});
	});
});