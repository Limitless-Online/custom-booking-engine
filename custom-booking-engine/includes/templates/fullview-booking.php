<?php 
	
	function fullview_booking($attr){

		$attr = shortcode_atts( array(
            'function_page_no' => '509',
        ), $attr, 'fullview-booking' );

		$current_user = wp_get_current_user();

		$html 	 = "";
		$html 	.= '<script type="text/javascript">';
		$html 	.= '	var monthSlide = 0 ;';
		$html 	.= '	var dateArr = [];';
		$html 	.= '	var datesArr = [];';
		$html 	.= '	var temDatesArr = [];';
		$html 	.= '	var globalDates = [];	';	
		$html 	.= '	var globalSelectedDates = [];';
		$html 	.= '	var today = new Date();';

		$html 	.= '	var fix_user_id = '.$current_user->ID.';';
		$html 	.= '	var fix_data_loading = false;';

		$html 	.= '	function highlightDays(date) {';			
		$html 	.= '	   for (var i = 0; i < dates.length; i++) {';			
		$html 	.= '	     if (new Date(dates[i].dates).toString() == date.toString()) { ';     							    
		$html 	.= '	       return [true, dates[i].color];';
		$html 	.= '	     }';
		$html 	.= '	   }';
		$html 	.= '	   return [true, ""];';
		$html 	.= '	}';

		$html 	.= '	var fix_loaded_date_data = null;';



		$html 	.= '	var raw_data = null;';


		$html 	.= '	function load_raw_data(date_array) {';
		$html 	.= '		raw_data = date_array;';
		$html 	.= '	}';

		$html 	.= '	function find_date_owner(date_string) {';

		$html 	.= '		var date_object = new Date(date_string);';

		$html 	.= '		var td_day = date_object.getDate();';
		$html 	.= '		var td_month = date_object.getMonth();';
		$html 	.= '		var td_year = date_object.getYear();';
		$html 	.= '		var td_format = td_day+"."+td_month+"."+td_year;';

		$html 	.= '		for(ibl = 0; ibl < raw_data.length; ibl++) {';
				
		$html 	.= '			var booking_dates = raw_data[ibl].booking_dates.split(",");';
				
		$html 	.= '			for(ibd = 0; ibd < booking_dates.length; ibd++) {';
		$html 	.= '				var current_date = new Date(booking_dates[ibd]);';
						
		$html 	.= '				var cd_day = current_date.getDate();';
		$html 	.= '				var cd_month = current_date.getMonth();';
		$html 	.= '				var cd_year = current_date.getYear();';
		$html 	.= '				var cd_format = cd_day+"."+cd_month+"."+cd_year;';

		$html 	.= '				if(td_format == cd_format) {';
							//console.log(td_format);
							//console.log(cd_format);
		$html 	.= '					return raw_data[ibl].user_id;';
		$html 	.= '				}';
		$html 	.= '			}';
				
		$html 	.= '		}';
		$html 	.= '		return false;';
		$html 	.= '	}';









		$html 	.= '	function getSyndicateValues()';
		$html 	.= '	{';


		$html 	.= '		$("#user_id").val("");';
		$html 	.= '		$("#services-id").val("");';
		$html 	.= '		fix_user_change_count = 0;';
		$html 	.= '		fix_prev_user_id = null;';
		$html 	.= "		$('#fullview-booking-save input[type=hidden]').val('');";

		$html 	.= '		var syndicateId = $("#syndicate_list :selected").val();';
		$html 	.= '	    $("#booking_dates").val("");';
		$html 	.= '	    $("#altField").val("");   ';         
				//console.log(syndicateId);
		$html 	.= '		var syndicateData = {};';
		$html 	.= '		syndicateData.syndicateId = syndicateId;';
		$html 	.= '		syndicateData.type = "getSyndicateDates";';
		$html 	.= '	    var tempColorArray = [];';
		$html 	.= '	    var tempColorIdArray = [];';
		$html 	.= '		 monthSlide = 0;';
		$html 	.= '	    $.ajax({';
		$html 	.= '	      type:"POST",';
		$html 	.= '	      url : "?page_id='.$attr["function_page_no"].'",';
		$html 	.= '	      async: false,';
		$html 	.= '	      dataType : "text",';
		$html 	.= '	      data : syndicateData,';
		$html 	.= '	      success : function(result){';
			         //console.log(result);
			         //fix_loaded_date_data = result;
		$html 	.= '	         fix_loaded_date_data = jQuery.extend(true, {}, result);';
			         
		$html 	.= '	         obj = JSON.parse(result);';
		$html 	.= '	         load_raw_data(obj);';
		$html 	.= '	         fix_data_loading = true;';
		$html 	.= '	         var datesArray = new Array();';
		$html 	.= '	         var tempDatesArray = [];';
		$html 	.= '	         var ownerName = [];';
		$html 	.= '	         var userId = [];';
		$html 	.= '			 for(i in obj)';
		$html 	.= '			 {';
			           //console.log(i);
		$html 	.= '	           tempDatesArray[i] = {};';
		$html 	.= '	           tempColorArray[i] = {};';
		$html 	.= '	           tempColorIdArray[i] = {};';
		$html 	.= '	           for(j in obj[i])';
		$html 	.= '	            {';
			              //console.log(j+' '+obj[i][j]);
		$html 	.= '	               if(obj[i].first_name != "")';
		$html 	.= '	                 {';
		$html 	.= '	                   ownerName[i] = obj[i].first_name+" "+obj[i].surname; ';   
		$html 	.= '	                 }else{';
		$html 	.= '	                   ownerName[i] = " "+obj[i].surname;';
		$html 	.= '	                 }';
		$html 	.= '	                 userId[i] = obj[i].user_id;';
		$html 	.= '	                 if(j == "user_color")';
		$html 	.= '	                 {';
		$html 	.= '	                   if(obj[i][j])';
		$html 	.= '	                   { ';                               
		$html 	.= '	                     tempDatesArray[i]["color"] = obj[i][j];';
		$html 	.= '	                   }else{';
		$html 	.= '	                     tempDatesArray[i]["color"] = "";';
		$html 	.= '				       }';
		$html 	.= '	                   tempColorArray[i] = obj[i].user_color; ';                       
		$html 	.= '	                   tempColorIdArray[i] = obj[i].user_id;  ';                          
		$html 	.= '					}';
		$html 	.= '	                if(j == "booking_dates")';
		$html 	.= '	                {';
		$html 	.= '	                  tempDatesArray[i]["dates"] = obj[i][j];';
		$html 	.= '	                } ';          
		$html 	.= '	             }';
		$html 	.= '	             datesArray[i] = tempDatesArray[i];';
			             //console.log('datesArray = '+datesArray);                    
		$html 	.= '	            }';
		$html 	.= '				var radioText ="";';
		$html 	.= '	            var radioText = radioText + "<div class=\'ml-row\'><div class=\'availabel-days fullview-adays\'><span></span>Available Days</div><div class=\'red-circle-days\'><input type=\'radio\' value=\'red\' class=\'validate[required] radio unlimited_select\' id=\'red\' name=\'color\' onclick=\'updateServices(this.id)\' /><span></span>Red Circle Days</div><div class=\'services-days\'><input type=\'radio\' value=\'grey\' class=\'validate[required] radio unlimited_select\' id=\'grey\' name=\'color\' onclick=\'updateServices(this.id)\' /><span></span>Service Days</div></div>";';
			            //console.log(ownerName);
			            //console.log(tempColorArray);
			            //console.log(tempColorIdArray);
		$html 	.= '	            var radioText = radioText + "<div class=\'ml-row2\'><div class=\'ownername_fullview\'></div></div>";';
			            /*for(color in tempColorArray)
			            {
			              if(tempColorArray[color] != '' && ownerName[color] != ''  && ownerName[color] != ' ')
			              {   
			                radioText = radioText + '<span class="bkng-mngmnt-picker"><input type="radio" value="'+tempColorArray[color]+'" class="validate[required] radio limited_select" id="'+tempColorIdArray[color]+'" name="color" onclick="updateOwner(this.id)" /><div style="background:'+tempColorArray[color]+';"></div><div class="owner-name">'+ownerName[color]+'</div></span>';
			              }
			            }*/
		$html 	.= '	            radioText = radioText + "</div>";';
		$html 	.= '	            $(".color-picker").html(radioText);';
		$html 	.= '	            var finaldata = new Array();';
		$html 	.= '	            var count = 0;';
		$html 	.= '	            var datesArr1 = [];';
		$html 	.= '	            var temDatesArr1 = [];';
		$html 	.= '	            for(dat in datesArray)';
		$html 	.= '	            {';
			                //console.log(dat);
			                //console.log(datesArray[dat]);
		$html 	.= '	                finaldata[count] = {}; ';                       
		$html 	.= '	                for(t in datesArray[dat])';
		$html 	.= '	                {';
		$html 	.= '	                   var tempArray1 = new Array();';                        
		$html 	.= '	                   if(t == "dates")';
		$html 	.= '	                   {';
		$html 	.= '	                      var temp = datesArray[dat][t].split(",");';
		$html 	.= '	                      for(m in temp)';
		$html 	.= '	                      {';
		$html 	.= '	                         tempArray1[count] = {};';
		$html 	.= '	                         tempArray1[count]["color"] = datesArray[dat].color;';
		$html 	.= '	                         tempArray1[count]["dates"] = temp[m];';
			                         //console.log(temp[m]);
		$html 	.= '	                         finaldata[count] = tempArray1[count];';
			                                     
			                         //datesArr1[count] =  [];
		$html 	.= '	                         var tempDate1 = temp[m];';
		$html 	.= '	                         var dateObj1 = new Date(tempDate1);';
			                         //console.log(dateObj1);
		$html 	.= '	                         datesArr1.push(dateObj1);';
		$html 	.= '	                         var temDatesArr2 = [];';
		$html 	.= '	                         temDatesArr2.push({date : temp[m]});';
		$html 	.= '	                         temDatesArr2.push({color : datesArray[dat].color});';
		$html 	.= '	                         temDatesArr1.push({count : temDatesArr2});';
			                                
		$html 	.= '	                         count++; ';                           
		$html 	.= '	                       }';
		$html 	.= '	                   }  ';                      
		$html 	.= '	                }';
		$html 	.= '	            }';
			            //console.log(temDatesArr1);
		$html 	.= '	            globalDates = temDatesArr1;';
		$html 	.= '	            getDataes(datesArr1, temDatesArr1);';
		$html 	.= '	            updateOwners(syndicateId);';
		$html 	.= '	        fix_data_loading = false;';
		$html 	.= '	        }';
		$html 	.= '	   });';
		$html 	.= '	}';

		$html 	.= '	function updateOwners(syndicateId)';
		$html 	.= '	{';
		$html 	.= '	    var getOwnersDetails = {};';
		$html 	.= '		getOwnersDetails.syndicateId = syndicateId;';
		$html 	.= '		getOwnersDetails.type = "getOwnersDetails";';
		$html 	.= '	    $.ajax({';
		$html 	.= '	      type:"POST",';
		$html 	.= '	      url : "?page_id='.$attr["function_page_no"].'",';
		$html 	.= '	      async: false,';
		$html 	.= '	      dataType : "text",';
		$html 	.= '	      data : getOwnersDetails,';
		$html 	.= '	      success : function(result){';
		$html 	.= '	         if(result != "")';
		$html 	.= '	         {';
		$html 	.= '	            var radioString = "";';
		$html 	.= '	            var ownerDetails = JSON.parse(result);';
			            //console.log(ownerDetails);
		$html 	.= '				radioString = radioString + "<div class=\'cols\'>";';
		$html 	.= '				var count = 0;';
		$html 	.= '	            for(i in ownerDetails)';
		$html 	.= '	            {';
			                //console.log(ownerDetails[i]);
		$html 	.= '	                radioString = radioString + "<span class=\'bkng-mngmnt-picker\'><input type=\'radio\' value=\'"+ownerDetails[i].color+"\' class=\'validate[required] radio limited_select\' id=\'"+ownerDetails[i].user_id+"\' name=\'color\' onclick=\'updateOwner(this.id)\' /><div style=\'background:"+ownerDetails[i].color+";\'></div><div class=\'owner-name\'>"+ownerDetails[i].name+"</div></span>";';
		$html 	.= '					if(count == 3)';
		$html 	.= '					{';
		$html 	.= '						radioString = radioString + "</div><div class=\'cols\'>";';
		$html 	.= '					}';
		$html 	.= '					count++;';
		$html 	.= '	            }';
		$html 	.= '				radioString = radioString + "</div>";';
		$html 	.= '	            $(".ownername_fullview").html(radioString);';
		$html 	.= '	         }';
		$html 	.= '	      }';
		$html 	.= '	    });';
		$html 	.= '	}';


		$html 	.= '	function getDataes(dates, temDatesArr)';
		$html 	.= '	{';
		$html 	.= '	    $("#maxPicks").multiDatesPicker("resetDates", "picked");';
		$html 	.= '	    if(dates.length > 0)';
		$html 	.= '	    {';
			        //console.log('we are in getDataes');
		$html 	.= '	        $("#maxPicks").multiDatesPicker({';			
			    			//maxPicks: 3,
		$html 	.= '	                numberOfMonths: 3,';
		$html 	.= '	                addDates: dates,';
		$html 	.= '	                showCurrentAtPos: 1,';
		$html 	.= '	                maxDate: "+1Y",';
		$html 	.= '	                onSelect: function(dateText, inst) {';
		$html 	.= '	                                dateDetails = dateText.split("\/");';
			    							//console.log(monthSlide);
			    							//console.log("###########################################");
		$html 	.= '	    							currentDate = new Date();';							
		$html 	.= '	    							if( monthSlide == 0 ){';
		$html 	.= '	    								dt = new Date();';
		$html 	.= '	    							}else{';
		$html 	.= '	    								currentMonth = (new Date()).getMonth();';
		$html 	.= '	    								dt = new Date(parseInt(dateDetails[2]),currentMonth + monthSlide,parseInt(dateDetails[1]) );';
			    
		$html 	.= '	    							}';
			    							//console.log(dt);
		$html 	.= '	                                var newDate = dt;';
			                                //var newDate = new Date();
		$html 	.= '	    							inst.drawMonth = newDate.getMonth();';
		$html 	.= '	                                inst.drawYear = newDate.getFullYear();';
		$html 	.= '	                                inst.currentMonth = newDate.getMonth();';
		$html 	.= '	                                inst.currentYear = newDate.getFullYear();';
		$html 	.= '	                                inst.input.trigger("change");   ';                                                                                 
		$html 	.= '	                            }';
		$html 	.= '	            });';
			        //console.log('we are calling addcolors');
		$html 	.= '	        addColors(temDatesArr);';
		$html 	.= '	    }else{';
		$html 	.= '	        $("#maxPicks").multiDatesPicker({';			
			    			//maxPicks: 3,
		$html 	.= '	                numberOfMonths: 3,';
			                //addDates: dates,
		$html 	.= '	                showCurrentAtPos: 1,';
		$html 	.= '	                maxDate: "+1Y",';
		$html 	.= '	                onSelect: function(dateText, inst) {';
		$html 	.= '	                                dateDetails = dateText.split("\/");';
			    							//console.log(monthSlide);
			    							//console.log("###########################################");
		$html 	.= '	    							currentDate = new Date();';							
		$html 	.= '	    							if( monthSlide == 0 ){';
		$html 	.= '	    								dt = new Date();';
		$html 	.= '	    							}else{';
		$html 	.= '	    								currentMonth = (new Date()).getMonth();';
		$html 	.= '	    								dt = new Date(parseInt(dateDetails[2]),currentMonth + monthSlide,parseInt(dateDetails[1]) );';
			    
		$html 	.= '	    							}';
			    							//console.log(dt);
		$html 	.= '	                                var newDate = dt;';
			                                //var newDate = new Date();
		$html 	.= '	    							inst.drawMonth = newDate.getMonth();';
		$html 	.= '	                                inst.drawYear = newDate.getFullYear();';
		$html 	.= '	                                inst.currentMonth = newDate.getMonth();';
		$html 	.= '	                                inst.currentYear = newDate.getFullYear();';
		$html 	.= '	                                inst.input.trigger("change");   ';                                                                                 
		$html 	.= '	                            }';
		$html 	.= '	            });';
		$html 	.= '	    }';
		$html 	.= '	}';


		$html 	.= '	function eraseColors(temDatesArr)';
		$html 	.= '	{	';
		$html 	.= '	    var dateExists = "";';
			    //console.log('eraseColors');
			    //console.log(temDatesArr);
			    //$('.ui-state-highlight').each(function() {
		$html 	.= '	    $(".ui-datepicker-calendar td").each(function() {';
		$html 	.= '	        var year = $(this).attr("data-year");';
		$html 	.= '	        var month = $(this).attr("data-month");';
			                
		$html 	.= '	        if(typeof month != "undefined")';
		$html 	.= '	        {';
			           
		$html 	.= '	            if(month.length == 1)';
		$html 	.= '	            {';
		$html 	.= '	                if(parseInt(month)!=9)';
		$html 	.= '	                {';
		$html 	.= '	                    month = "0"+(parseInt(month) + 1);';

		$html 	.= '	                }';
		$html 	.= '	                else{';
		$html 	.= '	                    month = parseInt(month) + 1;';
		$html 	.= '	                }';
		$html 	.= '	            }else{';
			                
		$html 	.= '	                month = parseInt(month) + 1;';
		$html 	.= '	            }   ';
		$html 	.= '	        }';
		$html 	.= '	        var day = $(this).text();';
		$html 	.= '	        if(day.length == 1)';
		$html 	.= '	        {';
		$html 	.= '	            day = "0"+day;';
		$html 	.= '	        }';
		$html 	.= '	        var current_date = month+"/"+day+"/"+year;';
			   
		$html 	.= '	        dateExists = checkDateExists(temDatesArr, current_date);';
			   //console.log('testing');
		$html 	.= '	        if(dateExists != "")';
		$html 	.= '	        {';
			        	//console.log(dateExists);
		$html 	.= '	            $(this).children().css("background", "#fff");';
		$html 	.= '	        }';
		$html 	.= '	    });';
		$html 	.= '	}';

		$html 	.= '	function addColors(temDatesArr)';
		$html 	.= '	{	';
		$html 	.= '	    var dateExists = "";';
			    //console.log('addColors');
			    //console.log(temDatesArr);
			    
		$html 	.= '	    $(".ui-datepicker-calendar td").each(function() {';
			    //$('.ui-state-highlight').each(function() {
		$html 	.= '	        var year = $(this).attr("data-year");';
		$html 	.= '	        var month = $(this).attr("data-month");';
			                
		$html 	.= '	        if(typeof month != "undefined")';
		$html 	.= '	        {';
			           
		$html 	.= '	            if(month.length == 1)';
		$html 	.= '	            {';
		$html 	.= '	                if(parseInt(month)!=9)';
		$html 	.= '	                {';
		$html 	.= '	                    month = "0"+(parseInt(month) + 1);';

		$html 	.= '	                }';
		$html 	.= '	                else{';
		$html 	.= '	                    month = parseInt(month) + 1;';
		$html 	.= '	                }';
		$html 	.= '	            }else{';
			                
		$html 	.= '	                month = parseInt(month) + 1;';
		$html 	.= '	            }   ';
		$html 	.= '	        }';
		$html 	.= '	        var day = $(this).text();';
		$html 	.= '	        if(day.length == 1)';
		$html 	.= '	        {';
		$html 	.= '	            day = "0"+day;';
		$html 	.= '	        }';
		$html 	.= '	        var current_date = month+"/"+day+"/"+year;';
			   
			        //console.log('wat '+current_date);
			        
		$html 	.= '	        dateExists = checkDateExists(temDatesArr, current_date);';
			        
			   		//console.log(dateExists);
			   		
		$html 	.= '	        if(dateExists != "")';
		$html 	.= '	        {';
		$html 	.= '	        	if(dateExists == "red") {';
			        		//console.log('red found at: '+current_date+' length: '+temDatesArr.length);
			        		//temDatesArr = new Array();
			        		
		$html 	.= '	        	}';
			        	//console.log(dateExists);
		$html 	.= '	            $(this).children().css("background", dateExists);';
		$html 	.= '	        }';
		$html 	.= '	    });';
		$html 	.= '	}';


		$html 	.= '	function checkDateExists(temDatesArr, current_date)';
		$html 	.= '	{';
			//console.log('checkDateExists');
		$html 	.= '	    var dateExists = "";';
		$html 	.= '	    for(i in temDatesArr)';
		$html 	.= '	    {';
		$html 	.= '	        var temp1 = [];';
		$html 	.= '	        temp1 = temDatesArr[i];';
		$html 	.= '	        for(j in temp1)';
		$html 	.= '	        {';
		$html 	.= '	            var temp2 = [];';
		$html 	.= '	            temp2 = temp1[j];';
		$html 	.= '	            for(k in temp2)';
		$html 	.= '	            {';
		$html 	.= '	                if(current_date == temp2[k].date)';
		$html 	.= '	                {';
			  
		$html 	.= '	                    var tempColor = temp2[++k].color;';
		$html 	.= '	                    if(typeof tempColor != "undefined")';
		$html 	.= '	                    {';
		$html 	.= '	                        dateExists = tempColor;';
		$html 	.= '	                    }';
			                                         
			                      //$(this).children().css('background', tempColor);
		$html 	.= '	                }';
		$html 	.= '	           }';
		$html 	.= '	       }';
		$html 	.= '	    }';
		$html 	.= '	    return dateExists;';
		$html 	.= '	}';
			// hack in ui-min.js file to update colors to calender in full view booking
		$html 	.= '	function updateAllDates()';
		$html 	.= '	{       ';
			//console.log('updateAllDates');
		$html 	.= '	    addColors(globalDates);';
			    
			    // needed TODO
			    //addColors(fix_globalNewDates);
		$html 	.= '	}';

		$html 	.= '	var modified_dates = new Array();';
		$html 	.= '	var current_action = null;';

		$html 	.= '	function updateDates(currentSelectedDate) {';
		$html 	.= '	}';

		$html 	.= '	var fix_selected_date = null;';
		$html 	.= '	var fix_dialog_action = null;';

		$html 	.= '	function fix_dialog_updateDates() {';
			//console.log('fix_dialog_updateDates '+fix_selected_date);
				//var action = $('#action').val();
				
		$html 	.= '	    var currentSelectedColor = $("input[name=color]:radio:checked").val();';
		$html 	.= '	    var currentSelectedUserId = $("input[name=color]:radio:checked").attr("id");';
				
		$html 	.= '		fix_selected_date_object = new Date(fix_selected_date);';
				
		$html 	.= '		var date_owner = find_date_owner(fix_selected_date);';

		$html 	.= '		if(fix_dialog_action == "cancel") {';
					// hack on hacks on hacks
		$html 	.= '			fix_data_loading = true;';
		$html 	.= '			$("#maxPicks").multiDatesPicker("addDates", new Array(fix_selected_date_object));';
		$html 	.= '			fix_data_loading = false;';
		$html 	.= '		}';
				//console.log($("#user_id").val());
		$html 	.= '		if(fix_dialog_action == "deduct" || fix_dialog_action == "recredit" || $("#user_id").val() == "") {';
			            //console.log('wat2');
					//console.log('deduct / recredit');
				
		$html 	.= '			var $to_remove = $("#dates_to_remove");';
		$html 	.= '			var $to_add = $("#dates_to_add");';
		$html 	.= '			var $recredit = $("#recredit");';
					
					// add to add queue
		$html 	.= '			field = $to_remove.val();';
		$html 	.= '			var values = "";';
		$html 	.= '			if(field == "")';
		$html 	.= '			{';
		$html 	.= '				values = fix_selected_date;  ';  
		$html 	.= '			}else{';
		$html 	.= '				values = field + "," + fix_selected_date;';
		$html 	.= '			} ';
		$html 	.= '			$to_remove.val(values);';
				
					// add to add queue
		$html 	.= '			field = $recredit.val();';
		$html 	.= '			var values = "";';
		$html 	.= '			var deduct = "0";';
		$html 	.= '			if(fix_dialog_action == "deduct") {';
		$html 	.= '				deduct = "1";';
		$html 	.= '			}';
		$html 	.= '			if(field == "")';
		$html 	.= '			{';
		$html 	.= '				values = deduct;  ';  
		$html 	.= '			}else{';
		$html 	.= '				values = field + "," + deduct;';
		$html 	.= '			} ';
		$html 	.= '			$recredit.val(values);';
					
					// todo here
					//console.log('huh');
					//eraseColors(fix_globalNewDates);
					//find_remove(fix_selected_date);
					
					
					// HEEERE
					//console.log(globalDates);
		$html 	.= '			fix_remove_global_dates(fix_selected_date);';
					
		// 			for(irm = 0; irm < globalDates.length; irm++) {
		// 				if(globalDates[irm].count[0].date == fix_selected_date) {
							
		// 					var fuckinghell = new Array();
		// 					fuckinghell.push(globalDates[irm]);
		// 					globalDates.splice(irm, 1);
		// 					eraseColors(fuckinghell);
		// 				}
		// 			}
					
		$html 	.= '		}';
				
		$html 	.= '		fix_selected_date = null;';
		$html 	.= '		fix_dialog_action = null;';
		$html 	.= '	}';

		$html 	.= '	function fix_remove_global_dates(fix_selected_date) {';

		$html 	.= '		for(irm = 0; irm < globalDates.length; irm++) {';
		$html 	.= '			if(globalDates[irm].count[0].date == fix_selected_date) {';
						
		$html 	.= '				var fuckinghell = new Array();';
		$html 	.= '				fuckinghell.push(globalDates[irm]);';
		$html 	.= '				globalDates.splice(irm, 1);';
		$html 	.= '				eraseColors(fuckinghell);';
		$html 	.= '			}';
		$html 	.= '		}';
				
		$html 	.= '	}';

		$html 	.= '	function fix_exists_in_input_array($input, date_object) {';

		$html 	.= '		var input_value = $input.val();';

		$html 	.= '		if(input_value != "") {';

		$html 	.= '		var td_day = date_object.getDate();';
		$html 	.= '		var td_month = date_object.getMonth();';
		$html 	.= '		var td_year = date_object.getYear();';
		$html 	.= '		var td_format = td_day+"."+td_month+"."+td_year;';

		$html 	.= '		var value_array = input_value.split(",");';

		$html 	.= '		for(iv = 0; iv < value_array.length; iv++) {';
				
		$html 	.= '			var svalue = new Date(value_array[iv]);';

		$html 	.= '			var cd_day = svalue.getDate();';
		$html 	.= '			var cd_month = svalue.getMonth();';
		$html 	.= '			var cd_year = svalue.getYear();';
		$html 	.= '			var cd_format = cd_day+"."+cd_month+"."+cd_year;';

		$html 	.= '			if(td_format == cd_format) {';

		$html 	.= '				return iv;';
		$html 	.= '			}';
		$html 	.= '		}';
		$html 	.= '		}';
		$html 	.= '		return -1;';

		$html 	.= '	}';


			/*
			patched in an array to keep track of newly selected dates so we can colour them in
			*/
		$html 	.= '	var fix_globalNewDates = new Array();';
		$html 	.= '	function find_remove(date_string) {';
		$html 	.= '		for(ifr = 0; ifr < fix_globalNewDates.length; ifr++) {';
				
			//console.log(fix_globalNewDates[ifr].count[0].date);
				//console.log(fix_globalNewDates[ifr].count[0].date +' '+ date_string);
		$html 	.= '			if(fix_globalNewDates[ifr].count[0].date == date_string) {';
						
						
				//console.log('uuu');
		$html 	.= '				fix_globalNewDates.splice(ifr, 1);';
		$html 	.= '			}';
		$html 	.= '		}';
				//console.log(fix_globalNewDates);
		$html 	.= '	}';

		$html 	.= '	function fix_updateDates(currentSelectedDate, operation_type) {';
				
		$html 	.= '	    var currentSelectedColor = $("input[name=color]:radio:checked").val();';
		$html 	.= '	    var currentSelectedUserId = $("input[name=color]:radio:checked").attr("id");';
				
		$html 	.= '		fix_selected_date = currentSelectedDate;';
		$html 	.= '		fix_selected_date_object = new Date(currentSelectedDate);';

		$html 	.= '		var $to_remove = $("#dates_to_remove");';
		$html 	.= '		var $to_add = $("#dates_to_add");';
		$html 	.= '		var $recredit = $("#recredit");';
				
		$html 	.= '		if(operation_type == "add") {';
				
		$html 	.= '			var mirror_operation_index = fix_exists_in_input_array($to_remove, fix_selected_date_object);';

					// only record as an addition if this date has been saved previous
		$html 	.= '			if(mirror_operation_index != -1) {';
						
						// remove date
		$html 	.= '				var value_array = $to_remove.val().split(",");';
		$html 	.= '				value_array.splice(mirror_operation_index, 1);';
						
		$html 	.= '				var values = "";';
		$html 	.= '				for(ivz = 0; ivz < value_array.length; ivz++) {';
		$html 	.= '					if(values == "") {';
		$html 	.= '						values = value_array[ivz];    ';
		$html 	.= '					}else{';
		$html 	.= '						values += "," + value_array[ivz];';
		$html 	.= '					} ';
		$html 	.= '				}';
		$html 	.= '				$to_remove.val(values);';
						
						// remove recredit value
		$html 	.= '				var value_array = $recredit.val().split(",");';
		$html 	.= '				value_array.splice(mirror_operation_index, 1);';
						
		$html 	.= '				var values = "";';
		$html 	.= '				for(ivz = 0; ivz < value_array.length; ivz++) {';
		$html 	.= '					if(values == "") {';
		$html 	.= '						values = value_array[ivz];   '; 
		$html 	.= '					}else{';
		$html 	.= '						values += "," + value_array[ivz];';
		$html 	.= '					} ';
		$html 	.= '				}';
		$html 	.= '				$recredit.val(values);';
						 
		$html 	.= '				var sigh = {"count": new Array({"date": fix_selected_date},{"color": currentSelectedColor})};';
		$html 	.= '				globalDates.push(sigh);';
						
						
		$html 	.= '				return true;';
		$html 	.= '			} else {';
				
				
						// add to add queue
		$html 	.= '				field = $to_add.val();';
		$html 	.= '				var values = "";';
		$html 	.= '				if(field == "")';
		$html 	.= '				{';
		$html 	.= '					values = currentSelectedDate;';    
		$html 	.= '				}else{';
		$html 	.= '					values = field + "," + currentSelectedDate;';
		$html 	.= '				} ';
		$html 	.= '				$to_add.val(values);';
					
						//console.log('here');
						//var tmp_colors = new Array();
		$html 	.= '				var tmp_date = new Array();';
		$html 	.= '				tmp_date.push({date : currentSelectedDate});';
		$html 	.= '				tmp_date.push({color : currentSelectedColor});';
						//fix_globalNewDates.push({count : tmp_date});
						
		$html 	.= '				var sigh = {"count": tmp_date};';
		$html 	.= '				globalDates.push(sigh);';
						//console.log(tmp_colors);

			    		//addColors(tmp_colors); 
		$html 	.= '				return true;';
		$html 	.= '			}';
					
		$html 	.= '		}';
				
		$html 	.= '		if(operation_type == "remove") {';

		$html 	.= '			var mirror_operation_index = fix_exists_in_input_array($to_add, fix_selected_date_object);';

					// only record as a removal if this date has been saved previous
		$html 	.= '			if(mirror_operation_index != -1) {';
					
		$html 	.= '				var value_array = $to_add.val().split(",");';
		$html 	.= '				value_array.splice(mirror_operation_index, 1);';
						
		$html 	.= '				var values = "";';
		$html 	.= '				for(ivz = 0; ivz < value_array.length; ivz++) {';
		$html 	.= '					if(values == "") {';
		$html 	.= '						values = value_array[ivz];  ';  
		$html 	.= '					}else{';
		$html 	.= '						values += "," + value_array[ivz];';
		$html 	.= '					} ';
		$html 	.= '				}';
		$html 	.= '				$to_add.val(values);';
						
						//console.log('huh2');
		$html 	.= '				fix_remove_global_dates(fix_selected_date);';
						//eraseColors(fix_globalNewDates);
						//find_remove(fix_selected_date);
						
		$html 	.= '				return true;';
						
		$html 	.= '			} else {';
						//console.log('here '+fix_selected_date);
						// ignore red circle days etc
		$html 	.= '				if($("#user_id").val() != "") {';
		$html 	.= '	            	$("#dialog").dialog("open");';
							//console.log('dialog ');
		$html 	.= '					return true;';
		$html 	.= '	            }';
			            
			            //console.log(fix_globalNewDates);
			            //eraseColors(fix_globalNewDates);
			            //console.log(fix_selected_date);
						//find_remove(fix_selected_date);
		$html 	.= '	            fix_dialog_updateDates(fix_selected_date);';
						//console.log('bypass');
						
		$html 	.= '				return true;';
		$html 	.= '			}';
		$html 	.= '		}';
		$html 	.= '	}';


		$html 	.= '	function fix_reset_calendar(result) {';


		$html 	.= '		var syndicateId = $("#syndicate_list :selected").val();';
		$html 	.= '	    var tempColorArray = [];';
		$html 	.= '	    var tempColorIdArray = [];';
			         //console.log(result);
			         //fix_loaded_date_data = result;
		$html 	.= '	         fix_loaded_date_data = jQuery.extend(true, {}, result);';
		$html 	.= '	         load_raw_data(obj);';
		$html 	.= '	         fix_data_loading = true;';
		$html 	.= '	         var datesArray = new Array();';
		$html 	.= '	         var tempDatesArray = [];';
		$html 	.= '	         var ownerName = [];';
		$html 	.= '	         var userId = [];';
		$html 	.= '			 for(i in obj)';
		$html 	.= '			 {';
			           //console.log(i);
		$html 	.= '	           tempDatesArray[i] = {};';
		$html 	.= '	           tempColorArray[i] = {};';
		$html 	.= '	           tempColorIdArray[i] = {};';
		$html 	.= '	           for(j in obj[i])';
		$html 	.= '	            {';
			              //console.log(j+' '+obj[i][j]);
		$html 	.= '	               if(obj[i].first_name != "")';
		$html 	.= '	                 {';
		$html 	.= '	                   ownerName[i] = obj[i].first_name+" "+obj[i].surname; ';   
		$html 	.= '	                 }else{';
		$html 	.= '	                   ownerName[i] = " "+obj[i].surname;';
		$html 	.= '	                 }';
		$html 	.= '	                 userId[i] = obj[i].user_id;';
		$html 	.= '	                 if(j == "user_color")';
		$html 	.= '	                 {';
		$html 	.= '	                   if(obj[i][j])';
		$html 	.= '	                   { ';                               
		$html 	.= '	                     tempDatesArray[i]["color"] = obj[i][j];';
		$html 	.= '	                   }else{';
		$html 	.= '	                     tempDatesArray[i]["color"] = "";';
		$html 	.= '				       }';
		$html 	.= '	                   tempColorArray[i] = obj[i].user_color;  ';                          
		$html 	.= '	                   tempColorIdArray[i] = obj[i].user_id;  ';                          
		$html 	.= '					}';
		$html 	.= '	                if(j == "booking_dates")';
		$html 	.= '	                {';
		$html 	.= '	                  tempDatesArray[i]["dates"] = obj[i][j];';
		$html 	.= '	                } ';     
		$html 	.= '	             }';
		$html 	.= '	             datesArray[i] = tempDatesArray[i];';
			             //console.log('datesArray = '+datesArray);                    
		$html 	.= '	            }';
		$html 	.= '				var radioText ="";';
		$html 	.= '	            var radioText = radioText + "<div class=\'ml-row\'><div class=\'availabel-days fullview-adays\'><span></span>Available Days</div><div class=\'red-circle-days\'><input type=\'radio\' value=\'red\' class=\'validate[required] radio unlimited_select\' id=\'red\' name=\'color\' onclick=\'updateServices(this.id)\' /><span></span>Red Circle Days</div><div class=\'services-days\'><input type=\'radio\' value=\'grey\' class=\'validate[required] radio unlimited_select\' id=\'grey\' name=\'color\' onclick=\'updateServices(this.id)\' /><span></span>Service Days</div></div>";';
			            //console.log(ownerName);
			            //console.log(tempColorArray);
			            //console.log(tempColorIdArray);
		$html 	.= '	            var radioText = radioText + "<div class=\'ml-row2\'><div class=\'ownername_fullview\'></div></div>";';
			            /*for(color in tempColorArray)
			            {
			              if(tempColorArray[color] != '' && ownerName[color] != ''  && ownerName[color] != ' ')
			              {   
			                radioText = radioText + '<span class="bkng-mngmnt-picker"><input type="radio" value="'+tempColorArray[color]+'" class="validate[required] radio limited_select" id="'+tempColorIdArray[color]+'" name="color" onclick="updateOwner(this.id)" /><div style="background:'+tempColorArray[color]+';"></div><div class="owner-name">'+ownerName[color]+'</div></span>';
			              }
			            }*/
		$html 	.= '	            radioText = radioText + "</div>";';
		$html 	.= '	            $(".color-picker").html(radioText);';
		$html 	.= '	            var finaldata = new Array();';
		$html 	.= '	            var count = 0;';
		$html 	.= '	            var datesArr1 = [];';
		$html 	.= '	            var temDatesArr1 = [];';
		$html 	.= '	            for(dat in datesArray)';
		$html 	.= '	            {';
			                //console.log(dat);
			                //console.log(datesArray[dat]);
		$html 	.= '	                finaldata[count] = {}; ';                       
		$html 	.= '	                for(t in datesArray[dat])';
		$html 	.= '	                {';
		$html 	.= '	                   var tempArray1 = new Array();';                        
		$html 	.= '	                   if(t == "dates")';
		$html 	.= '	                   {';
		$html 	.= '	                      var temp = datesArray[dat][t].split(",");';
		$html 	.= '	                      for(m in temp)';
		$html 	.= '	                      {';
		$html 	.= '	                         tempArray1[count] = {};';
		$html 	.= '	                         tempArray1[count]["color"] = datesArray[dat].color;';
		$html 	.= '	                         tempArray1[count]["dates"] = temp[m];';
			                         //console.log(temp[m]);
		$html 	.= '	                         finaldata[count] = tempArray1[count];';
			                                     
			                         //datesArr1[count] =  [];
		$html 	.= '	                         var tempDate1 = temp[m];';
		$html 	.= '	                         var dateObj1 = new Date(tempDate1);';
			                         //console.log(dateObj1);
		$html 	.= '	                         datesArr1.push(dateObj1);';
		$html 	.= '	                         var temDatesArr2 = [];';
		$html 	.= '	                         temDatesArr2.push({date : temp[m]});';
		$html 	.= '	                         temDatesArr2.push({color : datesArray[dat].color});';
		$html 	.= '	                         temDatesArr1.push({count : temDatesArr2});';
			                                
		$html 	.= '	                         count++; ';                           
		$html 	.= '	                       }';
		$html 	.= '	                   } ';                       
		$html 	.= '	                }';
		$html 	.= '	            }';
			            //console.log(temDatesArr1);
		$html 	.= '	            globalDates = temDatesArr1;';
		$html 	.= '	            getDataes(datesArr1, temDatesArr1);';
		$html 	.= '	            updateOwners(syndicateId);';
		$html 	.= '	        fix_data_loading = false;';
			        
			            
			            
		$html 	.= '	}';


		$html 	.= '	function getCurrentUserCount(datesSelectedValues, currentSelectedUserId)';
		$html 	.= '	{';
			    //console.log('from getCurrentUserCount');   
			    //console.log('currentSelectedUserId - '+currentSelectedUserId); 
			    //console.log('datesSelectedValues - '+datesSelectedValues);
		$html 	.= '	      var datesArray1 = datesSelectedValues.split(";");';
		$html 	.= '	      var tempSelectedDates = "";';
		$html 	.= '	      var tempCount = 0; ';
		$html 	.= '	      for(i in datesArray1)';
		$html 	.= '	      {';
			          //console.log('datesArray1 - '+datesArray1);
		$html 	.= '	          var tempDateId = datesArray1[i];';
		$html 	.= '	          if(tempDateId != "")';
		$html 	.= '	          {';
		$html 	.= '	              var datesArray2 = tempDateId.split(",");';
		$html 	.= '	              for(j in datesArray2)';
		$html 	.= '	              {';
		$html 	.= '	                  var tempDate = datesArray2[j];';
		$html 	.= '	                  var tempId = datesArray2[++j];';
			                  //console.log('tempDate - '+tempDate);
			                  //console.log('tempId - '+tempId);
		$html 	.= '	                  if(typeof tempId != "undefined" && !isNaN(tempId) && tempId == currentSelectedUserId)';
		$html 	.= '	                  {';
		$html 	.= '	                      ++tempCount;';
		$html 	.= '	                  }';
		$html 	.= '	                  continue;';
		$html 	.= '	              }';
		$html 	.= '	          }';
		$html 	.= '	      }';
		$html 	.= '	      return tempCount;';
		$html 	.= '	}';

		$html 	.= '	function checkDate(datesSelectedValues, currentSelctedDate, currentSelectedUserId)';
		$html 	.= '	{';
			    //console.log('checkDate called');
		$html 	.= '	    var datesArray1 = datesSelectedValues.split(";");';
		$html 	.= '	    var tempSelectedDates = "";';
		$html 	.= '	    var tempReturn = false; ';
		$html 	.= '	    for(i in datesArray1)';
		$html 	.= '	    {';
		$html 	.= '	        var tempDateId = datesArray1[i];';
		$html 	.= '	        if(tempDateId != "")';
		$html 	.= '	        {';
		$html 	.= '	            var datesArray2 = tempDateId.split(",");';
		$html 	.= '	            for(j in datesArray2)';
		$html 	.= '	            {';
		$html 	.= '	                var tempDate = datesArray2[j];';
		$html 	.= '	                var tempColor = datesArray2[++j];';
		$html 	.= '	                if(tempDate == currentSelctedDate)';
		$html 	.= '	                {';
		$html 	.= '	                    return true;';
		$html 	.= '	                    tempReturn = true;';
		$html 	.= '	                    continue;';
		$html 	.= '	                }else{';
		$html 	.= '	                    if(tempSelectedDates == "")';
		$html 	.= '	                    {';
		$html 	.= '	                        tempSelectedDates = tempDate+","+currentSelectedUserId+";";  ';                  
		$html 	.= '	                    }else{';
		$html 	.= '	                        tempSelectedDates = tempSelectedDates + tempDate+","+currentSelectedUserId+";";';
		$html 	.= '	                    } ';                                                                                                     
		$html 	.= '	                }';
		$html 	.= '	            }';
		$html 	.= '	        }';
		$html 	.= '	    }';
			    /*if(!tempReturn)
			    {
			        if(tempSelectedDates == '')
			        {
			             tempSelectedDates = datesSelectedValues;
			        }
			        $('#booking_dates').val(tempSelectedDates); 
			    }*/
		$html 	.= '	    return false; ';   
		$html 	.= '	}';

		$html 	.= '	function removeSelctedDate(datesSelectedValues, currentSelctedDate, currentSelectedUserId)';
		$html 	.= '	{';
			    //console.log('removeSelctedDate called');
		$html 	.= '	    var datesArray1 = datesSelectedValues.split(";");';
		$html 	.= '	    var tempSelectedDates = "";';
		$html 	.= '	    var tempReturn = false;';
		$html 	.= '	    var count = 0; ';
		$html 	.= '	    for(i in datesArray1)';
		$html 	.= '	    {';
		$html 	.= '	        var tempDateId = datesArray1[i];';
		$html 	.= '	        if(tempDateId != "")';
		$html 	.= '	        {';
		$html 	.= '	            var datesArray2 = tempDateId.split(",");';
		$html 	.= '	            for(j in datesArray2)';
		$html 	.= '	            {';
		$html 	.= '	                var tempDate = datesArray2[j];';
		$html 	.= '	                var tempColor = datesArray2[++j]; ';               
		$html 	.= '	                if(isNaN(tempDate))';
		$html 	.= '	                {';
		$html 	.= '	                    if(tempDate != currentSelctedDate)';
		$html 	.= '	                    {   ';                     
			                        //console.log('tempDate = '+tempDate);
		$html 	.= '	                        if(tempSelectedDates == "")';
		$html 	.= '	                        {';
		$html 	.= '	                            tempSelectedDates = tempDate+","+currentSelectedUserId+";";  ';                  
		$html 	.= '	                        }else{';
		$html 	.= '	                            tempSelectedDates = tempSelectedDates + tempDate+","+currentSelectedUserId+";";';
		$html 	.= '	                        }';
		$html 	.= '	                        count++;';
		$html 	.= '	                    }   ';
		$html 	.= '	                }';
		$html 	.= '	            }';
		$html 	.= '	        }';
		$html 	.= '	    }';
			    //console.log('tempSelectedDates = '+tempSelectedDates);
		$html 	.= '	    if(tempSelectedDates == "")';
		$html 	.= '	    {';
		$html 	.= '	        if(count == 0)';
		$html 	.= '	        {';
		$html 	.= '	            tempSelectedDates = "";'; 
		$html 	.= '	        }else{';
		$html 	.= '	            tempSelectedDates = datesSelectedValues; ';   
		$html 	.= '	        } ';       
		$html 	.= '	    }';
		$html 	.= '	    return tempSelectedDates;';
		$html 	.= '	}';

		$html 	.= '	function popDate(temDatesArr, current_date)';
		$html 	.= '	{';
			        
		$html 	.= '	}';

		$html 	.= '	$(document).ready(function() { ';

		$html 	.= '	    getSyndicateValues();';

		$html 	.= '	    $("#cancel1").click(function( event ) {';
		$html 	.= '	        var dates = $("#altField").val();';
		$html 	.= '	        if(dates == "")';
		$html 	.= '	        {';
			            //alert('Please select dates before canceling');
		$html 	.= '	        }else{';
		$html 	.= '	            var conf = confirm("Would you like to cancel the booking ?");';
		$html 	.= '	            if(conf == true)';
		$html 	.= '	            {';
		$html 	.= '	                $("#status").val(0);';
		$html 	.= '	            }else{';
		$html 	.= '	                $("#status").val(1);';
		$html 	.= '	            }';
		$html 	.= '	        }';	
		$html 	.= '	    });';
			    
		$html 	.= '	    $("#cancel2").click(function(){';
		$html 	.= '	        var dates = $("#altField").val();';
		$html 	.= '	        if(dates == "")';
		$html 	.= '	        {';
			            //alert('Please select dates before canceling');
		$html 	.= '	        }else{';
		$html 	.= '	            var elem = $(this).closest(".item");';
		$html 	.= '	            $.confirm({';
		$html 	.= '	                "title"		: "Delete Confirmation",';
		$html 	.= '	                "message"	: "Would you like to cancel the booking ?",';
		$html 	.= '	                "buttons"	: {';
		$html 	.= '	                    "Deduct"	: {';
		$html 	.= '	                        "class"	: "blue",';
		$html 	.= '	                        "action": function(){';
			                            // should not recredit the days canceled to owner account
		$html 	.= '	                            $("#recredit").val(0);';
		$html 	.= '	                        }';
		$html 	.= '	                    },';
		$html 	.= '	                    "Re-credit"	: {';
		$html 	.= '	                        "class"	: "grey",';
		$html 	.= '	                        "action": function(){';
			                            // need to recredit the number of days to owner account
		$html 	.= '	                            $("#recredit").val(1);';
		$html 	.= '	                        }';	
		$html 	.= '	                    },';
		$html 	.= '	                    "Cancel" : {';
		$html 	.= '	                        "class"	: "blue",';
		$html 	.= '	                        "action": function(){';
		$html 	.= '	                            $("#recredit").val(-1);';
		$html 	.= '	                        } ';
		$html 	.= '	                    } ';
		$html 	.= '	                }';
		$html 	.= '	            });';
		$html 	.= '	        }';
			        
		$html 	.= '	    }); ';
			    
		$html 	.= '	    $("#dialog").dialog({';
		$html 	.= '	       autoOpen: false,';
		$html 	.= '	       modal: true,';
		$html 	.= '	       resize: false,';
		$html 	.= '	       buttons : {';
		$html 	.= '	            "Deduct" : function() {  ';
			            
		$html 	.= '	            	fix_dialog_action = "deduct";';
			            
		$html 	.= '	                $(this).dialog("close");   ';       
		$html 	.= '	            },';
		$html 	.= '	            "Re-credit" : function() {';
		$html 	.= '	            	fix_dialog_action = "recredit";';
			            	
		$html 	.= '	                $(this).dialog("close"); ';           
		$html 	.= '	            },';
		$html 	.= '	            "Cancel" : function() {';
		$html 	.= '	            	fix_dialog_action = "cancel";';
			            	
		$html 	.= '	                $(this).dialog("close");';
		$html 	.= '	            }';
		$html 	.= '	       },';
		$html 	.= '		   close: function(event, ui){';
		$html 	.= '		      fix_dialog_updateDates();';
		$html 	.= '		   }';
		$html 	.= '	    });';
			    
		$html 	.= '	    $("#cancel").on("click", function(e) {';
			    
		$html 	.= '	        var result = confirm("Cancel changes?");';
		$html 	.= '	        if(result)';
		$html 	.= '	        {';
		$html 	.= '				fix_reset_calendar(fix_loaded_date_data);';
						
						//$('#fullview-booking-save')[0].reset();
		$html 	.= '				$("#fullview-booking-save input[type=hidden]").val("");';
						
		$html 	.= '				$("#services-id").val("");';
						
		$html 	.= '				fix_user_change_count = 0;';
		$html 	.= '				fix_prev_user_id = null;';

		$html 	.= '	        }';
		$html 	.= '	    });';
			            
		$html 	.= '	    $("#maxPicks").multiDatesPicker({';			
			            //maxPicks: 3,
		$html 	.= '	            numberOfMonths: 3,';
			            //addDates: datesArr,
		$html 	.= '	            showCurrentAtPos: 1,';
		$html 	.= '	            maxDate: "+1Y",';
		$html 	.= '	            onSelect: function(dateText, inst) {';
		$html 	.= '	                            dateDetails = dateText.split("\/");';
										
										//console.log("###########################################");
		$html 	.= '								currentMonth = (new Date()).getMonth();';
		$html 	.= '								currentYear = (new Date).getFullYear(); ';
		$html 	.= '								if( monthSlide == 0 ){';
		$html 	.= '									dt = new Date();';
		$html 	.= '								}else{';
											//console.log(currentMonth);
											//console.log(monthSlide);

		$html 	.= '									configMonth = (currentMonth + monthSlide) % 12 ;';
		$html 	.= '									configYear  =  currentYear + parseInt( (currentMonth + monthSlide) / 12 ) ;';

											//console.log(configMonth);
											//console.log(configYear);
		$html 	.= '									dt = new Date(configYear,configMonth,1 );';

		$html 	.= '								}';
										//console.log(dt);
		$html 	.= '	                            var newDate = dt;';
			                            //var newDate = new Date();
		$html 	.= '								inst.drawMonth = newDate.getMonth();';
		$html 	.= '	                            inst.drawYear = newDate.getFullYear();';
		$html 	.= '	                            inst.currentMonth = newDate.getMonth();';
		$html 	.= '	                            inst.currentYear = newDate.getFullYear();';
		$html 	.= '	                            inst.input.trigger("change");';
		$html 	.= '	                        }'; 
		$html 	.= '	        });';
		$html 	.= '			$(".ui-datepicker-next").live("click",function(){';
		$html 	.= '				monthSlide += 1;';
		$html 	.= '			});';
		$html 	.= '			$(".ui-datepicker-prev").live("click",function(){';
		$html 	.= '				monthSlide -= 1;';
		$html 	.= '			});';
		$html 	.= '	        addColors(globalDates);';
		$html 	.= '	    });	    ';

		$html 	.= '	</script>';

		$html 	.= '<div id="primary">';
		$html 	.= '	<div id="content" role="main">';
		$html 	.= '         <div class="booking-error-message">';
								if(isset($_SESSION['booking-error-message'])) { 
									$html 	.= $_SESSION['booking-error-message']; 
									unset($_SESSION['booking-error-message']); 
								} 
		$html 	.= '		 </div>';
		$html 	.= '         <input type="hidden" name="session" value="'.$_SESSION['submit-booking-error-message'].'" />';
		$html 	.= '         <form name="fullview-booking-save" method="post" id="fullview-booking-save" action="?page_id='.$attr["function_page_no"].'" onsubmit="return validateForm();" enctype="multipart/form-data">';
		$html 	.= '         <div class="fullview-booking">';
		$html 	.= '          <h1>Booking Management</h1>';
		$html 	.= '           <div class="top">';
		$html 	.= '            <div><label>Choose a Syndicate : </label> ';
		$html 	.= '            <select name="syndicate_list" id="syndicate_list" onchange="getSyndicateValues()">';
		                        	global $wpdb;
		                            $sql1 = "SELECT id, syndicate FROM wp_syndicate_details where status = 1";
		                            $results_total = $wpdb->get_results($sql1);
		                            foreach ($results_total as $key) { 
										$html 	.= '<option value="'.$key->id.'" ';
										if ($_GET["syndicate_id"]==$key->id){ 
										$html 	.= ' selected="selected" ';
										} else {} 
										$html 	.= '>'.$key->syndicate.'</option>';
		                        	}
		$html 	.= '            </select>';
		$html 	.= '            </div>';
		         
		$html 	.= '         <div class="calender-btns">';
		           // <!-- <input type="button" value="add" class="add" id="add" /> -->
		$html 	.= '            <input type="button" value="cancel" class="cancel" id="cancel" />';
		$html 	.= '            <input type="submit" name="save-changes" value="save changes" class="save-changes" id="save" />';
		$html 	.= '         </div> ';
				
		$html 	.= '        <div id="maxPicks">';
		        
		$html 	.= '        <div class="prev_month">Previous Month</div>';
		$html 	.= '        <div class="current_month">Current Month</div>';
		$html 	.= '        <div class="next_month">Next Month</div>';
		        
		$html 	.= '        </div>';
                            
		                $syndicate = isset($_REQUEST['syndicate_id']) ? $_REQUEST['syndicate_id'] : 1;
						$owner = "SELECT * FROM wp_owner_details where syndicate = '".$syndicate."' and status = 1 ";
						$owner_total = $wpdb->get_results($owner);
				                foreach ($owner_total as $owner_key) {
									$owner_syndicate = $owner_key->syndicate;
								} 
		$html 	.= '<div class="color-picker">';         

		$html 	.= '</div>';
		$html 	.= '                 <input type="hidden" name="dates" id="altField" value="" />';
		$html 	.= '                 <input type="hidden" name="booking_dates" id="booking_dates" value="" />';
		$html 	.= '                 <input type="hidden" name="user_id" id="user_id" value="" />';
		$html 	.= '                 <input type="hidden" name="services" id="services-id" value="" />';
		$html 	.= '                 <input type="hidden" name="status" id="status" value="1" />';
		$html 	.= '                 <input type="hidden" name="dates_count" id="dates_count" value="" />';
		$html 	.= '                 <input type="hidden" name="owner-booking-page" id="owner_booking_page" value="0" />';
		$html 	.= '                 <input type="hidden" name="today" id="today" value="" />';
		$html 	.= '                 <input type="hidden" name="action" id="action" value="" />';
		$html 	.= '                 <input type="hidden" name="booking_type" id="booking_type" value="" />';
		         
		$html 	.= '                 <input type="hidden" name="dates_to_remove" id="dates_to_remove" value="" />';
		$html 	.= '                 <input type="hidden" name="recredit" id="recredit" value="" />';
		$html 	.= '                 <input type="hidden" name="dates_to_add" id="dates_to_add" value="" />';
		$html 	.= '            	 </form>';

		$html 	.= '</div>';
		$html 	.= '</div>';
		$html 	.= '</div>';
		$html 	.= '<div id="dialog" title="Would you like to cancel the booking ?"></div>';

		$html 	.= '<script type="text/javascript">';

		$html 	.= 'function validateForm(deleteButton)';
		$html 	.= '{';

			// eh lets just insert todays date here
		$html 	.= '	var dtoday = new Date();';
		$html 	.= '	var dday = dtoday.getDate();';
		$html 	.= '	var dmonth = dtoday.getMonth()+1;';
		$html 	.= '	var dyear = dtoday.getFullYear();';
		$html 	.= '	var dtoday = dmonth+"/"+dday+"/"+dyear;';

		$html 	.= '    $("#today").val(dtoday);';
		    
		    
		$html 	.= '    var dates_to_remove = $("#dates_to_remove").val();';
		$html 	.= '    var dates_to_add = $("#dates_to_add").val();';
		    //var userId = $('#user_id').val();
		    //var servicesId = $('#services-id').val();
		    //var status = parseInt($('#status').val());
		    
		    // just make sure there is something to send
		$html 	.= '    var dodgy_test = dates_to_remove.length + dates_to_add.length;';
		$html 	.= '    if(dodgy_test > 0)';
		$html 	.= '    {';
		    	/*
		        var booking_dates = $('#booking_dates').val();
		        if(booking_dates == '')
		        { 
		            alert('Please select Dates');
		            return false;   
		        }
		        */
		$html 	.= '        return true;';
		$html 	.= '    }else {';
		$html 	.= '		alert("Need to modify dates first.");';
		$html 	.= '		return false;';
		$html 	.= '    }';
		    /*
		    var conf = confirm('Would you like to save the changes made?');
		    if(conf == true)
		    {
		        //$('#fullview-booking-save').submit();
		        return true;   
		    }else{
		        return false;            
		    }
		    */
		$html 	.= '    return false;';
		$html 	.= '}';

		$html 	.= 'var fix_user_change_count = 0;';
		$html 	.= 'var fix_prev_user_id = null;';

		$html 	.= 'function updateOwner(id)';
		$html 	.= '{';
			// if we are switching users, check with user about losing data
		$html 	.= '	if(fix_user_change_count != 0) {';
			
		$html 	.= '		var result = confirm("Changing user will lose all unsaved data");';
		$html 	.= '		if(result) {';
		$html 	.= '			fix_reset_calendar(fix_loaded_date_data);';
		$html 	.= '			$(".color-picker input[id="+id+"]").attr("checked","checked");';
					
					//$('#fullview-booking-save')[0].reset();
		$html 	.= '			$("#fullview-booking-save input[type=hidden]").val("");';
					
		$html 	.= '			$("#user_id").val(id);';
		$html 	.= '			$("#services-id").val("");';
		$html 	.= '			fix_prev_user_id = id;';
				
		$html 	.= '		} else {';
		$html 	.= '			$(".color-picker input[id="+fix_prev_user_id+"]").attr("checked","checked");';
		$html 	.= '		}';
		$html 	.= '    }';
		    
		    // set initial values
		$html 	.= '    if(fix_prev_user_id == null) {';
		$html 	.= '		$("#user_id").val(id);';
		$html 	.= '		$("#services-id").val("");';
		$html 	.= '    	fix_prev_user_id = id;';
		$html 	.= '    }';
		$html 	.= '	fix_user_change_count++;';
		$html 	.= '}  ';  

		$html 	.= 'function updateServices(id)';
		$html 	.= '{';
		$html 	.= '    $("#user_id").val("");';
		$html 	.= '    $("#services-id").val(id);';
		$html 	.= '} ';   
		$html 	.= '</script>';
		
		$html   .= '<script src="'.plugin_dir_url(__FILE__).'../../assets/addons/js/jquery1.10.3-ui.min.js" type="text/javascript"></script> ';
		$html 	.= '<script type="text/javascript" src="'.plugin_dir_url(__FILE__).'../../assets/addons/js/jquery-ui.multidatespicker.js"></script>';
        
		$html 	.= '<link rel="stylesheet" type="text/css" href="'.plugin_dir_url(__FILE__).'../../assets/addons/css/mdp.css" />';
		$html 	.= '<link rel="stylesheet" type="text/css" href="'.plugin_dir_url(__FILE__).'../../assets/addons/css/prettify.css" />';
		$html 	.= '<script type="text/javascript" src="'.plugin_dir_url(__FILE__).'../../assets/addons/js/prettify.js"></script>';
		$html 	.= '<script type="text/javascript" src="'.plugin_dir_url(__FILE__).'../../assets/addons/js/lang-css.js"></script>';

	    $html 	.= '<script type="text/javascript" src="'.plugin_dir_url(__FILE__).'../../assets/addons/js/jquery.easy-confirm-dialog.js"></script>';

		return $html;
	}

	add_shortcode( 'fullview-booking', 'fullview_booking' );

?>