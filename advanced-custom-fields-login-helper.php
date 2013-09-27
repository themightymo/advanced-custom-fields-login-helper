<?php
/*
Plugin Name: Advanced Custom Fields Login Helper
Plugin URI: https://github.com/themightymo/advanced-custom-fields-login-helper
Description: Allows you to use Advanced Custom Fields to manage user access to posts and pages.
Version: 1.0
Author: Toby Cryns
Author URI: http://www.themightymo.com
License: GPL2
*/


function does_user_have_access($content) {
	
	// Grab the current user's info so that we can compare it to the "allowed" users from the ACF "User" field later.
	$current_user = wp_get_current_user();
	
	// Store the ACF "User" info
	$values = get_field('user_info');
	
	if($values) { 
		// Create an array of users that will be able to access the page from the ACF "User" field
		$users_that_can_access_this_post = array();
		foreach($values as $value) {
			$user_IDs_that_can_access_this_post[] = $value['ID'];
		} 
		// Check to see if the current user is in the "User" field's array
		if (in_array($current_user->ID, $user_IDs_that_can_access_this_post, false) || current_user_can( 'manage_options' ) || current_user_can( 'create_users' )) {
			// Display the post
			display_all_acf_fields();
		} else {
			// Hide the post content if the user is not in the ACF "User" array
			echo 'You do not have access to this post.  Please let Toby know if you do, indeed, need access.' . edit_post_link('Edit', '', ' ');
		}
	} else {
		// If no users have been selected on this page's ACF field, then display the content for admins and display an "error" message for other users.
		if ( current_user_can( 'manage_options' ) || current_user_can( 'create_users' ) ) {
			// Display the contents of the post to admins
			display_all_acf_fields();
		} else {
			// Error message for non-admins.
			echo 'You do not have access to this post.  Please let Toby know if you do, indeed, need access.' . edit_post_link('Edit', '', ' ');
		}
			
	}
	
	return $content;
	
}


function display_all_acf_fields() { 

		// Display a list of users who can access the current page to site admins. 
        if (current_user_can( 'manage_options' ) || current_user_can( 'create_users' )) {
            echo '<pre>';
            echo 'The following users have access to this login info:<br />';
            $values = get_field('user_info');
            //print_r($values);
            
            foreach($values as $value) {
                echo $value['display_name'];
                echo '<br>';
            }  
            echo '</pre>';
        }
        
        /*
        
        HERE IS WHERE YOU ADD EXTRA CONTENT, ADVANCED CUSTOM FIELD THEME HOOKS SUCH AS get_field($var) and the_field($var), ETC.
        
 ___________________          _-_
 \==============_=_/ ____.---'---`---.____
             \_ \    \----._________.----/
               \ \   /  /    `-_-'
           __,--`.`-'..'-_
          /____          ||
               `--.____,-'
                
                
                Starship Enterprise via http://www.chris.com/ascii/index.php?art=television/star%20trek
		*/
        
}

add_filter('the_content', 'does_user_have_access', 20);



// By default, the Advanced Custom Fields "User" field is super short.  This fixes that via CSS.
function my_enqueue($hook) {
    if( 'post.php' != $hook )
    return;
    //wp_enqueue_style( 'my_custom_script', plugins_url('/tmm-logins-helper.js', __FILE__) );
	wp_register_style( 'custom_wp_admin_css', plugins_url('/advanced-custom-fields-login-helper.css', __FILE__), false, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'my_enqueue' );