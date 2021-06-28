<?php
/**
 * use for add shortcode
 *
 * @author Devidas
 */

// check wither class exists or not
if( ! class_exists('GP_Add_Post') ):
    class GP_Add_Post extends GP_Common{
        
        function __construct(){
        }
        
        /*
    	*  gp_add_post
    	*
    	*  [gp_add_post /] call function
    	*  @type	function
    	*
    	*  @param	atts,content
    	*  @return	HTML form
    	*/
        public static function gp_add_post($atts, $content = null) {
            // $atts = shortcode_atts( array('id' => null), $atts, 'gp_add_post' );
            // $post_id =  $atts['id'];


            $current_user = wp_get_current_user();
            $is_auther_login = parent::check_is_auther_login();
            if( true === $is_auther_login){
                ob_start(); ?>
                <form method="post" id="add-post-form" name="add-post-form" class="add-post-form"  novalidate="novalidate">
                    <div class="form-group">
                        <label for="post_title"><?php _e( 'Post Title', 'guest-post' ); ?></label>
                        <input type="text" name="post_title" class="form-control" id="post_title" />
                    </div>
                    <?php
                        $post_types = parent::get_custume_post_type();
                        if ( is_array( $post_types ) && sizeof( $post_types ) > 0 ): 
                    ?>
                        <div class="form-group">
                            <label for="post_type"><?php _e( 'Post Type', 'guest-post' ); ?></label>
                            <select name="post_type" class="form-control" id="post_type" >
                                <?php
                                foreach ( $post_types as $type ):
                                    echo wp_sprintf( '<option value="%s">%s</option>', $type->name, $type->label );
                                endforeach;
                                ?>               
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="post_content"><?php _e( 'Post Description', 'guest-post' ); ?></label>
                        <?php 
                            wp_editor( '', 'post_content', array( 'media_buttons' => false,'editor_class'  => 'post-content-required-field' ) );
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="post_excerpt"><?php _e( 'Post Excerpt', 'guest-post' ); ?></label>
                        <textarea  name="post_excerpt" class="form-control" id="post_excerpt" rows="4" ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="featured_image"><?php _e( 'Featured Image', 'guest-post' ); ?></label>
                        <!-- <label for="choose-featured-image" class="btn btn-light">Set Featured Image</label> -->
                        <button class="btn btn-group-sm btn-info" id="choose-featured-image"><?php _e( 'Choose featured image', 'guest-post' ); ?></button>
                        <input type="hidden" id="featured_image" name="featured_image" value="" />
                        <div class="guest-post-featured-image"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="add_post_submit"><?php _e( 'Submit', 'guest-post' ); ?></button>
                    </div>
                    <div class="form-group response"></div>
                </form>
                <?php 
                $html = ob_get_contents();
                ob_end_clean();
                return $html;
            }else{
                return  wp_sprintf( '<div class="alert alert-warning"> %s  <strong>%s</strong>.</div>', __( 'For add new post please login with user role - ', 'guest-post' ), GP_ALLOW_ROLE );
            }
        }


        /*
    	*  post_form_save_handler
    	*
    	*  Use for post save handler.
    	*  @type	function
    	*
    	*  @param   N/A
    	*  @return	json data
    	*/
        public function post_form_save_handler(){
            check_ajax_referer('guest-post', 'security');  //Check security
               
                $post_data = array();
                parse_str($_POST['post_data'], $post_data); //serialize POST to array
                
                if ($post_data):
                    $post_title   = sanitize_text_field($post_data['post_title']);
                    $post_type    = sanitize_text_field($post_data['post_type']);
                    $post_content = wp_filter_post_kses($post_data['post_content']); //Sanitizes content for allowed HTML tags for post content.
                    $post_excerpt = wp_filter_post_kses($post_data['post_excerpt']); 
                    $featured_image     = intval($post_data['featured_image']); //Get the integer value of a variable
                    
                    //postarr 
                    $postarr = array(
                        'post_content' => $post_content,
                        'post_title'   => $post_title,
                        'post_excerpt' => $post_excerpt,
                        'post_status'  => 'draft',
                        'post_type'    => $post_type,
                    );
                    $post_id = wp_insert_post($postarr); //insert post
                    if (!is_wp_error($post_id)) { //check error
                        set_post_thumbnail($post_id, $featured_image); 
                        wp_send_json_success(array('post_id' => $post_id), 200);
                    } else {
                        wp_send_json_error($post_id->get_error_message());
                    }
                else:
                    wp_send_json_error(array('message' => _e( 'Something went wrong please try again later', 'guest-post' )));
                endif;
                wp_die();
        }
    }
endif;