<?php


vc_add_shortcode_param( 'fws_image', 'fws_image_settings_field' );
function fws_image_settings_field( $param, $value ) {
    $param_line = '';
    $param_line .= '<input type="hidden" class="wpb_vc_param_value gallery_widget_attached_images_ids '.esc_attr($param['param_name']).' '.esc_attr($param['type']).'" name="'.esc_attr($param['param_name']).'" value="'.esc_attr($value).'"/>';
    //$param_line .= '<a class="button gallery_widget_add_images" href="#" use-single="true" title="'.__('Add image', "js_composer").'">'.__('Add image', "js_composer").'</a>';
    $param_line .= '<div class="gallery_widget_attached_images">';
    $param_line .= '<ul class="gallery_widget_attached_images_list">';

    if(strpos($value, "http://") !== false || strpos($value, "https://") !== false) {
        //$param_value = fjarrett_get_attachment_id_by_url($param_value);
        $param_line .= '<li class="added">
					<img src="'. esc_attr($value) .'" />
					<a href="#" class="vc_icon-remove"><i class="vc-composer-icon vc-c-icon-close"></i></a>
				</li>';
    } else {
        $param_line .= ($value != '') ? fieldAttachedImages(explode(",", esc_attr($value))) : '';
    }


    $param_line .= '</ul>';
    $param_line .= '</div>';
    $param_line .= '<div class="gallery_widget_site_images">';
    // $param_line .= siteAttachedImages(explode(",", $param_value));
    $param_line .= '</div>';
    $param_line .= '<a class="add_docs" href="#" use-single="true" title="'.__('Add image', "js_composer").'">'.__('Add file', "js_composer").'</a>';//class: button
    //$param_line .= '<div class="wpb_clear"></div>';
    return $param_line;
}
function my_admin_footer() {
    wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/js/vc.js', array ( 'jquery' ), 1.1, true);
}

add_action('admin_footer', 'my_admin_footer');

class vcInfoBox extends WPBakeryShortCode {

    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_infobox_mapping' ) );
        add_shortcode( 'gitep_doc', array( $this, 'vc_infobox_html' ) );
    }

    // Element Mapping
    public function vc_infobox_mapping() {

        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }

        // Map the block with vc_map()
        vc_map(
          array(
            'base'     => 'gitep_doc',
            "icon"     => "gitep_doc",
            'js_view' => 'VcCustomElementView',
            //'custom_markup' => '<div class="vc_custom-element-container">File: Text: %content%</div>',
            'name'     => __( 'Document'),
            'category' => 'Content',
            'weight'   => 230,
            'value'    => '',
            'params'   => array(
              [

                'type' => 'fws_image',
                'heading' => __( 'Document', 'js_composer' ),
                'param_name' => 'file',
                'description' => __( 'Select file from media library.', 'js_composer' ),
              ],
              [

                'type' => 'textfield',
                'heading' => __( 'Link text', 'js_composer' ),
                'param_name' => 'text',
                'name' => 'text',
                'description' => __( 'Describe the file', 'js_composer' ),
              ],
              [

                'type' => 'textfield',
                'heading' => __( 'Search keywords', 'js_composer' ),
                'param_name' => 'phrase',
                'description' => __( '', 'js_composer' ),
              ]

            )
          )
        );

    }


    // Element HTML
    public function vc_infobox_html( $atts, $file = false, $text = false ) {

        // Params extraction
        extract(
          shortcode_atts(
            array(
              'file'   => '',
              'text' => '',
            ),
            $atts
          )
        );
        $icon = wp_get_attachment_image_src($file, 'medium');

        // Fill $html var with data
        $html = '<div class="vc-doc-wrap">'. wp_get_attachment_link($file, 'medium', false, $icon[0], '<span style="background-image:url('.$icon[0].');"></span>'.$text) .'</div>';

        return $html;

    }

} // End Element Class

// Element Class Init
new vcInfoBox();

/*

 */



/* Comment me pls */
function acme_login_redirect( $redirect_to, $request, $user  ) {
    return ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) ? site_url() : site_url();
}
add_filter( 'login_redirect', 'acme_login_redirect', 10, 3 );



function generate_subject_title($id) {
    $subject = get_the_title($id);
    $subject_url = get_permalink($id);
    $data = <<<EOT
<h3 class="widgettitle subjectheader"><a href="$subject_url">$subject</a></h3>
EOT;

    return $data;
}
function generate_subject_subpages($id,$current = 0) {
    $data = '';

    $the_query = new WP_Query( array( 'post_type' => 'subject', 'post_parent' => $id, 'orderby' => 'menu_order title', 'order' => 'ASC', 'posts_per_page' => -1 ) );

    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        $currentClass = (get_the_ID() === $current ? 'current-menu-item' : '');
        $data = $data.'<li class="menu-item '.$currentClass.'"><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
    }
    wp_reset_postdata();


    return $data;
}

/* Return a list of trainees that are linked to the current subject being viewed */
function generate_trainee_list() {
    $data = '';

    global $post;

    $wp_user_query = new WP_User_Query( array( 'meta_key' => 'trainee_subject', 'meta_value' => $post->ID, 'meta_compare' => 'LIKE', 'orderby' => 'display_name', 'order' => 'ASC' ) );
    $trainees = $wp_user_query->results;

    $data .= "<h4>List of ".$post->post_title." trainees</h4>";

    if ( !empty( $trainees ) )
    {
        foreach ( $trainees as $trainee )
        {
            $data .= '<div class="trainee-name">'.$trainee->display_name.'</div><div class="trainee-email"><i class="fa fa-envelope-o"></i> <a href="mailto:'.$trainee->user_email.'">'.$trainee->user_email.'</a></div></>';
        }
    }
    else
    {
        $data .= '<div>There are no current trainees.</div>';
    }


    return $data;
}

function subject_menu_shortcode( $atts, $shortcode_name = null ) {
    global $current_user;
    wp_get_current_user();
    $role = ( $current_user->roles[0] );

    global $post;
    $parent = $post->post_parent;
    // True for main subject pages
    $currentID = $post->ID;
    if($parent === 0){
        $h3 = generate_subject_title($currentID );
        $li = generate_subject_subpages($currentID );
        $topID = $currentID;
    }
    // True for subpages of subjects
    else{
        $h3 = generate_subject_title($parent,$currentID );
        $li = generate_subject_subpages($parent,$currentID );
        $topID = $parent;
    }


    if ( $role === 'trainee' ) {

        $data = <<<EOT
$h3
<div class="subjects-submenu submenu-widget submenu-widget-course-information">
    <ul class="menu">
        $li
    </ul>
</div>
EOT;
    }
    else{
        $li = '';
        $loop = new WP_Query( array( 'post_type' => 'subject', 'post_parent' => 0, 'orderby' => 'menu_order title', 'order' => 'ASC', 'posts_per_page' => -1 ) );
        while ( $loop->have_posts() ) : $loop->the_post();
            $currentClass = (get_the_ID() === $topID ? 'current-menu-item' : '');
            $li = $li.'<li class="menu-item '.$currentClass.'"><a href="'.get_permalink($post).'">'.get_the_title($post).'</a><ul class="sub-menu">';
            $li = $li.generate_subject_subpages(get_the_ID($post),$currentID);
            $li = $li.'</ul></li>';
        endwhile; wp_reset_query();
        $data = <<<EOT
<h3 class="widgettitle"><a href="/subjects/">Subjects</a></h3>
<div class="subjects-submenu submenu-widget submenu-widget-course-information">
    <ul class="menu">
        $li
    </ul>
</div>
EOT;
    }

    return $data;

}
add_shortcode( 'subject_menu', 'subject_menu_shortcode' );


?>
