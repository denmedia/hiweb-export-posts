<?php
	
	
	/**
	 * Created by PhpStorm.
	 * User: hiweb
	 * Date: 19.09.2016
	 * Time: 10:26
	 */
	class hw_export{

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
		 * @return mixed
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
			$R = array();
			if( $this->is_exist() ){
				$posts = get_posts( array(
					'posts_per_page' => - 1, 'post_type' => $this->post_type
				) );
				if( is_array( $posts ) )
					foreach( $posts as $post ){
						$R[ $post->ID ] = array(
							'post' => $post, 'meta' => array(), 'terms' => array()
						);
						///META
						$meta = get_post_meta( $post->ID );
						foreach( $meta as $key => $val ){
							$R[ $post->ID ]['meta'][ $key ] = is_array( $val ) ? reset( $val ) : $val;
						}
						//TAXONOMIES
						$taxonomies = get_object_taxonomies( $this->post_type, 'object' );
						if( is_array( $taxonomies ) )
							foreach( $taxonomies as $taxonomy_name => $taxonomy ){
								$R[ $post->ID ]['terms'][ $taxonomy_name ] = array();
								$args = array( 'orderby' => 'name', 'order' => 'ASC', 'fields' => 'all' );
								$terms = wp_get_post_terms( $post->ID, $taxonomy_name, $args );
								if( is_array( $terms ) )
									foreach( $terms as $term ){
										$R[ $post->ID ]['terms'][ $taxonomy_name ][] = $term->name;
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
			$this->path = $filepath;
			if( $this->is_exist() ){
				if( strpos( $filepath, HW_EXPORT_POSTS_DIR_UPLOAD ) === false ){
					wp_mkdir_p( HW_EXPORT_POSTS_DIR_UPLOAD );
					$newPath = HW_EXPORT_POSTS_DIR_UPLOAD . '/' . basename( $filepath ) . '.json';
					if( copy( $filepath, $newPath ) ){
						$this->path = HW_EXPORT_POSTS_DIR_UPLOAD . '/' . basename( $filepath ) . '.json';
					}
				}
				$this->content = file_get_contents( $this->path );
				$this->data = json_decode( $this->content, true );
			}
		}


		public function is_exist(){
			return file_exists( $this->path ) && is_file( $this->path ) && is_readable( $this->path );
		}


		public function size(){
			return filesize( $this->path );
		}


		public function data(){
			return $this->data;
		}


		public function post_types(){
			return array_keys( $this->data() );
		}

	}
	
	
	class hw_export_hooks{
		
		public function admin_menu(){
			add_submenu_page( 'tools.php', 'hiWeb Export Post', 'hiWeb Export Posts', 'publish_posts', HW_EXPORT_POSTS_SLUG_PAGE, array( $this, 'dashboard' ) );
		}
		
		
		public function dashboard(){
			if( !isset( $_GET['mod'] ) ){
				include_once HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-1.php';
				return;
			}
			switch( $_GET['mod'] ){
				case 'export':
					include_once HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-export-1.php';
					break;
				case 'import':
					if( count( $_POST ) == 0 && count( $_FILES ) == 0 ){
						include_once HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-import-1.php';
					}else{
						include_once HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-import-2.php';
					}
					break;
				default:
					include_once HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-1.php';
			}
		}


		public function ajax_download(){
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
		
		
		public function ajax(){
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