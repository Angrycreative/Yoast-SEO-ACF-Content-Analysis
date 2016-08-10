<?php
/*
Plugin Name: ACF Content Analysis for Yoast SEO
Plugin URI: http://angrycreative.se
Description: Ensure that Yoast SEO analysize all ACF content including Flexible Content and Repeaters.
Version: 1.2.5
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
     * Plugin version, used for automatic updates and for cache-busting of style and script file references.
     *
     * @since    0.1.0
     * @var     string
     */
    const VERSION = '1.2.5';
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
    private $pagenow = '';

    /**
     * variable containing places where the plugin will fetch acf data
     *
     * @since    1.1.0
     * @var array
     */
    private $analysize_page_types = array(
            'term.php',
            'post.php',
            'edit-tags.php', // will be removed in future versions of the plugin.
            'post-new.php',
        );

    function __construct(){

        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action( 'admin_print_scripts-post-new.php', array($this, 'enqueue_admin_scripts') , 999 );
        add_action( 'admin_print_scripts-post.php', array($this, 'enqueue_admin_scripts'), 999 );
        add_action( 'wp_ajax_' . $this->plugin_slug . '_get_fields', array($this, 'ajax_get_fields') );
        if(isset($GLOBALS['pagenow'])) {
            $this->pagenow = $GLOBALS['pagenow'];
        }

    }
    function get_excluded_fields() {
        return apply_filters( 'ysacf_exclude_fields', array() );
    }
    /**
     * Filter what ACF Fields not to score
     * @param field name array
     */

    function get_field_data($fields) {

        $data = '';
        if(!empty($fields)) {
            foreach($fields as $key =>$item) {

                if(in_array((string)$key, $this->get_excluded_fields()) ){
                    continue;
                } else {
                    switch(gettype($item)) {
                        case 'string':
                            $data = $data.' '.$item;
                            break;

                        case 'array':
                            if(isset($item['sizes']['thumbnail'])) {
                                // put all images in img tags for scoring.
                                $alt = '';
                                if(isset($item['alt'])) {
                                    $alt = $item['alt'];
                                }
                                $title = '';
                                if(isset($item['title'])) {
                                    $title = $item['title'];
                                }
                                $data = $data.' <img src="'.$item['sizes']['thumbnail'] . '" alt="' . $alt .'" title="' . $title . '"/>';
                            } else {

                                $data = $data.' '.$this->get_field_data($item);
                            }

                            break;
                    }
                }
            }
        }


        return $data;
    }

    function ajax_get_fields() {

        $pid = filter_input(INPUT_POST, 'postId', FILTER_SANITIZE_STRING);

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
            if($this->pagenow === 'term.php' || $this->pagenow === 'edit-tags.php') {

                $id = filter_input(INPUT_GET, 'taxonomy', FILTER_SANITIZE_STRING) . '_' . filter_input(INPUT_GET, 'tag_ID', FILTER_SANITIZE_NUMBER_INT);

            } else {
                global $post;
                $id = $post->ID;
            }

            wp_enqueue_script($this->plugin_slug, AC_SEO_ACF_ANALYSIS_PLUGIN_URL . 'yoast-seo-plugin.js', array('jquery'), self::VERSION);

            wp_localize_script($this->plugin_slug, 'yoast_acf_settings', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'id' => $id,
                'ajax_action' => $this->plugin_slug . '_get_fields'
            ));
        }

    }

}

add_action( 'plugins_loaded', 'init_ysacf' );

function init_ysacf() {
    new AC_Yoast_SEO_ACF_Content_Analysis();
}
