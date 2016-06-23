<?php 

/**
 * Template Name: Default Plain Template
 */


if($user_ID!=0){
	get_header('login'); 
	if(have_posts()):
		while(have_posts()): the_post();
       		the_content();
   		endwhile;
	endif;
	get_footer('login'); 
}
else{
		$url = '/management/';
	    header("Location:$url");
}

?>