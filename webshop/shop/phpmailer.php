<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


//Load Composer's autoloader
require '../../vendor/autoload.php';

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['order_id'])){

  require_once "../../php/include/db_connect.php";

  // declare order variables to be used in the email body
    $order_id = $_GET['order_id'];

    $sql = 
    "SELECT relations.name, CONCAT(relations.street, ' ', relations.house_nr) AS 'delivery_address', relations.zip_code, relations.city, relations.country_code, relations.phone_number, relations.phone_number
    FROM orders
    JOIN relations
      ON orders.relation_id = relations.id
    WHERE orders.id = $order_id";

    // Construct the SQL query
    $sql = "SELECT orders.id, relations.name, CONCAT(relations.street, ' ', relations.house_nr) AS 'delivery_address', relations.zip_code, relations.city, relations.country_code, relations.phone_number, relations.email_adress
    FROM orders
    JOIN relations
    ON orders.relation_id = relations.id
    WHERE orders.id = $order_id";

    // Execute the query
    $result = mysqli_query($connection, $sql);

    // Check if the query executed successfully
    if ($result) {
    // Fetch the data from the result set
    $row = mysqli_fetch_assoc($result);

    // Store the data in PHP variables
    $name = $row['name'];
    $deliveryAddress = $row['delivery_address'];
    $zipCode = $row['zip_code'];
    $city = $row['city'];
    $countryCode = $row['country_code'];
    $phoneNumber = $row['phone_number'];
    $emailAdress = $row['email_adress'];

    // Free the result set
    mysqli_free_result($result);
    } else {
    // Handle the case when the query fails
    echo "Query failed: " . mysqli_error($connection);
    }

    // create new query to load the article lines data:
    $sql = "SELECT articles.name AS 'article_name', order_lines.quantity AS 'quantity', articles.selling_price AS 'selling_price'
    FROM order_lines
    JOIN articles
      ON articles.id = order_lines.article_id
    WHERE order_lines.order_id = $order_id
    ;";

    if ($result) {
    //query the results
    $result = $connection->query($sql);

    //define empty variable to hold the article line html
    $article_lines = "";

    // add new order line to the variable for each article line
    while($row = $result->fetch_assoc()) {
      $article_lines .= 
      "<table class='row row-7' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;'>
      <tbody>
        <tr>
          <td>
            <table class='row-content' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 680px;' width='680'>
              <tbody>
                <tr>
                  <td class='column column-1' width='33.333333333333336%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                    <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                      <tr>
                        <td class='pad' style='padding-bottom:10px;padding-left:30px;padding-right:10px;padding-top:10px;'>
                          <div style='color:#101112;direction:ltr;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:16.8px;'>
                            <p style='margin: 0;'>". $row['article_name'] ."</p>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class='column column-2' width='33.333333333333336%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                    <table class='paragraph_block block-1' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                      <tr>
                        <td class='pad'>
                          <div style='color:#101112;direction:ltr;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:16.8px;'>
                            <p style='margin: 0;'>". $row['quantity'] ."</p>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class='column column-3' width='33.333333333333336%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                    <table class='paragraph_block block-1' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                      <tr>
                        <td class='pad'>
                          <div style='color:#101112;direction:ltr;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:16.8px;'>
                            <p style='margin: 0;'>&euro; ". $row['selling_price'] * $row['quantity'] ."</p>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>";
    }
    } else {
    // Handle the case when the query fails
    echo "Query failed: " . mysqli_error($conn);
    }


    //define order total
    $sql = "SELECT SUM(articles.selling_price * order_lines.quantity) AS total_selling_price
    FROM order_lines
    JOIN articles ON order_lines.article_id = articles.id
    WHERE order_lines.order_id = $order_id";

    $result = $connection->query($sql);

    if($result){
    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);
    // Retrieve the total selling price from the result
    $order_total = $row['total_selling_price'];

    }else{
    echo "Query failed: " . mysqli_error($conn);
    }


  //Create an instance; passing `true` enables exceptions
  $mail = new PHPMailer(true);
  
  try {
      //Server settings
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = 'smtp-mail.outlook.com';                     //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
      $mail->Username   = 'jeroenf_post@outlook.com';                     //SMTP username
      $mail->Password   = 'Password';                               //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
      $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  
      //Recipients
      $mail->setFrom('jeroenf_post@outlook.com', 'GreenHome');
      $mail->addAddress($emailAdress);     //Add a recipient
  
  $body = 
    "<!DOCTYPE html>
    <html xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office' lang='en'>
    
    <head>
      <title></title>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
      <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'><!--<![endif]-->
      <style>
        * {
          box-sizing: border-box;
        }
    
        body {
          margin: 0;
          padding: 0;
        }
    
        a[x-apple-data-detectors] {
          color: inherit !important;
          text-decoration: inherit !important;
        }
    
        #MessageViewBody a {
          color: inherit;
          text-decoration: none;
        }
    
        p {
          line-height: inherit
        }
    
        .desktop_hide,
        .desktop_hide table {
          mso-hide: all;
          display: none;
          max-height: 0px;
          overflow: hidden;
        }
    
        .image_block img+div {
          display: none;
        }
    
        @media (max-width:700px) {
    
          .desktop_hide table.icons-inner,
          .social_block.desktop_hide .social-table {
            display: inline-block !important;
          }
    
          .icons-inner {
            text-align: center;
          }
    
          .icons-inner td {
            margin: 0 auto;
          }
    
          .row-content {
            width: 100% !important;
          }
    
          .mobile_hide {
            display: none;
          }
    
          .stack .column {
            width: 100%;
            display: block;
          }
    
          .mobile_hide {
            min-height: 0;
            max-height: 0;
            max-width: 0;
            overflow: hidden;
            font-size: 0px;
          }
    
          .desktop_hide,
          .desktop_hide table {
            display: table !important;
            max-height: none !important;
          }
        }
      </style>
    </head>
    
    <body style='margin: 0; background-color: #ffffff; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;'>
      <table class='nl-container' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;'>
        <tbody>
          <tr>
            <td>
              <table class='row row-1' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #508279;'>
                <tbody>
                  <tr>
                    <td>
                      <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px;' width='680'>
                        <tbody>
                          <tr>
                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <div class='spacer_block block-1' style='height:45px;line-height:45px;font-size:1px;'>&#8202;</div>
                              <table class='image_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                              <tr>
                                <td class='pad' style='width:100%;padding-right:0px;padding-left:0px;'>
                                  <div class='alignment' align='center' style='line-height:10px'><a href='http://127.0.0.1/inventorymanagement/webshop/index/index.php' target='_blank' style='outline:none' tabindex='-1'><img src='https://6f48b6326b.imgdist.com/public/users/Integrators/BeeProAgency/995182_979920/logo-main-white.png' style='display: block; height: auto; border: 0; width: 204px; max-width: 100%;' width='204' alt='Logo' title='Logo'></a></div>
                                </td>
                              </tr>
                            </table>
                              <div class='spacer_block block-3' style='height:45px;line-height:45px;font-size:1px;'>&#8202;</div>
                              <table class='image_block block-4' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                <tr>
                                  <td class='pad' style='width:100%;padding-right:0px;padding-left:0px;'>
                                    <div class='alignment' align='center' style='line-height:10px'><a href='http://127.0.0.1/inventorymanagement/webshop/index/index.php' target='_blank' style='outline:none' tabindex='-1'><img src='https://6f48b6326b.imgdist.com/public/users/Integrators/BeeProAgency/995182_979920/booking.png' style='display: block; height: auto; border: 0; width: 238px; max-width: 100%;' width='238'></a></div>
                                  </td>
                                </tr>
                              </table>
                              <div class='spacer_block block-5' style='height:20px;line-height:20px;font-size:1px;'>&#8202;</div>
                              <table class='text_block block-6' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                <tr>
                                  <td class='pad' style='padding-bottom:15px;padding-left:10px;padding-right:10px;padding-top:10px;'>
                                    <div style='font-family: sans-serif'>
                                      <div class style='font-size: 14px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 16.8px; color: #ffffff; line-height: 1.2;'>
                                        <p style='margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;'><strong><span style='font-size:38px;'>Jouw bestelling is binnen!</span></strong></p>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              <table class='text_block block-7' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                <tr>
                                  <td class='pad' style='padding-bottom:20px;padding-left:60px;padding-right:60px;padding-top:10px;'>
                                    <div style='font-family: sans-serif'>
                                      <div class style='font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 21.6px; color: #ffffff; line-height: 1.8;'>
                                        <p style='margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 32.4px;'><span style='font-size:18px;'>Blijf rustig zitten, wij gaan er mee aan de slag</span></p>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              <table class='button_block block-8' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                <tr>
                                  <td class='pad'>
                                    <div class='alignment' align='center'><!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='http://127.0.0.1/inventorymanagement/webshop/index/index.php' style='height:42px;width:207px;v-text-anchor:middle;' arcsize='10%' stroke='false' fillcolor='#ffffff'><w:anchorlock/><v:textbox inset='0px,0px,0px,0px'><center style='color:#33563c; font-family:Tahoma, sans-serif; font-size:16px'><![endif]--><a href='http://127.0.0.1/inventorymanagement/webshop/index/index.php' target='_blank' style='text-decoration:none;display:inline-block;color:#33563c;background-color:#ffffff;border-radius:4px;width:auto;border-top:0px solid #8a3b8f;font-weight:undefined;border-right:0px solid #8a3b8f;border-bottom:0px solid #8a3b8f;border-left:0px solid #8a3b8f;padding-top:5px;padding-bottom:5px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:16px;text-align:center;mso-border-alt:none;word-break:keep-all;'><span style='padding-left:40px;padding-right:40px;font-size:16px;display:inline-block;letter-spacing:normal;'><span dir='ltr' style='word-break: break-word; line-height: 32px;'><strong>Track jouw order</strong></span></span></a><!--[if mso]></center></v:textbox></v:roundrect><![endif]--></div>
                                  </td>
                                </tr>
                              </table>
                              <div class='spacer_block block-9' style='height:45px;line-height:45px;font-size:1px;'>&#8202;</div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class='row row-2' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                <tbody>
                  <tr>
                    <td>
                      <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px;' width='680'>
                        <tbody>
                          <tr>
                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <div class='spacer_block block-1' style='height:25px;line-height:25px;font-size:1px;'>&#8202;</div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class='row row-3' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                <tbody>
                  <tr>
                    <td>
                      <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px;' width='680'>
                        <tbody>
                          <tr>
                            <td class='column column-1' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <table class='text_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                <tr>
                                  <td class='pad' style='padding-bottom:5px;padding-left:30px;padding-right:30px;padding-top:10px;'>
                                    <div style='font-family: sans-serif'>
                                      <div class style='font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 21.6px; color: #33563c; line-height: 1.8;'>
                                        <p style='margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 25.2px;'><strong><span style='font-size:16px;'>Waar gaan we bezorgen:</span></strong></p>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              <table class='text_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                <tr>
                                  <td class='pad' style='padding-bottom:10px;padding-left:30px;padding-right:30px;padding-top:10px;'>
                                    <div style='font-family: sans-serif'>
                                      <div class style='font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 21.6px; color: #33563c; line-height: 1.8;'>
                                        <p style='margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 28.8px;'><span style='font-size:16px;'><strong><span style>" . $name . "</span></strong></span></p>
                                        <p style='margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 28.8px;'><span style='font-size:16px;'><span style>" . $deliveryAddress ."</span></span></p>
                                        <p style='margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 28.8px;'><span style='font-size:16px;'><span style>". $zipCode . ", " . $city .  " " . $countryCode . "</span></span></p>
                                        <p style='margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 28.8px;'><span style='font-size:16px;'><span style>". $phoneNumber ."</span></span></p>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td class='column column-2' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                <tr>
                                  <td class='pad' style='width:100%;padding-right:0px;padding-left:0px;'>
                                    <div class='alignment' align='center' style='line-height:10px'><img src='https://6f48b6326b.imgdist.com/public/users/Integrators/BeeProAgency/995182_979920/fast-delivery.png' style='display: block; height: auto; border: 0; width: 187px; max-width: 100%;' width='187'></div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class='row row-4' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                <tbody>
                  <tr>
                    <td>
                      <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px;' width='680'>
                        <tbody>
                          <tr>
                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <div class='spacer_block block-1' style='height:20px;line-height:20px;font-size:1px;'>&#8202;</div>
                              <table class='divider_block block-2' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                <tr>
                                  <td class='pad'>
                                    <div class='alignment' align='center'>
                                      <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                        <tr>
                                          <td class='divider_inner' style='font-size: 1px; line-height: 1px; border-top: 1px solid #BBBBBB;'><span>&#8202;</span></td>
                                        </tr>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              <div class='spacer_block block-3' style='height:20px;line-height:20px;font-size:1px;'>&#8202;</div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class='row row-5' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                <tbody>
                  <tr>
                    <td>
                      <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px;' width='680'>
                        <tbody>
                          <tr>
                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <table class='text_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                <tr>
                                  <td class='pad' style='padding-bottom:5px;padding-left:30px;padding-right:30px;padding-top:10px;'>
                                    <div style='font-family: sans-serif'>
                                      <div class style='font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 21.6px; color: #33563c; line-height: 1.8;'>
                                        <p style='margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 25.2px;'><strong><span style='font-size:16px;'>Wat heb je besteld:</span></strong></p>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class='row row-6' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
              <tbody>
                <tr>
                  <td>
                    <table class='row-content' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 680px;' width='680'>
                      <tbody>
                        <tr>
                          <td class='column column-1' width='33.333333333333336%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                            <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                              <tr>
                                <td class='pad' style='padding-bottom:10px;padding-left:30px;padding-right:10px;padding-top:10px;'>
                                  <div style='color:#101112;direction:ltr;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:12px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:14.399999999999999px;'>
                                    <p style='margin: 0;'><strong>Artikel</strong></p>
                                  </div>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td class='column column-2' width='33.333333333333336%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                            <table class='paragraph_block block-1' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                              <tr>
                                <td class='pad'>
                                  <div style='color:#101112;direction:ltr;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:12px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:14.399999999999999px;'>
                                    <p style='margin: 0;'><strong>Aantal</strong></p>
                                  </div>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td class='column column-3' width='33.333333333333336%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                            <table class='paragraph_block block-1' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                              <tr>
                                <td class='pad'>
                                  <div style='color:#101112;direction:ltr;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:12px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:14.399999999999999px;'>
                                    <p style='margin: 0;'><strong>prijs</strong></p>
                                  </div>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
              <table class='row row-7' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                <tbody>
                  <tr>
                    <td>
                      <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 680px;' width='680'>
                        <tbody> ". $article_lines ."
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class='row row-9' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                <tbody>
                  <tr>
                    <td>
                      <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 680px;' width='680'>
                        <tbody>
                          <tr>
                            <td class='column column-1' width='25%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-left: 10px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <div class='spacer_block block-1 mobile_hide' style='height:25px;line-height:25px;font-size:1px;'>&#8202;</div>
                            </td>
                            <td class='column column-2' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <table class='text_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                <tr>
                                  <td class='pad' style='padding-left:35px;padding-right:10px;padding-top:10px;'>
                                    <div style='font-family: sans-serif'>
                                      <div class style='font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #232323; line-height: 1.2;'>
                                        <p style='margin: 0; font-size: 14px; mso-line-height-alt: 16.8px;'><span style='font-size:18px;'>Order Totaal</span></p>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td class='column column-3' width='25%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <table class='text_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                <tr>
                                  <td class='pad' style='padding-left:30px;padding-right:10px;padding-top:10px;'>
                                    <div style='font-family: sans-serif'>
                                      <div class style='font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #000000; line-height: 1.2;'>
                                        <p style='margin: 0; font-size: 14px; mso-line-height-alt: 16.8px;'><span style='font-size:18px;'>&euro; ". $order_total ."</span></p>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class='row row-10' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                <tbody>
                  <tr>
                    <td>
                      <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px;' width='680'>
                        <tbody>
                          <tr>
                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <div class='spacer_block block-1' style='height:20px;line-height:20px;font-size:1px;'>&#8202;</div>
                              <table class='divider_block block-2' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                <tr>
                                  <td class='pad'>
                                    <div class='alignment' align='center'>
                                      <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                        <tr>
                                          <td class='divider_inner' style='font-size: 1px; line-height: 1px; border-top: 1px solid #BBBBBB;'><span>&#8202;</span></td>
                                        </tr>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              <div class='spacer_block block-3' style='height:20px;line-height:20px;font-size:1px;'>&#8202;</div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class='row row-11' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #508279;'>
                <tbody>
                  <tr>
                    <td>
                      <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px;' width='680'>
                        <tbody>
                          <tr>
                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <div class='spacer_block block-1 mobile_hide' style='height:20px;line-height:20px;font-size:1px;'>&#8202;</div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class='row row-12' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #508279;'>
                <tbody>
                  <tr>
                    <td>
                      <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px;' width='680'>
                        <tbody>
                          <tr>
                            <td class='column column-1' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <table class='text_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                <tr>
                                  <td class='pad' style='padding-bottom:10px;padding-left:30px;padding-right:10px;padding-top:10px;'>
                                    <div style='font-family: sans-serif'>
                                      <div class style='font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;'>
                                        <p style='margin: 0; font-size: 18px; text-align: left; mso-line-height-alt: 21.599999999999998px;'><strong><span style>Volg ons!</span></strong></p>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              <table class='text_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                <tr>
                                  <td class='pad' style='padding-bottom:10px;padding-left:30px;padding-right:10px;padding-top:10px;'>
                                    <div style='font-family: sans-serif'>
                                      <div class style='font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 18px; color: #ffffff; line-height: 1.5;'>
                                        <p style='margin: 0; font-size: 14px; text-align: left; mso-line-height-alt: 18px;'><span style='font-size:12px;'>Blijf up to date met nieuwe collecties en speciale aanbiedingen voor jou door ons te volgen op de volgende socials</span></p>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              <table class='social_block block-3' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                <tr>
                                  <td class='pad' style='padding-bottom:10px;padding-left:30px;text-align:left;padding-right:0px;'>
                                    <div class='alignment' align='left'>
                                      <table class='social-table' width='210px' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block;'>
                                        <tr>
                                          <td style='padding:0 10px 0 0;'><a href='https://www.facebook.com/' target='_blank'><img src='https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/facebook@2x.png' width='32' height='32' alt='Facebook' title='Facebook' style='display: block; height: auto; border: 0;'></a></td>
                                          <td style='padding:0 10px 0 0;'><a href='https://twitter.com/' target='_blank'><img src='https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/twitter@2x.png' width='32' height='32' alt='Twitter' title='Twitter' style='display: block; height: auto; border: 0;'></a></td>
                                          <td style='padding:0 10px 0 0;'><a href='https://instagram.com/' target='_blank'><img src='https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/instagram@2x.png' width='32' height='32' alt='Instagram' title='Instagram' style='display: block; height: auto; border: 0;'></a></td>
                                          <td style='padding:0 10px 0 0;'><a href='https://www.tiktok.com' target='_blank'><img src='https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/tiktok@2x.png' width='32' height='32' alt='TikTok' title='TikTok' style='display: block; height: auto; border: 0;'></a></td>
                                          <td style='padding:0 10px 0 0;'><a href='https://www.linkedin.com/' target='_blank'><img src='https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/linkedin@2x.png' width='32' height='32' alt='LinkedIn' title='LinkedIn' style='display: block; height: auto; border: 0;'></a></td>
                                        </tr>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td class='column column-2' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <table class='text_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                <tr>
                                  <td class='pad' style='padding-bottom:10px;padding-left:30px;padding-right:10px;padding-top:10px;'>
                                    <div style='font-family: sans-serif'>
                                      <div class style='font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;'>
                                        <p style='margin: 0; font-size: 18px; text-align: left; mso-line-height-alt: 21.599999999999998px;'><strong><span style>Service & contact</span></strong></p>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              <table class='text_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                <tr>
                                  <td class='pad' style='padding-bottom:10px;padding-left:30px;padding-right:10px;padding-top:10px;'>
                                    <div style='font-family: sans-serif'>
                                      <div class style='font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 18px; color: #ffffff; line-height: 1.5;'>
                                        <p style='margin: 0; mso-line-height-alt: 18px;'><span style='font-size:12px;'>www.greenhome.com</span></p>
                                        <p style='margin: 0; mso-line-height-alt: 18px;'><span style='font-size:12px;'>Hogeschoollaan 1</span></p>
                                        <p style='margin: 0; mso-line-height-alt: 18px;'><span style='font-size:12px;'>4818 CR, Breda</span></p>
                                        <p style='margin: 0; mso-line-height-alt: 18px;'><span style='font-size:12px;'>+316 123 456 78</span></p>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <table class='row row-13' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #508279;'>
                <tbody>
                  <tr>
                    <td>
                      <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px;' width='680'>
                        <tbody>
                          <tr>
                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                              <div class='spacer_block block-1' style='height:20px;line-height:20px;font-size:1px;'>&#8202;</div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table><!-- End -->
    </body>
    
    </html>";
  
  //Content
  $mail->isHTML(true);                                  //Set email format to HTML
  $mail->Subject = 'Jouw bestelling met nummer: ' . $order_id;
  $mail->Body    = $body;

  $mail->send();
  echo 'Message has been sent';

  echo '<script type="text/javascript">';
  echo 'document.addEventListener("DOMContentLoaded", function() {';
  echo '  document.getElementById("loading-overlay").style.display = "none";';
  echo '});';
  echo '</script>';
  
  // go to the order confirmation page
  header("Location: GUI_order_placed.php?order_id=$order_id");
  exit;

  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error:" . $mail->ErrorInfo;
  }



}

?>