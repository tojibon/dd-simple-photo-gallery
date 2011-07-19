<?php
/*
Plugin Name: DD Simple Photo Gallery
Plugin URI: http://www.dropndot.com/blog/wordpress/dd-simple-photo-gallery-wordpress-plugin/
Description: DD Simple Photo Gallery is a free, simple, fast and light weight wordpress  plugin to create photo gallery for your wordpress enabled website.
Version: 1.0
Author: Jewel Ahmed
Author URI: http://www.phpfarmer.com
License: GPL2

Copyright 2011 Jewel Ahmed (email : jewel@dropndot.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once('dd_spg_libs.php');


if ( is_admin() ) {
    //If admin user interface/screen
    include_once('admin/dd_spg_admin_settings.php');
} else {
    //If front-end user interface/screen
    
    /* Short code to load DD Simple Photo Gallery plugin.  Detects the word
     * [DDSPG_Gallery] in posts or pages and loads the gallery.
     */
    add_shortcode('DDSPG_Gallery', 'dd_spg_display_gallery'); 
    
    /*
    *
    * Loading plugins required javascript files here for front end
    * */
    add_action('wp_print_scripts', 'enqueue_dd_spg_scripts');
    
    /*
    *
    * Locading plugins required css files here for front-end
    * */
    add_action('wp_print_styles', 'enqueue_dd_spg_styles');
}


        
//Loading dd simple photo gallery required javascript files
function enqueue_dd_spg_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('enqueue_dd_spg_script', BASE_URL . "/js/slide_script.js");
}

//Loading dd simple photo gallery required css files
function enqueue_dd_spg_styles() {
    wp_enqueue_style('enqueue_dd_spg_styles', BASE_URL . "/css/style.css", 100);
}

//Creating dd simple photo gallery front-end html out for post, page or category.
function dd_spg_display_gallery($atts) {
    
    global $wpdb;
    $table_photo = $wpdb->prefix . "dd_spg_photos";
    $table_gallery = $wpdb->prefix . "dd_spg_galleries";
    
    $return_options_data=dd_spg_get_all_options();
    $option_data = (object) $return_options_data;
    $dd_spg_is_display_title_and_des = strtolower($option_data->dd_spg_is_display_title_and_des);
    $dd_spg_slide_speed = strtolower($option_data->dd_spg_slide_speed);
	
	//Initializing thumbnail width height
    $img_thumb_size = explode('x', strtolower(trim($option_data->dd_spg_thumb_size)));  //width x height
    $thumb_width = ($img_thumb_size[0])?$img_thumb_size[0]:120;    //Calculating thumbnail width
    $thumb_height = ($img_thumb_size[1])?$img_thumb_size[1]:100;   //Calculating thumbnail height
     
    //Initializing large width height
    $img_large_size = explode('x', strtolower(trim($option_data->dd_spg_large_size)));  //height x width
    $img_large_width = ($img_large_size[0])?$img_large_size[0]:120;    //Calculating large image width
    $img_large_height = ($img_large_size[1])?$img_large_size[1]:100;   //Calculating large image height
    
    
    
    
    extract( shortcode_atts( array(
        'id' => '0',
    ), $atts ) );
    
    
    if(empty($id)){
        return false;
    }
    
    
    $sql="select * from ".$table_gallery." where id='".$id."'";
    $gallery_data = $wpdb->get_row($sql);
    
    
    
    $sql="select * from ".$table_photo." where gallery_id='".$id."'";
    $photo_data = $wpdb->get_results($sql);
    
    
    if(!empty($gallery_data)){
        
        
        $return_text = '<div id="ddslideshow">';
        $return_text.='<ul class="ddslides">';
            foreach($photo_data as $row){
				$image = '<img src="'.BASE_URL.'/include/resize.php?src='.$row->photo.'&h='.$img_large_height.'&w='.$img_large_width.'&zc=1" alt="'.$row->description.'" title="'.$row->title.'" />';
                $return_text.='<li>'.$image.'</li>';    
            }
        $return_text.='</ul>';
		if($dd_spg_is_display_title_and_des=='yes'){
        $return_text.='<div id="imgCaption"><h4></h4>';
		$return_text.='<p></p></div>';
		?>
		<script language="javascript" type="text/javascript">
			var displayImageInfo = true;	
		</script>
		<?php
		}else{
		?>
		<script language="javascript" type="text/javascript">
			var displayImageInfo = false;	
		</script>
		<?php	
		
		}?>
		<script language="javascript" type="text/javascript">
			var slideSpeed = '<?php echo $dd_spg_slide_speed; ?>';
			var largeImageWidth = '<?php echo $img_large_width; ?>';
			var largeImageHeight = '<?php echo $img_large_height; ?>';
			var thumbImageWidth = '<?php echo $thumb_width; ?>';
			var thumbImageHeight = '<?php echo $thumb_height; ?>';
		</script>
		<?php 
        $return_text.='<span class="arrow previous"></span><span class="arrow next"></span></div>';		
		
		$return_text.= '<div id="thumbshow">';
		$return_text.= '<div id="thumbwrap">';
        $return_text.='<ul class="thumbs">';
            foreach($photo_data as $row){
                $image = '<img src="'.BASE_URL.'/include/resize.php?src='.$row->photo.'&h='.$thumb_height.'&w='.$thumb_width.'&zc=1" alt="'.$row->description.'" title="'.$row->title.'" />';
                $return_text.='<li>'.$image.'</li>';    
            }
        $return_text.='</ul></div>';
       
        $return_text.='<span class="navarrow prevbtn"></span><span class="navarrow nextbtn"></span></div>';
		
    
        return $return_text;
        
        
    } else {
        return false;
    }
    return false;
}    



//Plugins installation process starts here!
register_activation_hook(__FILE__,'dd_spg_install');
register_activation_hook(__FILE__,'dd_spg_install_options');

//creating galleries database table when activating the plugins
function dd_spg_install() {
   global $wpdb;
   $table_galleries = $wpdb->prefix . "dd_spg_galleries";
   $table_photos = $wpdb->prefix . "dd_spg_photos";
   
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_galleries'") != $table_galleries) {
        //Creating database galleries table for dd simple photo gallery    
        $sql = 'CREATE TABLE '.$table_galleries.' (
  id int(11) NOT NULL auto_increment,
  title varchar(120) NOT NULL,
  description text,
  created datetime default NULL,
  updated datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
    
        $wpdb->query($sql);
    }
    
    
    
    
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_photos'") != $table_photos) {
        //Creating database photos table for dd simple photo gallery
        $sql = 'CREATE TABLE '.$table_photos.' (
  id int(11) NOT NULL auto_increment,
  gallery_id int(11) NOT NULL default 0,
  title varchar(120) NOT NULL,
  photo text NOT NULL,
  description text,
  ordering int(11) NOT NULL default 0,
  created datetime default NULL,
  updated datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;';
        $wpdb->query($sql);
        
    }
}


//adding plugins required all settings options here when activating the plugins
function dd_spg_install_options(){
    // add_option( $name, $value, $deprecated, $autoload );
    if(!get_option('dd_spg_slide_speed'))
        add_option('dd_spg_slide_speed', '500', '', 'no');       // image thumbnail sliding speed in milliseconds 
    
    if(!get_option('dd_spg_is_display_title_and_des'))
        add_option('dd_spg_is_display_title_and_des', 'yes', '', 'no');       // image information display or not settings 
    
    if(!get_option('dd_spg_thumb_size'))
        add_option('dd_spg_thumb_size', '50x50', '', 'no');     // in pixel width x height
    
    if(!get_option('dd_spg_large_size'))
        add_option('dd_spg_large_size', '550x250', '', 'no');   // in pixel width x height
    
    if(!get_option('dd_sfg_db_version'))
        add_option("dd_sfg_db_version", VERSION, '', 'no');     // plugins database verions 1.0
}