<!-- Footer -->
<div class=" footer-top-bg">
    <div class="container center-content">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="hp-fa">
                    <h3 style="color: #fff;">Twitter Updates</h3>



<a class="twitter-timeline" height="300" data-theme="dark" data-dnt="true" href="https://twitter.com/AFGRIDogFood" data-widget-id="440440636303355904">Tweets by @AFGRIDogFood</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


                   <!--  <h3 style="color: #fff;">Twitter Updates</h3>
                    <img src="<?php echo get_template_directory_uri() ?>/img/footer_images/Footer-Elements-Twitter-Place-Holder.png"> -->
                </div>

            </div>
            <div class="hp-fa col-xs-12 col-sm-4">
                <div class="hp-fa">

                    <?php if(dynamic_sidebar('footer_two')): ?>
                    <?php else: ?>
                        <p>please add a footer widget.</p>
                    <?php endif; ?>

                   <!--  <h3 style="color: #fff;">Facebook</h3>
                    <img src="<?php echo get_template_directory_uri() ?>/img/footer_images/Footer-Elements-Facebook-Place-Holder.png"> -->
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="hp-fa">
                    <h3 style="color: #fff;">Quick Links</h3>
                       <ul style="list-style: none;">
                           <li><a href="http://www.afgri.co.za/" target="_blank" style="color: #fff;">AFGRI Website</a></li>
                       </ul>
                    <h3 style="color: #fff;">Customer Care</h3>
                    <ul style="list-style: none;">
                        <li style="color:#fff;font-size:18px;">Tel: 011 977 7737</li>
                    </ul>
                    <h3 style="color: #fff;">Operating Hours</h3>
                    <ul style="list-style: none;">
                        <li style="color:#fff;font-size:18px;">Mon - Fri: 9AM to 5PM</li>
                    </ul>
                    <br>
                    <br>
                    <img src="<?php echo get_template_directory_uri() ?>/img/footer_images/Footer-Elements-ppbAFGRI-Logo.png" style="width:240px;">
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="footer-bg">
        <div class="container center-content">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <div class="footer-menu">
                        <ul>
                            <li><a class="active" href="/">Home</a></li>
                            <li><a href="<?php echo get_bloginfo('url') ?>/about-us">About us</a></li>
                            <li><a href="<?php echo get_bloginfo('url') ?>/products">Products</a></li>
                            <li><a href="<?php echo get_bloginfo('url') ?>/store-locator">Store locator</a></li>
                            <li><a href="<?php echo get_bloginfo('url') ?>/register">Registration</a></li>
                            <li><a href="<?php echo get_bloginfo('url') ?>/contact-us">Contact us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <span class="copyr pull-right">Copyright <?php echo date('Y') ?> &copy; AFGRI Dog Food</span>
                </div>
            </div>
        </div>
    </div>
</footer>


<script src="<?php echo get_template_directory_uri() ?>/js/vendor/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/js/main.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-48729436-1', 'afgridogfood.co.za');
  ga('send', 'pageview');

</script>
<?php wp_footer(); ?>
</body>
</html>