<?php
// define('WP_DEBUG', true);


/*
Plugin Name: NewSum Article Feeder
Plugin URI: http://scify.org/
Description: Plugin for importing content from NewSum server
Author: George G. (ggianna@scify.org)
Version: 1.0
Author URI: http://scify.org/
*/

function optionsSavedNotice() {
      echo('<div class="updated"><strong>Options saved!</strong></div>');
    
}
// Update options
if(isset($_POST['action']) && ($_POST['action'] == 'update')) {
//    echo '<h1>';
//    var_dump(get_option('newsumfeed_server_url', ''));
//    echo '</h1>';
    //Form data sent
    update_option('newsumfeed_min_sources', $_POST['newsumfeed_min_sources']);
    update_option('newsumfeed_server_url', $_POST['newsumfeed_server_url']);
    update_option('newsumfeed_api_key', $_POST['newsumfeed_api_key']);
    update_option('newsumfeed_lang', $_POST['newsumfeed_lang']);
    add_action('admin_notices', 'optionsSavedNotice');
}

    


// Admin menus

add_action('admin_menu', 'newsumfeed_admin_menu');
function newsumfeed_admin_menu() {
    // TODO: Enable translation
//    add_options_page(_e("NewSum Feed", 'newsumfeed'), _e("NewSum Feed Options", 'newsumfeed'), 
//      'manage_options', "newsumfeed_options", "newsumfeed_form");
    add_menu_page( "NewSum Feed", "Import NewSum news", "manage_options", 
        "newsumfeed-import", "newsum_import_form"//, $icon_url, $position 
        );
    add_options_page("NewSum Feed", "NewSum Feed Options", 
      'manage_options', "newsumfeed-settings", "newsumfeed_options_form");
    add_action( 'admin_init', 'newsumfeed_register_settings' );
}

function newsum_import_form() {
    if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    include ("importPostsPage.php");
}

function newsumfeed_options_form() {
    if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access the NewSum settings page.' ) );
    }
    include ('optionForm.php');
//  echo 'Testing...';
}


function newsumfeed_register_settings() {
  register_setting( 'newsumfeed_settings', 'newsumfeed_server_url' );
  register_setting( 'newsumfeed_settings', 'newsumfeed_min_sources' );
  register_setting( 'newsumfeed_settings', 'newsumfeed_api_key' );    
  register_setting( 'newsumfeed_settings', 'newsumfeed_lang' );
}



// Init translation
add_action('init', 'newsumfeed_action_init');
function newsumfeed_action_init()
{
    // Localization
    load_plugin_textdomain('newsumfeed', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

/**
 * Proper way to enqueue scripts and styles
 */
function theme_name_scripts() {
	wp_enqueue_style( 'newsumfeed-css', plugins_url("newsumfeed")."/newsumfeed.css" );
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
?>

