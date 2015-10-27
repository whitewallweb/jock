
<?php if (have_posts()): while (have_posts()) : the_post(); ?>
<div class="blog-box">
        <div class="desc">
        <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
        	<?php the_post_thumbnail('small'); // Declare pixel size you need inside the array ?>
        <?php endif; ?>
            <h3>
                <?php the_title(); ?>
            </h3>
                <?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>
        </div>
    <!-- /article -->
</div>

    <div class="clearfix"></div>
    <div class="divider"></div>
<?php endwhile; ?>

<?php else: ?>

	<!-- article -->
	<article>
		<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
	</article>
	<!-- /article -->

<?php endif; ?>
