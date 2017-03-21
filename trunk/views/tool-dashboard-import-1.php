<div class="wrap">
	<h1><a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button">←</a> <?php _e('hiWeb Import Tool → Select Data File','hw_export_posts') ?> <small><?php _e('(step 1 of 3)','hw_export_posts') ?></small></h1>
	<div class="card pressthis">
		<form action="<?php echo admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE . '&mod=import' ) ?>" method="post" enctype="multipart/form-data">
			<a href="<?php echo admin_url('tools.php?page='.HW_EXPORT_POSTS_SLUG_PAGE.'&mod=history') ?>" class="button button-primary"><?php _e('Select Already Uploaded file','hw_export_posts') ?></a>
			<h2><?php _e('or Upload export JSON file:','hw_export_posts') ?></h2>
			<p><?php _e('After upload file you must select where (wath post type) you want import posts','hw_export_posts') ?></p>
			<p><input type="file" name="file_data"/></p>
			<button class="button button-primary" type="submit"><?php _e('Upload','hw_export_posts') ?></button>
		</form>
	</div>
</div>