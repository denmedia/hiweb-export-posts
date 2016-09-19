<div class="wrap">
	<h1><a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button">←</a> hiWeb Export Tool</h1>
	<div class="card pressthis">
		<form action="<?php echo admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE . '&mod=import' ) ?>" method="post" enctype="multipart/form-data">
			<h2>1 / 3 - Select JSON data and start import...</h2>
			<p>After upload file you must select where (wath post type) you want import posts</p>
			<p><input type="file" name="file_data"/></p>
			<button class="button button-primary" type="submit">Upload</button>
		</form>
	</div>
</div>