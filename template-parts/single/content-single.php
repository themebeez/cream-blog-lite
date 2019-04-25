<?php
/**
 * Template part for displaying post detail of post format standard.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Cream_Blog_Lite
 */
?>
<section id="post-<?php the_ID(); ?>" <?php post_class( 'cb-page-entry post-page-entry' ); ?>>

    <div class="page-title">
        <h2><?php the_title(); ?></h2>
    </div><!-- .page-title -->

    <?php 
    cream_blog_post_meta( true, true, true ); 

    $show_featured_image = cream_blog_get_option( 'cream_blog_display_featured_image_post' ); 
    if( $show_featured_image == true ) {
        cream_blog_post_thumbnail(); 
    }
    ?>

    <div class="cb-editor-contents-entry">
        <?php
        the_content();

        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cream-blog-lite' ),
            'after'  => '</div>',
        ) );
        ?>
    </div><!-- .cb-editor-contents-entry -->

    <?php
    cream_blog_post_tags_meta();

    /**
    * Hook - cream_blog_lite_post_navigation.
    *
    * @hooked cream_blog_lite_post_navigation_action - 10
    */
    do_action( 'cream_blog_lite_post_navigation' );

    get_template_part( 'template-parts/single/content', 'author' );

    get_template_part( 'template-parts/single/content', 'related' );
    ?>
</section><!-- .cb-page-entry.post-page-entry -->