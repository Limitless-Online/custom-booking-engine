<?php 

	function management_area(){

		$html  = "";
		
		$html .= '<div id="primary">';
		$html .= '    <div id="content" role="main">';
		    
		$html .= '<div class="bcknd management-area">';
		$html .= '<h1>MANAGEMENT AREA</h1>';
		$html .= '<a href="'.get_page_link(505).'">BOOKINGS</a>';
		$html .= '<a href="'.get_page_link(511).'">CREATE / EDIT CUSTOMER PROFILE</a>';
		$html .= '<a href="'.get_page_link(507).'">DEFECT REPORTS</a>';
		$html .= '<a href="'.get_page_link(513).'">CREATE / EDIT A SYNDICATE</a>';
		$html .= '</div>';

		$html .= '     </div>';
		$html .= '</div>';

		return $html;
	}

	add_shortcode( 'management-area', 'management_area' );
?>