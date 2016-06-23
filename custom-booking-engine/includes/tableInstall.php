<?php

  function InstallTable() {
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    $charset_collate = $wpdb->get_charset_collate();

    // ===========================================================================
    //  Booking Calculations
    // ===========================================================================

    $tbl = $wpdb->prefix . 'booking_calculations';
    if(!$wpdb->get_var("SHOW TABLES LIKE '{$tbl}'")) {
          $sql = "CREATE TABLE $tbl (
          id INT(9) NOT NULL AUTO_INCREMENT,
          date_created DATETIME,
          date_updated DATETIME,
          total_no_of_days INT(10),
          allowed_weekdays INT(50),
          allowed_weekends INT(50),
          booked_weekdays INT(10),
          booked_weekends INT(10),
          weekday_count INT(10),
          weekend_booked TINYINT(1),
          user_id INT(10),
          syndicate_id INT(10),
          last_weekend_booked DATE,
          start_date DATE,
          end_date DATE,
          UNIQUE KEY id (id)
        );";
        dbDelta( $sql );
      }

    // ===========================================================================
    //  Booking Penalties
    // ===========================================================================
    
      $tbl = $wpdb->prefix . 'booking_penalties';
    if(!$wpdb->get_var("SHOW TABLES LIKE '{$tbl}'")) {
          $sql = "CREATE TABLE $tbl (
          id INT(10) NOT NULL AUTO_INCREMENT,
          user_id INT(11) NOT NULL,
          date VARCHAR(255) NOT NULL,
          UNIQUE KEY id (id)
        );";
        dbDelta( $sql );
      }

    // ===========================================================================
    //  Duplicator Packages
    // ===========================================================================
    
      $tbl = $wpdb->prefix . 'duplicator_packages';
    if(!$wpdb->get_var("SHOW TABLES LIKE '{$tbl}'")) {
          $sql = "CREATE TABLE $tbl (
          id BIGINT(20) NOT NULL AUTO_INCREMENT,
          name VARCHAR(250) NOT NULL,
          hash VARCHAR(250) NOT NULL,
          status INT(11) NOT NULL,
          created DATETIME NOT NULL,
          owner VARCHAR(60) NOT NULL,
          package MEDIUMBLOB NOT NULL,
          UNIQUE KEY id (id)
        );";
        dbDelta( $sql );
      }

    // ===========================================================================
    //  Duplicator Packages
    // ===========================================================================
    
      $tbl = $wpdb->prefix . 'duplicator_packages';
    if(!$wpdb->get_var("SHOW TABLES LIKE '{$tbl}'")) {
          $sql = "CREATE TABLE $tbl (
          id BIGINT(20) NOT NULL AUTO_INCREMENT,
          name VARCHAR(250) NOT NULL,
          hash VARCHAR(250) NOT NULL,
          status INT(11) NOT NULL,
          created DATETIME NOT NULL,
          owner VARCHAR(60) NOT NULL,
          package MEDIUMBLOB NOT NULL,
          UNIQUE KEY id (id)
        );";
        dbDelta( $sql );
      }

    // ===========================================================================
    //  GALLERIES
    // ===========================================================================
    
      $tbl = $wpdb->prefix . 'gallery_galleries';
    if(!$wpdb->get_var("SHOW TABLES LIKE '{$tbl}'")) {
          $sql = "CREATE TABLE $tbl (
          id BIGINT(20) NOT NULL AUTO_INCREMENT,
          title VARCHAR(150) NOT NULL,
          created DATETIME NOT NULL,
          modified DATETIME NOT NULL,
          UNIQUE KEY id (id)
        );";
        dbDelta( $sql );
      }



    // ===========================================================================
    //  GALLERIES SLIDES
    // ===========================================================================
    
      $tbl = $wpdb->prefix . 'gallery_galleriesslides';
    if(!$wpdb->get_var("SHOW TABLES LIKE '{$tbl}'")) {
          $sql = "CREATE TABLE $tbl (
          id BIGINT(20) NOT NULL AUTO_INCREMENT,
          gallery_id INT(11) NOT NULL,
          slide_id INT(11) NOT NULL,
          `order` INT(11) NOT NULL,
          created DATETIME NOT NULL,
          modified DATETIME NOT NULL,
          UNIQUE KEY id (id)
        );";
        dbDelta( $sql );
      }

    // ===========================================================================
    //  SLIDES
    // ===========================================================================
    
      $tbl = $wpdb->prefix . 'gallery_slides';
    if(!$wpdb->get_var("SHOW TABLES LIKE '{$tbl}'")) {
          $sql = "CREATE TABLE $tbl (
          id BIGINT(20) NOT NULL AUTO_INCREMENT,
          title VARCHAR(150) NOT NULL,
          description TEXT NOT NULL,
          image VARCHAR(50) NOT NULL,
          `type` ENUM('FILE','URL') NOT NULL,
          image_url VARCHAR(200) NOT NULL,
          uselink ENUM('Y','N') NOT NULL,
          linktarget ENUM('SELF','BLANK') NOT NULL,
          link VARCHAR(200) NOT NULL,
          `order` INT(11) NOT NULL,
          created DATETIME NOT NULL,
          modified DATETIME NOT NULL,
          section INT(5) NOT NULL,
          UNIQUE KEY id (id)
        );";
        dbDelta( $sql );
      }

    // ===========================================================================
    //  OWNER BOOKING
    // ===========================================================================
    
      $tbl = $wpdb->prefix . 'owner_booking';
    if(!$wpdb->get_var("SHOW TABLES LIKE '{$tbl}'")) {
          $sql = "CREATE TABLE $tbl (
          id INT(11) NOT NULL AUTO_INCREMENT,
          user_id INT(11) NOT NULL,
          user_color VARCHAR(100) NOT NULL,
          syndicate_id INT(11) NOT NULL,
          booking_dates VARCHAR(255) NOT NULL,
          estimated_time VARCHAR(100) NOT NULL,
          catering VARCHAR(100) NOT NULL,
          skippering VARCHAR(100) NOT NULL,
          skippering_time VARCHAR(255) NOT NULL,
          spl_request VARCHAR(255) NOT NULL,
          status TINYINT(1),
          session_id VARCHAR(55),
          UNIQUE KEY id (id)
        );";
        dbDelta( $sql );
      }

    // ===========================================================================
    //  OWNER DETAILS
    // ===========================================================================
    
      $tbl = $wpdb->prefix . 'owner_details';
    if(!$wpdb->get_var("SHOW TABLES LIKE '{$tbl}'")) {
          $sql = "CREATE TABLE $tbl (
          id INT(11) NOT NULL AUTO_INCREMENT,
          syndicate VARCHAR(255) NOT NULL,
          first_name VARCHAR(255) NOT NULL,
          surname VARCHAR(255) NOT NULL,
          address TEXT NOT NULL,
          city VARCHAR(255) NOT NULL,
          state VARCHAR(255) NOT NULL,
          pincode VARCHAR(25) NOT NULL,
          country VARCHAR(255) NOT NULL,
          dob VARCHAR(255) NOT NULL,
          sex VARCHAR(255) NOT NULL,
          tel VARCHAR(25) NOT NULL,
          mobile VARCHAR(25) NOT NULL,
          email VARCHAR(255) NOT NULL,
          contact_no VARCHAR(255) NOT NULL,
          color VARCHAR(255) NOT NULL,
          boat_no VARCHAR(255) NOT NULL,
          date_owner VARCHAR(255) NOT NULL,
          name_account VARCHAR(255) NOT NULL,
          bsb VARCHAR(255) NOT NULL,
          account_no VARCHAR(255) NOT NULL,
          cc_card_name VARCHAR(255) NOT NULL,
          cc_card_no VARCHAR(25) NOT NULL,
          cc_exp_date VARCHAR(255) NOT NULL,
          cc_cvv INT(4) NOT NULL,
          cc_type VARCHAR(255) NOT NULL,
          nd_first_name VARCHAR(255) NOT NULL,
          nd_surname VARCHAR(255) NOT NULL,
          nd_tel VARCHAR(25) NOT NULL,
          nd_license VARCHAR(255) NOT NULL,
          syndicate_id INT(11) NOT NULL,
          user_id INT(11) NOT NULL,
          status INT(1),
          user_pass_base64 VARCHAR(64),
          UNIQUE KEY id (id)
        );";
        dbDelta( $sql );
      }

    // ===========================================================================
    //  Report Defect
    // ===========================================================================
    
      $tbl = $wpdb->prefix . 'report_defect';
    if(!$wpdb->get_var("SHOW TABLES LIKE '{$tbl}'")) {
          $sql = "CREATE TABLE $tbl (
          id INT(11) NOT NULL AUTO_INCREMENT,
          user_id INT(11) NOT NULL,
          syndicate_name VARCHAR(255) NOT NULL,
          description TEXT NOT NULL,
          image TEXT NOT NULL,
          create_date TIMESTAMP NOT NULL,
          work_carried_out TEXT NOT NULL,
          status VARCHAR(255) NOT NULL,
          UNIQUE KEY id (id)
        );";
        dbDelta( $sql );
      }

    // ===========================================================================
    //  Syndicate Details
    // ===========================================================================
    
      $tbl = $wpdb->prefix . 'syndicate_details';
    if(!$wpdb->get_var("SHOW TABLES LIKE '{$tbl}'")) {
          $sql = "CREATE TABLE $tbl (
          id INT(11) NOT NULL AUTO_INCREMENT,
          syndicate VARCHAR(255) NOT NULL,
          syndicate_name VARCHAR(255) NOT NULL,
          syndicate_year VARCHAR(10) NOT NULL,
          model VARCHAR(255) NOT NULL,
          cmm_date VARCHAR(100) NOT NULL,
          owners INT(11) NOT NULL,
          notes TEXT NOT NULL,
          status TINYINT(1),
          image TEXT,
          thumbnail_image TEXT,
          UNIQUE KEY id (id)
        );";
        dbDelta( $sql );
      }

  }

?>