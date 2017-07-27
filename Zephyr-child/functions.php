<?php
#######################################
### Custom functions code goes here ###
#######################################


/* Include a functions file to deal specifically with subjects */
include __DIR__."/subject_functions.php";

/* Apply some custom CSS styles to the ADMIN SECTION for different logged in roles */
function my_admin_css() {
    echo '<style>.us-hb-screenlock{display: none;}#colorbox, #cboxOverlay, #cboxWrapper{z-index:100001;}</style>';

    global $current_user;
    wp_get_current_user();
    $role = ($current_user->roles[0]);
    # Specific CSS for both Leader & Manager roles
    if($role === 'leader' || $role === 'manager'){
        # Hide the Slider Revolution menu item
        echo '<style>#toplevel_page_revslider {display: none;}</style>';
    }

    # Specific CSS for leader role only
    if($role === 'leader'){
        echo '<style>#postbox-container-2{clear: both;width: calc(100% + 300px) !important;}#post-body.columns-2 #side-sortables{min-height:10px;}#titlewrap, #titlewrap * {user-select: none;pointer-events: none;}.closed .inside{display:block !important;}#preview-action{position: absolute;left: 11px;bottom: 11px;}.vc_controls-row .vc_column-add{left:0;position:absolute}.vc_column-move{visibility: hidden}#categorydiv,#pageparentdiv,.row-actions,.check-column,.wp-list-table.posts tfoot,#menu-posts-subject ul,.search-box,#screen-meta-links,.subsubsub,.tablenav,#slugdiv,#wpfooter,#postimagediv,.handlediv,#misc-publishing-actions,.vc_column-clone,.vc_column-delete,.vc_column-toggle,#us_sidebar_settings,#us_header_settings,#us_titlebar_settings,#us_footer_settings,#postdivrich,.page-title-action,#mymetabox_revslider_0,#edit-slug-buttons,.composer-switch,#vc_navbar,#menu-posts,#menu-media,#menu-tools,#toplevel_page_vc-welcome{display:none !important;} .wpb_content_element:hover>.vc_controls, .vc_shortcodes_container > .vc_controls .vc_control, .wpb_row_container > .wpb_vc_column > .vc_controls .vc_control, .wpb_row_container > .wpb_vc_column_inner > .vc_controls .vc_control{filter: alpha(opacity=100) !important;opacity: 1 !important;visibility:visible !important;}</style>';
    }
    # Specific CSS for manager role only
    if($role === 'manager') {
        echo '<style>
                    #menu-tools, #toplevel_page_vc-welcome, .check-column, .bulkactions, #new_role, #new_role2, #changeit, #changeit2, 
                    .ai1ec-panel:nth-child(3), #postcustom, #commentstatusdiv,
                    li#menu-posts-ai1ec_event ul.wp-submenu li:nth-child(5), li#menu-posts-ai1ec_event ul.wp-submenu li:nth-child(6), li#menu-posts-ai1ec_event ul.wp-submenu li:nth-child(7), li#menu-posts-ai1ec_event ul.wp-submenu li:nth-child(8)  
                    {display: none !important;}
             </style>';
    }
}
add_action('admin_head', 'my_admin_css');

/* Add some additional CSS styles for specific user roles to the FRONT END site
/* No longer needed as entire theme display dropdown link removed for all users
function user_role_specific_css() {
    global $current_user;
    wp_get_current_user();
    $role = ($current_user->roles[0]);
    # Specific CSS for manager role only
    if($role === 'manager') {
        echo '  <style>
                    #wp-admin-bar-us_theme_otions {display: none !important;}       /* This hides the "Theme options" admin menu link from any "Managers" /*
                </style>';
    }
}
add_action('wp_head', 'user_role_specific_css');
*/



/* Add a custom editing menu option in the admin system */
function add_edit_subjects_admin_menu( $wp_admin_bar ) {

    global $current_user;
    wp_get_current_user();
    $role = ($current_user->roles[0]);
    $wp_admin_bar->remove_node( 'us_theme_otions' );
    if($role === 'leader') {
        $sid = get_field('leader_subject', 'user_'.$current_user->ID);
        $args = array(
          'id' => 'subject'.$id,
          'title' => __( 'Edit Subjects', 'us' ),
          'href' => admin_url( 'edit.php?post_type=subject' ),
          'parent' => 'site-name'
        );
        $wp_admin_bar->add_node( $args );
    }
}
add_action( 'admin_bar_menu', 'add_edit_subjects_admin_menu', 999 );


/* Preset the calendar categories based on the Trainee's linked subject */
function presetTraineeCalendarCategories() {
    global $current_user;
    wp_get_current_user();
    $role = ($current_user->roles[0]);
    if($role === 'trainee' && shortcode_exists( 'ai1ec' )) {

    }
}
//presetTraineeCalendarCategories();




/* ??? I don't understand the point of this */
function custom_upload_filter( $file ){
    $file['name'] = $file['name'];
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'custom_upload_filter' );

/* Specify a custom 'Course Information' menu */
function register_cinfo() {
    register_nav_menu('cinfo',__( 'Course Information' ));
}
add_action( 'init', 'register_cinfo' );


/* Google API key */
function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyBC5_o8JlxyLFjRJ0yC2vXZnl57AuoqRaU');
}
add_action('acf/init', 'my_acf_init');

/* Specify CSS & JS file override files for the WP login page */
function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/user-login.css' );
    wp_enqueue_script('custom-jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js');                               // Load the jQuery library
    wp_enqueue_script( 'custom-login', get_stylesheet_directory_uri() . '/user-login.js', array( 'jquery' ), 1.0, true );           // Load a custom javascript file
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet', 100 );

/* Specify the CSS override file for the footer */
function add_footer_styles() {
    wp_enqueue_style( 'custom-footer', get_stylesheet_directory_uri() . '/footer-styles.css' );
}
add_action( 'get_footer', 'add_footer_styles', 100 );


/* Modify the logo URL & title text */
function my_login_logo_url() {return get_bloginfo( 'url' );}
add_filter( 'login_headerurl', 'my_login_logo_url' );
function my_login_logo_url_title() {return 'GITEP intranet';}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

/* Modify the default login text on the login button */
function register_text( $translated ) {return str_ireplace(  'Log In',  'SUBMIT',  $translated );}
add_filter(  'gettext',  'register_text'  );

/* Modify the "Remember Me" text so can be used as a header */
function lost_password_text( $translated ) {return str_ireplace(  'Remember Me',  'Training Portal Login',  $translated );}
add_filter('gettext', 'lost_password_text');

/* Remove the invalid login page shake */
function my_login_head() {remove_action('login_head', 'wp_shake_js', 12);}
add_action('login_head', 'my_login_head');

/* Change the default error message that's displayed on an incorrect login */
function login_error_override() {return 'Incorrect login details... <a href="wp-login.php?action=lostpassword">lost your password?</a>';}
add_filter('login_errors', 'login_error_override');

/* Modify lost password text */
function lost_password_button_text( $translated ) {return str_ireplace(  'Get New Password',  'Request password link',  $translated );}
add_filter( 'gettext', 'lost_password_button_text' );

# MANAGERS  - uncomment this to update the Manager roles
/*
if( get_role('manager') ){
    remove_role( 'manager' );
    $result = add_role( 'manager', __('Manager'),
        array(

            'level_4' => true,
            'level_3' => true,
            'level_2' => true,
            'level_1' => true,
            'level_0' => true,

            'read' => true,
            'edit_dashboard' => true,

            'read_ai1ec_event' => true,
			'edit_ai1ec_event' => true,
			'edit_ai1ec_events' => true,
			'edit_private_ai1ec_events' => true,
			'edit_published_ai1ec_events' => true,
			'delete_ai1ec_event' => true,
			'delete_ai1ec_events' => true,
			'delete_published_ai1ec_events' => true,
			'delete_private_ai1ec_events' => true,
			'publish_ai1ec_events' => true,
			'read_private_ai1ec_events' => true,
			'manage_events_categories' => true,
			'manage_ai1ec_feeds' => true,
			'edit_others_ai1ec_events' => true,
			'delete_others_ai1ec_events' => true,
            'switch_ai1ec_themes' => false,
			'manage_ai1ec_options' => false,

            'create_users' => true,
            'delete_users' => true,
            'add_users' => true,
            'remove_users' => true,
            'edit_users' => true,
            'list_users' => true,
            'promote_users' => true,

            'upload_files' => true,
            'edit_files' => true,

            'publish_posts' => true,
            'edit_posts' => true,
            'edit_published_posts' => true,
            'delete_posts' => true,
            'delete_published_posts' => true,
            'read_private_posts' => true,
            'edit_private_posts' => true,
            'delete_private_posts' => true,
            'delete_others_posts' => true,
            'edit_others_posts' => true,

            'publish_pages' => true,
            'edit_pages' => true,
            'edit_published_pages' => true,
            'delete_pages' => true,
            'delete_published_pages' => true,
            'read_private_pages' => true,
            'edit_private_pages' => true,
            'delete_private_pages' => true,
            'delete_others_pages' => true,
            'edit_others_pages' => true,

            'manage_categories' => false,
            'manage_links' => false,
            'update_core' => false,
            'moderate_comments' => false,
            'unfiltered_html' => false,
            'unfiltered_upload' => false,
            'manage_options' => false,
            'install_plugins' => false,
            'activate_plugins' => false,
            'update_plugins' => false,
            'edit_plugins' => false,
            'delete_plugins' => false,
            'install_themes' => false,
            'edit_themes' => false,
            'update_themes' => false,
            'edit_theme_options' => false,
            'switch_themes' => false,
            'delete_themes' => false,
            'import' => false,
            'export' => false

        )
    );
}
*/


# LEADERS  - uncomment this to update the Leader roles
/*
if( get_role('leader') ){
    remove_role( 'leader' );
    $result = add_role( 'leader', __('Leader'),
      array(
        'upload_files' => true,
        'edit_posts' => true,
        'edit_published_posts' => true,
        'level_4' => true,
        'level_3' => true,
        'level_2' => true,
        'level_1' => true,
        'level_0' => true,


        'read' => false,
        'edit_files' => false,
        'publish_posts' => false,
        'delete_posts' => false,
        'delete_published_posts' => false,
        'read_private_posts' => false,
        'edit_private_posts' => false,
        'delete_private_posts' => false,
        'publish_pages' => false,
        'edit_pages' => false,
        'edit_published_pages' => false,
        'delete_pages' => false,
        'delete_published_pages' => false,
        'read_private_pages' => false,
        'edit_private_pages' => false,
        'delete_private_pages' => false,
        'manage_categories' => false,
        'manage_links' => false,
        'delete_others_posts' => false,
        'delete_others_pages' => false,
        'edit_others_posts' => false,
        'edit_others_pages' => false,
        'update_core' => false,
        'moderate_comments' => false,
        'unfiltered_html' => false,
        'unfiltered_upload' => false,
        'edit_dashboard' => false,
        'manage_options' => false,
        'install_plugins' => false,
        'activate_plugins' => false,
        'update_plugins' => false,
        'edit_plugins' => false,
        'delete_plugins' => false,
        'install_themes' => false,
        'edit_themes' => false,
        'update_themes' => false,
        'edit_theme_options' => false,
        'switch_themes' => false,
        'delete_themes' => false,
        'list_users' => false,
        'edit_users' => false,
        'promote_users' => false,
        'remove_users' => false,
        'import' => false,
        'export' => false

      )
    );
}
*/


# TRAINEES  - uncomment this to update the Trainee roles
/*
if( get_role('trainee') ){
    remove_role( 'trainee' );
    $result = add_role( 'trainee', __('Trainee'),
      array(
        'read' => false, // true allows this capability
        'edit_posts' => false, // Allows user to edit their own posts
        'edit_pages' => false, // Allows user to edit pages
        'edit_others_posts' => false, // Allows user to edit others posts not just their own
        'create_posts' => false, // Allows user to create new posts
        'manage_categories' => false, // Allows user to manage post categories
        'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode
        'edit_themes' => false, // false denies this capability. User can’t edit your theme
        'install_plugins' => false, // User cant add new plugins
        'update_plugin' => false, // User can’t update any plugins
        'update_core' => false // user cant perform core updates
      )
    );
}
*/

/* remove some of the standard in-built Wordpress roles */
if( get_role('subscriber') ){
    remove_role( 'subscriber' );
}
if( get_role('contributor') ){
    remove_role( 'contributor' );
}
if( get_role('author') ){
    remove_role( 'author' );
}
if( get_role('editor') ){
    remove_role( 'editor' );
}
if( get_role('manage_schema_options') ){
    remove_role( 'manage_schema_options' );
}

/* Remove various standard Wordpress admin elements from the admin toolbar */
function my_edit_toolbar($wp_toolbar) {
	$wp_toolbar->remove_node('wp-logo');
	$wp_toolbar->remove_node('customize');
	$wp_toolbar->remove_node('updates');
	$wp_toolbar->remove_node('comments');
	$wp_toolbar->remove_node('new-content');
}
add_action('admin_bar_menu', 'my_edit_toolbar', 999);


/* Create a menu system to display the logged in user's nickname & any required dropdowns */
function user_menu_shortcode( $atts, $shortcode_name = null ) {
    $logout = esc_url( wp_logout_url('home') );
    global $current_user;
    wp_get_current_user();
    $username = $current_user->nickname;
	$data = <<<EOT
<div class="w-dropdown source_own ush_dropdown_1"><div class="w-dropdown-h"><div class="w-dropdown-list" style="display: none; height: 0px;">
<a class="w-dropdown-item" href="$logout"><span class="w-dropdown-item-title">Logout</span></a>
</div><div class="w-dropdown-current"><a class="w-dropdown-item" href="javascript:void(0)"><span class="w-dropdown-item-title">$username</span></a></div></div></div>
EOT;

	return $data;
}
add_shortcode( 'user_menu', 'user_menu_shortcode' );

/* Get the address from the database */
function get_address () {
    global $wpdb;
    $query = "SELECT options FROM " . $wpdb->prefix . "structuring_markup" . " WHERE type = 'local_business' ORDER BY type ASC";
    $results = $wpdb->get_results( $query );
    $options = unserialize( $results[0]->options );
    return $options;
}
/* Returns a formatted address */
function get_business_address( $atts ) {
    $a = shortcode_atts( array(
        'link' => false,
        'tag' => 'p',
        'class' => '',
        'label' => 'Address',
        'icon' => '',
    ), $atts );
    $addr = get_address();
    $option = '<span style="float:left;">' . $addr['street_address'] . '<br>' . $addr['address_locality'] . '<br>' . $addr['address_region'] . '<br>' . $addr['postal_code'] . '</span>';

    $class = ($a['class'] != '' ? 'i-cf ' . esc_attr($a['class']) : 'i-cf');
    $label = ($a['label'] != '' ? '<strong style="line-height:inherit;float:left;">' . esc_attr($a['label']) . ':&nbsp;</strong> ' : '');
    $icon = ($a['icon'] != '' ? '<i style="line-height:inherit;float:left;" class="info-icon fa ' . esc_attr($a['icon']) . '"></i>' : '');
    if( $a['tag'] != 'none') {
        $output = '<' . esc_attr($a['tag']) . ' class="' . $class . '">' . $icon . $label . $option . '</' . esc_attr($a['tag']) . '>';
    }
    else {
        $value = (esc_attr($a['link']) != 'false' ? '<a ' . $class . ' href="tel:' . $option . '">' . $icon . $option . '</a>' : $icon . $option );
        $output = $label . $value;
    }

    return $output;
}
add_shortcode( 'org_address', 'get_business_address' );

function get_organization_options ($param) {
    global $wpdb;
    $query = "SELECT options FROM " . $wpdb->prefix . "structuring_markup" . " WHERE type = 'organization' ORDER BY type ASC";
    $results = $wpdb->get_results( $query );
    $options = unserialize( $results[0]->options );
    return $options[$param];
}

/* Returns an email address with a formatted mailto link action */
function get_org_email( $atts ) {
    $a = shortcode_atts( array(
        'link' => true,
        'tag' => 'p',
        'class' => '',
        'label' => 'Email',
        'icon' => '',
    ), $atts );
    $option = get_organization_options('email');
    $class = ($a['class'] != '' ? 'class="' . esc_attr($a['class']) . '"' : '');
    $label = ($a['label'] != '' ? '<strong>' . esc_attr($a['label']) . ':</strong> ' : '');
    $icon = ($a['icon'] != '' ? '<i class="info-icon fa ' . esc_attr($a['icon']) . '"></i>' : '');
    if( $a['tag'] != 'none') {
        $value = (esc_attr($a['link']) != 'false' ? '<a href="mailto:' . $option . '">' . $option . '</a>' : $option );
        $output = '<' . esc_attr($a['tag']) . ' ' . $class . '>' . $icon . $label . $value . '</' . esc_attr($a['tag']) . '>';
    }
    else {
        $value = (esc_attr($a['link']) != 'false' ? '<a ' . $class . ' href="mailto:' . $option . '">' . $icon . $option . '</a>' : $icon . $option );
        $output = $label . $value;
    }
    return $output;
}
add_shortcode( 'org_email', 'get_org_email' );

/* Returns a nicely formatted telephone number */
function get_org_phone( $atts ) {
    $a = shortcode_atts( array(
        'link' => true,
        'tag' => 'p',
        'class' => '',
        'label' => 'Phone',
        'icon' => '',
    ), $atts );
    $option = get_organization_options('telephone');
    $option_formatted = preg_replace('~(\d{2})[^\d]{0,7}(\d{4})[^\d]{0,7}(\d{3})(\d{3}).*~', '0$2 $3 $4', str_replace('+','',$option));

    $class = ($a['class'] != '' ? 'class="' . esc_attr($a['class']) . '"' : '');
    $label = ($a['label'] != '' ? '<strong>' . esc_attr($a['label']) . ':</strong> ' : '');
    $icon = ($a['icon'] != '' ? '<i class="info-icon fa ' . esc_attr($a['icon']) . '"></i>' : '');
    if( $a['tag'] != 'none') {
        $value = (esc_attr($a['link']) != 'false' ? '<a href="tel:' . $option . '">' . $option_formatted . '</a>' : $option_formatted );
        $output = '<' . esc_attr($a['tag']) . ' ' . $class . '>' . $icon . $label . $value . '</' . esc_attr($a['tag']) . '>';
    }
    else {
        $value = (esc_attr($a['link']) != 'false' ? '<a ' . $class . ' href="tel:' . $option . '">' . $icon . $option_formatted . '</a>' : $icon . $option_formatted );
        $output = $label . $value;
    }
    return $output;
}
add_shortcode( 'org_phone', 'get_org_phone' );

/* Create social media links */
function get_social( $atts ) {
    $a = shortcode_atts( array(
        'link' => true,
        'tag' => 'p',
        'class' => '',
        'label' => '',
        'icon' => '',
        'account' => 'facebook',
        'text' => '',
    ), $atts );
    $social = get_organization_options('social');
    $option = $social[$a['account']];
    $class = ($a['class'] != '' ? 'class="' . esc_attr($a['class']) . '"' : '');
    $label = ($a['label'] != '' ? '<strong>' . esc_attr($a['label']) . ':</strong> ' : '');

    switch ($a['account']) {
        case 'facebook':
            $link = ($option != '' ? $option : 'https://www.facebook.com/public?type=pages&query='.get_organization_options('name'));
            $text = ($a['text'] != '' ? $a['text'] : ' &nbsp; Facebook');
            $icon = ($a['icon'] != '' ? '<i class="info-icon fa ' . esc_attr($a['icon']) . '"></i>' : '<i class="info-icon fa fa fa-facebook-official"></i>');
            break;
        case 'twitter':
            $link = ($option != '' ? $option : 'https://twitter.com/search?src=typd&f=users&q='.get_organization_options('name'));
            $text = ($a['text'] != '' ? $a['text'] : ' &nbsp; Twitter');
            $icon = ($a['icon'] != '' ? '<i class="info-icon fa ' . esc_attr($a['icon']) . '"></i>' : '<i class="info-icon fa fa fa-twitter"></i>');
            break;
        case 'google':
            $link = ($option != '' ? $option : 'https://plus.google.com/s/'.get_organization_options('name').'/people');
            $text = ($a['text'] != '' ? $a['text'] : ' &nbsp; Google+');
            $icon = ($a['icon'] != '' ? '<i class="info-icon fa ' . esc_attr($a['icon']) . '"></i>' : '<i class="info-icon fa fa fa-google-plus-official"></i>');
            break;
        case 'instagram':
            $link = ($option != '' ? $option : 'https://www.instagram.com/explore/tags/'.get_organization_options('name'));
            $text = ($a['text'] != '' ? $a['text'] : ' &nbsp; Instagram');
            $icon = ($a['icon'] != '' ? '<i class="info-icon fa ' . esc_attr($a['icon']) . '"></i>' : '<i class="info-icon fa fa fa-instagram"></i>');
            break;
        case 'youtube':
            $link = ($option != '' ? $option : 'https://www.youtube.com/results?sp=EgIQAg%253D%253D&q='.get_organization_options('name'));
            $text = ($a['text'] != '' ? $a['text'] : ' &nbsp; YouTube');
            $icon = ($a['icon'] != '' ? '<i class="info-icon fa ' . esc_attr($a['icon']) . '"></i>' : '<i class="info-icon fa fa fa-youtube"></i>');
            break;
        case 'linkedin':
            $link = ($option != '' ? $option : 'https://www.linkedin.com/search/results/companies/?keywords='.get_organization_options('name'));
            $text = ($a['text'] != '' ? $a['text'] : ' &nbsp; LinkedIn');
            $icon = ($a['icon'] != '' ? '<i class="info-icon fa ' . esc_attr($a['icon']) . '"></i>' : '<i class="info-icon fa fa fa-linkedin"></i>');
            break;
    }

    if( $a['tag'] != 'none') {
        $value = (esc_attr($a['link']) != 'false' ? '<a target="_blank" href="' . $link . '">' . $icon . $text . '</a>' : $text );
        $output = '<' . esc_attr($a['tag']) . ' ' . $class . '>' . $label . $value . '</' . esc_attr($a['tag']) . '>';
    }
    else {
        $value = (esc_attr($a['link']) != 'false' ? '<a target="_blank" ' . $class . ' href="' . $link . '">' . $icon . $text . '</a>' : $icon . $text );
        $output = $label . $value;
    }
    return $output;
}
add_shortcode( 'org_social', 'get_social' );

/* Get the currently logged in user's nickname, so can be used as part of the menu display */
function get_nickname () {
    global $current_user;
    wp_get_current_user();
	if ( is_user_logged_in() ) {
		echo $current_user->nickname;
	} else {
		echo '';
	}
}
add_shortcode('user_nickname', 'get_nickname');

/* If the currently logged in role is a trainee, obtain the course leader's details; & display in the sidebar */
function get_leader () {
    global $current_user;
    wp_get_current_user();
    $role = ($current_user->roles[0]);
    if ($role === 'trainee'){

        $sub = get_field('trainee_subject', 'user_'.$current_user->ID);
        $userSub = get_post_field( 'post_name', $sub[0] );

        $leader = get_field('trainee_leader', 'user_'.$current_user->ID);
        $subject = get_field('leader_subject', 'user_'.$leader[ID]);
        $subject_title = get_the_title($subject[0]);

        $school = get_field('leader_school', 'user_'.$leader[ID]);
        $school_data = get_field('place', $school);
        $data = <<<EOT
<div class="widgetavatarimage">$leader[user_avatar]</div>
<h3 class="widgettitle">Your Course Leader</h3>
<p class="leader-title">$leader[user_firstname] $leader[user_lastname]</p>
<p>$subject_title Subject Leader</p>
<p><i class="fa fa-phone" aria-hidden="true"></i> $school_data[formatted_phone_number]</p>
<p><i class="fa fa-envelope-o" aria-hidden="true"></i> <a href="mailto:$leader[user_email]">$leader[user_email]</a></p>
EOT;
    }

    return $data;

}
add_shortcode('leader', 'get_leader');


/*
We want users with a 'Manager' role to be able to add new users & edit existing users, but we don't want them to be able to:
1. Add new Administrators
2. Delete Administrators
3. Change any user's role (inc themselves) to Administrator
*/
class modifyUserDisplayForNonAdministrators {

    # Add filters
    function __construct() {
        add_filter( 'editable_roles', array(&$this, 'editable_roles') );
        add_filter( 'map_meta_cap', array(&$this, 'map_meta_cap'), 10, 4 );
    }

    # Remove 'Administrator' from the list of roles if the current user is not an Administrator
    function editable_roles( $roles ){
        if( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
            unset( $roles['administrator']);
        }
        return $roles;
    }

    # If someone is trying to edit or delete an Administrator and that user isn't an Administrator, don't allow it
    function map_meta_cap( $caps, $cap, $user_id, $args ){

        switch( $cap ){
            case 'edit_user':
            case 'remove_user':
            case 'promote_user':
                if( isset($args[0]) && $args[0] == $user_id )
                    break;
                elseif( !isset($args[0]) )
                    $caps[] = 'do_not_allow';
                $other = new WP_User( absint($args[0]) );
                if( $other->has_cap( 'administrator' ) ){
                    if(!current_user_can('administrator')){
                        $caps[] = 'do_not_allow';
                    }
                }
                break;
            case 'delete_user':
            case 'delete_users':
                if( !isset($args[0]) )
                    break;
                $other = new WP_User( absint($args[0]) );
                if( $other->has_cap( 'administrator' ) ){
                    if(!current_user_can('administrator')){
                        $caps[] = 'do_not_allow';
                    }
                }
                break;
            default:
                break;
        }
        return $caps;
    }

}
$check_user_permissions = new modifyUserDisplayForNonAdministrators();

