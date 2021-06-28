<?php
	if ( !defined( 'WP_CLI' ) || !WP_CLI ) {
		//Then we don't want to load the plugin
		return;
	}

	class GP_WP_CLI_COMMANDS extends WP_CLI_Command {

		/**
		 * Set current guest post author role
		 *
		 * ## OPTIONS
		 * --role=<string>
		 * : Please pass --role option
		 * 
		 * ## EXAMPLES
		 *
		 * wp guest_post set_gp_author --role=admin
		 *
		 * @when after_wp_load
		 */
		public function set_gp_author ( $args, $assoc_args ) {

			//Get the args
			$role = trim(strtolower($assoc_args['role'])); //get option data
			$editable_roles = get_editable_roles(); //get all editable roles

			$get_gp_post_current_author_role = get_option('gp_post_author_role'); //get gp_post_author_role
		
			WP_CLI::line( 'Start setting role...' );
			if (array_key_exists($role,$editable_roles)){ //check pass role in exist or not
				if($get_gp_post_current_author_role == $role){
					WP_CLI::log( "{$role} is already set." );
				}else{
					update_option( 'gp_post_author_role', $role ); //set role in option table with gp_post_author_role
					WP_CLI::success( "Guest post create role seted as {$role}" );
				}
			}else{
				WP_CLI::warning( "The role {$role} does not seem to exist." );
			}
		}

		/**
		 * Get current guest post author role
		 * 
		 * ## EXAMPLES
		 *
		 * wp guest_post get_gp_author
		 *
		 * @when after_wp_load
		 */
		public function get_gp_author ( $args, $assoc_args ) {
				$role = get_option('gp_post_author_role');  //get gp_post_author_role
				if(!$role){ //check role is exist or not
					$role = "author"; //return default role
				}
				WP_CLI::log( "Current guest post editor role is: {$role}." );
		}

	}

	WP_CLI::add_command( 'guest_post', 'GP_WP_CLI_COMMANDS' );