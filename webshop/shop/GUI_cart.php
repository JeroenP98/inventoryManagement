<?php
session_start();
$page_name = "Cart | GreenHome";
require_once "../include/header.php";
?>

<main>
  <div class="container justify-content-center">
    <div class="row">
      <h1 class="fw-bold">Winkelwagen & verzend details</h1>
      <hr>
      <?php if(isset($_GET['error_message'])):?>
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
          <p><strong>Oeps!</strong> <?=$_GET['error_message']?></p>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
    </div>
    <div class="row">
      <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary">Winkelwagen</span>
          <span class="badge bg-success rounded-pill">
            <?php 
            // calculate and show how many unique items are in the cart
            $cart_count = !empty($_SESSION['cart']) ? count($_SESSION['cart']) : "0"; echo $cart_count;
            ?>
          </span>
        </h4>
        <ul class="list-group mb-3">
          <?php 
          // load each cart item into the cart overview
          if(!empty($_SESSION['cart'])){
            $cart = $_SESSION['cart'];
            foreach($cart as $key => $cart_item){
          ?>
          <li class="list-group-item d-flex justify-content-between align-items-center lh-sm" id="<?=$key?>">
            <div>
              <h6 class="my-0"><?=$cart_item['article_name']?></h6>
            </div>
            <div class="d-flex">
              <form action="update_cart.php" method="POST">
                <input type="hidden" name="action" value="decrease">
                <input type="hidden" name="key" value="<?=$key?>">
                <button class="badge btn bg-secondary rounded-pill decrease-quantity" type="submit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                  <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                  </svg>
                </button>
              </form>
              <span class="badge bg-primary rounded-pill quantity mx-1" data-cart-item-id="<?=$key?>"><?=$cart_item['quantity']?>x</span>
              <form action="update_cart.php" method="POST">
                <input type="hidden" name="action" value="increase">
                <input type="hidden" name="key" value="<?=$key?>">
                <button class="badge btn bg-secondary rounded-pill decrease-quantity" type="submit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                  </svg>
                </button>
              </form>

            </div>
            <form action="update_cart.php" method="POST">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="key" value="<?=$key?>">
              <button class="btn px-2 align-items-center delete-item" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-trash" viewBox="0 0 16 16">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                </svg>
              </button>

            </form>
          </li>
          <?php
          }} else {?>
          <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">Je winkelwagen is leeg!</h6>
            </div>
          </li>

          <?php };  //close the if and foreach loop?>

        </ul>

        <hr class="my-4">
        
        <?php if(!empty($_SESSION['cart'])):?>
        <ul class="list-group">
          <li class="list-group-item fw-bold">
          <h4 class="d-flex justify-content-between" id="cart-total">
            <span class="text-primary">Totaal:</span>
            €<?php 
            $cart_total = 0;
            foreach($cart as $cart_item){
              $cart_total += ($cart_item['selling_price'] * $cart_item['quantity']);};

            echo $cart_total ?>
          </h4>  
          </li>
        </ul>
        <?php endif; ?>
      </div>
      <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Verzend details</h4>
        <form class="needs-validation" method="POST" action="controller_checkout.php">
          <?php 
          
          // disable form input fields if the cart is empty
          if(empty($_SESSION['cart'])):?>
          <fieldset disabled>
          <?php endif; ?>
          <div class="row g-3">

            <div class="col-sm-6">
              <label for="first_name" class="form-label">Voornaam</label>
              <input type="text" class="form-control" id="first_name"  name="first_name" required>
            </div>

            <div class="col-sm-6">
              <label for="last_name" class="form-label">Achternaam</label>
              <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>

            <div class="col-7">
              <label for="email_adress" class="form-label">Email</label>
              <input type="email" class="form-control" id="email_adress" name="email_adress" required>
            </div>

            <div class="col-5">
              <label for="phone_number" class="form-label">Telefoon</label>
              <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>

            <div class="col-8">
              <label for="street" class="form-label">Straat</label>
              <input type="text" class="form-control" id="street" name="street" required>
            </div>

            <div class="col-4">
              <label for="house_nr" class="form-label">Huisnummer</label>
              <input type="text" class="form-control" id="house_nr" name="house_nr" required>
            </div>

            <div class="col-md-5">
              <label for="country_code" class="form-label">Land</label>
              <select class="form-control" id="country_code" name="country_code" required>
                <option value="" disabled selected>Select a country</option>
              </select>
            </div>

            <script>
              // Make an HTTP GET request to the REST Countries API
              fetch('https://restcountries.com/v3.1/all')
                .then(response => response.json())
                .then(data => {

                  // Sort the data array by name.common
                  data.sort((a, b) => a.name.common.localeCompare(b.name.common));
                  // Get a reference to the select field
                  const select = document.getElementById('country_code');

                  // Loop through the data and add each country code as an option
                  data.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.cca2; // Use the cca2 property instead
                    option.text = country.name.common; // Use the cca2 property instead
                    select.add(option);
                  });
                })
                .catch(error => console.error(error));
            </script>



            <div class="col-md-4">
              <label for="city" class="form-label">Stad</label>
              <input type="text" class="form-control" id="city"  name="city" required>
            </div>

            <div class="col-md-3">
              <label for="zip_code" class="form-label">Postcode</label>
              <input type="text" class="form-control" id="zip_code" name="zip_code" required>
            </div>
          </div>
          <hr class="my-4">

          <button class="w-100 btn btn-primary btn-lg" type="submit">Place order</button>
          <?php 
          // close the fieldset tag when the cart is empty
          if(empty($_SESSION['cart'])):?>
          </fieldset>
          <?php endif; ?>


      
        </form>
      </div>
    </div>

  </div>
</main>
<?php require_once "../include/footer.php";?>