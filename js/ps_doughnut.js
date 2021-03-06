$(document).ready(function() {

	var backgroundColorArray=["#76C175", "#46BFBD", "#FDB45C", "#08c"];
	var borderColorArray=["#76C175", "#46BFBD", "#FDB45C", "#08c"];
	var backgroundColorFilledArray = ["#e60000" ,"#ff9999"];

	var bookedColor = "#F7464A ";
	var commonBookedSlots = 0;

	var titleText = "";
	var dateText = "";

	var commonTblText="";
	var commonTblTextF="";

	var mClicked = 0;
	var fClicked = 0;

	var isPMCol = 0;

	var lastClick = 1;

	function checkIfLoadedAgain(){
		if($("#selCountryValue").val() != -99){
			//alert($("#selCountryValue").val());
			$("#selCountry").val($("#selCountryValue").val());

			$.ajax({
				url : "ps_parkingData.php",
				type : "POST",
				data: "CNTRY="+$("#selCountryValue").val(),
				success : function(htmldata){
					$("#selCompany").html(htmldata);
					if($("#selCompanyValue").val() != -99){
						$("#selCompany").val($("#selCompanyValue").val());
						//$("#btnDoughnutRefreshButton").show();
						getDoughnut(((lastClick==1)?"M":"F"));
						if(lastClick==1){
							mClicked = 1;
						}else{
							fClicked = 1;
						}
					}
				},
				error : function(data) {
					console.log(data);
				},
				async: false
			});
		}
	}

	checkIfLoadedAgain();

	function getAddButton(uniqueId){
		var addButtonText='&nbsp;&nbsp;<button id="' + uniqueId + '" type="button" class="addButtonClass btn btn-success">';
	    addButtonText+='<span class="glyphicon glyphicon-plus-sign"></span>';
	    addButtonText+='</button>';

	    return addButtonText;
	}

	function getDeleteButton(uniqueId){
    	var delButtonText='&nbsp;&nbsp;<button id="' + uniqueId + '" type="button" class="delButtonClass btn btn-danger">';
    	delButtonText+='<span class="glyphicon glyphicon-minus-sign"></span>';
    	delButtonText+='</button>';

    	return delButtonText;
	}

	function loadRefresh(){
		//alert();
		$("lClickedValue").val(lastClick);
		$("#frm_Dashboard").submit();
	}


	$("#btnDoughnutButton").attr("disabled", "true");
	if($("#selCountryValue").val() != -99){
		$("#btnDoughnutRefreshButton").show();
	}else{
		$("#btnDoughnutRefreshButton").hide();
		$(".tab-pane:first").addClass("active");
	}

	$(".selectDBClass").change(function(){
		var enableButton = true;
		$(".selectDBClass").each(function(){
			if($(this).val() == "")
				enableButton = false;
		});

		if(enableButton == true){
			$("#btnDoughnutButton").removeAttr("disabled");
			//$("#btnDoughnutRefreshButton").show();
		}
		else{
			$("#btnDoughnutButton").attr("disabled", "true");
			$("#btnDoughnutRefreshButton").hide();
		}
	});

	

	/*$(".nav").children("li").click(function(){
		//$(".tab-pane").toggleClass("active");
		//$(".nav").children("li").toggleClass("active");

		//alert($(this).children("a").attr("href").replace("#",''));

		var genderId = $(this).children("a").attr("href").replace("#",'');
		if(genderId == 1){
			getDoughnut("M");
		}else{
			getDoughnut("F");
		}
	});*/

	$(".mTab").click(function(event){
		
		$(".tab-pane").each(function(){
			$(this).removeClass("active");
			$(".fTab").removeClass("active");
		});
		//alert(lastClick + " and " + mClicked);
		if(lastClick != 1 && mClicked == 1){
			loadRefresh();
			//$(".tab-pane").toggleClass("active");
			//$(".nav").children("li").toggleClass("active");
			//event.stopImmediatePropagation();
		}else{
			getDoughnut("M");
			$(".tab-pane[id=2]").addClass("active");
			//$(".nav").children("li").toggleClass("active");
			$(this).addClass("active");
			lastClick = 1;
			mClicked = 1;
		}
	});

	$(".fTab").click(function(event){

		$(".tab-pane").each(function(){
			$(this).removeClass("active");
			$(".mTab").removeClass("active");
		});

		//alert();
		
		//alert(lastClick + " and " + fClicked);
		if(lastClick != 2 && fClicked == 1){
			loadRefresh();
			//event.stopImmediatePropagation();		
		}else{
			getDoughnut("F");
			$(".tab-pane[id=2]").addClass("active");
			//$(".nav").children("li").toggleClass("active");
			$(this).addClass("active");
			lastClick = 2;
			fClicked = 1;
		}
	});

	/*$(".mTab, .fTab").click(function(){
		//alert();
		
		$(".tab-pane").each(function(){
			alert(1);
			if($(this).hasClass("active")){
				alert(2 + " and " + $(this).attr("id"));
				if($(this).attr("id") == 1){
					//alert(mClicked);
					if(lastClick != $(this).attr("id") && mClicked == 1){
						alert();
					}else{
						getDoughnut("M");
						lastClick = $(this).attr("id");
						mClicked = 1;
					}		
				}
				else{
					if(lastClick != $(this).attr("id") && fClicked == 1){
						alert();
					}else{
						lastClick = $(this).attr("id");
						fClicked = 1;
					}	
				}
			}
		});
	})*/

	$("#btnDoughnutButton").click(function(){
		$("#btnDoughnutButton").attr("disabled", "true");
		$("#btnDoughnutRefreshButton").show();

		$(".tab-pane").each(function(){
			if($(this).hasClass("active")){
				if($(this).attr("id") == 1){
					//alert(mClicked);
					getDoughnut("M");					
				}
				else{
					getDoughnut("F");
				}
			}
		});
		/*$(".tab-pane").toggleClass("active");
		$(".nav").children("li").toggleClass("active");*/
		//$(".tab-pane").toggleClass("active");
		/*$(".nav").children("li").toggleClass("active");*/
			//}
	});

	$("#btnDoughnutRefreshButton").click(function(){
		$(".tab-pane").each(function(){
			if($(this).hasClass("active")){
				if($(this).attr("id") == 1)
					getDoughnut("M");
				else
					getDoughnut("F");
			}
		});
		/*$(".tab-pane").toggleClass("active");
		$(".nav").children("li").toggleClass("active");
		$(".tab-pane").toggleClass("active");
		$(".nav").children("li").toggleClass("active");*/
	});

	function getDoughnut(gender){

		commonTblText="";
		commonTblTextF="";

		$("#psParkingCanvas").empty();
		$("#doughnutDataTable").empty();
		$("#psParkingCanvasF").empty();
		$("#doughnutDataTableF").empty();

		var xmlhttp;

		var urlData = "psDoughnutData.php?cntr=" + $("#selCountry").val() + "&cmpn=" + $("#selCompany").val();
		//alert(urlData);
		$.ajax({
			async: false,
			url : urlData,
			type : "GET",
			success : function(data){
				//alert(data);

				//data = [{"company_ID":"1","building_ID":"1","COMPANY_BUILDING_ID":"1","BUILDING_NO":"B01","COMPANY_NAME":"Bumble Bee","COMPANY_LOCATION":"Pune, Maharashtra, India","COMPANY_LANDMARK":"","COMMON_PARKINGS_TOTAL":"100","COMMON_PARKINGS_AVAILABLE":"90","COMMON_PARKINGS_BOOKED":"10","FEMALE_PARKINGS_TOTAL":"0","FEMALE_PARKINGS_AVAILABLE":"0","FEMALE_PARKINGS_BOOKED":"0"},{"company_ID":"1","building_ID":"2","COMPANY_BUILDING_ID":"2","BUILDING_NO":"B02","COMPANY_NAME":"Bumble Bee","COMPANY_LOCATION":"Pune, Maharashtra, India","COMPANY_LANDMARK":"","COMMON_PARKINGS_TOTAL":"100","COMMON_PARKINGS_AVAILABLE":"50","COMMON_PARKINGS_BOOKED":"50","FEMALE_PARKINGS_TOTAL":"30","FEMALE_PARKINGS_AVAILABLE":"10","FEMALE_PARKINGS_BOOKED":"20"},{"company_ID":"1","building_ID":"3","COMPANY_BUILDING_ID":"3","BUILDING_NO":"B03","COMPANY_NAME":"Bumble Bee","COMPANY_LOCATION":"Pune, Maharashtra, India","COMPANY_LANDMARK":"","COMMON_PARKINGS_TOTAL":"120","COMMON_PARKINGS_AVAILABLE":"50","COMMON_PARKINGS_BOOKED":"70","FEMALE_PARKINGS_TOTAL":"40","FEMALE_PARKINGS_AVAILABLE":"20","FEMALE_PARKINGS_BOOKED":"20"},{"company_ID":"1","building_ID":"4","COMPANY_BUILDING_ID":"4","BUILDING_NO":"B04","COMPANY_NAME":"Bumble Bee","COMPANY_LOCATION":"Pune, Maharashtra, India","COMPANY_LANDMARK":"","COMMON_PARKINGS_TOTAL":"50","COMMON_PARKINGS_AVAILABLE":"30","COMMON_PARKINGS_BOOKED":"20","FEMALE_PARKINGS_TOTAL":"25","FEMALE_PARKINGS_AVAILABLE":"5","FEMALE_PARKINGS_BOOKED":"20"}];

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

				var totalBooked = 0;
				var totalBookedF = 0;

				if(dataLength == 0){
				}else{
					titleText = data[0].COMPANY_NAME + ", " + data[0].COMPANY_LOCATION;
					
					var curDate = new Date();
					dateText = curDate.getDate() + "-" + (curDate.getMonth()+1) + "-" + curDate.getFullYear();
					dateText += " : " + curDate.getHours() + "." + curDate.getMinutes() + "." + curDate.getSeconds();

					$("#panelTitle").html("Dashboard &nbsp;&nbsp; <b style='color: #dff0d8; font-size: 12px'>" + dateText + "</b>");
					//$("#span_dateText").show();

					//titleText += "(&lt;b&gt;" + dateText + "&lt;/b&gt;)";

					if(dataLength == 0){

					}else{
						for (var itr = 0, colorItr = 0; itr < dataLength; itr++, colorItr++) {

							if(gender == "M"){

								commonBookedSlots+=data[itr].COMMON_PARKINGS_BOOKED;

								doughnutContextArray.push(data[itr].BUILDING_NO);
								//$("#doughnutCanvasContainer").append('<canvas id="' + data[itr].BUILDING_NO + '" width="400" height="400"></canvas>');

								labelText.push("Building - " + data[itr].BUILDING_NO);
								createTable(data[itr].BUILDING_NO + " (" + data[itr].COMMON_TIMING + ")", data[itr].COMMON_PARKINGS_TOTAL, data[itr].COMMON_PARKINGS_AVAILABLE, data[itr].COMMON_PARKINGS_BOOKED, data[itr].COMPANY_BUILDING_ID, data[itr].IS_PM);
								commOn_DatasetValues.push(data[itr].COMMON_PARKINGS_AVAILABLE);

								totalBooked+= parseInt(data[itr].COMMON_PARKINGS_BOOKED);

								backgroundColorValues.push(backgroundColorArray[itr]);
								borderColorValues.push(borderColorArray[itr]);
								borderWidthValues.push(1);
							}else{
								if(data[itr].FEMALE_PARKINGS_TOTAL != 0){
									createTableF(data[itr].BUILDING_NO + " (" + data[itr].FEMALE_TIMING + ")", data[itr].FEMALE_PARKINGS_TOTAL, data[itr].FEMALE_PARKINGS_AVAILABLE, data[itr].FEMALE_PARKINGS_BOOKED, data[itr].COMPANY_BUILDING_ID, data[itr].IS_PM);
									labelTextF.push("Building - " + data[itr].BUILDING_NO);
									//labelTextF.push("Booked (Building - " + data[itr].BUILDING_NO + ")");
									
									female_DatasetValues.push(data[itr].FEMALE_PARKINGS_AVAILABLE);
									//female_DatasetValues.push(data[itr].FEMALE_PARKINGS_BOOKED);
									
									backgroundColorValuesF.push(backgroundColorArray[itr]);
									/*if(backgroundColorFilledArray.length <= colorItr){
										colorItr=0;
									}*/
									
									borderColorValuesF.push(borderColorArray[itr]);
									//backgroundColorValuesF.push(backgroundColorFilledArray[colorItr]);
									
									borderWidthValuesF.push(1);
									//borderWidthValuesF.push(1);

									totalBookedF+= parseInt(data[itr].FEMALE_PARKINGS_BOOKED);
								}
							}
							
						}

						//labelText.push("Total Booked");
						//commOn_DatasetValues.push(commonBookedSlots);
						//female_DatasetValues.push(data[itr].FEMALE_PARKINGS_AVAILABLE);
						//console.log(backgroundColorArray[itr]);
						if(gender == "M"){
							commOn_DatasetValues.push(totalBooked);
							labelText.push("Total Booked");
							backgroundColorValues.push(bookedColor);
							backgroundColorValues.push(backgroundColorFilledArray[0]);
							borderColorValues.push(bookedColor);
							borderColorValues.push(backgroundColorFilledArray[0]);
							borderWidthValues.push(1);
							borderWidthValues.push(1);
						}else{
							labelTextF.push("Total Booked");
							female_DatasetValues.push(totalBookedF);
							backgroundColorValuesF.push(backgroundColorFilledArray[0]);
							borderColorValuesF.push(backgroundColorFilledArray[0]);
							borderWidthValuesF.push(1);
						}
					}
				}

				var tmpDoughnutData = {};
				var tmpDoughnutDataF = {};

				if(gender == "M"){
					tmpDoughnutData = {
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
				}else{
					tmpDoughnutDataF = {
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
				}

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

				if(gender == "M"){
					var chart = new Chart( "psParkingCanvas", {
						type : "doughnut",
						data : doughnutData[0],
						options : doughnutOption[0]
					});

					drawTable(commonTblText, "doughnutDataTable");
				}else{
					var chart = new Chart( "psParkingCanvasF", {
						type : "doughnut",
						data : tmpDoughnutDataF,
						options : doughnutOption[0]
					});

					drawTable(commonTblTextF, "doughnutDataTableF");
				}

				$("#div_mainTab").show();
				$(".noDataDisplay").hide();

				 $('html, body').animate({
			        scrollTop: $("#div_dashboardPanel").offset().top
			    }, 500);
			},
			error : function(data) {
				console.log(data);
				//alert(data);
			}
		});
	}

	function createTable(field1, field2, field3, field4, uniqueIdVal, isPM){
		var addButtonText = "";
		if(isPM == "Y"){
			addButtonText = getAddButton(uniqueIdVal);
		}
		var delButtonText = "";
		if(isPM == "Y"){
			delButtonText = getDeleteButton(uniqueIdVal);
		}
		//commonTblText+="<tr><td>" + field1 + "</td><td>" + field2 + "</td><td>" + field3 + "</td><td>" + field4 + "</td><td>" + addButtonText + "</td><td>" + delButtonText + "</td></tr>"
		commonTblText+="<tr><td>" + field1 + "</td><td>" + field3 + addButtonText + "</td><td>" + field4 + delButtonText + "</td>";
		//if($("#inpRoU").val() == 4)
		/*if(isPM == "Y"){
			isPMCol = 1;
			commonTblTextF+="<td>" + addButtonText + "</td><td>" + delButtonText + "</td></tr>";
		}*/
	}

	function createTableF(field1, field2, field3, field4, uniqueIdVal, isPM){
		var addButtonText = "";
		if(isPM == "Y"){
			addButtonText = getAddButton(uniqueIdVal);
		}
		var delButtonText = "";
		if(isPM == "Y"){
			delButtonText = getDeleteButton(uniqueIdVal);
		}
		//commonTblTextF+="<tr><td><span style='color: green' class='glyphicon glyphicon-plus-sign'></td><td>" + field1 + "</td><td>" + field2 + "</td><td>" + field3 + "</td><td>" + field4 + "</td><td><span style='color: red' class='glyphicon glyphicon-minus-sign'></td></tr>"
		commonTblTextF+= "<tr><td>" + field1 + "</td><td>" + field3 + addButtonText + "</td><td>" + field4 + delButtonText + "</td>";
		/*if(isPM == "Y"){
			isPMCol = 1;
			commonTblTextF+="<td>" + addButtonText + "</td><td>" + delButtonText + "</td></tr>";
		}*/
	}

	/*function drawTable(){
		commonTblBodyText="<table class='doughnut-table-class table table-striped'><thead><tr><th></th><th>Building</th><th>Total</th><th>Available</th><th>Booked</th><th></th></tr></thead>";
		commonTblBodyText+="<tbody>";
		commonTblBodyText+=commonTblText;
		commonTblBodyText+="</tbody>";
		commonTblBodyText+="</table>";

		//console.log();

		$("#doughnutDataTable").html(commonTblBodyText);
	}

	function drawTableF(){
		alert(commonTblTextF);
		commonTblBodyText="<table class='doughnut-table-class table table-striped'><thead><tr><th></th><th>Building</th><th>Total</th><th>Available</th><th>Booked</th><th></th></tr></thead>";
		commonTblBodyText+="<tbody>";
		commonTblBodyText+=commonTblTextF;
		commonTblBodyText+="</tbody>";
		commonTblBodyText+="</table>";

		//console.log();

		$("#doughnutDataTableF").html(commonTblBodyText);
	}*/

	function drawTable(commonTblTextVal, tblHandler){
		//alert(commonTblTextF);
		commonTblBodyText="<table class='doughnut-table-class table table-striped table-condensed table-hover'><thead><tr><th>Building</th><th>Available</th><th>Booked</th>";
		if(isPMCol == "Y")
			commonTblBodyText+="<th></th><th></th>";
		commonTblBodyText+="</tr></thead>";
		commonTblBodyText+="<tbody>";
		commonTblBodyText+=commonTblTextVal;
		commonTblBodyText+="</tbody>";
		commonTblBodyText+="</table>";

		console.log("-----");
		console.log(commonTblBodyText);

		//console.log();

		$("#" + tblHandler).html(commonTblBodyText);

		console.log("is " + tblHandler);

		$(".addButtonClass").off('click').on('click',function(){
			var tdChildren = $(this).parents("tr").children("td");

			var availableParkingSpace = parseInt(tdChildren.eq(1).text());
			var bookedParkingSpace = parseInt(tdChildren.eq(2).text());

			var confirmMessage = "<strong>Current - <strong> Available: " + availableParkingSpace + " and Booked: " + bookedParkingSpace + "<h3>Book 1 parking space?</h3><string>Post Booking - </string>Available: " + (availableParkingSpace-1) + " and Booked: " + (bookedParkingSpace + 1);

			var handlerId = $(this).prop('id');

			var maleOrFemale = "M";

			if($(this).parents("div").attr("id") == "doughnutDataTable")
				maleOrFemale = "M"
			else
				maleOrFemale = "F";

			bootbox.confirm({
				message: confirmMessage,
				buttons: {
			        confirm: {
			            label: 'Book',
			            className: 'btn-success'
			        },
			        cancel: {
			            label: 'Cancel',
			            className: 'btn-warning'
			        }
			    },
			    callback: function(result){
			    	bookParkingSpace(result, handlerId, maleOrFemale,'A');
			    }
			});
			//alert($(this).attr("id"));
		});

		$(".delButtonClass").off('click').on('click',function(){
			var tdChildren = $(this).parents("tr").children("td");

			var availableParkingSpace = parseInt(tdChildren.eq(1).text());
			var bookedParkingSpace = parseInt(tdChildren.eq(2).text());

			var confirmMessage = "<strong>Current - <strong> Available: " + availableParkingSpace + " and Booked: " + bookedParkingSpace + "<h3>Free 1 parking space?</h3><string>Post Booking - </string>Available: " + (availableParkingSpace+1) + " and Booked: " + (bookedParkingSpace - 1);

			var handlerId = $(this).prop('id');

			var maleOrFemale = "M";

			if($(this).parents("div").attr("id") == "doughnutDataTable")
				maleOrFemale = "M"
			else
				maleOrFemale = "F";

			bootbox.confirm({
				message: confirmMessage,
				buttons: {
			        confirm: {
			            label: 'Free',
			            className: 'btn-success'
			        },
			        cancel: {
			            label: 'Cancel',
			            className: 'btn-warning'
			        }
			    },
			    callback: function(result){
			    	bookParkingSpace(result, handlerId, maleOrFemale,'D');
			    }
			});
		});

		enableDisableButton();


	}

	function bookParkingSpace(result, btnHandlerValue, maleOrFemale, bookedOrFreed){
		if(result){
			//alert(btnHandler);
			//alert(btnHandler.val());

			var handlerValue = btnHandlerValue;

			$.ajax({
				async: false,
				url : "ps_updateParkingData.php",
				type : "POST",
				data: "CMPNY_BLDG_ID="+handlerValue+"&mORf="+maleOrFemale+"&aORd="+bookedOrFreed,
				success : function(htmldata){
					//$("#selParking").html(htmldata);
					//alert("done " + htmldata);
					//console.log(htmldata);
					//location.reload();
					$(".tab-pane").each(function(){
						if($(this).hasClass("active")){
							if($(this).attr("id") == 1)
								getDoughnut("M");
							else
								getDoughnut("F");
						}
					});
				},
				error : function(data) {
					console.log(data);
				}
			});
		}
	}

	function enableDisableButton(){
		$(".delButtonClass").each(function(){
			var tdChildren = $(this).parents("tr").children("td");

			var availableParkingSpace = parseInt(tdChildren.eq(1).text());
			var bookedParkingSpace = parseInt(tdChildren.eq(2).text());

			if(bookedParkingSpace == 0){
				$(this).attr('disabled','true');
			}else{
				$(this).removeAttr('disabled');
			}

		});	

		$(".addButtonClass").each(function(){
			var tdChildren = $(this).parents("tr").children("td");

			var availableParkingSpace = parseInt(tdChildren.eq(1).text());
			var bookedParkingSpace = parseInt(tdChildren.eq(2).text());

			console.log(availableParkingSpace);

			if(parseInt(availableParkingSpace) == 0){
				$(this).attr('disabled','true');
			}else{
				$(this).removeAttr('disabled');
			}

		});	
	}

});