<?php
$urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', $urlArray);
$numSegments = count($segments);
$currentSegment = $segments[$numSegments - 2];
if ($currentSegment == 'grandeur'){
?>

<div class="row rowspace-20 s-prod">
    <div class="col-xs-4"><img src="<?php echo get_template_directory_uri()?>/img/products/grandeur/Product---Grandeur.png"></div>
    <div class="col-xs-8">
        <h2>GRANDEUR</h2>
        <p>GRANDEUR is enriched with a unique blend of vitamins and minerals. Only the best quality ingredients are used, which ensures exceptional nutritional value and palatability.</p>
    </div>
</div>
<div class="row s-prod">
    <div class="col-xs-4">
        <p class="orange-sub">Enriched with:</p>
        <div class="row enrich">
            <div class="col-xs-6">
                <img src="<?php echo get_template_directory_uri()?>/img/products/grandeur/Grandeur-Icons1.png" style="width: 90; height: 90;">
                <img src="<?php echo get_template_directory_uri()?>/img/products/grandeur/Grandeur-Icons2.png" style="width: 90; height: 90;">
                <img src="<?php echo get_template_directory_uri()?>/img/products/grandeur/Grandeur-Icons3.png" style="width: 90; height: 90;">
                <img src="<?php echo get_template_directory_uri()?>/img/products/grandeur/Grandeur-Icons4.png" style="width: 90; height: 90;">
            </div>
            <div class="col-xs-6">
                <img src="<?php echo get_template_directory_uri()?>/img/products/grandeur/Grandeur-Icons5.png" style="width: 90; height: 90;">
                <img src="<?php echo get_template_directory_uri()?>/img/products/grandeur/Grandeur-Icons6.png" style="width: 90; height: 90;">
                <img src="<?php echo get_template_directory_uri()?>/img/products/grandeur/Grandeur-Icons7.png" style="width: 90; height: 90;">
                <img src="<?php echo get_template_directory_uri()?>/img/products/grandeur/Grandeur-Icons8.png" style="width: 90; height: 90;">
            </div>
        </div>
    </div>
    <div class="col-xs-8">
        <p class="orange-sub">Benefits of GRANDEUR:</p>
        <p class="grey-sub">Joint cartilage health</p>
        <p>hondroitin sulphate and Glucosamine hydrochloride support mobility by maintaining healthy joint cartilage.</p>

        <p class="grey-sub">Betaine</p>
        <p>
            Betaine is a strong osmolyte, which plays an important role in the prevention of dehydration.
            Betaine also lowers the levels of homocysteine (a toxic metabolite) that have been
            linked to cardiovascular disease.
        </p>
        <p class="grey-sub">High quality carbohydrates</p>
        <p>
            Supplies abundant energy for maximum activity, growth and development.
        </p>
        <p class="grey-sub">Antioxidants</p>
        <p>
            Enriched with Vitamin E, Vitamin C and chelated Selenium to help improve immunity.
        </p>
        <p class="grey-sub">Essential fatty acids</p>
        <p>
            Omega 6: Omega 3 fatty acids balanced for a healthy glossy coat.
        </p>
        <p class="grey-sub">Prebiotics</p>
        <p>
            FOS (Fructo oligosaccharides) is a prebiotic that modifies the microbial population in
            the intestine through competitive exclusion of harmful microflora
            and thus improves intestinal health.
        </p>
        <p class="grey-sub">Scientifically formulated amino acids</p>
        <p>
            Essential to maintain body muscle and other vital functions.
        </p>
        <p class="grey-sub">Calcium and Phosphorus ratio</p>
        <p>
            Balanced Calcium and Phosphorus ratio for developing and maintaining a strong skeletal structure.
        </p>

    </div>
</div>

<div class="divider"></div>
<?php
} if ($currentSegment == 'lek-a-lik'){
?>
<div class="row rowspace-20 s-prod">
    <div class="col-xs-4"><img src="<?php echo get_template_directory_uri()?>/img/products/lek-a-lik/Product---Lekalik.png"></div>
    <div class="col-xs-8">
        <h2>LEK-A-LIK</h2>
        <p>Adult dogs require a balanced diet which includes all the essential nutritional elements necessary to maintain a healthy and active lifestyle.</p>
    </div>
</div>
<div class="row s-prod">
    <div class="col-xs-4">
        <p class="orange-sub">Enriched with:</p>
        <div class="row enrich">
            <div class="col-xs-6">
                <img src="<?php echo get_template_directory_uri()?>/img/products/lek-a-lik/Lek-a-Lik-Icons1.png" style="width: 90; height: 90;">
            </div>
            <div class="col-xs-6">
                <img src="<?php echo get_template_directory_uri()?>/img/products/lek-a-lik/Lek-a-Lik-Icons2.png" style="width: 90; height: 90;">
            </div>
        </div>
    </div>
    <div class="col-xs-8">
        <p class="orange-sub">Benefits of LEK-A-LIK:</p>
        <ul>
            <li>LEK-A-LIK contains only the finest ingredients, which have been carefully selected and combined                to provide complete nutritional benefits for your beloved pet. </li>
            <li>LEK-A-LIK is also balanced with omega 3 and 6 fatty acids for a healthy skin and coat. </li>
            <li>LEK-A-LIK is manufactured from excellent quality ingredients, ensuring great tasting, and healthy food for your pet. </li>
        </ul>

    </div>
</div>

<div class="divider"></div>
<?php }  if ($currentSegment == 'jock'){
    ?>
    <div class="row rowspace-20 s-prod">
        <div class="col-xs-4"><img src="<?php echo get_template_directory_uri()?>/img/products/jock/Product---Jock.png"></div>
        <div class="col-xs-8">
            <h2>JOCK</h2>
            <p class="orange-sub">Nutrition is key!</p>
            <p>Your dog’s mental attitude, health, happiness, behaviour, longevity and overall well-being are directly related to nutrition. Proper diet, in correct proportions, or, in the case of canine athletes, optimal feed times can enhance performance, boost health to new levels and extend the life of your cherished pet.
                Dogs require a balanced diet of meat, vegetables and quality grains, and premium dog food provides these nutrients without adding inexpensive fillers and preservatives.</p>
        </div>
    </div>
    <div class="row s-prod">
        <div class="col-xs-4">
            <p class="orange-sub">Enriched with:</p>
            <div class="row enrich">
                <div class="col-xs-6">
                    <img src="<?php echo get_template_directory_uri()?>/img/products/jock/Jock-Icons1.png" style="width: 90; height: 90;">
                    <img src="<?php echo get_template_directory_uri()?>/img/products/jock/Jock-Icons2.png" style="width: 90; height: 90;">
                    <img src="<?php echo get_template_directory_uri()?>/img/products/jock/Jock-Icons3.png" style="width: 90; height: 90;">
                </div>
                <div class="col-xs-6">
                    <img src="<?php echo get_template_directory_uri()?>/img/products/jock/Jock-Icons4.png" style="width: 90; height: 90;">
                    <img src="<?php echo get_template_directory_uri()?>/img/products/jock/Jock-Icons5.png" style="width: 90; height: 90;">
                </div>
            </div>
        </div>
        <div class="col-xs-8">
            <p class="orange-sub">JOCK Benefits:</p>
            <ul>
                <li>
                    <p class="grey-sub">Superior Nutritional Value:</p>
                    <ul>
                        <li>
                            Sufficient calories to meet your dog’s needs for growth, activity and repair.
                        </li>
                        <li>
                            Optimal combination of fats, carbohydrates, proteins, vitamins, minerals and water.
                        </li>
                        <li>
                            Vitamin and mineral supplements in balanced concentrations
                        </li>
                    </ul>
                </li>
                <li>
                  <p>
                    <p class="grey-sub">Healthy Weight and More Energy:</p>
                    <p>
                        Once your dog adjusts to a premium dog food, you’ll find that him eating less, and because he is
                        receiving the required amount of vitamins, minerals and other nutrients, results in
                        him having more energy as well.
                    </p>
                </li>
                <li>
                    <p class="grey-sub">Less Health Risks:</p>
                    <p>
                        Additives and preservatives can cause a host of health problems, such as allergies and skin conditions,
                        while others can lead to even more dangerous conditions such as blindness or cancer.
                    </p>
                </li>
            </ul>

        </div>
    </div>

    <div class="divider"></div>
<?php }  ?>