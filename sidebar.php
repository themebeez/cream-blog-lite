<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Cream_Blog_Lite
 */

if ( ! is_active_sidebar( 'sidebar' ) ) {
	return;
}

$sidebar_class = cream_blog_sidebar_class();
?>
<div class="<?php echo esc_attr( $sidebar_class ); ?> sidebar-layout-two">
	<aside class="secondary">
		<?php dynamic_sidebar( 'sidebar' ); ?>
	</aside><!-- #secondary --> 
</div><!-- .col.sticky_portion -->