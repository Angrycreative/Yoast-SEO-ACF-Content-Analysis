<?php
/*
Plugin Name: ACF-Content Analysis for Yoast SEO
Plugin URI: http://angrycreative.se
Description: This plugin ensures that Yoast SEO analysize all ACF content including Flexible Content and Repeaters
Version: 1.1
Author: ViktorFroberg, marol87, pekz0r, angrycreative
Author URI: http://angrycreative.se
License: GPL
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define('AC_SEO_ACF_ANALYSIS_PLUGIN_SLUG', 'ac-yoast-seo-acf-content-analysis');
define('AC_SEO_ACF_ANALYSIS_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('AC_SEO_ACF_ANALYSIS_PLUGIN_URL', plugins_url('', __FILE__).'/');
define('AC_SEO_ACF_ANALYSIS_PLUGIN_NAME', untrailingslashit(plugin_basename(__FILE__)));

class AC_Yoast_SEO_ACF_Content_Analysis
{
    /**
     * Plugin version, used for autoatic updates and for cache-busting of style and script file references.
     *
     * @since    0.1.0
     * @var     string
     */
    const VERSION = '1.0';
    /**
     * Unique identifier for the plugin.
     * This value is used as the text domain when internationalizing strings of text. It should
     * match the Text Domain file header in the main plugin file.
     *
     * @since    0.1.0
     * @var      string
     */
    public $plugin_slug = 'ysacf';
    
    /**
     * Holds the global `$pagenow` variable's value.
     *
     * @since    1.1.0
     * @var string
     */
    private $pagenow;

    /**
     * variable containing places where the plugin will fetch acf data
     *
     * @since    1.1.0
     * @var array
     */
    private $analysize_page_types = array(
            'edit-tags.php',
            'post.php',
            'post-new.php',

        );

    function __construct(){
        
        
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action( 'admin_print_scripts-post-new.php', array($this, 'enqueue_admin_scripts') , 999 );
        add_action( 'admin_print_scripts-post.php', array($this, 'enqueue_admin_scripts'), 999 );
        add_action( 'wp_ajax_' . $this->plugin_slug . '_get_fields', array($this, 'ajax_get_fields') );

        $this->pagenow = $GLOBALS['pagenow'];
    }
    function get_excluded_fields() {
        return apply_filters( 'ysacf_exclude_fields', array() );
    }
    /**
     * Filter what ACF Fields not to score
     * @param field name array
     */

    function get_field_data($fields) {

        $values = $this->get_values( $fields );
        $data = '';
        
        foreach($fields as $key =>$item) {
            
            if(in_array($key, $this->get_excluded_fields()) ){
                continue;
            } else {
                switch(gettype($item)) {
                    case 'string':
                        if (preg_match('/(\.jpg|\.png|\.bmp)$/', $item)) {
                            $data = $data.' <img src="'.$item .'">';
                        } else {
                            $data = $data.' '.$item;    
                        }
                        
                        break;
                    case 'array':
                        if($key === 'sizes' && isset($item['sizes']['thumbnail'])) {
                            // put all images in img tags for scoring.
                            $data = $data.' <img src="'.$item['sizes']['thumbnail'];    
                        } else {

                            $data = $data.' '.$this->get_field_data($item);
                        }
                        
                        break;
                }
            }
        }
        
        return $data;
    }

    function get_values($array) {
        $value_array = array_values($array);
        return $value_array;
    }

    function ajax_get_fields() {
        $pid = $_POST['postId'];
        
        $fields = get_fields( $pid );

        wp_send_json( $this->get_field_data( $fields ) );   
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since     0.1.0
     */
    public function enqueue_admin_scripts() {

        if( in_array( $this->pagenow, $this->analysize_page_types ) ) {

            // if this is a taxonomy, get the taxonomy id else get the post id
            if($this->pagenow === 'edit-tags.php' ) {
                $id = $_GET['taxonomy'] . '_' . $_GET['tag_ID'];
                wp_enqueue_script($this->plugin_slug, AC_SEO_ACF_ANALYSIS_PLUGIN_URL . 'yoast-seo-plugin.js', array('jquery', 'yoast-seo', 'wp-seo-term-scraper'), self::VERSION);    
            } else {
                global $post;
                $id = $post->ID;
                wp_enqueue_script($this->plugin_slug, AC_SEO_ACF_ANALYSIS_PLUGIN_URL . 'yoast-seo-plugin.js', array('jquery', 'yoast-seo', 'wp-seo-post-scraper'), self::VERSION);    
            }

            
            
            wp_localize_script($this->plugin_slug, 'yoast_acf_settings', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'id' => $id,
                'ajax_action' => $this->plugin_slug . '_get_fields'
            ));
        }
        
    }


}

new AC_Yoast_SEO_ACF_Content_Analysis();

