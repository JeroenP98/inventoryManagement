<?php 
// start sessions su user data gets carried over
//session_start();

// define the page name for dynamic page name loading in header file
$page_name = "Contact | GreenHome";

//include the header html
require_once "../include/header.php";
?>
    <!-- Back to top button-->
    <button onclick="backToTop()" id="top"><img src="./images/arrowhead-up.png" alt="Back to top button"></button>

    <div class="contact_container">
      <div class="contact_background">
        <div class="contact_links">
          <div class="contact_links_top">
            <h1><?php echo translate('Contactgegevens')?> </h1>
          </div>
          <div class="NAW_grid">
            <span class="grid_item1"><i class="fa-regular fa-envelope"></i></span>
            <span class="grid_item2"><a href="mailto:jf.post1@student.avans.nl">contact@GreenHome.nl</a></span>
            <span class="grid_item3"><i class="fa-solid fa-phone"></i></i></i></span>
            <span class="grid_item4"><a href="tel:0885257500">+316 12345678</a></span>
            <span class="grid_item5"><i class="fa-solid fa-map-location-dot"></i></i></span>
            <span class="grid_item6"><a
                href="https://www.google.com/maps?saddr=My+Location&daddr=hogeschoollaan+1+breda"
                target="_blank">Hogeschoollaan 1, 4818
                CR Breda</a></span>
          </div>
          <div class="maps">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2479.1383395512426!2d4.79577231591493!3d51.584027912918245!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c6a1d826c8567d%3A0xdaa7f5a2dd8692b6!2sHogeschoollaan%201%2C%204818%20CR%20Breda!5e0!3m2!1snl!2snl!4v1665433169093!5m2!1snl!2snl"
              allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
        <div class="contact_rechts">
          <div class="contact_rechts_top">
            <h1><?php echo translate('Contactformulier')?></h1>
          </div>
          <div class="form_body">
            <form action="https://formspree.io/f/mqkjldll" method="POST" id="my-form">
              <!--action to submit the form to the formspree server. responses get sent to baseballjeroen@hotmail.com
              Login to formspree.io with 
              username: baseballjeroen@hotmail.com
              password: 6VEUrk-ihD-K44u
              -->
              <label for="name"><?php echo translate('Naam')?></label>
              <br>
              <input type="text" id="name" name="id" placeholder="Jan Janssen" class="form-focus" required>
              <br>
              <label for="email"><?php echo translate('E-mail adres')?></label>
              <br>
              <input type="email" id="email" name="email" placeholder="jan@hotmail.com" class="form-focus" required>
              <br>
              <label for="message"><?php echo translate('Jouw bericht')?></label>
              <br>
              <textarea name="message" id="message" cols="43" rows="10"
                placeholder="Schrijf hier jouw bericht aan ons"></textarea>
              <br>
              <input type="submit" name="submit" value="verstuur" id="submit" class="form-focus" required>
            </form>
          </div>
        </div>
      </div>
    </div>
<?php 

//include the footer html
require_once "../include/footer.php"; 
?>

