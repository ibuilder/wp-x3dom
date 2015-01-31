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
/* Enqueue scripts
/*-------------------------------------------------------*/
$WPxtdViewer = new xtdViewer();
class xtdViewer {
  protected $turl;
  protected $tkey;
  protected $murl;
  protected $mloc;
  protected $mwidth;
  protected $mheight;
  public function __construct() {
    add_action( 'wp_enqueue_scripts', array($this, 'xtd_scripts'), 10, 0  );
    add_action( 'wp_footer', array($this, 'xtd_fscript'));
    add_shortcode('x3d', array($this, 'xtd_shortcode'));
  }
  public function xtd_scripts() {
    wp_register_script('x3dom', plugins_url('x3dom.js', __FILE__), false);
    wp_register_style('x3dom', plugins_url('x3dom.css', __FILE__), false);
    wp_enqueue_script('x3dom');
    wp_enqueue_style('x3dom');
  }
public function strLength($str,$len){ 
      $length = strlen($str); 
      if($length > $len){ 
          return substr($str,0,$len).'...'; 
      }else{ 
          return $str; 
      } 
  } 
public function xtd_shortcode( $atts ) {
  extract( shortcode_atts( array(
    'url' => plugins_url('hello_kitty.x3d', __FILE__),
    'width' => '800',
    'height' => '600'
  ), $atts, 'x3d' ) );
  $filestring = self::strLength(basename($url, ".js"),10); 
  $this->tkey = 'em_mdl-'.$filestring;
  if ( $this->turl == '' ) {
    $this->turl = $url;
    set_transient( $tkey, $this->turl, 60 * 60 * 24 );
  }
  $this->turl = get_transient( $tkey );
  $this->murl = $url;
  $this->mwidth = $width;
  $this->mheight = $height;
  $v = file_get_contents($url);
  return $v;
}
public function xtd_fscript() {
    ?>
    <script id="xtdviewer">
  </script>
  <?php
  }
}