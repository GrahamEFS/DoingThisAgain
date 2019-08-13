<?php
/**
 * @package WordPress
 * @subpackage Highend
 */

/* ACTIONS */
#add_action('template_redirect', 'beanstalk_always_load_js_composer'); # UNCOMMENT to serve js_composer.min.css on every page.
add_action('wp_enqueue_scripts', 'enqueue_parent_styles' );


/* FILTERS */
#add_filter('stylesheet_uri', 'beanstalk_use_min_css', 10, 1); # UNCOMMENT TO SERVE style.min.css
add_filter ('style_loader_src', 'beanstalk_remove_cssjs_ver', 10, 2);
add_filter ('script_loader_src', 'beanstalk_remove_cssjs_ver', 10, 2);
#add_action('wp_footer', 'beanstalk_facebook_track_on_wpmailsent'); # UNCOMMENT TO TRACK FB CONVERSIONS ON WP MAIL SENT
/* SHORTCODES */

add_action('wp_enqueue_scripts', 'register_jacascript');

function register_jacascript() {
	wp_register_script('jquery-match-height', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js', array('jquery'));
	wp_register_script('match-height-testimonial', get_stylesheet_directory_uri() . '/match-height-testimonial.js', array('jquery', 'jquery-match-height'));
	wp_enqueue_script('jquery-match-height');
	wp_enqueue_script('match-height-testimonial');
}


/* FUNCTIONS - ACTIONS */

/**
 * Load Visual Composer CSS on pages that do not use Visual Composer.
 * I.E. Highend Team pages, Woocommerce Products, ETC.
 */
function beanstalk_always_load_js_composer() {
	$handle = 'js_composer_front';
	$src = "/wp-content/plugins/js_composer/assets/css/js_composer.min.css";
	$deps = array();

	if (!wp_style_is($handle, 'registered')) {
		wp_enqueue_style($handle, $src, $deps);
	} else if (!wp_style_is($handle, 'enqueued')) {
		wp_enqueue_style($handle);
	}
}

function enqueue_parent_styles() {
		wp_enqueue_style('parent-style', get_template_directory_uri().'/style.css');
}


function beanstalk_facebook_track_on_wpmailsent() {
?>
<script>
		document.addEventListener( 'wpcf7mailsent', function( event ) {
			fbq('track', 'Lead');
		}, false );
</script>
<?php
}


/* FUNCTIONS - FILTERS */

/**
 * Serve minified CSS by replacing style.css with style.min.css .
 *
 * @param $stylesheet_uri
 *
 * @return mixed
 */
function beanstalk_use_min_css($stylesheet_uri) {
	return str_replace('style.css', 'style.min.css', $stylesheet_uri);
}

/**
 * Serve CSS and JS files without query string.
 * Removed for higher page speed scores!
 *
 * @param $stylesheet_uri
 *
 * @return mixed
 */
function beanstalk_remove_cssjs_ver($src) {
	if (strpos($src, '?ver=')) {
		$src = remove_query_arg('ver', $src);
	}
	return $src;
}


/* FUNCTIONS - SHORTCODES */

/* FUNCTIONS */


add_action('wp_enqueue_scripts', 'efs_register_slick');

/**
 * Register slick.js dependecy in wordpress.
 * Enqueue slick stylesheets.
 */
function efs_register_slick() {
	$handle = "slick-js";
	$src = "//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js";
	$deps = array('jquery');
	$ver = false;
	$in_footer = true;
	wp_register_script($handle, $src, $deps, $ver, $in_footer);
	
	$handle = "slick-lightbox-js";
	$src = "//cdnjs.cloudflare.com/ajax/libs/slick-lightbox/0.2.12/slick-lightbox.min.js";
	$deps = array('jquery');
	$ver = false;
	$in_footer = true;
	wp_register_script($handle, $src, $deps, $ver, $in_footer);

	$handle = "slick-css";
	$src = "//cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css";
	$deps = array();
	wp_enqueue_style($handle, $src, $deps);

	$handle = "slick-theme-css";
	$src = "//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css";
	$deps = array('slick-css');
	wp_enqueue_style($handle, $src, $deps);
	
	$handle = "slick-lightbox-css";
	$src = "//cdnjs.cloudflare.com/ajax/libs/slick-lightbox/0.2.12/slick-lightbox.css";
	$deps = array('slick-css');
	wp_enqueue_style($handle, $src, $deps);
	
}