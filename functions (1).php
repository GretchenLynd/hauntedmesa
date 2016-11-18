<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'lifestyle', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'lifestyle' ) );


//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Lifestyle Pro Theme', 'lifestyle' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/lifestyle/' );
define( 'CHILD_THEME_VERSION', '3.0.0' );

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue Droid Sans and Roboto Slab Google fonts
add_action( 'wp_enqueue_scripts', 'lifestyle_google_fonts' );
function lifestyle_google_fonts() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Droid+Sans:400,700|Roboto+Slab:400,300,700', array(), CHILD_THEME_VERSION );
	
}

//* Add new image sizes
add_image_size( 'home-large', 634, 360, TRUE );
add_image_size( 'home-small', 266, 160, TRUE );
add_image_size( 'grid', 320, 130, TRUE );

//* Add support for custom background
add_theme_support( 'custom-background', array(
	'default-image' => get_stylesheet_directory_uri() . '/images/bg.png',
	'default-color' => 'fff',
) );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'header_image'    => '',
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'height'          => 138,
	'width'           => 572,
) );

//* Add support for additional color style options
add_theme_support( 'genesis-style-selector', array(
	'lifestyle-pro-blue'    => __( 'Lifestyle Pro Blue', 'lifestyle' ),
	'lifestyle-pro-green'   => __( 'Lifestyle Pro Green', 'lifestyle' ),
	'lifestyle-pro-mustard' => __( 'Lifestyle Pro Mustard', 'lifestyle' ),
	'lifestyle-pro-purple'  => __( 'Lifestyle Pro Purple', 'lifestyle' ),
	'lifestyle-pro-red'     => __( 'Lifestyle Pro Red', 'lifestyle' ),
) );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Reposition the primary navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'lifestyle_author_box_gravatar' );
function lifestyle_author_box_gravatar( $size ) {

	return 96;
		
}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'lifestyle_comments_gravatar' );
function lifestyle_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;
	
}

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'home-top',
	'name'        => __( 'Home - Top', 'lifestyle' ),
	'description' => __( 'This is the top section of the homepage.', 'lifestyle' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-middle',
	'name'        => __( 'Home - Middle', 'lifestyle' ),
	'description' => __( 'This is the middle section of the homepage.', 'lifestyle' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-bottom-left',
	'name'        => __( 'Home - Bottom Left', 'lifestyle' ),
	'description' => __( 'This is the bottom left section of the homepage.', 'lifestyle' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-bottom-right',
	'name'        => __( 'Home - Bottom Right', 'lifestyle' ),
	'description' => __( 'This is the bottom right section of the homepage.', 'lifestyle' ),
) );


//* Customize the entire footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'sp_custom_footer' );
function sp_custom_footer() {
	?>
	<p>&copy; Copyright 2016 Enjoy Rhinebeck &middot; PO Box 384, Rhinebeck, NY 12572 &middot; Website by <a href="http://www.roadwarriorcreative.com/" target="_blank">Road Warrior Creative</a></p>
	<?php
}

/** Unregister secondary sidebar */
unregister_sidebar( 'sidebar-alt' );

/** Unregister layout settings */
genesis_unregister_layout( 'sidebar-content' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

function amenities_init() {
	// create a new taxonomy
	register_taxonomy(
		'amenities',
		'post',
		array(
			'label' => __( 'Amenities' ),
			'rewrite' => array( 'slug' => 'amenities' ),
			'hierarchical' => true,
		)
	);
}
add_action( 'init', 'amenities_init' );

/** Customize the post meta function */

add_filter( 'genesis_post_meta', 'post_meta_filter' );
function post_meta_filter($post_meta) {
	if ( is_single() ) {
    $post_meta = '[post_categories before="Filed Under: "] [post_tags before="Tagged With: "]<br/>[post_terms before="Amenities: " taxonomy="amenities"]';
    return $post_meta;
}
	if ( is_archive() ) {
	remove_action( 'genesis_after_post_content', 'genesis_post_meta', 'genesis_entry_footer' );
}
}

//* Remove the post info function
remove_action( 'genesis_before_post_content', 'genesis_post_info' );

add_action( 'genesis_before', 'remove_excerpt_on_category_archive_pages' );
function remove_excerpt_on_category_archive_pages() {
    if( is_category() ) {
        remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
      remove_action( 'genesis_entry_content', 'genesis_do_post_image' );
				add_action('show_thumbnail_on_category_archive_pages' );

    }
}

function show_thumbnail_on_category_archive_pages() {
	the_post_thumbnail('thumbnail');
}


function conditional_alphabetize( $query ) {

    if ( is_admin() || ! $query->is_main_query() )
        return;

    if ( is_archive() ) {
                
	$query->set( 'order', 'ASC' );	
	$query->set( 'orderby', 'title' );
		
        return;
    }
}

add_action( 'pre_get_posts', 'conditional_alphabetize', 1 );

/**
 * Remove Image Alignment from Featured Image
 *
 */
function be_remove_image_alignment( $attributes ) {
  $attributes['class'] = str_replace( 'alignleft', 'aligncenter', $attributes['class'] );
	return $attributes;
}
add_filter( 'genesis_attr_entry-image', 'be_remove_image_alignment' );

//* Display a custom favicon
add_filter( 'genesis_pre_load_favicon', 'sp_favicon_filter' );
function sp_favicon_filter( $favicon_url ) {
	return 'http://enjoyrhinebeck.com/new/wp-content/uploads/2014/03/favicon.ico';
}

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'beautiful_remove_comment_form_allowed_tags' );
function beautiful_remove_comment_form_allowed_tags( $defaults ) {

	$defaults['comment_notes_after'] = '';
	return $defaults;

}

/**
 * Add Column Classes to Display Posts Shortcodes
 * @author Bill Erickson
 * @link http://www.billerickson.net/code/add-column-classes-to-display-posts-shortcode
 * 
 * Usage: [display-posts columns="2"]
 *
 * @param array $classes
 * @param object $post
 * @param object $query
 * @return array $classes
 */
function be_display_post_class( $classes, $post, $listing, $atts ) {
	if( !isset( $atts['columns'] ) )
		return $classes;
		
	$columns = array( '', '', 'one-half', 'one-third', 'one-fourth', 'one-fifth', 'one-sixth' );
	$classes[] = $columns[$atts['columns']];
	if( 0 == $listing->current_post || 0 == $listing->current_post % $atts['columns'] )
		$classes[] = 'first';
	return $classes;
}
add_filter( 'display_posts_shortcode_post_class', 'be_display_post_class', 10, 4 );

<?php
/**
 * Add classes to Display Posts Shortcode plugin
 * @author Bill Erickson
 * @link http://wordpress.org/extend/plugins/display-posts-shortcode/
 *
 * @param string $output the original markup for an individual post
 * @param array $atts all the attributes passed to the shortcode
 * @param string $image the image part of the output
 * @param string $title the title part of the output
 * @param string $date the date part of the output
 * @param string $excerpt the excerpt part of the output
 * @param string $inner_wrapper what html element to wrap each post in (default is li)
 * @return string $output the modified markup for an individual post
 */
function be_display_posts_classes( $output, $atts, $image, $title, $date, $excerpt, $inner_wrapper ) {
	
	$classes = 'listing-item';
	
	// Counter
	global $dps_counter;
	$classes .= ' dps-list-item-' . $dps_counter;
	$dps_counter++;
	
	// Current Page
	global $dps_current_page;
	if( $dps_current_page == get_permalink() )
		$classes .= ' current';
	// Now let's rebuild the output.
	$output = '<' . $inner_wrapper . ' class="' . $classes . '">' . $image . $title . $date . $excerpt . '</' . $inner_wrapper . '>';
	// Finally we'll return the modified output
	return $output;
}
add_filter( 'display_posts_shortcode_output', 'be_display_posts_classes', 10, 7 );
/**
 * Display Posts Shortcode - start counter and save current url
 * @author Bill Erickson
 * @link http://wordpress.org/extend/plugins/display-posts-shortcode/
 *
 * @param array $args
 * @return array $args
 */
function be_display_posts_counter_start( $args ) {
	global $dps_counter, $dps_current_page, $post;
	$dps_counter = 0;
	$dps_current_page = get_permalink( $post->ID );
	echo $dps_current_page;
	return $args;
}
add_filter( 'display_posts_shortcode_args', 'be_display_posts_counter_start' );

//* Enqueue JavaScript file for hamburger menu
// ----------------------------------------------------------------------------------------------------------------------------------------------
add_action( 'wp_enqueue_scripts', 'pi_hamburger_enqueue_scripts' );
function pi_hamburger_enqueue_scripts() {
wp_enqueue_script( 'pi-hamburger-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true ); 
}


function themeprefix_swap_title_image() {
     if ( is_archive() || is_home() ) {
		remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
		add_action( 'genesis_entry_header', 'genesis_do_post_image', 8 );
	}
}
add_action( 'genesis_before_content', 'themeprefix_swap_title_image' );