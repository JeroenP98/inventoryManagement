<?php 
require_once 'header.php';
?>
<!-- Hero begin-->
<section class="hero-section">
    <div class="container-fluid h-100">
        <div class="row align-items-center h-100">
            <div class="col-6 indexGrid">
                <div class="grid_item_1">
                    <img class="" src="images/virender-singh-hE0nmTffKtM-unsplash.jpg" alt="Company impression">
                </div>
            </div>
            <div class="col-6 indexGrid ">
                <div class="grid_item_2 text-center">
                    <h1>GreenHome</h1>
                    <h2>Furniture with an eco friendly origin</h2>
                    <a href="collectie.html"><button class="btn btn-lg btn-primary">Discover</button></a>
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
            <h1>Een impactvolle manier van wonen</h1>
            <p class="indexText">Stijl je woonkamer met de meest moderne stijlen van dit moment, met een duurzame
                boventoon</p>
        </div>



        <div class="col-8">
            <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="image-wrapper"
                            style="background-image: url('./images/bilal-mansuri-Pp5IwU-m8uc-unsplash.jpg')"></div>
                    </div>
                    <div class="carousel-item">
                        <div class="image-wrapper"
                            style="background-image: url('./images/jason-wang-NxAwryAbtIw-unsplash.jpg')"></div>
                    </div>
                    <div class="carousel-item">
                        <div class="image-wrapper"
                            style="background-image: url('./images/guven-gunes-Iy3GN4iPLW8-unsplash.jpg')"></div>
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
            <a href="./collectie.html" class="image-anchor"><img src="./images/spacejoy-0JGRXomKXSw-unsplash.jpg"
                    class="w-100 h-100" alt="link naar productpagina"></a>
        </div>

        <div class="col-4 d-flex flex-column justify-content-center align-items-center">
            <h1>Duurzaamheid voorop</h1>
            <p class="indexText">Duurzaam ontwikkelde meubelstukken voor de milieubewuste geest</p>
        </div>
    </div>
</div>
<!-- Content end -->

<script>
    $(document).ready(function () {
        $('.carousel').carousel({
            interval: 2000 // Change interval time in milliseconds here
        });
    });
</script>

<?php 
require_once 'footer.php';
?>