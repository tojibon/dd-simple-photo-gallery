<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();


/**
* To remove or drop all tables related to this plugins and settins variables from wordpress options
* 
* @return null
* @access public
*/
function dd_spg_uninstall() {	
	global $wpdb;
	
	
	$sql = 'DROP TABLE IF EXISTS '. $wpdb->prefix.'dd_spg_galleries;';
	$wpdb->query( $sql );
	
	$sql = 'DROP TABLE IF EXISTS '. $wpdb->prefix.'dd_spg_photos;';
	$wpdb->query( $sql );
	
	delete_option('dd_spg_slide_speed');
    delete_option('dd_sfg_db_version');
	delete_option('dd_spg_is_display_title_and_des');
	delete_option('dd_spg_thumb_size');     //widthxheight
    delete_option('dd_spg_large_size');     // in pixel
}


dd_spg_uninstall();
