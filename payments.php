<?php
ob_start();

// Your website settings begins here

$payment_mode = "Live"; // "Live" or "Test"

$user_or_payer_email_address = "info@vasplus.info"; // This is the email address of your user that is about to make a payment

$paypal_email = "vasplusblog@yahoo.com";  // This is your paypal email address
$your_website_logo_url = "http://vasplus.info/logo/logo_paypal.png";

$item_name = 'PayPal Integration Demo';	// This is the name of the item that a user is about to pay for
$item_amount = 1.00; // This is the amount charged for the item that a user is about to pay for
$item_number = date('d-m-y').'-'.rand(34560,98721);	// This is the unigue number that identifies the item that a user is about to pay for
$currency_code = 'USD';	// The currency that you wish to accept on your site

$no_note = '1';	// 1= Yes, 0 = No
$cmd = '_xclick';


// This is the URL to the notification page on your website
$vpb_notification_url = 'http://'.str_replace(basename($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]), '', $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]).'paypal_notifications.php';



/*===========================    You do not need to modify the below codes unless you know what you are doing   ==============================*/

// For data security
class vpb_rc4_algorithm
{
   public function vpb_encodes($a,$b){
      for($i,$c;$i<256;$i++)$c[$i]=$i;
      for($i=0,$d,$e,$g=strlen($a);$i<256;$i++){
         $d=($d+$c[$i]+ord($a[$i%$g]))%256;
         $e=$c[$i];
         $c[$i]=$c[$d];
         $c[$d]=$e;
      }
      for($y,$i,$d=0,$f;$y<strlen($b);$y++){
         $i=($i+1)%256;
         $d=($d+$c[$i])%256;
         $e=$c[$i];
         $c[$i]=$c[$d];
         $c[$d]=$e;
         $f.=chr(ord($b[$y])^$c[($c[$i]+$c[$d])%256]);
      }
      return $f;
   }
   public function vpb_decodes($a,$b){return vpb_rc4_algorithm::vpb_encodes($a,$b);}
}
function vpb_encrpt_url_data($data)
{
	$key  = 'a7e88837b63bf2941ef819dc8ca282';
	$plain_text = vpb_rc4_algorithm::vpb_encodes($key,$data);
	return base64_encode($plain_text);
}

// Payment is completed successfully. This is the success page URL
$return_url = $vpb_notification_url.'?crypt='.vpb_encrpt_url_data('user-paid-successfully;'.$user_or_payer_email_address);

// Payment was canceled by the user. This is the cancel page URL
$cancel_url = $vpb_notification_url.'?crypt='.vpb_encrpt_url_data('user-canceled-payment;'.$user_or_payer_email_address);

// There was a problem with the payment. This is the notification page URL
$notify_url = $vpb_notification_url.'?crypt='.vpb_encrpt_url_data('payment-notification-brought;'.$user_or_payer_email_address);

$tax = 0;
$custom = $_SERVER['REMOTE_ADDR'];

$vpb_paypal_url_data = "?business=".urlencode($paypal_email)."&";	
$vpb_paypal_url_data .= "item_name=".urlencode($item_name)."&";
$vpb_paypal_url_data .= "amount=".urlencode($item_amount)."&";
$vpb_paypal_url_data .= "item_number=".urlencode($item_number)."&";
$vpb_paypal_url_data .= "cpp_header_image=".urlencode($your_website_logo_url)."&";
$vpb_paypal_url_data .= "tax=".urlencode($tax)."&";
$vpb_paypal_url_data .= "custom=".urlencode($custom)."&";
$vpb_paypal_url_data .= "currency_code=".urlencode($currency_code)."&";
$vpb_paypal_url_data .= "no_note=".urlencode($no_note)."&";
$vpb_paypal_url_data .= "cmd=".urlencode($cmd)."&";

foreach($_POST as $key => $value){
	$value = urlencode(stripslashes($value));
	$vpb_paypal_url_data .= "$key=$value&";
}

$vpb_paypal_url_data .= "return=".urlencode(stripslashes($return_url))."&";
$vpb_paypal_url_data .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
$vpb_paypal_url_data .= "notify_url=".urlencode($notify_url);

if($payment_mode == "Live")
{
	header('location: https://www.paypal.com/cgi-bin/webscr'.$vpb_paypal_url_data);
	exit();
}
else
{
	header('location: https://www.sandbox.paypal.com/cgi-bin/webscr'.$vpb_paypal_url_data);
	exit();
}
?>