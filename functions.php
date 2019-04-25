<?php
/**
 * Child theme functions
 *
 * Functions file for child theme, enqueues parent and child stylesheets by default.
 *
 * @since	1.0.0
 * @package Cream_Blog_Lite
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! function_exists( 'cream_blog_lite_setup' ) ) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function cream_blog_lite_setup() {

        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Royale News, use a find and replace
         * to change 'cream-blog-lite' to the name of your theme in all the template files.
         */
        load_child_theme_textdomain( 'cream-blog-lite', get_stylesheet_directory() . '/languages' );

        /*
         * Images sizes for child theme
         */
        add_image_size( 'cream-blog-lite-thumbnail-one', 400, 400, true ); // Sidebar Post Widget
    }
}
add_action( 'after_setup_theme', 'cream_blog_lite_setup' );

if ( ! function_exists( 'cream_blog_lite_enqueue_styles' ) ) {
	/**
	 * Enqueue Styles.
	 *
	 * Enqueue parent style and child styles where parent are the dependency
	 * for child styles so that parent styles always get enqueued first.
	 *
	 * @since 1.0.0
	 */
	function cream_blog_lite_enqueue_styles() {

		// Enqueue Parent theme's stylesheet.
		wp_enqueue_style( 'cream-blog-lite-parent-style', get_template_directory_uri() . '/style.css' );
		// Enqueue Parent theme's main stylesheet
		wp_enqueue_style( 'cream-blog-lite-parent-main', get_template_directory_uri() . '/assets/dist/css/main.css' );

		// Enqueue Child theme's stylesheet.
		// Setting 'parent-style' as a dependency will ensure that the child theme stylesheet loads after it.
		wp_enqueue_style( 'cream-blog-lite-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'cream-blog-lite-parent-style' ) );

		wp_enqueue_style( 'cream-blog-lite-child-fonts', cream_blog_lite_fonts_url() );

		wp_enqueue_style( 'cream-blog-lite-child-main', get_stylesheet_directory_uri() . '/assets/dist/css/main.css' );

        wp_enqueue_script( 'cream-blog-lite-child-bundle', get_stylesheet_directory_uri() . '/assets/dist/js/bundle.min.js', array( 'jquery' ), true );
	}
}
// Add enqueue function to the desired action.
add_action( 'wp_enqueue_scripts', 'cream_blog_lite_enqueue_styles', 20 );


/**
 * Funtion To Get Google Fonts
 */
if ( !function_exists( 'cream_blog_lite_fonts_url' ) ) {
    /**
     * Return Font's URL.
     *
     * @since 1.0.0
     * @return string Fonts URL.
     */
    function cream_blog_lite_fonts_url() {

        $fonts_url = '';
        $fonts = array();
        $subsets = 'latin,latin-ext';

        /* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Poppins font: on or off', 'cream-blog-lite')) {
            $fonts[] = 'Poppins:400,400i,500,600,700,700i,900,900i';
        }

        /* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Oswald font: on or off', 'cream-blog-lite')) {
            $fonts[] = 'Oswald:400,500,600,700';
        }

        /* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Pacifico font: on or off', 'cream-blog-lite')) {
            $fonts[] = 'Pacifico:400';
        }

        if ($fonts) {
            $fonts_url = add_query_arg(array(
                'family' => urldecode(implode('|', $fonts)),
                'subset' => urldecode($subsets),
            ), 'https://fonts.googleapis.com/css');
        }
        return $fonts_url;
    }
}

// Removal of hook action of banner/slider added in parent theme
if( ! function_exists( 'cream_blog_lite_banner_slider_action' ) ) {

    function cream_blog_lite_banner_slider_action() {

        $enable_child_slider = get_theme_mod( 'cream_blog_lite_enable_child_banner', true );
        $show_banner = cream_blog_get_option( 'cream_blog_enable_banner' );

        if( $show_banner == true ) {

            if( $enable_child_slider == true ) {

                $banner_query = cream_blog_banner_query();

                if( $banner_query->have_posts() ) {
                    ?> 
                    <div class="cb-banner">
                        <div class="banner-inner">
                            <div class="cb-container">
                                <div class="owl-carousel" id="cb-banner-style-9">
                                    <?php
                                    while( $banner_query->have_posts() ) {
                                        $banner_query->the_post();
                                        $thumbnail_url = '';
                                        if( has_post_thumbnail() ) {
                                            $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'cream-blog-thumbnail-one' );
                                            if( !empty( $thumbnail_url ) ) {
                                                ?>
                                                <div class="item">
                                                    <?php
                                                    if( cream_blog_get_option( 'cream_blog_enable_lazyload' ) == true ) {
                                                        ?>
                                                        <div class="thumb lazyload" data-bg="<?php echo esc_url( $thumbnail_url ); ?>" style="background-image: url(<?php echo esc_url( $thumbnail_url ); ?>);">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="thumb" style="background-image: url(<?php echo esc_url( $thumbnail_url ); ?>);">
                                                        <?php
                                                    }
                                                    ?>
                                                        <div class="mask"></div>
                                                        <div class="post-contents">
                                                            <?php cream_blog_post_categories_meta(); ?>
                                                            <div class="post-title">
                                                                <h3>
                                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                                </h3>
                                                            </div><!-- .post-title -->
                                                            <?php cream_blog_post_meta( true, false, false ); ?>
                                                        </div><!-- .post-contents -->
                                                    </div><!-- .thumb.lazyloading -->                                            
                                                </div><!-- .item -->
                                                <?php
                                            }
                                        }
                                    }
                                    wp_reset_postdata();
                                    ?>
                                </div><!-- #cb-banner-style-2.owl-carousel -->
                            </div><!-- .cb-container -->
                        </div><!-- .banner-inner -->
                    </div><!-- .cb=banner -->
                    <?php
                }
            } else {

                /**
                * Hook - cream_blog_banner_slider.
                *
                * @hooked cream_blog_banner_slider_action - 10
                */
                do_action( 'cream_blog_banner_slider' );
            }
        }
    }
}
// Addition of action for banner/slider in child theme
add_action( 'cream_blog_lite_banner_slider', 'cream_blog_lite_banner_slider_action' );


if( ! function_exists( 'cream_blog_lite_post_navigation_action' ) ) {

    function cream_blog_lite_post_navigation_action() {

        $previous_post = get_previous_post(); 
        $next_post = get_next_post();
        ?>
        <nav class="navigation post-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'cream-blog-lite' ); ?></h2>
            <div class="nav-links">
                <div class="previous-nav">
                    <?php
                    if( !empty( $previous_post ) ) {
                        ?>
                        <div class="prev-icon"><i class="feather icon-arrow-left"></i><?php esc_html_e( 'Prev Post', 'cream-blog-lite' ); ?></div>
                        <a href="<?php echo esc_url( get_permalink( $previous_post->ID ) ); ?>" rel="prev"><?php echo esc_html( $previous_post->post_title ); ?></a>
                        <?php
                    }
                    ?>
                </div>                  
                <div class="next-nav">
                    <?php
                    if( !empty( $next_post ) ) {
                        ?>
                        <div class="next-icon"><?php esc_html_e( 'Next Post', 'cream-blog-lite' ); ?><i class="feather icon-arrow-right"></i></div>
                        <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" rel="next"><?php echo esc_html( $next_post->post_title ); ?></a>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </nav>
        <?php
    }
}
// Addition of action for post navigation in child theme
add_action( 'cream_blog_lite_post_navigation', 'cream_blog_lite_post_navigation_action' );


/**
 * Register custom widget.
 * 
 * @link https://developer.wordpress.org/themes/functionality/widgets/#registering-a-widget
 */
if( ! function_exists( 'cream_blog_lite_widgets_init' ) ) {

    function cream_blog_lite_widgets_init() {

        register_widget( 'Cream_Blog_Lite_Category_Post_Widget' );
    }
}
add_action( 'widgets_init', 'cream_blog_lite_widgets_init' );


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
if( ! function_exists( 'cream_blog_lte_customizer_register' ) ) {

    function cream_blog_lte_customizer_register( $wp_customize ) {

        // Enable Child Banner
        $wp_customize->add_setting( 
            'cream_blog_lite_enable_child_banner', 
            array(
                'sanitize_callback' => 'wp_validate_boolean',
                'default'           => true,
            ) 
        );

        // Enable Child Banner
        $wp_customize->add_control( 
            'cream_blog_lite_enable_child_banner', 
            array(
                'label'             => esc_html__( 'Enable Child Banner/Slider', 'cream-blog-lite' ),
                'description'       => esc_html__( 'On enabling this option, banner/slider of parent theme will not be shown.', 'cream-blog-lite' ),
                'section'           => 'cream_blog_banner_options',
                'type'              => 'checkbox', 
                'active_callback'   => 'cream_blog_lite_is_slider_active',
            ) 
        );
    }
}
add_action( 'customize_register', 'cream_blog_lte_customizer_register', 20 );


/**
 * Active callback function for child banner/slider
 */
if( ! function_exists( 'cream_blog_lite_is_slider_active' ) ) {

    function cream_blog_lite_is_slider_active( $control ) {

        if( $control->manager->get_setting( 'cream_blog_enable_banner' )->value() == true ) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Dynamic css
 */
if( ! function_exists( 'cream_blog_lite_dynamic_css' ) ) {

    function cream_blog_lite_dynamic_css() {

        $primary_color = cream_blog_get_option( 'cream_blog_theme_color' );
        ?>
        <style>
            <?php
            if( !empty( $primary_color ) ) {
                ?>


                .metas-list li span, 
                .metas-list li a, 
                .cb-post-widget .metas .metas-list li span, 
                .cb-post-widget .metas .metas-list li a {

                    color:<?php echo esc_attr( $primary_color ); ?>;
                }

                .header-style-5 .cb-navigation-main-outer, .header-style-3 .cb-navigation-main-outer, .is-sticky #cb-stickhead, ul.post-categories li a, .widget .widget-title h3, #toTop, .calendar_wrap caption, #header-search input[type="submit"], .search-box input[type="submit"], .widget_product_search input[type="submit"], .widget_search input[type="submit"], .cb-pagination .pagi-style-1 .nav-links span.current, .cb-pagination .pagi-style-2 .nav-links span.current, #comments form input[type="submit"], .metas-list li.posted-date::before, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce .wc-forward, .woocommerce a.added_to_cart, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce nav.woocommerce-pagination ul li span.current, .widget_product_search button, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .post-tags a, .jetpack_subscription_widget input[type="submit"]:hover, .owl-carousel .owl-nav button.owl-prev, .owl-carousel .owl-nav button.owl-next, .cb-author-widget .author-bio a:after,
                .sidebar-layout-two .widget .widget-title {

                    background-color: <?php echo esc_attr( $primary_color ); ?>;
                }

                footer .widget .widget-title h3,
                .section-title {
                    border-left-color: <?php echo esc_attr( $primary_color ); ?>;
                }
                <?php
            }
            ?>
        </style>
        <?php
    }
}
add_action( 'wp_head', 'cream_blog_lite_dynamic_css' );


/**
 * Load Custom Post Widget
 */
require get_stylesheet_directory() . '/widgets/cream-blog-lite-category-post-widget.php';