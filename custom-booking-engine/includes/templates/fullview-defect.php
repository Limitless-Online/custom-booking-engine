<?php 
	
	function fullview_defect($attr){


		$attr = shortcode_atts( array(
            'function_page_no' => '509',
        ), $attr, 'fullview-defect' );


		$html 	 = "";
		$html 	.= '<script type="text/javascript" src="'.plugin_dir_url(__FILE__).'../../assets/addons/js/jquery-1.9.1.min.js" ></script>';
		global $wpdb;
		$query = "SELECT report.id, report.create_date, report.image, report.description, report.status,report.work_carried_out, syndicate.syndicate as syndicate_name, owner.first_name as first_name, owner.surname  as surname
		                                        FROM wp_report_defect as report 
		                                        join wp_users as users on users.id = report.user_id
		                                        join wp_owner_details as owner on owner.user_id = report.user_id
		                                        join wp_syndicate_details as syndicate on owner.syndicate_id = syndicate.id 
		                                        order by report.id desc";
		if(!isset($_REQUEST['viewall']))
		{
		    $query = $query . ' limit 3';    
		}
		//echo $query;
		$getDefectIssues = $wpdb->get_results($query);
		//<!-- SlidesJS Required: Link to jquery.slides.js -->
		$html 	.= '<script src="'.plugin_dir_url(__FILE__).'../../assets/addons/js/jquery.slides.min.js"></script>';
		//<!-- End SlidesJS Required -->
	    //<!-- SlidesJS Required: Initialize SlidesJS with a jQuery doc ready -->
		$html 	.= '<script>';
		$html 	.= '  $(function() {';
		$html 	.= '    $(".slides").slidesjs({';
		$html 	.= '      width: 438,';
		$html 	.= '      height: 252';
		$html 	.= '    });';
		$html 	.= '  });';
		$html 	.= '</script>';

		$html 	.= '<script type="text/javascript">';
		$html 	.= '    var defectIssuesData = new Array();';
		$html 	.= '	var idAssigned = false ;';
		$html 	.= '	var firstId ;';
		foreach($getDefectIssues as $getDefectIssuesData){
		
		//$html 	.= ' "<pre>";'.print_R($getDefectIssuesData);
		$html 	.= '    var defectData = new Array();';
		$html 	.= '	if( idAssigned == false ){';
		$html 	.= '		firstId = '. $getDefectIssuesData->id .';';
		$html 	.= '		idAssigned = true ;';
		$html 	.= '	}';
		$html 	.= '    defectData["create_date"] = "'.date("d/m/y", strtotime($getDefectIssuesData->create_date)).'";';
		$html 	.= '    defectData["syndicate_name"] = "'.$getDefectIssuesData->syndicate_name.'";';
		$html 	.= '    defectData["image"] = ".'.$getDefectIssuesData->image.'";';
		$html 	.= '    defectData["description"] = "'.trim($getDefectIssuesData->description).'";';
		$html 	.= '    defectData["work-carried-out"] = "'.trim($getDefectIssuesData->work_carried_out).'";';
		$html 	.= '    defectData["user_name"] = "'.$getDefectIssuesData->first_name.'"' +'" "'+'"'.$getDefectIssuesData->surname.'";';
		$html 	.= '    defectData["status"] = "'.$getDefectIssuesData->status.'";';
		$html 	.= '    defectIssuesData['.$getDefectIssuesData->id.'] = defectData;';

		} 
		//<!-- End SlidesJS Required -->
		$html 	.= '	$(document).ready(function(){';                 
		$html 	.= '         $("#report-in-detail-data").hide(); ';
		          
		$html 	.= '            function getUrlVars()';
		$html 	.= '            {';
		$html 	.= '                var vars = [], hash;';
		$html 	.= '                var hashes = window.location.href.slice(window.location.href.indexOf("?") + 1).split("&");';
		$html 	.= '                for(var i = 0; i < hashes.length; i++)';
		$html 	.= '                {';
		$html 	.= '                    hash = hashes[i].split("=");';
		$html 	.= '                    vars.push(hash[0]);';
		$html 	.= '                    vars[hash[0]] = hash[1];';
		$html 	.= '                }';
						//console.log(vars);
		$html 	.= '                return vars;';
		$html 	.= '            }';
		         
		$html 	.= '         var currentID = getUrlVars()["current_id"];';
		        // alert(firstId);
		$html 	.= '         if(currentID!=null){';
		$html 	.= '            getDetailedData(currentID); ';
		$html 	.= '         }else{';
		$html 	.= '           getDetailedData(firstId);  ';
		$html 	.= '         }';
		         
		$html 	.= '    });  ';   
		$html 	.= '</script>';

		$html 	.= '<div id="primary">';
		$html 	.= '    <div id="content" role="main">';
		$html 	.= '        <div class="bcknd management-area">';
		$html 	.= '            <form action="?page_id='.$attr["function_page_no"].'" method="post" id="fullview-defect" name="fullview-defect" onsubmit="return validateForm();">';
		$html 	.= '                <table>';
		$html 	.= '                    <tr>';
		$html 	.= '                        <td style="padding: 15px;">';
		$html 	.= '                            <div class="defects-issues">';
		$html 	.= '                                <h1>DEFECTS/ISSUES</h1>';
		$html 	.= '                                <div class="defects-issues-data" style="border-right: 1px solid silver; padding-right: 15px;">  ';                          
		$html 	.= '                                    <table name="defects-issues" id="defects-issues">';
		$html 	.= '                                        <tr>';
		$html 	.= '                                            <th class="date"><strong>DD/MM/YY</strong></th>';
		$html 	.= '                                            <th class="syndicate-name"><strong>SYNDICATE NAME</strong></th>';
		$html 	.= '                                            <th class="reportedby"><strong>REPORTED BY</strong></th>';
		$html 	.= '                                        </tr>';
		                                        $i=0; foreach($getDefectIssues as $getDefectIssuesData)
		                                            {                                     
		$html 	.= '                                            <div>';
		$html 	.= '                                                <tr style="border: 1px solid;">';
		$html 	.= '                                                    <td colspan="3">';
		$html 	.= '                                                        <table cellpadding="0" cellspacing="0" href="javascript:void(0)" onclick="getDetailedData('.$getDefectIssuesData->id.')" style="width: 100%" ';
																				if($i%2 == 0){
																				$html 	.= 'class="defect-table-'.$getDefectIssuesData->id.' even"';
																				}else{
																				$html 	.= 'class="defect-table-'.$getDefectIssuesData->id.' odd"';
																				} 
																				$html 	.= '>';
		$html 	.= '                                                            <tr><td>'.date("d/m/y", strtotime($getDefectIssuesData->create_date)).'</td>';
		$html 	.= '                                                                <td>'.$getDefectIssuesData->syndicate_name.'</td>';
		$html 	.= '                                                                <td>'.$getDefectIssuesData->first_name ." ".$getDefectIssuesData->surname.'</td>';
		$html 	.= '                                                            </tr>';
		$html 	.= '                                                            <tr style="border: 1px solid;">';
		$html 	.= '                                                                <td colspan="3" class="defect-desc">'.$getDefectIssuesData->description.'</td>';
		$html 	.= '                                                            </tr>';
		$html 	.= '                                                       </table>';                                                
		$html 	.= '                                                    </td>';
		$html 	.= '                                                </tr>';   
		$html 	.= '                                             </div>';
		                                        $i++; }                                    
		$html 	.= '                                    </table>';
		$html 	.= '                                    <div align="bottom"><input type="button" name="view-all" id="view-all" value="VIEW ALL" onclick="redirectSamePage()" /></div>';
		$html 	.= '                                </div>';
		$html 	.= '                            </div>';
		$html 	.= '                        </td>';
		$html 	.= '                        <td style="padding: 15px; width: 100%;">';
		$html 	.= '                            <div class="report-in-detail">';
		$html 	.= '                                <h1>REPORT IN DETAIL</h1>';
		$html 	.= '                                <div> <button name="delete" id="delete" value="Delete" class="defect-delete">DELETE</button></button></div>';
		$html 	.= '                                <div><span>';
													if(isset($_SESSION['fullview-defect-message'])){ echo $_SESSION['fullview-defect-message'];unset($_SESSION['fullview-defect-message']); }
		$html 	.= '								</span></div>';
		$html 	.= '                                <div id="report-in-detail-data">';
		$html 	.= '                                    <table>';
		$html 	.= '                                        <tr>';
		$html 	.= '                                            <td>';
		$html 	.= '                                                <div id="report-in-detail-data-image">';
		   // <!--                                                <span id="report-image"></span>-->
		$html 	.= '                                                    <div class="slides"></div>';
		$html 	.= '                                                </div>';
		$html 	.= '                                            </td>';
		$html 	.= '                                            <td valign="top">';
		$html 	.= '                                                <table class="report-details-spec">';
		$html 	.= '                                                    <tr><td>DATE LODGED :</td><td><span id="create_date" style="padding-left: 5px;"></span></td></tr>';
		$html 	.= '                                                    <tr><td>REPORTED BY :</td><td><span id="user_name" style="padding-left: 5px;"></span></td></tr>';
		$html 	.= '                                                    <tr><td>SYNDICATE :</td><td><span id="syndicate" style="padding-left: 5px;"></span></td></tr>';
		$html 	.= '                                                    <tr><td>STATUS :</td><td><span id="status" style="padding-left: 5px;"></span><input type="hidden" id="status-hidden" name="status-hidden" value="" /></td></tr>';
		$html 	.= '                                                </table>';                                        
		$html 	.= '                                            </td>';
		$html 	.= '                                        </tr>';
		$html 	.= '                                        <tr><td colspan="2"><input type="button" name="download-images" value="DOWNLOAD IMAGES" onclick="downloadImages()" />';
		$html 	.= '                                            <input type="hidden" name="defect-id" id="defect-id" value="" />';
		$html 	.= '                                            </td></tr>';
		$html 	.= '                                        <tr><td colspan="2"><h3>PROBLEM</h3><textarea name="problem" cols="120" rows="7" id="description"></textarea></td></tr>';
		$html 	.= '                                        <tr><td colspan="2"><h3>WORK CARRIED OUT</h3><textarea name="work-carried-out" id="work-carried-out"  cols="120" rows="7"></textarea></td></tr>';
		$html 	.= '                                        <tr>';
		$html 	.= '                                            <td width="50%"><input class="defect-rgt-btm-btn" id="resolved-button" type="button" name="resolved" value="RESOLVED" /></td>';
		$html 	.= '                                            <td width="50%"><input class="defect-rgt-btm-btn" id="unresolved-button" type="button" name="unresolved" value="UNRESOLVED" /></td>';
		$html 	.= '                                        </tr>';
		$html 	.= '                                        <tr>';
		$html 	.= '                                            <td width="50%">';
		$html 	.= '                                                <input type="submit" name="fullview-defect" value="SAVE" />';
		$html 	.= '                                                <input type="hidden" name="id" value="" id="id" />';
		$html 	.= '                                                <input type="hidden" name="url" value="" id="url" />';
		$html 	.= '                                                <input type="hidden" name="status_button" value="0" id="status_button" />';
		$html 	.= '                                            </td>';
		$html 	.= '                                            <td width="50%"><input type="button" name="download-as-pdf" value="DOWNLOAD AS PDF" onclick="downloadAsPDF()" /></td>';
		$html 	.= '                                        </tr>';
		$html 	.= '                                    </table>';
		$html 	.= '                                </div>';
		$html 	.= '                            </div>';
		$html 	.= '                        </td>';
		$html 	.= '                    </tr>';
		$html 	.= '                </table> ';
		$html 	.= '             </form>   ';        
		$html 	.= '        </div>';
		$html 	.= '    </div>';
		$html 	.= '</div>';


		$html 	.= '<script type="text/javascript">';
		$html 	.= '    function validateForm()';
		$html 	.= '    {';
		$html 	.= '        var work_carried_out = $("#work-carried-out").val();';
		$html 	.= '        var statusButtonClicked = parseInt($("#status_button").val());';
		$html 	.= '        if(work_carried_out == "" && !statusButtonClicked)';
		$html 	.= '        {';
		$html 	.= '            return false;';
		$html 	.= '        }else{';
		$html 	.= '            var conf = confirm("Would you like to save the changes made to defect?");';
		$html 	.= '            if(conf == true)';
		$html 	.= '            {';
		$html 	.= '                return true;';
		$html 	.= '            }else{';
		$html 	.= '                return false;';
		$html 	.= '            }';
		$html 	.= '        }';
		$html 	.= '    }';
		$html 	.= '    function getDetailedData(id)';
		$html 	.= '    {';
		$html 	.= '        removeAll();';
		        //currentData.class = 'current';
		        //console.log(currentData);
		$html 	.= '        $(".current").removeClass("current");';
		$html 	.= '        $(".defect-table-"+id).addClass("current");';
		$html 	.= '        $(".slides").empty();';
		$html 	.= '        var image = defectIssuesData[id]["image"];';
		$html 	.= '        var imageArray = image.split(",");';
		$html 	.= '        for(i in imageArray)';
		$html 	.= '        {';
		$html 	.= '            var img = $(document.createElement("img"));';
		$html 	.= '            img.attr("src",imageArray[i]);';
		$html 	.= '            img.attr("width","150px");';
		$html 	.= '            img.attr("height","150px");';
		$html 	.= '            img.appendTo(".slides");  ';                      
		$html 	.= '        }';
		$html 	.= '        $("#create_date").html(defectIssuesData[id]["create_date"]);';
		$html 	.= '        $("#user_name").html(defectIssuesData[id]["user_name"]);';
		$html 	.= '        $("#syndicate").html(defectIssuesData[id]["syndicate_name"]);';
		$html 	.= '        $("#status").html(defectIssuesData[id]["status"]);';
		$html 	.= '        $("#status-hidden").val(defectIssuesData[id]["status"]);  ';      
		        
		$html 	.= '        $(".slides").add(img);';
		$html 	.= '        $("textarea#description").val(defectIssuesData[id]["description"]);';
		$html 	.= '        $("textarea#work-carried-out").val(defectIssuesData[id]["work-carried-out"]);';        
		$html 	.= '        $("#defect-id").val(id);';
		$html 	.= '        $("#id").val(id);';
		$html 	.= '        $("#report-in-detail-data").show();';
		        
		$html 	.= '    } ';    
		$html 	.= '    function downloadImages()';
		$html 	.= '    {';
		$html 	.= '        type = "download";';
		$html 	.= '        id = $("#defect-id").val();';
		$html 	.= '		url = "'.get_site_url().'";';
		$html 	.= '        window.location.assign(url+"?page_id='.$attr["function_page_no"].'&type="+type+"&id="+id);';
		$html 	.= '    }';
		$html 	.= '    function downloadAsPDF()';
		$html 	.= '    {';
		$html 	.= '        type = "downloadaspdf";';
		$html 	.= '        id = $("#defect-id").val();';
		$html 	.= '		url = "'.get_site_url().'";';
		$html 	.= '        window.location.assign(url+"?page_id=500&type="+type+"&id="+id);';
		$html 	.= '    }';
		$html 	.= '    function redirectSamePage()';
		$html 	.= '    {';
		$html 	.= '        if(window.location.href.indexOf("viewall") === -1)';
		$html 	.= '        {';
		$html 	.= '            id = $("#defect-id").val();';
		$html   .= '			url = "'.get_site_url().'";';
		$html 	.= '            window.location.href = url+"/full-view-defect?viewall=all&current_id=" + id;';    
		$html 	.= '        }  ';      
		$html 	.= '    }';
		$html 	.= '    function removeAll()';
		$html 	.= '    {';
		$html 	.= '        $("#create_date").html("");';
		$html 	.= '        $("#user_name").html("");';
		$html 	.= '        $("#syndicate").html("");';
		$html 	.= '        $("#status").html("");';
		$html 	.= '        $("#status-hidden").val("");';
		$html 	.= '        $("textarea#description").val(""); ';       
		$html 	.= '        $("textarea#work-carried-out").val("");';
		$html 	.= '        $("#defect-id").val("");';
		$html 	.= '    }';
		$html 	.= '    function updateStatusButton()';
		$html 	.= '    {';
		$html 	.= '        $("#status_button").val(1);';
		$html 	.= '    }';
		$html 	.= '    $("#unresolved-button").click(function() {';
		$html 	.= '        updateStatusButton();';
		$html 	.= '        var currentStatus = $("#status").html();';
		$html 	.= '        var status = $("#unresolved-button").val();';
		$html 	.= '        if(status != currentStatus)';
		$html 	.= '        {';
		$html 	.= '            $("#status").html(status);';
		$html 	.= '            $("#status-hidden").val(status);';
		//            if(updateStatusConfirmation())
		//                updateStatus($("#unresolved-button").val());    
		$html 	.= '        } ';       
		$html 	.= '    });';
		$html 	.= '    $("#resolved-button").click(function() {';
		$html 	.= '        updateStatusButton();';
		$html 	.= '        var currentStatus = $("#status").html();';
		$html 	.= '        var status = $("#resolved-button").val();';
		$html 	.= '        if(status != currentStatus)';
		$html 	.= '        {';
		$html 	.= '            $("#status").html(status);';
		$html 	.= '            $("#status-hidden").val(status);';
		            //if(updateStatusConfirmation())
		                //updateStatus($("#resolved-button").val());                
		$html 	.= '        }';
		$html 	.= '    });';
		    
		$html 	.= '    $("#delete").click(function() {';
		$html 	.= '        var conf = confirm("Would you like to delete the defect?");';
		$html 	.= '        if(conf == true)';
		$html 	.= '        {';
		$html 	.= '            var id = $("#id").val();';
		$html 	.= '            var deleteData = {};';
		$html 	.= '            deleteData.defectId = id;';
		$html 	.= '            deleteData.type = "defect_delete";';
		$html 	.= '            $.ajax({';
		$html 	.= '                 type:"POST",';
		$html 	.= '                 url : "?page_id='.$attr["function_page_no"].'",';
		$html 	.= '                 dataType : "text",';
		$html 	.= '                 data : deleteData,';
		$html 	.= '                 success : function(result){';
		$html 	.= '                     if(result)';
		$html 	.= '                     {';
		$html 	.= '                        location.reload();';
		$html 	.= '                     }else{';
		$html 	.= '                        alert("Unable to delete");';
		$html 	.= '                     }';                                  
		$html 	.= '                 },';
		$html 	.= '             });';
		$html 	.= '        }else{';
		$html 	.= '            return false;';
		$html 	.= '        }';
		$html 	.= '    });';
		    
		$html 	.= '    function updateStatus(currentData)';
		$html 	.= '    {';
		$html 	.= '        updateData={};';
		$html 	.= '        updateData.status = currentData;';
		$html 	.= '        updateData.id = $("#id").val();';
		$html 	.= '        updateData.type = "updateDefectAction";';
		$html 	.= '        $.ajax({';
		$html 	.= '             type:"POST",';
		$html 	.= '             url : "?page_id='.$attr["function_page_no"].'",';
		$html 	.= '             dataType : "text",';
		$html 	.= '             data : updateData,';
		$html 	.= '             success : function(result){';
		$html 	.= '                 if(result)';
		$html 	.= '                 {';
		$html 	.= '                    $("#status").html(currentData);';
		$html 	.= '                    alert("Status is Updated Sucessfully.");   ';                 
		$html 	.= '                 }else{';
		$html 	.= '                    alert("Status is cannot be updated.");';
		$html 	.= '                 }   ';                               
		$html 	.= '             },';
		$html 	.= '         });';
		$html 	.= '    }';
		$html 	.= '</script>';

		return $html;
	}

	add_shortcode( 'fullview-defect', 'fullview_defect' );

?>