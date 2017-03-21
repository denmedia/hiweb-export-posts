<div class="wrap">
	<h1><a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button">←</a> <?php _e( 'hiWeb Export Tool → Select Posts for Export', 'hw_export_posts' ) ?></h1>
	<p class="describe"><?php _e( 'Select Post Types For Export...', 'hw_export_posts' ) ?></p>
	<form action="<?php echo admin_url( 'admin-ajax.php?action=hw_export_posts_download' ) ?>" method="post">
		<table class="wp-list-table widefat fixed striped pages">
			<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'Select All', 'hw_export_posts' ) ?></label><input id="cb-select-all-1" type="checkbox"></td>
				<th><?php _e( 'Post Type', 'hw_export_posts' ) ?></th>
				<th><?php _e( 'Posts Count', 'hw_export_posts' ) ?></th>
				<th><?php _e( 'Taxonomies', 'hw_export_posts' ) ?></th>
				<th></th>
			</tr>
			</thead>

			<tbody id="the-list">
			<?php
				foreach( hiweb_export()->post_types() as $post_type_name => $post_type ){ ?>
					<tr>
						<th scope="row" class="check-column"><label class="screen-reader-text" for="cb-select-<?php echo $post_type_name ?>">Select cb-select-<?php echo $post_type->get()->label ?></label>
							<input type="checkbox" name="pt[]" id="cb-select-<?php echo $post_type_name ?>" value="<?php echo $post_type_name ?>">
							<div class="locked-indicator"></div>
						</th>
						<td class="title column-title has-row-actions column-primary page-title" data-colname="Title"><strong>
								<a class="row-title" href="<?php echo $post_type->url_edit() ?>" target="_blank"><?php echo $post_type->get()->label ?></a></strong>
						</td>
						<td>
							<?php echo $post_type->count() ?>
						</td>
						<td>

							<?php
								$taxonomies = $post_type->taxonomies();
								$taxonomies_info = array();
								if( is_array( $taxonomies ) && count( $taxonomies ) > 0 ){
									foreach( $taxonomies as $taxonomy_name => $taxonomy ){
										$term_args = array(
											'taxonomy' => $taxonomy_name,
											'hide_empty' => false,
										);
										$terms_count = count( get_terms( $term_args ) );
										$taxonomies_info[] = '<b><a href="' . self_admin_url( 'edit-tags.php?taxonomy=' . $taxonomy_name . '&post_type=' . $post_type_name ) . '" target="_blank">' . $taxonomy->label . '</a></b> <span title="Terms Count">(' . $terms_count . ')';
									}
									echo implode( ', ', $taxonomies_info );
								} else {
									?>─<?php
								}

							?>
						</td>
						<td style="text-align: right">
							<a href="<?php echo HW_EXPORT_POSTS_URL_AJAX . '?action=hw_export_posts&pt=' . $post_type_name ?>" class="button button-small" target="_blank"><?php _e( 'Open JSON', 'hw_export_posts' ) ?></a>
							<a href="<?php echo HW_EXPORT_POSTS_URL_AJAX . '?action=hw_export_posts_download&pt=' . $post_type_name ?>" class="button button-small" target="_blank"><?php _e( 'Download JSON', 'hw_export_posts' ) ?></a>
						</td>
					</tr>
					<?php
				}
			?>
			</tbody>

			<tfoot>
			<tr>
				<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'Select All', 'hw_export_posts' ) ?></label><input id="cb-select-all-1" type="checkbox"></td>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			</tfoot>

		</table>
		<p>
			<button type="submit" class="button button-primary"><?php _e( 'Export All Selected Posts', 'hw_export_posts' ) ?></button>
		</p>
	</form>
</div>