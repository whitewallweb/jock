<?php
    $user_ID = get_current_user_id();
    $meta = array_map( function( $a ){ return $a[0]; }, get_user_meta( $user_ID ) );
    $data = get_userdata( $user_ID );

    $username = $data->user_login;
    $firstname = $data->first_name;
    $surname = $data->last_name;
    $email = $data->user_email;

    $nickname = $meta['nickname'];
    $mobile = $meta['mobile'];
    $region = $meta['region'];
    $user_gender = $meta['user_gender'];
    $newsletter = $meta['newsletter'];
    $mobile = $meta['mobile'];
    $region = $meta['region'];
    $user_gender = $meta['user_gender'];

    $pet_name = array();
    $breed = array();
    $pet_age = array();
    $pet_weight = array();
    $pet_gender = array();
    $pet_food_of_choice = array();

for($i = 1;$i <= 10; $i++) {
    $pet_name[$i] = $meta['pet_name_'.$i];
    $breed[$i] = $meta['breed_'.$i];
    $pet_age[$i] = $meta['pet_age_'.$i];
    $pet_weight[$i] = $meta['pet_weight_'.$i];
    $pet_gender[$i] = $meta['pet_gender_'.$i];
    $pet_food_of_choice[$i] = $meta['pet_food_of_choice_'.$i];
    $pet_activity_level[$i] = $meta['pet_activity_level_'.$i];
}

?>
<script>
    //alert(<?php echo $all_meta_for_user?>);
    jQuery(document).ready(function ($$) {
        $$("#firstname").val('<?php echo $firstname ?>');
        $$("#surname").val('<?php echo $surname ?>');
        $$("#nickname").val('<?php echo $nickname ?>');
        $$("#email").val('<?php echo $email ?>');
        $$("#mobile").val('<?php echo $mobile ?>');
        $$("#region").val('<?php echo $region ?>');
        if('<?php echo $user_gender ?>' == 'male'){ $$("#user_gender_m").prop("checked",true);} else {$$("#user_gender_f").prop("checked",true);}
        if('<?php echo $newsletter ?>' == 'yes'){ $$("#newsletter_y").prop("checked",true);} else {$$("#newsletter_n").prop("checked",true);}

        <?php for($i = 1;$i <= 10; $i++) {?>
            $$("#pet_name_"+<?php echo $i ?>).val('<?php echo $pet_name[$i] ?>');
            $$("#breed_"+<?php echo $i ?>).val('<?php echo $breed[$i] ?>');
            $$("#pet_age_"+<?php echo $i ?>).val('<?php echo $pet_age[$i] ?>');
            $$("#pet_weight_"+<?php echo $i ?>).val('<?php echo $pet_weight[$i] ?>');
            if("<?php echo $pet_gender[$i] ?>" == "male"){ $$("#pet_gender_"+<?php echo $i ?>+"_m").prop("checked",true);} else {$$("#pet_gender_"+<?php echo $i ?>+"_f").prop("checked",true);}
            $$("#pet_food_of_choice_" + <?php echo $i ?>).val('<?php echo $pet_food_of_choice[$i] ?>');
            $$("#pet_activity_level_" + <?php echo $i ?>).val('<?php echo $pet_activity_level[$i] ?>');
        <?php } ?>

        $$("#checkbox").prop("checked", true);
        $$("#terms").hide();
        $$("#checkboxdiv").hide();
        $$("#passworddiv").hide();

        <?php if(!empty($meta['pet_name_3'])) {?>
            $$("#tab3").show();
            tabclick++;
            <?php if(!empty($meta['pet_name_4'])) {?>
                $$("#tab4").show();
                tabclick++;
                <?php if(!empty($meta['pet_name_5'])) {?>
                    $$("#tab5").show();
                    tabclick++;
                    <?php if(!empty($meta['pet_name_6'])) {?>
                        $$("#tab6").show();
                        tabclick++;
                        <?php if(!empty($meta['pet_name_7'])) {?>
                            $$("#tab7").show();
                            tabclick++;
                            <?php if(!empty($meta['pet_name_8'])) {?>
                                $$("#tab8").show();
                                tabclick++;
                                <?php if(!empty($meta['pet_name_9'])) {?>
                                    $$("#tab9").show();
                                    tabclick++;
                                    <?php if(!empty($meta['pet_name_10'])) {?>
                                        $$("#tab10").show();
                                        tabclick++;
                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        <?php }?>
                    <?php }?>
                <?php }?>
            <?php }?>
        <?php } ?>
    });
</script>