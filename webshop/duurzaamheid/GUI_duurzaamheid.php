<?php 
// start sessions su user data gets carried over
session_start();

// define the page name for dynamic page name loading in header file
$page_name = "Duurzaamheid | GreenHome";

//include the header html
require_once "../include/header.php";
?>




<body class="d-flex flex-column h-100">

<section class="hero-sectio">
    <div class="container-fluid h-100">
        <div class="row align-items-center h-100">
            <div class="col-sm-9 indexGrid">
                <div class="grid_item_1 help">
                    <img class="bg-image duurzaamheid-bg-img" src="../images/Layer 2.png"  alt="Green energie">
                </div>
            </div>
            <div class="col-sm-2 indexGrid">
                <div class="grid_item_2 text-end">
                    <h1 class="row1-hero-tekst"><?php echo translate('Duurzame')?></h1>
                    <h1 class="row2-hero-tekst"><?php echo translate('Toekomst')?></h1>
                    <h1 class="row1-hero-tekst"><?php echo translate('Creeren')?></h1>
                </div>
            </div>
            <div class="col-sm-1 indexGrid">
            </div>
        </div>
    </div>

</section>
<!-- Hero end-->

<div class="container">
        <div class="row ">
            <div class="col-sm-8">
                <div class="Background-clean-environment">
                     <img class="img-duurzaamheid" src="../images/sustainability2.png" alt="cleaning the environment" class="w-1267 h-356">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="Background-clean-environment2">
                    <div>
                    <h1 class="H1_row1"><?php echo translate('Minder CO2 uitstoot')?></h1>
                    <p class="H1_row2"><?php echo translate('Onze fabrieken produceren energieneutraal. Zo werken wij aan een betere toekomst voor ons allemaal.')?> </p>
                    </div>
                </div>
            </div>
        </div>
</div>

<div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="Background-clean-environment3">
                    <div>
                    <h1 class="H1_row3"><?php echo translate('Een boom voor elke meubel')?></h1>
                    <p class="H1_row4"><?php echo translate('Buiten dat wij meubels energieneutraal produceren, geven wij ook terug aan de natuur.')?></p>
                    <p class="H1_row4"><?php echo translate('Voor ieder meubelstuk dat wij verkopen, planten wij 50 zaadjes. om zo weer nieuwe bomen een leven te geven')?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="Background-clean-environment4">
                     <img class="img-duurzaamheid" src="../images/sustainability3.png" alt="cleaning the environment" class="w-1267 h-356">
                </div>
            </div>
        </div>
</div>

<div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div class="Background-clean-environment">
                     <img class="img-duurzaamheid" src="../images/sustainability4.png" alt="cleaning the environment" class="w-1267 h-356">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="Background-clean-environment2">
                    <div>
                    <h1 class="H1_row1"><?php echo translate('Afvalscheiding')?></h1>
                    <p class="H1_row4"><?php echo translate('In onze fabrieken werken wij actief mee aan het scheiden van afval.')?> </p>
                    <p class="H1_row4"><?php echo translate('Delen van dit afval worden uiteindelijk weer verwerkt in onze meubels!')?> </p>
                    </div>
                </div>
            </div>
        </div>
</div>


<?php 

//include the footer html
require_once "../include/footer.php"; 
?>
