<?php
/**
 * Plugin Name: 	Media Selector test plugin
 * Plugin URI:		http://jeroensormani.com/
 * Description:		Add a media selector to your settings page.
 * Version: 		0.0.1
 * Author: 			Jeroen Sormani
 * Author URI: 		http://www.jeroensormani.com/
 */




require_once 'assets/media_plug_functions.php';

function styles_scripts() {
    wp_enqueue_style('media-style', plugins_url('/css/media-style.css', __FILE__));
    wp_enqueue_script('media-scripts', plugins_url('/js/media_script.js', __FILE__), array( 'jquery' ), rand(), true);
}
add_action( 'admin_enqueue_scripts', 'styles_scripts' );