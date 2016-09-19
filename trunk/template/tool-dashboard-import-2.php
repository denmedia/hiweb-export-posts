<?php

	$file = hiweb_export()->file( isset( $_GET['file_data'] ) ? $_GET['file_data'] : ( isset( $_FILES['file_data']['tmp_name'] ) ? $_FILES['file_data']['tmp_name'] : false ) );
	if( $file->is_exist() ):
		?>
		<div class="wrap">
			<h1><a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button">←</a> hiWeb Import Tool → Select Post Types and Setup <small>(step 2 of 3)</small></h1>
			<div>
				<form action="<?php echo admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE . '&mod=import&file_data='.$file->basename() ) ?>" method="post">

					<table class="wp-list-table widefat fixed striped pages">
						<thead>
						<tr>
							<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" checked type="checkbox"></td>
							<th width="15%">Post Type</th>
							<th></th>
							<th>Convert to Post type...</th>
							<th width="40%">Taxonomies</th>
						</tr>
						</thead>
						<tbody id="the-list">

						<?php foreach( $file->data() as $post_type_name => $posts_data ){
							$post_type = $file->post_type( $post_type_name );
							$posts_count = $file->posts_count( $post_type_name );
							$posts_count = $posts_count !== false ? $posts_count : count( $file->posts( $post_type_name ) );
							?>
							<tr data-post-type="<?php echo $post_type_name ?>">
								<th scope="row" class="check-column">
									<input type="checkbox" name="pt[]" checked id="cb-select-<?php echo $post_type_name ?>" value="<?php echo $post_type_name ?>">
									<div class="locked-indicator"></div>
								</th>
								<td>
									<b class="row-title"><?php echo $post_type['label']; ?></b>
									<div>posts: <?php echo $posts_count ?></div>
								</td>
								<td>
									<label><input type="checkbox" name="pts[<?php echo $post_type_name ?>][remove]"> clear before insert</label>
									<!--<select name="replace_key">
										<option value="">- not update -</option>
										<option value="ID">ID</option>
										<option value="post_title">post_title</option>
										<option value="post_date">post_date</option>
									</select>-->
								</td>
								<td>
									<select name="pts[<?php echo $post_type_name ?>][post_type]"><?php

											foreach( hiweb_export()->post_types() as $to_type => $ty_type_obj ){
												$selected = $post_type_name == $to_type ? 'selected' : '';
												?>
												<option <?php echo $selected ?> value="<?php echo $to_type ?>"><?php echo $ty_type_obj->get()->label . ' (' . $ty_type_obj->type() . ')' ?></option><?php
											}

										?></select>
								</td>
								<td>
									<?php foreach( $file->taxonomies( $post_type_name ) as $taxonomy_name => $taxonomy ){
										?>
										<p><?php echo $taxonomy['label'] ?> → <select  name="pts[<?php echo $post_type_name ?>][taxonomies][<?php echo $taxonomy_name ?>]" id="taxonomy_<?php echo $post_type_name . '_' . $taxonomy_name ?>">
											<?php echo hiweb_export()->html()->select_options_taxonomies(); ?>
										</select></p><?php
									} ?>
								</td>
							</tr>
							<?php
						} ?>
						</tbody>
						<tfoot>
						<tr>
							<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input checked id="cb-select-all-1" type="checkbox"></td>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
						</tfoot>

					</table>
					<p>
						<button type="submit" class="button button-primary button-hero">Import Selected Posts</button>
					</p>
				</form>
			</div>
		</div>
		<script>
			jQuery(document).ready(function ($) {
				window.history.pushState("", "", 'tools.php?page=<?php echo HW_EXPORT_POSTS_SLUG_PAGE ?>&mod=import&file_data=<?php echo $file->basename() ?>');
				$('select[name="dest_post_type"]').change(function () {
					var post_type_name = $(this).closest('tr[data-post-type]').attr('data-post-type');
					$.ajax({});
				});
			});
		</script>
	<?php else: ?>
		<div class="wrap"><h1>File Error...</h1></div>
	<?php endif;