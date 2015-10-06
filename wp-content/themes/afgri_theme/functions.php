<?php

/*
 *  Author: Todd Motto | @toddmotto
 *  URL: html5blank.com | @html5blank
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

//this line removes the existing Woocommerce codes

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);

remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

//This line adds a new action which adds a tag BEFORE the woocommerce "stuff"

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);

//This line adds a new action which adds a tag AFTER the woocommerce "stuff"

add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

//This line adds a new css section tag called "woogrid" BEFORE the woocommerce "stuff"

function my_theme_wrapper_start()
{
    echo '<section id="woogrid">';
}

//This line ends the new "woogrid" section

function my_theme_wrapper_end()
{
    echo '</section>';
}

add_theme_support('menus');

if (!isset($content_width)) {
    $content_width = 900;
}

if (function_exists('add_theme_support')) {
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 190, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');
    add_image_size('mycustomsize', 190, 140, true );

    // Add Support for Custom Backgrounds - Uncomment below if you're going to use
    /*add_theme_support('custom-background', array(
	'default-color' => 'FFF',
	'default-image' => get_template_directory_uri() . '/img/bg.jpg'
    ));*/

    // Add Support for Custom Header - Uncomment below if you're going to use
    /*add_theme_support('custom-header', array(
	'default-image'			=> get_template_directory_uri() . '/img/headers/default.jpg',
	'header-text'			=> false,
	'default-text-color'		=> '000',
	'width'				=> 1000,
	'height'			=> 198,
	'random-default'		=> false,
	'wp-head-callback'		=> $wphead_cb,
	'admin-head-callback'		=> $adminhead_cb,
	'admin-preview-callback'	=> $adminpreview_cb
    ));*/

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

// HTML5 Blank navigation
function html5blank_nav()
{
    wp_nav_menu(
        array(
            'theme_location' => 'header-menu',
            'menu' => '',
            'container' => 'div',
            'container_class' => 'menu-{menu slug}-container',
            'container_id' => '',
            'menu_class' => 'menu',
            'menu_id' => '',
            'echo' => true,
            'fallback_cb' => 'wp_page_menu',
            'before' => '',
            'after' => '',
            'link_before' => '',
            'link_after' => '',
            'items_wrap' => '<ul>%3$s</ul>',
            'depth' => 0,
            'walker' => ''
        )
    );
}

// Load HTML5 Blank scripts (header.php)
function html5blank_header_scripts()
{
    if (!is_admin()) {

    	wp_deregister_script('jquery'); // Deregister WordPress jQuery
    	wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', array(), '1.9.1'); // Google CDN jQuery
    	wp_enqueue_script('jquery'); // Enqueue it!
//
//    	wp_register_script('conditionizr', 'http://cdnjs.cloudflare.com/ajax/libs/conditionizr.js/4.0.0/conditionizr.js', array(), '4.0.0'); // Conditionizr
//        wp_enqueue_script('conditionizr'); // Enqueue it!
//
        wp_register_script('parsley', '//cdnjs.cloudflare.com/ajax/libs/parsley.js/1.2.2/parsley.min.js', array(), '2.6.2'); // Modernizr
        wp_enqueue_script('parsley'); // Enqueue it!

        wp_register_script('html5blankscripts', get_template_directory_uri() . '/js/scripts.js', array(), '1.0.0'); // Custom scripts
        wp_enqueue_script('html5blankscripts'); // Enqueue it!

        wp_register_script('dynatabs', get_template_directory_uri() . '/js/tdi.tabs.js', array(), '1.0.0');
        wp_enqueue_script('dynatabs'); // Enqueue it!
    }
}

// Load HTML5 Blank conditional scripts
function html5blank_conditional_scripts()
{
    if (is_page('pagenamehere')) {
        wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0'); // Conditional script(s)
        wp_enqueue_script('scriptname'); // Enqueue it!
    }
}


// Load HTML5 Blank styles
function html5blank_styles()
{
    wp_register_style('normalize', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '1.0', 'all');
    wp_enqueue_style('normalize'); // Enqueue it!

    wp_register_style('html5blank', get_template_directory_uri() . '/css/bootstrap-theme.min.css', array(), '1.0', 'all');
    wp_enqueue_style('html5blank'); // Enqueue it!

    wp_register_style('afgri-theme', get_template_directory_uri() . '/css/main2.css', array(), '1.0', 'all');
    wp_enqueue_style('afgri-theme');
}

// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'html5blank'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'html5blank'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'html5blank') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar')) {
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 50;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '... <p><a class="view-article morebutton" href="' . get_permalink($post->ID) . '">' . __('View Article', 'html5blank') . '</a></p>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

function is_200($url)
{
    $options['http'] = array(
        'method' => "HEAD",
        'ignore_errors' => 1,
        'max_redirects' => 0
    );
    $body = file_get_contents($url, NULL, stream_context_create($options));
    sscanf($http_response_header[0], 'HTTP/%*d.%*d %d', $code);
    return $code === 200;
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions($html)
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ('div' != $args['style']) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
<?php endif; ?>
    <div class="comment-author vcard">
        <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['180']); ?>
        <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
    </div>
    <?php if ($comment->comment_approved == '0') : ?>
    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
    <br/>
<?php endif; ?>

    <div class="comment-meta commentmetadata"><a
            href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
            <?php
            printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'), '  ', '');
        ?>
    </div>

    <?php comment_text() ?>

    <div class="reply">
        <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </div>
    <?php if ('div' != $args['style']) : ?>
    </div>
<?php endif; ?>
<?php
}

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'html5blank_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'html5blank_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'html5blank_styles'); // Add Theme Stylesheet
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
add_action('init', 'create_post_type_html5'); // Add our HTML5 Blank Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

// Create 1 Custom Post type for a Demo, called HTML5-Blank
function create_post_type_html5()
{
    register_taxonomy_for_object_type('category', 'html5-blank'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'html5-blank');
    register_post_type('html5-blank', // Register Custom Post Type
        array(
            'labels' => array(
                'name' => __('HTML5 Blank Custom Post', 'html5blank'), // Rename these to suit
                'singular_name' => __('HTML5 Blank Custom Post', 'html5blank'),
                'add_new' => __('Add New', 'html5blank'),
                'add_new_item' => __('Add New HTML5 Blank Custom Post', 'html5blank'),
                'edit' => __('Edit', 'html5blank'),
                'edit_item' => __('Edit HTML5 Blank Custom Post', 'html5blank'),
                'new_item' => __('New HTML5 Blank Custom Post', 'html5blank'),
                'view' => __('View HTML5 Blank Custom Post', 'html5blank'),
                'view_item' => __('View HTML5 Blank Custom Post', 'html5blank'),
                'search_items' => __('Search HTML5 Blank Custom Post', 'html5blank'),
                'not_found' => __('No HTML5 Blank Custom Posts found', 'html5blank'),
                'not_found_in_trash' => __('No HTML5 Blank Custom Posts found in Trash', 'html5blank')
            ),
            'public' => true,
            'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
            'has_archive' => true,
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'thumbnail'
            ), // Go to Dashboard Custom HTML5 Blank post for supports
            'can_export' => true, // Allows export in Tools > Export
            'taxonomies' => array(
                'post_tag',
                'category'
            ) // Add Category and Post Tags support
        ));
}

/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function html5_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}

if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Sidebar Widgets',
        'id' => 'sidebar-widgets',
        'description' => 'Widget Area',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>'
    ));
}

add_action("wp_ajax_my_register", "my_register");
add_action('wp_ajax_nopriv_my_register', 'my_register');

function my_register()
{

    if (true) {
        $error = '';
        $uname = trim($_POST['username']);
        $email = trim($_POST['email']);
        $fname = trim($_POST['first_name']);
        $lname = trim($_POST['last_name']);
        $pswrd = trim($_POST['password']);

        if (empty($_POST['username']))
            $error .= '<p class="error">Enter Username</p>';

        if (empty($_POST['email']))
            $error .= '<p class="error">Enter Email Id</p>';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $error .= '<p class="error">Enter Valid Email</p>';

        if(!is_user_logged_in()) {
            if (empty($_POST['password'])) {
                $error .= '<p class="error">Password should not be blank</p>';
            }
        }

        if (empty($_POST['first_name']))
            $error .= '<p class="error">Enter First Name</p>';
        elseif (empty($fname))
            $error .= '<p class="error">Enter Valid First Name</p>';

        if (empty($_POST['last_name']))
            $error .= '<p class="error">Enter Last Name</p>';
        elseif (empty($lname))
            $error .= '<p class="error">Enter Valid Last Name</p>';

        if (empty($error)) {

            if(is_user_logged_in()) {
                $status = wp_update_user( array( 'ID' => get_current_user_id(), 'user_login' => $uname, 'user_email' => $email, 'first_name' => $fname, 'last_name' => $lname ) );
            }
            else {
                $status = wp_create_user($uname, $pswrd, $email);
            }

            if (is_wp_error($status)) {

                $msg = '';

                foreach ($status->errors as $key => $val) {

                    foreach ($val as $k => $v) {

                        $msg[] = $v;

                    }
                }

                header('Content-type: application/json');
                echo json_encode(array(
                    'error' => array(
                        'msg' => $msg
                    ),
                ));

            } else {
                send_register_email($_POST);
                $msg = 'Registration Successful';
                header('Content-type: application/json');
                echo json_encode(array(
                    'success' => array(
                        'msg' => $msg
                    ),
                ));
            }

        } else {

            echo $error;
        }
        die(1);
    }


}

function send_register_email ($post)
{
    $message_to_user = <<<EOF
    <!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<style type="text/css">
body {
	background-image: url('http://afgri.t.whitewallweb.net/wp-content/themes/afgri_theme/email/images/images/bg.png');
	background-repeat: repeat;
	text-align: center;
}
body,td,th {
	font-family: Baskerville, "Palatino Linotype", Palatino, "Century Schoolbook L", "Times New Roman", serif;
	font-size: 14px;
	color: #37322F;
	text-align: left;
}
h1 {
	font-size: 36px;
	color: #737044;
}
h2 {
	font-size: 24px;
	color: #C6A968;
}
h3 {
	font-size: 18px;
	color: #77A29F;
}
h1,h2,h3,h4,h5,h6 {
	font-weight: bold;
}
h4 {
	font-size: 10px;
	color: #776C65;
}
a:link {
	color: #507280;
}
a:visited {
	color: #706D42;
}
a:hover {
	color: #6F95A5;
}
a:active {
	color: #C6A968;
}
</style>
</head>

<body>
<p>&nbsp;</p>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" bgcolor="#37322F"><h4 style="text-align: center">Email not displaying correctly? Click
        <webversion>here</webversion>
        to view this email online.
    </h4></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#37322F"><img src="http://www.afgridogfood.co.za/wp-content/themes/afgri_theme/email/images/images/WelcomeTop.jpg" width="600" height="196" alt=""/></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#F5F4F0"><blockquote>
<br>
<h1>Welcome! {$post['first_name']}</h1>
      <h2>Thank you for registering with AFGRI DogFolio.</h2>
    </blockquote>    <br>
</td>
  </tr>
  <tr>
    <td width="337" bgcolor="#F5F4F0"><blockquote>
      <h3>Member Benefits:</h3>
      <p> As a member of AFGRI PetFolio, you will have exclusive access to loads of great deals, pet-specific information, event invitations, news, promotions and special offers.<br><br>
        You will also stand a chance to win some great prizes in any one of our competitions and lucky draws.
        <br>
        <br>
        Best Wishes,<br>
        The AFGRI DogFolio Team <br>
        <br>
        For support and enquiries please <a href="mailto:Yolanda.Hoyer@afgri.co.za">e-mail Yolanda Hoyer.</a>
    </p>
      <p>&nbsp; </p>
    </blockquote></td>
    <td width="263" align="right" bgcolor="#F5F4F0"><img src="http://www.afgridogfood.co.za/wp-content/themes/afgri_theme/email/images/images/DogFolio-Welcome.png" width="263" height="74" alt=""/><br>
    <img src="http://www.afgridogfood.co.za/wp-content/themes/afgri_theme/email/images/images/BenefitsS.png" width="263" height="287" alt=""/></td>
  </tr>
  <tr bgcolor="#37322F">
    <td height="30" colspan="2" style="text-align: center"> <h4><tweet>Follow on Twitter</tweet> | <fblike>Like us on Facebook</fblike> | <forwardtoafriend>Forward to a friend</forwardtoafriend></h4></td>
  </tr>
  <tr bgcolor="#37322F">
    <td height="122"><blockquote>
      <h4>You got this email because you signed up for our newsletter at www.afgridogfood.co.za.<br><br>
        Our mailing address is: <br>
        AFGRI Limited<br>
        12 Byls Bridge Boulevard<br>
        Highveld Ext 73<br>
        Centurion, Gauteng 0046<br>
        South Africa<br>
<br>
Copyright © 2014 AFGRI Limited, All rights reserved.</h4>
    </blockquote></td>
    <td align="right" valign="bottom"><img src="http://www.afgridogfood.co.za/wp-content/themes/afgri_theme/email/images/images/ppbAFGRI-Logos.png" width="263" height="89" alt=""/></td>
  </tr>
  <tr bgcolor="#37322F">
    <td height="36" colspan="2" style="text-align: center"> <h4><unsubscribe>Unsubscribe from this list</unsubscribe>
      | <preferences>Update subscription preferences</preferences></h4></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>

EOF;
    add_filter( 'wp_mail_content_type', 'set_html_content_type' );
    $headers = 'From: AFGRI Dog Food <Yolanda.Hoyer@afgri.co.za>' . "\r\n";
    wp_mail( $post['email'], 'Thank you for signing up', $message_to_user, $headers);//reset content type
    //remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
}

function set_html_content_type() {

    return 'text/html';
}

function user_metadata( $user_id ){

    $meta_data = array(
        'first_name',
        'last_name',
        'mobile',
        'region',
        'user_gender',
        'newsletter'
    );

    for($i=1;$i<=10;$i++){
        array_push($meta_data,'pet_name_'.$i);
        array_push($meta_data,'breed_'.$i);
        array_push($meta_data,'pet_age_'.$i);
        array_push($meta_data,'pet_weight_'.$i);
        array_push($meta_data,'pet_gender_'.$i);
        array_push($meta_data,'pet_food_of_choice_'.$i);
        array_push($meta_data,'pet_activity_level_'.$i);
    }

    foreach ($meta_data as $item) {
        if( !empty( $_POST[$item] )){
            update_user_meta($user_id, $item, trim($_POST[$item]));
        }
    }

    update_user_meta( $user_id, 'show_admin_bar_front', false );
}
add_action( 'user_register', 'user_metadata' );
add_action( 'profile_update', 'user_metadata' );

function create_widget($name, $id, $description) {
    $args = array(
        'name'          => __( $name ),
        'id'            => $id,
        'description'   => $description,
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h3 style="color:white;">',
        'after_title'   => '</h3>'
    );

    register_sidebar($args);
}

// already handled by twiter embed
// create_widget('footer one', 'footer_one', 'first footer widget');
create_widget('footer two', 'footer_two', 'second footer widget');

/** changing default wordpres email settings */

add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');

function new_mail_from($old) {
    return 'Yolanda.Hoyer@afgri.co.za';
}
function new_mail_from_name($old) {
    return 'AFGRI Dog Food';
}
//
//add_action('woocommerce_checkout_process', 'validate_checkout_field_process');
//
//function validate_checkout_field_process() {
//    // Check if set, if its not set add an error.
//    if ( ! $_POST['billing_phone']){
//        wc_add_notice( __( 'Please enter phone number' ), 'error' );
//    }
//    $stripped = preg_replace( '/\D/', '', $_POST['billing_phone'] );
//    $_POST['billing_phone'] = $stripped;
//    if( strlen( $_POST['billing_phone'] ) != 10 ) { // Number string must equal this
//         wc_add_notice( __( 'Please enter a 10 digit phone number.' ), 'error' );
//    }
//     if ( ! $_POST['terms_and']){
//        wc_add_notice( __( 'Please accept the terms and conditions' ), 'error' );
//    }
//             
//}
//
//add_action( 'woocommerce_after_order_notes', 'custom_jock_checkout_fields' );
//
//function custom_jock_checkout_fields($checkout)
//{
//     echo '<div id="billing_terms_and conditions">';
//
//    woocommerce_form_field( 'terms_and conditions', array(
//        'type'          => 'checkbox',
//        'class'         => array('my-field-class form-row-wide'),
//        'label'         => __('I accept the <a target ="_blank" href="/general-conditions-of-online-supply">Terms and Conditions</a>'),
//        'placeholder'   => __(''),
//        ), $checkout->get_value( 'my_field_name' ));
//
//    echo '</div>';
//    
//}


?>