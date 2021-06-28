<?php
/*
    Plugin Name: Guest Post
    Plugin URI: https://github.com/devdanidhariya/guest-post
    Description: This exercise is about creating an interface in Front-end site of website, so that guest authors
    can submit posts from front-side.
    Version: 1.0.0
    Author: Devidas Danidhariya
    Author URI: http://devidas.in/
    License: GPL v2 or later
    License URI: https://www.gnu.org/licenses/gpl-2.0.html
    Text Domain: guest-post
    Domain Path: /languages
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('GP') ):

    class GP {
    	
    	/** @var string The plugin version number */
    	var $version = '1.0.0';
    	
    	/*
    	*  __construct
    	*
    	*  A constructor to ensure is only initialized once
    	*
    	*  @type	function
    	*
    	*  @param	N/A
    	*  @return	N/A
    	*/
    	
    	public function __construct() {
            // vars
            $this->basename = plugin_basename( __FILE__ );
            $this->path = plugin_dir_path( __FILE__ );
            $this->url = plugin_dir_url( __FILE__ );
            $this->slug = dirname($this->basename);
            $this->lib = $this->url.'lib/';

            // Define
            define('GP_BASENAME', $this->basename);
            define('GP_PATH', $this->path );
            define('GP_URL', $this->url );
            define('GP_SLUG', $this->slug);
            define('GP_LIB', $this->lib); 

            $get_gp_post_current_author_role = get_option('gp_post_author_role');
            if($get_gp_post_current_author_role){
                define('GP_ALLOW_ROLE', $get_gp_post_current_author_role); 
            }else{
                define('GP_ALLOW_ROLE', 'author'); 
            }
             
            
        }
    	
    	
    	/*
    	*  initialize
    	*
    	*  Use for initialize funcatinality,include file and register hook
    	*  @type	function
    	*
    	*  @param	N/A
    	*  @return	N/A
    	*/
    		
    	public function initialize() {                
            // Hooks
            register_activation_hook( __FILE__, array( $this, 'install' ) );
            
            // File Include
            require_once ($this->path . 'class-gp-comman.php');
            require_once ($this->path . 'shortcode/add-post-form.php');
            require_once ($this->path . 'shortcode/list-posts.php');
            require_once ($this->path . 'cli/classs-gp-cli.php');


            // Action hooks
            add_action('init',	array($this, 'register_post_types'));
            add_action( 'wp_enqueue_scripts', array($this,'load_custom_style_script') );

            //Ajax hooks 
            add_action( 'wp_ajax_post_form_submit', array('GP_Add_Post','post_form_save_handler' ));
            add_action('wp_ajax_nopriv_post_form_submit',  array('GP_Add_Post','post_form_save_handler' ));
            // add_action( 'wp_ajax_slider_delete', array('adminSlider','slider_delete' ));
            // add_action( 'wp_ajax_slider_preview', array('adminSlider','slider_preview' ));
            
            //Shortcode
            add_shortcode( 'gp_add_post', array('GP_Add_Post','gp_add_post') );
            add_shortcode( 'gp_list_posts', array('GP_List_Post','gp_list_posts') );

            //send email notification hook
            add_action('draft_guest_post', array('GP_Common','send_admin_notify_email'), 10, 2);

        }

        /*
    	*  install
    	*
    	*  At plugin active time call
    	*  @type	function
    	*
    	*  @param	N/A
    	*  @return	N/A
    	*/
            
        public function install(){
            
        }
        

        /*
    	*  load custom style script
    	*
    	*  Enqueue scripts and styles 
    	*  @type	function
    	*
    	*  @param	N/A
    	*  @return	N/A
    	*/
        public function load_custom_style_script(){
            $gp_common = new GP_Common;
            $is_auther_login = $gp_common::check_is_auther_login();

            // include js css file in particuler pages
            if( true === $is_auther_login){ // cehck withere user is login then enqueue script
                
                wp_enqueue_media(); //Load media upload dependency

                wp_register_script('jquery-validate-min', $this->lib.'/js/jquery.validate.min.js', array( 'jquery' ));
                wp_enqueue_script('jquery-validate-min'); // Jjquery validate
                
                wp_register_script('guest_post_js', $this->lib.'js/main.js');
                wp_enqueue_script('guest_post_js'); //custom script
                
                //localize script with custom var
                wp_localize_script( 'guest_post_js', 'gp_data', array(
                                        'ajaxurl' => admin_url( 'admin-ajax.php' ),
                                        'nonce'   => wp_create_nonce( "guest-post" ),
                                        'sucess'  => __( 'Post created successfully', 'guest-post' ),
                                        'error'   => __( 'Something went wrong please try again later', 'guest-post' )
                                    )
                );
                
                // CSS
                // wp_register_style('guest_post_js', $this->lib.'css/style.css');
                // wp_enqueue_style('guest_post_js');
            }
        }
            
        /*
    	*  register_post_types
    	*
    	*  This function will register post types
    	*
    	*  @param	n/a
    	*  @return	n/a
    	*/
    	
    	public function register_post_types() {
                
                // register post type 'guest-post'
                $labels = array(
                    'name'                  => _x( 'Guest Posts', 'Post Type General Name', 'guest-post' ),
                    'singular_name'         => _x( 'Guest Post', 'Post Type Singular Name', 'guest-post' ),
                    'menu_name'             => __( 'Guest Posts', 'guest-post' ),
                    'name_admin_bar'        => __( 'Guest Post', 'guest-post' ),
                    'archives'              => __( 'Item Archives', 'guest-post' ),
                    'attributes'            => __( 'Item Attributes', 'guest-post' ),
                    'parent_item_colon'     => __( 'Parent Item:', 'guest-post' ),
                    'all_items'             => __( 'All Items', 'guest-post' ),
                    'add_new_item'          => __( 'Add New Item', 'guest-post' ),
                    'add_new'               => __( 'Add New', 'guest-post' ),
                    'new_item'              => __( 'New Item', 'guest-post' ),
                    'edit_item'             => __( 'Edit Item', 'guest-post' ),
                    'update_item'           => __( 'Update Item', 'guest-post' ),
                    'view_item'             => __( 'View Item', 'guest-post' ),
                    'view_items'            => __( 'View Items', 'guest-post' ),
                    'search_items'          => __( 'Search Item', 'guest-post' ),
                    'not_found'             => __( 'Not found', 'guest-post' ),
                    'not_found_in_trash'    => __( 'Not found in Trash', 'guest-post' ),
                    'featured_image'        => __( 'Featured Image', 'guest-post' ),
                    'set_featured_image'    => __( 'Set featured image', 'guest-post' ),
                    'remove_featured_image' => __( 'Remove featured image', 'guest-post' ),
                    'use_featured_image'    => __( 'Use as featured image', 'guest-post' ),
                    'insert_into_item'      => __( 'Insert into item', 'guest-post' ),
                    'uploaded_to_this_item' => __( 'Uploaded to this item', 'guest-post' ),
                    'items_list'            => __( 'Items list', 'guest-post' ),
                    'items_list_navigation' => __( 'Items list navigation', 'guest-post' ),
                    'filter_items_list'     => __( 'Filter items list', 'guest-post' ),
                );
                $args   = array(
                    'label'               => __( 'Guest Post', 'guest-post' ),
                    'description'         => __( 'This is Guest Post Type', 'guest-post' ),
                    'labels'              => $labels,
                    'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
                    'hierarchical'        => false,
                    'public'              => true,
                    'show_ui'             => true,
                    'show_in_menu'        => true,
                    'menu_position'       => 5,
                    'menu_icon'           => 'dashicons-groups',
                    'show_in_admin_bar'   => true,
                    'show_in_nav_menus'   => true,
                    'can_export'          => true,
                    'has_archive'         => true,
                    'exclude_from_search' => false,
                    'publicly_queryable'  => true,
                    'query_var'          => true,
                    'rewrite'            => array( 'slug' => 'guest-post' ),
                    'capability_type'     => 'post',
                );
                register_post_type( 'guest_post', $args );
    	}   
    }

    $gp = new GP(); //create object
    $gp->initialize(); //initialize method
endif; // class_exists check