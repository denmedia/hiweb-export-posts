<?php
	
	
	/**
	 * Created by PhpStorm.
	 * User: hiweb
	 * Date: 19.09.2016
	 * Time: 10:26
	 */
	class hw_export{

		/** @var hw_export_post_type[] */
		private $post_types = array();
		
		public $post_types_args = array(
			'public' => true, '_builtin' => false
		);
		

		/**
		 * @return hw_export_hooks
		 */
		public function hooks(){
			static $class;
			if( !$class instanceof hw_export_hooks )
				$class = new hw_export_hooks();
			return $class;
		}


		/**
		 * @param $post_type
		 * @return hw_export_post_type
		 */
		public function post_type( $post_type ){
			if( !array_key_exists( $post_type, $this->post_types ) ){
				$this->post_types[ $post_type ] = new hw_export_post_type( $post_type );
			}
			return $this->post_types[ $post_type ];
		}


		/**
		 * @return hw_export_post_type[]
		 */
		public function post_types( $post_types = null ){
			if( !is_array( $post_types ) ){
				$post_types = get_post_types( $this->post_types_args, 'names', 'or' );
			}
			$R = array();
			if( is_array( $post_types ) )
				foreach( $post_types as $post_type ){
					$R[ $post_type ] = $this->post_type( $post_type );
				}
			return $R;
		}


		/**
		 * @param $filePath
		 * @return hw_export_import
		 */
		public function file( $filePath ){
			static $class;
			if( !$class instanceof hw_export_import ){
				$class = new hw_export_import( $filePath );
			}
			return $class;
		}


		/**
		 * @return hw_export_html
		 */
		public function html(){
			static $class;
			if( !$class instanceof hw_export_html ){
				$class = new hw_export_html();
			}
			return $class;
		}
		
		
	}
	
	
	class hw_export_post_type{
		
		private $post_type;
		
		private $export_taxonomy = true;
		private $export_meta = true;
		/** @var null|WP_Post_Type */
		private $object;
		
		
		public function __construct( $post_type ){
			$this->post_type = $post_type;
			if( $this->is_exist() )
				$this->object = get_post_type_object( $this->post_type );
		}


		/**
		 * @return mixed
		 */
		public function type(){
			return $this->post_type;
		}


		/**
		 * @return null|WP_Post_Type
		 */
		public function get(){
			return $this->object;
		}


		public function url_edit(){
			return self_admin_url( 'edit.php?post_type=' . $this->post_type );
		}


		/**
		 * Возвращает количество записей
		 * @return int
		 */
		public function count(){
			$posts_count = 0;
			foreach( (array)wp_count_posts( $this->post_type ) as $count ){
				$posts_count += (int)$count;
			}
			return $posts_count;
		}


		/**
		 * @return array
		 */
		public function taxonomies(){
			return get_object_taxonomies( $this->post_type, 'object' );
		}


		/**
		 * @return WP_Term[]
		 */
		public function taxonomy_terms(){
			$R = array();
			$taxonomy_object = get_object_taxonomies( $this->post_type, 'object' );
			if( is_array( $taxonomy_object ) )
				foreach( $taxonomy_object as $taxonomy_name => $taxonomy ){
					$term_args = array(
						'taxonomy' => $taxonomy_name, 'hide_empty' => false,
					);
					$R[ $taxonomy_name ] = get_terms( $term_args );
				}
			return $R;
		}


		/**
		 * Возвращает TRUE, если тип поста существует
		 * @return bool
		 */
		public function is_exist(){
			$post_types = get_post_types( hiweb_export()->post_types_args, 'names', 'or' );
			return @array_key_exists( $this->post_type, $post_types );
		}


		public function data(){
			$R = array(
				'posts' => array(), 'post_type' => $this->get(), 'taxonomies' => $this->taxonomies(), 'terms' => $this->taxonomy_terms()
			);
			if( $this->is_exist() ){
				$posts = get_posts( array(
					'posts_per_page' => - 1, 'post_type' => $this->post_type
				) );
				if( is_array( $posts ) )
					foreach( $posts as $post ){
						$R['posts'][ $post->ID ] = array(
							'post' => $post, 'meta' => array(), 'terms' => array()
						);
						///META
						$meta = get_post_meta( $post->ID );
						foreach( $meta as $key => $val ){
							$R['posts'][ $post->ID ]['meta'][ $key ] = is_array( $val ) ? reset( $val ) : $val;
						}
						//TAXONOMIES
						$taxonomies = get_object_taxonomies( $this->post_type, 'object' );
						if( is_array( $taxonomies ) )
							foreach( $taxonomies as $taxonomy_name => $taxonomy ){
								$R['posts'][ $post->ID ]['terms'][ $taxonomy_name ] = array();
								$args = array( 'orderby' => 'name', 'order' => 'ASC', 'fields' => 'all' );
								$terms = wp_get_post_terms( $post->ID, $taxonomy_name, $args );
								if( is_array( $terms ) )
									foreach( $terms as $term ){
										$R['posts'][ $post->ID ]['terms'][ $taxonomy_name ][] = $term->name;
									}
							}
					}
			}
			return $R;
		}
		
	}


	class hw_export_import{

		private $path;
		private $content;
		private $data;


		public function __construct( $filepath ){
			if( !file_exists( $filepath ) && file_exists( HW_EXPORT_POSTS_DIR_UPLOAD . '/' . $filepath ) )
				$filepath = HW_EXPORT_POSTS_DIR_UPLOAD . '/' . $filepath;
			$this->path = $filepath;
			if( $this->is_exist() ){
				if( strpos( $filepath, HW_EXPORT_POSTS_DIR_UPLOAD ) === false ){
					wp_mkdir_p( HW_EXPORT_POSTS_DIR_UPLOAD );
					$newPath = HW_EXPORT_POSTS_DIR_UPLOAD . '/' . microtime( true ) . '.json';
					if( copy( $filepath, $newPath ) ){
						$this->path = $newPath;
					}
				}
				$this->content = file_get_contents( $this->path );
				$this->data = json_decode( $this->content, true );
			}
		}


		/**
		 * @return bool
		 */
		public function is_exist(){
			return file_exists( $this->path ) && is_file( $this->path ) && is_readable( $this->path );
		}


		/**
		 * @return string
		 */
		public function basename(){
			return basename( $this->path );
		}


		/**
		 * @return int
		 */
		public function size(){
			return filesize( $this->path );
		}


		/**
		 * @return array|mixed|object
		 */
		public function data(){
			return $this->data;
		}


		/**
		 * @return array
		 */
		public function post_types(){
			return array_keys( $this->data() );
		}


		public function post_type_data( $post_type ){
			if( !isset( $this->data[ $post_type ] ) )
				return false;
			
			return $this->data[ $post_type ];
		}


		/**
		 * @param $post_type
		 * @return array
		 */
		public function post_type( $post_type ){
			if( !isset( $this->data[ $post_type ]['post_type'] ) )
				return false;
			return $this->data[ $post_type ]['post_type'];
		}


		/**
		 * @param $post_type
		 * @return array
		 */
		public function posts( $post_type ){
			if( !isset( $this->data[ $post_type ]['posts'] ) )
				return false;
			return $this->data[ $post_type ]['posts'];
		}


		/**
		 * @param $post_type
		 * @return array
		 */
		public function posts_count( $post_type ){
			if( !isset( $this->data[ $post_type ]['posts_count'] ) )
				return false;
			return $this->data[ $post_type ]['posts_count'];
		}


		/**
		 * @param $post_type
		 * @return array
		 */
		public function taxonomies( $post_type ){
			if( !isset( $this->data[ $post_type ]['taxonomies'] ) )
				return false;
			return $this->data[ $post_type ]['taxonomies'];
		}


		/**
		 * @param $post_type
		 * @return array
		 */
		public function terms( $post_type ){
			if( !isset( $this->data[ $post_type ]['terms'] ) )
				return false;
			return $this->data[ $post_type ]['terms'];
		}


		public function process( $settings ){
			global $wpdb;
			$R = array(
				'success' => array(), 'error' => array(), 'taxonomies' => array(), 'meta' => array()
			);
			if( is_array( $settings ) && isset( $settings['pt'] ) && isset( $settings['pts'] ) && is_array( $settings['pts'] ) )
				foreach( $settings['pt'] as $pt ){
					$sett = $settings['pts'][ $pt ];
					////
					if( isset( $sett['remove'] ) && strtolower( $sett['remove'] ) == 'on' ){
						$query = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_type="' . $sett['post_type'] . '"' );
						$query = $wpdb->query( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE post_id NOT IN (SELECT id FROM ' . $wpdb->prefix . 'posts)' );
						$query = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'term_relationships WHERE object_id NOT IN (SELECT id FROM ' . $wpdb->prefix . 'posts)' );
					}
					$data = $this->post_type_data( $pt );
					////
					if( is_array( $data ) ){
						////////////
						if( isset( $data['posts'] ) && is_array( $data['posts'] ) )
							foreach( $data['posts'] as $post_id => $post_data ){
								$post = $post_data['post'];
								$meta = $post_data['meta'];
								$tax = $post_data['terms'];
								///
								$args = array(
									'post_content' => $post['post_content'], 'post_excerpt' => $post['post_excerpt'], 'post_name' => $post['post_name'], 'post_status' => $post['post_status'], 'post_title' => $post['post_title'],
									'post_type' => $sett['post_type'], 'post_date' => $post['post_date'], 'post_date_gmt' => $post['post_date_gmt'], 'post_mime_type' => $post['post_mime_type'], 'post_modified' => $post['post_modified'],
									'post_modified_gmt' => $post['post_modified_gmt'], 'post_parent' => $post['post_parent'], 'post_password' => $post['post_password'], 'comment_status' => $post['comment_status'], 'filter' => $post['filter'],
									'menu_order' => $post['menu_order'], 'post_content_filtered' => $post['post_content_filtered']
								);
								///
								$newId = wp_insert_post( $args );
								if( $newId != false ){
									$R['success'][ $newId ] = array(
										'post' => $post, 'meta' => $meta, 'tax' => $tax
									);
									foreach( $tax as $taxonomy => $terms ){
										if( isset( $sett['taxonomies'][ $taxonomy ] ) && trim( $sett['taxonomies'][ $taxonomy ] ) != '' ){
											wp_set_object_terms( $newId, $terms, $sett['taxonomies'][ $taxonomy ] );
										}
									}
									foreach( $meta as $metaKey => $metaValue ){
										update_post_meta( $newId, $metaKey, $metaValue[0] );
									}
								}else{
									$R['error'][ $newId ] = false;
								}
							}
						////////////
					}
					return $R;
				}
			return false;
		}


	}


	class hw_export_html{

		public function select_options_taxonomies( $post_type = null ){
			if( !is_string( $post_type ) )
				$post_type = reset( array_keys( hiweb_export()->post_types() ) );
			$R = '<option value="">- dont import -</option>';
			foreach( hiweb_export()->post_type( $post_type )->taxonomies() as $taxonomy_name => $taxonomy ){
				$R .= '<option value="' . $taxonomy_name . '">' . $taxonomy->label . '</option>';
			}
			return $R;
		}

	}
	
	
	class hw_export_hooks{
		
		public function admin_menu(){
			add_submenu_page( 'tools.php', 'hiWeb Export Post', 'hiWeb Export / Import', 'publish_posts', HW_EXPORT_POSTS_SLUG_PAGE, array( $this, 'dashboard' ) );
		}
		
		
		public function dashboard(){
			if( !isset( $_GET['mod'] ) ){
				include_once HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-1.php';
				return;
			}
			switch( $_GET['mod'] ){
				case 'export':
					include HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-export-1.php';
					break;
				case 'import':
					if( count( $_POST ) == 0 && ( isset( $_FILES['file_data'] ) || isset( $_GET['file_data'] ) ) ){
						include HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-import-2.php';
					}elseif( count( $_POST ) > 0 ){
						include HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-import-3.php';
					}else{
						include HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-import-1.php';
					}
					break;
				case 'history':
					include HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-history.php';
					break;
				default:
					include_once HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-1.php';
			}
		}


		public function posts(){
			$R = array();
			if( isset( $_GET['pt'] ) && !is_array( $_GET['pt'] ) ){
				$post_type = hiweb_export()->post_type( $_GET['pt'] );
				$R[ $post_type->type() ] = $post_type->data();
			}elseif( !isset( $_POST['pt'] ) || is_array( $_POST['pt'] ) ){
				$post_types = hiweb_export()->post_types( $_POST['pt'] );
				foreach( $post_types as $post_type ){
					$R[ $post_type->type() ] = $post_type->data();
				}
			}
			$content = json_encode( $R );
			////
			header( 'Content-Type: application/json' );
			header( 'Content-Length: ' . strlen( $content ) );
			echo $content;
			wp_die();
		}
		
		
		public function posts_download(){
			$R = array();
			if( isset( $_GET['pt'] ) && !is_array( $_GET['pt'] ) ){
				$post_type = hiweb_export()->post_type( $_GET['pt'] );
				$R[ $post_type->type() ] = $post_type->data();
			}elseif( !isset( $_POST['pt'] ) || is_array( $_POST['pt'] ) ){
				$post_types = hiweb_export()->post_types( $_POST['pt'] );
				foreach( $post_types as $post_type ){
					$R[ $post_type->type() ] = $post_type->data();
				}
			}
			$content = json_encode( $R );
			////
			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Type: application/json' );
			header( 'Content-Disposition: attachment; filename=' . implode( '-', array_keys( $R ) ) . '.json' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Connection: Keep-Alive' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Pragma: public' );
			header( 'Content-Length: ' . strlen( $content ) );
			echo $content;
			wp_die();
		}
		
	}