/*
 * MultiDatesPicker v1.6.1
 * http://multidatespickr.sourceforge.net/
 * 
 * Copyright 2011, Luca Lauretta
 * Dual licensed under the MIT or GPL version 2 licenses.
 */
var selectedDates = new Array();
function getMaxDateFromArray(dateArray){
   maxDate = new Date(1970, 1 ,1) ;
   $.each(dateArray,function(i,dateValue){       
       if( maxDate < dateValue){
           maxDate = dateValue ;
       }
   })   
   return maxDate ;
}

function getMinDateFromArray(dateArray){
   minDate = new Date(2070, 1 ,1);
   $.each(dateArray,function(i,dateValue){
       if( minDate > dateValue){
           minDate = dateValue ; 
       }
   })
   return minDate ;
}

(function( $ ){
	$.extend($.ui, { multiDatesPicker: { version: "1.6.1" } });
	
	$.fn.multiDatesPicker = function(method) {
		var mdp_arguments = arguments;
		var ret = this;
		var today_date = new Date();
		var day_zero = new Date(0);
		var mdp_events = {};
		
		function removeDate(date, type) {
			if(!type) type = 'picked';
			// sigh
			orig_date = date;
			date = dateConvert.call(this, date);
            if( window.location.pathname.match("/booking/") != null){
//console.log(selectedDates);
                if(selectedDates.length > 0){                
                
                    maxDate = getMaxDateFromArray(selectedDates); 
                    minDate = getMinDateFromArray(selectedDates);

                    if( ( date - minDate)  == 86400000 && ( maxDate - date ) == 86400000 ){
               				alert("You can only book more than 1 day per session if they are consecutive.");     
											return false;
                    }
                
                } 
                remove_from_anniversary_bucket(orig_date);
            }
            if( window.location.pathname.match("/full-view-booking/") != null){
            //console.log($('#maxPicks').multiDatesPicker('getDates'));
            	var selected_user_id = $('input[name=color]:radio:checked').attr('id');
    			//var currentSelectedColor = $('input[name=color]:radio:checked').val();
            		
            		//console.log(orig_date);
            		//console.log(fix_data_loading);
            		//console.log(selected_user_id);
            	// hacks on hacks on hacks
            	if(fix_data_loading != true && selected_user_id != null) {
            	//if(fix_data_loading != true) {
            		var owner_id = find_date_owner(date);
            		//console.log(owner_id);
            		//console.log(selected_user_id);
            		// only allow dates from the current selected user to be modified
            		if(owner_id != false && owner_id != selected_user_id) {
            			return false;
            		}
            		//$('#action').val('remove');
            		//console.log('remove');
            		
            			//console.log('wat0');
            			
            		var result = fix_updateDates(orig_date, 'remove');
            		
            			//console.log('2.1 '+result);
            		if(!result) {
            			//console.log('2.2 '+result);
            			return false;
            		}
            		//return true;
            	}
            }

                        $.each(selectedDates,function(i,dateValue){
                            if( dateValue != null && dateValue.getTime() == date.getTime() ){
//console.log('um1');
                                maxDate = getMaxDateFromArray(selectedDates); 
                                minDate = getMinDateFromArray(selectedDates); 
                                   selectedDates.splice(i,1); 
                               
                                
                            }
                        });
                        
			for(var i in this.multiDatesPicker.dates[type]) {
//console.log('um2');
				if(!methods.compareDates(this.multiDatesPicker.dates[type][i], date)) {

					return this.multiDatesPicker.dates[type].splice(i, 1).pop();
				
				}
			
			}
		}
		function removeIndex(index, type) {
			if(!type) type = 'picked';
			return this.multiDatesPicker.dates[type].splice(index, 1).pop();
		}
		function addDate(date, type, no_sort) {
		
			
            if( window.location.pathname.match("/full-view-booking/") != null){
            
            	var selected_user_id = $('input[name=color]:radio:checked').attr('id');
            		
            	// hacks on hacks on hacks
            	if(fix_data_loading != true && selected_user_id != null) {
            
            		var owner_id = find_date_owner(date);
            		
            		// only allow dates from the current selected user to be modified
            		if(owner_id != false && owner_id != selected_user_id) {
            			return false;
            		}
            		//console.log('add');
            		var result = fix_updateDates(date, 'add');
            		if(!result) {
            			return false;
            		}
            		
    				//var currentSelectedColor = $('input[name=color]:radio:checked').val();
					//$('.ui-datepicker-current-day').css('background','red');
            	}
            }
		
		    var owner_booking_page = $("#owner_booking_page").val();  
                    prev_selected_dates = $("#booking_dates").val() ;            
                    selection = prev_selected_dates.split(";");            
                    reject = false ;
                    if( window.location.pathname.match("/booking/") != null){

select_anniversary_bucket(date);
					if(!valid_selection(date)) {
						return false;
					}

                        if(selectedDates.length > 0){                
                
                            currentSelectedDate = $("#altField").val() ;
                            currentSelectedDateFormated = new Date(currentSelectedDate);
                                
                            maxDate = getMaxDateFromArray(selectedDates); 
                            minDate = getMinDateFromArray(selectedDates);

                    if( ( minDate - currentSelectedDateFormated )  == 86400000 || (currentSelectedDateFormated - maxDate ) == 86400000 ){
                    
                    }else{
                        alert("You can only book more than 1 day per session if they are consecutive.");
                        return false;
                    }         
                          
                
                }
                
                /*
                only book future dates
                */
				var bookable_from = new Date();
				var fix_select = new Date(date);
        console.log(fix_select);
        console.log(bookable_from);
				if(fix_select < bookable_from) {
					alert("You can only book dates in the future.");
					return false;
				}  
            }
            
         
            if( $("input:checked").hasClass("limited_select") ){
                var user_id = $("#user_id").val();
                var userCount = getUserSelctionCount(user_id, selection);
                if(userCount > 2){
                    alert("Can't select more then 3 days for syndicate booking");
                    return false;
                }
            }   
            
            if(owner_booking_page)
            {
                booking_count = parseInt($("#booking_count").val());                
                if(booking_count == 3){
                    alert("Can't select more then 3 days for syndicate booking");
                    return false;
                }
            } 
            
            
            if( window.location.pathname.match("/booking/") != null){
                //console.log('ajh trace: updateDates - click date, check anniversary bucket');
    
    bucket = update_anniversary_bucket(date);
    
    if(bucket) {
    	update_count(bucket);
    }
            }
            
            
            selectedDates.push( new Date( $("#altField").val() ) ) ;                      
			if(!type) type = 'picked';
			date = dateConvert.call(this, date);
			
			// @todo: use jQuery UI datepicker method instead
			date.setHours(0);
			date.setMinutes(0);
			date.setSeconds(0);
			date.setMilliseconds(0);
			
			if (methods.gotDate.call(this, date, type) === false) {
				this.multiDatesPicker.dates[type].push(date);
				if(!no_sort) this.multiDatesPicker.dates[type].sort(methods.compareDates);
			} 
		}
        
        function getUserSelctionCount(user_id, selection)
        {
            var  count = 0;
            for(i in selection)
            {
                var temp = selection[i];
                var tempArray = [];
                tempArray = temp.split(',');
                if(tempArray[1])
                {
                    if(tempArray[1] == user_id)
                    {
                        count++;
                    }
                }
            }
            return count;
        }
        
		function sortDates(type) {
			if(!type) type = 'picked';
			this.multiDatesPicker.dates[type].sort(methods.compareDates);
		}
		function dateConvert(date, desired_type, date_format) {
			if(!desired_type) desired_type = 'object';/*
			if(!date_format && (typeof date == 'string')) {
				date_format = $(this).datepicker('option', 'dateFormat');
				if(!date_format) date_format = $.datepicker._defaults.dateFormat;
			}
			*/
			return methods.dateConvert.call(this, date, desired_type, date_format);
		}
		
		var methods = {
			init : function( options ) {
				var $this = $(this);
				this.multiDatesPicker.changed = false;
				
				var mdp_events = {
					beforeShow: function(input, inst) {
						this.multiDatesPicker.changed = false;
						if(this.multiDatesPicker.originalBeforeShow) 
							this.multiDatesPicker.originalBeforeShow.call(this, input, inst);
					},
					onSelect : function(dateText, inst) {
						var $this = $(this);
						this.multiDatesPicker.changed = true;
						if( $("input:checked").hasClass("limited_select") == false && $("input:checked").hasClass("unlimited_select") == false){
							this.multiDatesPicker.originalOnSelect.call(this, dateText, inst);
							alert("Please select booking type");
							return false;
						}
						if (dateText) {
							$this.multiDatesPicker('toggleDate', dateText);
						}
						
						if (this.multiDatesPicker.mode == 'normal' && this.multiDatesPicker.dates.picked.length > 0 && this.multiDatesPicker.pickableRange) {
							var min_date = this.multiDatesPicker.dates.picked[0],
								max_date = new Date(min_date.getTime());
							
							methods.sumDays(max_date, this.multiDatesPicker.pickableRange-1);
								
							// counts the number of disabled dates in the range
							if(this.multiDatesPicker.adjustRangeToDisabled) {
								var c_disabled, 
									disabled = this.multiDatesPicker.dates.disabled.slice(0);
								do {
									c_disabled = 0;
									for(var i = 0; i < disabled.length; i++) {
										if(disabled[i].getTime() <= max_date.getTime()) {
											if((min_date.getTime() <= disabled[i].getTime()) && (disabled[i].getTime() <= max_date.getTime()) ) {
												c_disabled++;
											}
											disabled.splice(i, 1);
											i--;
										}
									}
									max_date.setDate(max_date.getDate() + c_disabled);
								} while(c_disabled != 0);
							}
							
							if(this.multiDatesPicker.maxDate && (max_date > this.multiDatesPicker.maxDate))
								max_date = this.multiDatesPicker.maxDate;
							
							$this
								.datepicker("option", "minDate", min_date)
								.datepicker("option", "maxDate", max_date);
						} else {
							$this
								.datepicker("option", "minDate", this.multiDatesPicker.minDate)
								.datepicker("option", "maxDate", this.multiDatesPicker.maxDate);
						}
						
						if(this.tagName == 'INPUT') { // for inputs
							$this.val(
								$this.multiDatesPicker('getDates', 'string')
							);
						}
						
						if(this.multiDatesPicker.originalOnSelect && dateText)
							this.multiDatesPicker.originalOnSelect.call(this, dateText, inst);
						
						// thanks to bibendus83 -> http://sourceforge.net/tracker/?func=detail&atid=1495384&aid=3403159&group_id=358205
						if ($this.datepicker('option', 'altField') != undefined && $this.datepicker('option', 'altField') != "") {
							$($this.datepicker('option', 'altField')).val(
								$this.multiDatesPicker('getDates', 'string')
							);
						}
					},
					beforeShowDay : function(date) {
						var $this = $(this),
							gotThisDate = $this.multiDatesPicker('gotDate', date) !== false,
							isDisabledCalendar = $this.datepicker('option', 'disabled'),
							isDisabledDate = $this.multiDatesPicker('gotDate', date, 'disabled') !== false,
							areAllSelected = this.multiDatesPicker.maxPicks == this.multiDatesPicker.dates.picked.length;
						
						var custom = [true, ''];
						if(this.multiDatesPicker.originalBeforeShowDay)
							custom = this.multiDatesPicker.originalBeforeShowDay.call(this, date);
						
						var highlight_class = gotThisDate ? 'ui-state-highlight' : custom[1];
						var selectable_date = !(isDisabledCalendar || isDisabledDate || (areAllSelected && !highlight_class));
						return [selectable_date && custom[0], highlight_class];
					},
					onClose: function(dateText, inst) {
						if(this.tagName == 'INPUT' && this.multiDatesPicker.changed) {
							$(inst.dpDiv[0]).stop(false,true);
							setTimeout('$("#'+inst.id+'").datepicker("show")',50);
						}
						if(this.multiDatesPicker.originalOnClose) this.multiDatesPicker.originalOnClose.call(this, dateText, inst);
					}
				};
				
				if(options) {
					this.multiDatesPicker.originalBeforeShow = options.beforeShow;
					this.multiDatesPicker.originalOnSelect = options.onSelect;
					this.multiDatesPicker.originalBeforeShowDay = options.beforeShowDay;
					this.multiDatesPicker.originalOnClose = options.onClose;
					
					$this.datepicker(options);
					
					this.multiDatesPicker.minDate = $.datepicker._determineDate(this, options.minDate, null);
					this.multiDatesPicker.maxDate = $.datepicker._determineDate(this, options.maxDate, null);
					
					if(options.addDates) methods.addDates.call(this, options.addDates);
					if(options.addDisabledDates)
						methods.addDates.call(this, options.addDisabledDates, 'disabled');
					
					methods.setMode.call(this, options);
				} else {
					$this.datepicker();
				}
				
				$this.datepicker('option', mdp_events);
				
				if(this.tagName == 'INPUT') $this.val($this.multiDatesPicker('getDates', 'string'));
				
				// Fixes the altField filled with defaultDate by default
				var altFieldOption = $this.datepicker('option', 'altField');
				if (altFieldOption) $(altFieldOption).val($this.multiDatesPicker('getDates', 'string'));
			},
			compareDates : function(date1, date2) {
				date1 = dateConvert.call(this, date1);
				date2 = dateConvert.call(this, date2);
				// return > 0 means date1 is later than date2 
				// return == 0 means date1 is the same day as date2 
				// return < 0 means date1 is earlier than date2 
				var diff = date1.getFullYear() - date2.getFullYear();
				if(!diff) {
					diff = date1.getMonth() - date2.getMonth();
					if(!diff) 
						diff = date1.getDate() - date2.getDate();
				}
				return diff;
			},
			sumDays : function( date, n_days ) {
				var origDateType = typeof date;
				obj_date = dateConvert.call(this, date);
				obj_date.setDate(obj_date.getDate() + n_days);
				return dateConvert.call(this, obj_date, origDateType);
			},
			dateConvert : function( date, desired_format, dateFormat ) {
				var from_format = typeof date;
				
				if(from_format == desired_format) {
					if(from_format == 'object') {
						try {
							date.getTime();
						} catch (e) {
							$.error('Received date is in a non supported format!');
							return false;
						}
					}
					return date;
				}
				
				var $this = $(this);
				if(typeof date == 'undefined') date = new Date(0);
				
				if(desired_format != 'string' && desired_format != 'object' && desired_format != 'number')
					$.error('Date format "'+ desired_format +'" not supported!');
				
				if(!dateFormat) {
					dateFormat = $.datepicker._defaults.dateFormat;
					
					// thanks to bibendus83 -> http://sourceforge.net/tracker/index.php?func=detail&aid=3213174&group_id=358205&atid=1495382
					var dp_dateFormat = $this.datepicker('option', 'dateFormat');
					if (dp_dateFormat) {
						dateFormat = dp_dateFormat;
					}
				}
				
				// converts to object as a neutral format
				switch(from_format) {
					case 'object': break;
					case 'string': date = $.datepicker.parseDate(dateFormat, date); break;
					case 'number': date = new Date(date); break;
					default: $.error('Conversion from "'+ desired_format +'" format not allowed on jQuery.multiDatesPicker');
				}
				// then converts to the desired format
				switch(desired_format) {
					case 'object': return date;
					case 'string': return $.datepicker.formatDate(dateFormat, date);
					case 'number': return date.getTime();
					default: $.error('Conversion to "'+ desired_format +'" format not allowed on jQuery.multiDatesPicker');
				}
				return false;
			},
			gotDate : function( date, type ) {
				if(!type) type = 'picked';
				for(var i = 0; i < this.multiDatesPicker.dates[type].length; i++) {
					if(methods.compareDates.call(this, this.multiDatesPicker.dates[type][i], date) === 0) {
						return i;
					}
				}
				return false;
			},
			getDates : function( format, type ) {
				if(!format) format = 'string';
				if(!type) type = 'picked';
				switch (format) {
					case 'object':
						return this.multiDatesPicker.dates[type];
					case 'string':
					case 'number':
						var o_dates = new Array();
						for(var i in this.multiDatesPicker.dates[type])
							o_dates.push(
								dateConvert.call(
									this, 
									this.multiDatesPicker.dates[type][i], 
									format
								)
							);
						return o_dates;
					
					default: $.error('Format "'+format+'" not supported!');
				}
			},
			addDates : function( dates, type ) {
				if(dates.length > 0) {
					if(!type) type = 'picked';
					switch(typeof dates) {
						case 'object':
						case 'array':
							if(dates.length) {
								for(var i in dates)
									addDate.call(this, dates[i], type, true);
								sortDates.call(this, type);
								break;
							} // else does the same as 'string'
						case 'string':
						case 'number':
							addDate.call(this, dates, type);
							break;
						default: 
							$.error('Date format "'+ typeof dates +'" not allowed on jQuery.multiDatesPicker');
					}
					$(this).datepicker('refresh');
				} else {
					$.error('Empty array of dates received.');
				}
			},
			removeDates : function( dates, type ) {
				if(!type) type = 'picked';
				var removed = [];
				if (Object.prototype.toString.call(dates) === '[object Array]') {
					for(var i in dates.sort(function(a,b){return b-a})) {
						removed.push(removeDate.call(this, dates[i], type));
					}
				} else {
					removed.push(removeDate.call(this, dates, type));
				}
				$(this).datepicker('refresh');
				return removed;
			},
			removeIndexes : function( indexes, type ) {
				if(!type) type = 'picked';
				var removed = [];
				if (Object.prototype.toString.call(indexes) === '[object Array]') {
					for(var i in indexes.sort(function(a,b){return b-a})) {
						removed.push(removeIndex.call(this, indexes[i], type));
					}
				} else {
					removed.push(removeIndex.call(this, indexes, type));
				}
				$(this).datepicker('refresh');
				return removed;
			},
			resetDates : function ( type ) {
				if(!type) type = 'picked';
				this.multiDatesPicker.dates[type] = [];
				$(this).datepicker('refresh');
			},
			toggleDate : function( date, type ) {
				if(!type) type = 'picked';
				
				switch(this.multiDatesPicker.mode) {
					case 'daysRange':
						this.multiDatesPicker.dates[type] = []; // deletes all picked/disabled dates
						var end = this.multiDatesPicker.autoselectRange[1];
						var begin = this.multiDatesPicker.autoselectRange[0];
						if(end < begin) { // switch
							end = this.multiDatesPicker.autoselectRange[0];
							begin = this.multiDatesPicker.autoselectRange[1];
						}
						for(var i = begin; i < end; i++) 
							methods.addDates.call(this, methods.sumDays(date, i), type);
						break;
					default:
						if(methods.gotDate.call(this, date) === false) // adds dates
							methods.addDates.call(this, date, type);
						else // removes dates
							methods.removeDates.call(this, date, type);
						break;
				}
			}, 
			setMode : function( options ) {
				var $this = $(this);
				if(options.mode) this.multiDatesPicker.mode = options.mode;
				
				switch(this.multiDatesPicker.mode) {
					case 'normal':
						for(option in options)
							switch(option) {
								case 'maxPicks':
								case 'minPicks':
								case 'pickableRange':
								case 'adjustRangeToDisabled':
									this.multiDatesPicker[option] = options[option];
									break;
								//default: $.error('Option ' + option + ' ignored for mode "'.options.mode.'".');
							}
					break;
					case 'daysRange':
					case 'weeksRange':
						var mandatory = 1;
						for(option in options)
							switch(option) {
								case 'autoselectRange':
									mandatory--;
								case 'pickableRange':
								case 'adjustRangeToDisabled':
									this.multiDatesPicker[option] = options[option];
									break;
								//default: $.error('Option ' + option + ' does not exist for setMode on jQuery.multiDatesPicker');
							}
						if(mandatory > 0) $.error('Some mandatory options not specified!');
					break;
				}
				
				/*
				if(options.pickableRange) {
					$this.datepicker("option", "maxDate", options.pickableRange);
					$this.datepicker("option", "minDate", this.multiDatesPicker.minDate);
				}
				*/
				
				if(mdp_events.onSelect)
					mdp_events.onSelect();
				$this.datepicker('refresh');
			}
		};
		
		this.each(function() {
			if (!this.multiDatesPicker) {
				this.multiDatesPicker = {
					dates: {
						picked: [],
						disabled: []
					},
					mode: 'normal',
					adjustRangeToDisabled: true
				};
			}
			
			if(methods[method]) {
				var exec_result = methods[method].apply(this, Array.prototype.slice.call(mdp_arguments, 1));
				switch(method) {
					case 'getDates':
					case 'removeDates':
					case 'gotDate':
					case 'sumDays':
					case 'compareDates':
					case 'dateConvert':
						ret = exec_result;
				}
				return exec_result;
			} else if( typeof method === 'object' || ! method ) {
				return methods.init.apply(this, mdp_arguments);
			} else {
				$.error('Method ' +  method + ' does not exist on jQuery.multiDatesPicker');
			}
			return false;
		});
		
		if(method != 'gotDate' && method != 'getDates') {
			aaaa = 1;
		}
		
		return ret;
	};

	var PROP_NAME = 'multiDatesPicker';
	var dpuuid = new Date().getTime();
	var instActive;

	$.multiDatesPicker = {version: false};
	//$.multiDatesPicker = new MultiDatesPicker(); // singleton instance
	$.multiDatesPicker.initialized = false;
	$.multiDatesPicker.uuid = new Date().getTime();
	$.multiDatesPicker.version = $.ui.multiDatesPicker.version;

	// Workaround for #4055
	// Add another global to avoid noConflict issues with inline event handlers
	window['DP_jQuery_' + dpuuid] = $;
})( jQuery );