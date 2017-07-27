<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Template of website header semantics
 */


global $current_user;
wp_get_current_user();
if($current_user->ID === 0){
    header('Location: /login'); exit;
}
$role = ($current_user->roles[0]);
if ($role === 'trainee'){
    $currentSubject = get_query_var('subject');
    $sub = get_field('trainee_subject', 'user_'.$current_user->ID);
    $userSubLink = get_permalink( $sub[0] );
    $userSubID = get_post_field( 'ID', $sub[0] );

    global $post;
    $parent = $post->post_parent;
    // True for main subject pages
    $currentID = $post->ID;
    if($parent === 0){
        $topID = $currentID;
    }
    // True for subpages of subjects
    else{
        $topID = $parent;
    }

    if ($currentSubject !== ''){
        if ($topID !== $userSubID){
            header('Location: '.$userSubLink); exit;
        }
    }
    elseif($currentID === 140){
        header('Location: '.$userSubLink); exit;
    }
    $subli = '<ul class="w-nav-list level_2">';
    $subli = $subli.get_sub(get_permalink($sub[0]),2);
    $subli = $subli.'</ul>';
}
else {
    $subli = '<ul class="w-nav-list level_2">';
    $loop = new WP_Query( array( 'post_type' => 'subject', 'post_parent' => 0, 'orderby' => 'menu_order title', 'order' => 'ASC', 'posts_per_page' => -1 ) );
    while ( $loop->have_posts() ) : $loop->the_post();
        $subli = $subli.'<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children w-nav-item level_2 togglable"><a class="w-nav-anchor level_2" href="'.get_permalink($post).'"><span class="w-nav-title">'.get_the_title($post).'</span><span class="w-nav-arrow"></span><span class="ripple-container"></span></a><ul class="w-nav-list level_3">'.get_sub(get_permalink($post)).'</ul></li>';
    endwhile; wp_reset_query();
    $subli = $subli.'</ul>';
}



function get_sub($link,$level = 3){
    $items = ['resources' => 'Resources','calendar' => 'Calendar','audit' => 'Subject Knowledge Audit','reading-list' => 'Reading List','useful-links' => 'Useful Links','lesson-plan-proforma' => 'Lesson Plan Proforma'];
    $data = '';
    foreach ($items as $url => $item){
        $data = $data.'<li class="menu-item menu-item-type-post_type menu-item-object-page w-nav-item level_'.$level.'"><a class="w-nav-anchor level_'.$level.'" href="'.$link.$url.'"><span class="w-nav-title">'.$item.'</span><span class="w-nav-arrow"></span><span class="ripple-container"></span></a></li>';
    }
    return $data;
}


class arc_walker extends Walker_Nav_Menu {

    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        // depth dependent classes
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
        $level = ( $depth + 2 ); // because it counts the first submenu as 0
        // build html
        $output .= "\n" . $indent . '<ul class="w-nav-list level_' . $level . '">' . "\n";
    }

    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
        $output .= $indent . "</ul>\n";
    }

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $level = ( $depth + 1 ); // because it counts the first submenu as 0

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'w-nav-item';
        $classes[] = 'level_' . $level;
        $classes[] = 'menu-item-' . $item->ID;

        // Removing active classes for scroll links, so they could be handled by JavaScript instead
        if ( isset( $item->url ) AND strpos( $item->url, '#' ) !== FALSE ) {
            $classes = array_diff(
              $classes, array(
                'current-menu-item',
                'current-menu-ancestor',
                'current-menu-parent',
              )
            );
        }

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

        $item_output = $args->before;
        $item_output .= '<a class="w-nav-anchor level_' . $level . '" ' . $attributes . '>';
        $item_output .= $args->link_before . '<span class="w-nav-title">' . apply_filters( 'the_title', $item->title, $item->ID ) . '</span><span class="w-nav-arrow"></span>' . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
        $output .= "$indent</li>\n";
    }
}

$us_layout = US_Layout::instance();

if ( $us_layout->header_show == 'never' ) {
	return;
}

global $us_header_settings;
us_load_header_settings_once();

$options = us_arr_path( $us_header_settings, 'default.options', array() );
$layout = us_arr_path( $us_header_settings, 'default.layout', array() );
$data = us_arr_path( $us_header_settings, 'data', array() );

echo '<header class="l-header ' . $us_layout->header_classes();
if ( isset( $options['bg_img'] ) AND ! empty( $options['bg_img'] ) ) {
	echo ' with_bgimg';
}
echo '" itemscope="itemscope" itemtype="https://schema.org/WPHeader">';
foreach ( array( 'top', 'middle', 'bottom' ) as $valign ) {
	$show_state_count = 0;
	foreach ( array( 'default', 'tablets', 'mobiles' ) as $state ) {
		if ( ! isset( $us_header_settings[$state]['options'][$valign.'_show'] ) OR $us_header_settings[$state]['options'][$valign.'_show'] == 1 ) {
			$show_state_count++;
		}
	}
	if ( $show_state_count == 0 ) {
		continue;
	}
	echo '<div class="l-subheader at_' . $valign;
	if ( isset( $options[$valign . '_fullwidth'] ) AND $options[$valign . '_fullwidth'] ) {
		echo ' width_full';
	}
	echo '"><div class="l-subheader-h">';
	foreach ( array( 'left', 'center', 'right' ) as $halign ) {
		echo '<div class="l-subheader-cell at_' . $halign . '">';
		if($valign === 'middle' && $halign === 'right'){
            echo '<nav class="w-nav type_desktop animation_height height_full ush_menu_1" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">';
            echo '<a class="w-nav-control" href="javascript:void(0);"><span>' . us_translate( 'Menu' ) . '</span></a>';
            echo '<ul class="w-nav-list level_1 hover_simple hide_for_mobiles">';
            $subjectURL = ($role === 'trainee' ? '/subject/'.$userSub : '/subjects');
            $subjectText = ($role === 'trainee' ? 'My Subject' : 'Subjects');
            echo '<li class="subject-item menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children w-nav-item level_1 togglable"><a class="w-nav-anchor level_1" href="'.$subjectURL.'"><span class="w-nav-title">'.$subjectText.'</span><span class="w-nav-arrow"></span><span class="ripple-container"></span></a>'.$subli.'</li>';
            wp_nav_menu(
              array(
                'theme_location' => 'us_main_menu',
                'menu_class' => 'w-nav-list level_1 hover_simple hide_for_mobiles',
                'item_spacing' => 'discard',
                'container' => 'none',
                'fallback_cb' => FALSE,
                'items_wrap' => '%3$s',
                'walker' => new arc_walker()
              )
            );
            echo '</ul>';
            echo '<div class="w-nav-options hidden" onclick="return {&quot;mobileWidth&quot;:900,&quot;mobileBehavior&quot;:1}"></div>';
            echo '</nav>';
        }
        if ( isset( $layout[$valign . '_' . $halign] ) ) {
			us_output_header_elms( $layout, $data, $valign . '_' . $halign );
		}
		echo '</div>';
	}
	echo '</div></div>';
}

// Outputting elements that are hidden in default state but are visible in tablets / mobiles state
$default_elms = us_get_header_shown_elements_list( us_get_header_layout() );
$tablets_elms = us_get_header_shown_elements_list( us_get_header_layout( 'tablets' ) );
$mobiles_elms = us_get_header_shown_elements_list( us_get_header_layout( 'mobiles' ) );
$layout['temporarily_hidden'] = array_diff( array_unique( array_merge( $tablets_elms, $mobiles_elms ) ), $default_elms );
echo '<div class="l-subheader for_hidden hidden">';
us_output_header_elms( $layout, $data, 'temporarily_hidden' );
echo '</div>';

echo '</header>';
