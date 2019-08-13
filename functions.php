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
    }
}
