<?php

/**
 * Template Name: Boat Detail PDF
 */

//require_once('pdf/html2fpdf.php');

require_once("dompdf/dompdf_config.inc.php"); //include dompdf config
ob_start();
?>

<?php 
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'downloadaspdf')
{
    $defectId = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
    if($defectId)
    {
        $defectQuery = "SELECT * FROM wp_report_defect WHERE id = ".$defectId;
        $defectData = $wpdb->get_results($defectQuery);
        $defectDetails = '';
        foreach($defectData as $defectPostedData)
        {
            $defectDetails = $defectPostedData;   
        }
        //echo "<pre>";print_r($defectPostedData);die;
        if($defectDetails != '')
        {    
            $image = '';
            $dateLoged = date("d/m/y", strtotime($defectPostedData->create_date));
            $reportedBy = '';
            $syndicate = '';
            $status = $defectPostedData->status;
            $problem = $defectPostedData->description;
            $workCarriedOut = $defectPostedData->work_carried_out;
            
            $imagePath = $defectPostedData->image;
            if($imagePath)
            {
                $imagesArray = explode(',', $imagePath);
                $files_to_zip = array();
               	foreach($imagesArray as $filename)
                {
                    $image = $filename;
                    break; // for first file name, the client has changed the requirement on 24-May-2013
                }    
            }
            
            $userID = $defectPostedData->user_id;
            $userQuery = "select syndicate.syndicate, ownerd.first_name, ownerd.surname 
                            from wp_owner_details as ownerd
                            join wp_syndicate_details as syndicate on ownerd.syndicate_id = syndicate.id
                            where ownerd.user_id = ".$userID;
            $userData = $wpdb->get_results($userQuery);
            foreach($userData as $users)
            {
                $reportedBy = $users->first_name.' '.$users->surname;
                $syndicate = $users->syndicate;
            }
            $pdfFileName = (str_replace(' ', '_', $syndicate)).'_'.(str_replace(' ', '_', $reportedBy)).'_'.$defectId.'.pdf';
            //echo $image;
            //die;
?>

<div id="primary">
	<div id="content" role="main">
		<table width="985" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:open_sanssemibold, Arial, Helvetica, sans-serif;">
          <tr>
            <td>
            <table width="985" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="padding:20px 0 30px 0;"><img src="<?php echo  get_template_directory_uri(); ?>/images/logo.jpg" width="365" height="74" alt="Luxury Boats" /></td>
                      </tr>
                       <tr><td>&nbsp;&nbsp; <br /></td></tr>
					  <tr>
                        <td style=" color:#40b5e5; font-size:14px;font-weight:bold; ">DEFECTS / ISSUES <span style="padding-left: 15em;">REPORT IN DETAIL</span></td>
                      </tr>
					  <tr><td>&nbsp;&nbsp;</td></tr><tr><td>&nbsp;&nbsp;</td></tr>	
                      <tr>
                      <td valign="top" style="padding:30px 0 0 0;"><table width="985" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="342"><img src="<?php echo $image ?>" width="312" height="201" alt="Luxury Boats" style="border:1px solid #000000;"/></td>
                      	<td valign="top"><table width="643" border="0" cellspacing="0" cellpadding="0" style="color:#000000; font-size:14px; text-transform:uppercase; text-align:left;">
                      <tr>
                        <td width="150" style="padding:0 0 20px 0;">DATE LODGED: </td>
                        <td style="padding:0 0 20px 0;"><?php echo $dateLoged ?></td>
                      </tr>
                      <tr>
                        <td width="150" style="padding:0 0 20px 0;">REPORTED BY: </td>
                        <td style="padding:0 0 20px 0;"><?php echo $reportedBy ?></td>
                      </tr>
                      <tr>
                        <td width="150" style="padding:0 0 20px 0;">SYNDICATE: </td>
                        <td style="padding:0 0 20px 0;"><?php echo $syndicate ?></td>
                      </tr>
                      <tr>
                        <td width="150" style="padding:0 0 20px 0;">STATUS: </td>
                        <td style="padding:0 0 20px 0;"><?php echo $status ?></td>
                      </tr>
                    </table>
                    </td>
                      </tr>
                    </table>
                    </td>
                    </tr>
                     <tr><td>&nbsp;&nbsp;</td></tr><tr><td>&nbsp;&nbsp;</td></tr><tr><td>&nbsp;&nbsp;</td></tr><tr><td>&nbsp;&nbsp;</td></tr>
					 <tr>
                     <td style="color:#40b5e5; font-size:14px; font-weight:bold; padding:30px 0 20px 0;">PROBLEM</td>
                     </tr>
                     <tr><td>&nbsp;&nbsp;</td></tr><tr><td>&nbsp;&nbsp;</td></tr>
					 <tr>
                     <td width="200px">
                        <div id="textarea" style="width:547px; height:118px; color:#000; border:1px solid #000; font-family:open_sanssemibold, Arial, Helvetica, sans-serif; padding:10px 20px 10px 26px; resize:none;"><?php echo $problem ?></div></td>
                     </tr>
                     <tr>
                     <td style="color:#40b5e5; font-size:14px; font-weight:bold; padding:24px 0 20px 0;">WORK CARRIED OUT</td>
                     </tr>
                     <tr><td>&nbsp;&nbsp;</td></tr><tr><td>&nbsp;&nbsp;</td></tr>
					 <tr>
                     <td><div style="width:547px; height:118px; color:#000; border:1px solid #000; font-family:open_sanssemibold, Arial, Helvetica, sans-serif; padding:10px 20px 10px 26px; resize:none;"><?php echo $workCarriedOut ?></div></td>
                     </tr>
                     <tr><td>&nbsp;&nbsp;</td></tr><tr><td>&nbsp;&nbsp;</td></tr><tr><td>&nbsp;&nbsp;</td></tr>
					 <tr><td align="right" style="font-family:open_sanssemibold, Arial, Helvetica, sans-serif; font-size:12px; color:#686868; padding:0 30px 15px 0;">Copyright © 2013 Luxury Boat Syndicates</td></tr>
                     <tr><td style=" border-bottom: 1px solid #666;"></td></tr>
                      <tr><td align="right" style="font-family:open_sanssemibold, Arial, Helvetica, sans-serif; font-size:12px; color:#686868; padding:15px 30px 50px 0;">Site by Platform Digital</td></tr>
                  
                </table>
                
                </td>
              </tr>
        </table>
			</div><!-- #content -->
		</div><!-- #primary -->
<?php
try{
      $html = '';
      $html = ob_get_contents();
      ob_end_clean();
      $old_limit = ini_set("memory_limit", "64M");
      $dompdf = new DOMPDF();
	  $dompdf->load_html($html);
      $dompdf->set_paper("-p", "landscape");
      $dompdf->render();        
      $dompdf->stream($pdfFileName);
}catch(Exception $error)
{
    echo $error;
    die();
    //wp_redirect(home_url('/full-view-defect'));
}
          
        }
    }
}
wp_redirect(home_url('/full-view-defect'));
?>