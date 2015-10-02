<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' />
    <title></title>
    <meta name="description" content="">
   <!-- <meta name="viewport" content="width=device-width">-->
    <?php wp_head(); ?>
    <script>(function() {
            var _fbq = window._fbq || (window._fbq = []);
            if (!_fbq.loaded) {
                var fbds = document.createElement('script');
                fbds.async = true;
                fbds.src = '//connect.facebook.net/en_US/fbds.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(fbds, s);
                _fbq.loaded = true;
            }
            _fbq.push(['addPixelId', '929111463784329']);
        })();
        window._fbq = window._fbq || [];
        window._fbq.push(['track', 'PixelInitialized', {}]);
    </script>
    <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=929111463784329&amp;ev=PixelInitialized" /></noscript>
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->

<div class="header-bg">
	<?php if (!is_front_page()) { ?>
		<img src="<?php echo get_template_directory_uri()?>/img/header_images/headerstrip.jpg" style="width:100%">
	<?php } ?>
    <div class="container top-container">
		<?php if (is_front_page()) { ?>
			<img src="<?php echo get_template_directory_uri()?>/img/header_images/Home-Sliders-2-new.jpg" style="width:100%">
         <?php }?>
    </div>

    <div class="main-menu-outside">
        <div class="main-menu-holder">
            <div class="testy">
				<div id="logo"><a href="/"><img src="<?php echo get_template_directory_uri()?>/img/jock_logo.png"></a></div>
                <div class="row menu-row">
                    <div class="col-xs-12">
						<a href="/cart/"><img src="<?php echo get_template_directory_uri()?>/img/trolley.png" class="trolley"></a>
                        <div class="main-menu">
                            <?php $args = array (
                                'menu' => 'main'
                            );
                            wp_nav_menu( $args ); ?>
                            <!--<ul>
                                <li><a class="active" href="#home">Home</a></li>
                                <li><a href="about-us.php">About us</a></li>
                                <li><a href="products.php">Products</a></li>
                                <li><a href="store-locator.php">Store locator</a></li>
                                <li><a href="register.php">Registration</a></li>
                                <li><a href="contact-us.php">Contact us</a></li>
                            </ul> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>