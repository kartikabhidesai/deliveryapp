		//$(function() {$('.alert').fadeOut(10000);});
		if (buttonValue == null) { var buttonValue=[]; }
		var dataTable = $('#dt-list').DataTable({
					"draw" : false,
					"processing": true,
					"serverSide": true,
					"stateSave": true,
					"buttons":   buttonValue,
					"stateDuration": 60*30,
					"columnDefs": [ {
						  "targets": 0,
						  "orderable": false,
						  "searchable": false
						   
						} ],
						'aoColumnDefs': [{
						'bSortable': false,
						'aTargets': targetsCols /* 1st one, start by the right */
					}]	,
					"iDisplayLength" : 25 ,
					"pageLength" : 25,
					"order": orderCols,
					"ajax":{
						url :gridUrl, // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							$(".dt-list-error").html("");
							$("#dt-list").append('<tbody class="dt-list-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
							$("#dt-list_processing").css("display","none");
							
						}
					}
			} );
			
		$(document).on("click", ".btnDelete", function () {
					
				var id = $(this).data('id');
				var row = $(this).closest("tr").get(0);
				bootbox.confirm("Are you sure you want to delete record?", function(r) {
				if(r==true)
				{	
					$.ajax({
					   type: "POST",
					   url: actionUrl,
					   data: {data_ids:id},
					   cache: false,
						success: function()
						{
							dataTable.row( $(this).parents('tr') ).remove().draw(false);
							getMessage("error","Deleted Successfully");
						}
					   
					 });
				}
				else
				{
					getMessage("cancelled","Cancelled");
				}
				});
			});
		
		$(document).ready(function() {
			
				$('.alert').fadeOut(6000);
				
				$("#selectall").on('click',function() { // bulk checked
					var status = this.checked;
					$(".deleteRow").each( function() {
						$(this).prop("checked",status);
					});
				});
				
					$('#btnMultiDelete').on("click", function(event){ // triggering delete one by one
						if( $('.deleteRow:checked').length > 0 ){  // at-least one checkbox checked
						
							bootbox.confirm("Are you sure you want to delete selected record?", function(r)
							{
								if(r==true)
								{
									var ids = [];
									$('.deleteRow').each(function(){
										if($(this).is(':checked')) { 
											ids.push($(this).val());
										}
									});
									var ids_string = ids.toString();  // array to string conversion 
									$.ajax({
										type: "POST",
										url: actionUrl,
										data: {data_ids:ids_string,"action":"delete"},
										success: function(result) {
											dataTable.draw(false); // redrawing datatable
											$('#selectall').attr('checked', false); // Unchecks it
											getMessage("error","Deleted Successfully");
										},
										async:false
									});
								}
								else
								{
									getMessage("cancelled","Cancelled");
								}
							});	
						}
						else
						{
							bootbox.alert("Please check at least one checkbox");	
						}
					});	
					
					$('#btnFreeDelivery').on("click", function(event){ // triggering delete one by one
						//if( $('.freeDeliveryRow:checked').length > 0 ){  // at-least one checkbox checked
						if(true) {
						
							bootbox.confirm("Are you sure you want to set free delivery?", function(r)
							{
								if(r==true)
								{
									var ids = [];
									var ids1 = [];
									$('.freeDeliveryRow').each(function(){
										if($(this).is(':checked')) { 
											ids.push($(this).val());
										}
									});
									$('.deleteRow').each(function(){
										//if($(this).is(':checked')) { 
											ids1.push($(this).val());
										//}
									});
									var ids_string = ids.toString();  // array to string conversion 
									var ids_string1 = ids1.toString();  // array to string conversion 
									$.ajax({
										type: "POST",
										url: actionUrl,
										data: {data_ids:ids_string,data_ids1:ids_string1,"action":"free_delivery"},
										success: function(result) {
											dataTable.draw(false); // redrawing datatable
											//$('#selectall').attr('checked', false); // Unchecks it
											getMessage("success","Successfully done");
										},
										async:false
									});
								}
								else
								{
									getMessage("cancelled","Cancelled");
								}
							});	
						}
						else
						{
							bootbox.alert("Please check at least one checkbox");	
						}
					});
					
					$(document).on("click", ".cancelDialog", function () {
					var id = $(this).data('id');
					 var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("divCancelOrder").innerHTML = xmlhttp.responseText;
					}
					};
					
					xmlhttp.open("GET", cancelUrl+"?id=" + id, true);
					xmlhttp.send();
					});	
				});
		
		
		
		
		$(document).on("click", ".ViewDialog", function () {
			var id = $(this).data('id');
			 var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
			};
			
			xmlhttp.open("GET", viewUrl+"?id=" + id, true);
			xmlhttp.send();
		});
		 
		// Only for Customer From Order Menu - Start 
		$(document).on("click", ".ViewDialogCust", function () {
			var id = $(this).data('id');
			 var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHintCust").innerHTML = xmlhttp.responseText;
            }
			};
			
			xmlhttp.open("GET", viewUrlCust+"?id=" + id, true);
			xmlhttp.send();
		});
		// Only for Customer From Order Menu - End 

		$(document).on("click", ".manageDialog", function () {
			var id = $(this).data('id');
			 var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtManage").innerHTML = xmlhttp.responseText;
            }
			};
			
			xmlhttp.open("GET", manageUrl+"?id=" + id, true);
			xmlhttp.send();
		});		
		
		
			function updateStatus(ids,action)
				{
					bootbox.confirm("Do you want to continue?", function(r)
					{
						if(r==true)
						{
							var ids_string = ids.toString();  // array to string conversion 
							$.ajax({
								type: "POST",
								url: actionUrl,
								data: {data_ids:ids_string,"action":action},
								success: function(result) {
									dataTable.draw(false); // redrawing datatable
									getMessage(action,ucFirst(action) + " successfully");
								},
								async:false
							});
						}
						else
						{
							getMessage("cancelled","Cancelled");
						}
					});
				}
				
				function ucFirst( str ) {
					return str.substr(0, 1).toUpperCase() + str.substr(1);
				}