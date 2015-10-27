<?php

/**
 * Template Name: Register
 */
?>

<?php get_header(); ?>
    <div class="container-bg">

    <div class="container center-content">
    <div class="row">

    <div class="col-xs-6 reg-left">
        <div class="reg-welcome">
            <span>Welcome to</span>

            <h2>DogFolio</h2>

            <p>
                Register your precious pet on our DogFolio and gain access to breed- and need-specific
                information.
            </p>
        </div>
        <p>
            <img src="<?php echo get_template_directory_uri(); ?>/img/reg-info.png">
        </p>

        <p>
            <img src="<?php echo get_template_directory_uri(); ?>/img/reg-news.png">
        </p>

        <p>
            <img src="<?php echo get_template_directory_uri(); ?>/img/reg-promo.png">
        </p>

        <p>
            <img src="<?php echo get_template_directory_uri(); ?>/img/reg-comp.png">
        </p>

        <p>
            <img src="<?php echo get_template_directory_uri(); ?>/img/reg-events.png">
        </p>

    </div>

    <div class="col-xs-12 col-md-6 reg-right">
    <div class="main-left">
    <div class="row">
    <div id='success' class="hidden">
        <h3>Thank You!</h3>

        <p>
            Thank you for registering with AFGRI DogFolio. Your request has been received and a confirmation email will
            be sent shortly.
        </p>
    </div>
    <div class="col-xs-12">
        <h2 id="formtitle" style="padding-bottom: 5px !important;">Personal information</h2>

        <div id="logindiv">
            <?php if (!is_user_logged_in()) { ?>
                <p>Please log in to change your details or register below</p>
            <?php } else { ?>
                <?php include("editform.php"); ?>
            <?php } ?>
            <?php if (function_exists('wplb_login')) {
                wplb_login();
            } ?>
        </div>
        <div style="clear:both"></div>

    </div>
    <form action="<?php echo admin_url('admin-ajax.php?action=my_register'); ?>" parsley-validate method="post"
          id="reg-form">
    <div class="col-xs-12">

    <div class="form-group">
        <label>Name *</label>
        <input type="text" class="form-control" name="first_name" id="firstname" required placeholder="Enter name">
    </div>
    <div class="form-group">
        <label>Surname *</label>
        <input type="text" class="form-control" name="last_name" id="surname" required placeholder="Enter surname">
    </div>
    <div class="form-group">
        <label>Email *</label>
        <input type="email" class="form-control" name="email" id="email" required placeholder="Enter email">
    </div>
    <div class="form-group">
        <label>Username *</label>
        <input type="text" class="form-control" name="username" id="nickname" required placeholder="Enter Username" <?php if(is_user_logged_in()) {?>readonly<?php }?>>
    </div>
    <div class="form-group" id="passworddiv">
        <label>Password *</label>
        <?php if (is_user_logged_in()) { ?>
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
        <?php } else { ?>
            <input type="password" class="form-control" name="password" id="password" required
                   placeholder="Enter password">
        <?php } ?>
    </div>
    <div class="form-group">
        <label>Mobile </label>
        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter mobile">
    </div>
    <div class="form-group">
        <label>Region *</label>
        <select name="region" class="form-control" id="region">
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
    <label>Gender *</label>

    <div class="form-group">
        <div class="radio-inline">
            <label>
                <input type="radio" id="user_gender_f" name="user_gender" parsley-required="true" value="female"/>
                Female
            </label>
        </div>
        <div class="radio-inline">
            <label>
                <input type="radio" id="user_gender_m" name="user_gender" value="male"/>
                Male
            </label>
        </div>
    </div>
    <label>Sign up for our newsletter</label>

    <div class="form-group">
        <div class="radio-inline">
            <label>
                <input type="radio" name="newsletter" id="newsletter_y" value="yes" class="check" checked/>
                yes
            </label>
        </div>
        <div class="radio-inline">
            <label>
                <input type="radio" name="newsletter" id="newsletter_n" value="no"/>
                no
            </label>
        </div>
    </div>
    <h2 style="padding-bottom: 15px;">Dog information</h2>


    <!-- Tab Headers Start -->
    <ul class="tabs" id="dynatab">
        <li><a href="#tabview1">1st Dog</a></li>
        <li><a href="#tabview2">2nd</a></li>
        <li id="tab3" style="display: none;"><a href="#tabview3">3rd</a></li>
        <li id="tab4" style="display: none;"><a href="#tabview4">4th</a></li>
        <li id="tab5" style="display: none;"><a href="#tabview5">5th</a></li>
        <li id="tab6" style="display: none;"><a href="#tabview6">6th</a></li>
        <li id="tab7" style="display: none;"><a href="#tabview7">7th</a></li>
        <li id="tab8" style="display: none;"><a href="#tabview8">8th</a></li>
        <li id="tab9" style="display: none;"><a href="#tabview9">9th</a></li>
        <li id="tab10" style="display: none;"><a href="#tabview10">10th</a></li>
        <li id="addtab"><a href="javascript:addTab()" id="addtab">+</a></li>
    </ul>
    <!-- Tab Headers End -->
    <!-- Tab Body Start -->
    <div class="tabcontents" id="dynabody">
    <div id="tabview1">
        <div class="form-group">
            <label>Dog's name</label>
            <input type="text" class="form-control" name="pet_name_1" id="pet_name_1" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label>Breed</label>
            <?php
            global $wpdb;
            $breeds = $wpdb->get_results("SELECT * FROM wp_breeds;");
            ?>
            <select name='breed_1' id="breed_1" class="form-control">
                <option value="">Please select</option>
                <?php foreach ($breeds as $breed) { ?>
                    <option
                        value="<?php echo $breed->breed_id . ':' . $breed->breed_size ?>"><?php echo $breed->breed ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Age (in human years)</label>
            <input type="text" class="form-control" name="pet_age_1" id="pet_age_1" placeholder="Enter pet age">
        </div>
        <div class="form-group">
            <label>Weight</label>
            <input type="text" class="form-control" name="pet_weight_1" id="pet_weight_1"
                   placeholder="Enter weight (KG)">
        </div>
        <div class="form-group">
            <label>Activity level</label>
            <select name='pet_activity_level_1' id='pet_activity_level_1' class="form-control">
                <option value="">Please select</option>
                <option value="low">Lap Dog (low activity)</option>
                <option value="moderate">Dog of the town (Moderate activity)</option>
                <option value="high">Adventure Dog (Highly Active)</option>
            </select>
        </div>

        <label>Gender</label>

        <div class="form-group  edit">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_1" id="pet_gender_1_f" value="female" checked>
                    Female
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_1" id="pet_gender_1_m" value="male">
                    Male
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>Where do you buy your dog food?</label>
            <select name="pet_food_of_choice_1" id="pet_food_of_choice_1" class="form-control">
                <option>Please select</option>
                <option value="Online">Online</option>
                <option value="Supermarket">Supermarket</option>
                <option value="Veterinary practice">Veterinary practice</option>
                <option value="Speciality pet store">Speciality pet store</option>
            </select>
        </div>
    </div>


    <div id="tabview2">
        <div class="form-group">
            <label>Dog's name</label>
            <input type="text" class="form-control" name="pet_name_2" id="pet_name_2" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label>Breed</label>
            <?php
            global $wpdb;
            $breeds = $wpdb->get_results("SELECT * FROM wp_breeds;");
            ?>
            <select name='breed_2' id="breed_2" class="form-control">
                <option value="">Please select</option>
                <?php foreach ($breeds as $breed) { ?>
                    <option
                        value="<?php echo $breed->breed_id . ':' . $breed->breed_size ?>"><?php echo $breed->breed ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Age (in human years)</label>
            <input type="text" class="form-control" name="pet_age_2" id="pet_age_2" placeholder="Enter pet age">
        </div>
        <div class="form-group">
            <label>Weight</label>
            <input type="text" class="form-control" name="pet_weight_2" id="pet_weight_2"
                   placeholder="Enter weight (KG)">
        </div>
        <div class="form-group">
            <label>Activity level</label>
            <select name='pet_activity_level_2' id='pet_activity_level_2' class="form-control">
                <option value="">Please select</option>
                <option value="low">Lap Dog (low activity)</option>
                <option value="moderate">Dog of the town (Moderate activity)</option>
                <option value="high">Adventure Dog (Highly Active)</option>
            </select>
        </div>

        <label>Gender</label>

        <div class="form-group  edit">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_2" id="pet_gender_2_f" value="female" checked>
                    Female
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_2" id="pet_gender_2_m" value="male">
                    Male
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>Where do you buy your dog food?</label>
            <select name="pet_food_of_choice_2" id="pet_food_of_choice_2" class="form-control">
                <option>Please select</option>
                <option value="Online">Online</option>
                <option value="Supermarket">Supermarket</option>
                <option value="Veterinary practice">Veterinary practice</option>
                <option value="Speciality pet store">Speciality pet store</option>
            </select>
        </div>
    </div>


    <div id="tabview3">
        <div class="form-group">
            <label>Dog's name</label>
            <input type="text" class="form-control" name="pet_name_3" id="pet_name_3" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label>Breed</label>
            <?php
            global $wpdb;
            $breeds = $wpdb->get_results("SELECT * FROM wp_breeds;");
            ?>
            <select name='breed_3' id="breed_3" class="form-control">
                <option value="">Please select</option>
                <?php foreach ($breeds as $breed) { ?>
                    <option
                        value="<?php echo $breed->breed_id . ':' . $breed->breed_size ?>"><?php echo $breed->breed ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Age (in human years)</label>
            <input type="text" class="form-control" name="pet_age_3" id="pet_age_3" placeholder="Enter pet age">
        </div>
        <div class="form-group">
            <label>Weight</label>
            <input type="text" class="form-control" name="pet_weight_3" id="pet_weight_3"
                   placeholder="Enter weight (KG)">
        </div>
        <div class="form-group">
            <label>Activity level</label>
            <select name='pet_activity_level_3' id='pet_activity_level_3' class="form-control">
                <option value="">Please select</option>
                <option value="low">Lap Dog (low activity)</option>
                <option value="moderate">Dog of the town (Moderate activity)</option>
                <option value="high">Adventure Dog (Highly Active)</option>
            </select>
        </div>

        <label>Gender</label>

        <div class="form-group  edit">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_3" id="pet_gender_3_f" value="female" checked>
                    Female
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_3" id="pet_gender_3_m" value="male">
                    Male
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>Where do you buy your dog food?</label>
            <select name="pet_food_of_choice_3" id="pet_food_of_choice_3" class="form-control">
                <option>Please select</option>
                <option value="Online">Online</option>
                <option value="Supermarket">Supermarket</option>
                <option value="Veterinary practice">Veterinary practice</option>
                <option value="Speciality pet store">Speciality pet store</option>
            </select>
        </div>
    </div>


    <div id="tabview4">
        <div class="form-group">
            <label>Dog's name</label>
            <input type="text" class="form-control" name="pet_name_4" id="pet_name_4" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label>Breed</label>
            <?php
            global $wpdb;
            $breeds = $wpdb->get_results("SELECT * FROM wp_breeds;");
            ?>
            <select name='breed_4' id="breed_4" class="form-control">
                <option value="">Please select</option>
                <?php foreach ($breeds as $breed) { ?>
                    <option
                        value="<?php echo $breed->breed_id . ':' . $breed->breed_size ?>"><?php echo $breed->breed ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Age (in human years)</label>
            <input type="text" class="form-control" name="pet_age_4" id="pet_age_4" placeholder="Enter pet age">
        </div>
        <div class="form-group">
            <label>Weight</label>
            <input type="text" class="form-control" name="pet_weight_4" id="pet_weight_4"
                   placeholder="Enter weight (KG)">
        </div>
        <div class="form-group">
            <label>Activity level</label>
            <select name='pet_activity_level_4' id='pet_activity_level_4' class="form-control">
                <option value="">Please select</option>
                <option value="low">Lap Dog (low activity)</option>
                <option value="moderate">Dog of the town (Moderate activity)</option>
                <option value="high">Adventure Dog (Highly Active)</option>
            </select>
        </div>

        <label>Gender</label>

        <div class="form-group  edit">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_4" id="pet_gender_4_f" value="female" checked>
                    Female
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_4" id="pet_gender_4_m" value="male">
                    Male
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>Where do you buy your dog food?</label>
            <select name="pet_food_of_choice_4" id="pet_food_of_choice_4" class="form-control">
                <option>Please select</option>
                <option value="Online">Online</option>
                <option value="Supermarket">Supermarket</option>
                <option value="Veterinary practice">Veterinary practice</option>
                <option value="Speciality pet store">Speciality pet store</option>
            </select>
        </div>
    </div>


    <div id="tabview5">
        <div class="form-group">
            <label>Dog's name</label>
            <input type="text" class="form-control" name="pet_name_5" id="pet_name_5" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label>Breed</label>
            <?php
            global $wpdb;
            $breeds = $wpdb->get_results("SELECT * FROM wp_breeds;");
            ?>
            <select name='breed_5' id="breed_5" class="form-control">
                <option value="">Please select</option>
                <?php foreach ($breeds as $breed) { ?>
                    <option
                        value="<?php echo $breed->breed_id . ':' . $breed->breed_size ?>"><?php echo $breed->breed ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Age (in human years)</label>
            <input type="text" class="form-control" name="pet_age_5" id="pet_age_5" placeholder="Enter pet age">
        </div>
        <div class="form-group">
            <label>Weight</label>
            <input type="text" class="form-control" name="pet_weight_5" id="pet_weight_5"
                   placeholder="Enter weight (KG)">
        </div>
        <div class="form-group">
            <label>Activity level</label>
            <select name='pet_activity_level_5' id='pet_activity_level_5' class="form-control">
                <option value="">Please select</option>
                <option value="low">Lap Dog (low activity)</option>
                <option value="moderate">Dog of the town (Moderate activity)</option>
                <option value="high">Adventure Dog (Highly Active)</option>
            </select>
        </div>

        <label>Gender</label>

        <div class="form-group  edit">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_5" id="pet_gender_5_f" value="female" checked>
                    Female
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_5" id="pet_gender_5_m" value="male">
                    Male
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>Where do you buy your dog food?</label>
            <select name="pet_food_of_choice_5" id="pet_food_of_choice_5" class="form-control">
                <option>Please select</option>
                <option value="Online">Online</option>
                <option value="Supermarket">Supermarket</option>
                <option value="Veterinary practice">Veterinary practice</option>
                <option value="Speciality pet store">Speciality pet store</option>
            </select>
        </div>
    </div>


    <div id="tabview6">
        <div class="form-group">
            <label>Dog's name</label>
            <input type="text" class="form-control" name="pet_name_6" id="pet_name_6" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label>Breed</label>
            <?php
            global $wpdb;
            $breeds = $wpdb->get_results("SELECT * FROM wp_breeds;");
            ?>
            <select name='breed_6' id="breed_6" class="form-control">
                <option value="">Please select</option>
                <?php foreach ($breeds as $breed) { ?>
                    <option
                        value="<?php echo $breed->breed_id . ':' . $breed->breed_size ?>"><?php echo $breed->breed ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Age (in human years)</label>
            <input type="text" class="form-control" name="pet_age_6" id="pet_age_6" placeholder="Enter pet age">
        </div>
        <div class="form-group">
            <label>Weight</label>
            <input type="text" class="form-control" name="pet_weight_6" id="pet_weight_6"
                   placeholder="Enter weight (KG)">
        </div>
        <div class="form-group">
            <label>Activity level</label>
            <select name='pet_activity_level_6' id='pet_activity_level_6' class="form-control">
                <option value="">Please select</option>
                <option value="low">Lap Dog (low activity)</option>
                <option value="moderate">Dog of the town (Moderate activity)</option>
                <option value="high">Adventure Dog (Highly Active)</option>
            </select>
        </div>

        <label>Gender</label>

        <div class="form-group  edit">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_6" id="pet_gender_6_f" value="female" checked>
                    Female
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_6" id="pet_gender_6_m" value="male">
                    Male
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>Where do you buy your dog food?</label>
            <select name="pet_food_of_choice_6" id="pet_food_of_choice_6" class="form-control">
                <option>Please select</option>
                <option value="Online">Online</option>
                <option value="Supermarket">Supermarket</option>
                <option value="Veterinary practice">Veterinary practice</option>
                <option value="Speciality pet store">Speciality pet store</option>
            </select>
        </div>
    </div>


    <div id="tabview7">
        <div class="form-group">
            <label>Dog's name</label>
            <input type="text" class="form-control" name="pet_name_7" id="pet_name_7" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label>Breed</label>
            <?php
            global $wpdb;
            $breeds = $wpdb->get_results("SELECT * FROM wp_breeds;");
            ?>
            <select name='breed_7' id="breed_7" class="form-control">
                <option value="">Please select</option>
                <?php foreach ($breeds as $breed) { ?>
                    <option
                        value="<?php echo $breed->breed_id . ':' . $breed->breed_size ?>"><?php echo $breed->breed ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Age (in human years)</label>
            <input type="text" class="form-control" name="pet_age_7" id="pet_age_7" placeholder="Enter pet age">
        </div>
        <div class="form-group">
            <label>Weight</label>
            <input type="text" class="form-control" name="pet_weight_7" id="pet_weight_7"
                   placeholder="Enter weight (KG)">
        </div>
        <div class="form-group">
            <label>Activity level</label>
            <select name='pet_activity_level_7' id='pet_activity_level_7' class="form-control">
                <option value="">Please select</option>
                <option value="low">Lap Dog (low activity)</option>
                <option value="moderate">Dog of the town (Moderate activity)</option>
                <option value="high">Adventure Dog (Highly Active)</option>
            </select>
        </div>

        <label>Gender</label>

        <div class="form-group  edit">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_7" id="pet_gender_7_f" value="female" checked>
                    Female
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_7" id="pet_gender_7_m" value="male">
                    Male
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>Where do you buy your dog food?</label>
            <select name="pet_food_of_choice_7" id="pet_food_of_choice_7" class="form-control">
                <option>Please select</option>
                <option value="Online">Online</option>
                <option value="Supermarket">Supermarket</option>
                <option value="Veterinary practice">Veterinary practice</option>
                <option value="Speciality pet store">Speciality pet store</option>
            </select>
        </div>
    </div>


    <div id="tabview8">
        <div class="form-group">
            <label>Dog's name</label>
            <input type="text" class="form-control" name="pet_name_8" id="pet_name_8" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label>Breed</label>
            <?php
            global $wpdb;
            $breeds = $wpdb->get_results("SELECT * FROM wp_breeds;");
            ?>
            <select name='breed_8' id="breed_8" class="form-control">
                <option value="">Please select</option>
                <?php foreach ($breeds as $breed) { ?>
                    <option
                        value="<?php echo $breed->breed_id . ':' . $breed->breed_size ?>"><?php echo $breed->breed ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Age (in human years)</label>
            <input type="text" class="form-control" name="pet_age_8" id="pet_age_8" placeholder="Enter pet age">
        </div>
        <div class="form-group">
            <label>Weight</label>
            <input type="text" class="form-control" name="pet_weight_8" id="pet_weight_8"
                   placeholder="Enter weight (KG)">
        </div>
        <div class="form-group">
            <label>Activity level</label>
            <select name='pet_activity_level_8' id='pet_activity_level_8' class="form-control">
                <option value="">Please select</option>
                <option value="low">Lap Dog (low activity)</option>
                <option value="moderate">Dog of the town (Moderate activity)</option>
                <option value="high">Adventure Dog (Highly Active)</option>
            </select>
        </div>

        <label>Gender</label>

        <div class="form-group  edit">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_8" id="pet_gender_8_f" value="female" checked>
                    Female
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_8" id="pet_gender_8_m" value="male">
                    Male
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>Where do you buy your dog food?</label>
            <select name="pet_food_of_choice_8" id="pet_food_of_choice_8" class="form-control">
                <option>Please select</option>
                <option value="Online">Online</option>
                <option value="Supermarket">Supermarket</option>
                <option value="Veterinary practice">Veterinary practice</option>
                <option value="Speciality pet store">Speciality pet store</option>
            </select>
        </div>
    </div>


    <div id="tabview9">
        <div class="form-group">
            <label>Dog's name</label>
            <input type="text" class="form-control" name="pet_name_9" id="pet_name_9" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label>Breed</label>
            <?php
            global $wpdb;
            $breeds = $wpdb->get_results("SELECT * FROM wp_breeds;");
            ?>
            <select name='breed_9' id="breed_9" class="form-control">
                <option value="">Please select</option>
                <?php foreach ($breeds as $breed) { ?>
                    <option
                        value="<?php echo $breed->breed_id . ':' . $breed->breed_size ?>"><?php echo $breed->breed ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Age (in human years)</label>
            <input type="text" class="form-control" name="pet_age_9" id="pet_age_9" placeholder="Enter pet age">
        </div>
        <div class="form-group">
            <label>Weight</label>
            <input type="text" class="form-control" name="pet_weight_9" id="pet_weight_9"
                   placeholder="Enter weight (KG)">
        </div>
        <div class="form-group">
            <label>Activity level</label>
            <select name='pet_activity_level_9' id='pet_activity_level_9' class="form-control">
                <option value="">Please select</option>
                <option value="low">Lap Dog (low activity)</option>
                <option value="moderate">Dog of the town (Moderate activity)</option>
                <option value="high">Adventure Dog (Highly Active)</option>
            </select>
        </div>

        <label>Gender</label>

        <div class="form-group  edit">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_9" id="pet_gender_9_f" value="female" checked>
                    Female
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_9" id="pet_gender_9_m" value="male">
                    Male
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>Where do you buy your dog food?</label>
            <select name="pet_food_of_choice_9" id="pet_food_of_choice_9" class="form-control">
                <option>Please select</option>
                <option value="Online">Online</option>
                <option value="Supermarket">Supermarket</option>
                <option value="Veterinary practice">Veterinary practice</option>
                <option value="Speciality pet store">Speciality pet store</option>
            </select>
        </div>
    </div>


    <div id="tabview10">
        <div class="form-group">
            <label>Dog's name</label>
            <input type="text" class="form-control" name="pet_name_10" id="pet_name_10" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label>Breed</label>
            <?php
            global $wpdb;
            $breeds = $wpdb->get_results("SELECT * FROM wp_breeds;");
            ?>
            <select name='breed_10' id="breed_10" class="form-control">
                <option value="">Please select</option>
                <?php foreach ($breeds as $breed) { ?>
                    <option
                        value="<?php echo $breed->breed_id . ':' . $breed->breed_size ?>"><?php echo $breed->breed ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Age (in human years)</label>
            <input type="text" class="form-control" name="pet_age_10" id="pet_age_10" placeholder="Enter pet age">
        </div>
        <div class="form-group">
            <label>Weight</label>
            <input type="text" class="form-control" name="pet_weight_10" id="pet_weight_10"
                   placeholder="Enter weight (KG)">
        </div>
        <div class="form-group">
            <label>Activity level</label>
            <select name='pet_activity_level_10' id='pet_activity_level_10' class="form-control">
                <option value="">Please select</option>
                <option value="low">Lap Dog (low activity)</option>
                <option value="moderate">Dog of the town (Moderate activity)</option>
                <option value="high">Adventure Dog (Highly Active)</option>
            </select>
        </div>

        <label>Gender</label>

        <div class="form-group  edit">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_10" id="pet_gender_10_f" value="female" checked>
                    Female
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="pet_gender_10" id="pet_gender_10_m" value="male">
                    Male
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>Where do you buy your dog food?</label>
            <select name="pet_food_of_choice_10" id="pet_food_of_choice_10" class="form-control">
                <option>Please select</option>
                <option value="Online">Online</option>
                <option value="Supermarket">Supermarket</option>
                <option value="Veterinary practice">Veterinary practice</option>
                <option value="Speciality pet store">Speciality pet store</option>
            </select>
        </div>
    </div>


    </div>
    <!-- Tab Body End -->
    <div class="form-group" style="margin-top:15px;">
        <label>What is 12 + 8?</label>
        <input type="text" class="form-control" name="robot" id="robot" required parsley-equalto="#hiddenfield" parsley-error-message="What is 12 + 8?"placeholder="Enter answer">
        <input type="hidden" value="20" name="hiddenfield" id="hiddenfield">
    </div>

    <label id="terms"><a href="#" class="" data-toggle="modal" data-target="#basicModal">Terms and conditions
            *</a></label>

    <div class="checkbox" id="checkboxdiv">
        <label>
            <input type="checkbox" name="terms" parsley-required="true" value="accepted" id="checkbox">
            I accept.
        </label>
    </div>
    </div>
    <a id="submit-reg-form" class="morebutton" href="#" style="margin-top: 15px;">Submit</a>
    </div>
    </form>
    </div>

    </div>
    </div>


    </div>
    </div>
    <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="modal-title" id="myModalLabel">Terms and Conditions</h4>
    </div>
    <div class="modal-body">
    <p>
        1. ACCEPTANCE OF THESE TERMS
    </p>

    <p>
        These terms and conditions ("terms") govern your use of the AFGRI Website/s ("Websites") including your
        application to register as a Client and your online
        application(s) for facilities with AFGRI.These terms constitute an agreement between you and AFGRI ("AFGRI") and
        the then current version of the terms
        become binding on you each time you visit the Websites.
    </p>

    <p>
        The terms of this agreement may be applicable together with further General Terms and Specific Terms entered
        into.
    </p>

    <p>
        2. ACCESS CODES
    </p>

    <p>
        When you register on the Websites you will need to select a password and enter your identity number ("access
        codes"). By entering your access codes AFGRI
        is entitled to assume that the person using the Websites is you. You are solely responsible for ensuring that
        you safeguard the confidentiality of your
        access codes. AFGRI will not be responsible if you fail to do this.
    </p>

    <p>
        3. PASSWORD
    </p>

    <p>
        If you have a password you undertake to keep it secure and warrant that no other person shall use the Websites
        with your password, and you acknowledge
        further that you are responsible for ensuring that no unauthorised access to the Websites is obtained using your
        password, and that you will be liable for
        all such activities conducted pursuant to such use, whether authorised or not.
    </p>

    <p>
        4. SERVICES
    </p>

    <p>
        4.1 Once you have registered using the online facility, AFGRI will contact you authenticating registration by
        verifying your details.
    </p>

    <p>
        4.2 AFGRI may offer you new services from time to time and reserves the right to modify,replace or discontinue
        any existing service without prior notice to
        you.
    </p>

    <p>
        4.3 You undertake to acquaint yourself with the functionality of the Websites and how it is to be used and, if
        needed, enlist the assistance of AFGRI.
    </p>

    <p>
        4.4 You undertake to acquaint yourself with and follow the security procedures communicated by AFGRI from time
        to time as well as such other procedures as
        may be applicable to the Websites and specifically those that may be displayed on AFGRI's internet Websites.
    </p>

    <p>
        4.5 You hereby acknowledge that any failure on the part of yourself to follow the recommended security
        procedures may result in a breach of the
        confidentiality of the your confidential information and may lead to unauthorised access to your account and
        information.
    </p>

    <p>
        4.6 You undertake to ensure that nobody other than yourself in person is permitted to use the Websites to which
        you have subscribed.
    </p>

    <p>
        In the event that you are a business, you shall ensure that only authorised employees have access to and are
        allowed to use the Websites.
    </p>

    <p>
        4.7 You undertake to ensure the safekeeping and confidentiality of all confidential information, and shall
        particularly ensure that the confidential
        information is not written down and kept where it can easily be discovered.
    </p>

    <p>
        4.8 You undertake to notify AFGRI immediately upon reasonably becoming aware or suspecting that confidential
        information has been lost or forgotten or may
        have fallen into the hands of an unauthorised person.
    </p>

    <p>
        4.9 You shall not cede or assign any of your rights under this agreement without the prior written consent of
        AFGRI.
    </p>

    <p>
        4.10 You shall not operate or use the Websites in any manner that may be prejudicial to AFGRI.
    </p>

    <p>
        5. INFORMATION ON THIS SITE
    </p>

    <p>
        5.1 The online facility allows you, a registered user, to access your account/s held with AFGRI.
    </p>

    <p>
        5.2 The information provided is intended to provide you with account and transaction details of your account/s
        held with AFGRI.
    </p>

    <p>
        5.3 All information regarding the facility, product and services including information in respect of the terms
        and conditions, rates of return or any other
        matters, is subject to change without notice.
    </p>

    <p>
        5.4 AFGRI is not responsible for web casting or any other form of transmission from linked sites.
    </p>

    <p>
        5.5 AFGRI provides certain links to you only as a convenience, and the inclusion of any links does not imply
        endorsement by AFGRI of the site, their
        business or security practices or any association with its operators.
    </p>

    <p>
        5.6 Without derogating from the generality of the above, and to the extent legally permitted, AFGRI will not be
        liable for:
    </p>

    <p>
        5.6.1 Any interruption, malfunction, downtime, off-line situation or other failure of the site or online
        services, AFGRI's system, databases or any of its
        components, beyond AFGRI's reasonable control;
    </p>

    <p>
        5.6.2 Any loss or damage with regard to your data or other data directly or indirectly caused by malfunction of
        AFGRI's system, third party systems, power
        failures, unlawful access to or theft of data,computer viruses or destructive code on AFGRI's system or third
        party systems or programming defects;
    </p>

    <p>
        5.6.3 Any interruption, malfunction, downtime or other failure of goods or services provided by third parties,
        including, without limitation, third party
        systems such as the public switched telecommunication service providers; internet service providers, electricity
        suppliers, local authorities and
        certification authorities; or any event over which AFGRI has no direct control.
    </p>

    <p>
        6. TRANSMISSION OF INFORMATION
    </p>

    <p>
        6.1 AFGRI is not responsible for the proper and/or complete transmission of the information contained in the
        electronic communication or of the electronic
        communication itself nor in any delay in its receipt.
    </p>

    <p>
        6.2 Security measures have been implemented to ensure the safety and integrity of the Websites. Please read the
        privacy policy for more information.
        However, despite this, information that is transmitted over the Internet may be susceptible to unlawful access
        and monitoring.
    </p>

    <p>
        7. DEEMED RULES FOR SENDING AND RECEIVING ELECTRONIC MESSAGES
    </p>

    <p>
        7.1 You hereby acknowledge that AFGRI will primarily use e-mail and electronic notices on the Websites, as
        AFGRI's main communication tool for all
        communications relating to the Websites, or these terms and conditions. Such communications may include the use
        of sms (short message services), registered
        mail or telephonic advice.
    </p>

    <p>
        7.2 You and AFGRI hereby agree that the provisions of Part 2 of Chapter III of the Electronic Communications
        &amp; Transactions Act 25 of 2002 are hereby
        excluded and that the following rules will apply when AFGRI and youself send each other electronic messages via
        any electronic means, including via the
        Websites and its application forms and email("communication system").
    </p>

    <p>
        7.3 Where you make an offer to AFGRI, an agreement is formed at the time AFGRI sends you its written acceptance
        of your offer.
    </p>

    <p>
        An automated or manual acknowledgement of receipt of your electronic message shall not be deemed to constitute
        acceptance.
    </p>

    <p>
        7.4 All electronic messages will be deemed to have been sent from, and received at your specified e-mail address
        and AFGRI's address as specified in the
        Websites.
    </p>

    <p>
        7.5 An electronic message is deemed to have been sent:
    </p>

    <p>
        - by you, at the time at which AFGRI is capable of accessing such message;
    </p>

    <p>
        - by AFGRI, at the time shown on the electronic message as having been sent or,
    </p>

    <p>
        if not so shown, at the time shown on our computer system as having been sent.
    </p>

    <p>
        7.6 An electronic message is deemed to be received -
    </p>

    <p>
        - by you, once it becomes capable of being retrieved by you;
    </p>

    <p>
        - by AFGRI, once AFGRI has confirmed receipt thereof or responded thereto, whichever is the earlier.
    </p>

    <p>
        7.7 An electronic message shall be attributed -
    </p>

    <p>
        - to you, if it purports to have originated from you, irrespective of the fact that someone else may have
        impersonated you or whether the electronic
        message sent to AFGRI resulted from an error or malfunction in the communication system;
    </p>

    <p>
        - to AFGRI, if it has been sent by a duly authorised representative and such representative acted within the
        scope of such authority or by an automated
        system programmed by AFGRI and such system operated without error or malfunction.
    </p>

    <p>
        7.8 Unless otherwise provided for in these terms, confirmation of receipt of your electronic message is required
        to give legal effect to such electronic
        message.
    </p>

    <p>
        8. CAUSES BEYOND REASONABLE CONTROL
    </p>

    <p>
        Neither you nor AFGRI, nor AFGRI's IT personnel will be held liable for any failure to perform any obligation to
        the other due to causes beyond your, AFGRI
        or AFGRI's IT personnel's respective reasonable control, including lightning, flood, exceptionally severe
        weather, fire,explosion, war, civil disorder,
        industrial disputes, acts or omissions of persons for whom AFGRI is not responsible (including
        telecommunications and internet service providers) or acts
        of government or other competent authorities.
    </p>

    <p>
        9. ACCURACY OF INFORMATION
    </p>

    <p>
        AFGRI gives no guarantee of any kind concerning the content on our Websites. AFGRI does not give any warranty
        (express or implied)or make any
        representation that AFGRI's online service will operate error free or without interruption or that any errors
        will be corrected or that the content is
        complete, accurate, up to date, or being fit for a particular purpose.
    </p>

    <p>
        10. VIRUSES
    </p>

    <p>
        Whilst AFGRI will take reasonable steps to exclude viruses from the Websites, and do not guarantee or warrant
        that any material available for downloading
        from AFGRI's Websites will be free from infection, viruses and/or other code that has contaminating or
        destructive properties and no liability is accepted
        for viruses. You are responsible for and recommended to take your own precautions and implement sufficient
        procedures and virus checks (including running
        anti-virus software and other security checks) to satisfy your particular requirements.
    </p>

    <p>
        11. HYPERLINKS
    </p>

    <p>
        This site may contain hyper-links to third party sites. AFGRI is not responsible for the content of, or the
        services offered by those sites. The
        hyper-link(s) are provided solely for your convenience and should not be construed as an express or implied
        endorsement by AFGRI of the site(s)or the
        products or services provided therein. You access those sites and use their products and services solely at your
        own risk.
    </p>

    <p>
        12. WARRANTEES AND REPRESENTATIONS
    </p>

    <p>
        AFGRI makes no representations or warranties, whether express or implied, and assume no liability or
        responsibility for the proper performance of the
        Websites and/or the services and/or the information and/or images contained on the Websites, and the services
        are thus used at your own risk. In particular
        AFGRI makes no warranty that the services will meet your requirements, be uninterrupted, complete, timely,
        secure or error free.
    </p>

    <p>
        13. INDEMNIFICATION
    </p>

    <p>
        You indemnify and hold AFGRI harmless against all and any loss, liability, actions, suites, proceedings,costs,
        demands and damages of all and every kind,
        (including direct, indirect, special or consequential damages), and whether in an action based on contract,
        negligence or any other action, arising out of
        or in connection with the failure or delay in the performance of the services offered on the Websites, or the
        use of the services, information and/or
        images available on the Websites, whether due to AFGRI's negligence or not.
    </p>

    <p>
        14. PRIVACY POLICY
    </p>

    <p>
        14.1 AFGRI and all its associated companies are committed to respecting the privacy of your personal data. To
        demonstrate our commitment, AFGRI has created
        this Privacy Statement in order to communicate its intent to provide effective processes for the appropriate
        handling of such private information and to
        comply with applicable legislation that governs the authentication, protection and disclosure of personal
        information.
    </p>

    <p>
        14.2 AFGRI does not distribute any of your personal information to third parties; unless it's required to
        deliver the products or services requested by
        you. In addition, AFGRI will not sell your personal information to third parties unless you give us your
        specific permission to do so. For example, AFGRI
        may disclose your data to a credit card company to obtain payment for a purchase you initiated. It may also be
        necessary to pass on your data to a supplier
        who will deliver the product on order. In addition, AFGRI may be obligated to disclose personal information to
        meet any legal or regulatory requirements of
        applicable laws.
    </p>

    <p>
        14.3 AFGRI reserves the right to amend or modify this Privacy statement at any time in response to new privacy
        legislation.
    </p>

    <p>
        14.4 Whilst your name and e-mail address which is supplied to AFGRI when registering / applying for the
        facility, will not automatically be made available
        to the recipient of your SMS, AFGRI is nevertheless able to trace the source of an SMS, and such information
        will be made available to the authorities if
        required by law.
    </p>

    <p>
        14.5 Monitoring or recording of your calls, e-mails or SMS's may take place for business purposes to the extent
        permitted by law, such as for example
        quality control and training for the purposes of marketing and improving the services. However, in these
        situations, AFGRI will not disclose information
        that could be used to personally identify you.
    </p>

    <p>
        15. CAPACITY TO ENTER INTO AGREEMENTS
    </p>

    <p>
        15.1 You hereby warrant to AFGRI that you have the required legal capacity to enter into and be bound by these
        terms.
    </p>

    <p>
        15.2 Minors must be assisted by their legal guardians when reading these terms.
    </p>

    <p>
        15.3 Accept the terms herein by clicking on "Accept Terms" below.
    </p>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
    </div>
    </div>
    </div>
    <script type="text/javascript">
        var tabclick = 2;
        jQuery('#submit-reg-form').click(function (event) {
            event.preventDefault();
            jQuery('#reg-form').parsley({
                successClass: 'success',
                errorClass: 'error',
                errors: {
                    classHandler: function (el) {
                        return jQuery(el).closest('.form-group');
                    },
                    errorsWrapper: '<span class=\"help-inline\"></span>',
                    errorElem: '<span></span>'
                }
            });
            if (jQuery('#reg-form').parsley('validate')) {
                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php?action=my_register'); ?>',
                    data: jQuery('#reg-form').serialize(),
                    dataType: 'json',
                    success: function (data) {
                        if (data.error) {
                            alert(data.error.msg);
                        } else {
                            //alert(data.success.msg);
                            <?php if(is_user_logged_in()) {?>
                            alert("Your details have been updated");
                            <?php } else { ?>
                            jQuery('#reg-form').hide();
                            jQuery('#formtitle').hide();
                            jQuery('#logindiv').hide();
                            jQuery('#success').removeClass('hidden');
                            <?php } ?>
                        }
                    }
                });
            }
            return false;
        });

        jQuery(document).ready(function ($$) {
            $$("#dynatab").dynatabs({
                tabBodyID: "dynabody"
            });

            //Prevent page jump with hash links
            $$('a[href^="#"]').click(function (e) {
                e.preventDefault();
            });

            $$("ul li").on('click', function () {
                $("ul li").removeClass('selected');
                $(this).addClass('selected');
            });
        });

        function addTab() {
            $("ul li").removeClass('selected');

            $("#tabview" + tabclick).hide();
            tabclick++;
            $("#tab" + tabclick).show().fadeIn('slow');
            $("#tabview" + tabclick).show();
            $("#tab" + tabclick).addClass('active selected');
            if (tabclick == 10) {
                $("#addtab").hide();
            }
        }
    </script>

    <style>
        /* JQUERY EASY TABS */
        .etabs {
            margin: 0;
            padding: 0;
        }

        .tab {
            display: inline-block;
            zoom: 1;
            *display: inline;
            background: #eee;
            border: solid 1px #999;
            border-bottom: none;
            -moz-border-radius: 4px 4px 0 0;
            -webkit-border-radius: 4px 4px 0 0;
        }

        .tab a {
            font-size: 14px;
            line-height: 2em;
            display: block;
            padding: 0 10px;
            outline: none;
        }

        .tab a:hover {
            text-decoration: underline;
        }

        .tab.active {
            background: #fff;
            padding-top: 6px;
            position: relative;
            top: 1px;
            border-color: #666;
        }

        .tab a.active {
            font-weight: bold;
        }

        .tab-container .panel-container {
            background: #fff;
            border: solid #666 1px;
            padding: 10px;
            -moz-border-radius: 0 4px 4px 4px;
            -webkit-border-radius: 0 4px 4px 4px;
        }

        .panel-container {
            background: #fff;
            border: solid #666 1px;
            padding: 10px;
            -moz-border-radius: 0 4px 4px 4px;
            -webkit-border-radius: 0 4px 4px 4px;
        }
    </style>

    </div> <!-- /container -->
<?php get_footer(); ?>