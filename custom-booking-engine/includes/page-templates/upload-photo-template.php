<?php
/**
 * Template Name: Upload Photos
 */
if($user_ID!=0)
 {
session_start();
get_header('login');
unset($_SESSION['images']); 
//unset($_SESSION['error']);
?>
<div id="primary">
    <div id="content" role="main">
        <div class="report-issue bcknd">
            <h1>UPLOAD PHOTOS</h1>
            <?php if(isset($_SESSION['error'])){ ?><div class="error"><?php echo $_SESSION['error']; ?></div><?php } ?>
            <div class="left">
                <p>&nbsp;</p>
                <div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <p><span class="upload-img">Image1 :</span><input name="image[]" type="file" /></p>
                        <p><span class="upload-img">Image2 :</span><input name="image[]" type="file" /></p>
                        <p><span class="upload-img">Image3 :</span><input name="image[]" type="file" /></p>

                        <input class="upload-submit-new" type="submit" name="submit" value="finish uploading" />
                    </form>
                </div><!-- #content -->
            </div><!-- #primary -->
<?php
try {
    if(isset($_POST['submit']))
    {
        $image = array();
        foreach($_FILES['image']['name'] as $index => $name)
        {
            if($_FILES['image']['error'][$index] == 4)
            {
                continue;
            }
            if($_FILES['image']['error'][$index] == 0)
            {
                $fileName = $_FILES['image']['name'][$index];
                $tmpName = $_FILES['image']['tmp_name'][$index];
                $fileSize = $_FILES['image']['size'][$index];
                $fileType = $_FILES['image']['type'][$index];
                
                $uploadDir = wp_upload_dir();
                $fieldPath = $uploadDir['path'];
                $fieldUrl = $uploadDir['url'];

                if(($fileType == "image/gif" ||
                        $fileType == "image/jpeg" ||
                        $fileType == "image/pjpeg" ||
                        $fileType == "image/png" ||
                        $fileType == "image/x-png"))
                {
                    /*if($fileSize > 500000)
                    {
                        $_SESSION['error'] = 'Not allowed images size';
                        exit;
                    }*/
                    $date = date('YmdHis');
                    $fileName = $date.'_'.$fileName;
                    $imagePath = $fieldPath . '/' . $fileName;
                    $imageUrl = $fieldUrl . '/' . $fileName;

                    $result = @move_uploaded_file($tmpName, $imagePath);
                    if(!$result)
                    {
                       // echo "Error uploading";
                        $_SESSION['error'] = "Error uploading";
                        exit;
                    }
                     list($width, $height) = getimagesize($imageUrl);
                     //echo $width;
                    if($width <= '480' || $height <= '250'){
                    $_SESSION['error'] = "Error uploading image width less than 480 pixel or height less than 250 pixel";
                        exit;
                    }else{
                        $resizedImage = image_resize( $imagePath, '480', '250' );
                    }
                    $resizeImageUrl = str_replace($fieldPath, $fieldUrl, $resizedImage);                    
                    $image[] = $resizeImageUrl;                    
                }else{
                    $_SESSION['error'] = 'Not allowed images type';
                    exit;
                }
            }
        }
    }

    if(!empty($image))
    {
        $_SESSION['images'] = $image;
        
        $image = array();
        
        //$location = home_url( '/report-defect' );
         //echo "<meta http-equiv='refresh' content='0;url=$location' />";
        //wp_redirect(home_url('/report-defect'));
        //header('Location: '.esc_url( home_url( '/report-defect' ) ));
		?>
		<script type=text/javascript>
			$(document).ready(function(){
			  window.location.href = "<?php echo  home_url( '/report-defect' ); ?>" ;
			});
		</script>
		<?php
    }
        
    //echo "<pre>";print_r($image);die;
    unset($_POST['submit']);
}catch(Exception $error){
    //echo $error->getMessage();
    $_SESSION['error'] = $error->getMessage();
}

?>
<?php get_footer('login'); ?>

<?php 
 }
else
	 {
		$url = '/owner-login/';
	    header("Location:$url");
	 }
?>