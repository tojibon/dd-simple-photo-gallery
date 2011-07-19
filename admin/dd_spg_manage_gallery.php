<?php


    $url=$_SERVER['REQUEST_URI'];
    $page='dd_spg_manage_gallery';  //Used to create this page link like back, no thanks, edit and delte
    $photo_page='dd_spg_manage_photo'; //Used to create this page link like back, no thanks, edit and delte
    
    //Photo Gallery edit 
    if(!empty($_REQUEST['action']) && 'edit'==$_REQUEST['action']){
        if(!empty($_REQUEST['id'])){
            
            //Validation starts here
            $err='';
            if(!empty($_POST['submit'])){
                if(empty($_POST['title'])){
                    $err = 'Gallery title field can not be blank.';
                } elseif(empty($_POST['description'])){
                    $err = 'Gallery description field can not be blank.';
                }
            }
            
            
            if(!empty($_POST['submit']) && empty($err)){
                $sql='update '.$table_name.' 
                set 
                title="'.strip_tags($_POST['title']).'",
                description="'.strip_tags($_POST['description']).'",
                updated=concat(CURDATE()," ",CURTIME())
                 
                where id='.$_REQUEST['id'];
                if($wpdb->query($sql)){
                    $msg ='Gallery information has been updated successfully!';
                } else {
                    $err ='Sorry, Can not update gallery information.';
                }
            }
            
            
            
            $sql="select * from ".$table_name." where id='".$_GET['id']."'";
            $old=$wpdb->get_row($sql);
        }
        
        $action = 'edit';
    } else {
        $action = 'add';
    }
    
    //Photo Gallery new add
    if(!empty($_REQUEST['action']) && 'add'==$_REQUEST['action']){
            $err='';
            if(!empty($_POST['submit'])){
                if(empty($_POST['title'])){
                    $err = 'Gallery title field can not be blank.';
                } elseif(empty($_POST['description'])){
                    $err = 'Gallery description field can not be blank.';
                }
            }
            
            if(!empty($_POST['submit']) && empty($err)){
                $sql='INSERT INTO '.$table_name.' 
                set 
                title="'.strip_tags($_POST['title']).'",
                description="'.strip_tags($_POST['description']).'",
                created=concat(CURDATE()," ",CURTIME()),
                updated=concat(CURDATE()," ",CURTIME())
                ';
                if($wpdb->query($sql)){
                    $msg ='Gallery information has been saved successfully!';
                } else {
                    $err ='Sorry, Can not add new gallery information.';
                }
            } else {
                //Creating old objects for each data field
                $old = (object) $_POST;
            }
    }
    
    //Photo Gallery delete
    if(!empty($_REQUEST['action']) && 'delete'==$_REQUEST['action']){
        if(!empty($_REQUEST['id'])){
            if(!empty($_POST['submit'])){
                $sql="delete from ".$table_name." where id='".$_GET['id']."'";
                if($wpdb->query($sql)){
                    $msg ='Gallery has been deleted successfully!';
                } else {
                    $err ='Gallery Can\'t be deleted right now!';
                }
            } else {
                //Displaying confirm delete screen!
                $sql="select * from ".$table_name." where id='".$_GET['id']."'";
                $old_to_delete=$wpdb->get_row($sql);
                $action = 'delete';
            }
        }
    } 
    
    
    
    
 

    
?>
<div class='wrap'>
<h2><a href='<?=PLUGINS_WEBSITE?>'><img src="<?php
echo (BASE_URL . '/admin/images/logo_big.png'); ?>" align='center'/></a>Manage Galleries | DD Simple Photo Gallery</h2>


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
        <form method='post' action='<?php echo $url ?>'>
        <div class="postbox">
            <h3><?php if($old) echo 'Edit'; else echo 'Add'; ?> Gallery</h3>
            <table class='form-table'>
                <tr valign='top'>
                <th scope='row'>Gallery Name</th>
                <td><input maxlength='30' type='text' id='title' name='title' value="<?=$old->title?>" /><font size='3' color='red'>*</font></td>
                </tr>        
                <tr valign='top'>
                <th scope='row'>Gallery Description</th>
                <td style="vertical-align: top;" valign="top">
                <textarea cols="60" rows="6" id="description" name="description" style="float: left;"><?=$old->description?></textarea><font size='3' color='red'>*</font>        
                </td>
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
    <h3>Saved Galleries</h3>
    <table cellspacing="0" class="widefat post fixed">
            <thead>
                <tr>
                    <th style="" class="manage-column" scope="col">#Id</th>
                    <th style="" class="manage-column" scope="col">Gallery Title</th>
                    <th style="" class="manage-column" scope="col">Gallery Code</th>
                    <th style="" class="manage-column" scope="col">Updated Time</th>
                </tr>
            </thead>
            <?php
                $sql = "SELECT * FROM  ".$table_name." ORDER BY  title";
                $paging_data = paging($sql, 10, '?page='.$page.'&');
            ?>
            <tfoot>
                <tr>
                    <th class="manage-column" colspan="4"><?=$paging_data['link']?></th>
                </tr>
            </tfoot>
            <tbody>
            <?php
            $i = 0;
            foreach($paging_data['data'] as $row){
                $i++;
            ?>
                <tr valign="top">
                    <td class="post-title column-title">
                    <strong>
                        <?=$row->id?></strong>
                        <div class="row-actions">
                            <span class="edit">
                                <a href="?page=<?=$page?>&action=<?php echo 'edit&amp;id' ?>=<?=$row->id?>">Edit</a> | 
                            </span>
                            
                            <span class="trash"><a href="?page=<?=$page?>&action=delete&id=<?=$row->id?>">Delete</a></span>
                       </div>
                    </td>
                    <td><a href="?page=<?=$photo_page?>&gallery_id=<?=$row->id?>"><?=$row->title?></a></td>
                    <td>[DDSPG_Gallery id="<?=$row->id?>"]</td>
                    <td><?=shorten_mysql_date($row->updated)?></td>
            <?php
            }
            ?>    
            
            <?php 
            if($i<1){   //No previous saved entry found!
                ?>
                <tr>
                    <td class="post-title column-title" colspan="4" style="vertical-align: middle; text-align: center; padding: 30px 5px;">
                        No previous saved photo gallery found. 
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
echo dd_spg_box('Usage Instructions', 'Insert the Gallery Code in any of your posts or pages to display your Custom Gallery.');
 ?>
</div>
