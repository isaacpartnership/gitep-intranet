<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * The template for displaying all single posts and attachments
 */

$us_layout = US_Layout::instance();
get_header();

$post_type = get_post_type();
if ( $post_type == 'post' ) {
    $template_vars = array(
      'title' => us_get_option( 'titlebar_post_title', 'Blog' ),
    );
    us_load_template( 'templates/titlebar', $template_vars );
} elseif ( in_array( $post_type, us_get_option( 'custom_post_types_support', array() ) ) ) {
    us_load_template( 'templates/titlebar' );
}

$template_vars = array(
  'metas' => (array) us_get_option( 'post_meta', array() ),
  'show_tags' => in_array( 'tags', us_get_option( 'post_meta', array() ) ),
);

$default_post_sidebar_id = us_get_option( 'post_sidebar_id', 'default_sidebar' );

?>
<div class="l-main">
    <div class="l-main-h i-cf">

        <main class="l-content" itemprop="mainContentOfPage">

            <?php do_action( 'us_before_single' ) ?>

            <?php
            while ( have_posts() ) : ?>
            <?php the_post(); ?>


            <?php

            // Note: it should be filtered by 'the_content' before processing to output
            $the_content = get_the_content();



            if ( ! post_password_required() ) {
                $the_content = apply_filters( 'the_content', $the_content );
            }



            // If content has no sections, we'll create them manually
            $has_own_sections = ( strpos( $the_content, ' class="l-section' ) !== FALSE );
            if ( ! $has_own_sections ) {
                $the_content = '<section class="l-section"><div class="l-section-h i-cf" itemprop="text">' . $the_content . '</div></section>';
            }



            ?>


            <?php echo $the_content ?>

            <?php
            # If the currently logged in user is not a trainee & this is the top level subject page (ie parent is zero), display a list of the trainees for this subject
            global $current_user;
            $role = ($current_user->roles[0]);
            echo ($role !== 'trainee' && $post->post_parent === 0) ? generate_trainee_list() : "";
            ?>

            <?php endwhile; ?>

            <?php do_action( 'us_after_single' ) ?>

        </main>

        <?php if ( $us_layout->sidebar_pos == 'left' OR $us_layout->sidebar_pos == 'right' ): ?>
            <aside class="l-sidebar at_<?php echo $us_layout->sidebar_pos . ' ' . us_dynamic_sidebar_id( $default_post_sidebar_id ); ?>" itemscope="itemscope" itemtype="https://schema.org/WPSideBar">
                <?php us_dynamic_sidebar( $default_post_sidebar_id ); ?>
            </aside>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
