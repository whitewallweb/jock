<?php
/**
 * Created by JetBrains PhpStorm.
 * Template Name: Store Locator
 * Date: 2014/01/29
 * Time: 7:29 PM
 * To change this template use File | Settings | File Templates.
 */

?>
<?php get_header(); ?>
<div class="container-bg">

    <div class="container center-content">

        <div class="row">

            <div class="col-xs-12 col-sm-9 ml">
                <div class="main-left">
                    <h2>Store Locator</h2>
                    <div class="row">
                        <div class="col-xs-12">
                            <?php echo do_shortcode("[wpsl]"); ?>
                            <div class="divider"></div>
                            <h3>Want to sell our product in your shop or vetinary practice?</h3>
                            <p>
				If you are interested in selling one of our quality products please feel free to <a href="<?php echo get_bloginfo('url') ?>/contact-us/">send us an email</a>.
			    </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-3 mr">
                <?php get_sidebar(); ?>
            </div>

        </div>

    </div>

</div> <!-- /container -->
<?php get_footer(); ?>