<?php

/**
* public constant for plugins base url
*
* @var string url
* @access public
*/
define('BASE_URL', plugins_url() . '/' . basename(dirname(__FILE__)));

/**
* public constant for plugins name
*
* @var string 
* @access public
*/
define('PLUGINS_NAME', basename(dirname(__FILE__)));

/**
* public constant for website url which come from wordpress site url option settings
*
* @var string url
* @access public
*/
define('SITE_URL', get_option('siteurl'));

/**
* public constant version number for plugins 
*
* @var float
* @access public
*/
define('VERSION', '1.0');

/**
* public constant for plugins details or support url
*
* @var string url
* @access public
*/
define('PLUGINS_WEBSITE', 'http://www.dropndot.com/blog/wordpress/dd-simple-photo-gallery-wordpress-plugin/');

/**
* To generate Information display box on admin interface
* 
* @param title string Information box title
* @param message string Information content
* @return string
* @access public
*/
function dd_spg_box($title, $message) {
     return "
        <div id=\"poststuff\">
        <div class=\"postbox\">
        <h3>$title</h3>
        <table class='form-table'>
        <td>$message</td>
        </table>
        </div></div>
        ";
}

/**
* To generate plugins information menu on admin interface
* 
* @return string
* @access public
*/
function dd_spg_version_line(){
    return "" .
    " <h4 align=\"right\" style=\"margin-right:0.5%\">" .
       " &nbsp;Version: <b>" . VERSION . "</b> |" .
        " <a href=\"".PLUGINS_WEBSITE."\">Support</a> |" .
        " <a href=\"http://wordpress.org/extend/plugins/dd-simple-photo-gallery-plugin/faq/\">FAQ</a> |" .
        " <a href=\"http://wordpress.org/extend/plugins/dd-simple-photo-gallery-plugin/\">Rate this plugin</a> |" .
        " <a href=\"http://wordpress.org/extend/plugins/dd-simple-photo-gallery-plugin/changelog/\">Changelog</a> |" .
        " <a href=\"http://www.phpfarmer.com/\">Live Demo</a>" .
    " </h4>";
}

/**
* To generate markup for showing current default settings preview on admin interface
* 
* @return string
* @access public
*/
function dd_spg_current_settings_box() {
    $message = "Display image information - <b>" . get_option('dd_spg_is_display_title_and_des') . "</b>";
    $message .= "<br />Size of Photos - <b>" . get_option('dd_spg_thumb_size') . "</b>";
    $message .= "<br />Photo Maximum Size - <b>" . get_option('dd_spg_large_size') . "</b>";
	$message .= "<br />Slider Speed - <b>" . get_option('dd_spg_slide_speed') . " milliseconds</b>";
    return dd_spg_box('Default Settings for Preview', $message);
}

/**
* Date modifier helper function
* 
* @param str string mysql formatted date string
* @param full boolean is the return date include the time also
* @return string
* @access public
*/
function shorten_mysql_date( $str, $full=true ) {

            if(empty($str))
            return false;
            
            $date_time = explode(' ', $str);
            $day = explode('-', $date_time[0]);
            
            if(!empty($date_time[1]))
            $time = explode(':', $date_time[1]);
            
            $today = date('M j, Y');
            $yesterday = date('M j, Y', mktime(0,0,0, date("m")  , date("d")-1, date("Y")));
            $entry_day = date('M j, Y', mktime(0,0,0,$day[1],$day[2],$day[0])); 
            if($full)
                $entry_day = date('M j, Y H:i:s a', mktime($time[0],$time[1],$time[2],$day[1],$day[2],$day[0])); 
                
            if ($entry_day==$today) {
                $entry_day = 'Today';
            } elseif ($entry_day==$yesterday) {
                $entry_day = 'Yesterday';
            }   

            return $entry_day;
}    

/**
* Data grids data pagination function on admin interface
* 
* @param sql string mysql query to generate pagination array with data and links
* @param limit int numbers of listing to be return
* @param path string default current page url string
* @return array('data', 'link')
* @access public
*/
function paging($sql,$limit,$path) { 
    global $wpdb;
    $pagination_return_data = array();
    
    
    $result_data = $wpdb->get_results($sql);
    $total_pages = count($result_data);
    
    $adjacents = "2";
    $page = (int) (!isset($_REQUEST["paging"]) ? 1 : $_REQUEST["paging"]);
    $page = ($page == 0 ? 1 : $page);

    if($page)
    $start = ($page - 1) * $limit;
    else
    $start = 0;

    $sql = $sql ."  LIMIT $start, $limit";
    $return_data = $wpdb->get_results($sql);
    $pagination_return_data['data']=$return_data;

    
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total_pages/$limit);
    $lpm1 = $lastpage - 1;

    $pagination = "";
    if($lastpage > 1)
    {   
    $pagination .= "<div class='pagination'>";
    if ($page > 1)
        $pagination.= "<a href='".$path."paging=$prev'><< previous</a>";
    else
        $pagination.= "<span class='disabled'><< previous</span>";   
    
        if ($lastpage < 7 + ($adjacents * 2))
        {   
            for ($counter = 1; $counter <= $lastpage; $counter++)
            {
                if ($counter == $page)
                    $pagination.= "<span class='current'>$counter</span>";
                else
                    $pagination.= "<a href='".$path."paging=$counter'>$counter</a>";     
                         
            }
        }elseif($lastpage > 5 + ($adjacents * 2)){
        if($page < 1 + ($adjacents * 2)){
            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
            if ($counter == $page)
                $pagination.= "<span class='current'>$counter</span>";
            else
                $pagination.= "<a href='".$path."paging=$counter'>$counter</a>";     
                         
            }
            $pagination.= "...";
            $pagination.= "<a href='".$path."paging=$lpm1'>$lpm1</a>";
            $pagination.= "<a href='".$path."paging=$lastpage'>$lastpage</a>";   
           
        }elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
            $pagination.= "<a href='".$path."paging=1'>1</a>";
            $pagination.= "<a href='".$path."paging=2'>2</a>";
            $pagination.= "...";
        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
        {
        if ($counter == $page)
            $pagination.= "<span class='current'>$counter</span>";
        else
            $pagination.= "<a href='".$path."paging=$counter'>$counter</a>";     
                     
        }
            $pagination.= "..";
            $pagination.= "<a href='".$path."paging=$lpm1'>$lpm1</a>";
            $pagination.= "<a href='".$path."paging=$lastpage'>$lastpage</a>";   
           
        }else{
            $pagination.= "<a href='".$path."paging=1'>1</a>";
            $pagination.= "<a href='".$path."paging=2'>2</a>";
            $pagination.= "..";
        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
        {
        if ($counter == $page)
            $pagination.= "<span class='current'>$counter</span>";
        else
            $pagination.= "<a href='".$path."paging=$counter'>$counter</a>";     
                     
        }
        }
    }
    
    if ($page < $counter - 1)
        $pagination.= "<a href='".$path."paging=$next'>next >></a>";
    else
        $pagination.= "<span class='disabled'>next >></span>";
        
        
        $pagination.= "</div>";       
    }
    
    $pagination_return_data['link']=$pagination;
    return $pagination_return_data;
}     

/**
* Validating plugins allowed file types when uploading photos or files
* 
* @param file_name string currently uploading file name field
* @param allrowed_types array allowed file extensions array list
* @return boolean
* @access public if allowed then true else false
*/
function _allowed_file_type($file_name, $allowed_types=array()){
    foreach($allowed_types as $key=>$value){
        $pos = strpos(strtolower($file_name), strtolower($value));
        if ($pos === false) {
            $return = false;
        } else {
            $return = true;
            break;
        }
    }
    return $return;
}

/**
* Getting plugins settings all options in a array 
* 
* @return array
* @access public
*/
function dd_spg_get_all_options() {
    return array(
        'dd_spg_db_version' => get_option('dd_spg_db_version'),
        'dd_spg_is_display_title_and_des' => get_option('dd_spg_is_display_title_and_des'),
        'dd_spg_thumb_size' => get_option('dd_spg_thumb_size'),
        'dd_spg_large_size' => get_option('dd_spg_large_size'),
		'dd_spg_slide_speed' => get_option('dd_spg_slide_speed'),
    );
}
