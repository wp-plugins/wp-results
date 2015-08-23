<?php
/*
Plugin Name: WP Results
Plugin URI: https://github.com/dadmor/Results
Description: Results will be amazing!!!
Author: gdurtan
Author URI: https://pl.linkedin.com/pub/grzegorz-durtan/11/b74/296
Version: 0.0.5
License: GPL2
*/

define("PLUGIN_SANDF_DIR", dirname(__FILE__));
define("PLUGIN_SANDF_URI", plugin_dir_url( __FILE__ ));

/* Include better manage wp options */
include plugin_dir_path( __FILE__ ).'class/wp-options-manager.class.php';
$R_OPTIONS = new wp_options_manager();
$R_OPTIONS -> register_ajax_methods();

include plugin_dir_path( __FILE__ ).'class/admin-column-frontend.class.php';
$ACOL = new admin_column_frontend();

/* Include class to inject custom templates int wordpress pages */
include plugin_dir_path( __FILE__ ).'class/virtual-template.class.php';

/* Forms */
include plugin_dir_path( __FILE__ ).'class/wp-alpaca-options.class.php';
include plugin_dir_path( __FILE__ ).'inc/metabox-alpacajs-form.php';

include plugin_dir_path( __FILE__ ).'class/wp-search-and-filter.class.php';
include plugin_dir_path( __FILE__ ).'inc/widget-filter-and-search.php';

/* Inclide search and filters class */


/* widget to display loop with sidebars */
/* result widget remove admin bar ??? */
//include plugin_dir_path( __FILE__ ).'inc/widget-result-list.php';

include plugin_dir_path( __FILE__ ).'inc/widget-paypal-cart.php';

/* ADD CUSTOMIZE CONTROLLS TO THIS THEME */
include plugin_dir_path( __FILE__ ).'inc/customize-controlls.php';

/* ADD ENDPOINT REST API */
include plugin_dir_path( __FILE__ ).'inc/rest-endpoint-api.php';


/* Register manu and display block */
function wp_results_menu()
{  
	add_menu_page('Results', 'Results', 'administrator', 'url_wp_results', 'wp_results_callback');
	add_submenu_page('url_wp_results', 'Results GRID', 'Results GRID', 'administrator', 'url_wp_results_grid', 'add_grid_callback');
	add_submenu_page('url_wp_results', 'Results TPL parts', 'Results TPL parts', 'administrator', 'url_wp_results_parts', 'add_parts_callback');
	add_submenu_page('url_wp_results', 'Results FORMS', 'Results FORMS', 'administrator', 'url_wp_results_forms', 'add_forms_callback');
}
add_action('admin_menu', 'wp_results_menu');

function wp_results_callback() {
	wpr_admin_page( 'home' , 'WP Results plugin' );
}

function add_grid_callback(){
	wpr_reg_styles( array('skeleton-grid-creator', 'grid-creator' ) );
	wpr_reg_scripts( array('jquery-tmpl.min','grid-creator','options-manager-helpers'));
	wpr_admin_page( 'grid', 'WP Results Grid Creator' );
}

function add_parts_callback() {
	wpr_admin_page( 'add-parts', 'WP Results parts' );
}

function add_forms_callback(){
	wp_enqueue_script('jquery-ui-sortable');

	wpr_reg_scripts( array( 'alpaca-core.min', 'lodash', 'lodash-deep', 'alpacajs-ux-form-editor' ));
	wpr_reg_styles( array('alpaca.min', 'ux-form-editor-style', 'skeleton-grid-creator', 'grid-creator' ));
	wpr_admin_page('ux-form-builder', 'WP Results Form Builder' );
}

function wp_result_theme_scripts() {
	wpr_reg_styles( array('skeleton-only-grid','list-style' ) );
}
add_action( 'wp_enqueue_scripts', 'wp_result_theme_scripts' );

/* dynamic template helpers functions */
function get_skeleton_class($tpl){
	if($tpl['results_loop']){
		if(($tpl['left_bar'])&&($tpl['results_loop'])){
			$side_class = 'four';
			$loop_class = 'eight';	
		}
		if(($tpl['right_bar'])&&($tpl['results_loop'])){
			$side_class = 'four';
			$loop_class = 'eight';	
		}
		if(($tpl['left_bar'])&&($tpl['right_bar'])){
			$side_class = 'three';
			$loop_class = 'six';	
		}
	}else{
		if(($tpl['left_bar'])||($tpl['right_loop'])){
			$side_class = 'twelve';
		}
		if(($tpl['left_bar'])&&($tpl['right_loop'])){
			$side_class = 'six';
		}
	}
	return (object)array('side'=> $side_class, 'loop' => $loop_class);
}

function my_sidebar($id){
	
	//var_dump($id);
	if($id['id']){
		// TODO check it if you change template !!!!
		if ( is_active_sidebar( $id['id'] ) ) {
			dynamic_sidebar($id['name']);
		}
	}else{
		if($id == true){
			echo '&nbsp;';
		}
	}
}

/* ADMIN PAGES HELPERS METHODS */
function wpr_reg_scripts( $array ){
	foreach ( $array as $key ) {
		wp_register_script( $key, plugins_url('/js/'.$key.'.js', __FILE__) );
		wp_enqueue_script( $key );
	}
}
function wpr_reg_styles( $array ){
	foreach ( $array as $key ) {
		wp_register_style( $key, plugins_url('/css/'.$key.'.css', __FILE__) );
		wp_enqueue_style( $key );
	}
}
function wpr_admin_page( $page , $name ){
	echo '<div class="wrap"><h2>'.$name.'</h2>';
	include 'inc/plugin-page-'.$page.'.php';
	echo '</div>';
}
