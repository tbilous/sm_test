<?php
/*
Author: Hall Internet Marketing
URL: https://github.com/hallme/scaffolding

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, ect.
*/

/******************************************
 *
 * TABLE OF CONTENTS
 *
 * 1. Include Files
 * 2. Scripts & Enqueueing
 * 3. Theme Support
 * 4. Custom Page Headers
 * 5. Thumbnail Size Options
 * 6. Change Name of Post Types in Admin Backend
 * 7. Menus & Navigation
 * 8. Active Sidebars
 * 9. Related Posts Function
 * 10. Comment Layout
 * 11. Search Functions
 * 12. Add First and Last Classes to Menu & Sidebar
 * 13. Add First and Last Classes to Posts
 * 14. Custom Functions
 ******************************************/

//Set up the content width value based on the theme's design.
if (!isset($content_width)) {
    $content_width = 474;
}

//Adjust content_width value for image attachment template.
function test_content_width()
{
    if (is_attachment() && wp_attachment_is_image()) {
        $GLOBALS['content_width'] = 810;
    }
}

add_action('template_redirect', 'test_content_width');

/*********************
 * 1. INCLUDE FILES
 *********************/
define('SCAFFOLDING_INCLUDE_PATH', dirname(__FILE__) . '/includes/');
require_once(SCAFFOLDING_INCLUDE_PATH . 'base-functions.php');
//require_once(SCAFFOLDING_INCLUDE_PATH.'custom-post-type.php');


/*********************
 * 2. SCRIPTS & ENQUEUEING
 *********************/
function my_scripts_method()
{
    // получаем версию jQuery
    wp_enqueue_script('jquery');
    $wp_jquery_ver = $GLOBALS['wp_scripts']->registered['jquery']->ver; // для версий WP меньше 3.6 'jquery' меняем на 'jquery-core'
    $jquery_ver = $wp_jquery_ver == '' ? '1.11.0' : $wp_jquery_ver;

    wp_deregister_script('jquery-core');
    wp_register_script('jquery-core', '//ajax.googleapis.com/ajax/libs/jquery/' . $jquery_ver . '/jquery.min.js');
    wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts', 'my_scripts_method', 99);

function test_scripts_and_styles()
{
    global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

    // modernizr (without media query polyfill)
    wp_enqueue_script('test-modernizr', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js', false, null);

    // respondjs
    wp_enqueue_script('test-respondjs', '//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js', false, null);

    // register main stylesheet
    wp_enqueue_style('test-stylesheet', get_stylesheet_directory_uri() . '/css/style.css', array(), '', 'all');

    // ie-only style sheet
    wp_enqueue_style('test-ie-only', get_stylesheet_directory_uri() . '/css/ie.css', array(), '');
    $wp_styles->add_data('test-ie-only', 'conditional', 'lt IE 9'); // add conditional wrapper around ie stylesheet

    //Magnific Popup (LightBox)
    wp_enqueue_script('test-magnific-popup-js', '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/0.9.9/jquery.magnific-popup.min.js', array('jquery'), '0.9.9', true);

    //Font Awesome (icon set)
    wp_enqueue_style('test-font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3');

    // iCheck (better radio and checkbox inputs)
    wp_enqueue_script('test-icheck', '//cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.1/icheck.min.js', array('jquery'), '1.0.1', true);

    //Chosen - http://harvesthq.github.io/chosen/
    wp_enqueue_script('chosen-js', '//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js', array('jquery'), '1.1.0', true);

    // comment reply script for threaded comments
    if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
        wp_enqueue_script('comment-reply');
    }

    //adding scripts file in the footer
    wp_enqueue_script('my-bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '', true);
    wp_enqueue_script('readmore', get_stylesheet_directory_uri() . '/js/readmore.min.js', array('jquery'), '', true);
    wp_enqueue_script('test-js', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), '', true);

}

//add admin style

//from functions.php

//First solution : one file
add_action('admin_enqueue_scripts', 'load_admin_style');
function load_admin_style()
{
    wp_register_style('admin_css', get_template_directory_uri() . '/css/admin-style.css', false, '1.0.0');
//OR
    wp_enqueue_style('admin_css', get_template_directory_uri() . '/css/admin-style.css', false, '1.0.0');
}


/*********************
 * 3. THEME SUPPORT
 *********************/

// Adding WP 3+ Functions & Theme Support
function test_theme_support()
{

    // Make theme available for translation
    load_theme_textdomain('test', get_template_directory() . '/languages');

    add_theme_support('post-thumbnails'); // wp thumbnails (sizes handled in functions.php)

    set_post_thumbnail_size(125, 125, true); // default thumb size

    /*  Feature Currently Disabled
    // wp custom background (thx to @bransonwerner for update)
    add_theme_support( 'custom-background',
        array(
        'default-image' => '',  // background image default
        'default-color' => '', // background color default (dont add the #)
        'wp-head-callback' => '_custom_background_cb',
        'admin-head-callback' => '',
        'admin-preview-callback' => ''
        )
    );
    */

    add_theme_support('automatic-feed-links'); // rss thingy

    // to add header image support go here: http://themble.com/support/adding-header-background-image-support/
    //adding custome header suport
    add_theme_support('custom-header', array(
            'default-image' => '%s/images/logo-header.png',
            'random-default' => false,
            'width' => 73,  // Make sure to set this
            'height' => 56, // Make sure to set this
            'flex-height' => true,
            'flex-width' => true,
            'default-text-color' => 'ffffff',
            'header-text' => false,
            'uploads' => true,
            'wp-head-callback' => 'test_custom_headers_callback',
            'admin-head-callback' => '',
            'admin-preview-callback' => '',
        )
    );

    // Feature Currently Disabled
    // adding post format support
    add_theme_support('post-formats',
        array(
            'aside',            // title less blurb
            'gallery',            // gallery of images
            'link',                // quick link to other site
            'image',            // an image
            'quote',            // a quick quote
            'status',            // a Facebook like status update
            'video',            // video
            'audio',            // audio
            'chat'                // chat transcript
        )
    );


    // wp menus
    add_theme_support('menus');

    // registering wp3+ menus
    register_nav_menus(
        array(
            'main-nav' => __('Main Menu', 'test'),    // main nav in header
            'footer-nav' => __('Footer Menu', 'test') // secondary nav in footer
        )
    );
} /* end scaffolding theme support */


/*********************
 * 4. CUSTOM PAGE HEADERS
 *********************/

register_default_headers(array(
    'default' => array(
        'url' => get_template_directory_uri() . '/images/logo-header.png',
        'thumbnail_url' => get_template_directory_uri() . '/images/logo-header.png',
        'description' => __('default', 'test')
    )
));

//Set header image as a BG
function test_custom_headers_callback()
{
    ?>
    <style type="text/css">.banner {
        background-image: url(<?php header_image(); ?>);
        /*-ms-behavior: url(








    <?php echo get_template_directory_uri() ?>          /includes/backgroundsize.min.htc);*/
    }</style><?php
}


/*********************
 * 5. THUMBNAIL SIZE OPTIONS
 *********************/

// Thumbnail sizes
//add_image_size( 'test-thumb-600', 600, 150, true );


/*********************
 * 6. CHANGE NAME OF POSTS TYPE IN ADMIN BACKEND
 *********************/

/* //Currently commented out. This is useful for improving UX in the WP backend
function test_change_post_menu_label() {
	global $menu;
	global $submenu;
	$menu[5][0] = 'News';
	$submenu['edit.php'][5][0] = 'All News Entries';
	$submenu['edit.php'][10][0] = 'Add News Entries';
	$submenu['edit.php'][15][0] = 'Categories'; // Change name for categories
	$submenu['edit.php'][16][0] = 'Tags'; // Change name for tags
	echo '';
}

function test_change_post_object_label() {
	global $wp_post_types;
	$labels = &$wp_post_types['post']->labels;
	$labels->name = 'News';
	$labels->singular_name = 'News';
	$labels->add_new = 'Add News Entry';
	$labels->add_new_item = 'Add News Entry';
	$labels->edit_item = 'Edit News Entry';
	$labels->new_item = 'News Entry';
	$labels->view_item = 'View Entry';
	$labels->search_items = 'Search News Entries';
	$labels->not_found = 'No News Entries found';
	$labels->not_found_in_trash = 'No News Entries found in Trash';
}
add_action( 'init', 'test_change_post_object_label' );
add_action( 'admin_menu', 'test_change_post_menu_label' );
*/


/*********************
 * 7. MENUS & NAVIGATION
 *********************/

// the main menu
function test_main_nav()
{
    // display the wp3 menu if available
    wp_nav_menu(array(
        'menu' => '',                                     // nav name
        'theme_location' => 'main-nav',                     // where it's located in the theme
        'depth' => 0,                                     // limit the depth of the nav
        'fallback_cb' => '',     // fallback function
        'theme-location' => 'primary',
        'container' => 'div',
        'container_class' => 'navbar-collapse collapse',
        'container_id' => 'navbar',
        'menu_class' => 'nav navbar-nav',
        'walker' => new test_walker_nav_menu
    ));
} /* end scaffolding main nav */

// the footer menu (should you choose to use one)
function test_footer_nav()
{
    wp_nav_menu(array(
        'container' => '',
        'container_class' => '',
        'menu' => '',
        'menu_class' => 'menu footer-menu',
        'theme_location' => 'footer-nav',
        'before' => '',
        'after' => '',
        'link_before' => '',
        'link_after' => '',
        'depth' => 0,
        'fallback_cb' => '__return_false'
    ));
} /* end scaffolding footer link */

//Custom walker to build main menu
class test_walker_nav_menu extends Walker_Nav_Menu
{
    // add classes to ul sub-menus
    function start_lvl(&$output, $depth = 0, $args = Array())
    {
        // depth dependent classes
        $indent = ($depth > 0 ? str_repeat("\t", $depth) : ''); // code indent
        $display_depth = ($depth + 1); // because it counts the first submenu as 0
        $classes = array(
            'sub-menu',
            ($display_depth % 2 ? 'menu-odd' : 'menu-even'),
            'menu-depth-' . $display_depth
        );
        $class_names = implode(' ', $classes);
        // build html
        $output .= "\n" . $indent . '<ul class="' . $class_names . '"><li><a class="menu-back-button" title="Click to Go Back a Menu"><i class="fa fa-chevron-left"></i> Back</a></li>' . "\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = Array(), $id = 0)
    {
        global $wp_query;
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $class_names = $value = '';

        //set <li> classes
        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        if ($args->has_children) {
            $classes[] = 'menu-has-children';
        }
        if (!$args->has_children) {
            $classes[] = 'menu-item-no-children';
        }
        //combine the class array into a string
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = ' class="' . esc_attr($class_names) . '"';

        //set <li> id
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

        //set outer <li> and it's attributes
        $output .= $indent . '<li' . $id . $value . $class_names . '>';

        //set <a> attributes
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : ' title="' . esc_attr(strip_tags($item->title)) . '"';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        //Add menu button links to items with children
        if ($args->has_children) {
            $menu_pull_link = '<a class="menu-button" title="Click to Open Menu"><i class="fa fa-chevron-right"></i></a>';
        } else {
            $menu_pull_link = '';
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $menu_pull_link . $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function end_el(&$output, $item, $depth = 0, $args = array())
    {
        $output .= "</li>\n";
    }

    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {

        //Set custom arg to tell if item has children
        $id_field = $this->db_fields['id'];
        if (is_object($args[0])) {
            $args[0]->has_children = !empty($children_elements[$element->$id_field]);
        }

        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}


/*********************
 * 8. ACTIVE SIDEBARS
 *********************/

// Sidebars & Widgetizes Areas
function test_register_sidebars()
{
    register_sidebar(array(
        'id' => 'left-sidebar',
        'name' => __('Left Sidebar', 'test'),
        'description' => __('The Left (primary) sidebar used for the interior menu.', 'test'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'id' => 'right-sidebar',
        'name' => __('Right Sidebar', 'test'),
        'description' => __('The Right sidebar used for the interior call to actions.', 'test'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'id' => 'header-sidebar',
        'name' => __('Header Sidebar', 'test'),
        'description' => __('The Header sidebar used for the header manifest.', 'test'),
        'before_widget' => '<div id="%1$s" class="main-header widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h1>',
        'after_title' => '</h1>',
    ));
} // don't remove this bracket!


/*********************
 * 9. RELATED POSTS FUNCTION
 *********************/

// Related Posts Function (call using test_related_posts(); )
function test_related_posts($tag_arr)
{
    echo '<ul id="test-related-posts">';
    global $post;
    $tags = wp_get_post_tags($post->ID);
    if ($tags) {
        foreach ($tags as $tag) {
            $tag_arr .= $tag->slug . ',';
        }
        $args = array(
            'tag' => $tag_arr,
            'numberposts' => 5, /* you can change this to show more */
            'post__not_in' => array($post->ID)
        );
        $related_posts = get_posts($args);
        if ($related_posts) {
            foreach ($related_posts as $post) :
                setup_postdata($post); ?>
                <li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>"
                                            title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
                <?php
            endforeach;
        } else {
            echo '<li class="no_related_post">' . __('No Related Posts Yet!', 'test') . '</li>';
        }
    }
    wp_reset_query();
    echo '</ul>';
} /* end scaffolding related posts function */


/*********************
 * 10. COMMENT LAYOUT
 *********************/

// Comment Layout
function test_comments($comment, $args, $depth)
{
$GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?>>
    <article id="comment-<?php comment_ID(); ?>" class="clearfix">
        <header class="comment-author vcard">
            <?php
            /*
                this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
                echo get_avatar($comment,$size='32',$default='<path_to_url>' );
            */
            ?>
            <!-- custom gravatar call -->
            <?php
            // create variable
            $bgauthemail = get_comment_author_email();
            ?>
            <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5($bgauthemail); ?>?s=32"
                 class="load-gravatar avatar avatar-48 photo" height="32" width="32"
                 src="<?php echo get_template_directory_uri(); ?>/images/nothing.gif"/>
            <!-- end custom gravatar call -->
            <?php printf(__('<cite class="fn">%s</cite>', 'test'), get_comment_author_link()) ?>
            <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a
                    href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>"><?php comment_time(__('F jS, Y', 'test')); ?> </a>
            </time>
            <?php edit_comment_link(__('(Edit)', 'test'), '  ', '') ?>
        </header>
        <?php if ($comment->comment_approved == '0') : ?>
            <div class="alert info">
                <p><?php _e('Your comment is awaiting moderation.', 'test') ?></p>
            </div>
        <?php endif; ?>
        <section class="comment_content clearfix">
            <?php comment_text() ?>
        </section>
        <?php comment_reply_link(array_merge($args, array(
            'depth' => $depth,
            'max_depth' => $args['max_depth']
        ))) ?>
    </article>
    <!-- </li> is added by WordPress automatically -->
    <?php
    } // don't remove this bracket!


    /*********************
     * 11. SEARCH FUNCTIONS
     *********************/

    // Search Form
    function test_wpsearch($form)
    {
        $form = '<form role="search" method="get" id="searchform" action="' . home_url('/') . '" >
	<label class="screen-reader-text" for="s">' . __('Search for:', 'test') . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . esc_attr__('Search the Site...', 'test') . '" />
	<input type="submit" id="searchsubmit" value="' . esc_attr__('Search') . '" />
	</form>';

        return $form;
    } // don't remove this bracket!

    /*********************
     * 12. ADD FIRST AND LAST CLASSES TO MENU & SIDEBAR
     *********************/

    function test_add_first_and_last($output)
    {
        $output = preg_replace('/class="menu-item/', 'class="first-item menu-item', $output, 1);
        $last_pos = strripos($output, 'class="menu-item');
        if ($last_pos !== false) {
            $output = substr_replace($output, 'class="last-item menu-item', $last_pos, 16 /* 16 = hardcoded strlen('class="menu-item') */);
        }

        return $output;
    }

    add_filter('wp_nav_menu', 'test_add_first_and_last');

    // Add "first" and "last" CSS classes to dynamic sidebar widgets. Also adds numeric index class for each widget (widget-1, widget-2, etc.)
    function test_widget_first_last_classes($params)
    {

        global $my_widget_num; // Global a counter array
        $this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
        $arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets

        if (!$my_widget_num) {// If the counter array doesn't exist, create it
            $my_widget_num = array();
        }

        if (!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
            return $params; // No widgets in this sidebar... bail early.
        }

        if (isset($my_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
            $my_widget_num[$this_id]++;
        } else { // If not, create it starting with 1
            $my_widget_num[$this_id] = 1;
        }

        $class = 'class="widget-' . $my_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options

        if ($my_widget_num[$this_id] == 1) { // If this is the first widget
            $class .= 'first-widget ';
        } elseif ($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) { // If this is the last widget
            $class .= 'last-widget ';
        }

        $params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"

        return $params;

    }

    add_filter('dynamic_sidebar_params', 'test_widget_first_last_classes');


    /*********************
     * 13. ADD FIRST AND LAST CLASSES TO POSTS
     *********************/

    function test_post_classes($classes)
    {
        global $wp_query;
        if ($wp_query->current_post == 0) {
            $classes[] = 'first-post';
        } elseif (($wp_query->current_post + 1) == $wp_query->post_count) {
            $classes[] = 'last-post';
        }

        return $classes;
    }

    add_filter('post_class', 'test_post_classes');


    /*********************
     * 14. CUSTOM FUNCTIONS
     *********************/

    // This removes the annoying […] to a Read More link
    function test_excerpt_more($more)
    {
        global $post;

        // edit here if you like
        return '...  <a class="test_excerpt_more" href="' . get_permalink($post->ID) . '" title="' . __('Read', 'test') . get_the_title($post->ID) . '">' . __('<span>More</span>', 'test') . '</a>';
    }

    //This is a modified the_author_posts_link() which just returns the link.
    //This is necessary to allow usage of the usual l10n process with printf().
    function test_get_the_author_posts_link()
    {
        global $authordata;
        if (!is_object($authordata)) {
            return false;
        }
        $link = sprintf(
            '<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
            get_author_posts_url($authordata->ID, $authordata->user_nicename),
            esc_attr(sprintf(__('Posts by %s'), get_the_author())), // No further l10n needed, core will take care of this one
            get_the_author()
        );

        return $link;
    }


    function true_remove_default_widget()
    {
        unregister_widget('WP_Widget_Text'); // Текст
        unregister_widget('WP_Widget_Recent_Posts'); // Текст
    }

    add_action('widgets_init', 'true_remove_default_widget', 20);


    class MY_Widget_Text extends WP_Widget
    {

    public function __construct()
    {
        $widget_ops = array('classname' => 'widget_text', 'description' => __('Arbitrary text or HTML.'));
        $control_ops = array('width' => 400, 'height' => 350);
        parent::__construct('text', __('Text'), $widget_ops, $control_ops);
    }

    public function widget($args, $instance)
    {

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $widget_text = !empty($instance['text']) ? $instance['text'] : '';

        $text = apply_filters('widget_text', $widget_text, $instance, $this);

        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        } ?>
        <ul class="textwidget list-unstyled">
            <?php echo !empty($instance['filter']) ? wpautop($text) : $text; ?>
        </ul>
        <?php
        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        if (current_user_can('unfiltered_html')) {
            $instance['text'] = $new_instance['text'];
        } else {
            $instance['text'] = wp_kses_post(stripslashes($new_instance['list']));
        }
        $instance['filter'] = !empty($new_instance['filter']);

        return $instance;
    }

    public function form($instance)
    {
    $instance = wp_parse_args((array)$instance, array('title' => '', 'text' => ''));
    $filter = isset($instance['filter']) ? $instance['filter'] : 0;
    $title = sanitize_text_field($instance['title']);
    ?>
    <li><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
               name="<?php echo $this->get_field_name('title'); ?>" type="text"
               value="<?php echo esc_attr($title); ?>"/></li>

    <li><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Content:'); ?></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>"
                      name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea($instance['text']); ?></textarea>
    </li>

    <li><input id="<?php echo $this->get_field_id('filter'); ?>"
               name="<?php echo $this->get_field_name('filter'); ?>"
               type="checkbox"<?php checked($filter); ?> />&nbsp;<label
            for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label>
    </li>
<?php
}
}

// Creating the widget
class wpb_widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
// Base ID of your widget
            'wpb_widget',

// Widget name will appear in UI
            __('Header Text Widget', 'wpb_widget_domain'),

// Widget description
            array('description' => __('Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain'),)
        );
    }

// Creating widget front-end
// This is where the action happens

    public function widget($args, $instance)
    {
        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $widget_text = !empty($instance['text']) ? $instance['text'] : '';

        $text = apply_filters('widget_text', $widget_text, $instance, $this);

        echo $args['before_widget'];

        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        } ?>

        <?php echo !empty($instance['filter']) ? wpautop($text) : $text; ?>

        <?php
        echo $args['after_widget'];
    }

// Widget Backend
    public function form($instance)
    {
        $instance = wp_parse_args((array)$instance, array('title' => '', 'text' => ''));
        $filter = isset($instance['filter']) ? $instance['filter'] : 0;
        $title = sanitize_text_field($instance['title']);
        ?>
        <li><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/></li>

        <li><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Content:'); ?></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>"
                      name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea($instance['text']); ?></textarea>
        </li>

        <li><input id="<?php echo $this->get_field_id('filter'); ?>"
                   name="<?php echo $this->get_field_name('filter'); ?>"
                   type="checkbox"<?php checked($filter); ?> />&nbsp;<label
                for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label>
        </li>
        <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        if (current_user_can('unfiltered_html')) {
            $instance['text'] = $new_instance['text'];
        } else {
            $instance['text'] = wp_kses_post(stripslashes($new_instance['list']));
        }
        $instance['filter'] = !empty($new_instance['filter']);

        return $instance;
    }
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget()
{
    register_widget('wpb_widget');
}

add_action('widgets_init', 'wpb_load_widget');

class MY_Widget_Recent_Posts extends WP_Widget
{

    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget_recent_entries',
            'description' => __("Your site&#8217;s most recent Posts.")
        );
        parent::__construct('recent-posts', __('Recent Posts'), $widget_ops);
        $this->alt_option_name = 'widget_recent_entries';
    }

    public function widget($args, $instance)
    {
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $title = (!empty($instance['title'])) ? $instance['title'] : __('Recent Posts');

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
        if (!$number) {
            $number = 5;
        }
        $show_date = isset($instance['show_date']) ? $instance['show_date'] : false;

        $r = new WP_Query(apply_filters('widget_posts_args', array(
            'posts_per_page' => $number,
            'no_found_rows' => true,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true
        )));

        if ($r->have_posts()) :
            ?>
            <?php echo $args['before_widget']; ?>
            <?php if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        } ?>

            <?php while ($r->have_posts()) : $r->the_post(); ?>
            <ul class="widget-recent-list list-inline">
                <li class="">
                    <?php if ($show_date) : ?>
                        <ul class="list-unstyled text-center recent-date">
                            <li class="post-date"><?php echo get_the_date('j'); ?></li>
                            <li class="post-date"><?php echo get_the_date('M'); ?></li>
                        </ul>
                    <?php endif; ?>
                </li>
                <li class="recent-text">
                    <a href="<?php the_permalink(); ?>">
                        <?php get_the_title() ? the_title() : the_ID(); ?>
                    </a>
                    <?php //the_content('...'); ?>
                    <p><?php echo excerpt(12); ?></p>
                </li>
            </ul>
        <?php endwhile; ?>
            <?php echo $args['after_widget']; ?>
            <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = (int)$new_instance['number'];
        $instance['show_date'] = isset($new_instance['show_date']) ? (bool)$new_instance['show_date'] : false;

        return $instance;
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? (bool)$instance['show_date'] : false;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>

        <p><label
                for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>"
                   name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1"
                   value="<?php echo $number; ?>" size="3"/></p>

        <p><input class="checkbox" type="checkbox"<?php checked($show_date); ?>
                  id="<?php echo $this->get_field_id('show_date'); ?>"
                  name="<?php echo $this->get_field_name('show_date'); ?>"/>
            <label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Display post date?'); ?></label>
        </p>
        <?php
    }
}

function true_add_default_widget()
{
    register_widget('MY_Widget_Text'); // Текст
    register_widget('MY_Widget_Recent_Posts'); // Текст
}

add_action('widgets_init', 'true_add_default_widget');


function excerpt($limit)
{
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(" ", $excerpt) . "<a class='link-more' href=" . get_the_permalink() . "> ...</a>";
    } else {
        $excerpt = implode(" ", $excerpt);
    }
    $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
    return $excerpt;
}
function excerpt_black_btn($limit)
{
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(" ", $excerpt) . "<a class='collapsed-link link-more collapsed-link__gallery' href=" . get_the_permalink() . "> <span class=\"btn btn-clear\">More</span> <span class=\"btn btn-clear  fa fa-angle-right\"></span></a>";
    } else {
        $excerpt = implode(" ", $excerpt);
    }
    $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
    return $excerpt;
}

function content($limit)
{
    $content = explode(' ', get_the_content(), $limit);
    if (count($content) >= $limit) {
        array_pop($content);
        $content = implode(" ", $content) . '...';
    } else {
        $content = implode(" ", $content);
    }
    $content = preg_replace('/\[.+\]/', '', $content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}


add_theme_support('post-thumbnails');

add_action('after_setup_theme', 'test_custom_thumbnail_size');
function test_custom_thumbnail_size()
{
    add_image_size('thumb-small', 200, 200, true); // Hard crop to exact dimensions (crops sides or top and bottom)
    add_image_size('thumb-medium', 520, 9999); // Crop to 520px width, unlimited height
    add_image_size('thumb-large', 720, 340); // Soft proprtional crop to max 720px width, max 340px height
}

//Example
//if ( has_post_thumbnail() ) { the_post_thumbnail( 'thumb-small' ); }


