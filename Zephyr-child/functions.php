<?php
/* Custom functions code goes here. */

add_action( 'usof_after_save', 'ccfm_clear_cache_for_me' );

add_action('admin_head', 'my_admin_css');
function my_admin_css() {
  echo '<style>.us-hb-screenlock{display:none;}#colorbox, #cboxOverlay, #cboxWrapper{z-index:100001;}</style>';
}


function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyBC5_o8JlxyLFjRJ0yC2vXZnl57AuoqRaU');
}
add_action('acf/init', 'my_acf_init');


function my_edit_toolbar($wp_toolbar) {
	$wp_toolbar->remove_node('wp-logo');
	$wp_toolbar->remove_node('customize');
	$wp_toolbar->remove_node('updates');
	$wp_toolbar->remove_node('comments');
	$wp_toolbar->remove_node('new-content');
}
add_action('admin_bar_menu', 'my_edit_toolbar', 999);



function user_menu_shortcode( $atts, $shortcode_name = null ) {
     $logout = esc_url( wp_logout_url('home') );
     $username = 'Will';

	$url = <<<EOT
<div class="w-dropdown source_own ush_dropdown_1"><div class="w-dropdown-h"><div class="w-dropdown-list" style="display: none; height: 0px;">
<a class="w-dropdown-item" href="/profile"><span class="w-dropdown-item-title">Profile</span></a>
<a class="w-dropdown-item" href="$logout"><span class="w-dropdown-item-title">Logout</span></a>
</div><div class="w-dropdown-current"><a class="w-dropdown-item" href="javascript:void(0)"><span class="w-dropdown-item-title">$username</span></a></div></div></div>

EOT;

	return $url;

}
add_shortcode( 'user_menu', 'user_menu_shortcode' );

function get_address () {
    global $wpdb;
    $query = "SELECT options FROM " . $wpdb->prefix . "structuring_markup" . " WHERE type = 'local_business' ORDER BY type ASC";
    $results = $wpdb->get_results( $query );
    $options = unserialize( $results[0]->options );
    return $options;
}
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

function loggedin () {
	if ( is_user_logged_in() ) {
		echo 'Welcome, registered user!';
	} else {
		echo 'Welcome, visitor!';
	}
}
add_shortcode('is_user_logged_in', 'loggedin');