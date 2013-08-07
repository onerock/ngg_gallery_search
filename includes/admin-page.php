<?php

global $wpdb, $ngg;
	
	function add_ngs2_menu()  {
	    add_submenu_page( NGGFOLDER, 'NextGen Gallery Search 2', 'Gallery Search', 8, __FILE__, 'ngs2_search_page');
	}

add_action('admin_menu', 'add_ngs2_menu');

function ngs2_search_page() {
		global $wpdb, $ngg;
		
		if(file_exists(WP_PLUGIN_DIR.'/'.NGGFOLDER.'/admin/functions.php')) {
			require(WP_PLUGIN_DIR.'/'.NGGFOLDER.'/admin/functions.php');
		
		?>
		<div class="wrap">
			<h2><?php _e('NextGEN Gallery Search', 'nggallery') ?></h2>
			<br style="clear: both;"/>

			<form method="post" action="<?=$_SERVER['REQUEST_URI']?>"> 
			
			<table class="form-table" style="width:570px;"> 
				<tr valign="top"> 
					<th scope="row"><label for="ngs2_filter">Search Keyword</label></th> 
					<td><input name="ngs2_filter" type="text" id="ngs2_filter" value="<?=$_POST['ngs2_filter'];?>" class="regular-text" /></td>
					<td><input type="submit" name="Submit" class="button-primary" value="Search" /></td>
				</tr> 
			</table>
			
			</form>

			<br style="clear: both;"/>
		
			<table class="widefat">
				<thead>
				<tr>
					<th scope="col" ><?php _e('ID') ?></th>
					<th scope="col" ><?php _e('Title', 'nggallery') ?></th>
					<th scope="col" ><?php _e('Description', 'nggallery') ?></th>
					<th scope="col" ><?php _e('Author', 'nggallery') ?></th>
					<th scope="col" ><?php _e('Page ID', 'nggallery') ?></th>
					<th scope="col" ><?php _e('Quantity', 'nggallery') ?></th>
					<th scope="col" ><?php _e('Action'); ?></th>
				</tr>
				</thead>
				<tbody>
	<?php
				
	$gallerylist = ngs2_search_galleries($_POST['ngs2_filter']);

	if($gallerylist) {
		foreach($gallerylist as $gallery) {
			$class = ( $class == 'class="alternate"' ) ? '' : 'class="alternate"';
			$gid = $gallery->gid;
			$name = (empty($gallery->title) ) ? $gallery->name : $gallery->title;
			$author_user = get_userdata( (int) $gallery->author );
			?>
			<tr id="gallery-<?php echo $gid ?>" <?php echo $class; ?> >
				<th scope="row"><?php echo $gid; ?></th>
				<td>
					<?php if(nggAdmin::can_manage_this_gallery($gallery->author)) { ?>
						<a href="<?php echo wp_nonce_url( "admin.php?page=nggallery-manage-gallery&amp;mode=edit&amp;gid=" . $gid, 'ngg_editgallery')?>" class='edit' title="<?php _e('Edit') ?>" >
							<?php echo $name; ?>
						</a>
					<?php } else { ?>
						<?php echo $gallery->title; ?>
					<?php } ?>
				</td>
				<td><?php echo $gallery->galdesc; ?>&nbsp;</td>
				<td><?php echo $author_user->display_name; ?></td>
				<td><?php echo $gallery->pageid; ?></td>
				<td><?php echo $gallery->counter; ?></td>
				<td>
					<?php if(nggAdmin::can_manage_this_gallery($gallery->author)) : ?>
						<a href="<?php echo wp_nonce_url( "admin.php?page=nggallery-manage-gallery&amp;mode=delete&amp;gid=" . $gid, 'ngg_editgallery')?>" class="delete" onclick="javascript:check=confirm( '<?php _e("Delete this gallery ?",'nggallery')?>');if(check==false) return false;"><?php _e('Delete') ?></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php
		}
	} else {
		echo '<tr><td colspan="7" align="center"><strong>'.__('No entries found','nggallery').'</strong></td></tr>';
	}
	?>			
				</tbody>
			</table>
		</div>
	<?php
		} else { ?>
		<div class="wrap">
			<h2><?php _e('NextGEN Gallery not found!', 'nggallery') ?></h2>			
		</div>
<?php		}
	}

?>