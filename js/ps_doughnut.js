$(document).ready(function() {

	var backgroundColorArray=["#76C175", "#46BFBD", "#FDB45C", "#08c"];
	var borderColorArray=["#76C175", "#46BFBD", "#FDB45C", "#08c"];

	var bookedColor = "#F7464A ";
	var commonBookedSlots = 0;

	var titleText = "";

	var commonTblText="";
	var commonTblTextF="";

	function getAddButton(uniqueId){
		var addButtonText='<button id="' + uniqueId + '" type="button" class="addButtonClass btn btn-success">';
	    addButtonText+='<span class="glyphicon glyphicon-plus-sign"></span>';
	    addButtonText+='</button>';

	    return addButtonText;
	}

	function getDeleteButton(uniqueId){
    	var delButtonText='<button id="' + uniqueId + '" type="button" class="delButtonClass btn btn-danger">';
    	delButtonText+='<span class="glyphicon glyphicon-minus-sign"></span>';
    	delButtonText+='</button>';

    	return delButtonText;
	}

	$("#btnDoughnutButton").click(function(){
		//$("#selCountry").val();

		getDoughnut();
		//getDoughnutF();
	});

	function getDoughnut(){
		$.ajax({
			url : "psDoughnutData.php",
			type : "GET",
			success : function(data){
				//console.log(data);

				data = [{"company_ID":"1","building_ID":"1","BUILDING_NO":"B01","COMPANY_NAME":"Bumble Bee","COMPANY_LOCATION":"Pune, Maharashtra, India","COMPANY_LANDMARK":"","COMMON_PARKINGS_TOTAL":"100","COMMON_PARKINGS_AVAILABLE":"100","COMMON_PARKINGS_BOOKED":"0","FEMALE_PARKINGS_TOTAL":"0","FEMALE_PARKINGS_AVAILABLE":"0","FEMALE_PARKINGS_BOOKED":"0"},{"company_ID":"1","building_ID":"2","BUILDING_NO":"B02","COMPANY_NAME":"Bumble Bee","COMPANY_LOCATION":"Pune, Maharashtra, India","COMPANY_LANDMARK":"","COMMON_PARKINGS_TOTAL":"100","COMMON_PARKINGS_AVAILABLE":"100","COMMON_PARKINGS_BOOKED":"0","FEMALE_PARKINGS_TOTAL":"30","FEMALE_PARKINGS_AVAILABLE":"30","FEMALE_PARKINGS_BOOKED":"0"},{"company_ID":"1","building_ID":"3","BUILDING_NO":"B03","COMPANY_NAME":"Bumble Bee","COMPANY_LOCATION":"Pune, Maharashtra, India","COMPANY_LANDMARK":"","COMMON_PARKINGS_TOTAL":"120","COMMON_PARKINGS_AVAILABLE":"120","COMMON_PARKINGS_BOOKED":"0","FEMALE_PARKINGS_TOTAL":"40","FEMALE_PARKINGS_AVAILABLE":"40","FEMALE_PARKINGS_BOOKED":"0"},{"company_ID":"1","building_ID":"4","BUILDING_NO":"B04","COMPANY_NAME":"Bumble Bee","COMPANY_LOCATION":"Pune, Maharashtra, India","COMPANY_LANDMARK":"","COMMON_PARKINGS_TOTAL":"50","COMMON_PARKINGS_AVAILABLE":"50","COMMON_PARKINGS_BOOKED":"0","FEMALE_PARKINGS_TOTAL":"25","FEMALE_PARKINGS_AVAILABLE":"25","FEMALE_PARKINGS_BOOKED":"0"}];

				var dataLength = data.length;
				
				//alert(dataLength);
				var doughnutContextArray = [];
				//var doughnutLabel = ["Available Common Parking", "Female Parking"];
				//var doughnutLabel = [];
				var doughnutData = [];
				var doughnutOption = [];

				var labelText = [];
				var labelTextF = [];

				var commOn_DatasetValues = [];
				var female_DatasetValues = [];

				var backgroundColorValues = [];
				var backgroundColorValuesF = [];

				var borderColorValues = [];
				var borderColorValuesF = [];

				var borderWidthValues = [];
				var borderWidthValuesF = [];

				if(dataLength == 0){
				}else{
					titleText = data[0].BUILDING_NO + " - " + data[0].COMPANY_NAME + ", " + data[0].COMPANY_LOCATION + ", " + data[0].COMPANY_LANDMARK;
					if(dataLength == 1){

					}else{
						for (var itr = 0; itr < dataLength; itr++) {

							commonBookedSlots+=data[itr].COMMON_PARKINGS_BOOKED;

							doughnutContextArray.push(data[itr].BUILDING_NO);
							//$("#doughnutCanvasContainer").append('<canvas id="' + data[itr].BUILDING_NO + '" width="400" height="400"></canvas>');

							labelText.push("Building - " + data[itr].BUILDING_NO);
							createTable(data[itr].BUILDING_NO, data[itr].COMMON_PARKINGS_TOTAL, data[itr].COMMON_PARKINGS_AVAILABLE, data[itr].COMMON_PARKINGS_BOOKED, data[itr].company_ID + "_" + data[itr].building_ID);
							commOn_DatasetValues.push(data[itr].COMMON_PARKINGS_AVAILABLE);

							if(data[itr].FEMALE_PARKINGS_TOTAL != 0){
								createTableF(data[itr].BUILDING_NO, data[itr].FEMALE_PARKINGS_TOTAL, data[itr].FEMALE_PARKINGS_AVAILABLE, data[itr].FEMALE_PARKINGS_BOOKED, data[itr].company_ID + "_" + data[itr].building_ID);
								labelTextF.push("Building - " + data[itr].BUILDING_NO);
								female_DatasetValues.push(data[itr].FEMALE_PARKINGS_AVAILABLE);
								backgroundColorValuesF.push(backgroundColorArray[itr]);
								borderColorValuesF.push(borderColorArray[itr]);
								borderWidthValuesF.push(1);
							}

							backgroundColorValues.push(backgroundColorArray[itr]);
							borderColorValues.push(borderColorArray[itr]);
							borderWidthValues.push(1);
						}

						//labelText.push("Total Booked");
						//commOn_DatasetValues.push(commonBookedSlots);
						//female_DatasetValues.push(data[itr].FEMALE_PARKINGS_AVAILABLE);
						//console.log(backgroundColorArray[itr]);
						backgroundColorValues.push[bookedColor];
						borderColorValues.push[bookedColor];
						borderWidthValues.push(1);
					}
				}

				var tmpDoughnutData = {
					//labels : doughnutLabel,
					labels : labelText,
					datasets : [
						{
							label : "ABC",//data[itr].BUILDING_NO + "<br/>" + data[itr].COMPANY_NAME + ", " + data[itr].COMPANY_LOCATION + "<br/>" + data[itr].COMPANY_LANDMARK,
							data : commOn_DatasetValues,
							backgroundColor : backgroundColorValues,
			                borderColor : borderColorValues,
			                borderWidth : borderWidthValues
						}
					]
				};

				var tmpDoughnutDataF = {
					//labels : doughnutLabel,
					labels : labelTextF,
					datasets : [
						{
							label : "ABC",//data[itr].BUILDING_NO + "<br/>" + data[itr].COMPANY_NAME + ", " + data[itr].COMPANY_LOCATION + "<br/>" + data[itr].COMPANY_LANDMARK,
							data : female_DatasetValues,
							backgroundColor : backgroundColorValuesF,
			                borderColor : borderColorValuesF,
			                borderWidth : borderWidthValuesF
						}
					]
				};

				var tmpDoughnutOption = {
					title : {
						display : true,
						position : "top",
						text : titleText,
						fontSize : 18,
						fontColor : "#111"
					},
					legend : {
						display : true,
						position : "top"
					}//,
					/*segmentShowStroke : true,
					segmentStrokeColor : "white",
					segmentStrokeWidth : 6*/
				};



				doughnutData.push(tmpDoughnutData);
				//doughnutDataF.push(tmpDoughnutDataF);

				doughnutOption.push(tmpDoughnutOption);		

				//console.log("-----");
				//console.log(doughnutData[0]);
				//console.log(doughnutOption[0]);

				//console.log($("#psParkingCanvas").length);
				//console.log("-----");

				var chart = new Chart( "psParkingCanvas", {
					type : "doughnut",
					data : doughnutData[0],
					options : doughnutOption[0]
				});

				var chart = new Chart( "psParkingCanvasF", {
					type : "doughnut",
					data : tmpDoughnutDataF,
					options : doughnutOption[0]
				});

				drawTable(commonTblText, "doughnutDataTable");
				drawTable(commonTblTextF, "doughnutDataTableF");
			},
			error : function(data) {
				console.log(data);
			}
		});
	}

	function createTable(field1, field2, field3, field4, uniqueIdVal){
		var addButtonText = getAddButton(uniqueIdVal);
		var delButtonText = getDeleteButton(uniqueIdVal);
		//commonTblText+="<tr><td>" + field1 + "</td><td>" + field2 + "</td><td>" + field3 + "</td><td>" + field4 + "</td><td>" + addButtonText + "</td><td>" + delButtonText + "</td></tr>"
		commonTblText+="<tr><td>" + field1 + "</td><td>" + field3 + "</td><td>" + field4 + "</td><td>" + addButtonText + "</td><td>" + delButtonText + "</td></tr>"
	}

	function createTableF(field1, field2, field3, field4, uniqueIdVal){
		var addButtonText = getAddButton(uniqueIdVal);
		var delButtonText = getDeleteButton(uniqueIdVal);
		//commonTblTextF+="<tr><td><span style='color: green' class='glyphicon glyphicon-plus-sign'></td><td>" + field1 + "</td><td>" + field2 + "</td><td>" + field3 + "</td><td>" + field4 + "</td><td><span style='color: red' class='glyphicon glyphicon-minus-sign'></td></tr>"
		commonTblTextF+="<tr><td>" + field1 + "</td><td>" + field3 + "</td><td>" + field4 + "</td><td>" + addButtonText + "</td><td>" + delButtonText + "</td></tr>"
	}

	/*function drawTable(){
		commonTblBodyText="<table class='doughnut-table-class table table-striped'><thead><tr><th></th><th>Building</th><th>Total</th><th>Available</th><th>Booked</th><th></th></tr></thead>";
		commonTblBodyText+="<tbody>";
		commonTblBodyText+=commonTblText;
		commonTblBodyText+="</tbody>";
		commonTblBodyText+="</table>";

		//console.log();

		$("#doughnutDataTable").html(commonTblBodyText);
	}*/

	function drawTable(commonTblTextVal, tblHandler){
		//alert(commonTblTextF);
		commonTblBodyText="<table class='doughnut-table-class table table-striped'><thead><tr><th>Building</th><th>Total</th><th>Booked</th><th></th><th></th></tr></thead>";
		commonTblBodyText+="<tbody>";
		commonTblBodyText+=commonTblTextVal;
		commonTblBodyText+="</tbody>";
		commonTblBodyText+="</table>";

		//console.log();

		$("#" + tblHandler).html(commonTblBodyText);

		$(".addButtonClass").off('click').on('click',function(){
			//alert($(this).attr("id"));
		});

		$(".delButtonClass").off('click').on('click',function(){
			//alert($(this).attr("id"));
		});
	}

});