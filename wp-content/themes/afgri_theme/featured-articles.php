<div class="container center-content">
    <div class="row">
        <div class="col-xs-12 col-md-4 col-sm-4">
            <div class="hp-fa">
                <?php $args = array(
                    'posts_per_page' => 1,
                    'post__in'  => get_option( 'sticky_posts' ),
                    'ignore_sticky_posts' => 1
                );
                $the_query = new WP_Query( $args ); ?>
                <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
                <h3 class="pull-left">Featured Articles</h3>
                            
                            <span class=" ltgt pull-right">
                                <!--<a href="#">&lt;</a>
                                &nbsp;
                                <a href="#">&gt;</a>
                                -->
                            </span>

                <div class="clearfix"></div>

                <h4><?php the_title(); ?></h4>

                    <?php the_excerpt(); ?>

                <?php endwhile; ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="hp-rn">
                <h3>Recent News</h3>

                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar Widgets') ) : ?>
                <?php endif; ?>

            </div>
        </div>
        <div class="col-xs-12 col-sm-4  col-md-4 hide-mobile">
            <div class="hp-ps">

                <img src="<?php echo get_template_directory_uri() ?>/img/header_images/Adspace - Jock_Promo-Teaser.jpg">

            </div>
        </div>
    </div>
</div>