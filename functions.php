<?php
/**
 * Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * You can override functions wrapped with function_exists() call by defining
 * them first in your child theme's functions.php file.
 *
 * @package Highend
 * @since   1.0.0
 */

/**
 * Define constants.
 */
define( 'HBTHEMES_ROOT', get_template_directory() );
define( 'HBTHEMES_URI', get_template_directory_uri() );
define( 'HBTHEMES_INCLUDES', HBTHEMES_ROOT . '/includes' );
define( 'HBTHEMES_ADMIN', HBTHEMES_ROOT . '/admin' );
define( 'HBTHEMES_FUNCTIONS', HBTHEMES_ROOT . '/functions' );
define( 'HBTHEMES_ADMIN_URI', HBTHEMES_URI . '/admin' );
define( 'HB_THEME_VERSION', wp_get_theme('HighendWP')->get( 'Version' ) );

if ( ! function_exists( 'highend_theme_setup' ) ) {

	/**
	 * Basic theme setup function.
	 * 
	 * @since 3.4.1
	 */
	function highend_theme_setup() {

		// Load textdomain.
		load_theme_textdomain( 'hbthemes', get_stylesheet_directory() . '/languages' );

		// Add theme support.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-formats', array(
			'gallery',
			'image',
			'quote',
			'video',
			'audio',
			'link'
		));

		// Add support for WooCommerce.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Register Navigations.
		register_nav_menus( array(
			'main-menu'		=> esc_html__( 'Main Menu', 	'hbthemes' ),
			'footer-menu'	=> esc_html__( 'Footer Menu', 	'hbthemes' ),
			'mobile-menu'	=> esc_html__( 'Mobile Menu', 	'hbthemes' ),
			'one-page-menu'	=> esc_html__( 'One Page Menu', 'hbthemes' ),
		) );

		// Set content width.
		if ( ! isset( $content_width ) ) {
			if ( hb_options( 'hb_content_width' ) == '940px' ) {
				$content_width = 940;
			} else {
				$content_width = 1140;
			}
		}

		global $themeoptions;

		if ( defined( 'WP_ADMIN' ) && WP_ADMIN ) {
			require_once( 'includes/tinymce/shortcode-popup.php' );
		}
	}
}
add_action( 'after_setup_theme', 'highend_theme_setup' );

/* Start Highend 3.4.1 Update */
require get_theme_file_path( 'hbframework/hbframework.php' );

/**
 * Register Widget Areas.
 *
 * @since 3.5.0
 */
function highend_widgets_init() {

	// Default Sidebar.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Default Sidebar', 'hbthemes' ),
			'id'            => 'hb-default-sidebar',
			'description'   => esc_html__( 'This is a default sidebar for widgets. You can create unlimited sidebars in Highend > Sidebar Manager. You need to select this sidebar in page meta settings to display it.','hbthemes' ),
			'before_widget' => '<div id="%1$s" class="widget-item %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>'
		)
	);

	// Side Panel Sidebar.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Side Panel Section', 'hbthemes' ),
			'id'            => 'hb-side-section-sidebar',
			'description'   => esc_html__( 'Add your widgets for the side panel section here. Make sure you have enabled the offset side panel section option in Highend Options > Layout Settings > Header Settings.','hbthemes' ),
			'before_widget' => '<div id="%1$s" class="widget-item %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>'
		)
	);

	// Sidebar Attributes.
	$sidebar_attr = array(
		'name'          => '',
		'description'   => __( 'This is an area for widgets. Drag and drop your widgets here.', 'hbthemes' ),
		'before_widget' => '<div id="%1$s" class="widget-item %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>'
	);

	$sidebar_id = 0;
	$sidebar_names = array(
		'Footer 1',
		'Footer 2',
		'Footer 3',
		'Footer 4'
	);

	foreach ( $sidebar_names as $sidebar_name ) {
		$sidebar_attr['name'] = $sidebar_name;
		$sidebar_attr['id']   = 'custom-sidebar' . $sidebar_id++;
		register_sidebar( $sidebar_attr );
	}
}
add_action( 'widgets_init', 'highend_widgets_init' );

/**
 * Enqueue and register scripts and styles.
 *
 * @since 3.5.0
 */
function highend_enqueues() {

	// Main stylesheet.
	wp_enqueue_style( 
		'highend_styles',
		get_stylesheet_uri(),
		false,
		HB_THEME_VERSION,
		'all'
	);

	// Responsive stylesheet.
	if ( hb_options( 'hb_responsive' ) ) {
		wp_enqueue_style( 
			'highend_responsive',
			HBTHEMES_URI . '/css/responsive.css',
			false,
			HB_THEME_VERSION,
			'all'
		);
	}

	// Icons.
	wp_enqueue_style(
		'highend_icomoon',
		HBTHEMES_URI . '/css/icons.css',
		false,
		HB_THEME_VERSION,
		'all'
	);

	// Dynamic CSS.
	$dynamic_css_path = HBTHEMES_ROOT . '/css/dynamic-styles.css';
	if ( wp_is_writable( $dynamic_css_path ) && ! is_customize_preview() ) {
		wp_enqueue_style(
			'highend_dynamic_styles',
			HBTHEMES_URI . '/css/dynamic-styles.css',
			false,
			filemtime( $dynamic_css_path ),
			'all'
		);
	}

	// Main script.
	wp_enqueue_script(
		'highend_scripts',
		HBTHEMES_URI . '/scripts/scripts.js',
		array( 'jquery' ),
		HB_THEME_VERSION,
		true
	);

	if ( ! highend_is_maintenance() ) {	

		wp_enqueue_script( 'highend_google_jsapi', '//www.google.com/jsapi', null, HB_THEME_VERSION, true );
		wp_enqueue_script( 'highend_map', HBTHEMES_URI . '/scripts/map.js', array( 'jquery', 'highend_google_jsapi' ), HB_THEME_VERSION, true );
		wp_enqueue_script( 'highend_mediaelement', HBTHEMES_URI . '/scripts/mediaelement/mediaelement.js', array('jquery'), HB_THEME_VERSION, true );
		wp_enqueue_script( 'highend_flexslider', HBTHEMES_URI . '/scripts/jquery.flexslider.js', array('jquery'), HB_THEME_VERSION, true );
		wp_enqueue_script( 'highend_validate', HBTHEMES_URI . '/scripts/jquery.validate.js', array('jquery'), HB_THEME_VERSION, true );
		wp_enqueue_script( 'highend_easychart', HBTHEMES_URI . '/scripts/jquery.easychart.js', array('jquery'), HB_THEME_VERSION, true );
		wp_enqueue_script( 'highend_carousel', HBTHEMES_URI . '/scripts/responsivecarousel.min.js', array('jquery'), HB_THEME_VERSION, true );
		wp_enqueue_script( 'highend_owl_carousel', HBTHEMES_URI . '/scripts/jquery.owl.carousel.min.js', array('jquery'), HB_THEME_VERSION, true );

		if ( vp_metabox( 'misc_settings.hb_onepage' ) ) {
			wp_enqueue_script( 'highend_nav', HBTHEMES_URI . '/scripts/jquery.nav.js', array('jquery'), HB_THEME_VERSION, true );
		}

		if ( hb_options( 'hb_ajax_search' ) ) {
			wp_enqueue_script( 'jquery-ui-autocomplete');	
		}

		if ( 'hb-bokeh-effect' === vp_metabox( 'featured_section.hb_featured_section_effect' ) ){
			wp_enqueue_script( 'highend_fs_effects', HBTHEMES_URI . '/scripts/canvas-effects.js', array('jquery'), HB_THEME_VERSION, true );
		} else if ( 'hb-clines-effect' === vp_metabox( 'featured_section.hb_featured_section_effect' ) ) {
			wp_enqueue_script( 'highend_cl_effects', HBTHEMES_URI . '/scripts/canvas-lines.js', array('jquery'), HB_THEME_VERSION, true );
		}
	}

	if ( 'ytube-like' === hb_options( 'hb_queryloader' ) ) {
		wp_enqueue_script( 'highend_pace', HBTHEMES_URI . '/scripts/jquery.pace.js', array('jquery'), HB_THEME_VERSION, true );
	}

	if ( 'page-presentation-fullwidth.php' === basename( get_page_template() ) ) {
		wp_enqueue_script( 'highend_fullpage', HBTHEMES_URI . '/scripts/jquery.fullpage.js', array('jquery'), HB_THEME_VERSION, true );
	}

	wp_enqueue_script( 'highend_jquery_custom', HBTHEMES_URI . '/scripts/jquery.custom.js', array('jquery'), HB_THEME_VERSION, true );

	if ( is_singular() && comments_open() ){
		wp_enqueue_script( 'comment-reply' );
	}

	// Add additional theme styles.
	do_action( 'highend_enqueue_scripts' );

}
add_action( 'wp_enqueue_scripts', 'highend_enqueues' );

/**
 * Enqueue and register admin scripts and styles.
 *
 * @since 3.5.0
 */
function highend_admin_enqueues() {

	// Admin style
	wp_enqueue_style(
		'highend_admin_style',
		HBTHEMES_URI . '/admin/assets/css/custom-admin.css',
		false,
		HB_THEME_VERSION,
		'all'
	);
}
add_action('admin_enqueue_scripts', 'highend_admin_enqueues' );

/* Automatic Theme updates */
if ( class_exists( 'ThemeUpdateChecker' ) ) {
	$MyThemeUpdateChecker = new ThemeUpdateChecker (
		'HighendWP',
		'http://hb-themes.com/update/?action=get_metadata&slug=HighendWP'
	);
}

// Redirect to About page when activated
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == "themes.php") {
	header('Location: ' . admin_url() . 'admin.php?page=hb_about');
}

require_once get_theme_file_path( 'functions/dynamic-styles.php' );


/* RETRIEVE FROM THEME OPTIONS
================================================== */
function hb_options( $name ) {
	if ( function_exists('vp_option') ) {
		return apply_filters( 'highend_options_value', vp_option( 'hb_highend_option.' . $name ), $name );
	}
	return;
}

remove_filter('nav_menu_description', 'strip_tags');

/* INCLUDES
================================================== */

include ( HBTHEMES_ADMIN . '/author-meta.php');

include ( HBTHEMES_FUNCTIONS . '/helpers.php' );
include ( HBTHEMES_FUNCTIONS . '/common.php' );
include ( HBTHEMES_FUNCTIONS . '/deprecated.php' );
include ( HBTHEMES_FUNCTIONS . '/template-parts.php' );
include ( HBTHEMES_FUNCTIONS . '/template-functions.php' );


include( 'admin/theme-custom-post-types.php');
include( 'admin/theme-custom-taxonomies.php');

include ( 'options-framework/bootstrap.php');
include ( 'admin/theme-options-dependency.php');
include ( 'admin/metaboxes/metabox-dependency.php');
if ( !defined('RWMB_VER') ) {
	include ( 'admin/metaboxes/meta-box-master/meta-box.php');
}
include ( 'admin/metaboxes/gallery-multiupload.php');
include ( 'functions/breadcrumbs.php');
include ( 'functions/theme-likes.php');
include ( 'functions/theme-thumbnails-resize.php');
include ( 'functions/pagination-ajax.php');
include ( 'includes/shortcodes.php');



/* THEME OPTIONS
================================================== */
add_action('after_setup_theme', 'hb_init_options');
if ( !function_exists('hb_init_options') ) {
	function hb_init_options() {
		global $themeoptions;
		$tmpl_opt      = HBTHEMES_ADMIN . '/theme-options.php';
		$themeoptions = new VP_Option(array(
			'is_dev_mode' => false,
			'option_key' => 'hb_highend_option',
			'page_slug' => 'highend_options',
			'template' => $tmpl_opt,
			'menu_page' => 'hb_about',
			'use_auto_group_naming' => true,
			'use_exim_menu' => false,
			'minimum_role' => 'edit_theme_options',
			'layout' => 'fixed',
			'page_title' => __( 'Highend Options', 'hbthemes' ),
			'menu_label' => '<span style="color:#00b9eb;border-bottom:solid 2px #00b9eb;">' . __( 'Highend Options', 'hbthemes' ) . '</span>', 
		));
	}
}


/* METABOXES
================================================== */
function hb_init_metaboxes() {
	
	if ( highend_is_module_enabled('hb_module_portfolio') ) {
		$mb_path_standard_portfolio_page_template_settings = HBTHEMES_ADMIN . '/metaboxes/meta-portfolio-standard-page-settings.php';
		$mb_post_settings = new VP_Metabox(array(
			'id' => 'portfolio_standard_page_settings',
			'types' => array(
				'page'
			),
			'title' => __('Portfolio Template Settings', 'hbthemes'),
			'priority' => 'low',
			'is_dev_mode' => false,
			'template' => $mb_path_standard_portfolio_page_template_settings
		));

		$mb_path_portfolio_settings                        = HBTHEMES_ADMIN . '/metaboxes/meta-portfolio-settings.php';
		$mb_post_settings = new VP_Metabox(array(
			'id' => 'portfolio_settings',
			'types' => array(
				'portfolio'
			),
			'title' => __('Portfolio Page Settings', 'hbthemes'),
			'priority' => 'low',
			'is_dev_mode' => false,
			'template' => $mb_path_portfolio_settings
		));

		$mb_path_portfolio_layout_settings                 = HBTHEMES_ADMIN . '/metaboxes/meta-portfolio-layout-settings.php';
		$mb_post_settings = new VP_Metabox(array(
			'id' => 'portfolio_layout_settings',
			'types' => array(
				'portfolio'
			),
			'title' => __('Portfolio Layout Settings', 'hbthemes'),
			'priority' => 'low',
			'is_dev_mode' => false,
			'context' => 'side',
			'template' => $mb_path_portfolio_layout_settings
		));
	
	}

	if ( highend_is_module_enabled('hb_module_pricing_tables') ) {
		$mb_path_pricing_settings                          = HBTHEMES_ADMIN . '/metaboxes/meta-pricing-table-settings.php';  
		$mb_post_settings = new VP_Metabox(array(
			'id' => 'pricing_settings',
			'types' => array(
				'hb_pricing_table'
			),
			'title' => __('Pricing Settings', 'hbthemes'),
			'priority' => 'low',
			'is_dev_mode' => false,
			'template' => $mb_path_pricing_settings
		));
	}

	if ( highend_is_module_enabled('hb_module_gallery') ) {
		$mb_path_fw_gallery_page_template_settings         = HBTHEMES_ADMIN . '/metaboxes/meta-gallery-fw-page-settings.php';
		$mb_post_settings = new VP_Metabox(array(
			'id' => 'gallery_fw_page_settings',
			'types' => array(
				'page'
			),
			'title' => __('Fullwidth Gallery Template Settings', 'hbthemes'),
			'priority' => 'low',
			'is_dev_mode' => false,
			'template' => $mb_path_fw_gallery_page_template_settings
		));

		$mb_path_standard_gallery_page_template_settings   = HBTHEMES_ADMIN . '/metaboxes/meta-gallery-standard-page-settings.php';
		$mb_post_settings = new VP_Metabox(array(
			'id' => 'gallery_standard_page_settings',
			'types' => array(
				'page'
			),
			'title' => __('Standard Gallery Template Settings', 'hbthemes'),
			'priority' => 'low',
			'is_dev_mode' => false,
			'template' => $mb_path_standard_gallery_page_template_settings
		));  

		$mb_path_gallery_settings                          = HBTHEMES_ADMIN . '/metaboxes/meta-gallery-settings.php';
		$mb_gallery_settings = new VP_Metabox(array(
			'id' => 'gallery_settings',
			'types' => array(
				'gallery',
			),
			'title' => __('Gallery Settings', 'hbthemes'),
			'priority' => 'low',
			'is_dev_mode' => false,
			'template' => $mb_path_gallery_settings
		));
	   
	}

	if ( highend_is_module_enabled('hb_module_testimonials') ) {
		$mb_path_testimonials_settings                     = HBTHEMES_ADMIN . '/metaboxes/meta-testimonials.php';
		$mb_post_settings = new VP_Metabox(array(
			'id' => 'testimonial_type_settings',
			'types' => array(
				'hb_testimonials'
			),
			'title' => __('Testimonial Settings', 'hbthemes'),
			'priority' => 'low',
			'is_dev_mode' => false,
			'template' => $mb_path_testimonials_settings
		));
	}

	if ( highend_is_module_enabled('hb_module_team_members') ) {
		$mb_path_team_layout_settings                      = HBTHEMES_ADMIN . '/metaboxes/meta-team-layout-settings.php';
		$mb_post_settings = new VP_Metabox(array(
			'id' => 'team_layout_settings',
			'types' => array(
				'team'
			),
			'title' => __('Team Layout Settings', 'hbthemes'),
			'priority' => 'low',
			'is_dev_mode' => false,
			'context' => 'side',
			'template' => $mb_path_team_layout_settings
		));

		$mb_path_team_member_settings                      = HBTHEMES_ADMIN . '/metaboxes/meta-team-member-settings.php';
		$mb_post_settings = new VP_Metabox(array(
			'id' => 'team_member_settings',
			'types' => array(
				'team'
			),
			'title' => __('Team Member Settings', 'hbthemes'),
			'priority' => 'low',
			'is_dev_mode' => false,
			'template' => $mb_path_team_member_settings
		));
	}

	if ( highend_is_module_enabled('hb_module_clients') ) {
		$mb_path_clients_settings                          = HBTHEMES_ADMIN . '/metaboxes/meta-clients-settings.php';
		$mb_post_settings = new VP_Metabox(array(
			'id' => 'clients_settings',
			'types' => array(
				'clients'
			),
			'title' => __('Clients Settings', 'hbthemes'),
			'priority' => 'low',
			'is_dev_mode' => false,
			'template' => $mb_path_clients_settings
		));
	}

	$mb_path_presentation_settings                     = HBTHEMES_ADMIN . '/metaboxes/meta-presentation-settings.php';
	$mb_presentation_settings = new VP_Metabox(array(
		'id' => 'presentation_settings',
		'types' => array(
			'page',
		),
		'title' => __('Presentation Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'template' => $mb_path_presentation_settings
	));

	$mb_path_featured_section_settings                 = HBTHEMES_ADMIN . '/metaboxes/meta-featured-page-section.php';
	$mb_post_settings = new VP_Metabox(array(
		'id' => 'featured_section',
		'types' => array(
			'page',
			'team'
		),
		'title' => __('Featured Section Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'template' => $mb_path_featured_section_settings
	));
	

	$mb_path_contact_page_template_settings            = HBTHEMES_ADMIN . '/metaboxes/meta-contact-page-settings.php';
	$mb_post_settings = new VP_Metabox(array(
		'id' => 'contact_page_settings',
		'types' => array(
			'page'
		),
		'title' => __('Contact Template Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'template' => $mb_path_contact_page_template_settings
	));

	$mb_path_post_format_settings                      = HBTHEMES_ADMIN . '/metaboxes/meta-post-format-settings.php';
	$mb_post_settings = new VP_Metabox(array(
		'id' => 'post_format_settings',
		'types' => array(
			'post'
		),
		'title' => __('Post Format Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'template' => $mb_path_post_format_settings
	));
	$mb_path_blog_page_template_settings               = HBTHEMES_ADMIN . '/metaboxes/meta-blog-page-settings.php';
	$mb_post_settings = new VP_Metabox(array(
		'id' => 'blog_page_settings',
		'types' => array(
			'page'
		),
		'title' => __('Classic Blog Template Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'template' => $mb_path_blog_page_template_settings
	));

	$mb_path_blog_page_minimal_template_settings       = HBTHEMES_ADMIN . '/metaboxes/meta-blog-page-minimal-settings.php';
	$mb_post_settings = new VP_Metabox(array(
		'id' => 'blog_page_minimal_settings',
		'types' => array(
			'page'
		),
		'title' => __('Minimal Blog Template Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'template' => $mb_path_blog_page_minimal_template_settings
	));

	$mb_path_grid_blog_page_template_settings          = HBTHEMES_ADMIN . '/metaboxes/meta-blog-grid-page-settings.php';
	$mb_post_settings = new VP_Metabox(array(
		'id' => 'blog_grid_page_settings',
		'types' => array(
			'page'
		),
		'title' => __('Grid Blog Template Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'template' => $mb_path_grid_blog_page_template_settings
	));

	$mb_path_fw_blog_page_template_settings            = HBTHEMES_ADMIN . '/metaboxes/meta-blog-fw-page-settings.php';
	$mb_post_settings = new VP_Metabox(array(
		'id' => 'blog_fw_page_settings',
		'types' => array(
			'page'
		),
		'title' => __('Fullwidth Blog Template Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'template' => $mb_path_fw_blog_page_template_settings
	));

	$mb_path_general_settings                          = HBTHEMES_ADMIN . '/metaboxes/meta-general-settings.php';
	$mb_post_settings = new VP_Metabox(array(
		'id' => 'general_settings',
		'types' => array(
			'post',
			'page',
			'team',
			'portfolio',
			'faq'
		),
		'title' => __('General Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'template' => $mb_path_general_settings
	));

	$mb_path_layout_settings                           = HBTHEMES_ADMIN . '/metaboxes/meta-layout-settings.php';
	$mb_post_settings = new VP_Metabox(array(
		'id' => 'layout_settings',
		'types' => array(
			'post',
			'page'
		),
		'title' => __('Layout Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'context' => 'side',
		'template' => $mb_path_layout_settings
	));

	$mb_path_background_settings                       = HBTHEMES_ADMIN . '/metaboxes/meta-background-settings.php';
	$mb_post_settings = new VP_Metabox(array(
		'id' => 'background_settings',
		'types' => array(
			'post',
			'page',
			'team',
			'portfolio',
			'faq'
		),
		'title' => __('Background Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'template' => $mb_path_background_settings
	));

	$mb_path_misc_settings                             = HBTHEMES_ADMIN . '/metaboxes/meta-misc-settings.php';
	$mb_post_settings = new VP_Metabox(array(
		'id' => 'misc_settings',
		'types' => array(
			'post',
			'page',
			'team',
			'portfolio',
			'faq'
		),
		'title' => __('Misc Settings', 'hbthemes'),
		'priority' => 'low',
		'is_dev_mode' => false,
		'template' => $mb_path_misc_settings
	));
}
add_action('init', 'hb_init_metaboxes');


/* SEARCH FILTER
================================================== */
add_action('pre_get_posts','hb_search_filter');
if (!function_exists('hb_search_filter')) {
	function hb_search_filter($query) {
		if ( !is_admin() && $query->is_main_query() ) {
			if ($query->is_search) {
				$query->set( 's', rtrim(get_search_query()) );
			}
		}
	}
}

/* CUSTOM WORDPRESS LOGIN LOGO
================================================== */
add_action('login_head', 'hb_custom_login_logo');
function hb_custom_login_logo() {
	if (hb_options('hb_wordpress_logo')) {
		echo '<style type="text/css">
			h1 a { background-image:url(' . hb_options('hb_wordpress_logo') . ') !important; background-size:contain !important; width:274px !important; height: 63px !important; }
		</style>';
	}
}

add_filter( 'login_headerurl', 'hb_custom_login_logo_url' );
function hb_custom_login_logo_url($url) {
	return get_site_url();
}


/*  THEME WIDGETS
================================================== */
include(HBTHEMES_INCLUDES . '/widgets/widget-most-commented-posts.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-latest-posts.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-latest-posts-simple.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-most-liked-posts.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-recent-comments.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-testimonials.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-pinterest.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-flickr.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-dribbble.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-google.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-facebook.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-contact-info.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-social-icons.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-gmap.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-twitter.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-portfolio.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-gallery.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-portfolio-random.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-most-liked-portfolio.php');
include(HBTHEMES_INCLUDES . '/widgets/widget-ads-300x250.php');


/* UNREGISTER THEME WIDGETS
================================================== */
function hb_unregister_widgets() {
	$widgets_to_unreg = array();

	if ( !highend_is_module_enabled('hb_module_portfolio') ) {
		$widgets_to_unreg[] = 'HB_Liked_Portfolio_Widget';
		$widgets_to_unreg[] = 'HB_Portfolio_Widget_Rand';
		$widgets_to_unreg[] = 'HB_Portfolio_Widget';
	}

	if ( !highend_is_module_enabled('hb_module_gallery') ) {
		$widgets_to_unreg[] = 'HB_Gallery_Widget';
	}

	if ( !highend_is_module_enabled('hb_module_testimonials') ) {
		$widgets_to_unreg[] = 'HB_Testimonials_Widget';
	}

	foreach ($widgets_to_unreg as $widget) {
		unregister_widget( $widget );
	}
}
add_action( 'widgets_init', 'hb_unregister_widgets' );


/* LOAD MORE
================================================== */
function wp_infinitepaginate() {
	$loopFile       = $_POST['loop_file'];
	$paged          = $_POST['page_no'];
	$category       = $_POST['category'];

	if ($category != '' && $category != ' '){
		$category = explode("+", $category);
	} else {
		$category = array();
	}

	$col_count = "";
	$posts_per_page = get_option('posts_per_page');

	if ( isset($_POST['col_count'] ))
		$col_count      = $_POST['col_count'];

	query_posts(array(
		'paged' => $paged,
		'category__in' => $category,
		'post_status' => array('publish')
	));

	get_template_part($loopFile);
	exit;
}
add_action('wp_ajax_infinite_scroll', 'wp_infinitepaginate');
add_action('wp_ajax_nopriv_infinite_scroll', 'wp_infinitepaginate');


/* MAINTENANCE MODE
================================================== */
function maintenace_mode() {
	$hidden_param = "";

	if ( ! highend_is_module_enabled('hb_module_coming_soon_mode') ) return; 

	if (isset($_GET['hb_maintenance'])){
		$hidden_param = $_GET['hb_maintenance'];
	}
	
	if ( highend_is_maintenance() || ($hidden_param == 'yes') ) {
		get_template_part('hb-maintenance');
		exit;
	}
}
add_action('get_header', 'maintenace_mode');

/* AJAX LIBRARY
================================================== */
function hb_add_ajax_library() {
	$html = '<script type="text/javascript">';
	$html .= 'var ajaxurl = "' . admin_url('admin-ajax.php') . '"';
	$html .= '</script>';
	echo $html;
}
add_action('wp_head', 'hb_add_ajax_library');

/* CHECK IF YOAST SEO PLUGIN IS INSTALLED
================================================== */
if( !function_exists('hb_seo_plugin_installed') ) {
	function hb_seo_plugin_installed() {
		if( defined('WPSEO_VERSION') ) {
			return true;
		}
		return false;
	}
}

/* FILTER THE DEFAULT COMMENT FIELDS
================================================== */
add_filter( 'comment_form_fields', 'hx_custom_fields' );
function hx_custom_fields( $fields ) {

	$commenter 		= wp_get_current_commenter();
	$req 			= get_option( 'require_name_email' );
	$aria_req 		= ( $req ? " aria-required='true' required='required'" : '' );
	$comment_field 	= $fields['comment'];

	unset( $fields['comment'] );
	unset( $fields['cookies'] );

	$fields[ 'author' ] = '<p class="comment-form-author"><input id="author" name="author" type="text" placeholder="'. __( 'Your real name *', 'hbthemes' ) .'" value="'. esc_attr( $commenter['comment_author'] ) . '" size="30" tabindex="107"' . $aria_req . ' /></p>';

	$fields[ 'email' ] = '<p class="comment-form-email"><input id="email" name="email" type="email" placeholder="'. __( 'Your email address *', 'hbthemes' ) .'" value="'. esc_attr( $commenter['comment_author_email'] ) . '" size="40"  tabindex="108"' . $aria_req . ' /></p>';

	$fields[ 'url' ] = '<p class="comment-form-url"><input id="url" placeholder="'. __( 'Your website URL', 'hbthemes' ) .'" name="url" type="text" value="" tabindex="109" size="30" maxlength="200"></p>';

	$fields['comment'] = '<p class="comment-form-comment"><textarea id="comment" tabindex="110" name="comment" cols="45" placeholder="'. __( 'Your comment *', 'hbthemes' ) .'" rows="8" maxlength="65525" aria-required="true" required="required"></textarea></p>';

	$fields['cookies'] = '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"><label for="wp-comment-cookies-consent">'. __( 'Save my name and email in this browser for the next time I comment.', 'hbthemes' ) .'</label></p>';

	return $fields;
}

/* AJAX SEARCH
================================================== */
add_action('init', 'hb_ajax_search_init');
function hb_ajax_search_init() {
	add_action('wp_ajax_hb_ajax_search', 'hb_ajax_search');
	add_action('wp_ajax_nopriv_hb_ajax_search', 'hb_ajax_search');
}
function hb_ajax_search() {
	$search_term  = $_REQUEST['term'];
	$search_term  = apply_filters('get_search_query', $search_term);
	$search_array = array(
		's' => $search_term,
		'showposts' => 5,
		'post_type' => 'any',
		'post_status' => 'publish',
		'post_password' => '',
		'suppress_filters' => true
	);
	$query        = http_build_query($search_array);
	$posts        = get_posts($query);
	$suggestions  = array();
	global $post;
	foreach ($posts as $post):
		setup_postdata($post);
		$suggestion  = array();
		$format      = get_post_format(get_the_ID());
		$icon_to_use = 'hb-moon-file-3';
		if ($format == 'video') {
			$icon_to_use = 'hb-moon-play-2';
		} else if ($format == 'status' || $format == 'standard') {
			$icon_to_use = 'hb-moon-pencil';
		} else if ($format == 'gallery' || $format == 'image') {
			$icon_to_use = 'hb-moon-image-3';
		} else if ($format == 'audio') {
			$icon_to_use = 'hb-moon-music-2';
		} else if ($format == 'quote') {
			$icon_to_use = 'hb-moon-quotes-right';
		} else if ($format == 'link') {
			$icon_to_use = 'hb-moon-link-5';
		}
		$suggestion['label'] = esc_html($post->post_title);
		$suggestion['link']  = get_permalink();
		$suggestion['date']  = get_the_time('F j Y');
		$suggestion['image'] = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail($post->ID, 'thumbnail', array(
			'title' => ''
		)) : '<i class="' . $icon_to_use . '"></i>';
		$suggestions[]       = $suggestion;
	endforeach;
	// JSON encode and echo
	$response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";
	echo $response;
	exit;
}

/* AJAX MAIL
================================================== */
add_action('wp_ajax_mail_action', 'sending_mail');
add_action('wp_ajax_nopriv_mail_action', 'sending_mail');
function sending_mail() {
	$site     = get_site_url();
	$subject  = __('New Message!', 'hbthemes');
	$email    = $_POST['contact_email'];
	$email_s  = filter_var($email, FILTER_SANITIZE_EMAIL);
	$comments = stripslashes($_POST['contact_comments']);
	$name     = stripslashes($_POST['contact_name']);
	$to       = hb_options('hb_contact_settings_email');
	$message  = "Name: $name \n\nEmail: $email \n\nMessage: $comments \n\nThis email was sent from $site";
	$headers  = 'From: ' . $name . ' <' . $email_s . '>' . "\r\n" . 'Reply-To: ' . $email_s;
	wp_mail($to, $subject, $message, $headers);
	exit();
}

/* HIDE META
================================================== */
add_action('admin_print_scripts-post-new.php', 'hb_hide_meta_admin_scripts');
add_action('admin_print_scripts-post.php', 'hb_hide_meta_admin_scripts');
function hb_hide_meta_admin_scripts() {
	wp_enqueue_script('hb-hide-meta', get_template_directory_uri() . '/admin/metaboxes/hide-meta.js', array(
		'jquery'
	));
}


/* QUICK SHORTCODES
================================================== */
add_shortcode('wp-link', 'wp_link_shortcode');
function wp_link_shortcode() {
	return '<a href="http://wordpress.org" target="_blank">WordPress</a>';
}

add_shortcode('the-year', 'the_year_shortcode');
function the_year_shortcode() {
	return date('Y');
}


/* REMOVE PAGE TEMPLATES
================================================== */
function hb_remove_page_templates( $templates ) {
	if ( !highend_is_module_enabled('hb_module_portfolio') ) {
		unset( $templates['page-portfolio-simple.php'] );
		unset( $templates['page-portfolio-standard.php'] );
	}

	if ( !highend_is_module_enabled('hb_module_gallery') ) {
		unset( $templates['page-gallery-fullwidth.php'] );
		unset( $templates['page-gallery-standard.php'] );
	}

	return $templates;
}
add_filter( 'theme_page_templates', 'hb_remove_page_templates' );


/* REMOVE SHORTCODES
================================================== */
function hb_remove_shortcodes() {

	// PORTFOLIO
	if ( !highend_is_module_enabled('hb_module_portfolio')) {
		remove_shortcode( 'portfolio_fullwidth' );
		remove_shortcode( 'portfolio_carousel' );

		if ( function_exists('vc_remove_element') ) {
			vc_remove_element("portfolio_fullwidth");
			vc_remove_element("portfolio_carousel");
		}
	}

	// GALLERY
	if ( !highend_is_module_enabled('hb_module_gallery')) {
		remove_shortcode( 'gallery_fullwidth' );
		remove_shortcode( 'gallery_carousel' );

		if ( function_exists('vc_remove_element') ) {
			vc_remove_element("gallery_fullwidth");
			vc_remove_element("gallery_carousel");
		}
	}

	// PRICING TABLES
	if ( !highend_is_module_enabled('hb_module_pricing_tables')) {
		remove_shortcode( 'menu_pricing_item' );
		remove_shortcode( 'pricing_table' );

		if ( function_exists('vc_remove_element') ) {
			vc_remove_element("menu_pricing_item");
			vc_remove_element("pricing_table");
		}
	}

	// FAQ
	if ( !highend_is_module_enabled('hb_module_faq')) {
		remove_shortcode( 'faq' );

		if ( function_exists('vc_remove_element') ) {
			vc_remove_element("faq");
		}
	}

	// TESTIMONIALS
	if ( !highend_is_module_enabled('hb_module_testimonials')) {
		remove_shortcode( 'testimonial_box' );
		remove_shortcode( 'testimonial_slider' );

		if ( function_exists('vc_remove_element') ) {
			vc_remove_element("testimonial_box");
			vc_remove_element("testimonial_slider");
		}
	}

	// TEAM MEMBERS
	if ( !highend_is_module_enabled('hb_module_team_members')) {
		remove_shortcode( 'team_carousel' );
		remove_shortcode( 'team_member_box' );

		if ( function_exists('vc_remove_element') ) {
			vc_remove_element("team_carousel");
			vc_remove_element("team_member_box");
		}
	}

	// CLIENTS
	if ( !highend_is_module_enabled('hb_module_clients')) {
		remove_shortcode( 'client_carousel' );

		if ( function_exists('vc_remove_element') ) {
			vc_remove_element("client_carousel");
		}
	}	
}
add_action( 'init', 'hb_remove_shortcodes' );

function hb_buildStyle( $bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $margin_bottom = '' ) {

	$has_image = false;
	$style = '';
	if ( (int) $bg_image > 0 && false !== ( $image_url = wp_get_attachment_url( $bg_image, 'large' ) ) ) {
		$has_image = true;
		$style .= 'background-image: url(' . $image_url . ');';
	}
	if ( ! empty( $bg_color ) ) {
		$style .= hb_get_css_color( 'background-color', $bg_color );
	}
	if ( ! empty( $bg_image_repeat ) && $has_image ) {
		if ( 'cover' === $bg_image_repeat ) {
			$style .= 'background-repeat:no-repeat;background-size: cover;';
		} elseif ( 'contain' === $bg_image_repeat ) {
			$style .= 'background-repeat:no-repeat;background-size: contain;';
		} elseif ( 'no-repeat' === $bg_image_repeat ) {
			$style .= 'background-repeat: no-repeat;';
		}
	}
	if ( ! empty( $font_color ) ) {
		$style .= hb_get_css_color( 'color', $font_color );
	}
	if ( '' !== $padding ) {
		$style .= 'padding: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding ) ? $padding : $padding . 'px' ) . ';';
	}
	if ( '' !== $margin_bottom ) {
		$style .= 'margin-bottom: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $margin_bottom ) ? $margin_bottom : $margin_bottom . 'px' ) . ';';
	}

	return empty( $style ) ? '' : ' style="' . esc_attr( $style ) . '"';
}

function hb_get_css_color( $prefix, $color ) {
	$rgb_color = preg_match( '/rgba/', $color ) ? preg_replace( array(
		'/\s+/',
		'/^rgba\((\d+)\,(\d+)\,(\d+)\,([\d\.]+)\)$/',
	), array( '', 'rgb($1,$2,$3)' ), $color ) : $color;
	$string = $prefix . ':' . $rgb_color . ';';
	if ( $rgb_color !== $color ) {
		$string .= $prefix . ':' . $color . ';';
	}

	return $string;
}

/* THEME SUPPORT
================================================== */
// add_filter('widget_text', 'do_shortcode');
// add_filter('widget_text', 'shortcode_unautop');

// /* SHORTCODES IN TEXT WIDGET
// ================================================== */
// function theme_widget_text_shortcode($content) {
// 	$content          = do_shortcode($content);
// 	$new_content      = '';
// 	$pattern_full     = '{(\[raw\].*?\[/raw\])}is';
// 	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
// 	$pieces           = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
// 	foreach ($pieces as $piece) {
// 		if (preg_match($pattern_contents, $piece, $matches)) {
// 			$new_content .= $matches[1];
// 		} else {
// 			$new_content .= do_shortcode($piece);
// 		}
// 	}
// 	return $new_content;
// }
// add_filter('widget_text', 'theme_widget_text_shortcode');
// add_filter('widget_text', 'do_shortcode');

/* SHORTCODE PARAGRAPH FIX
================================================== */
function shortcode_empty_paragraph_fix($content) {
	$array   = array(
		'<p>[' => '[',
		']</p>' => ']',
		'<br/>[' => '[',
		']<br/>' => ']',
		']<br />' => ']',
		'<br />[' => '['
	);
	$content = strtr($content, $array);
	return $content;
}
add_filter('the_content', 'shortcode_empty_paragraph_fix');

/*KIOSK INFORMATION
================================================= */
// Register Custom Post Type
function register_efs_kiosk() {

    $labels = array(
        'name'                  => _x( 'Kiosks', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Kiosk', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Kiosks', 'text_domain' ),
        'name_admin_bar'        => __( 'Kiosk', 'text_domain' ),
        'archives'              => __( 'Kiosk Archives', 'text_domain' ),
        'attributes'            => __( 'Kiosk Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Kiosk:', 'text_domain' ),
        'all_items'             => __( 'All Kiosks', 'text_domain' ),
        'add_new_item'          => __( 'Add New Kiosk', 'text_domain' ),
        'add_new'               => __( 'Add New Kiosk', 'text_domain' ),
        'new_item'              => __( 'New Kiosk', 'text_domain' ),
        'edit_item'             => __( 'Edit Kiosk', 'text_domain' ),
        'update_item'           => __( 'Update Kiosk', 'text_domain' ),
        'view_item'             => __( 'View Kiosk', 'text_domain' ),
        'view_items'            => __( 'View Kiosks', 'text_domain' ),
        'search_items'          => __( 'Search Kiosk', 'text_domain' ),
        'not_found'             => __( 'Kiosk Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Kiosk Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Kiosk', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Kiosk', 'text_domain' ),
        'items_list'            => __( 'Kiosks list', 'text_domain' ),
        'items_list_navigation' => __( 'Kiosks list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter Kiosks list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Kiosk', 'text_domain' ),
        'description'           => __( 'Generates a Kiosk Page using the Solar Edge API.', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => array(
            'slug' => 'kiosk'
        )
    );
    register_post_type( 'efs_kiosk', $args );

}
add_action( 'init', 'register_efs_kiosk', 0 );

function request_solar_edge_data($kiosk_id) {
    if (empty($kiosk_id)) {
        return false;
    }

    if (get_post_type($kiosk_id) !== 'efs_kiosk') {
        return false;
    }

    date_default_timezone_set('America/Chicago');

    $last_updated = get_field('last_updated', $kiosk_id);
    $timestamp = time();
    // 20 minutes * 60 seconds / minute = 1200 seconds
    $cache_cold_timeout = 1200;

    if ($timestamp - $cache_cold_timeout <= $last_updated) {
        // Wait at least $cache_cold_timout seconds before requesting new data from API.
        return false;
    }

    $site_api_key = get_field('site_api_key', $kiosk_id);
    $site_id = get_field('site_id', $kiosk_id);

    $date_format = "Y-m-d 00:00:00";
    $power_time_start = urlencode(date($date_format));
    $power_time_end = urlencode(date($date_format, strtotime('+1 day')));

    $site_details_endpoint = "https://monitoringapi.solaredge.com/site/${site_id}/details.json?api_key=${site_api_key}";
    $site_overview_endpoint = "https://monitoringapi.solaredge.com/site/${site_id}/overview.json?api_key=${site_api_key}";
    $site_envBenefits_endpoint = "https://monitoringapi.solaredge.com/site/${site_id}/envBenefits.json?systemUnits=Imperial&api_key=${site_api_key}";
    /*$site_meters_endpoint = "https://monitoringapi.solaredge.com/site/${site_id}/energyDetails.json?meters=Production&startTime=2019-01-5%2011:00:00&endTime=2019-04-05%2013:00:00&timeUnit=MONTH&api_key=${site_api_key}";*/
    $site_power_endpoint = "https://monitoringapi.solaredge.com/site/${site_id}/power.json?startTime=${power_time_start}&endTime=${power_time_end}&api_key=${site_api_key}";

    $handle = curl_init($site_details_endpoint);
    curl_setopt_array(
        $handle,
        array(
            CURLOPT_URL => $site_details_endpoint,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_RETURNTRANSFER => true
        )
    );
    
    $site_details_json_raw = curl_exec($handle);

    curl_setopt_array(
        $handle,
        array(
            CURLOPT_URL => $site_overview_endpoint,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_RETURNTRANSFER => true
        )
    );

    $site_overview_json_raw = curl_exec($handle);

    curl_setopt_array(
        $handle,
        array(
            CURLOPT_URL => $site_envBenefits_endpoint,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_RETURNTRANSFER => true
        )
    );

    $site_envBenefits_json_raw = curl_exec($handle);

/*    curl_setopt_array(
        $handle,
        array(
            CURLOPT_URL => $site_meters_endpoint,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_RETURNTRANSFER => true
        )
    );

    $site_meters_json_raw = curl_exec($handle);*/

    curl_setopt_array(
        $handle,
        array(
            CURLOPT_URL => $site_power_endpoint,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_RETURNTRANSFER => true
        )
    );

    $site_power_json_raw = curl_exec($handle);

    curl_close($handle);

    $site_details_json = json_decode($site_details_json_raw, true);
    $site_overview_json = json_decode($site_overview_json_raw, true);
    $site_envBenefits_json = json_decode($site_envBenefits_json_raw, true);
//    $site_meters_json = json_decode($site_meters_json_raw, true);
    $site_power_json = json_decode($site_power_json_raw, true);



    $monthly_energy_data = [];
    /*foreach($site_meters_json['energyDetails']['meters'][0]['values'] as $datum) {
        $monthly_energy_data[] = array(
            'month_date' => date('M', strtotime($datum['date'])),
            'month_value' => $datum['value'],
        );
    }*/

    $count = count($site_power_json['power']['values']);
    for ($i = 0; $i < $count; ++ $i) {
        $site_power_json['power']['values'][$i]['date'] = substr($site_power_json['power']['values'][$i]['date'], 11, 5);
    }

//    echo '<pre style="color: white;">' . print_r($site_power_json, true) . '</pre>';

    $site_data = array(
        'site_name'	=> $site_details_json['details']['name'],
        'install_date' => $site_details_json['details']['installationDate'],
        'peak_power' =>	$site_details_json['details']['peakPower'],
        'lifetime_energy' => $site_overview_json['overview']['lifeTimeData']['energy'],
        'lifetime_revenue' => $site_overview_json['overview']['lifeTimeData']['revenue'],
        'current_power' => $site_overview_json['overview']['currentPower']['power'],
        'monthly_energy' => $site_overview_json['overview']['lastMonthData']['energy'],
        'co2_emission_saved' => $site_envBenefits_json['envBenefits']['gasEmissionSaved']['units'],
        'co2_emission_unit' => $site_envBenefits_json['envBenefits']['gasEmissionSaved']['co2'],
        'trees_planted' => $site_envBenefits_json['envBenefits']['treesPlanted'],
        'monthly_energy_data' => $monthly_energy_data,
        /*'monthly_energy_data_unit' => $site_meters_json['energyDetails']['unit'],*/
        'monthly_energy_data_unit' => '',
        'site_power' => serialize($site_power_json['power']['values'])
    );

//Update the field using this array as value:
    update_field('last_updated', $timestamp, $kiosk_id);
    update_field( 'site_data', $site_data, $kiosk_id );
    return true;
}

add_action('wp_enqueue_scripts', 'register_chartist');
function register_chartist() {
    if (get_post_type() != 'efs_kiosk') {
        return;
    }
}

add_action('wp_enqueue_scripts', 'register_moment');
function register_moment() {
    if (get_post_type() != 'efs_kiosk') {
        return;
    }
}

add_action('wp_enqueue_scripts', 'register_kiosk');
function register_kiosk() {
    if (get_post_type() != 'efs_kiosk') {
        return;
    }

    $dir_stylesheet = get_stylesheet_directory_uri();

    wp_enqueue_style(
        'chartist-css',
        'https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css'
    );

    wp_enqueue_script(
        'chartist-js',
        'https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js',
        array(),
        false,
        true
    );

    wp_enqueue_script(
        'chartist-axistitle-js',
        $dir_stylesheet . '/js/chartist-plugin-axistitle.js',
        array('chartist-js'),
        false,
        true
    );

    wp_enqueue_script(
        'chartist-missing-data-js',
        $dir_stylesheet . '/js/pluginMissingData.js',
        array('chartist-js'),
        false,
        true
    );

    wp_enqueue_script(
        'packery-js',
        'https://unpkg.com/packery@2/dist/packery.pkgd.min.js',
        array(),
        false,
        true
    );

    wp_enqueue_script(
        'kiosk-js',
        $dir_stylesheet . '/js/kiosk.js',
        array('jquery', 'packery-js', 'chartist-js'),
        false,
        true
    );

    wp_localize_script(
        'kiosk-js',
        'ajax_object',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' )
        )
    );
}

add_action('wp_ajax_refresh_kiosk', 'get_kiosk_data');
add_action('wp_ajax_nopriv_refresh_kiosk', 'get_kiosk_data');

function get_kiosk_data() {
    $post_index = 'kiosk-id';

    if (! isset($_POST[$post_index])) {
        wp_die();
    } else if (empty($_POST[$post_index])) {
        wp_die();
    }

    $kiosk_id = intval($_POST[$post_index]);
    if (get_post_type($kiosk_id) != 'efs_kiosk') {
        wp_die();
    }

    request_solar_edge_data($kiosk_id);

    $site_data = get_field('site_data', $kiosk_id);
    $site_quote = get_field('site_quote', $kiosk_id);
    $site_nav = get_field('kiosk_navigation', 'option');
    $last_updated = date('m/d/Y h:i A', get_field('last_updated', $kiosk_id));

    $site_data['site_power'] = unserialize($site_data['site_power']);
    $site_data['today'] = date('m/d/Y');

    $json_data = array(
        'solar_edge' => $site_data,
        'quote' => $site_quote,
        'site_nav' => $site_nav,
        'last_updated' => $last_updated,
    );

    echo json_encode($json_data);
    wp_die();
}

add_action('init', 'add_kiosk_options');
function add_kiosk_options() {
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_sub_page(array(
            'page_title' 	=> 'Kiosk Navigation',
            'menu_title'	=> 'Navigation',
            'parent_slug'	=> 'edit.php?post_type=efs_kiosk',
        ));

add_action('acf/init', 'add_kiosk_options');

function add_kiosk_options() {
    if( function_exists('acf_add_local_field_group') ):

        acf_add_local_field_group(array(
            'key' => 'group_5cc1f5d2ec232',
            'title' => 'Kiosk',
            'fields' => array(
                array(
                    'key' => 'field_5cc1f5f535072',
                    'label' => 'SITE API KEY',
                    'name' => 'site_api_key',
                    'type' => 'text',
                    'instructions' => '►To generate a Site API key:
In the Site Admin> Site Access tab > Access Control tab > API Access section:
1 Acknowledge reading and agreeing to the SolarEdge API Terms & Conditions.
2 Click Generate API key.
3 Copy the key.
4 Click Save.
5 Paste the key in the input box.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5cc1f8fb752cf',
                    'label' => 'SITE ID',
                    'name' => 'site_id',
                    'type' => 'text',
                    'instructions' => '►To retrieve a Site ID:
In the Site Admin> Site Access tab > Access Control tab > API Access section:
1 Copy the Site ID.
2 Paste the key in the input box.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5cc217ac7a7bc',
                    'label' => 'SITE QUOTE',
                    'name' => 'site_quote',
                    'type' => 'textarea',
                    'instructions' => 'Input the Quote form the site owner.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'new_lines' => '',
                ),
                array(
                    'key' => 'field_5cc1fbddc0703',
                    'label' => 'LAST UPDATED',
                    'name' => 'last_updated',
                    'type' => 'number',
                    'instructions' => 'Do not modify.	This is the timestamp of the last update time of this API.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => 0,
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                ),
                array(
                    'key' => 'field_5cc1fbc1c0702',
                    'label' => 'SITE DATA',
                    'name' => 'site_data',
                    'type' => 'group',
                    'instructions' => 'The data retrieved from the API during the last request.	This data is cached and served on subsequent requests until the cache is cold.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5cc1fc9f30aef',
                            'label' => 'Site Name',
                            'name' => 'site_name',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc1fcab30af0',
                            'label' => 'Install Date',
                            'name' => 'install_date',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc2095e807e8',
                            'label' => 'Current Power',
                            'name' => 'current_power',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc2078a82c78',
                            'label' => 'Peak Power',
                            'name' => 'peak_power',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc20a633e554',
                            'label' => 'Last Month Energy',
                            'name' => 'monthly_energy',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc208cc43d48',
                            'label' => 'Lifetime Energy',
                            'name' => 'lifetime_energy',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc208d543d49',
                            'label' => 'Lifetime Revenue',
                            'name' => 'lifetime_revenue',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc20c009937e',
                            'label' => 'CO2 Emission Saved',
                            'name' => 'co2_emission_saved',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc20c189937f',
                            'label' => 'CO2 Emission UNIT',
                            'name' => 'co2_emission_unit',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc20c2899380',
                            'label' => 'Trees Planted',
                            'name' => 'trees_planted',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc213ced3262',
                            'label' => 'Monthly Energy Data',
                            'name' => 'monthly_energy_data',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'collapsed' => '',
                            'min' => 0,
                            'max' => 0,
                            'layout' => 'table',
                            'button_label' => '',
                            'sub_fields' => array(
                                array(
                                    'key' => 'field_5cc214add3263',
                                    'label' => 'Month Date',
                                    'name' => 'month_date',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => '',
                                ),
                                array(
                                    'key' => 'field_5cc214e4d3264',
                                    'label' => 'Month Value',
                                    'name' => 'month_value',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => '',
                                ),
                            ),
                        ),
                        array(
                            'key' => 'field_5cc2150dd3265',
                            'label' => 'Monthly Energy Data Unit',
                            'name' => 'monthly_energy_data_unit',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cfe84cd5f5cd',
                            'label' => 'Site Power',
                            'name' => 'site_power',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'efs_kiosk',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'the_content',
            ),
            'active' => true,
            'description' => '',
        ));

        acf_add_local_field_group(array(
            'key' => 'group_5cc9e94fb9ed1',
            'title' => 'Kiosk Navigation',
            'fields' => array(
                array(
                    'key' => 'field_5cc9e955662b9',
                    'label' => 'Kiosk Navigation',
                    'name' => 'kiosk_navigation',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => '',
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'table',
                    'button_label' => '',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5cc9e972662ba',
                            'label' => 'Button URL',
                            'name' => 'button_url',
                            'type' => 'url',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                        ),
                        array(
                            'key' => 'field_5cc9e98a662bb',
                            'label' => 'Button Label',
                            'name' => 'button_label',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'acf-options-navigation',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));

    endif;

}
