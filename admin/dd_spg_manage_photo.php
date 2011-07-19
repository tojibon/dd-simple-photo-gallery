<?php

    $url=$_SERVER['REQUEST_URI'];
    $page='dd_spg_manage_photo'; //Used to create this page link like back, no thanks, edit and delte
    
    $allowed_file_types = array('.jpg','.gif','.png');
    $max_file_size = 5000000;
     
    
    $sql="select id,title from ".$table_gallery.' ORDER by title';
    $gallery_results=$wpdb->get_results($sql); 
            
    //Photo Gallery edit 
    if(!empty($_REQUEST['action']) && 'edit'==$_REQUEST['action']){
        if(!empty($_REQUEST['id'])){
            
            //Validation starts here
            $err='';
            
            $file_field='photo';
            if(!empty($_FILES[$file_field]) && !empty($_FILES[$file_field]['name'])){
                if($_FILES[$file_field]["error"]){
                    $err ='File upload error: ' . $_FILES[$file_field]["error"];
                } elseif(!_allowed_file_type($_FILES[$file_field]["name"],$allowed_file_types)){
                    $err ='Invalid file type. only allowed: '.implode(', ',$allowed_file_types);
                } elseif($_FILES[$file_field]["size"] > $max_file_size){
                    $err ='Very Large File. Please try a smaller file';
                }    
            }
            
            if(!empty($_POST['submit'])){
                if(empty($_POST['title'])){
                    $err = 'Photo title field can not be blank.';
                }elseif(empty($_POST['gallery_id'])){
                    $err = 'Please select a photo gallery. Photo gallery field can not be blank.';
                }  
            }
            
            
            
            if(!empty($_POST['submit']) && empty($err)){
                if(!empty($_FILES[$file_field]) && !empty($_FILES[$file_field]['name'])){
                    $upload = wp_upload_bits($_FILES[$file_field]["name"], null, @file_get_contents($_FILES[$file_field]["tmp_name"]));
                    $sql='update '.$table_photo.' 
                        SET 
                        gallery_id="'.$_POST['gallery_id'].'",
                        title="'.strip_tags($_POST['title']).'",
                        photo="'.$upload['url'].'",
                        description="'.strip_tags($_POST['description']).'",
                        ordering="'.$_POST['ordering'].'",
                        updated=concat(CURDATE()," ",CURTIME())
                        where id='.$_REQUEST['id'];
                } else {
                    $sql='update '.$table_photo.' 
                        SET 
                        gallery_id="'.$_POST['gallery_id'].'",
                        title="'.strip_tags($_POST['title']).'",
                        description="'.strip_tags($_POST['description']).'",
                        ordering="'.$_POST['ordering'].'",
                        updated=concat(CURDATE()," ",CURTIME())
                        where id='.$_REQUEST['id'];
                }
                
                
                
                if($wpdb->query($sql)){
                    $msg ='Photo information has been updated successfully!';
                } else {
                    $err ='Sorry, Can not update photo information.';
                }
            }
            
            
            
            $sql="select * from ".$table_photo." where id='".$_GET['id']."'";
            $old=$wpdb->get_row($sql);
        }
        
        $action = 'edit';
    } else {
        $action = 'add';
    }
    
    //Photo Gallery new add
    if(!empty($_REQUEST['action']) && 'add'==$_REQUEST['action']){
            $err='';
            $file_field='photo';
            if($_FILES[$file_field]["error"]){
                $err ='File upload error no: ' . $_FILES[$file_field]["error"];
            } elseif(!_allowed_file_type($_FILES[$file_field]["name"],$allowed_file_types)){
                $err ='Invalid file type. only allowed: '.implode(', ',$allowed_file_types);
            } elseif($_FILES[$file_field]["size"] > $max_file_size){
                $err ='Very Large File. Please try a smaller file';
            }
            
            if(!empty($_POST['submit'])){
                if(empty($_POST['title'])){
                    $err = 'Photo title field can not be blank.';
                }elseif(empty($_POST['gallery_id'])){
                    $err = 'Please select a photo gallery. Photo gallery field can not be blank.';
                } 
            }
            
            
            
            
            if(!empty($_POST['submit']) && empty($err)){
                $upload = wp_upload_bits($_FILES[$file_field]["name"], null, @file_get_contents($_FILES[$file_field]["tmp_name"])); 
                
                
                $sql='INSERT INTO '.$table_photo.' 
                set 
                gallery_id="'.$_POST['gallery_id'].'",
                title="'.strip_tags($_POST['title']).'",
                photo="'.$upload['url'].'",
                description="'.strip_tags($_POST['description']).'",
                ordering="'.$_POST['ordering'].'",
                created=concat(CURDATE()," ",CURTIME()),
                updated=concat(CURDATE()," ",CURTIME())
                ';
                if($wpdb->query($sql)){
                    $msg ='Photo information has been saved successfully!';
                } else {
                    $err ='Sorry, Can not add new photo information.';
                }
            } else {
                //Creating old objects for each data field
                $old = (object) $_POST;
            }
    }
    
    //Photo Item delete
    if(!empty($_REQUEST['action']) && 'delete'==$_REQUEST['action']){
        if(!empty($_REQUEST['id'])){
            if(!empty($_POST['submit'])){
                $sql="select photo from ".$table_photo." where id='".$_REQUEST['id']."'";
                $data=$wpdb->get_row($sql);
                
                $sql="delete from ".$table_photo." where id='".$_REQUEST['id']."'";
                if($wpdb->query($sql)){
                    $msg ='Photo has been deleted successfully!';
                    @unlink($data->photo);  //Deleting photo from directory
                } else {
                    $err ='Photo Can\'t be deleted right now!';
                }
            } else {
                //Displaying confirm delete screen!
                $sql="select * from ".$table_photo." where id='".$_GET['id']."'";
                $old_to_delete=$wpdb->get_row($sql);
                $action = 'delete';
            }
        }
    } 
    
    
    
    
 

    
?>
<div class='wrap'>
<h2><a href='<?=PLUGINS_WEBSITE?>'><img src="<?php
echo (BASE_URL . '/admin/images/logo_big.png'); ?>" align='center'/></a>Manage Photos | DD Simple Photo Gallery</h2>


<?php if (!empty($msg)) { ?>
    <div class="updated fade"><p><strong><?=$msg?></strong></p></div>
<?php } ?>

<?php if (!empty($err)) { ?>
    <div class="error fade"><p><strong><?=$err?></strong></p></div>
<?php } ?>





<?php echo dd_spg_version_line(); ?>
<div class="postbox-container" style="width:70%;">
<div id="poststuff">

<?php
    if(!empty($old_to_delete)){
        ?>
        <form method='post' action='<?php echo $url ?>'>
        <div class="postbox">
            <h3>Delete <?=$old_to_delete->title?>?</h3>
            <table class='form-table'>
                <tr valign='top'>
                <td scope='row'>Are you sure, you want to delete this data?</td>
                </tr>        
                
                </tr>
            </table>
        </div>

        <input type="submit" class="button-primary" name="submit" value="Delete <?=$old_to_delete->title?>" />
        <input class="hidden" type="hidden" value="<?=$action?>" name="action" />&nbsp;<a href="?page=<?=$page?>">No Thanks</a>
        </form>
        <?php 
    } else {
        ?>
        <form method='post' action='<?php echo $url ?>' enctype="multipart/form-data">
        <div class="postbox">
            <h3><?php if($old) echo 'Edit'; else echo 'Add'; ?> Photo</h3>
            <table class='form-table'>
                <tr valign='top'>
                <th scope='row'>Gallery</th>
                <td>
                <select name="gallery_id">
                    <option value="">Select Gallery...</option>
                    <?php 
                        foreach($gallery_results as $row){
                            if(!empty($old->gallery_id) && $old->gallery_id==$row->id){
                                $selected = ' selected="selected"';
                            } elseif(!empty($_REQUEST['gallery_id']) && $_REQUEST['gallery_id']==$row->id) {
                                $selected = ' selected="selected"';
                            } else {
                                $selected = '';
                            }
                    ?>
                        <option value="<?=$row->id?>" <?=$selected?>><?=$row->title?></option>
                    <?php 
                        }
                    ?>
                </select><font size='3' color='red'>*</font>
                </td>
                </tr>        
                <tr valign='top'>
                <th scope='row'>Photo Title</th>
                <td><input maxlength='30' type='text' id='title' name='title' value="<?=$old->title?>" /><font size='3' color='red'>*</font></td>
                </tr>        
                <tr valign='top'>
                <th scope='row'>Photo</th>
                <td><input maxlength='30' type='file' id='photo' name='photo' value="<?=$old->photo?>" /><font size='3' color='red'>*</font>
                
                <?php 
                if(!empty($old->photo)){
                ?>
                    <br />
                    <img src="<?=BASE_URL?>/include/resize.php?src=<?=$old->photo?>&h=100&w=100&zc=1" />
                <?php 
                }
                ?>
                
                </td>
                </tr>        
                <tr valign='top'>
                <th scope='row'>Photo Description</th>
                <td>
                <textarea cols="60" rows="6" id="description" name="description"><?=$old->description?></textarea>        
                </td>
                </tr>
                <tr valign='top'>
                <th scope='row'>Ordering</th>
                <td><input maxlength='30' type='text' id='ordering' name='ordering' value="<?=$old->ordering?>" /></td>
                </tr>        
                
            </table>
        </div>

        


        <input type="submit" class="button-primary" name="submit" value="Save Gallery" />
        <?php
            if($old){
        ?>
        &nbsp;<a href="?page=<?=$page?>">Cancel</a>
        <?php
            } 
        ?>
        
        <input class="hidden" type="hidden" value="<?=$action?>" name="action" />
        </form>
        <?php 
    }
?>

<br />

<form method='post' action='<?php echo $url ?>'>

<div class="postbox">
    <h3>Saved Photos</h3>
    <table cellspacing="0" class="widefat post fixed">
            <thead>
                <tr>
                    <th style="" class="manage-column" scope="col">#Id</th>
                    <th style="" class="manage-column" scope="col">Title</th>
                    <th style="" class="manage-column" scope="col">Photo</th>
					<th style="" class="manage-column" scope="col">Ordering</th>
                    <th style="" class="manage-column" scope="col">Gallery</th>
                    <th style="" class="manage-column" scope="col">Updated Time</th>
                </tr>
            </thead>
            <?php
                /*$sql = "SELECT * FROM  ".$table_photo." ORDER BY ordering";*/
                
                if(!empty($_REQUEST['gallery_id'])){
                    $sql = "SELECT ".$table_photo.".*, ".$table_gallery.".title as gallery FROM ".$table_photo." LEFT JOIN ".$table_gallery."  ON ".$table_photo.".gallery_id = ".$table_gallery.".id WHERE ".$table_photo.".gallery_id='".$_REQUEST['gallery_id']."' order by ".$table_photo.".title asc";
                    $paging_data = paging($sql, 10, '?page='.$page.'&gallery_id='.$_REQUEST['gallery_id'].'&');
                } else {
                    $sql = "SELECT ".$table_photo.".*, ".$table_gallery.".title as gallery FROM ".$table_photo." LEFT JOIN ".$table_gallery."  ON ".$table_photo.".gallery_id = ".$table_gallery.".id order by ".$table_photo.".title asc";    
                    $paging_data = paging($sql, 10, '?page='.$page.'&');
                }
                
            ?>
            <tfoot>
                <tr>
                    <th class="manage-column" colspan="6"><?=$paging_data['link']?></th>
                </tr>
            </tfoot>
            <tbody>
            <?php
            $i=0;
            foreach($paging_data['data'] as $row){
                $i++;
            ?>
                <tr valign="middle">
                    <td class="post-title column-title" style="vertical-align: middle;">
                    <strong>
                        <?=$row->id?></strong>
                        <div class="row-actions">
                            <span class="edit">
                                <a href="?page=<?=$page?>&action=<?php echo 'edit&amp;id' ?>=<?=$row->id?>">Edit</a> | 
                            </span>
                            
                            <span class="trash"><a href="?page=<?=$page?>&action=delete&id=<?=$row->id?>">Delete</a></span>
                       </div>
                    </td>
                    <td style="vertical-align: middle;"><?=$row->title?></td>
                    <td style="vertical-align: middle;">
                    <?php 
                    if(!empty($row->photo)){
                    ?>
                        <img src="<?=BASE_URL?>/include/resize.php?src=<?=$row->photo?>&h=50&w=50&zc=1" />
                    <?php 
                    }
                    ?></td>
		             <td style="vertical-align: middle;"><?=$row->ordering?></td>
					
                    <td style="vertical-align: middle;"><a href="?page=<?=$page?>&gallery_id=<?=$row->gallery_id?>"><?=$row->gallery?></a></td>
                    <td style="vertical-align: middle;"><?=shorten_mysql_date($row->updated)?></td>
            <?php
            }
            ?>   
            
            
            <?php 
            if($i<1){   //No previous saved entry found!
                ?>
                <tr>
                    <td class="post-title column-title" colspan="6" style="vertical-align: middle; text-align: center; padding: 30px 5px;">
                        No previous saved photo found. 
                    </td>
                </tr>
                <?php 
            }
            ?>
             
            </tbody>
        </table>
</div></div>
</form>


</div>





<div class="postbox-container" style="width: 29%;">
<?php
echo dd_spg_current_settings_box();
echo dd_spg_box('Upload Instructions', 'Please see the above photos gallery settings and upload according the settings of photo gallery size in pixel.');
 ?>
</div>
