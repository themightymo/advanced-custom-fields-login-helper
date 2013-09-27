<?php
/*
Plugin Name: The Mighty Mo's Lowrize Logins Helper!
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: The Plugin's Version Number, e.g.: 1.0
Author: Name Of The Plugin Author
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/



function display_acf_data($fieldName) {
	if (get_field($fieldName)) {
		the_field($fieldName);
	}
}

function display_acf_data2 ($fieldName, $message, $isNot) {
	if (get_field($fieldName) && get_field($fieldName) != $isNot) { 
		echo '<li>';
		echo $message . ' ' . get_field($fieldName); 
		echo '</li>';
	}
}


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
		if ( current_user_can( 'manage_options' ) || current_user_can( 'create_users' ) ) {
			// Display the contents of the post to admins
			display_all_acf_fields();
		} else {
			echo 'You do not have access to this post.  Please let Toby know if you do, indeed, need access.' . edit_post_link('Edit', '', ' ');
		}
			
	}
	
	return $content;
	
}


function display_all_acf_fields() { ?>
	
	<?php if (get_field('website_url') != 'http://' || get_field('wordpress_admin_user') || get_field('wordpress_admin_password')) { ?>

        <h2>Website Info:</h2>
        
        <ul>
            <?php display_acf_data2('website_url', 'Website URL:', 'http://'); ?>
        </ul>
        
        <h2>WordPress Login Info:</h2>
        <ul>
            <?php display_acf_data2('wordpress_login_url', 'WordPress Login URL:','http://' ); ?>
            <?php if (get_field('wordpress_admin_user')) { ?><li>WordPress Admin Username: <?php display_acf_data('wordpress_admin_user'); ?></li><?php } ?>
            <?php if (get_field('wordpress_admin_password')) { ?><li>WordPress Admin Password: <?php display_acf_data('wordpress_admin_password'); ?></li><?php } ?>
        </ul>
    
    <?php } ?>
	
    <?php if (get_field('ip_address') || get_field('cpanel_url') || get_field('cpanel_username') || get_field('cpanel_password') || get_field('ftp_hostname') || get_field('ftp_username') || get_field('ftp_password')) { ?>
    
        <h2>cPanel & FTP:</h2>
        <ul>
        <?php if (get_field('ip_address')) { ?><li>ip Address: <?php display_acf_data('ip_address'); ?></li><?php } ?>
        <?php if (get_field('cpanel_url')) { ?><li>cPanel URL: <?php display_acf_data('cpanel_url'); ?></li><?php } ?>
        <?php if (get_field('cpanel_username')) { ?><li>cPanel Username: <?php display_acf_data('cpanel_username'); ?></li><?php } ?>
        <?php if (get_field('cpanel_password')) { ?><li>cPanel Password: <?php display_acf_data('cpanel_password'); ?></li><?php } ?>
        <?php if (get_field('ftp_hostname')) { ?><li>FTP Hostname: <?php display_acf_data('ftp_hostname'); ?></li><?php } ?>
        <?php if (get_field('ftp_username')) { ?><li>FTP Username: <?php display_acf_data('ftp_username'); ?></li><?php } ?>
        <?php if (get_field('ftp_password')) { ?><li>FTP Password: <?php display_acf_data('ftp_password'); ?></li><?php } ?>
        </ul>
	
    <?php } ?>
    
    <?php if (get_field('contact_information_url')!='https://www.lowrize.com/themightymo/') { ?>

	<h2>Contact Info:</h2>
	<ul>
		<?php if (get_field('contact_information_url')!='https://www.lowrize.com/themightymo/') { ?><li>Contact Info: <br /><?php display_acf_data('contact_information_url'); ?></li><?php } ?>
	</ul>
	
    <?php } ?>
    
    
    <?php if (get_field('miscellaneous_info')) { ?>

        <h2>Miscellaneous Info:</h2>
        <ul>
            <?php if (get_field('miscellaneous_info')) { ?><li><?php display_acf_data('miscellaneous_info'); ?></li><?php } ?>
        </ul>
    
   <?php } ?>
   
    <?php if ( current_user_can( 'manage_options' ) || current_user_can( 'create_users' ) ) {
		// Display the contents of the post to admins
		?>
        <h2>Secret Info:</h2>
        <ul>
            <?php if (get_field('secret_info')) { ?><li><?php display_acf_data('secret_info'); ?></li><?php } ?>
        </ul>
        <?php
	} ?>
    
    
    
    
		<?php 
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
	
	
	 
	
	if(get_field('attachments'))
	{
		echo '<ul>';
	 
		while(has_sub_field('attachments'))
		{
			$attachment = get_sub_field('attachment');
			echo '<li><a href="' . $attachment['url'] . '">' . $attachment['title'] . '</li>';
		}
	 
		echo '</ul>';
	}
	 
	
	
	
	
	
	
}

/*function my_custom_content_filter ($content) {
	$content = $content . display_all_acf_fields();
}*/
add_filter('the_content', 'does_user_have_access', 20);









// Fix the styling of the "users" Advanced Custom Field group
function my_enqueue($hook) {
    if( 'post.php' != $hook )
    return;
    //wp_enqueue_style( 'my_custom_script', plugins_url('/tmm-logins-helper.js', __FILE__) );
	wp_register_style( 'custom_wp_admin_css', plugins_url('/advanced-custom-fields-login-helper.css', __FILE__), false, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'my_enqueue' );