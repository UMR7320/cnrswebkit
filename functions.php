<?php
/**
* C. SEGUINOT TRIED TO activate shortcodes!!
* Allow Pods Templates to use shortcodes
*
* NOTE: Will only work if the constant PODS_SHORTCODE_ALLOW_SUB_SHORTCODES is defined and set to
  true, which by default it IS NOT.
*/
add_filter( 'pods_shortcode', function( $tags )  {
  $tags[ 'shortcodes' ] = true;
  
  return $tags;
  
});

// define the upgrader_pre_download callback to stop upgrade of a git project

// apply_filters( 'upgrader_pre_download', bool $reply, string $package, WP_Upgrader $this )
function filter_upgrader_pre_download( $false, $package, $instance ) { 
    // var_dump($package);die();
    return $false; 
}; 
             
// add the filter 
add_filter( 'upgrader_pre_download', 'filter_upgrader_pre_download', 10, 3 ); 
    
    
// C.SEGUINOT TEMPORAIRE!!! Acunetix Vulnerable Javascript library 
// This will replace core jQuery version and instead load version 3.1.1 from Google's server.
function replace_core_jquery_version() {
	wp_deregister_script( 'jquery' );
	// Change the URL if you want to load a local copy of jQuery from your own server.
	wp_register_script( 'jquery', "https://code.jquery.com/jquery-3.1.1.min.js", array(), '3.1.1' );
}
add_action( 'wp_enqueue_scripts', 'replace_core_jquery_version' );
	
// Disable use XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );


// C. Seguinot: Pensez à masquer la version de votre WordPress, car elle donne des informations aux hackers
remove_action("wp_head", "wp_generator");
	

/**
 * CNRS Web Kit functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package Atos
 * @subpackage CNRS_Web_Kit
 * @since CNRS Web Kit 1.0
 */
/**
 * CNRS Web Kit only works in WordPress 4.4 or later.
 */
if (version_compare($GLOBALS['wp_version'], '4.4-alpha', '<')) {
    require get_template_directory() . '/inc/back-compat.php';
}

if (!function_exists('cnrswebkit_setup')) :

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     *
     * Create your own cnrswebkit_setup() function to override in a child theme.
     *
     * @since CNRS Web Kit 1.0
     */
    function cnrswebkit_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/cnrswebkit
         * If you're building a theme based on CNRS Web Kit, use a find and replace
         * to change 'cnrswebkit' to the name of your theme in all the template files
         */
    
	   // C. Seguinot Add text domain support will load language file named as /languages/cnrswebkit-fr_FR.mo
	   $locale = apply_filters( 'theme_locale', get_locale(), 'cnrswebkit' );
	   $path = get_template_directory() . '/languages/cnrswebkit-' .$locale . '.mo'; 
	   $temp= load_textdomain('cnrswebkit', $path);
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for custom logo.
         *
         *  @since CNRS Web Kit 1.2
         */
        add_theme_support('custom-logo', array(
            'height' => 240,
            'width' => 240,
            'flex-height' => true,
        ));

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(1200, 9999);

        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(array(
            'primary' => __('Primary Menu', 'cnrswebkit'),
            'secondary' => __('Secondary Menu', 'cnrswebkit'),
            'social' => __('Social Links Menu', 'cnrswebkit'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'status',
            'audio',
            'chat',
        ));

        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style(array('css/editor-style.css', cnrswebkit_fonts_url()));

        // Indicate widget sidebars can use selective refresh in the Customizer.
        add_theme_support('customize-selective-refresh-widgets');
    }

endif; // cnrswebkit_setup
add_action('after_setup_theme', 'cnrswebkit_setup');

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since CNRS Web Kit 1.0
 */
function cnrswebkit_content_width() {
    $GLOBALS['content_width'] = apply_filters('cnrswebkit_content_width', 840);
}

add_action('after_setup_theme', 'cnrswebkit_content_width', 0);

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since CNRS Web Kit 1.0
 */
function cnrswebkit_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'cnrswebkit'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here to appear in your sidebar.', 'cnrswebkit'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => __('Content Bottom 1', 'cnrswebkit'),
        'id' => 'sidebar-2',
        'description' => __('Appears at the bottom of the content on posts and pages.', 'cnrswebkit'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => __('Content Bottom 2', 'cnrswebkit'),
        'id' => 'sidebar-3',
        'description' => __('Appears at the bottom of the content on posts and pages.', 'cnrswebkit'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'cnrswebkit_widgets_init');

if (!function_exists('cnrswebkit_fonts_url')) :

    /**
     * Register Google fonts for CNRS Web Kit.
     *
     * Create your own cnrswebkit_fonts_url() function to override in a child theme.
     *
     * @since CNRS Web Kit 1.0
     *
     * @return string Google fonts URL for the theme.
     */
    function cnrswebkit_fonts_url() {
        $fonts_url = '';
        $fonts = array();
        $subsets = 'latin,latin-ext';

        /* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Merriweather font: on or off', 'cnrswebkit')) {
            $fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
        }

        /* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Montserrat font: on or off', 'cnrswebkit')) {
            $fonts[] = 'Montserrat:400,700';
        }

        /* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Inconsolata font: on or off', 'cnrswebkit')) {
            $fonts[] = 'Inconsolata:400';
        }

        if ($fonts) {
            $fonts_url = add_query_arg(array(
                'family' => urlencode(implode('|', $fonts)),
                'subset' => urlencode($subsets),
                    ), 'https://fonts.googleapis.com/css');
        }

        return $fonts_url;
    }

endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since CNRS Web Kit 1.0
 */
function cnrswebkit_javascript_detection() {
    echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}

add_action('wp_head', 'cnrswebkit_javascript_detection', 0);

/**
 * Enqueues scripts and styles.
 *
 * @since CNRS Web Kit 1.0
 */
function cnrswebkit_scripts() {
    // Add Genericons, used in the main stylesheet.
    wp_enqueue_style('genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1');

    // Theme stylesheet.
    wp_enqueue_style('cnrswebkit-style', get_stylesheet_uri());

    // Load the Internet Explorer specific stylesheet.
    wp_enqueue_style('cnrswebkit-ie', get_template_directory_uri() . '/css/ie.css', array('cnrswebkit-style'), '20160816');
    wp_style_add_data('cnrswebkit-ie', 'conditional', 'lt IE 10');

    // Load the Internet Explorer 8 specific stylesheet.
    wp_enqueue_style('cnrswebkit-ie8', get_template_directory_uri() . '/css/ie8.css', array('cnrswebkit-style'), '20160816');
    wp_style_add_data('cnrswebkit-ie8', 'conditional', 'lt IE 9');

    // Load the Internet Explorer 7 specific stylesheet.
    wp_enqueue_style('cnrswebkit-ie7', get_template_directory_uri() . '/css/ie7.css', array('cnrswebkit-style'), '20160816');
    wp_style_add_data('cnrswebkit-ie7', 'conditional', 'lt IE 8');

    // Load the html5 shiv.
    wp_enqueue_script('cnrswebkit-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3');
    wp_script_add_data('cnrswebkit-html5', 'conditional', 'lt IE 9');

    wp_enqueue_script('cnrswebkit-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160816', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    if (is_singular() && wp_attachment_is_image()) {
        wp_enqueue_script('cnrswebkit-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array('jquery'), '20160816');
    }

    wp_enqueue_script('cnrswebkit-script', get_template_directory_uri() . '/js/functions.js', array('jquery'), '20160816', true);

    wp_localize_script('cnrswebkit-script', 'screenReaderText', array(
        'expand' => __('expand child menu', 'cnrswebkit'),
        'collapse' => __('collapse child menu', 'cnrswebkit'),
    ));
}

add_action('wp_enqueue_scripts', 'cnrswebkit_scripts');

/**
 * Adds custom classes to the array of body classes.
 *
 * @since CNRS Web Kit 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (May be) filtered body classes.
 */
function cnrswebkit_body_classes($classes) {
    // Adds a class of custom-background-image to sites with a custom background image.
    if (get_background_image()) {
        $classes[] = 'custom-background-image';
    }

    // Adds a class of group-blog to sites with more than 1 published author.
    if (is_multi_author()) {
        $classes[] = 'group-blog';
    }

    // Adds a class of no-sidebar to sites without active sidebar.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    return $classes;
}

add_filter('body_class', 'cnrswebkit_body_classes');

/**
 * Converts a HEX value to RGB.
 *
 * @since CNRS Web Kit 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function cnrswebkit_hex2rgb($color) {
    $color = trim($color, '#');

    if (strlen($color) === 3) {
        $r = hexdec(substr($color, 0, 1) . substr($color, 0, 1));
        $g = hexdec(substr($color, 1, 1) . substr($color, 1, 1));
        $b = hexdec(substr($color, 2, 1) . substr($color, 2, 1));
    } else if (strlen($color) === 6) {
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
    } else {
        return array();
    }

    return array('red' => $r, 'green' => $g, 'blue' => $b);
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since CNRS Web Kit 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function cnrswebkit_content_image_sizes_attr($sizes, $size) {
    $width = $size[0];

    840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

    if ('page' === get_post_type()) {
        840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
    } else {
        840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
        600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
    }

    return $sizes;
}

add_filter('wp_calculate_image_sizes', 'cnrswebkit_content_image_sizes_attr', 10, 2);

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since CNRS Web Kit 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function cnrswebkit_post_thumbnail_sizes_attr($attr, $attachment, $size) {
    if ('post-thumbnail' === $size) {
        is_active_sidebar('sidebar-1') && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
        !is_active_sidebar('sidebar-1') && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
    }
    return $attr;
}

add_filter('wp_get_attachment_image_attributes', 'cnrswebkit_post_thumbnail_sizes_attr', 10, 3);

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since CNRS Web Kit 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function cnrswebkit_widget_tag_cloud_args($args) {
    $args['largest'] = 1;
    $args['smallest'] = 1;
    $args['unit'] = 'em';
    return $args;
}

add_filter('widget_tag_cloud_args', 'cnrswebkit_widget_tag_cloud_args');

remove_action('wp_head', 'wp_generator');

/**
 * Loading of specific pages functions.
 */
require get_template_directory() . '/inc/inc-pages-functions.php';


// C. Seguinot Admin Taxonomy Filter (based on Plugin https://wordpress.org/plugins/admin-taxonomy-filter/)
if ( is_admin() ) {
	require_once dirname( __FILE__ ) . '/inc/class-atf2-controller.php';
	require_once dirname( __FILE__ ) . '/inc/class-atf2-settings.php';
	$controller = new ATF2_Controller;
	$controller->init();
	$settings = new ATF2_Settings;
	$settings->init();
}