<?php
	$result = hiweb_export()->file( $_GET['file_data'] )->process( $_POST );
?>
<div class="wrap">
	<h1><a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button">←</a> hiWeb Import Tool → Result
		<small>(step 3 of 3)</small>
	</h1>
	<h1>
		<small>Success:</small> <?php echo count( $result['success'] ) ?></h1>
	<h1>
		<small>Errors:</small> <?php echo count( $result['error'] ) ?></h1>
	<h3>Insert Taxonomies: </h3>
	<p><?php foreach( $result['taxonomies'] as $taxonomy => $terms ){
			?><b><?php echo $taxonomy ?></b>: <?php echo implode( ', ', $terms ) ?><br/><?php
		} ?></p>
	<h3>Insert Meta Keys:</h3>
	<p><?php foreach( $result['meta'] as $meta => $meta_count ){
			?><b><?php echo $meta ?></b> : <?php echo $meta_count ?><br/><?php
		} ?></p>
</div>