<?php

/**
 * Template Name: Report Defect
 */
if($user_ID!=0)
 {
session_start();
 get_header('login');
 unset($_SESSION['error']);
 
 if(isset($_SESSION['images']))
 {
    $images = $_SESSION['images'];
 }else{
    $images = [];
 }
 
  ?>

  <!-- SlidesJS Required: Link to jQuery -->
  <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
  <!-- End SlidesJS Required -->

  <!-- SlidesJS Required: Link to jquery.slides.js -->
  <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.slides.min.js"></script>
  <!-- End SlidesJS Required -->

  <!-- SlidesJS Required: Initialize SlidesJS with a jQuery doc ready -->
  <script>
    $(function() {
      $('#slides').slidesjs({
        width: 480,
        height: 255
      });
     if($('.slidesjs-slide').length==1){
		    $('.slidesjs-navigation').hide();
		  } 
    });
	
	
  </script>
  <!-- End SlidesJS Required -->
  

<div id="primary">
    <div id="content" role="main">

<div class="report-issue bcknd">
<h1>REPORT A DEFECT/ ISSUE</h1>
<div class="left">
<p>&nbsp;</p>
<div>
  
      <?php if(isset($images))
      {
      ?><div style="position:relative;" id="slides">
      <?php 
          foreach($images as $image){ ?>                
              <img src="<?php echo $image; ?>" name="images[]" />  
      <?php } ?>
      </div> 
      <?php } ?>
   
 </div>
<a style="float:left; margin:23px 0 0 20px;" href="<?php echo get_page_link(523); ?>">upload photos</a>
</div>
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="rgt">
                    <p>PLEASE DESCRIBE THE PROBLEM IN DETAIL</p>
                    <div><textarea name="description" id="description"></textarea></div>
                    <input type="hidden" name="image" value="<?php echo implode(',', $images); ?>" />
                    <a style="float:right;" href="#"> <input type="submit" value="SEND TO MANAGEMENT" /></a>
                  </div>
              </form>


<?php  wp_get_current_user();

$user_id = $current_user->ID;
//echo "<pre>";print_R($_POST);
if($_POST[description]!=""){
$wpdb->insert('wp_report_defect', array('id' => '', 'user_id' => $user_id, 'syndicate_name' => $syndicate, 'image' => $_POST['image'], 'description' => $_POST['description']) ); ?>
<div class="message">Thank you, your issue has been submitted to<br />
      Luxury Boat Syndicates Management Team.  </div> 
<?php
	
	$current_user = wp_get_current_user();
	
	
	$sql1 = "SELECT * FROM wp_owner_details where email = '".$current_user->user_email."'";
	$results_total = $wpdb->get_results($sql1);
	foreach ($results_total as $key) {
		$syndicate_name= $key->syndicate;
		$first_name = $key->first_name;
		$last_name = $key->surname;
		
		 ?>

<?php } //echo $syndicate_name; ?>
<?php 
        $to = $current_user->user_email;
        $subject = "LBS Defect Details";
        $message = '<div style="width:932px; margin: auto; padding:0 25px; font-size:15px;">
                        <a rel="home" title="Luxury Boat" href="http://luxuryboatsyndicates.com.au">
                        <img src="http://luxuryboatsyndicates.com.au/wp-content/themes/LuxuryBoat/images/logo.jpg"></a>
                        <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Dear '.$first_name . " ". $last_name.',</p>
                        <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif;">Below are defect details :</p>
                        <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Syndicatename: '.$syndicate_name.'</p>
                        <p style="font-family:Arial, Helvetica, sans-serif; margin:15px 0;">Reported by: '.$first_name . " ". $last_name.'</p>
                        <p style="font-family:Arial, Helvetica, sans-serif; margin:15px 0;">Problem: '.$_POST['description'].'</p>
                        <p style="margin:0; font-family:Arial, Helvetica, sans-serif; line-height:25px;">THANK YOU FROM THE LUXURY BOAT SYNDICATES TEAM.<br />
                        IF YOU WOULD LIKE TO CONTACT US PLEASE CALL US ON<br />
                        02 8231 6538.</p>
                        </p>';
        $headers = "From: LBS <enquiry@luxuryboatsyndicates.com.au>\r\n";

        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        mail($to,$subject,$message,$headers);
        
        $adminlist = get_option('custom_admin_list');
       
        for($i=0; $i<count($adminlist); $i++){
           
           $to_mng = $adminlist[$i];
           $subject_mng = "LBS Defect Details";
           $message_mng = '<div style="width:932px; margin: auto; padding:0 25px; font-size:15px;">
                          <a rel="home" title="Luxury Boat" href="http://luxuryboatsyndicates.com.au">
                          <img src="http://luxuryboatsyndicates.com.au/wp-content/themes/LuxuryBoat/images/logo.jpg"></a>
                          <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Dear Admin,</p>
                          <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif;">Below are defect details :</p>
                          <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Syndicatename: '.$syndicate_name.'</p>
                          <p style="font-family:Arial, Helvetica, sans-serif; margin:15px 0;">Reported by: '.$first_name . " ". $last_name.'</p>
                          <p style="font-family:Arial, Helvetica, sans-serif; margin:15px 0;">Problem: '.$_POST['description'].'</p>
                          </p>';
          $headers_mng = "From: ".$first_name . " ". $last_name." <".$current_user->user_email.">\r\n";

          $headers_mng .= "MIME-Version: 1.0\r\n";
          $headers_mng .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

          mail($to_mng,$subject_mng,$message_mng,$headers_mng);
        }
        
} ?>
  
    </div>
  
			</div><!-- #content -->
		</div><!-- #primary -->
  
<?php get_footer('login'); ?>

<?php 
 }
else
	 {
		$url = '/owner-login/';
	    header("Location:$url");
	 }
?>