<?php get_header(); ?>
<div class="container-bg">

    <div class="container center-content">

        <div class="row">
            <div class="col-xs-9 ml">
                <div class="main-left">
                    <h2><?php _e( 'Page not found', 'html5blank' ); ?></h2>
                        <a href="<?php echo home_url(); ?>"><?php _e( 'Return home?', 'html5blank' ); ?></a>
                        <br class="clear">
                </div>
            </div>

            <div class="col-xs-3 mr">
                <?php get_sidebar(); ?>
            </div>

        </div>

    </div>

</div> <!-- /container -->



<?php get_footer(); ?>

