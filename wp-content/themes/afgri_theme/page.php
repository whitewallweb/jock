<?php get_header(); ?>
<div class="container-bg">

    <div class="container center-content">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 ml">
                <div class="main-left">
                    <?php if ( function_exists( 'breadcrumb_trail' ) ) breadcrumb_trail(); ?>
                    <h2><?php the_title(); ?></h2>
                    <?php if (have_posts()): while (have_posts()) : the_post(); ?>
                    <!-- article -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php the_content(); ?>
                        <br class="clear">
                        <?php edit_post_link(); ?>
                    </article>
                    <!-- /article -->
                    <?php endwhile ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 mr">
                <?php get_sidebar(); ?>
            </div>

        </div>

    </div>

</div> <!-- /container -->



<?php get_footer(); ?>