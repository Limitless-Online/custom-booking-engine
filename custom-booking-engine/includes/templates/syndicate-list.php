<?php 
    function syndicate_list($attr){
        
        $attr = shortcode_atts( array(
            'function_page_no' => '509',
        ), $attr, 'syndicate-list' );

        $html   = "";

        $html  .= '<script>';
        $html  .= '    $(document).ready(function () {';
        $html  .= '    var a = {';
        $html  .= '        home: "homeSearch occupancy calendar monthPicker freetextSuggest freetextHint cardFees promotions".split(" "),';
        $html  .= '        results: "advancedSearch calendar monthPicker occupancy dateFilters freetextSuggest tooltip tooltip_analytics mediaQuery map mapResults currencyConverter fixedDateBar shortlist adslot".split(" "),';
        $html  .= '        details: "reviews calendar monthPicker dateFilters tooltip mediaQuery map mapDetails galleryNav gallery shortlist detailsText currencyConverter detailsBedding".split(" "),';
        $html  .= '        seo: "advancedSearch calendar monthPicker dateFilters tooltip mediaQuery map mapResults shortlist currencyConverter adslot dealsIframe".split(" "),';
        $html  .= '        seopoi: ["mediaQuery", "hotelsTabs", "map", "mapResults", "mobile"],';
        $html  .= '        seomap: ["mediaQuery", "hotelsTabs", "map", "mapResults", "mobile"],';
        $html  .= '        seomobile: ["mediaQuery", "hotelsTabs", "mobile"],';
        $html  .= '        hotelsenquiry: ["enquiry"],';
        $html  .= '        hotelsregion: ["mediaQuery", "mobile", "hotelsTabs", "dealsIframe", "currencyConverter"],';
        $html  .= '        hotelsdetails: "hotelsTabs calendar monthPicker dateFilters tooltip map mapDetails gallery galleryNav shortlist currencyConverter reviews detailsText".split(" ")';
        $html  .= '    };';
        $html  .= '    "undefined" !== typeof wotifConfig && a[wotifConfig.page] && wotif.utils.initModules(wotif, a[wotifConfig.page]);';
        $html  .= '    $("#UploadForm").on("submit", function(e) {';
        $html  .= '        e.preventDefault();';
        $html  .= '        $("#SubmitButton").attr("disabled", ""); ';
                //show uploading message
        $html  .= '        $("#output").html("<div style=\"padding:10px\"><img src=\"images/ajax-loader.gif\" alt=\"Please Wait\"/> <span>Uploading...</span></div>");';
        $html  .= '        $("#UploadForm").ajaxSubmit({';
        $html  .= '            target: "#output",';
        $html  .= '            success:  afterSuccess';
        $html  .= '        });';
        $html  .= '    });';
        $html  .= '});';

        $html  .= 'function afterSuccess()  {';
        $html  .= '    $("#UploadForm").resetForm();';
        $html  .= '    $("#SubmitButton").removeAttr("disabled"); ';
        $html  .= '}';
        $html  .= '$(function(a) {';
        $html  .= '    minimumDate = new Date();';
        $html  .= '$( "#cmm_date" ).datepicker({';

                //dayNamesMin: f.date.daysOfWeek.shortDays,
        $html  .= '        buttonText: "",';
        $html  .= '        duration: 0,';
        $html  .= '        firstDay: 1,';
        $html  .= '        dateFormat: "D dd M yy",';
        $html  .= '        altFormat: "D dd M yy",';
        $html  .= '        altField: "input[name=\'cmm_date\']",';
                // minDate: minimumDate,
               //buttonImage: "<?php echo get_template_directory_uri(); /images/date-clndr-icon.jpg",
        $html  .= '        buttonImageOnly: !0,';
        $html  .= '        showOn: "both",';
        $html  .= '        showOtherMonths: !0,';
        $html  .= '        showMonthPicker: !0,';
        $html  .= '        changeMonth: true,';
        $html  .= '        changeYear: true,';
        $html  .= '        constrainInput: !1';
        $html  .= '});';
        $html  .= '});';
        $html  .= '</script>';

        $html .= '<div id="primary" class="list-of-syndicates">';
        $html .= '    <div id="content" role="main">';
        $html .= '        <div class="wrap create-edit-syndicate bcknd">';
                    
        $html .= '            <h1>CREATE/ EDIT A SYNDICATE</h1>';
        $html .= '            <h2>EXISTING SYNDICATE DETAILS</h2>';
                        
        $html .= '               <div class="syndicate-boat-name"><label>Syndicate : </label> <select name="syndicate" id="syndicate" onchange="getSyndicateDetails(this)">';
                                    global $wpdb;
                                    $sql1 = "SELECT id, syndicate FROM wp_syndicate_details where status = 1";
                                    $results_total = $wpdb->get_results($sql1);
        
                                    foreach ($results_total as $key) { 
                                        $html .= '<option value="'.$key->id.'" >'.$key->syndicate.'</option>';
                                    } 
                                     
        $html .= '                  </select>';
        $html .= '                </div>';
                        if(isset($_SESSION['syndicate_form_message'])) { 
                            $html .= '<div class="syndicate_status">'.$_SESSION['syndicate_form_message'].'</div>';
                            unset($_SESSION['syndicate_form_message']); 
                        }
                   
        $html .= '                <form method="post" id="syndicate_form" name="syndicate_form" action="'.home_url().'/?page_id='.$attr["function_page_no"].'" enctype="multipart/form-data">';  
        $html .= '                    <div>';
        $html .= '                <div>';
        $html .= '                    <a href="javascript:void(0)" onclick="removeAll()" id="add">add</a>';
        $html .= '                    <a href="javascript:void(0)" onclick="removeReadOnly()">edit</a>';
        $html .= '                    <a href="javascript:void(0)" onclick="deleteRow()">delete</a>';
        $html .= '                    <a style="margin-right:0;" href="javascript:void(0)" id="save">save</a>';
        $html .= '                    <input type="hidden" name="syndicate_form" value="syndicate_form-submit" />';                    
        $html .= '                </div>';
        $html .= '                <div><h2>SYNDICATE INFORMATION</h2></div>';
        $html .= '                <div><label>Name : </label><input type="text" id="syndicate_name" class="validate[required] text-input" name="syndicate_name" value=""   /></div>';
        $html .= '                <div><label>Year Purchased :</label><input type="text" id="syndicate_year" class="validate[required] text-input" name="syndicate_year" value=""  /></div>';
        $html .= '                <div><label>Model :</label><input type="text" name="model" id="model" class="validate[required] text-input" value=""/></div>';
        //<!--                <div><label>Syndicate Image :</label><input type="file" name="syndicate_image" id="syndicate_image" class="text-input" value=""/></div>-->
        $html .= '                <div class="cmm_clnd_icon"><label>Commencement Date :</label><input type="text" name="cmm_date" id="cmm_date"></div>';
        $html .= '                <div><label>Number of Owners :</label>';
        $html .= '                        <select name="owners" id="owners" class="validate[required] selectBox">';
                                //<!-- <option value="1">1</option> -->
        $html .= '                        <option value="2">2</option>';
                                //<!-- <option value="3">3</option>   -->
        $html .= '                        <option value="4">4</option>';
                                //<!-- <option value="5">5</option>  -->
        $html .= '                        <option value="6">6</option>';
                                //<!-- <option value="7">7</option>  -->
        $html .= '                        <option value="8">8</option>'; 
        $html .= '                        </select>';
        $html .= '                </div>';
        $html .= '                <div><label style="width:100%; margin-bottom:10px;">Notes :</label><textarea name="notes" id="notes"> </textarea></div>';
        $html .= '                <input type="hidden" name="status" id="status" value="1" />';
        $html .= '                <input type="hidden" name="syndicate_id" id="syndicate_id" value="" />';
        $html .= '                        <input type="hidden" name="syndicate_image" id="syndicate_image" value="" />';
        $html .= '                        <input type="hidden" name="syndicate_image_thumbnail" id="syndicate_image_thumbnail" value="" />';
        $html .= '                        <input type="hidden" name="syndicate_image_save" id="syndicate_image_save" value="0" />';
        $html .= '                        <input type="hidden" name="syndicate_image_delete" id="syndicate_image_delete" value="0" />';
        $html .= '                <input type="hidden" name="add-clicked" id="add-clicked" value="0" />';
        $html .= '                    </div>';
        $html .= '            </form>';  
        $html .= '                <form action="'.get_template_directory_uri().'/processupload.php" method="post" onsubmit="return checkFile();" enctype="multipart/form-data" id="UploadForm" style="position: absolute;right: 35px;top: 252px;width: 300px;">';
        $html .= '                    <div class="syndicate-upload-count-image">';
        $html .= '                        <div>';
        $html .= '                            <input name="ImageFile" type="file" />';
        $html .= '                            <div id="output"></div>';                         
        $html .= '                        </div>';
        $html .= '                        <div class="syndicate-upload-buttons">';
        $html .= '                            <input type="submit"  id="SubmitButton" value="Upload" style="background: url(\''.plugin_dir_url( __file__ ).'../../assets/images/bcknd-btns.png\') no-repeat scroll 0 0 transparent;color: #FFFFFF;float: left;font-size: 14px;height: 25px;line-height: 24px;margin: 10px 26px 10px 0;text-align: center;text-decoration: none;text-transform: uppercase;width: 135px;border: none;cursor: pointer;" />';                            
        $html .= '                            <a id="upload_delete" name="upload_delete" href="javascript:void(0)" onclick="uploadDelete()">Delete</a>';
        $html .= '                            <a id="upload_save" name="upload_save" href="javascript:void(0)" onclick="uploadSave()">Save</a>';
        $html .= '                        </div>';
        $html .= '                   </div>';
        $html .= '            </form>';
        $html .= '        </div>';

        $html .= '    </div><!-- #content -->';
        $html .= '</div><!-- #primary -->';
        
        $html .= '<script type="text/javascript">';
        $html .= '   $ = jQuery.noConflict();';
        $html .= '   var fieldArray = new Array();';
        $html .= '   fieldArray[0] = "syndicate_name";';
        $html .= '   fieldArray[1] = "syndicate_year";';
        $html .= '   fieldArray[2] = "model";';
        $html .= '   fieldArray[3] = "cmm_date";';
        $html .= '   fieldArray[4] = "owners";';
        $html .= '   fieldArray[5] = "notes";';
           
        $html .= '   (function($){';
        $html .= '        $("#save").click(function( event ) {';
                //jQuery("#syndicate_form").validationEngine();
        $html .= '            var add_clicked = parseInt($("#add-clicked").val());';
        $html .= '            if(add_clicked)';
        $html .= '            {';
        $html .= '                if(checkInputFields())';
        $html .= '                {';
        $html .= '                    var conf = confirm("Would you like to add this syndicate ?");';    
        $html .= '                }else{';
        $html .= '                    return false;';                    
        $html .= '                }';                    
        $html .= '             }else{';
        $html .= '                var conf = confirm("Would you like to save the changes made to syndicate ?");';
        $html .= '            }';
        $html .= '            if(conf)';
        $html .= '            {';
        $html .= '                $("#syndicate_form").submit();';
        $html .= '                  return true;';
        $html .= '            }else{';
        $html .= '                var status = parseInt($("#status").val());';
        $html .= '                if(!status)';
        $html .= '                {';
        $html .= '                    $("#status").val(1);';
        $html .= '            }';
        $html .= '            }';
                        
        $html .= '        }); ';
        $html .= '    })(jQuery);';
        $html .= '    $("input").focus(function() {';
        $html .= '        var currentValue = $("#"+$(this).attr("id")).val();';
        $html .= '        if(currentValue.indexOf("LEASE ENTER") !== -1)';
        $html .= '        {';
        $html .= '            $("#"+$(this).attr("id")).val("");';
        $html .= '        }';            
        $html .= '    });';
            
        $html .= '    function checkFile()';
        $html .= '    {';        
        $html .= '        var fileName = $("input[type=file]").val();';
        $html .= '        console.log(fileName);';
        $html .= '        if(typeof fileName == "undefined")';
        $html .= '        {';
        $html .= '            console.log("we are inside");';
        $html .= '            return false;';
        $html .= '        }else if(fileName != "")';
        $html .= '        {';
        $html .= '            return false;';
        $html .= '        }';
        $html .= '        return true;';
        $html .= '    }';
        $html .= '   function uploadSave()';
        $html .= '   {';
        $html .= '       var confirmSave = confirm("Would you like to save the changes made to image ?");';
        $html .= '       if (confirmSave == true)';
        $html .= '       {';
        $html .= '            var syndicateThumbnailImage = $("#syndicate_thumbnail").attr("alt");'; 
        $html .= '            var syndicateResizeImage = $("#syndicate_resize_image").attr("alt");';
        $html .= '            $("#syndicate_image").val(syndicateResizeImage);';
        $html .= '            $("#syndicate_image_thumbnail").val(syndicateThumbnailImage);';
        $html .= '            $("#syndicate_image_save").val(1);';
                    //$('#imageSave').html('Please save the syndicate inorder to apply the image changes.');
        $html .= '       }else{';
        $html .= '           $("#syndicate_image_save").val(0);  ';
        $html .= '       } ';
        $html .= '   }';
        $html .= '   function uploadDelete()';
        $html .= '   {';
        $html .= '       var confirmDelete = confirm("Are you sure you would to delete the image ?");';
        $html .= '       if (confirmDelete == true)';
        $html .= '       {';
        $html .= '          $("#syndicate_image_delete").val(1);'; 
        $html .= '       }else{';
        $html .= '           $("#syndicate_image_delete").val(0);';
        $html .= '            }';             
        $html .= '   }';
        $html .= '   function checkInputFields()';
        $html .= '   {';
        $html .= '        var result = true;';
        $html .= '        $("form :input").each(function(index, elm){';
        $html .= '            if(elm.type == "text")';
        $html .= '            {';
        $html .= '                if(elm.value == "")';
        $html .= '                {';
        $html .= '                    var fieldName = "please enter "+elm.name;'; 
        $html .= '                    $("#"+elm.id).val(fieldName.toUpperCase());';
        $html .= '                    result = false;';
        $html .= '                }';
        $html .= '            }';
        $html .= '        });';
        $html .= '        return result;';
        $html .= '   }';
             
        $html .= '   function getSyndicateDetails(select)';
        $html .= '   {';
        $html .= '       data1={};';
        $html .= '       data1.id = select.value;';
        $html .= '       $("#syndicate_id").val(select.value);';
        $html .= '       $("#syndicate_image").val(""); ';
        $html .= '       $("#syndicate_image_thumbnail").val("");';
        $html .= '       $("#syndicate_image_save").val(0);';
        $html .= '       $("#output").html("");';
        $html .= '       data1.type = "syndicate";';
        $html .= '       $.ajax({';
        $html .= '            type:"POST",';
        $html .= '            url : "?page_id='.$attr["function_page_no"].'",';
        $html .= '            dataType : "text",';
        $html .= '            data : data1,';
        $html .= '            success : function(result){';
       // $html .= '                console.log(result);';
        $html .= '                obj = JSON.parse(result);';
        $html .= '                $("#add-clicked").val(0);';
        $html .= '                for (x in obj)';
        $html .= '                {';
        $html .= '                    for (y in obj[x])';
        $html .= '                    {';
        $html .= '                        if(y == syndicate)';
        $html .= '                        {';
        $html .= '                            if(document.getElementById("syndicate_name"))';
        $html .= '                                document.getElementById("syndicate_name").value = obj[x][y];';
        $html .= '                        }else if(y == "owners"){';
        $html .= '                            var element = document.getElementById("owners");';
        $html .= '                            if(element)';
        $html .= '                                element.value = obj[x][y];';
                                    //document.getElementById('owners').value = obj[x][y];
        $html .= '                        }else if(y == "thumbnail_image"){';
        $html .= '                            if(typeof obj[x][y] != "undefined")';
        $html .= '                            {';
        $html .= '                                if(obj[x][y].indexOf("thumb_") != -1)';
        $html .= '                                {';
        $html .= '                                    var siteUrl = "'.site_url().'"; ';
        $html .= '                                    var imageHtml = "<img src=\'"+siteUrl+obj[x][y]+"\' />";';
        $html .= '                                    $("#output").html(imageHtml);';   
        $html .= '                                }';
        $html .= '                            } ';                          
        $html .= '                        }else if(y != "id" && y != "name"){';
        $html .= '                            if(document.getElementById(y))';
        $html .= '                                document.getElementById(y).value = obj[x][y];';
        $html .= '                        }';
                                    
        $html .= '                    }  ';                  
        $html .= '                }';
        $html .= '            },';

        $html .= '        });';
        $html .= '   }';
        $html .= '   function makeReadOnly()';
        $html .= '   {';
        $html .= '       for(i in fieldArray)';
        $html .= '       {';
        $html .= '           if(fieldArray[i] == "owners")';
        $html .= '           {';
        $html .= '               if(document.getElementById(fieldArray[i]))';
        $html .= '               {';
                            //document.getElementById(fieldArray[i]).disabled = "disabled";
        $html .= '               }';
        $html .= '           }else{';
        $html .= '               if(document.getElementById(fieldArray[i]))';
        $html .= '                   document.getElementById(fieldArray[i]).readOnly = "readonly";';
        $html .= '           }';
        $html .= '       }';
        $html .= '   }';
                   
        $html .= '   function checkDeletion()';
        $html .= '   {';
        $html .= '       var status = parseInt($("#status").val());';
              // console.log('status - '+status);
        $html .= '       if(status == 0)';
        $html .= '       {';
        $html .= '           $("#save").click();  ';
        $html .= '       }else{';
        $html .= '            return true;';
        $html .= '       }';
        $html .= '   }';
           
        $html .= '   function removeReadOnly()';
        $html .= '   {';
        $html .= '       checkDeletion();';
        $html .= '       for(i in fieldArray)';
        $html .= '       {';
        $html .= '           if(fieldArray[i] == "owners")';
        $html .= '           {';
        $html .= '               if(document.getElementById(fieldArray[i]))';
        $html .= '                   document.getElementById(fieldArray[i]).disabled = false;';
        $html .= '           }else{';
        $html .= '               if(document.getElementById(fieldArray[i]))';
        $html .= '                   document.getElementById(fieldArray[i]).readOnly = false;';
        $html .= '           }    ';       
        $html .= '       } ';
        $html .= '   } ';
           
        $html .= '   function addButtonClick()';
        $html .= '   {';
        $html .= '       var add_button_click = parseInt($("#add-clicked").val());';
              // console.log('add_button_click - '+add_button_click);
        $html .= '       if(add_button_click)';
        $html .= '       {';
        $html .= '            return true; ';
        $html .= '       }else{';
        $html .= '            return false;';
        $html .= '       }';
        $html .= '   }';
        $html .= '   function removeAll()';
        $html .= '   {';
        $html .= '       $("#add-clicked").val(1);'; 
        $html .= '       checkDeletion();';        
        $html .= '       inputs = document.forms["syndicate_form"].getElementsByTagName("input");';
        $html .= '       textareas = document.forms["syndicate_form"].getElementsByTagName("textarea");';
             
        $html .= '       for (index = 0; index < inputs.length; ++index) {';
        $html .= '           if(inputs[index].id != "status")';
        $html .= '           {';
        $html .= '               if(inputs[index].id != "add-clicked")';
        $html .= '               { ';
                          // console.log(inputs[index].id); 
        $html .= '           inputs[index].value = "";';
        $html .= '               }';
        $html .= '           }';
        $html .= '       }';
        $html .= '       $("#owners").attr("disabled", false);';
               
        $html .= '       for (textindex = 0; textindex < textareas.length; ++textindex) {';
        $html .= '           textareas[textindex].value = "";';
        $html .= '       }';
              
        $html .= '       removeReadOnly();';
        $html .= '   }';
        $html .= '   function deleteRow()';
        $html .= '   {';
        $html .= '       if(!addButtonClick())';
        $html .= '       {';
        $html .= '       var confirmData = confirm("Are you sure you would to delete?");';
        $html .= '       if (confirmData == true)';
        $html .= '       {';
        $html .= '               $("#status").val(0);';
        $html .= '           }else{';
        $html .= '               $("#status").val(1);';
        $html .= '                    }';              
            
        $html .= '        }';
        $html .= '   }';
        $html .= '</script>';
        
        $html .= '<script>';
        $html .= '    jQuery(document).ready( function() {';
        $html .= '     getSyndicateDetails(document.getElementById("syndicate"));';
        $html .= '    makeReadOnly();';
        $html .= '    });';
        $html .= '</script>';

        $html .= '<script src="'.plugin_dir_url(__FILE__).'../../assets/addons/js/jquery.min.js" type="text/javascript"></script> ';
    
        $html .= '<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'../../assets/addons/css/validationEngine.jquery.css" type="text/css"/>';
        $html .= '<script src="'.plugin_dir_url(__FILE__).'../../assets/addons/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>';
        $html .= '<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />';
        $html .= '<script src="http://code.jquery.com/jquery-1.9.1.js"></script>';
        $html .= '<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>';
        $html .= '<script type="text/javascript" src="'.plugin_dir_url(__FILE__).'../../assets/addons/js/jquery.form.js"></script>';

        return $html;
    }

    add_shortcode( 'syndicate-list', 'syndicate_list' );
?>