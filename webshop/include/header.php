

<!DOCTYPE html>
<html lang="en" class="h-100" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$page_name?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
        integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"
        defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"
        integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"
        defer></script>
    <script src="https://kit.fontawesome.com/65fb36ce5e.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
    <link rel="shortcut icon" href="../../images/logo.png">
    <!-- Links for Mapbox API tests
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
    <script defer src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>
    <script defer src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
    <script defer src="../js/mapbox_API.js"></script>
    -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <?php 



     // translation.php
    function translate($key) {
        if (!isset($_GET['lang'])) {
            $_SESSION['language'] = "nl";
        }
        $language = $_SESSION['language']; 
        $translations = include('translations/' . $language . '.php');
        return $translations[$key] ?? $key; 

    }

    ?>

</head>

<body class="d-flex flex-column h-100">


    <!-- Nav begin -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <a class="navbar-brand" href="../index/Index.php">
            <img src="../images/logo-green.svg" width="30" height="30" class="d-inline-block align-top" alt="">
        </a>
        <i class="flag flag-united-states"></i>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../index/Index.php"><?php echo translate('COLLECTIE')?> <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../shop/GUI_shop.php"><?php echo translate('SHOP')?> <span class="sr-only"></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../duurzaamheid/GUI_duurzaamheid.php"><?php echo translate('DUURZAAMHEID')?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../over ons/GUI_over_ons.php"><?php echo translate('OVER ONS')?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../contact/GUI_contact.php"><?php echo translate('CONTACT')?></a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <!-- shoppingcart -->
                <a class="navIcon shopIcon nav-item collection present-on-mobile" href="../shop/GUI_cart.php"
                    aria-label="Link naar het winkelmandje">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" id="shopBasket" class="navitem shop-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </a>
                <!-- user profile -->
                <a class="navIcon userIcon nav-item collection present-on-mobile" href="../../dashboard.php"
                    aria-label="Link naar het gebruikersprofiel">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="navitem user-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
                <li class="nav-item hidden-on-mobile">
                    <a id="iconText-shop" class="iconText navitem collection nav-link" href="../shop/GUI_cart.php"
                        aria-label="Link naar het winkelmandje"><?php echo translate('WINKELWAGEN')?></a>
                </li>
                <li class="nav-item hidden-on-mobile">
                    <a id="iconText-user" class="iconText navitem collection nav-link" href="../profiel/GUI_profiel.php"
                        aria-label="Link naar het gebruikersprofiel"><?php echo translate('PROFIEL')?></a>
                </li>
            </ul>
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php 
            if (isset($_GET['lang'])) {
                $allowedLanguages = array("en", "nl", "de");
                $userLanguage = $_GET['lang'];

                if (in_array($userLanguage, $allowedLanguages)) {
                    $_SESSION['language'] = $userLanguage;
                    echo $_SESSION['language'] ?? 'Language';
                } else {
                    echo "Gekozen taal is niet beschikbaar";
                    // Additional error handling if desired
                }
            }
            else {
                //$_SESSION['language'] = "nl"; // default taal nederlands
                echo $_SESSION['language'] ?? 'Language';
            }
             ?>
          </button>
          <div class="dropdown-menu language-selector" aria-labelledby="languageDropdown">
            <a class="dropdown-item" href="?lang=nl"> <i class="flag flag-netherlands"></i> Nederlands</a>
            <a class="dropdown-item" href="?lang=en"> <i class="flag flag-united-kingdom"></i> English </a>
            <a class="dropdown-item" href="?lang=de"><i class="flag flag-germany"></i> Deutsch</a>
          </div>
        </div>
        </div>


    </nav>


    
    <!-- Nav end -->