<?php
	$args = array(
		'public' => true, '_builtin' => false
	);
	$post_types = get_post_types( $args, 'names', 'or' );
?>
<div class="wrap">
	<h1>hiWeb Export Tool</h1>
	<p class="describe">Select Post Types For Export...</p>
	<form action="?page=<?php echo HW_EXPORT_POSTS_SLUG_PAGE ?>&mod=export">
		<table class="wp-list-table widefat fixed striped pages">
			<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
				<th>Post Type</th>
				<th>Posts Count</th>
				<th>Taxonomies</th>
				<th></th>
			</tr>
			</thead>

			<tbody id="the-list">
			<?php
				if( is_array( $post_types ) )
					foreach( $post_types as $post_type_name ){
						///POST TYPE OBJECT
						$post_type = get_post_type_object( $post_type_name );
						///POSTS COUNT
						$posts_count = 0;
						foreach( (array)wp_count_posts( $post_type_name ) as $count ){
							$posts_count += (int)$count;
						}
						///TAXONOMY, TERMS
						$taxonomy_object = get_object_taxonomies( $post_type_name, 'object' );
						?>
						<tr>
							<th scope="row" class="check-column"><label class="screen-reader-text" for="cb-select-195">Select Page 1</label>
								<input id="cb-select-195" type="checkbox" name="post[]" value="195">
								<div class="locked-indicator"></div>
							</th>
							<td class="title column-title has-row-actions column-primary page-title" data-colname="Title"><strong>
									<a class="row-title" href="<?php echo self_admin_url( 'edit.php?post_type=' . $post_type_name ) ?>" target="_blank"><?php echo $post_type->label ?></a></strong>
							</td>
							<td>
								<?php echo $posts_count; ?>
							</td>
							<td>
								<?php if( is_array( $taxonomy_object ) )
									$taxonomies_info = array();
									foreach( $taxonomy_object as $taxonomy_name => $taxnonomy ){
										$term_args = array(
											'taxonomy' => $taxonomy_name, 'hide_empty' => false,
										);
										$terms_count = count( get_terms( $term_args ) );
										$taxonomies_info[] = '<b><a href="'.self_admin_url('edit-tags.php?taxonomy='.$taxonomy_name.'&post_type='.$post_type_name).'" target="_blank">' . $taxnonomy->label . '</a></b> <span title="Terms Count">(' .
										$terms_count . ')';
									}

									echo implode( ', ', $taxonomies_info );
								?>
							</td>
							<td style="text-align: right">
								<a href="#" class="button button-small">Save JSON to PC...</a>
							</td>
						</tr>
						<?php
					}
			?>
			</tbody>

			<tfoot>
			<tr>
				<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			</tfoot>

		</table>
		<p>
			<button type="submit" class="button button-primary">Export All Selected Posts</button>
		</p>
	</form>
</div>