<?php 
session_start();
$page_name = "Home | Greenhome";
require_once "../include/header.php"; 

?>
<!-- Hero begin-->
<section class="hero-section">
    <div class="container-fluid h-100">
        <div class="row align-items-center h-100">
            <div class="col-6 indexGrid">
                <div class="grid_item_1">
                    <img class="" src="../images/virender-singh-hE0nmTffKtM-unsplash.jpg" alt="Company impression">
                </div>
            </div>
            <div class="col-6 indexGrid ">
                <div class="grid_item_2 text-center">
                    <h1><?php echo translate('GreenHome')?></h1>
                    <h2><?php echo translate('Furniture with an eco friendly origin')?></h2>
                    <a href="../shop/GUI_shop.php"><button class="btn btn-lg btn-primary"><?php echo translate('Discover')?></button></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Hero end-->

<!-- Content start -->
<div class="container">
    <div class="row">
        <div class="col-4 d-flex flex-column justify-content-center align-items-center">
            <h1><?php echo translate('Een impactvolle manier van wonen')?></h1>
            <p class="indexText"><?php echo translate('Stijl je woonkamer met de meest moderne stijlen van dit moment, met een duurzameboventoon')?></p>
        </div>
        <div class="col-8">
            <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="image-wrapper"
                            style="background-image: url('../images/bilal-mansuri-Pp5IwU-m8uc-unsplash.jpg')"></div>
                    </div>
                    <div class="carousel-item">
                        <div class="image-wrapper"
                            style="background-image: url('../images/jason-wang-NxAwryAbtIw-unsplash.jpg')"></div>
                    </div>
                    <div class="carousel-item">
                        <div class="image-wrapper"
                            style="background-image: url('../images/guven-gunes-Iy3GN4iPLW8-unsplash.jpg')"></div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#myCarousel" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#myCarousel" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </a>
            </div>
        </div>



    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-8">
            <a href="../collectie/GUI_collectie.php" class="image-anchor"><img src="../images/spacejoy-0JGRXomKXSw-unsplash.jpg"
                    class="w-100 h-100" alt="link naar productpagina"></a>
        </div>

        <div class="col-4 d-flex flex-column justify-content-center align-items-center">
            <h1><?php echo translate('Duurzaamheid voorop')?></h1>
            <p class="indexText"><?php echo translate('Duurzaam ontwikkelde meubelstukken voor de milieubewuste geest')?></p>
        </div>
    </div>
</div>
<!-- Content end -->
<?php 
require_once '..\include\footer.php';
?>