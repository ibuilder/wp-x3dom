<?php
/**
 * Plugin Name: x3Dom viewer
Plugin URI: http://turneremanager.com
Description: xtd viewer using three.js for CAD files
Author: Matthew M. Emma & Robert Carmosino
Version: 0.4
Author URI: http://www.turneremanager.com
Credits: va3c - http://va3c.github.io/
*/
/*-------------------------------------------------------*/
/* Add Model Post Type
/*-------------------------------------------------------*/
function register_xthreedom_posttype() {
    // Custom Post Type Labels      
    $labels = array(
      'name'               => _x( 'Models', 'post type general name' ),
      'singular_name'      => _x( 'Model', 'post type singular name' ),
      'add_new'            => _x( 'Add new', 'xthreedom' ),
      'add_new_item'       => __( 'Add new Model' ),
      'edit_item'          => __( 'Edit Model' ),
      'new_item'           => __( 'New Model' ),
      'all_items'          => __( 'Models' ),
      'view_item'          => __( 'View Model' ),
      'search_items'       => __( 'Search Models' ),
      'not_found'          => __( 'No Model found' ),
      'not_found_in_trash' => __( 'No Model found in trash' ),
      'parent_item_colon'  => __( 'Parent Model' ),
      'menu_name'          => __( 'Models' )
    );

    // Custom Post Type Capabilities  
    $capabilities = array(
      'edit_post'          => 'edit_post',
      'edit_posts'         => 'edit_posts',
      'edit_others_posts'  => 'edit_others_posts',
      'publish_posts'      => 'publish_posts',
      'read_post'          => 'read_post',
      'read_private_posts' => 'read_private_posts',
      'delete_post'        => 'delete_post'
    );

    // Custom Post Type Taxonomies  
    $taxonomies = array();

    // Custom Post Type Supports  
    $supports = array('title', 'trackbacks', 'comments', 'revisions', 'post-formats');

    // Custom Post Type Arguments  
    $args = array(
        'labels'             => $labels,
        'hierarchical'       => true,
        'description'        => 'Models',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => false,
        'show_in_admin_bar'  => false,
        'exclude_from_search'=> true,
        'query_var'          => true,
        'rewrite'            => true,
        'can_export'         => true,
        'has_archive'        => true,
        'menu_position'      => 100,
        'taxonomies'   => $taxonomies,
        'supports'           => $supports,
        //'capabilities'   => $capabilities,
        'capability_type'    => 'page'
    );
    register_post_type('xthreedom', $args );
  }
add_action('init', 'register_xthreedom_posttype');
/*-------------------------------------------------------*/
/* Verify Dependendencies
/*-------------------------------------------------------*/
if( !function_exists( 'acf_form' ) ) 
{
  add_action('admin_notices', 'x3dom_plugin_notice');
}
function x3dom_plugin_notice(){    
   echo '<div class="updated"><p>Congrats on installing the <i>x3d viewer</i> Plugin for Wordpress.  This plugin requires the following plugins installed on your wp site to work properly:
      <ul>
        <li><a href="https://wordpress.org/plugins/advanced-custom-fields/">Advanced Custom Fields</a></li>
        <li><a href="https://wordpress.org/plugins/advanced-custom-fields-code-area-field/">ACF Code Area Field</a></li>
      </ul>
   </p></div>';
}
add_action('acf/register_fields', 'my_register_fields');

function my_register_fields()
{
    include_once('acf_code_area-field/acf_code_area-v3.php');
}
/*-------------------------------------------------------*/
/* Add custom meta
/*-------------------------------------------------------*/
if(function_exists("register_field_group"))
{
  register_field_group(array (
    'id' => 'acf_x3dom',
    'title' => 'x3Dom',
    'fields' => array (
      array (
        'key' => 'field_54cd7c39a40a3',
        'label' => 'x3d Code',
        'name' => 'x3d_code',
        'type' => 'textarea',
        'default_value' => '',
        'placeholder' => '',
        'maxlength' => '',
        'rows' => '',
        'formatting' => 'br',
      ),
      array (
        'key' => 'field_54cd7e87fb551',
        'label' => 'Date Updated',
        'name' => 'date_updated',
        'type' => 'date_picker',
        'date_format' => 'yymmdd',
        'display_format' => 'dd/mm/yy',
        'first_day' => 1,
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'xthreedom',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'no_box',
      'hide_on_screen' => array (
      ),
    ),
    'menu_order' => 0,
  ));
}

/*-------------------------------------------------------*/
/* Enqueue scripts
/*-------------------------------------------------------*/
add_action( 'wp_enqueue_scripts', 'xtd_scripts', 10, 0  );
function xtd_scripts() {
    wp_register_script('x3dom', plugins_url('x3dom.js', __FILE__), false);
    wp_register_script('x3ddebug', plugins_url('x3dom.debug.js', __FILE__), false);
    wp_register_style('x3dom', plugins_url('x3dom.css', __FILE__), false);
    wp_enqueue_script('x3dom');
    wp_enqueue_style('x3dom');
    wp_enqueue_script('x3ddebug');
}

add_shortcode('x3d', 'xtd_shortcode');
function xtd_shortcode( $atts ) {
  extract( shortcode_atts( array(
    'id' => '27',
    'width' => '800',
    'height' => '600'
  ), $atts, 'x3d' ) );
  $x3dcode = get_field('x3d_code',$id, false);
  return $x3dcode;
}