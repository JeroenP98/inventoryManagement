<?php 
// start sessions su user data gets carried over
//session_start();

// define the page name for dynamic page name loading in header file
$page_name = "Over ons | GreenHome";



//include the header html
require_once "../include/header.php";
?>

<body class="d-flex flex-column h-100">
<!-- Hero end-->
    <section>
      <div class="section">
        <!-- Grid Container Start-->
        <div class="row-overons row row5_overons">
          <div class="col-12 row6_overons ">
            <h2 class="row0_overons">  <?php echo translate('over ons');?>   </h2>
          </div>
        </div>

        <div class="row-overons row row1_overons ">
            <div class="col-4 d-flex  justify-content-center ">
              <h3 class="text-white"><?php echo translate('ONZE MISSIE');?></h3>
            </div>
            <div class="col-8 d-flex justify-content-center">
              <p class="text-white"><?php echo translate('Duurzame meubels in iedere woonkamer, voor een groenere wereld.');?>
              </p>
            </div>
        </div>

        <div class="row-overons row row2_overons">
            <div class="col-4 d-flex justify-content-center">
              <h3 class="text-white"><?php echo translate('ONZE VISIE');?></h3>
            </div>
            <div class="col-8 d-flex justify-content-center">
              <p class="text-white"><?php echo translate('Het produceren van energie neutrale meubels door met leveranciers en producenten kritisch te kijken naar de supply chain en samen nieuwe oplossingen te bedenken.');?></p>
            </div>
          </div>

          <div class="row-overons row row3_overons">
              <div class="col-4 d-flex justify-content-center">
                <h3 class="row3_overons_text"><?php echo translate('ONZE WAARDES')?></h3>
              </div>
              <div class="col-8 d-flex justify-content-center">
                <p class="row3_overons_text"><?php echo translate('Met onze focus op duurzaamheid en verantwoordelijkheid willen we een positieve impact hebben op het milieu, terwijl we tegelijkertijd hoogwaardige en stijlvolle meubels leveren aan onze klanten.')?></p>
              </div>

          </div>
    </section> <!-- Grid Container End-->
<?php 

//include the footer html
require_once "../include/footer.php"; 
?>
