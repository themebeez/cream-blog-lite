<?php
/**
 * Custom Category Post Widget Class For Sidebar
 * 
 * @package Cream_Blog_Lite
 */

class Cream_Blog_Lite_Category_Post_Widget extends WP_Widget {
 
    function __construct() { 

        parent::__construct( 
        'cream-blog-lite-category-post-widget',  // Base ID
            esc_html__( 'CB: Post Widget', 'cream-blog-lite' ),   // Name
            array(
                'classname' => 'cb-post-widget cbl-post-widget',
                'description' => esc_html__( 'Displays Posts.', 'cream-blog-lite' ), 
            )
        );
 
    }
 
    public function widget( $args, $instance ) {

        $title          = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        $post_category  = $instance['post_cat'];

		$posts_no       = $instance[ 'post_no' ];

		echo $args[ 'before_widget' ];

		$post_args = array(
			'post_type' => 'post'
		);

        if( $post_category != 'none' ) {
            $post_args['category_name'] = $post_category;
        }

        if( $posts_no > 0 ) {
            $post_args['posts_per_page'] = $posts_no;
        } else {
            $post_args['posts_per_page'] = 5;
        }

		$post_query = new WP_Query( $post_args );

		if( $post_query->have_posts() ) {

			echo $args[ 'before_title' ];
				echo esc_html( $title );
			echo $args[ 'after_title' ];

            $count = 1;
            ?>
            <div class="post-widget-container">
                <?php
                while( $post_query->have_posts() ) {
                    $post_query->the_post();
                    ?>
                    <div class="cb-post-box">
                        <div class="cb-col">
                            <?php
                            $thumbnail_url = '';
                            if( has_post_thumbnail() ) {
                                $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'cream-blog-lite-thumbnail-one' );
                            }
                            if( !empty( $thumbnail_url ) ) {
                                ?>
                                <div class="thumbnail-count-container">
                                    <div class="thumb">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php
                                            if( cream_blog_get_option( 'cream_blog_enable_lazyload' ) == true ) {
                                                ?>
                                                <img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo esc_url( $thumbnail_url ); ?>" data-srcset="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>">
                                                <noscript>
                                                    <img src="<?php echo esc_url( $thumbnail_url ); ?>" srcset="<?php echo esc_url( $thumbnail_url ); ?>" class="image-fallback" alt="<?php the_title_attribute(); ?>">
                                                </noscript>
                                                <?php
                                            } else {
                                                ?>
                                                <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>">
                                                <?php
                                            }
                                            ?>    
                                        </a>
                                    </div>
                                    <div class="post-count"><?php echo esc_html( $count ); ?></div>
                                </div>
                                <?php
                            }
                            ?>
                        </div><!-- .cb-col -->
                        <div class="cb-col">
                            <div class="post-contents">
                                <div class="post-title">
                                    <h4>
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                </div><!-- .post-title -->
                                <?php cream_blog_post_meta( false, true, false ); ?>
                            </div><!-- .post-contents -->
                        </div><!-- .cb-col -->
                    </div><!-- .cb-post-box -->
                    <?php
                    $count++;
                }
                wp_reset_postdata();
                ?>
            </div><!-- .post-widget-container -->
            <?php            
		}
			
		echo $args[ 'after_widget' ]; 
 
    }
 
    public function form( $instance ) {
        $defaults = array(
            'title'       => '',
            'post_cat'	=> 'none',
            'post_no'	  => 5,
        );

        $instance = wp_parse_args( (array) $instance, $defaults );

		?>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_name('title') ); ?>">
                <strong><?php esc_html_e('Title', 'cream-blog-lite'); ?></strong>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />   
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'post_cat' ) )?>"><strong><?php echo esc_html__( 'Select Category: ', 'cream-blog-lite' ); ?></strong></label>
            <?php
            $categories = get_terms( 
                array( 
                    'taxonomy' => 'category', 
                    'hide_empty' => 0, 
                )
            );
            ?>
            <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'post_cat' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'post_cat' ) ); ?>">
                <option value="<?php esc_attr_e( 'none', 'cream-blog-lite' ); ?>"<?php if( $instance['post_cat'] == 'none' ) { ?>selected="selected"<?php } ?>><?php esc_html_e( 'Select Category', 'cream-blog-lite' ); ?></option>
                <?php
                if( !empty( $categories ) ) {
                    foreach( $categories as $cat ) {
                        ?>
                        <option value="<?php echo esc_attr( $cat->slug ); ?>" <?php if( $instance['post_cat'] == $cat->slug ) { ?>selected="selected"<?php } ?>><?php echo esc_html( $cat->name ); ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </p>

		<p>
            <label for="<?php echo esc_attr( $this->get_field_name('post_no') ); ?>">
                <strong><?php esc_html_e('No of Posts', 'cream-blog-lite'); ?></strong>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('post_no') ); ?>" name="<?php echo esc_attr( $this->get_field_name('post_no') ); ?>" type="number" value="<?php echo esc_attr( $instance['post_no'] ); ?>" />   
        </p>
		<?php
    }
 
    public function update( $new_instance, $old_instance ) {
 
        $instance = $old_instance;

        $instance['title']  	= sanitize_text_field( $new_instance['title'] );

        $instance['post_cat']   = sanitize_text_field( $new_instance['post_cat'] );

        $instance['post_no']  	= absint( $new_instance['post_no'] );

        return $instance;
    }
}