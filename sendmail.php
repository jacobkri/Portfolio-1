<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 400 BAD REQUEST");
  echo '<!doctype html><html lang="en"><head><title>Bad Request</title></head><body><h1>Illigal request</h1><p>The request was formed incorrectly</p></body></html>';exit();
}

if ((!isset($_POST['name'])) || (!isset($_POST['email'])) || (!isset($_POST['msg_src']))){
    header('HTTP/1.0 403 Forbidden');
    $msg = '<!doctype html><html lang="da"><head><title>Indhold mangler</title></head><body><h1>Indhold mangler</h1><p>Alle felter skal udfyldes!</p></body></html>';exit();
} else {
  $error = false;
  if(!preg_match('/^[^<>]{10,3000}$/', $_POST['msg_src'])) {
    header('HTTP/1.0 403 Forbidden');
    $msg = 'Din besked må max være 3000 karaktere lang.';
    $error = true;
  } else {$message = $_POST['msg_src'];}
  if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    header('HTTP/1.0 403 Forbidden');
    $msg = 'Ugyldig e-mailadresse.';
    $error = true;
  } else {$email = $_POST['email'];}
  if(!preg_match('/^[a-zA-ZæøåÆØÅ ]{1,32}$/', $_POST['name'])) {
    header('HTTP/1.0 403 Forbidden');
    $msg = 'Dit navn må kun bestå af <i>[a-zA-ZæøåÆØÅ ]</i>, og skal være mellem 1-32 karaktere langt.';
    $error = true;
  } else {$name = $_POST['name'];}
  if (isset($_POST['subject'])) {
    if(!preg_match('/^[a-zA-ZæøåÆØÅ0-9, -]{0,64}$/', $_POST['subject'])) {
      header('HTTP/1.0 403 Forbidden');
      $msg = 'Emnet for beskeden må kun indeholde <i>[a-zA-ZæøåÆØÅ0-9,- ]</i>, og skal være mellem 0-64 karaktere langt.</i>';
      $error = true;
    } else {$subject = $_POST['subject'];}
  } else {$subject='';}
  if ($error !== true) { // If no validation errors, do this:
    require $_SERVER["DOCUMENT_ROOT"] . '/lib/mailer_class.php';

    $mailer = new mailer_class();

    $mailer->from_email = 'beamticsupport@beamtic.com'; // Afsenders e-mail adresse
    $mailer->from_name = $name . ' - beamtic.com';
    $message = '<blockquote style="background:rgb(245,245,245);margin:1em;padding:1em;">' . $message . '</blockquote>';
    $message = '<p><b>Besked fra: </b><i>'.$name.'</i></p>' . $message;
    $message .= '<p><b>Angivet kontakt e-mail: </b><i>'.$email.'</i></p>';
    $message .= '<p style="font-style:italic;font-size:0.9em;">Beskeden blev sendt fra <a href="//kea.beamtic.com/">kea.beamtic.dk</a></p>';
    $recipients = array();

    $recipients['Jacob Kristensen'] = 'jacobkweb@gmail.com'; // Modtagers e-mail adresse
    // $recipients['second_name'] = 'second_recipient@example.com';

    $mailer->send_email($msg_subject=$subject, $message, $recipients);

    $msg = 'Din besked blev sendt.';
  }
}
header("Cache-Control: no cache");
?><!DOCTYPE html>
<html lang="da">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="icon" href="img/icon_3.png">
    <title>Kontakt</title>
</head>

<body>
  <article>
   <h1>Kontakt</h1>
   
   <div id="messageSent">
     <p><?php echo $msg; ?></p>
   </div>
   
  </article>
</body>

</html>



