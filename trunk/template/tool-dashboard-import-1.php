<div class="wrap">
	<h1><a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button">←</a> hiWeb Import Tool → Select Data File <small>(step 1 of 3)</small></h1>
	<div class="card pressthis">
		<form action="<?php echo admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE . '&mod=import' ) ?>" method="post" enctype="multipart/form-data">
			<a href="<?php echo admin_url('tools.php?page='.HW_EXPORT_POSTS_SLUG_PAGE.'&mod=history') ?>" class="button button-primary">Select Already Uploaded file</a>
			<h2>or Upload export JSON file:</h2>
			<p>After upload file you must select where (wath post type) you want import posts</p>
			<p><input type="file" name="file_data"/></p>
			<button class="button button-primary" type="submit">Upload</button>
		</form>
	</div>
</div>