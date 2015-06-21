<?php
/* Welcome to Stripped_Theme Basic
Commonly Used functions are implemented here.
Also there is a "setup"-function
TODO: order these functions in to seperate files which can be included
*/

// Adding Translation Option
load_theme_textdomain( 'stripped', TEMPLATEPATH.'/languages' );
$locale = get_locale();
$locale_file = TEMPLATEPATH."/languages/$locale.php";
if ( is_readable($locale_file) ) require_once($locale_file);

/* Common used functionsBased on 320 WP Bootstrap theme
*/

// Cleaning up the Wordpress Head
function wp_bootstrap_head_cleanup() {
  // remove header links
  remove_action( 'wp_head', 'feed_links_extra', 3 );                    // Category Feeds
  remove_action( 'wp_head', 'feed_links', 2 );                          // Post and Comment Feeds
  remove_action( 'wp_head', 'rsd_link' );                               // EditURI link
  remove_action( 'wp_head', 'wlwmanifest_link' );                       // Windows Live Writer
  remove_action( 'wp_head', 'index_rel_link' );                         // index link
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );            // previous link
  remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );             // start link
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); // Links for Adjacent Posts
  remove_action( 'wp_head', 'wp_generator' );                           // WP version
}
  // launching operation cleanup
  add_action('init', 'wp_bootstrap_head_cleanup');
  // remove WP version from RSS
  function wp_bootstrap_rss_version() { return ''; }
  add_filter('the_generator', 'wp_bootstrap_rss_version');

// loading jquery reply elements on single pages automatically
function wp_bootstrap_queue_js(){ if (!is_admin()){ if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) wp_enqueue_script( 'comment-reply' ); }
}
  // reply on comments script
  add_action('wp_print_scripts', 'wp_bootstrap_queue_js');

// Fixing the Read More in the Excerpts
// This removes the annoying […] to a Read More link
function wp_bootstrap_excerpt_more($more) {
  global $post;
  // edit here if you like
  return '...  <a href="'. get_permalink($post->ID) . '" class="more-link" title="Read '.get_the_title($post->ID).'">Read more &raquo;</a>';
}
add_filter('excerpt_more', 'wp_bootstrap_excerpt_more');

/*
Frequently used page navigation.
*/

// Numeric Page Navi
function page_navi($before = '', $after = '') {
  global $wpdb, $wp_query;
  $request = $wp_query->request;
  $posts_per_page = intval(get_query_var('posts_per_page'));
  $paged = intval(get_query_var('paged'));
  $numposts = $wp_query->found_posts;
  $max_page = $wp_query->max_num_pages;
  if ( $numposts <= $posts_per_page ) { return; }
  if(empty($paged) || $paged == 0) {
    $paged = 1;
  }
  $pages_to_show = 7;
  $pages_to_show_minus_1 = $pages_to_show-1;
  $half_page_start = floor($pages_to_show_minus_1/2);
  $half_page_end = ceil($pages_to_show_minus_1/2);
  $start_page = $paged - $half_page_start;
  if($start_page <= 0) {
    $start_page = 1;
  }
  $end_page = $paged + $half_page_end;
  if(($end_page - $start_page) != $pages_to_show_minus_1) {
    $end_page = $start_page + $pages_to_show_minus_1;
  }
  if($end_page > $max_page) {
    $start_page = $max_page - $pages_to_show_minus_1;
    $end_page = $max_page;
  }
  if($start_page <= 0) {
    $start_page = 1;
  }

  echo $before.'<ul class="pagination">'."";
  if ($paged > 1) {
    $first_page_text = "&laquo";
    echo '<li class="prev"><a href="'.get_pagenum_link().'" title="First">'.$first_page_text.'</a></li>';
  }

  $prevposts = get_previous_posts_link('Prev');
  if($prevposts) { echo '<li class="prevp">' . $prevposts  . '</li>'; }
  else { echo '<li class="disabled"><a href="#">Prev</a></li>'; }

  for($i = $start_page; $i  <= $end_page; $i++) {
    if($i == $paged) {
      echo '<li class="active"><a href="#">'.$i.'</a></li>';
    } else {
      echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
    }
  }
  echo '<li class="nextp">';
  next_posts_link('Next');
  echo '</li>';
  if ($end_page < $max_page) {
    $last_page_text = "&raquo;";
    echo '<li class="next"><a href="'.get_pagenum_link($max_page).'" title="Last">'.$last_page_text.'</a></li>';
  }
  echo '</ul>'.$after."";
}

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

add_filter('the_content', 'filter_ptags_on_images');

// Display trackbacks/pings callback function
function list_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
?>
        <li id="comment-<?php comment_ID(); ?>"><i class="icon icon-share-alt"></i>&nbsp;<?php comment_author_link(); ?>
<?php

}

// Enable shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

/****************** password protected post form *****/

add_filter( 'the_password_form', 'custom_password_form' );

function custom_password_form() {
  global $post;
  $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
  $o = '<div class="clearfix"><form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
  ' . '<p>' . __( "This post is password protected. To view it please enter your password below:" ,'stripped') . '</p>' . '
  <label for="' . $label . '">' . __( "Password:" ,'stripped') . ' </label><div class="input-append"><input name="post_password" id="' . $label . '" type="password" size="20" /><input type="submit" name="Submit" class="btn btn-primary" value="' . esc_attr__( "Submit",'stripped' ) . '" /></div>
  </form></div>
  ';
  return $o;
}

// Disable jump in 'read more' link
function remove_more_jump_link( $link ) {
  $offset = strpos($link, '#more-');
  if ( $offset ) {
    $end = strpos( $link, '"',$offset );
  }
  if ( $end ) {
    $link = substr_replace( $link, '', $offset, $end-$offset );
  }
  return $link;
}
add_filter( 'the_content_more_link', 'remove_more_jump_link' );

// Remove height/width attributes on images so they can be responsive
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );

function remove_thumbnail_dimensions( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}

// Add thumbnail class to thumbnail links
function add_class_attachment_link( $html ) {
    $postid = get_the_ID();
    $html = str_replace( '<a','<a class="thumbnail"',$html );
    return $html;
}
add_filter( 'wp_get_attachment_link', 'add_class_attachment_link', 10, 1 );

// Menu output mods
class Bootstrap_walker extends Walker_Nav_Menu{

  function start_el(&$output, $object, $depth = 0, $args = Array(), $current_object_id = 0){

   global $wp_query;
   $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

   $class_names = $value = '';

    // If the item has children, add the dropdown class for bootstrap
    if ( $args->has_children ) {
      $class_names = "dropdown ";
    }

    $classes = empty( $object->classes ) ? array() : (array) $object->classes;

    $class_names .= join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $object ) );
    $class_names = ' class="'. esc_attr( $class_names ) . '"';

    $output .= $indent . '<li id="menu-item-'. $object->ID . '"' . $value . $class_names .'>';

    $attributes  = ! empty( $object->attr_title ) ? ' title="'  . esc_attr( $object->attr_title ) .'"' : '';
    $attributes .= ! empty( $object->target )     ? ' target="' . esc_attr( $object->target     ) .'"' : '';
    $attributes .= ! empty( $object->xfn )        ? ' rel="'    . esc_attr( $object->xfn        ) .'"' : '';
    $attributes .= ! empty( $object->url )        ? ' href="'   . esc_attr( $object->url        ) .'"' : '';

    // if the item has children add these two attributes to the anchor tag
    // if ( $args->has_children ) {
      // $attributes .= ' class="dropdown-toggle" data-toggle="dropdown"';
    // }

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before .apply_filters( 'the_title', $object->title, $object->ID );
    $item_output .= $args->link_after;

    // if the item has children add the caret just before closing the anchor tag
    if ( $args->has_children ) {
      $item_output .= '<b class="caret"></b></a>';
    }
    else {
      $item_output .= '</a>';
    }

    $item_output .= $args->after;

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $object, $depth, $args );
  } // end start_el function

  function start_lvl(&$output, $depth = 0, $args = Array()) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
  }

  function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ){
    $id_field = $this->db_fields['id'];
    if ( is_object( $args[0] ) ) {
        $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
    }
    return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
  }
}

add_editor_style('editor-style.css');

// Add Twitter Bootstrap's standard 'active' class name to the active nav link item
add_filter('nav_menu_css_class', 'add_active_class', 10, 2 );

function add_active_class($classes, $item) {
  if( $item->menu_item_parent == 0 && in_array('current-menu-item', $classes) ) {
    $classes[] = "active";
  }

  return $classes;
}


/* Setup Stripped-Theme
  Register Styles
  Register Scripts
  Register Menu's
  Register Post Types
  etc.
*/
// enqueue styles
if( !function_exists("theme_styles") ) {
    function theme_styles() {
        wp_register_style( 'style', get_stylesheet_directory_uri() . '/library/css/style.css', [], '1.0', 'screen' );
        //wp_register_style( 'ie-style', get_stylesheet_directory_uri() . '/library/css/ie.css', [], '1.0', 'screen' );
        wp_enqueue_style( 'style' );
        //wp_enqueue_style( 'ie-style' );
    }
}
add_action( 'wp_enqueue_scripts', 'theme_styles' );

function my_login_stylesheet() { ?>
    <link rel="stylesheet" id="custom_wp_admin_css"  href="<?php echo get_stylesheet_directory_uri() . '/library/css/login.css'; ?>" type="text/css" media="all" />
<?php }

// enqueue javascript

if (!function_exists('stripped_setup')) :
    /**
     * This function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function stripped_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         */
        load_theme_textdomain('stripped', get_template_directory() . '/languages');
        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', [
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
        ]);
        add_theme_support( 'menus' );            // wp menus
        add_theme_support('post-thumbnails' ); //apperently this is needed
        register_nav_menus( // wp3+ menus
                [
                    'main_nav' => __('The Main Menu','stripped'), // main nav in header
                    'footer_nav' => __('The Footer Menu','stripped'), //navigation in footerbar
                ]
        );
    }
endif; // stripped_setup

add_action('after_setup_theme', 'stripped_setup');

function theme_js(){

  if (WP_ENV == 'local') { // use full scripts during development. define WP_ENV in wp-config.php
    wp_register_script( 'bootstrap', get_stylesheet_directory_uri().'/library/js/libs/bootstrap.js', ['jquery'], '1.0', true);
    wp_register_script( 'stripped', get_template_directory_uri() . '/library/js/app/scripts.js', ['jquery'], '1.0', true);
    wp_register_script( 'classie', get_stylesheet_directory_uri().'/library/js/vendor/classie/classie.js', ['jquery'], '1.0', true);
    wp_register_script( 'modernizr', get_template_directory_uri() . '/library/js/vendor/modernizr/modernizr.js', ['jquery'], '1.0', true);
  } else {
    wp_register_script( 'bootstrap', get_stylesheet_directory_uri().'/library/js/bootstrap.min.js', ['jquery'], '1.0', true);
    wp_register_script( 'stripped', get_template_directory_uri() . '/library/js/scripts.min.js', ['jquery'], '1.0', true);
    wp_register_script( 'classie', get_stylesheet_directory_uri().'/library/js/classie.min.js', ['jquery'], '1.0', true);
    wp_register_script( 'modernizr', get_template_directory_uri() . '/library/js/modernizr.min.js', ['jquery'], '1.0', true);
  }
    wp_enqueue_script( 'bootstrap' );
    wp_enqueue_script( 'stripped' );
    wp_enqueue_script( 'modernizr' );
    wp_enqueue_script( 'classie' );
}
add_action( 'wp_enqueue_scripts', 'theme_js' );
