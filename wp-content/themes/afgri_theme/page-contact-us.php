<?php
/*
Template Name: Contact Form
*/

//response generation function

$response = "";

//function to generate response
function my_contact_form_generate_response($type, $message)
{

    global $response;
    if ($type == "success") $response = "<div class='success'>{$message}</div>";
    else $response = "<div class='error'>{$message}</div>";

}

function set_html_content_type_here() {

    return 'text/html';
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                             //response messages
$not_human = "Human verification incorrect.";
$missing_content = "Please supply all information.";
$email_invalid = "Email Address Invalid.";
$message_unsent = "Message was not sent. Try Again.";
$message_sent = "Thanks! Your message has been sent.";

//user posted variables
$name = trim($_POST['message']['name']);
$surname = trim($_POST['message']['surname']);
$mobile = trim($_POST['message']['mobile']);
$region = trim($_POST['message']['region']);
$subject = trim($_POST['message']['subject']);
$email = trim($_POST['message']['email']);
$message = trim($_POST['message']['body']);
//$human = trim($_POST['message_human']);
$human .= 2;
$message = '';
foreach ($_POST['message'] as $key => $value) {
    $message .= $key.': '.$value. "\n";
}


//php mailer variables
//$to = get_option('admin_email');
$to['Compliments']  = 'Yolanda.Hoyer@afgri.co.za';
$to['Complaints']   = 'Yolanda.Hoyer@afgri.co.za';
$to['Enquiries']    = 'Yolanda.Hoyer@afgri.co.za';



$the_subject = $subject . " from: " . $name . ' '. $surname;
$headers = 'From: ' . $email . "\r\n" .
    'Reply-To: ' . $email . "\r\n";



$message_to_user = <<<EOF
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Untitled Document</title>
    <style type="text/css">
        body {
            background-image: url(http://afgri.t.whitewallweb.net/wp-content/themes/afgri_theme/email/images/images/bg.png);
            background-repeat: repeat;
            text-align: center;
        }

        body, td, th {
            font-family: Baskerville, "Palatino Linotype", Palatino, "Century Schoolbook L", "Times New Roman", serif;
            font-size: 14px;
            color: #37322F;
            text-align: left;
        }

        h1 {
            font-size: 36px;
            color: #737044;
        }

        h2 {
            font-size: 24px;
            color: #C6A968;
        }

        h3 {
            font-size: 18px;
            color: #77A29F;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: bold;
        }

        h4 {
            font-size: 10px;
            color: #776C65;
        }

        a:link {
            color: #507280;
        }

        a:visited {
            color: #706D42;
        }

        a:hover {
            color: #6F95A5;
        }

        a:active {
            color: #C6A968;
        }
    </style>
</head>

<body>
<p>&nbsp;</p>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td colspan="2" bgcolor="#37322F">
            <h4 style="text-align: center">Email not displaying correctly? Click
                <webversion>here</webversion>
                to view this email online.
            </h4>
        </td>
    </tr>
    <tr>
        <td colspan="2" bgcolor="#37322F"><img src="http://afgri.t.whitewallweb.net/wp-content/themes/afgri_theme/email/images/images/WelcomeTop.jpg" width="600" height="196" alt=""/></td>
    </tr>
    <tr>
        <td colspan="2" bgcolor="#F5F4F0">
            <blockquote>
                <br>

                <h3>Thank you for your enquiry</h3>

                <p> Thank you for getting in touch. Your enquiry has been received and we will respond to your query
                    within 48 hours. <br>
                    <br />
                    Best Wishes,<br />
                    The AFGRI DogFolio Team <br />
                </p>
            </blockquote>
            <br>
        </td>
    </tr>
    <tr bgcolor="#37322F">
        <td height="30" colspan="2" style="text-align: center">
            <h4>
                <tweet>Follow on Twitter</tweet>
                |
                <fblike>Like us on Facebook</fblike>
                |
                <forwardtoafriend>Forward to a friend</forwardtoafriend>
            </h4>
        </td>
    </tr>
    <tr bgcolor="#37322F">
        <td width="337" height="122">
            <blockquote>
                <h4>You got this email because you signed up for our newsletter at www.afgridogfood.co.za.<br><br>
                    Our mailing address is: <br>
                    AFGRI Limited<br>
                    12 Byls Bridge Boulevard<br>
                    Highveld Ext 73<br>
                    Centurion, Gauteng 0046<br>
                    South Africa<br>
                    <br>
                    Copyright © 2014 AFGRI Limited, All rights reserved.</h4>
            </blockquote>
        </td>
        <td width="263" align="right" valign="bottom"><img src="http://afgri.t.whitewallweb.net/wp-content/themes/afgri_theme/email/images/images/ppbAFGRI-Logos.png" width="263" height="89" alt=""/></td>
    </tr>
    <tr bgcolor="#37322F">
        <td height="36" colspan="2" style="text-align: center">
            <h4>
                <unsubscribe>Unsubscribe from this list</unsubscribe>                |
                <preferences>Update subscription preferences</preferences>
            </h4>
        </td>
    </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
EOF;




add_filter( 'wp_mail_content_type', 'set_html_content_type_here' );
wp_mail( $email, 'Thank you for your enquiry', $message_to_user );
//reset content type
//remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
    $sent = wp_mail($to[$subject], $the_subject, $message, $headers);
    if ($sent) {
        die(json_encode(array('msg' => "Your message has been sent"))) ;
    }
} else {?>
<?php get_header(); ?>
<div class="container-bg">
    <div class="container center-content">
        <div class="row">
            <div class="col-xs-12 col-md-9 ml">
                <div class="main-left">
                    <h2>Contact Us</h2>
                    <?php echo $response ?>
                    <div class="row">
                        <form id="contact-form" action="<?php the_permalink() ?>" parsley-validate method="post">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Name *</label>
                                    <input type="text" class="form-control" name="message[name]" required placeholder="Enter name">
                                </div>
                                <div class="form-group">
                                    <label>Surname *</label>
                                    <input type="text" class="form-control" name="message[surname]" required placeholder="Enter surname">
                                </div>
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" class="form-control" name="message[email]" required placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label>Mobile</label>
                                    <input type="text" class="form-control" name="message[mobile]" placeholder="Enter mobile">
                                </div>
                                <div class="form-group">
                                    <label>Region</label>
                                    <select name="message[region]" class="form-control">
                                        <option>Please select</option>
	                                <option value="EC">Eastern Cape</option>
                                        <option value="FS">Free State</option>
                                        <option value="GA">Gauteng</option>
                                        <option value="KZN">KwaZulu-Natal</option>
                                        <option value="LIM">Limpopo</option>
                                        <option value="MP">Mpumalanga</option>
                                        <option value="NAM">Namibia</option>
                                        <option value="NC">Northern Cape</option>
                                        <option value="NW">North West</option>
                                        <option value="SWA">Swaziland</option>
                                        <option value="WC">Western Cape</option>
                                      </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 hello">
                                <div class="form-group">
                                    <label>Subject *</label>
                                    <select  name="message[subject]" parsley-trigger="change"  parsley-required="true" class="form-control">
                                        <option value="">Please select</option>
                                        <option value="Compliments">Compliments</option>
                                        <option value="Complaints">Complaints</option>
                                        <option value="Enquiries">Enquiries</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea name="message[body]" required class="form-control message" rows="3"></textarea>
                                </div>
                                <a class="morebutton" id="submit-contact-form" href="#">Submit</a>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="alert alert-success" role="alert" style="display:none;margin-top:30px;"></div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                jQuery('#submit-contact-form').click(function(event) {
                    event.preventDefault();
                    jQuery('#contact-form').parsley({
                        successClass: 'success',
                        errorClass: 'error',
                        errors: {
                            classHandler: function(el) {
                                return jQuery(el).closest('.form-group');
                            },
                            errorsWrapper: '<span class=\"help-inline\"></span>',
                            errorElem: '<span></span>'
                        }
                    });
                    if (jQuery('#contact-form').parsley('validate')){
                        jQuery.ajax({
                            type: 'POST',
                            url: '<?php the_permalink() ?>',
                            data: jQuery('#contact-form').serialize(),
                            dataType: 'json',
                            success: function (data) {
                               // alert(data.msg);
                                jQuery(".alert").html(data.msg);
                                jQuery(".alert").show().fadeIn('slow');
                            }

                        });
                    }
                    return false;
                });
            </script>

            <div class="col-xs-3 mr">
                <?php get_sidebar(); ?>
            </div>

        </div>

    </div>

</div> <!-- /container -->
<?php get_footer();  } ?>
