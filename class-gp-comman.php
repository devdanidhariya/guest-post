<?php
/**
 * use for write commanly use funcation
 *
 * @author Devidas
 */

if( ! class_exists('GP_Common') ) :
    class GP_Common {
       public function __construct() {
            
        }

        /*
    	*  get_custume_post_type
    	*
    	*  Get active register custom post type
    	*  @type	function
    	*
    	*  @param	N/A
    	*  @return	Return register custom post type in object format
    	*/
        public static function get_custume_post_type(){
            $args       = array(
                '_builtin' => false
            );
            $post_types = get_post_types( $args, 'objects' );

            return $post_types;
        }

        /*
    	*  check_is_auther_login
    	*
    	*  Check whether user is login with another role
    	*  @type	function
    	*
    	*  @param	N/A
    	*  @return	return boolean. If user if login with auther role then true.else false.
    	*/
        public static function check_is_auther_login(){
            $current_user = wp_get_current_user(); //get current login user detail.
            if ( is_user_logged_in() && in_array( GP_ALLOW_ROLE, ( array ) $current_user->roles ) ){ // check user is login with Define constant role.
                return true;
            }else{
                return false;
            }
        }

        /*
    	*  send_admin_notify_email
    	*
    	*  Whene guest post create with draft status.
    	*  @type	function
    	*
    	*  @param	post_id,post object
    	*  @return	Send mail to admin.
    	*/
        public function send_admin_notify_email($post_id, $post){
            if (wp_is_post_revision($post_id)){ //ignore if revision post
                return;
            }

            $admin_email = get_option('admin_email');
            $blog_title = get_bloginfo();
            $post_edit_link =  get_edit_post_link($post_id);
            
            //set header
            $headers = array('Content-Type: text/html; charset=UTF-8','From: blog_title <'.$admin_email.'>');

            //set mail subject
            $subject = "[$blog_title] A post has been created ";
            

            // Mail body start
            $message = "Dear Admin.<br/><br/>A post has been created on $blog_title website. Please click <a href='$post_edit_link'>here</a> to review post<br/>";
            $message .= "<b>Title:</b> $post->post_title<br>";
            $message .= "<b>post type</b>: $post->post_type<br>";
            $message .= "<b>post excerpt</b>: $post->post_excerpt<br>";
            $message .= "<b>post date</b>: ".date('d M Y',strtotime($post->post_date))."<br>";

            $message .= "Regards,<br>All at $blog_title<br>";
            $message .= site_url();        
            // Mail body end

            wp_mail($admin_email, $subject, $message,$headers); //Send mail
        }
        

    }
endif;