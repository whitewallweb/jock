<?php get_header(); ?>
<div class="container-bg">
    <div class="container center-content">
        <div class="row">
            <div class="col-xs-9 ml">
                <div class="main-left">
                    <h2>Archive</h2>

                    <?php get_template_part('loop'); ?>

                    <?php get_template_part('pagination'); ?>
                </div>
            </div>
            <div class="col-xs-3 mr">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</div> <!-- /container -->
<?php get_footer(); ?>
