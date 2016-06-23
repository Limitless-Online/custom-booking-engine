<?php
/**
 * Template Name: Functions
 */
//include_once('Zipfile.php');
/* Owner queries    */
global $wpdb;
//echo "<pre>";print_r($_REQUEST);die;

$type = isset($_POST["type"]) ? $_POST["type"] : '';  
$email = isset($_POST['email']) ? $_POST['email'] : '';
$id = isset($_POST['user']) ? $_POST['user'] : '';
if($type == "add" && $email!=""){
	$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
	
	$sql12 = "SELECT count(*) as count FROM  wp_users where ID = '".$id."'";
	$count1 = $wpdb->get_results($sql12);

        foreach($count1 as $num1){
                $values1 = $num1->count;
                }
        if($values1 >0 ){
               $sqlUserEmail = "SELECT count(*) as count FROM  wp_users where email = '".$email."'";
            $countUserEmail = $wpdb->get_results($sqlUserEmail);

            foreach($countUserEmail as $numUserEmail){
                $valuesUserEmail = $numUserEmail->count;
                }
            if($valuesUserEmail >0){
                $wpdb->update('wp_users',array('user_pass' => md5($_POST['password']), 'user_nicename' => $_POST['first_name'], 'user_registered' => '', 'user_status' => '0', 'display_name' => $_POST['first_name']), 
               array( 'ID' => $id ));
                $UserEmail_res = "This email is already registered with Luxury Boat Syndicates, please enter another email address for this profile and the other fields are updated successfully.";
                echo $UserEmail_res ;
            }else{
               $wpdb->update('wp_users',array('user_login' => $_POST['email'], 'user_pass' => md5($_POST['password']), 'user_nicename' => $_POST['first_name'], 'user_email' => $_POST['email'], 'user_registered' => '', 'user_status' => '0', 'display_name' => $_POST['first_name']), 
               array( 'ID' => $id ));
            }
	}else{
            
            $sqlUserEmail = "SELECT count(*) as count FROM  wp_users where email = '".$email."'";
            $countUserEmail = $wpdb->get_results($sqlUserEmail);

            foreach($countUserEmail as $numUserEmail){
                $valuesUserEmail = $numUserEmail->count;
                }
            if($valuesUserEmail >0){
                $UserEmail_res = "This email is already registered with Luxury Boat Syndicates, please enter another email address for this profile.";
                echo $UserEmail_res ;
            }else{
		$wpdb->insert('wp_users',array('ID' => '', 'user_login' => $_POST['email'], 'user_pass' => md5($_POST['password']), 'user_nicename' => $_POST['first_name'], 'user_email' => $_POST['email'], 'user_registered' => '', 'user_status' => '0', 'display_name' => $_POST['first_name'] ) );
		
		$getRegisterID = $wpdb->insert_id;
                $my_user = new WP_User($getRegisterID);
                $my_user->set_role("subscriber");

                // Update User Meta
                update_user_meta($getRegisterID, 'rich_editing', 'true');
                update_user_meta($getRegisterID, 'show_admin_bar_front', 'true');
                update_user_meta($getRegisterID, 'admin_color', 'fresh');
                update_user_meta($getRegisterID, 'nickname', $_POST['first_name']);
                update_user_meta($getRegisterID, 'first_name', $_POST['first_name']);
            }
	}
	

    $syndicate = isset($_POST['syndicate']) ? $_POST['syndicate'] : '';
	
    $syndicate_name = "SELECT id, syndicate, owners, cmm_date FROM wp_syndicate_details WHERE syndicate = '" . $syndicate . "' and status = 1";
    $syndicate_result = $wpdb->get_results($syndicate_name);
    $numberOfOwners = 0;
    $commencementDate = 0;
    foreach($syndicate_result as $syn_id)
        {
	$syndicate_ID = $syn_id->id;
        $numberOfOwners = $syn_id->owners;
       $commencementDate = $syn_id->cmm_date;
	}
	$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
	$sql1 = "SELECT count(*) as count FROM wp_owner_details where id = '".$user_id."' and status = 1";
	$count = $wpdb->get_results($sql1);
         //$count = $wpdb->num_rows($results_total);
        foreach($count as $num){
	$values = $num->count;
	}
        if($values >0 ){
	if($status != '' && $status == 0)
        {
            
              // code to delete owners related to syndicate and booking dates related to owners
              $bookingDatesString = '';
              $ownerIdsString = '';
              $usersIdsString = '';
              $calculationIdsString = '';
              $syndicate_res = '';
              $deletedDates = false;
              $deletedUsers = false;
              $deletedSyndicate = false;
              $getBookingOwnerIdsQuery = "select GROUP_CONCAT(DISTINCT owner_details.id) as owner_ids, GROUP_CONCAT(DISTINCT users.id) as user_ids
                                          from wp_owner_details as owner_details
                                          join wp_users as users on users.id = owner_details.user_id
                                          where owner_details.email = '".$email."' group by owner_details.id";
              $bookingOwnerIdsDates = $wpdb->get_results($getBookingOwnerIdsQuery);
              foreach($bookingOwnerIdsDates as $ids)
              {
                  $bookingDatesString = $ids->booking_ids;
                  $ownerIdsString = $ids->owner_ids;
                  $usersIdsString = $ids->user_ids;
                  $calculationIdsString = $ids->calculation_id;
              }                                 
              
              // delete owners from owners table
              if($ownerIdsString != '')
              {
                  $deletedOwner = true;
                  $wpdb->delete('wp_owner_details', array('id' => $ownerIdsString), array('%s'));
              }
              // delete users from users table
              if($usersIdsString != '')
              {
                  $wpdb->delete('wp_owner_booking', array('user_id' => $usersIdsString), array('%s'));
                   $wpdb->delete('wp_booking_calculations', array('user_id' => $usersIdsString), array('%s'));
                  $wpdb->delete('wp_users', array('id' => $usersIdsString), array('%s'));
                  
              }
                            
              if($deletedOwner)
                  $syndicate_res = "Owner Deleted Successfully";
              if($usersIdsString)
                  $syndicate_res = $syndicate_res . " and respective booking dates are deleted";
              
              echo $syndicate_res;
              return;
           }elseif($status != '' && $status == 1){
               $sqlUserEmail = "SELECT count(*) as count FROM  wp_owner_details where email = '".$email."'";
            $countUserEmail = $wpdb->get_results($sqlUserEmail);

            foreach($countUserEmail as $numUserEmail){
                $valuesUserEmail = $numUserEmail->count;
                }
            if($valuesUserEmail >0){
                $wpdb->update('wp_owner_details',
                array('syndicate' => $_POST['syndicate'], 
                    'first_name' => $_POST['first_name'], 
                    'surname' => $_POST['surname'], 
                    'address' => $_POST['address'], 
                    'city' => $_POST['city'], 
                    'state' => $_POST['state'], 
                    'pincode' => $_POST['pincode'], 
                    'country' => $_POST['country'], 
                    'dob' => $_POST['dob'], 
                    'sex' => $_POST['sex'], 
                    'tel' => $_POST['tel'], 
                    'mobile' => $_POST['mobile'], 
                   'contact_no' => $_POST['contact_no'], 
                    'color' => $_POST['color'], 
                    'boat_no' => $_POST['boat_no'], 
                    'date_owner' => $_POST['date_owner'], 
                    'name_account' => $_POST['name_account'], 
                    'bsb' => $_POST['bsb'], 
                    'account_no' => $_POST['account_no'], 
                    'cc_card_name' => $_POST['cc_card_name'], 
                    'cc_card_no' => $_POST['cc_card_no'], 
                    'cc_exp_date' => $_POST['cc_exp_date'], 
                    'cc_cvv' => $_POST['cc_cvv'], 
                    'cc_type' => $_POST['cc_type'], 
                    'nd_first_name' => $_POST['nd_first_name'], 
                    'nd_surname' => $_POST['nd_surname'], 
                    'nd_tel' => $_POST['nd_tel'], 
                    'nd_license' => $_POST['nd_license'], 
                    'user_pass_base64' => base64_encode($_POST['password']),
                    'syndicate_id' => $syndicate_ID, 'status' => $_POST['status']), 
                array( 'id' => $user_id ));
                
                
                
                   // code to insert/update wp_booking_calculations table with the owners data
                $userDetailsQuery = "SELECT id as user_id FROM wp_users where user_email = '" . $email . "'";
                $userDetails = $wpdb->get_results($userDetailsQuery);
                $userId = isset($userDetails[0]->user_id) ? $userDetails[0]->user_id : 0;
                if($userId && $syndicate_ID) 
                {
                    //$syndicateDetailsQuery = "SELECT syndicate_id FROM wp_booking_calculations WHERE user_id = ".$userId ;
                    //$syndicateDetails = $wpdb->get_results($syndicateDetailsQuery);

                            // Query to Calculate the weekdays and weekend based upon date of joining
                            $syndicateCommDateQuery = "select cmm_date from wp_syndicate_details where id=". $syndicate_ID ;
                            $syndicateCommDate = strtotime($wpdb->get_results($syndicateCommDateQuery)[0]->cmm_date );

                             $ownerCommDateQuery = "select date_owner from wp_owner_details where syndicate_id = ". $syndicate_ID." and user_id = ".$userId;
                             $ownerCommDate = strtotime($wpdb->get_results($ownerCommDateQuery)[0]->date_owner );

                            //$currentTime = time();
                            //$timeDifference = $currentTime - $syndicateCommDate ;

                            $timeDifference = $ownerCommDate - $syndicateCommDate ;
                            $daysDifference = floor($timeDifference/3600/24);

                            $remainingDays = 365 - ($daysDifference % 365) ;
                            $reductionFactor = $remainingDays / 365 ;


                        $date = date('Y-m-d H:i:s');
                        if($numberOfOwners)
                        {
                            if($numberOfOwners == 8)
                            {
                                $allowedWeekdays = floor(30 * $reductionFactor);
                                $allowedWeekends = floor(13 * $reductionFactor);

                            }elseif($numberOfOwners == 6)
                            {
                                $allowedWeekdays = floor(38 * $reductionFactor);
                                $allowedWeekends = floor(19 * $reductionFactor);    
                            }elseif($numberOfOwners == 4)
                            {
                                $allowedWeekdays = floor(60 * $reductionFactor);
                                $allowedWeekends = floor(26 * $reductionFactor);    
                            }elseif($numberOfOwners == 2)
                            {
                                $allowedWeekdays = floor(120 * $reductionFactor);
                                $allowedWeekends = floor(52 * $reductionFactor);    
                            }
                            $totalNumberOfDays = ($allowedWeekdays + $allowedWeekends);     
                        }else{
                            $allowedWeekdays = 0;
                            $allowedWeekends = 0;
                        }
                        $tempDate = $commencementDate;
                        $tempDateArray = explode(' ', $tempDate);
                        //echo "<pre>";print_r($tempDateArray);
                        $year = isset($tempDateArray[3]) ? $tempDateArray[3] : '';
                        $month = isset($tempDateArray[2]) ? $tempDateArray[2] : '';
                        $day = isset($tempDateArray[1]) ? $tempDateArray[1] : '';
                        if($month != '')
                        {
                            $month = date('m', strtotime($month));    
                        }
                        if($month != '' && $day != '' && $year != '')
                        {
                            $commencementDate = $year.'-'.$month.'-'.$day;
                        }
                        //echo 'commencementDate - '.$commencementDate."<br>";
                        $date = new DateTime("+12 months $commencementDate");
                        $newCommencementDate = $date->format('Y-m-d') . "\n";
                        //echo 'newCommencementDate - '.$newCommencementDate."<br>";die;
                        $date = date('Y-m-d');
                         
                        $wpdb->update('wp_booking_calculations', 
                            array('total_no_of_days' => $totalNumberOfDays, 
                                'allowed_weekdays' => $allowedWeekdays, 
                                'allowed_weekends' => $allowedWeekends,
                                'start_date' => $commencementDate,
                                'end_date' => $newCommencementDate),
                                array('user_id' => $userId ));
                    }            
    
                $UserEmail_res = "This email is already registered with Luxury Boat Syndicates, please enter another email address for this profile and the other fields are updated successfully.";
                echo $UserEmail_res ; 
            }else{
                $wpdb->update('wp_owner_details',
                array('syndicate' => $_POST['syndicate'], 
                    'first_name' => $_POST['first_name'], 
                    'surname' => $_POST['surname'], 
                    'address' => $_POST['address'], 
                    'city' => $_POST['city'], 
                    'state' => $_POST['state'], 
                    'pincode' => $_POST['pincode'], 
                    'country' => $_POST['country'], 
                    'dob' => $_POST['dob'], 
                    'sex' => $_POST['sex'], 
                    'tel' => $_POST['tel'], 
                    'mobile' => $_POST['mobile'], 
                    'email' => $_POST['email'], 
                    'contact_no' => $_POST['contact_no'], 
                    'color' => $_POST['color'], 
                    'boat_no' => $_POST['boat_no'], 
                    'date_owner' => $_POST['date_owner'], 
                    'name_account' => $_POST['name_account'], 
                    'bsb' => $_POST['bsb'], 
                    'account_no' => $_POST['account_no'], 
                    'cc_card_name' => $_POST['cc_card_name'], 
                    'cc_card_no' => $_POST['cc_card_no'], 
                    'cc_exp_date' => $_POST['cc_exp_date'], 
                    'cc_cvv' => $_POST['cc_cvv'], 
                    'cc_type' => $_POST['cc_type'], 
                    'nd_first_name' => $_POST['nd_first_name'], 
                    'nd_surname' => $_POST['nd_surname'], 
                    'nd_tel' => $_POST['nd_tel'], 
                    'nd_license' => $_POST['nd_license'], 
                    'user_pass_base64' => base64_encode($_POST['password']),
                    'syndicate_id' => $syndicate_ID, 'status' => $_POST['status']), 
                array( 'id' => $user_id ));
                
                
                   // code to insert/update wp_booking_calculations table with the owners data
                    $userDetailsQuery = "SELECT id as user_id FROM wp_users where user_email = '" . $email . "'";
                    $userDetails = $wpdb->get_results($userDetailsQuery);
                    $userId = isset($userDetails[0]->user_id) ? $userDetails[0]->user_id : 0;
                    if($userId && $syndicate_ID) 
                    {
                        $syndicateDetailsQuery = "SELECT syndicate_id FROM wp_booking_calculations WHERE user_id = ".$userId." AND syndicate_id = " . $syndicate_ID;
                        $syndicateDetails = $wpdb->get_results($syndicateDetailsQuery);

                                // Query to Calculate the weekdays and weekend based upon date of joining
                                $syndicateCommDateQuery = "select cmm_date from wp_syndicate_details where id=". $syndicate_ID ;
                                $syndicateCommDate = strtotime($wpdb->get_results($syndicateCommDateQuery)[0]->cmm_date );

                                $ownerCommDateQuery = "select date_owner from wp_owner_details where syndicate_id = ". $syndicate_ID." and user_id = ".$userId;
                                $ownerCommDate = strtotime($wpdb->get_results($ownerCommDateQuery)[0]->date_owner );

                                //$currentTime = time();
                                //$timeDifference = $currentTime - $syndicateCommDate ;

                                $timeDifference = $ownerCommDate - $syndicateCommDate ;
                                $daysDifference = floor($timeDifference/3600/24);

                                $remainingDays = 365 - ($daysDifference % 365) ;
                                $reductionFactor = $remainingDays / 365 ;

                      
                            $date = date('Y-m-d H:i:s');
                            if($numberOfOwners)
                            {
                                if($numberOfOwners == 8)
                                {
                                    $allowedWeekdays = floor(30 * $reductionFactor);
                                    $allowedWeekends = floor(13 * $reductionFactor);

                                }elseif($numberOfOwners == 6)
                                {
                                    $allowedWeekdays = floor(38 * $reductionFactor);
                                    $allowedWeekends = floor(19 * $reductionFactor);    
                                }elseif($numberOfOwners == 4)
                                {
                                    $allowedWeekdays = floor(60 * $reductionFactor);
                                    $allowedWeekends = floor(26 * $reductionFactor);    
                                }elseif($numberOfOwners == 2)
                                {
                                    $allowedWeekdays = floor(120 * $reductionFactor);
                                    $allowedWeekends = floor(52 * $reductionFactor);    
                                }
                                $totalNumberOfDays = ($allowedWeekdays + $allowedWeekends);     
                            }else{
                                $allowedWeekdays = 0;
                                $allowedWeekends = 0;
                            }
                            $tempDate = $commencementDate;
                            $tempDateArray = explode(' ', $tempDate);
                            //echo "<pre>";print_r($tempDateArray);
                            $year = isset($tempDateArray[3]) ? $tempDateArray[3] : '';
                            $month = isset($tempDateArray[2]) ? $tempDateArray[2] : '';
                            $day = isset($tempDateArray[1]) ? $tempDateArray[1] : '';
                            if($month != '')
                            {
                                $month = date('m', strtotime($month));    
                            }
                            if($month != '' && $day != '' && $year != '')
                            {
                                $commencementDate = $year.'-'.$month.'-'.$day;
                            }
                            //echo 'commencementDate - '.$commencementDate."<br>";
                            $date = new DateTime("+12 months $commencementDate");
                            $newCommencementDate = $date->format('Y-m-d') . "\n";
                            //echo 'newCommencementDate - '.$newCommencementDate."<br>";die;
                            $date = date('Y-m-d');

                            $wpdb->update('wp_booking_calculations', 
                                array('total_no_of_days' => $totalNumberOfDays, 
                                    'allowed_weekdays' => $allowedWeekdays, 
                                    'allowed_weekends' => $allowedWeekends,
                                    'start_date' => $commencementDate,
                                    'end_date' => $newCommencementDate),
                                    array('user_id' => $userId ));
                                    
                    }
                
                $res = "Owner updated successfully";
            }
           }
	}else{
            $sqlUserEmail = "SELECT count(*) as count FROM  wp_owner_details where email = '".$email."'";
            $countUserEmail = $wpdb->get_results($sqlUserEmail);

            foreach($countUserEmail as $numUserEmail){
                $valuesUserEmail = $numUserEmail->count;
                }
            if($valuesUserEmail >0){
                $UserEmail_res = "This email is already registered with Luxury Boat Syndicates, please enter another email address for this profile.";
                echo $UserEmail_res ;
            }else{
	   // check if customer is alowed to create for this syndicate based on the syndicate owners count.
       $getOwnersForSyndicateQuery = "SELECT count(*) as count FROM wp_owner_details where syndicate_id = ".$syndicate_ID." and status = 1";
	   $getOwnersForSyndicateData = $wpdb->get_results($getOwnersForSyndicateQuery);
       $existingOwnersCount = 0;
       foreach($getOwnersForSyndicateData as $ownerCount){
            $existingOwnersCount = $ownerCount->count;
       }
       if($existingOwnersCount < $numberOfOwners)
       {
            $wpdb->insert('wp_owner_details', 
                    array('id' => '', 
                        'syndicate' => $_POST['syndicate'], 
                        'first_name' => $_POST['first_name'], 
                        'surname' => $_POST['surname'], 
                        'address' => $_POST['address'], 
                        'city' => $_POST['city'], 
                        'state' => $_POST['state'], 
                        'pincode' => $_POST['pincode'], 
                        'country' => $_POST['country'], 
                        'dob' => $_POST['dob'], 
                        'sex' => $_POST['sex'], 
                        'tel' => $_POST['tel'], 
                        'mobile' => $_POST['mobile'], 
                        'email' => $_POST['email'], 
                        'contact_no' => $_POST['contact_no'], 
                        'color' => $_POST['color'], 
                        'boat_no' => $_POST['boat_no'], 
                        'date_owner' => $_POST['date_owner'], 
                        'name_account' => $_POST['name_account'], 
                        'bsb' => $_POST['bsb'], 
                        'account_no' => $_POST['account_no'], 
                        'cc_card_name' => $_POST['cc_card_name'], 
                        'cc_card_no' => $_POST['cc_card_no'], 
                        'cc_exp_date' => $_POST['cc_exp_date'], 
                        'cc_cvv' => $_POST['cc_cvv'], 
                        'cc_type' => $_POST['cc_type'], 
                        'nd_first_name' => $_POST['nd_first_name'], 
                        'nd_surname' => $_POST['nd_surname'], 
                        'nd_tel' => $_POST['nd_tel'], 
                        'nd_license' => $_POST['nd_license'], 
                        'syndicate_id' => $syndicate_ID,
                        'user_id' => $getRegisterID, 
                        'user_pass_base64' => base64_encode($_POST['password']),
                        'status' => $_POST['status']) );		 
								
                        $to = $_POST['email'];
                        $subject = "LBS Login Details";
                        $message = '<div style="width:932px; margin: auto; padding:0 25px; font-size:15px;">
                                        <a rel="home" title="Luxury Boat" href="http://luxuryboatsyndicates.com.au">
                                        <img src="http://luxuryboatsyndicates.com.au/wp-content/themes/LuxuryBoat/images/logo.jpg"></a>
                                        <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Dear '.$_POST['first_name'].',</p>
                                        <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif;">Below are details on your login:</p>
                                        <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Username: '.$_POST['email'].'</p>
                                        <p style="font-family:Arial, Helvetica, sans-serif; margin:15px 0;">Password: '.$_POST['password'].'</p>

                                        <p style="margin:0; font-family:Arial, Helvetica, sans-serif; line-height:25px;">THANK YOU FROM THE LUXURY BOAT SYNDICATES TEAM.<br />
                                        IF YOU WOULD LIKE TO CONTACT US PLEASE CALL US ON<br />
                                        02 8231 6538.</p>
                                        </p>';
                        $headers = "From: LBS <enquiry@luxuryboatsyndicates.com.au>\r\n";

                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                        mail($to,$subject,$message,$headers);

				  
		    $res = "Owner created successfully";
       }else{
            $res = "Owner can not be created as the allocated number of owners for selected syndicate are completed";
       }
            }
    }
    // code to insert/update wp_booking_calculations table with the owners data
    $userDetailsQuery = "SELECT id as user_id FROM wp_users where user_email = '" . $email . "'";
    $userDetails = $wpdb->get_results($userDetailsQuery);
    $userId = isset($userDetails[0]->user_id) ? $userDetails[0]->user_id : 0;
    if($userId && $syndicate_ID) 
    {
        $syndicateDetailsQuery = "SELECT syndicate_id FROM wp_booking_calculations WHERE user_id = ".$userId." AND syndicate_id = " . $syndicate_ID;
        $syndicateDetails = $wpdb->get_results($syndicateDetailsQuery);

		// Query to Calculate the weekdays and weekend based upon date of joining
		$syndicateCommDateQuery = "select cmm_date from wp_syndicate_details where id=". $syndicate_ID ;
		$syndicateCommDate = strtotime($wpdb->get_results($syndicateCommDateQuery)[0]->cmm_date );
		
		$ownerCommDateQuery = "select date_owner from wp_owner_details where syndicate_id = ". $syndicate_ID." and user_id = ".$userId;
		$ownerCommDate = strtotime($wpdb->get_results($ownerCommDateQuery)[0]->date_owner );
		
		//$currentTime = time();
		//$timeDifference = $currentTime - $syndicateCommDate ;

		$timeDifference = $ownerCommDate - $syndicateCommDate ;
		$daysDifference = floor($timeDifference/3600/24);

		$remainingDays = 365 - ($daysDifference % 365) ;
		$reductionFactor = $remainingDays / 365 ;
		
        if(!$syndicateDetails)
        {
            $date = date('Y-m-d H:i:s');
            if($numberOfOwners)
            {
                if($numberOfOwners == 8)
                {
                    $allowedWeekdays = floor(30 * $reductionFactor);
                    $allowedWeekends = floor(13 * $reductionFactor);
					 
                }elseif($numberOfOwners == 6)
                {
                    $allowedWeekdays = floor(38 * $reductionFactor);
                    $allowedWeekends = floor(19 * $reductionFactor);    
                }elseif($numberOfOwners == 4)
                {
                    $allowedWeekdays = floor(60 * $reductionFactor);
                    $allowedWeekends = floor(26 * $reductionFactor);    
                }elseif($numberOfOwners == 2)
                {
                    $allowedWeekdays = floor(120 * $reductionFactor);
                    $allowedWeekends = floor(52 * $reductionFactor);    
                }
                $totalNumberOfDays = ($allowedWeekdays + $allowedWeekends);     
            }else{
                $allowedWeekdays = 0;
                $allowedWeekends = 0;
            }
            $tempDate = $commencementDate;
            $tempDateArray = explode(' ', $tempDate);
            //echo "<pre>";print_r($tempDateArray);
            $year = isset($tempDateArray[3]) ? $tempDateArray[3] : '';
            $month = isset($tempDateArray[2]) ? $tempDateArray[2] : '';
            $day = isset($tempDateArray[1]) ? $tempDateArray[1] : '';
            if($month != '')
            {
                $month = date('m', strtotime($month));    
            }
            if($month != '' && $day != '' && $year != '')
            {
                $commencementDate = $year.'-'.$month.'-'.$day;
            }
            //echo 'commencementDate - '.$commencementDate."<br>";
            $date = new DateTime("+12 months $commencementDate");
            $newCommencementDate = $date->format('Y-m-d') . "\n";
            //echo 'newCommencementDate - '.$newCommencementDate."<br>";die;
            $date = date('Y-m-d');
                        
            $wpdb->insert('wp_booking_calculations', 
                array('ID' => '', 
                    'date_created' => $date, 
                    'date_updated' => $date, 
                    'total_no_of_days' => $totalNumberOfDays, 
                    'allowed_weekdays' => $allowedWeekdays, 
                    'allowed_weekends' => $allowedWeekends, 
                    'booked_weekdays' => 0, 
                    'booked_weekends' => 0,
                    'weekday_count' => 0, 
                    'weekend_booked' => 0,
                    'last_weekend_booked' => '',
                    'syndicate_id' => $syndicate_ID, 
                    'user_id' => $userId,
                    'start_date' => $commencementDate,
                    'end_date' => $newCommencementDate
                    ));
        }            
    }   
echo $res;

 }

/* Syndicate queries    */
$name = isset($_POST['syndicate_name']) ? $_POST['syndicate_name'] : '';
$model = isset($_POST['model']) ? $_POST['model'] : '';
//$syndicate = isset($_POST['syndicate']) ? $_POST['syndicate'] : '';

if(isset($_REQUEST['syndicate_form']))
{	
    $syndicateId = isset($_REQUEST['syndicate_id']) ? $_REQUEST['syndicate_id'] : 0;
    $syndicateStatus = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
    $syndicatePostValue = isset($_REQUEST['cmm_date']) ? $_REQUEST['cmm_date'] : '';
    
    if(isset($_SESSION['syndicate_form_message']))
    {
        unset($_SESSION['syndicate_form_message']);
    }
  	//$sql1 = "SELECT count(*) as count FROM wp_syndicate_details where syndicate_name = '".trim($name)."' AND model = '".trim($model)."'";
  	//$count = $wpdb->get_results($sql1);
    $getSyndicateCountQuery = "SELECT count(*) as count FROM wp_syndicate_details where id = ".$syndicateId;
  	$count = $wpdb->get_results($getSyndicateCountQuery);
      //$count = $wpdb->num_rows($results_total);
    foreach($count as $num){
    	$values = $num->count;
    	}
    if($values >0 ){
               
         $getOwnersForSyndicate = "SELECT count(*) as count FROM wp_owner_details where syndicate_id = ".$syndicateId."";
         $getOwnersForSyndicate = $wpdb->get_results($getOwnersForSyndicate);
         $existingOwners = 0;
         $numberOfOwners = 0;
         foreach($getOwnersForSyndicate as $ownerCount)
         {
             $existingOwners = $ownerCount->count;
         }
         $numberOfOwners = $_POST['owners'];
             //echo $existingOwners.' > '.$numberOfOwners;die;
             if($existingOwners > $numberOfOwners){
               $syndicate_res = "Syndicate can't be updated as the syndicate has already more number of owners than selected value.";  
             }else{ 
              if($syndicateStatus != '' && $syndicateStatus == 0)
              {
                  // code to delete owners related to syndicate and booking dates related to owners
                  $bookingDatesString = '';
                  $ownerIdsString = '';
                  $usersIdsString = '';
                  $calculationIdsString = '';
                  $deletedDates = false;
                  $deletedUsers = false;
                  $deletedSyndicate = false;
                  $getBookingOwnerIdsQuery = "SELECT syndicate.id, GROUP_CONCAT( DISTINCT owner_details.id ) AS owner_ids, GROUP_CONCAT( DISTINCT users.id ) AS user_ids
                                                FROM wp_owner_details AS owner_details
                                                JOIN wp_syndicate_details AS syndicate ON owner_details.syndicate_id = syndicate.id
                                                JOIN wp_users AS users ON users.id = owner_details.user_id
                                                WHERE syndicate.id =".$syndicateId."
                                                GROUP BY syndicate.id";                                             
                  $bookingOwnerIdsDates = $wpdb->get_results($getBookingOwnerIdsQuery);
                  foreach($bookingOwnerIdsDates as $ids)
                  {
                      $bookingDatesString = $ids->booking_ids;
                      $ownerIdsString = $ids->owner_ids;
                      $usersIdsString = $ids->user_ids;
                      $calculationIdsString = $ids->calculation_id;
                  }                                 
                
                  // delete owners from owners table
                  if($ownerIdsString != '')
                  {
                      $deletedSyndicate = true;
                      $deletedOwner = true;
                      $wpdb->delete('wp_users', array('id' => $usersIdsString), array('%s'));
                      $wpdb->delete('wp_owner_details', array('syndicate_id' => $syndicateId), array('%s'));
                      $wpdb->delete('wp_owner_booking', array('syndicate_id' => $syndicateId), array('%s'));
                      $wpdb->delete('wp_booking_calculations', array('syndicate_id' => $syndicateId), array('%s'));
                      $wpdb->delete('wp_syndicate_details', array('id' => $syndicateId), array('%s'));
                  }else{
                      $deletedSyndicate = true;
                      $wpdb->delete('wp_syndicate_details', array('id' => $syndicateId), array('%s'));
                  }
                 
                  if($deletedSyndicate)
                      $syndicate_res = "Syndicate deleted successfully ";
                  if($deletedOwner)
                      $syndicate_res = $syndicate_res . " along with respective owners and their respective bookings.";
                                       
              }elseif($syndicateStatus != '' && $syndicateStatus == 1){
                if(isset($_POST['syndicate_image_save']) && $_POST['syndicate_image_save'] == 1 && $_POST['syndicate_image'] != "")
                {
                    $wpdb->update('wp_syndicate_details', array('syndicate' => $name ." ".$model, 'syndicate_name' => trim($_POST['syndicate_name']), 'syndicate_year' => $_POST['syndicate_year'], 'model' => $_POST['model'], 'cmm_date' => $_POST['cmm_date'], 'owners' => $_POST['owners'], 'notes' => $_POST['notes'], 'status' => $_POST['status'], 'image' => $_POST['syndicate_image'], 'thumbnail_image' => $_POST['syndicate_image_thumbnail']), array( 'syndicate_name' => $name, 'model' => $model ) );   
                    $syndicate_res = "Syndicate Updated Successfully"; 
                }elseif(isset($_POST['syndicate_image_delete']) && $_POST['syndicate_image_delete'] == 1)
                {
                    $wpdb->update('wp_syndicate_details', array('syndicate' => $name ." ".$model, 'syndicate_name' => trim($_POST['syndicate_name']), 'syndicate_year' => $_POST['syndicate_year'], 'model' => $_POST['model'], 'cmm_date' => $_POST['cmm_date'], 'owners' => $_POST['owners'], 'notes' => $_POST['notes'], 'status' => $_POST['status'], 'image' => '', 'thumbnail_image' => ''), array( 'syndicate_name' => $name, 'model' => $model ) );   
                    $syndicate_res = "Syndicate Updated Successfully"; 
                }else{
                    
                    // Query to Calculate the weekdays and weekend based upon date of joining
		$syndicateCommDateQuery = "select cmm_date from wp_syndicate_details where id=". $syndicateId ;
 		$syndicateCommDate = $wpdb->get_results($syndicateCommDateQuery)[0]->cmm_date ;
                            
		$syndicateCommYear = date('Y', strtotime($syndicateCommDate));
                
                $syndicatePostValueFormatted = date('Y', strtotime($syndicatePostValue));
                                              
		//$currentTime = time();
		//$timeDifference = $currentTime - $syndicateCommDate ;
                
		$yearDiffrence = ($syndicatePostValueFormatted - $syndicateCommYear);
                
		if($yearDiffrence == 1)
                    {                
                     $wpdb->update('wp_syndicate_details', array('syndicate' => $name ." ".$model, 'syndicate_name' => trim($_POST['syndicate_name']), 'syndicate_year' => $_POST['syndicate_year'], 'model' => $_POST['model'], 'cmm_date' => $_POST['cmm_date'], 'owners' => $_POST['owners'], 'notes' => $_POST['notes'], 'status' => $_POST['status']), array( 'syndicate_name' => $name, 'model' => $model ) );
                    
                     $wpdb->update('wp_owner_details',
                                array('date_owner' => $_POST['cmm_date']), 
                                array( 'syndicate_id' => $syndicateId ));
                     
                     $wpdb->delete('wp_owner_booking', array('syndicate_id' => $syndicateId), array('%s'));
                     
                     $wpdb->delete('wp_booking_calculations', array('syndicate_id' => $syndicateId), array('%s'));
                     
                           // Query to Calculate the weekdays and weekend based upon date of joining
                            $syndicateCommDateQuery = "select cmm_date,owners from wp_syndicate_details where id=". $syndicateId ;
                            $syndicateCommDate = strtotime($wpdb->get_results($syndicateCommDateQuery)[0]->cmm_date );
                            $numberOfOwners = $wpdb->get_results($syndicateCommDateQuery)[0]->owners;

                            $ownerCommDateQuery = "select date_owner,user_id from wp_owner_details where syndicate_id = ". $syndicateId ;
                             $ownerResult = $wpdb->get_results($ownerCommDateQuery) ;
                          
                            foreach($ownerResult as $ownerResultDate)
                            {
                               $ownreComm =  strtotime($ownerResultDate->date_owner);
                               $User_Id = $ownerResultDate->user_id;
                               
                             $timeDifference = $ownreComm - $syndicateCommDate ;
                             //echo $timeDifference .'<br />';                     
                            $daysDifference = floor($timeDifference/3600/24);

                            $remainingDays = 365 - ($daysDifference % 365) ;
                            $reductionFactor = $remainingDays / 365 ;
                            
                             // echo $reductionFactor;
                             // die;

                            $date = date('Y-m-d H:i:s');
                            if($numberOfOwners)
                            {
                                if($numberOfOwners == 8)
                                {
                                    $allowedWeekdays = floor(30 * $reductionFactor);
                                    $allowedWeekends = floor(13 * $reductionFactor);

                                }elseif($numberOfOwners == 6)
                                {
                                    $allowedWeekdays = floor(38 * $reductionFactor);
                                    $allowedWeekends = floor(19 * $reductionFactor);    
                                }elseif($numberOfOwners == 4)
                                {
                                    $allowedWeekdays = floor(60 * $reductionFactor);
                                    $allowedWeekends = floor(26 * $reductionFactor);    
                                }elseif($numberOfOwners == 2)
                                {
                                    $allowedWeekdays = floor(120 * $reductionFactor);
                                    $allowedWeekends = floor(52 * $reductionFactor);    
                                }
                                $totalNumberOfDays = ($allowedWeekdays + $allowedWeekends);     
                            }else{
                                $allowedWeekdays = 0;
                                $allowedWeekends = 0;
                            }
                            $tempDate = $commencementDate;
                            $tempDateArray = explode(' ', $tempDate);
                            //echo "<pre>";print_r($tempDateArray);
                            $year = isset($tempDateArray[3]) ? $tempDateArray[3] : '';
                            $month = isset($tempDateArray[2]) ? $tempDateArray[2] : '';
                            $day = isset($tempDateArray[1]) ? $tempDateArray[1] : '';
                            if($month != '')
                            {
                                $month = date('m', strtotime($month));    
                            }
                            if($month != '' && $day != '' && $year != '')
                            {
                                $commencementDate = $year.'-'.$month.'-'.$day;
                            }
                            //echo 'commencementDate - '.$commencementDate."<br>";
                            $date = new DateTime("+12 months $commencementDate");
                            $newCommencementDate = $date->format('Y-m-d') . "\n";
                            //echo 'newCommencementDate - '.$newCommencementDate."<br>";die;
                            $date = date('Y-m-d');
                            
                            $wpdb->insert('wp_booking_calculations', 
                                array('date_created' => $date, 
                                    'date_updated' => $date, 
                                    'total_no_of_days' => $totalNumberOfDays, 
                                    'allowed_weekdays' => $allowedWeekdays, 
                                    'allowed_weekends' => $allowedWeekends, 
                                    'booked_weekdays' => 0, 
                                    'booked_weekends' => 0,
                                    'weekday_count' => 0, 
                                    'weekend_booked' => 0,
                                    'last_weekend_booked' => '',
                                    'syndicate_id' => $syndicateId, 
                                    'user_id' => $User_Id,
                                    'start_date' => $commencementDate,
                                    'end_date' => $newCommencementDate));
                            }
                          
                                        
                    }else{
                        if($syndicatePostValue != "" && $yearDiffrence == 0){
                          $syndicate_res = "You can't modify syndicate commencement date as the anniversary of the syndicate has not been completed.";  
                        }else{
                           $wpdb->update('wp_syndicate_details', array('syndicate' => $name ." ".$model, 'syndicate_name' => trim($_POST['syndicate_name']), 'syndicate_year' => $_POST['syndicate_year'], 'model' => $_POST['model'], 'cmm_date' => $_POST['cmm_date'], 'owners' => $_POST['owners'], 'notes' => $_POST['notes'], 'status' => $_POST['status']), array( 'syndicate_name' => $name, 'model' => $model ) );  
                          $syndicate_res = "Syndicate Updated Successfully"; 
                        }
                     }
                }
               
             }
          }
	}else{    		
        $wpdb->insert('wp_syndicate_details', array('id' => '', 'syndicate' => $name . " ".$model, 'syndicate_name' => trim($_POST['syndicate_name']), 'syndicate_year' => $_POST['syndicate_year'], 'model' => $_POST['model'], 'cmm_date' => $_POST['cmm_date'], 'owners' => $_POST['owners'], 'notes' => $_POST['notes'], 'image' => $_POST['syndicate_image'], 'thumbnail_image' => $_POST['syndicate_image_thumbnail']));    		
        $syndicate_res = "Syndicate Created Successfully";    		
	}
        //echo $syndicate_res;
    $_SESSION['syndicate_form_message'] = $syndicate_res;
    wp_redirect(home_url('/list-of-syndicates'));

}

if($type == 'syndicate')
{
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $sql1 = "SELECT syndicate_name,syndicate_year,model,cmm_date,owners,notes,thumbnail_image FROM wp_syndicate_details where id = ".$id." and status = 1";
    //echo $sql1;
    $count = $wpdb->get_results($sql1);
    echo json_encode($count);
}

if($type == 'ownerListSurname')
{
    $syndicate = isset($_POST['syndicate']) ? $_POST['syndicate'] : '';
    //$syndicateSurnameQuery = "SELECT surname FROM wp_owner_details where syndicate = '".$syndicate."' and status = 1";
    $syndicateSurnameQuery = "SELECT owner_Details.surname, syndicate.cmm_date 
                                FROM wp_owner_details AS owner_Details 
                                JOIN wp_syndicate_details AS syndicate ON owner_Details.syndicate_id = syndicate.id
                                where owner_Details.syndicate = '".$syndicate."'";
    //echo $syndicateSurnameQuery;
    $count = $wpdb->get_results($syndicateSurnameQuery);
    echo json_encode($count);
}
if($type == 'getOwnerList')
{
    $syndicate = isset($_POST['syndicate']) ? $_POST['syndicate'] : '';
    $surname = isset($_POST['surname']) ? $_POST['surname'] : '';
    /*$syndicateDetailsQuery = "SELECT *, wu.user_pass as password FROM wp_owner_details as wod 
                                join wp_users as wu on wu.user_email = wod.email 
                                WHERE wod.syndicate = '".$syndicate."' and wod.surname = '".$surname."' and wod.status = 1";*/
    $syndicateDetailsQuery = "SELECT *, user_pass_base64 as password FROM wp_owner_details as wod  
                                WHERE wod.syndicate = '".$syndicate."' and wod.surname = '".$surname."' and wod.status = 1";
    //echo $sql1;
    $count = $wpdb->get_results($syndicateDetailsQuery);
    if(isset($count[0]))
    {
        $count[0]->password = base64_decode($count[0]->password);    
    }
    echo json_encode($count);
}
if($type == 'getSyndicateDates')
{    
    $syndicateId = isset($_POST['syndicateId']) ? $_POST['syndicateId'] : '';
    //$surname = isset($_POST['surname']) ? $_POST['surname'] : '';
	if($syndicateId != '')
	{
    	/*$syndicateDetailsQuery = "SELECT owner_details.color as user_color, owner_details.user_id, GROUP_CONCAT(owner_book.booking_dates) as booking_dates, '' as first_name , '' as surname  
                                        from wp_owner_booking as owner_book 
                                        join wp_owner_details as owner_details on owner_details.user_id = owner_book.user_id 
                                        where owner_book.syndicate_id = ".$syndicateId." and owner_book.status = 1 and owner_details.status = 1 group by user_color";
    		//echo $syndicateDetailsQuery;//die;
    	$syndicateDetails = $wpdb->get_results($syndicateDetailsQuery);
        //echo "<pre>";print_R($syndicateDetails);
        $syndicateDetailsAdditionalQuery = "SELECT owner_book.user_color as user_color, owner_book.user_id, GROUP_CONCAT(owner_book.booking_dates) as booking_dates, '' as first_name, '' as surname 
                                            from wp_owner_booking as owner_book 
                                            where owner_book.syndicate_id = ".$syndicateId." and owner_book.status = 1 and owner_book.user_id = 0
                                            group by user_color";
        $syndicateDetailsAdditional = $wpdb->get_results($syndicateDetailsAdditionalQuery);
        //print_R($syndicateDetailsAdditional);
        $ownersForSyndicateQuery = "select first_name, surname, color as user_color, user_id from wp_owner_details where syndicate_id = ".$syndicateId." and status = 1";
        $ownersForSyndicate = $wpdb->get_results($ownersForSyndicateQuery);*/
        /*
		 $ownersForSyndicateQuery = "SELECT owner_details.color AS user_color, owner_details.user_id AS user_id, GROUP_CONCAT(owner_book.booking_dates) AS booking_dates, first_name , surname  
				FROM wp_owner_booking AS owner_book 
				JOIN wp_owner_details AS owner_details ON owner_details.user_id = owner_book.user_id 
				WHERE owner_book.syndicate_id = ".$syndicateId." AND owner_book.status = 1 AND owner_details.status = 1 

				GROUP BY user_id 

				UNION ALL 

				SELECT owner_book.user_color AS user_color, owner_book.user_color AS user_id, GROUP_CONCAT(owner_book.booking_dates) AS booking_dates, '' AS first_name, '' AS surname
				FROM wp_owner_booking AS owner_book 
				WHERE owner_book.syndicate_id = ".$syndicateId." 
				AND owner_book.user_id = 0 

				GROUP BY user_color";*/	
		 $ownersForSyndicateQuery = "SELECT owner_details.color AS user_color, owner_details.user_id AS user_id, GROUP_CONCAT(owner_book.booking_dates) AS booking_dates, first_name , surname  
				FROM wp_owner_booking AS owner_book 
				JOIN wp_owner_details AS owner_details ON owner_details.user_id = owner_book.user_id 
				WHERE owner_book.syndicate_id = ".$syndicateId." AND owner_book.status = 1 AND owner_details.status = 1 AND owner_book.booking_dates <> ''

				GROUP BY user_id 

				UNION ALL 

				SELECT owner_book.user_color AS user_color, owner_book.user_color AS user_id, GROUP_CONCAT(owner_book.booking_dates) AS booking_dates, '' AS first_name, '' AS surname
				FROM wp_owner_booking AS owner_book 
				WHERE owner_book.syndicate_id = ".$syndicateId." 
				AND owner_book.user_id = 0 

				GROUP BY user_color";		
        $ownersForSyndicate = $wpdb->get_results($ownersForSyndicateQuery);
		
        $resultArray = array_merge($ownersForSyndicate);
        //print_R($resultArray);
		echo json_encode($resultArray);
	}
    
}

if($_REQUEST['save-changes'])
{
    //echo "<pre>";print_r($_REQUEST);
    $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
    $dates = isset($_REQUEST['dates']) ? $_REQUEST['dates'] : '';
    $booking_dates = isset($_REQUEST['booking_dates']) ? $_REQUEST['booking_dates'] : '';
    $user_color = isset($_REQUEST['color']) ? $_REQUEST['color'] : '';
    $syndicate_id = isset($_REQUEST['syndicate_list']) ? $_REQUEST['syndicate_list'] : 0;
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : 0;
    $currentId = 0;
    $errorMessage = '';
    $ownerBookedDates = array();
    
    $dates_to_remove = isset($_REQUEST['dates_to_remove']) ? $_REQUEST['dates_to_remove'] : '';
    $dates_to_add = isset($_REQUEST['dates_to_add']) ? $_REQUEST['dates_to_add'] : '';
    $recredit = isset($_REQUEST['recredit']) ? $_REQUEST['recredit'] : 0;
    
    // add dates
    $dates_to_array = array_unique(explode(',' , $dates_to_add));
		
		$booking_unique = md5(rand());
    foreach ($dates_to_array as $booked_date) {
    	if($booked_date != '') {
			$wpdb->insert('wp_owner_booking', 
				array(
					'user_id' => $user_id, 
					'user_color' => $user_color, 
					'booking_dates' => $booked_date, 
					'syndicate_id' => $syndicate_id, 
					'session_id' => $booking_unique,
					'status' => 1 
			));  
		}
    }
    
    // remove dates
    //var_dump($dates_to_remove);
    
    $dates_to_array = array_unique(explode(',' , $dates_to_remove));
    
    $remove_count = count($dates_to_array);
    
    for($i = 0; $i < $remove_count; $i++) {
    	
    	$booked_date = $dates_to_array[$i];
  		$query = "DELETE FROM wp_owner_booking WHERE user_id = '$user_id' AND booking_dates = '$booked_date' ";
		$wpdb->query($query); 
    	
    }
    
    
    //var_dump($recredit);
    
    // save penalties
    $penalties_to_array = array_unique(explode(',' , $recredit));
    
    $recredit_count = count($penalties_to_array);
    
    for($i = 0; $i < $recredit_count; $i++) {
    	if($penalties_to_array[$i] == 1) {
    		
			$wpdb->insert('wp_booking_penalties', 
				array(
					'user_id' => $user_id, 
					'date' => $dates_to_array[$i]
			));  
    	}
    }
    
    
    
    /*
    //$tempDate = '';
    //$tempDate = $tempDate.' : '.$booking_dates;
    if(isset($_SESSION['booking-error-message']))
    {
        unset($_SESSION['booking-error-message']);
    }
    $datesArray = array();
    $userIdArray = array();
    
    //$tempDate = $tempDate.' : '.$dates;
    //$tempDate = $tempDate.' : '.$booking_dates;
    
    if($booking_dates != '')
    {
        $datesIdArray = explode(';' , $booking_dates);
        foreach($datesIdArray as $dateIdString)
        {
            $dateIdArray = explode(',', $dateIdString);
            $i = 0;
            foreach($dateIdArray as $dateId)
            {
                if($dateId != '')
                {                
                    if($i == 0)
                    {
                        $datesArray[] = $dateId;
                    }else{
                        $userIdArray[] = $dateId;
                    }   
                    $i++;   
                } 
            }
        }
        //$ownerBookedDates = explode(',' , $dates);
        $ownerBookedDates = $datesArray;    
    }else{
        $ownerBookedDates = array_unique(explode(',' , $dates));
    }
    $bookingInsertArray = array();
    //echo "<pre>";print_r($ownerBookedDates); die;
    //$ownerBookedDates = explode(',' , $dates);
    //$tempDate = $tempDate.' : '.var_dump($ownerBookedDates);
    foreach($ownerBookedDates as $booked_date)
    {
        //$tempDate = $tempDate.' : '.$booked_date;
        $bookingDates = "SELECT count(*) as count from wp_owner_booking where booking_dates = '".$booked_date."' and syndicate_id = ".$syndicate_id;
    	$count = $wpdb->get_results($bookingDates);  
        //$tempDate = $tempDate.' : '.$bookingDates;  
    	foreach($count as $num)
        {
            $values = $num->count;
    	}
        if($values > 0 && $user_id != null)
        {
            $bookingCount = 0;
            $bookingDatesUsers = "SELECT count(*) as count from wp_owner_booking where booking_dates = '".$booked_date."' and syndicate_id = ".$syndicate_id." AND user_id = ".$user_id;
    	    $userDetails = $wpdb->get_results($bookingDatesUsers);
            //print_r($userDetails);die;
            foreach($userDetails as $userCount)
            {
                $bookingCount = $userCount->count;
            }
            if($bookingCount == 0)
            {
                $getUserName = "SELECT first_name, surname from wp_owner_details where syndicate_id = ".$syndicate_id." AND user_id = ".$user_id;
    	        $userDetails = $wpdb->get_results($getUserName);
                $selectedUserName = '';
                foreach($userDetails as $user)
                {
                    $selectedUserName = $user->first_name.' '.$user->surname;
                }
                $originalDate = $booked_date;
                $newDate = date("d-m-Y", strtotime($originalDate));
                $errorMessage = $errorMessage . 'The date <b>'.$newDate.'</b> selected is not associated with the owner. <b>'.$selectedUserName."</b><br />";
                continue;
            }
        }
        //$tempDate = $tempDate.' : '.$values;
		$bookedDate = date("Y-m-d", strtotime($booked_date));
		$currentDate = strtotime(date("Y-m-d"));	
		$bookedDateString = strtotime($bookedDate);
		
		
		if($values > 0 && $currentDate > $bookedDateString)
        {
            $datediff = $now - $bookedDateString;
            $numberOfDays = floor($datediff/(60*60*24));
            if($numberOfDays >= 0 && $numberOfDays <= 2 && $status == 0)
            {
	    $_SESSION['booking-error-message'] =  "You can't cancel as the booking date is less than 2 days";
                break;
                wp_redirect(home_url('/full-view-booking/?syndicate_id='.$_POST["syndicate_list"]));
            }
        }
		if($values > 0)
        {
            //$tempDate = $tempDate.' : '.'we are in if';
		   if($booked_date != '')
        	{
        	   if($status == 0)
               {
                   $returnMessage = ''; 
                   // Using where formatting.
						//$wpdb->delete( 'table', array( 'ID' => 1 ), array( '%d' ) );
                   //$wpdb->delete('wp_owner_booking', array('booking_dates' => $booked_date), array('%s')); //ori
                  $quer=" DELETE FROM wp_owner_booking WHERE user_id = '$user_id' AND booking_dates = '$booked_date' ";
						$wpdb->query($quer); 
                   $returnMessage = 'Bookings have been canceled successfully.'; 
//                    $getUserIdQuery = "SELECT user_id from wp_owner_booking where booking_dates = '".$booked_date."' and syndicate_id = ".$syndicate_id;
// 	               $getUserId = $wpdb->get_results($getUserIdQuery);
//                    $userId = 0;
//                    //echo "<pre>";print_r($getUserId);die;
//                    foreach($getUserId as $uesrId)
//                    {
//                        $userId = $uesrId->user_id; 
//                    }
                   $userId = $user_id; 
                   // check dates if they are weekday or weekend
                   $weekdays = 0;
                   $weekends = 0;
                   $weekendBooked = 0;
                   $lastWeekendBookedDate = '';
                   if(date('N', strtotime($booked_date)) >= 6)
                   {
                       $weekends++;
                   }else{
                       $weekdays++;
                   }  
                   //echo $userId;echo $syndicate_id;die();
                   if($userId && $syndicate_id)
                   {
                       $bookingCalculationsQuery = "SELECT * FROM wp_booking_calculations WHERE user_id = ".$userId." AND syndicate_id = ".$syndicate_id." ORDER BY id DESC LIMIT 1";
	                   $bookingCalculations = $wpdb->get_results($bookingCalculationsQuery);
                       $calculationData = array();
                       foreach($bookingCalculations as $calculationsData)
                       {
                            $calculationsData = $calculationsData;  
                       }
                       $bookedWeekends = $calculationsData->booked_weekends;
                       $bookedWeekdays = $calculationsData->booked_weekdays;
                       
                       $weekendBooked = $calculationsData->weekend_booked;
                       $weekendBookedDate = $calculationsData->last_weekend_booked;
                       $finalBookedWeekends = $bookedWeekends;
                       $finalBookedWeekdays = $bookedWeekdays;
                       if($recredit)
                       {
                           $finalBookedWeekends = ($bookedWeekends - $weekends);
                           $finalBookedWeekdays = ($bookedWeekdays - $weekdays);
                           if($finalBookedWeekends > $bookedWeekends)
                           {
                                $finalBookedWeekends = $bookedWeekends;                            
                           }
                           if($finalBookedWeekends < 0)
                           {
                                $finalBookedWeekends = 0;                            
                           }
                           if($finalBookedWeekdays > $bookedWeekdays)
                           {
                                $finalBookedWeekdays = $bookedWeekdays;                            
                           }
                           if($finalBookedWeekdays < 0)
                           {
                                $finalBookedWeekdays = 0;                            
                           }
                           $returnMessage = $returnMessage . ' Owner account is recredited with the cancelled number of days.';
                       }else{
                           $returnMessage = $returnMessage . ' Owner account is deducted with the cancelled number of days.';
                       }
                       if($weekendBooked)
                       {
                           $bookedDate = date("Y-m-d", strtotime($booked_date));
                           if($weekendBookedDate == $bookedDate)
                           {
                               $weekendBookedDate = '0000-00-00';
                               $weekendBooked = ($weekendBooked - 1);
                               if($weekendBooked < 0)
                               {
                                   $weekendBooked = 0; 
                               }  
                           }
                       }
                       //$wpdb->update( $table, $data, $where, $format = null, $where_format = null ); 
                       $wpdb->update('wp_booking_calculations'
                       , array('booked_weekends' => $finalBookedWeekends, 'booked_weekdays' => $finalBookedWeekdays, 'weekend_booked' => $weekendBooked, 'last_weekend_booked' => $weekendBookedDate)
                       , array('user_id' => $userId));                       
                   }                                                         
                   $_SESSION['booking-error-message'] = $returnMessage;                                                         
               }
                wp_redirect(home_url('/full-view-booking'));
           	 }
    	}else{	
    	    //$tempDate = $tempDate.' : '.'we are in else ';
            //$tempDate = $tempDate.' : '.' user color '.$user_color;
            //$tempDate = $tempDate.' : '.' booked_date '.$booked_date;
            //if($booked_date != '' && $user_color != '')
            if($booked_date != '')
            {
                //$tempDate = $tempDate.' : '.' userIdArray '.var_dump($userIdArray); 
                $user_id = isset($userIdArray[$currentId]) ? $userIdArray[$currentId] : $user_id;
                //$tempDate = $tempDate.' : '.' user Id '.$user_id;
                if($user_id == 'red' || $user_id == 'grey')
                {
                    $user_color = $user_id; 
                    $user_id = 0;
                }
                //$tempDate = $tempDate.' : '.' user Id '.$user_id;
                //$tempDate = $tempDate.' : '.' user color '.$user_color;
                if($user_id > 0 && $status != 0)
                {
                   $getUserColorQuery = "SELECT user_color from wp_owner_booking where user_id = ".$user_id." and syndicate_id = ".$syndicate_id;
                   //$tempDate = $tempDate.' : '.' getUserColorQuery '.$getUserColorQuery;
	                 $getUserColor = $wpdb->get_results($getUserColorQuery);                   
                   //echo "<pre>";print_r($getUserColor);die;
                   if(is_array($getUserColor))
                   {
                       $user_color = ''; 
                       foreach($getUserColor as $uesrId)
                       {
                           $user_color = $uesrId->user_color;
                          // break; 
                       }
                   }
                }
                //$tempDate = $tempDate.' : '.' user color '.$user_color;
                //$tempDate = $tempDate.' : '.' user_id '.$user_id;
                if($user_color == 'red' || $user_color == 'grey')
                {
                    $tempDate = $tempDate . ' user_color ' . $user_color;
                    $tempDate = $tempDate . ' user_id ' . $user_id;
                    $tempDate = $tempDate . ' booked_date ' . $booked_date;
                    $tempDate = $tempDate . ' syndicate_id ' . $syndicate_id;
                    $tempDate = $tempDate . ' userIdArray ' . $userIdArray[$currentId];
                    $tempDate = $tempDate . ' currentId ' . $currentId;
                $wpdb->insert('wp_owner_booking', array('id' => '', 
                'user_id' => $user_id, 
                        'user_color' => $userIdArray[$currentId], 
                'booking_dates' => $booked_date, 
                'syndicate_id' => $syndicate_id, 
                'status' => 1 
                ));                
                    $currentId++; 
                    continue;
                }            
                //  ------------- 
                
// what the fuck is this section

                $bookingCalcuationQuery = "SELECT * FROM wp_booking_calculations WHERE syndicate_id = ".$syndicate_id . " AND user_id = ".$user_id." ORDER BY id DESC LIMIT 1";
                //echo $bookingCalcuationQuery;
                $bookingCalcuationData = $wpdb->get_results($bookingCalcuationQuery);
                //echo "<pre>";print_r($bookingCalcuationData);
                $bookingCalcuationDataObj = array();
                foreach($bookingCalcuationData as $data)
                {
                    $bookingCalcuationDataObj = $data;
                }
                //echo "<pre>";print_r($bookingCalcuationDataObj);
                if($bookingCalcuationDataObj->allowed_weekdays <= $bookingCalcuationDataObj->booked_weekdays 
                    && $bookingCalcuationDataObj->allowed_weekdays <= $bookingCalcuationDataObj->booked_weekends)
                {
                    $_SESSION['booking-error-message'] = 'Bookings are completed for your account';
                    wp_redirect(home_url('/full-view-booking'));                    
                }elseif($bookingCalcuationDataObj->allowed_weekdays <= $bookingCalcuationDataObj->booked_weekdays)
                {
                    $_SESSION['booking-error-message'] =  'Bookings for weekdays are completed for your account';
                    wp_redirect(home_url('/full-view-booking'));                    
                }elseif($bookingCalcuationDataObj->allowed_weekends <= $bookingCalcuationDataObj->booked_weekends)
                {
                    $_SESSION['booking-error-message'] =  'Bookings for weekends are completed for your account';
                    wp_redirect(home_url('/full-view-booking'));                    
                }else{
                    // check dates if they are weekday or weekend
                    $weekdays = 0;
                    $weekends = 0;
                    $weekendBooked = 0;
                    $lastWeekendBookedDate = '';
                    $lastWeekendSelectedDate = '';
                    if(date('N', strtotime($booked_date)) >= 6)
                    {
                        $weekends++;
                        if($lastWeekendBookedDate == '')
                        {
                        $lastWeekendBookedDate = $booked_date;
                        }                        
                        $lastWeekendSelectedDate = $booked_date;
                        $weekendBooked = 1;
                    }else{
                        $weekdays++;
                    }
                    //echo 'weekends - '.$weekends.' weekdays - '.$weekdays;
                    // if already a weekend has booked then need to check for FORTNIGHT (two weees)
                    //�	BOAT OWNER IS ALLOWED TO BOOK 1 WEEKEND AT A TIME AND WAIT A FORTNIGHT BEFORE THE BOAT OWNER IS ALLOWED TO ANOTHER WEEKEND
                    if($weekends)
                    {
                        // query to check if current user has any booking for weekends
                        $checkWeekendsQuery = "select STR_TO_DATE(booking_dates, '%m/%d/%Y') as booking_dates 
                                                from wp_owner_booking 
                                                where WEEKDAY(STR_TO_DATE(booking_dates, '%m/%d/%Y')) >= 5 and STR_TO_DATE(booking_dates, '%m/%d/%Y') >= CURRENT_DATE() and user_id = ".$user_id;
                        $checkWeekendsResult = $wpdb->get_results($checkWeekendsQuery);
                        if(!empty($checkWeekendsResult))
                        {
                            //echo "<pre>";print_R($checkWeekendsResult);
                            foreach($checkWeekendsResult as $bookedWeekendDate)
                            {                                    
                                $selectedDateString = StrToTime($lastWeekendBookedDate);
                                $bookedWeekendDateString = StrToTime($bookedWeekendDate->booking_dates);
                                $dateDifference = floor(($selectedDateString - $bookedWeekendDateString)/(60*60*24));
                                //echo 'difference = '.$dateDifference;
                                if($dateDifference < 0)
                                {
                                    //$dateDifference = -($dateDifference);
                                    $selectedDateString = StrToTime($lastWeekendSelectedDate);
                                    //echo 'selectedDateString - '.$selectedDateString;
//                                    echo "<br>";
//                                    echo 'seelcted Date - '. date('Y-m-d', $selectedDateString);
                                    //echo "<br>";
                                    $bookedWeekendDateString = StrToTime($bookedWeekendDate->booking_dates);
                                    //echo 'bookedWeekendDateString - '.$bookedWeekendDateString;
                                    //echo "<br>";
                                    $dateDifference = floor(($bookedWeekendDateString - $selectedDateString)/(60*60*24));
                                    //echo 'difference = '.$dateDifference;
                                    //echo "<br>";
                                }
                                if($dateDifference < 13)
                                {
                                    $_SESSION['booking-error-message'] =  'You can not book consecutive weekends.';
                                    wp_redirect(home_url('/full-view-booking'));                                    
                                }
                            }
                        }
                        
//                         if($bookingCalcuationDataObj->weekend_booked)
//                         {
//                             $lastWeekendBookedDate = $bookingCalcuationDataObj->last_weekend_booked;
//                             $timeStamp = StrToTime($lastWeekendBookedDate);
//                             $fortnightdays = StrToTime('+15 days', $timeStamp);
//                             $fortnightDate = date('Y-m-d', $fortnightdays);
//                             if($fortnightDate != date('Y-m-d'))
//                             {
//                                 $_SESSION['booking-error-message'] =  'You can not book consecutive weekends.';
//                                 wp_redirect(home_url('/booking'));
//                             }
//                         }
                        
                    }
                    //�	OWNER CONSTRAINTS: 3 BOOKED SESSIONS AT A TIME ALLOWED THEN 1 MUST EXPIRE BEFORE THEY CAN DO ANOTHER ONE � THERE CANNOT BE ANYTHING MORE THAN 3 UPCOMING BOOKINGS AT ONE GO PER OWNER
                    $ownerBookedQuery = "select count(DISTINCT(session_id)) as count from wp_owner_booking where user_id = " . $user_id . " and syndicate_id = ".$syndicate_id." and STR_TO_DATE(booking_dates, '%m/%d/%Y') >= CURRENT_DATE()";
                    //echo $ownerBookedQuery;
                    $ownerBookedDates = $wpdb->get_results($ownerBookedQuery);
                    //echo 'count - '.count($ownerBookedDates)."<br>";
                    //echo "<pre>";print_R($ownerBookedDates);die;
                    $bookingCount = isset($ownerBookedDates[0]) ? $ownerBookedDates[0]->count : 0;
                    if($bookingCount >= 3)
                    {
                    	$_SESSION['booking-error-message'] =  'You can not make any further bookings before one of your existing bookings completes.';
                        wp_redirect(home_url('/full-view-booking'));
                    }
                    // if the added weekdays and weekends will make the booked count greated than the allowed count then return
                    $tempAllowedWeekdays = ($bookingCalcuationDataObj->booked_weekdays + $weekdays);  
                    $tempAllowedWeekends = ($bookingCalcuationDataObj->booked_weekends + $weekends);
                    if($bookingCalcuationDataObj->allowed_weekdays <= $tempAllowedWeekdays 
                    || $bookingCalcuationDataObj->allowed_weekdays <= $tempAllowedWeekends)
                    {
                        $_SESSION['booking-error-message'] =  'You selected more number of days than you are allowed';
                        wp_redirect(home_url('/full-view-booking'));                        
                    }else{
                        if(!isset($_SESSION['booking-error-message']))
                        {
                            $bookingInsertArray[$currentId]['user_id'] = $user_id;
                            $bookingInsertArray[$currentId]['user_color'] = $user_color;
                            $bookingInsertArray[$currentId]['booked_date'] = $booked_date;
                            $bookingInsertArray[$currentId]['syndicate_id'] = $syndicate_id;
                            $bookingInsertArray[$currentId]['date_updated'] = date('Y:m:d H:i:s');
                            $bookingInsertArray[$currentId]['booked_weekdays'] = $tempAllowedWeekdays;
                            $bookingInsertArray[$currentId]['booked_weekends'] = $tempAllowedWeekends;
                            $bookingInsertArray[$currentId]['weekend_booked'] = $weekendBooked;
                            $bookingInsertArray[$currentId]['last_weekend_booked'] = date("Y:m:d", strtotime($lastWeekendBookedDate));
                        }
                    }
                }
                
                // -------------- 
            }
    	}    
        //$tempDate = $tempDate.' : <br>';
        $currentId++;    
    }
    if(!isset($_SESSION['booking-error-message']) && !empty($bookingInsertArray))
    {
        if (function_exists('com_create_guid') === true)
        {
            $session_id = trim(com_create_guid(), '{}');
        }else{
            $session_id = uniqid(md5(rand()), true);
        }
        foreach($bookingInsertArray as $bookingInsert)
        {
            $wpdb->insert('wp_owner_booking', array('id' => '', 
                'user_id' => $bookingInsert['user_id'], 
                'user_color' => $bookingInsert['user_color'], 
                'booking_dates' => $bookingInsert['booked_date'], 
                'syndicate_id' => $bookingInsert['syndicate_id'], 
                'session_id' => $session_id,
                'status' => 1 
            )); 
            $weekdays = 0;
            $weekends = 0;
            if(date('N', strtotime($bookingInsert['booked_date'])) >= 6)
            {
                $weekends++;
                $weekendBooked = 1;
            }else{
                $weekdays++;
            }
            $actualBookedDays = array();
            
            $bookingCalcuationQuery = "SELECT * FROM wp_booking_calculations WHERE syndicate_id = ".$bookingInsert['syndicate_id'] . " AND user_id = ".$bookingInsert['user_id'];
            $bookingCalcuationData = $wpdb->get_results($bookingCalcuationQuery);
            foreach($bookingCalcuationData as $bookingData)
            {
                $actualBookedDays = $bookingData; 
            }
            
            $tempAllowedWeekdays = ($actualBookedDays->booked_weekdays + $weekdays);  
            $tempAllowedWeekends = ($actualBookedDays->booked_weekends + $weekends);
            
            $wpdb->update('wp_booking_calculations', 
                array('date_updated' => $bookingInsert['date_updated'], 
                'booked_weekdays' => $tempAllowedWeekdays, 
                'booked_weekends' => $tempAllowedWeekends, 
                'weekend_booked' => $bookingInsert['weekend_booked'],
                'last_weekend_booked' => $bookingInsert['last_weekend_booked']),
                array('syndicate_id' => $bookingInsert['syndicate_id'], 'user_id' => $bookingInsert['user_id']));
        }
    }
    //$_SESSION['booking-error-message'] = $errorMessage;
    $_SESSION['submit-booking-error-message'] = $tempDate;
    */
    
    
    wp_redirect(home_url('/full-view-booking?syndicate_id='.$_POST["syndicate_list"]));
	
}
if(isset($_REQUEST['submit-booking']))
{     
    try{
        if(isset($_SESSION['submit-booking-error-message']))
        {
            unset($_SESSION['submit-booking-error-message']);    
        }        
        //echo "<pre>";print_r($_REQUEST);
        $userId = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
        //echo 'userId - '.$userId;
        if($userId)
        {
            $selectedDates = isset($_REQUEST[dates]) ? $_REQUEST[dates] : '';
            if($selectedDates != "")
            {
                $syndicateId = isset($_REQUEST['syndicate_id']) ? $_REQUEST['syndicate_id'] : 0;
                if($syndicateId)
                {
// WHAT THE FUCK
/*
                    $bookingCalcuationQuery = "SELECT * FROM wp_booking_calculations WHERE syndicate_id = ".$syndicateId . " AND user_id = ".$userId;
                    //echo $bookingCalcuationQuery;
                    $bookingCalcuationData = $wpdb->get_results($bookingCalcuationQuery);
                    //echo "<pre>";print_r($bookingCalcuationData);
                    $bookingCalcuationDataObj = array();
                    foreach($bookingCalcuationData as $data)
                    {
                        $bookingCalcuationDataObj = $data;
                    }
                    //echo "<pre>";print_r($bookingCalcuationDataObj);
                    if($bookingCalcuationDataObj->allowed_weekdays <= $bookingCalcuationDataObj->booked_weekdays 
                        && $bookingCalcuationDataObj->allowed_weekdays <= $bookingCalcuationDataObj->booked_weekends)
                    {
                        $_SESSION['submit-booking-error-message'] = 'Bookings are completed for your account';
                        wp_redirect(home_url('/booking'));
                    }elseif($bookingCalcuationDataObj->allowed_weekdays <= $bookingCalcuationDataObj->booked_weekdays)
                    {
                        $_SESSION['submit-booking-error-message'] =  'Bookings for weekdays are completed for your account';
                        wp_redirect(home_url('/booking'));
                    }elseif($bookingCalcuationDataObj->allowed_weekends <= $bookingCalcuationDataObj->booked_weekends)
                    {
                        $_SESSION['submit-booking-error-message'] =  'Bookings for weekends are completed for your account';
                        wp_redirect(home_url('/booking'));
                    }else{
                        // check dates if they are weekday or weekend
                        $selectedDateArray = explode(',', $selectedDates);
                        $weekdays = 0;
                        $weekends = 0;
                        $weekendBooked = 0;
                        $lastWeekendBookedDate = '';
                        $lastWeekendSelectedDate = '';
                        foreach($selectedDateArray as $selectedDate)
                        {
                            if(date('N', strtotime($selectedDate)) >= 6)
                            {
                                $weekends++;
                                if($lastWeekendBookedDate == '')
                                {
                                $lastWeekendBookedDate = $selectedDate;
                                }               
                                $lastWeekendSelectedDate = $selectedDate;
                                $weekendBooked = 1;
                            }else{
                                $weekdays++;
                            }
                        }
                        //echo 'weekends - '.$weekends.' weekdays - '.$weekdays;
                        
                        // if already a weekend has booked then need to check for FORTNIGHT (two weees)
                        //�	BOAT OWNER IS ALLOWED TO BOOK 1 WEEKEND AT A TIME AND WAIT A FORTNIGHT BEFORE THE BOAT OWNER IS ALLOWED TO ANOTHER WEEKEND
                        if($weekends)
                        {
                            // query to check if current user has any booking for weekends
                            $checkWeekendsQuery = "select STR_TO_DATE(booking_dates, '%m/%d/%Y') as booking_dates 
                                                    from wp_owner_booking 
                                                    where WEEKDAY(STR_TO_DATE(booking_dates, '%m/%d/%Y')) >= 5 and STR_TO_DATE(booking_dates, '%m/%d/%Y') >= CURRENT_DATE() and user_id = ".$userId;
                            $checkWeekendsResult = $wpdb->get_results($checkWeekendsQuery);
                            if(!empty($checkWeekendsResult))
                            {
                                //echo "<pre>";print_R($checkWeekendsResult);
                                foreach($checkWeekendsResult as $bookedWeekendDate)
                                {                                    
                                    $selectedDateString = StrToTime($lastWeekendBookedDate);
                                    //echo 'selectedDateString - '.$selectedDateString;
                                    //echo "<br>";
                                    //echo 'seelcted Date - '. date('Y-m-d', $selectedDateString);
                                    //echo "<br>";
                                    $bookedWeekendDateString = StrToTime($bookedWeekendDate->booking_dates);
                                    //echo 'bookedWeekendDateString - '.$bookedWeekendDateString;
                                    //echo "<br>";
                                    $dateDifference = floor(($selectedDateString - $bookedWeekendDateString)/(60*60*24));
                                    //echo 'difference = '.$dateDifference;
                                    //echo "<br>";
                                    if($dateDifference < 0)
                                    {
                                        //$dateDifference = -($dateDifference);
                                        $selectedDateString = StrToTime($lastWeekendSelectedDate);
                                        //echo 'selectedDateString - '.$selectedDateString;
                                        //echo "<br>";
                                        //echo 'seelcted Date - '. date('Y-m-d', $selectedDateString);
                                        //echo "<br>";
                                        $bookedWeekendDateString = StrToTime($bookedWeekendDate->booking_dates);
                                        //echo 'bookedWeekendDateString - '.$bookedWeekendDateString;
                                        //echo "<br>";
                                        $dateDifference = floor(($bookedWeekendDateString - $selectedDateString)/(60*60*24));
                                        //echo 'difference = '.$dateDifference;
                                        //echo "<br>";
                                    }
                                    //echo 'difference = '.$dateDifference;//die;
                                    if($dateDifference < 13)
                                    {
                                        //die('we are inside if');
                                        $_SESSION['submit-booking-error-message'] =  'You can not book consecutive weekends. Please consult management.';
                                        wp_redirect(home_url('/booking'));
                                    }
                                }
                            }
//                             if($bookingCalcuationDataObj->weekend_booked)
//                             {
//                                 $lastWeekendBookedDate = $bookingCalcuationDataObj->last_weekend_booked;
//                                 $timeStamp = StrToTime($lastWeekendBookedDate);
//                                 $fortnightdays = StrToTime('+15 days', $timeStamp);
//                                 $fortnightDate = date('Y-m-d', $fortnightdays);
//                                 if($fortnightDate != date('Y-m-d'))
//                                 {
//                                     $_SESSION['submit-booking-error-message'] =  'You can not book consecutive weekends. Please consult management.';
//                                     wp_redirect(home_url('/booking'));
//                                 }
//                             }
                        }
                        
                        //�	OWNER CONSTRAINTS: 3 BOOKED SESSIONS AT A TIME ALLOWED THEN 1 MUST EXPIRE BEFORE THEY CAN DO ANOTHER ONE � THERE CANNOT BE ANYTHING MORE THAN 3 UPCOMING BOOKINGS AT ONE GO PER OWNER
                        $ownerBookedQuery = "select count(DISTINCT(session_id)) as count from wp_owner_booking where user_id = " . $userId . "  and syndicate_id = ".$syndicateId." and STR_TO_DATE(booking_dates, '%m/%d/%Y') >= CURRENT_DATE()";
                        //echo $ownerBookedQuery;
                        $ownerBookedDates = $wpdb->get_results($ownerBookedQuery);
                        //echo 'count - '.count($ownerBookedDates)."<br>";
                        //echo "<pre>";print_R($ownerBookedDates);die;
                        $bookingCount = isset($ownerBookedDates[0]) ? $ownerBookedDates[0]->count : 0;
                        //echo $bookingCount;die;
                        if($bookingCount >= 5)
                        {
                            $_SESSION['submit-booking-error-message'] =  'You can not make any further bookings before one of your existing bookings completes.';
                            wp_redirect(home_url('/booking'));
                        }

                        
                        // if the added weekdays and weekends will make the booked count greated than the allowed count then return
                        $tempAllowedWeekdays = ($bookingCalcuationDataObj->booked_weekdays + $weekdays);  
                        $tempAllowedWeekends = ($bookingCalcuationDataObj->booked_weekends + $weekends);
                        if($bookingCalcuationDataObj->allowed_weekdays <= $tempAllowedWeekdays 
                        || $bookingCalcuationDataObj->allowed_weekdays <= $tempAllowedWeekends)
                        {
                            $_SESSION['submit-booking-error-message'] =  'You selected more number of days than you are allowed';
                            wp_redirect(home_url('/booking'));
                        }else{
                            if($_SESSION['submit-booking-error-message'] == '')
                            {
                            */
				if ($_POST['catering']!=null)
                                    $catering = implode(',', $_POST['catering']);
                                else
                                    $catering = $_POST['catering'];

                                $selectedDateArray = explode(',', $selectedDates);
                                if (function_exists('com_create_guid') === true)
                                {
                                    $session_id = trim(com_create_guid(), '{}');
                                }else{
                                    $session_id = uniqid(md5(rand()), true);
                                }
                                $booking_unique = md5(rand());
                                foreach($selectedDateArray as $bookingDate)
                                {
                                $wpdb->insert('wp_owner_booking', 
                                    array('id' => '', 
                                        'user_id' => $userId, 
                                        'user_color' => $_POST['color'], 
                                        'booking_dates' => $bookingDate, 
                                        'syndicate_id' => $syndicateId, 
                                        'estimated_time' => $_POST['estimated_time'], 
                                        'catering' => $catering, 
                                        'skippering' => $_POST['skippering'], 
                                        'skippering_time' => $_POST['skippering_time'], 
                                        'spl_request' => $_POST['spl_request'],
                                        'session_id' => $booking_unique,
                                        'status' => 1));    
                                }
                                //echo 'weekendBooked - '.$weekendBooked."<br>";
                                //echo 'lastWeekendBookedDate - '.$lastWeekendBookedDate."<br>";
                                /*
                                if($weekendBooked)
                                {
                                    $wpdb->update('wp_booking_calculations', 
                                        array('date_updated' => date('Y:m:d H:i:s'), 
                                        'booked_weekdays' => $tempAllowedWeekdays, 
                                        'booked_weekends' => $tempAllowedWeekends, 
                                        'weekend_booked' => $weekendBooked,
                                        'last_weekend_booked' => date("Y:m:d", strtotime($lastWeekendBookedDate))),
                                        array('syndicate_id' => $syndicateId, 'user_id' => $userId));    
                                }else{
                                    $wpdb->update('wp_booking_calculations', 
                                        array('date_updated' => date('Y:m:d H:i:s'), 
                                        'booked_weekdays' => $tempAllowedWeekdays, 
                                        'booked_weekends' => $tempAllowedWeekends,), 
                                        array('syndicate_id' => $syndicateId, 'user_id' => $userId));
                                }
                                */
                            
							
                        $ownerdetailsQuery = "select syndicate,first_name, email from wp_owner_details where user_id = ".$userId;
                        //echo $ownerBookedQuery;
                        $ownerdetails = $wpdb->get_results($ownerdetailsQuery);
                        foreach($ownerdetails as $ownerrow){
                                $owner_name = $ownerrow->first_name;
                                $owner_email = $ownerrow->email;
				$owner_syndicate = $ownerrow->syndicate;
                                }
                                 
                                $tempDate = explode(",",$selectedDates);
                                $dateArray = array();
                                foreach( $tempDate as $d )
                                {
                                 $my_date = date('d/m/y', strtotime($d));
                                 $dateArray[] = $my_date;
                                }
                                $Date = implode(",",$dateArray);
                                
                                $to = $owner_email;
                                $subject = "Booking Request";
                                $message = '<div style="width:932px; margin: auto; padding:0 25px; font-size:15px;">
                                                <a rel="home" title="Luxury Boat" href="http://luxuryboatsyndicates.com.au/">
                                                <img src="http://luxuryboatsyndicates.com.au/wp-content/themes/LuxuryBoat/images/logo.jpg"></a>
                                                <h1 style=" color: #40B5E5;font-family:sans-serif, Trebuchet MS, Arial, Helvetica;font-size: 25px !important;font-weight: normal;margin: 42px 0;text-transform: uppercase;">booking details</h1>
                                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Dear '.$owner_name.',</p>
                                                <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif;">The following booking has been made:</p>
                                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Booking Dates: '.$Date.'</p>
                                                <p style="font-family:Arial, Helvetica, sans-serif; margin:15px 0;">Estimated Time of Departure: '.$_POST['estimated_time'].'</p>
                                                <p style="margin:15px 0; font-family:Arial, Helvetica, sans-serif;">Catering Requirements: '.$catering.'</p>
                                                <p style="font-family:Arial, Helvetica, sans-serif; margin:15px 0;">Skippering Requirements: '.$_POST['skippering'].'<br /> <br /> 
                                                <span style="margin:0 0 0 15px">Time Required: '.$_POST['skippering_time'].'</span></p>
                                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif;  margin:0px 0 30px 0;">Special Requests: '.$_POST['spl_request'].'</p>

                                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif; line-height:25px;">THANK YOU FROM THE LUXURY BOAT SYNDICATES TEAM.<br />
                                                IF YOU WOULD LIKE TO CONTACT US PLEASE CALL US ON<br />
                                                02 8231 6538.</p>
                                                </p>';
                                $headers = "From: LBS <bookings@luxuryboatsyndicates.com.au>\r\n";
                                $headers .= "MIME-Version: 1.0\r\n";
                                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                                mail($to,$subject,$message,$headers);



                                $to_mng = "bookings@luxuryboatsyndicates.com.au";
                                $subject_mng = "Booking Request";
                                $message_mng = '<div style="width:932px; margin: auto; padding:0 25px; font-size:15px;">
                                                <a rel="home" title="Luxury Boat" href="http://luxuryboatsyndicates.com.au/">
                                                <img src="http://luxuryboatsyndicates.com.au/wp-content/themes/LuxuryBoat/images/logo.jpg"></a>
                                                <h1 style=" color: #40B5E5;font-family:sans-serif, Trebuchet MS, Arial, Helvetica;font-size: 25px !important;font-weight: normal;margin: 42px 0;text-transform: uppercase;">booking details</h1>
                                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Dear Management,</p>
                                                <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif;">The following booking has been made:</p>
												<p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif;">Syndicate Name: '.$owner_syndicate.'</p>
												<p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif;">Owner Name: '.$owner_name.'</p>
                                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Booking Dates: '.$Date.'</p>
                                                <p style="font-family:Arial, Helvetica, sans-serif; margin:15px 0;">Estimated Time of Departure: '.$_POST['estimated_time'].'</p>
                                                <p style="margin:15px 0; font-family:Arial, Helvetica, sans-serif;">Catering Requirements: '.$catering.'</p>
                                                <p style="font-family:Arial, Helvetica, sans-serif; margin:15px 0;">Skippering Requirements: '.$_POST['skippering'].'<br /> <br /> 
                                                <span style="margin:0 0 0 15px">Time Required: '.$_POST['skippering_time'].'</span></p>
                                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif;  margin:0px 0 30px 0;">Special Requests: '.$_POST['spl_request'].'</p>

                                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif; line-height:25px;">THANK YOU FROM THE LUXURY BOAT SYNDICATES TEAM.<br />
                                                IF YOU WOULD LIKE TO CONTACT US PLEASE CALL US ON<br />
                                                02 8231 6538.</p>
                                                </p>';
                                $header_mng = "From: ".$owner_name." <".$owner_email.">\r\n";

                                $header_mng .= "MIME-Version: 1.0\r\n";
                                $header_mng .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                                mail($to_mng,$subject_mng,$message_mng,$header_mng);

                            }		
                       //}
                    //}
                //}        
            }
        }
        wp_redirect(home_url('/booking'));        
    }catch(Exception $error)
    {
        echo $error->getMessage();die;
    }     
}

if($type == 'delete')
{
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    if($id)
    {
        $module = isset($_POST['module']) ? $_POST['module'] : 0;
        $table = '';
        switch($module)
        {
            case 'owner':
                $table = 'wp_owner_details';                
                break;
            case 'booking':
                $table = 'wp_owner_booking';
                break;
            case 'syndicate':
                $table = 'wp_syndicate_details';
                break;        
        }
        $wpdb->update($table,array('status' => 0), array('id' => $id));
        echo true;       
    }else{
        echo false;
    }
}
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'download')
{
    $defectId = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
    if($defectId)
    {
        $images = '';
        $defectQuery = "SELECT image FROM wp_report_defect WHERE id = ".$defectId;
        $defectData = $wpdb->get_results($defectQuery);
        foreach($defectData as $image)
        {
            $images = $image->image;   
        }
        if($images != '')
        {
            $imagesArray = explode(',', $images);
            $files_to_zip = array();
            foreach($imagesArray as $filename)
            {
                $imagePath = explode('uploads', $filename);
                $uploadDir = wp_upload_dir();
                $fieldPath = $uploadDir['basedir'];
                $filename =  $fieldPath . $imagePath[1]; 
                $files_to_zip[] = $filename; 
            }
            if(!empty($files_to_zip))
            {
                $zipFileName = 'DefectView_'.$defectId.'.zip';                
                $ziper = new zipfile();
                $ziper->prefix_name = 'DefectView/'; // here you create folder which will contain downloaded files
                $ziper->addFiles($files_to_zip);  // array of files
                $ziper->output($zipFileName); 
                $ziper->forceDownload($zipFileName);
                @unlink($zipFileName); 
                wp_redirect(home_url('/full-view-defect'));  
            }else{
                wp_redirect(home_url('/full-view-defect'));
            }
                       
        }
    }
}

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
        if($defectDetails != '')
        {            
        }
    }
}
if($type == 'updateDefectAction')
{
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    if($id != '' && $status != '')
    {
        $wpdb->update('wp_report_defect',array('status' => $status), array( 'id' => $id ));
        echo 1;
    }else{
        echo 0;
    }
}
if($type == 'getDefectById')
{
    $defectId = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
    if($defectId)
    {
        $defectQuery = "SELECT report.create_date, report.description, report.image, report.status,report.work_carried_out, syndicate.syndicate as syndicate_name, users.display_name as user_name 
                            FROM wp_report_defect as report 
                            join wp_users as users on users.id = report.user_id
                            join wp_owner_details as owner on owner.user_id = report.user_id
                            join wp_syndicate_details as syndicate on owner.syndicate_id = syndicate.id
                            WHERE report.id = ".$defectId;
        $defectData = $wpdb->get_results($defectQuery);
        $defectDetails = '';
        foreach($defectData as $defectPostedData)
        {
            foreach($defectPostedData as $key => $value)
            {
                $defectDetails[$defectId][$key] = $value;    
            }
        }//print_r($defectDetails);
        echo json_encode($defectDetails);
    }
}
if(isset($_REQUEST['fullview-defect']) && $_REQUEST['fullview-defect'] == 'SAVE')
{
    $defectId = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
    if($defectId)
    {
        $status = isset($_REQUEST['status-hidden']) ? $_REQUEST['status-hidden'] : '';
        $problem = isset($_REQUEST['problem']) ? $_REQUEST['problem'] : '';
        $workCarriedOut = isset($_REQUEST['work-carried-out']) ? $_REQUEST['work-carried-out'] : '';
        $wpdb->update('wp_report_defect',array('description' => $problem, 'status' => $status, 'work_carried_out' => $workCarriedOut), array( 'id' => $defectId ));
        $_SESSION['fullview-defect-message'] = 'Updated Sucessfully';  
        wp_redirect(home_url('/full-view-defect?current_id='.$defectId));
    }
}
if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'save changes')
{
    //echo "<Pre>";print_R($_REQUEST);die;
    $syndicateId = isset($_REQUEST['syndicate_list']) ? $_REQUEST['syndicate_list'] : 0;
    if($syndicateId)
    {
        $userColor = isset($_REQUEST['color']) ? $_REQUEST['color'] : '';
        $dates = isset($_REQUEST['dates']) ? $_REQUEST['dates'] : '';
        $userId = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
        $services = isset($_REQUEST['services']) ? $_REQUEST['services'] : '';
        $color = ($userColor != '') ? $userColor : $services;
        if($dates != '' && $color != '')
        {
				$booking_unique = md5(rand());
        $wpdb->insert('wp_owner_booking',
                    array('id' => '',
                    'user_id' => $userId,
                    'user_color' => $color, 
                    'syndicate_id' => $syndicateId, 
                    'booking_dates' => $dates,
                    'estimated_time' => '',
                    'catering' => '',
                    'spl_request' => '',
                    'skippering' => '',
                    'skippering_time' => '', 
										'session_id' => $booking_unique,
                    'status' => '1'));
        //die('we aer jere');
        $_SESSION['fullview-booking-message'] = 'Inserted Sucessfully';
    }else{
            $_SESSION['fullview-booking-message'] = 'Required fields are not supplied';
        }        
    }else{
        $_SESSION['fullview-booking-message'] = 'Insufficent Data';
    }
    wp_redirect(home_url('/full-view-booking'));
}


if(isset($_REQUEST['cancel-booking']))
{
	$synd_id = $_POST['syndicate_id'];
	$syndicatedetailsQuery = "select syndicate from wp_syndicate_details where id = ".$synd_id;
                        //echo $ownerBookedQuery;
                        $syndicatedetails = $wpdb->get_results($syndicatedetailsQuery);
                        foreach($syndicatedetails as $syndicaterow){
                                $syndicate_name = $syndicaterow->syndicate;

                                }
                $user_Id = $_POST['user_id'];				
		$ownerdetailsQuery = "select first_name, surname, email from wp_owner_details where user_id = ".$user_Id;
                        //echo $ownerBookedQuery;
                $ownerdetails = $wpdb->get_results($ownerdetailsQuery);
                foreach($ownerdetails as $ownerrow){
                        $owner_name = $ownerrow->first_name;
                        $surname = $ownerrow->surname;
                        $email = $ownerrow->email;
                        }

                $to = $email;
                $subject = "Cancellation Request";
                $message = '<div style="width:932px; margin: auto; padding:0 25px; font-size:15px;">
                                <a rel="home" title="Luxury Boat" href="http://luxuryboatsyndicates.com.au">
                                <img src="http://luxuryboatsyndicates.com.au/wp-content/themes/LuxuryBoat/images/logo.jpg"></a>
                                <h1 style=" color: #40B5E5;font-family:sans-serif, Trebuchet MS, Arial, Helvetica;font-size: 25px !important;font-weight: normal;margin: 42px 0;text-transform: uppercase;">Booking Cancellation Details</h1>
                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Dear '.$owner_name.',</p>
                                <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif;">Below are details on your cancellation request:</p>
                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Syndicate Name: '.$syndicate_name.'</p>
                                <p style="font-family:Arial, Helvetica, sans-serif; margin:15px 0;">'.$_POST['spl_request'].'</p>																				
                                <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif; line-height:25px;">Thank you from the Luxury Boat Syndicates Team.<br />
                                Your cancellation will not be reflected in your booking <br />
                                calendar until management has reviewed your request and <br />
                                cancelled this in the system.</p>

                                <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif; line-height:25px;"><strong>Importanted Note:</strong> In order for your credit to not be deducted, <br />
                                your cancellation request must be made at least 48 hours prior to booking date</p>

                                <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif; line-height:25px;">If you would like to contact us, please call us on <br />
                                02 8231 6538.</p>
                                </p>';
                $headers = "From: LBS <bookings@luxuryboatsyndicates.com.au>\r\n";

                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                mail($to,$subject,$message,$headers);



                $to_mng = "bookings@luxuryboatsyndicates.com.au";
                $subject_mng = "Cancellation Request";
                $message_mng = '<div style="width:932px; margin: auto; padding:0 25px; font-size:15px;">
                                <a rel="home" title="Luxury Boat" href="http://luxuryboatsyndicates.com.au/">
                                <img src="http://luxuryboatsyndicates.com.au/wp-content/themes/LuxuryBoat/images/logo.jpg"></a>
                                <h1 style=" color: #40B5E5;font-family:sans-serif, Trebuchet MS, Arial, Helvetica;font-size: 25px !important;font-weight: normal;margin: 42px 0;text-transform: uppercase;">Booking Cancellation Details</h1>
                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Dear Admin,</p>
                                <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif;">Below are details on your cancellation request:</p>
                                <p style="margin:0; font-family:Arial, Helvetica, sans-serif;">Syndicate Name: '.$syndicate_name.'</p>
                                <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif;">Requester Name: '.$owner_name." ".$surname .'</p>
                                <p style="font-family:Arial, Helvetica, sans-serif; margin:15px 0;">'.$_POST['spl_request'].'</p>																				
                                <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif; line-height:25px;">Thank you from the Luxury Boat Syndicates Team.<br />
                                Your cancellation will not be reflected in your booking <br />
                                calendar until management has reviewed your request and <br />
                                cancelled this in the system.</p>

                                <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif; line-height:25px;"><strong>Importanted Note:</strong> In order for your credit to not be deducted, <br />
                                your cancellation request must be made at least 48 hours prior to booking date</p>

                                <p style="margin:25px 0; font-family:Arial, Helvetica, sans-serif; line-height:25px;">If you would like to contact us, please call us on <br />
                                02 8231 6538.</p>
                                </p>';
                $header = "From: ".$owner_name." ".$surname ." <".$email.">\r\n";

                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                mail($to_mng,$subject_mng,$message_mng,$header);


                 wp_redirect(home_url('/booking'));  

}

if($type == 'getOwnerColors')
{
    $syndicateName = isset($_REQUEST['syndicateName']) ? $_REQUEST['syndicateName'] : '';
    $query = "select GROUP_CONCAT(owners.color) as color from wp_owner_details as owners 
                join wp_syndicate_details as syndicate on syndicate.id = owners.syndicate_id
                where syndicate.syndicate = '".$syndicateName."'";
    $ownerColorDetails = $wpdb->get_results($query);
    $ownerColorString = '';
	foreach($ownerColorDetails as $ownerColor)
    {
        $ownerColorString = $ownerColor->color;        
	}
    echo $ownerColorString;
}
if($type == 'defect_delete')
{
    //echo "<pre>";print_R($_REQUEST);die;
    $defectId = isset($_REQUEST['defectId']) ? $_REQUEST['defectId'] : 0;
    //echo $defectId;die;
    if($defectId)
    {
        $query = "DELETE FROM wp_report_defect where id = ".$defectId;
        $wpdb->query($query);
        echo 1;   
    }else{
        echo 0;
    }
}
if($type == 'getOwnersDetails')
{
    $syndicateId = isset($_REQUEST['syndicateId']) ? $_REQUEST['syndicateId'] : 0;
    if($syndicateId)
    {
        $ownerQuery = "SELECT user_id, first_name, surname, color 
                FROM wp_owner_details 
                where status = 1 and syndicate_id = ".$syndicateId." and user_id != 0";
        $getOwnerDetails = $wpdb->get_results($ownerQuery);
        $ownerArray = array();
        $k = 0;
        foreach($getOwnerDetails as $ownerData)
        {            
            $ownerArray[$k]['user_id'] = $ownerData->user_id;
            $ownerArray[$k]['syndicate'] = $ownerData->syndicate;
            $ownerArray[$k]['name'] = $ownerData->first_name.' '.$ownerData->surname;
            $ownerArray[$k]['color'] = $ownerData->color;
            $k++;
        }
        echo json_encode($ownerArray);
    }else{
        echo '';
    }    
}

?>