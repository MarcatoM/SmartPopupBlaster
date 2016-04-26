<?php
/*
Plugin Name: Smart PopUp Blaster
Plugin URI: http://wordpress.org/plugins/smart-popup-blaster/
Description: Elegant & easy to use popup plugin, fully responsive, highly customizable, scroll, click & time-delay triggered popups, Add forms, social media boxes, videos & more.
Author: Marin Matosevic
Version: 1.3
Author URI: http://marinmatosevic.com
Text Domain: smart-popup-blaster
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include 'admin/add_cpt.php';
include 'admin/admin-panel.php';

function spb_styles_and_scripts() {	
	wp_enqueue_style('animation-style', plugins_url( '/assets/css/animation.css' , __FILE__ ));

	wp_enqueue_script('popup-script', plugins_url( '/assets/js/popup-script.js' , __FILE__ ), array('jquery'), '1.0', true);
	wp_localize_script( 'popup-script', 'cookieAjax', array('ajax_url' => admin_url( 'admin-ajax.php' )));
}
add_action( 'wp_enqueue_scripts', 'spb_styles_and_scripts' );


add_action( 'admin_print_scripts-post-new.php', 'spb_popup_custom_script', 11 );
add_action( 'admin_print_scripts-post.php', 'spb_popup_custom_script', 11 );

function spb_popup_custom_script() {
    global $post_type;
    if( 'spb' == $post_type ){
    wp_enqueue_style('jquery-ui');	
    wp_enqueue_style('jquery-styles', plugins_url( '/assets/css/jquery-ui.css', __FILE__ ));     	
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_style( 'wp-color-picker' );  
    wp_enqueue_script( 'wp-color-picker');

    wp_enqueue_script( 'popup_custom_script', plugins_url( '/assets/js/popup_custom_script.js', __FILE__ ), array('jquery'), '1.0', true);      
	}
}

add_action( 'wp_ajax_nopriv_popup_effect', 'popup_effect' );
add_action( 'wp_ajax_popup_effect', 'popup_effect' );

function popup_effect() {	

	$the_post_id = $_POST['the_post_id'];	

	$data_response = array();

	$effect = get_post_meta($the_post_id, 'spb_popup_effect', true);
	$trigger = get_post_meta($the_post_id, "spb_delay_trigger", true);
    $delay_value = get_post_meta($the_post_id, "spb_popup_delay_value", true);
    
    array_push($data_response, $the_post_id, $effect, $trigger, $delay_value); 	

	wp_send_json($data_response);
	die();
}


add_action( 'wp_enqueue_scripts', 'spb_custom_style_script', 11 );
function spb_custom_style_script() {

    wp_enqueue_style( 'spb_dynamic-css', admin_url('admin-ajax.php').'?action=spb_dynamic_css', '', '1.0');

}
add_action('wp_ajax_spb_dynamic_css', 'spb_dynamic_css');
add_action('wp_ajax_nopriv_spb_dynamic_css', 'spb_dynamic_css');
function spb_dynamic_css() {      
    require(plugin_dir_path( __FILE__ ).'/assets/css/custom.css.php'); 
    exit;
}

include 'admin/popup_setup.php';	



add_filter( 'plugin_action_links', 'spb_add_action_plugin', 10, 5 );
function spb_add_action_plugin( $actions, $plugin_file ){
  static $plugin;

  if (!isset($plugin))
    $plugin = plugin_basename(__FILE__);
  if ($plugin == $plugin_file) {

      $add = array('add' => '<a href="post-new.php?post_type=spb">' . __('Add PopUp', 'General') . '</a>');      
    
        $actions = array_merge($add, $actions);
      
    }
    
    return $actions;
}
?>