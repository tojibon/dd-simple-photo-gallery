<?php


    $url=$_SERVER['REQUEST_URI'];
    $page='dd_spg_manage_photo'; //Used to create this page link like back, no thanks, edit and delte
    
    if(!empty($_POST['submit'])){
		update_option('dd_spg_slide_speed', $_POST['dd_spg_slide_speed']);
        update_option('dd_spg_is_display_title_and_des', $_POST['dd_spg_is_display_title_and_des']);
        update_option('dd_spg_thumb_size', $_POST['dd_spg_thumb_size']);
        update_option('dd_spg_large_size', $_POST['dd_spg_large_size']);
        
        $msg = 'Your gallery settings has been updated successfully.';
    }
    
    $return_options_data=dd_spg_get_all_options();
    $data = (object) $return_options_data;
	
?>
<div class='wrap'>
<h2><a href='<?=PLUGINS_WEBSITE?>'><img src="<?php
echo (BASE_URL . '/admin/images/logo_big.png'); ?>" align='center'/></a>Manage Settings | DD Simple Photo Gallery</h2>


<?php if (!empty($msg)) { ?>
    <div class="updated fade"><p><strong><?=$msg?></strong></p></div>
<?php } ?>

<?php if (!empty($err)) { ?>
    <div class="error fade"><p><strong><?=$err?></strong></p></div>
<?php } ?>





<?php echo dd_spg_version_line(); ?>
<div class="postbox-container" style="width:70%;">
<div id="poststuff">
    <form method='post' action='<?php echo $url ?>'>
    <div class="postbox">
        <h3>Default Settings</h3>
        
        <table class='form-table'>
            <tr valign='top'>
            <th scope='row'>Display image information </th>
            <td>
			
			<?php 
			
			if($data->dd_spg_is_display_title_and_des=='yes'){ 
				$yes = ' checked="checked"';
				$no = '';
			} else {
				$no = ' checked="checked"';				
				$yes = '';
			}
			
			?>
			
			<input type='radio'  name='dd_spg_is_display_title_and_des' <?=$yes?> value="yes" />&nbsp; Yes
			
			<input type='radio' name='dd_spg_is_display_title_and_des' <?=$no?> value="no" />&nbsp;&nbsp;&nbsp;No
			
			</td>
            </tr>
            <tr valign='top'>
            <th scope='row'>Thumb Photo Size</th>
            <td><input maxlength='100' size='50%' type='text' id='dd_spg_thumb_size' name='dd_spg_thumb_size' value="<?=$data->dd_spg_thumb_size?>" /></td>
            </tr>
            
            <tr valign='top'>
            <th scope='row'>Large Photo Size</th>
            <td><input maxlength='100' size='50%' type='text' id='dd_spg_large_size' name='dd_spg_large_size' value="<?=$data->dd_spg_large_size?>" /></td>
            </tr>
			
			
            <tr valign='top'>
            <th scope='row'>Slider Speed in milliseconds</th>
            <td><input maxlength='100' size='50%' type='text' id='dd_spg_slide_speed' name='dd_spg_slide_speed' value="<?=$data->dd_spg_slide_speed?>" /></td>
            </tr>
        </table>
        
       
    </div>
    <input type="submit" id="submit" class="button-primary" name="submit" value="Save Changes" />
     </form>
</div>
</div>




<div class="postbox-container" style="width: 29%;">
<?php
echo dd_spg_current_settings_box();
echo dd_spg_box('Update Instructions', 'Please set all size in pixel.<br /><b>Example:</b><br />Thumb size: 80x80 as width x height (In Pixel)<br />Large Image size: 450x320 as width x height (In Pixel)');
 ?>
</div>