<?php
/**
 * seed functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package seed
 */

/* LAYOUT */
if (!isset($GLOBALS['s_blog_layout']))          {$GLOBALS['s_blog_layout']          = 'full-width';}    // full-width, leftbar, rightbar
if (!isset($GLOBALS['s_blog_layout_single']))   {$GLOBALS['s_blog_layout_single']   = 'full-width';}    // full-width, leftbar, rightbar
if (!isset($GLOBALS['s_blog_columns_m']))       {$GLOBALS['s_blog_columns_m']       = '1';}             // 1,2,3
if (!isset($GLOBALS['s_blog_columns_d']))       {$GLOBALS['s_blog_columns_d']       = '3';}             // 1,2,3,4,5,6
if (!isset($GLOBALS['s_blog_columns_d_style'])) {$GLOBALS['s_blog_columns_d_style'] = 'card';}          // list, card, caption
if (!isset($GLOBALS['s_blog_profile']))         {$GLOBALS['s_blog_profile']         = 'enable';}        // disable, enable
if (!isset($GLOBALS['s_shop_layout']))          {$GLOBALS['s_shop_layout']          = 'full-width';}    // full-width, leftbar, rightbar
if (!isset($GLOBALS['s_shop_pagebuilder']))     {$GLOBALS['s_shop_pagebuilder']     = 'disable';}       // disable, enable
if (!isset($GLOBALS['s_logo_path']))            {$GLOBALS['s_logo_path']            = 'none';}          // theme folder relative path, such as img/logo.svg .
if (!isset($GLOBALS['s_logo_width']))           {$GLOBALS['s_logo_width']           = '200';}           // any number, use in Custom Logo
if (!isset($GLOBALS['s_logo_height']))          {$GLOBALS['s_logo_height']          = '100';}           // any number, use in Custom Logo
if (!isset($GLOBALS['s_thumb_width']))          {$GLOBALS['s_thumb_width']          = '360';}           // any number
if (!isset($GLOBALS['s_thumb_height']))         {$GLOBALS['s_thumb_height']         = '189';}           // any number
if (!isset($GLOBALS['s_thumb_crop']))           {$GLOBALS['s_thumb_crop']           = true;}            // true, false
if (!isset($GLOBALS['s_title_style']))          {$GLOBALS['s_title_style']          = 'banner';}        // minimal, banner

/* ADD-ON */
if (!isset($GLOBALS['s_member_url']))           {$GLOBALS['s_member_url']           = 'none';}          // none, url path such as: /my-account/
if (!isset($GLOBALS['s_member_label']))         {$GLOBALS['s_member_label']         = 'Member';}        // ex: Member, สมาชิก
if (!isset($GLOBALS['s_keen_slider']))          {$GLOBALS['s_keen_slider']          = 'enable';}        // disable, enable
if (!isset($GLOBALS['s_style_css']))            {$GLOBALS['s_style_css']            = 'disable';}       // disable, enable
if (!isset($GLOBALS['s_jquery']))               {$GLOBALS['s_jquery']               = 'disable';}       // disable, enable
if (!isset($GLOBALS['s_fontawesome']))          {$GLOBALS['s_fontawesome']          = 'disable';}       // disable, enable
if (!isset($GLOBALS['s_wp_comments']))          {$GLOBALS['s_wp_comments']          = 'disable';}       // disable, enable
if (!isset($GLOBALS['s_admin_bar']))            {$GLOBALS['s_admin_bar']            = 'hide';}          // hide, show

/* CHECK WOOCOMMERCE */
include_once ABSPATH . 'wp-admin/includes/plugin.php';
if (is_plugin_active('woocommerce/woocommerce.php')) {
    $GLOBALS['s_is_woo']       = true;
    $GLOBALS['s_member_url']   = '/my-account/';
} else {
    $GLOBALS['s_is_woo']       = false;
}

/* Admin Bar */
if ($GLOBALS['s_admin_bar'] == 'hide') {
    add_filter('show_admin_bar', '__return_false');
}

/**
 * Setup Theme
 */
if (!function_exists('seed_setup')) {
    function seed_setup()
    {
        load_theme_textdomain('seed', get_template_directory() . '/languages');
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('custom-logo', array(
            'width'       => $GLOBALS['s_logo_width'],
            'height'      => $GLOBALS['s_logo_height'],
            'flex-width'  => true,
            'flex-height' => true,
        ));
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size($GLOBALS['s_thumb_width'], $GLOBALS['s_thumb_height'], $GLOBALS['s_thumb_crop']);
        register_nav_menus(array(
            'primary' => esc_html__('Main Menu', 'seed'),
            'mobile'  => esc_html__('Mobile Menu', 'seed'),
            'top-menu'  => esc_html__('Top Menu', 'seed'),
        ));

        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
        add_theme_support('custom-background');
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('align-wide');
    }
}
add_action('after_setup_theme', 'seed_setup');

function seed_content_width()
{
    $GLOBALS['content_width'] = apply_filters('seed_content_width', 750);
}
add_action('after_setup_theme', 'seed_content_width', 0);

/**
 * Register widget area.
 */
function seed_widgets_init()
{
    register_sidebar(array(
        'name'          => esc_html__('Footbar', 'seed'),
        'id'            => 'footbar',
        'description'   => '',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

}
add_action('widgets_init', 'seed_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function seed_scripts()
{
    wp_enqueue_style('s-mobile', get_theme_file_uri('/css/mobile.css'), array(), false);
    wp_enqueue_style('s-desktop', get_theme_file_uri('/css/desktop.css'), array(), false , '(min-width: 1025px)');

    if ($GLOBALS['s_style_css'] == 'enable') {
        wp_enqueue_style('s-style', get_stylesheet_uri());
    }
    wp_enqueue_script('s-scripts', get_theme_file_uri('/js/scripts.js'), array(), false, true);
    
    if ($GLOBALS['s_jquery'] == 'enable') {
        wp_enqueue_script('s-jquery', get_theme_file_uri('/js/main-jquery.js'), array('jquery'), false, true);
    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'seed_scripts');

/**
 * Add backend styles for Gutenberg.
 */
add_action('enqueue_block_editor_assets', 'seed_add_gutenberg_assets');
function seed_add_gutenberg_assets()
{
    wp_enqueue_style('seed-gutenberg', get_theme_file_uri('/css/wp-gutenberg.css'), false);
}

/**
 * Admin CSS
 */
function seed_admin_style()
{
    wp_enqueue_style('seed-admin-style', get_template_directory_uri() . '/css/wp-admin.css');
}
add_action('admin_enqueue_scripts', 'seed_admin_style');


/**
 * Remove "Category: ", "Tag: ", "Taxonomy: " from archive title
 */
add_filter('get_the_archive_title', 'seed_get_the_archive_title');
function seed_get_the_archive_title($title)
{
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }
    return $title;
}

/**
 * Custom WooCommerce Settings
 */
if ($GLOBALS['s_is_woo']) {
    require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Custom Shortcode
 */
require get_template_directory() . '/inc/shortcode.php';

/**
 * Redirect after login -  to current page
 */
function seed_redirect_to_request( $redirect_to, $request, $user ){
    return $request;
}
if($GLOBALS['s_member_url'] != 'none') {  
    add_filter('login_redirect', 'seed_redirect_to_request', 10, 3);
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
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {require get_template_directory() . '/inc/jetpack.php';}


//
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
	));
}


/*
     ####  #####    ##   #####  #    #  ####  #      
    #    # #    #  #  #  #    # #    # #    # #      
    #      #    # #    # #    # ###### #    # #      
    #  ### #####  ###### #####  #    # #  # # #      
    #    # #   #  #    # #      #    # #   #  #      
     ####  #    # #    # #      #    #  ### # ###### 
*/
add_action('graphql_register_types', function() {

    register_graphql_field('Page', 'greenshiftInlineCss', [
        'type' => 'String',
        'description' => 'Greenshift-generated inline CSS from wp_head',
        'resolve' => function($post) {
        return get_inline_css_by_id($post->ID, 'greenshift-post-css-inline-css');
        }
    ]);
    register_graphql_field('Event', 'greenshiftInlineCss', [
        'type' => 'String',
        'description' => 'Greenshift-generated inline CSS from wp_head',
        'resolve' => function($post) {
        return get_inline_css_by_id($post->ID, 'greenshift-post-css-inline-css');
        }
    ]);
    register_graphql_field('Post', 'greenshiftInlineCss', [
        'type' => 'String',
        'description' => 'Greenshift-generated inline CSS from wp_head',
        'resolve' => function($post) {
        return get_inline_css_by_id($post->ID, 'greenshift-post-css-inline-css');
        }
    ]);
    register_graphql_field('RootQuery', 'globalInlineCss', [
        'type' => 'String',
        'description' => 'Global CSS from <style id="global-styles-inline-css">',
        'resolve' => function() {
            return get_inline_css_by_id(null, 'global-styles-inline-css');
        }
    ]);
    register_graphql_field('RootQuery', 'sMobileCssUrl', [
        'type' => 'String',
        'description' => 'URL of mobile.css',
        'resolve' => fn() => get_enqueued_style_url('s-mobile')
    ]);

    register_graphql_field('RootQuery', 'sDesktopCssUrl', [
        'type' => 'String',
        'description' => 'URL of desktop.css',
        'resolve' => fn() => get_enqueued_style_url('s-desktop')
    ]);
});

function get_inline_css_by_id($post_id = null, $style_id) {
    if ($post_id) {
        $post = get_post($post_id);
        if (!$post) return null;
        setup_postdata($post);
    }

    ob_start();
    do_action('wp_enqueue_scripts');
    do_action('wp_head');
    $head_output = ob_get_clean();

    if ($post_id) wp_reset_postdata();

    if (preg_match('/<style[^>]+id=["\']' . preg_quote($style_id, '/') . '["\'][^>]*>(.*?)<\/style>/is', $head_output, $match)) {
        return trim($match[1]);
    }

    return null;
}

function get_enqueued_style_url($handle) {
  do_action('wp_enqueue_scripts'); // ensure styles are loaded
  global $wp_styles;

  return isset($wp_styles->registered[$handle])
    ? $wp_styles->registered[$handle]->src
    : null;
}

// Make SVG code work
add_filter( 'wp_kses_allowed_html', 'acf_add_allowed_svg_tag', 10, 2 );
function acf_add_allowed_svg_tag( $tags, $context ) {
    if ( $context === 'acf' ) {
        $tags['svg']  = array(
            'xmlns'       => true,
            'fill'        => true,
            'viewbox'     => true,
            'role'        => true,
            'aria-hidden' => true,
            'focusable'   => true,
        );
        $tags['path'] = array(
            'd'    => true,
            'fill' => true,
        );
    }

    return $tags;
}


// Ensure proper GraphQL exposure
add_action('init', function() {
    register_post_type('wp_block', [
        'show_in_graphql' => true,
        'graphql_single_name' => 'reusableBlock',
        'graphql_plural_name' => 'reusableBlocks',
        'show_in_rest' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'supports' => ['title', 'editor', 'custom-fields'],
        'capability_type' => 'post',
        'map_meta_cap' => true,
    ]);
}, 20);

// Force include in GraphQL if still not showing
add_filter('wpgraphql_post_typename', function($typename, $post) {
    if ($post->post_type === 'wp_block') {
        return 'ReusableBlock';
    }
    return $typename;
}, 10, 2);



/* === Social Media Block === */
if (function_exists('acf_register_block_type')) {
    add_action( 'acf/init', 'acf_register_social_media' );
}
function acf_register_social_media() { 
    acf_register_block_type(
        array(
            'name' => 'social-media',
            'title' => 'Social Media',
            'description' => __('Display Social Media'),
            'render_template' => 'template-parts/blocks/social-media.php',
            'icon' => array(
                'foreground' => '#ffffff',
                'background' => '#0093F9',
                'src' => 'share',
            ),
            'keywords' => array('social', 'media')
        )
    );
}


/* === Menu ID Block === */
if (function_exists('acf_register_block_type')) {
    add_action( 'acf/init', 'acf_menu_id' );
}
function acf_menu_id() { 
    acf_register_block_type(
        array(
            'name' => 'Menu ID',
            'title' => 'Menu ID',
            'description' => __('Display MENU BY ID'),
            'render_template' => 'template-parts/blocks/menu-id.php',
            'icon' => array(
                'foreground' => '#ffffff',
                'background' => '#0981C4',
                'src' => 'menu-alt3',
            ),
            'keywords' => array('news')
        )
    );
}

/* === Block: Event Carousel === */
if (function_exists('acf_register_block_type')) {
    add_action( 'acf/init', 'acf_event_carousel' );
}
function acf_event_carousel() { 
    acf_register_block_type(
        array(
            'name' => 'Event Carousel',
            'title' => 'Event Carousel',
            'description' => __('Display Event Carousel'),
            'render_template' => 'template-parts/blocks/event-carousel.php',
            'icon' => array(
                'foreground' => '#ffffff',
                'background' => '#0981C4',
                'src' => 'menu-alt3',
            ),
            'keywords' => array('news')
        )
    );
}


/* === Block: News and Activity === */
if (function_exists('acf_register_block_type')) {
    add_action( 'acf/init', 'acf_news_activity' );
}
function acf_news_activity() { 
    acf_register_block_type(
        array(
            'name' => 'News Activity',
            'title' => 'News Activity',
            'description' => __('Display News Activity'),
            'render_template' => 'template-parts/blocks/news-activity.php',
            'icon' => array(
                'foreground' => '#ffffff',
                'background' => '#0981C4',
                'src' => 'menu-alt3',
            ),
            'keywords' => array('news')
        )
    );
}

// 
add_filter( 'manage_event_posts_columns', 'smashing_filter_columns_event' );
function smashing_filter_columns_event( $columns ) {
    // Insert new columns before the 'date' column
    $new_columns = [];
    foreach ( $columns as $key => $value ) {
        if ( $key === 'date' ) {
            $new_columns['startdate'] = __( 'Start Date' );
            $new_columns['enddate']   = __( 'End Date' );
        }
        $new_columns[$key] = $value;
    }
    return $new_columns;
}

// Populate the custom column content
add_action( 'manage_event_posts_custom_column', 'smashing_column_event', 10, 2 );
function smashing_column_event( $column, $post_id ) {
    if ( 'startdate' === $column ) {
        $startdate = get_field( 'event_startdate', $post_id );
        echo $startdate ? date('Y-m-d H:i', strtotime($startdate)) : '-';
    }

    if ( 'enddate' === $column ) {
        $enddate = get_field( 'event_enddate', $post_id );
        echo $enddate ? date('Y-m-d H:i', strtotime($enddate)) : '-';
    }
}