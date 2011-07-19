<?php
add_action('admin_init', 'dd_spg_admin_init');
add_action('admin_menu', 'dd_spg_admin_menu');
add_action('admin_head', 'enqueue_dd_spg_admin_styles');

/**
* Loading stylesheet file for plugins admin interface to fixing the pagination styles
* 
* @return void
* @access public
*/    
function enqueue_dd_spg_admin_styles(){
    //wp_enqueue_style('dd_spg_admin_styles_register_name', BASE_URL . '/admin/style.css');
    $admin_style_url = BASE_URL . '/admin/css/style.css';
    echo '<link rel="stylesheet" href="'.$admin_style_url.'" type="text/css" media="screen" />';
}

/**
* Registering all options for plguins settins 
* 
* @return void
* @access public
*/
function dd_spg_admin_init() {
    //register_setting( 'myplugin', 'myplugin_setting_1', 'intval' );
    register_setting( 'dd_spg_settings', 'dd_spg_db_version');
    register_setting( 'dd_spg_settings', 'dd_spg_slide_speed');
    register_setting( 'dd_spg_settings', 'dd_spg_is_display_title_and_des');
    register_setting( 'dd_spg_settings', 'dd_spg_thumb_size');
    register_setting( 'dd_spg_settings', 'dd_spg_large_size');
}

/**
* Generating admin menu for plugins
* 
* @return void
* @access public
*/
function dd_spg_admin_menu() {
    
    add_menu_page( $page_title='Manage Gallery', $menu_title='Galleries', $capability='publish_posts', $menu_slug='dd_spg_manage_gallery', $function='dd_spg_manage_gallery', $icon_url=BASE_URL . "/admin/images/dd_spg_logo.png");
    
    add_submenu_page( $parent_slug='dd_spg_manage_gallery', $page_title='Manage Photos | DD Simple Photo Gallery', $menu_title='Manage Photos', $capability='publish_posts', $menu_slug='dd_spg_manage_photo', $function='dd_spg_manage_photo');
    
    add_submenu_page( $parent_slug='dd_spg_manage_gallery', $page_title='Default Settings | DD Simple Photo Gallery', $menu_title='Default Settings', $capability='manage_options', $menu_slug='dd_spg_settings', $function='dd_spg_settings');
    
}

/**
* Loading markup for plugins admin settings interface
* 
* @return void
* @access public
*/
function dd_spg_settings() {
    if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    
    include('dd_spg_settings.php');
}

/**
* Loading markup for plugins admin manage gallery page
* 
* @return void
* @access public
*/
function dd_spg_manage_gallery() {
    if (!current_user_can('publish_posts'))  {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    global $wpdb;
    $table_name = $wpdb->prefix . "dd_spg_galleries";
   
    include('dd_spg_manage_gallery.php');    
}

/**
* Loading markup for plugins admin manage photo page
* 
* @return void
* @access public
*/
function dd_spg_manage_photo() {
    if (!current_user_can('publish_posts'))  {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    global $wpdb;
    $table_photo = $wpdb->prefix . "dd_spg_photos";
    $table_gallery = $wpdb->prefix . "dd_spg_galleries";
    
    include('dd_spg_manage_photo.php');    
}