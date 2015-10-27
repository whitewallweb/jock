<!-- sidebar -->
<div class="right-side">
    <div class="rs-link">
        <a href="<?php echo get_bloginfo('url') ?>/products"><img src="<?php echo get_template_directory_uri(); ?>/img/rs-our-products.png"></a>
        <a href="<?php echo get_bloginfo('url') ?>/register"><img src="<?php echo get_template_directory_uri(); ?>/img/rs-newsletter.png"></a>
        <a href="<?php echo get_bloginfo('url') ?>/register"><img src="<?php echo get_template_directory_uri(); ?>/img/rs-promotions.png"></a>
        <a href="<?php echo get_bloginfo('url') ?>/register"><img src="<?php echo get_template_directory_uri(); ?>/img/rs-competitions.png"></a>
        <a href="<?php echo get_bloginfo('url') ?>/events"><img src="<?php echo get_template_directory_uri(); ?>/img/rs-events.png"></a>
    </div>
    <div class="rs-petfolio">
        <a href="<?php echo get_bloginfo('url') ?>/register"></a>
    </div>
    <?php
    $urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', $urlArray);
    $numSegments = count($segments);
    $currentSegment = $segments[$numSegments - 2];
    if ($currentSegment !== 'contact-us'){
    ?>
    <div id="ad" class="pull-right">
        <img src="<?php echo get_template_directory_uri() ?>/img/header_images/Adspace - Jock_Promo-Teaser.jpg">
    </div>
    <?php } ?>
</div>
<!-- /sidebar -->
