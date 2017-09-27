$("document").ready(function(){
	$("#selCountry").change(function(){
		countryVal=$(this).val();
		//alert(countryVal + " or " + $(this).text());
		/*$.ajax({
			url : "ps_parkingData.php",
			type : "POST",
			data: "CNTRY="+countryVal,
			success : function(htmldata){
				$("#selCompany").html(htmldata);
			},
			error : function(data) {
				console.log(data);
			},
			async: false
		});*/


		var xmlhttp;
		var url = "ps_parkingData.php";
		var dataParam = "CNTRY="+countryVal;

		//alert(dataParam);

		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 ) {
				$("#selCompany").html(xmlhttp.responseText);
			}else
				console.log(xmlhttp.responseText);
		}

		xmlhttp.open("POST",url,true);
		xmlhttp.responseType("text");
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send(dataParam);	

	});
});