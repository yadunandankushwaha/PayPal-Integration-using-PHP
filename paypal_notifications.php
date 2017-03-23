<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Notification | PayPal Integration using PHP</title>
</head>

<body>
<center>
<div style="font-family: helvetica, arial, 'lucida grande', sans-serif; font-size:12px; color:black; line-height:23px; width:650px;" align="center">


<?php
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
function vpb_decrpt_url_data($data)
{
	$key  = '';
	$v_data = base64_decode($data);
	$plain_text = vpb_rc4_algorithm::vpb_decodes($key,$v_data);
	return $plain_text;
}


if(isset($_GET["crypt"]) && !empty($_GET["crypt"]))
{
	$crypted_data = vpb_decrpt_url_data(strip_tags($_GET["crypt"]));
	list($payment_status, $user_email) = explode(';', $crypted_data, 2);
	
	if($payment_status == "user-paid-successfully")
	{
		// You can perform an activation or any relevant action here for a successful transaction using the user email address: $user_email
		
		echo 'You have made payment successfully and your email address as set in the script is <b>'.$user_email.'</b>.<br />Thank you for the payment.';
	}
	elseif($payment_status == "user-canceled-payment")
	{
		echo 'You seem to have canceled your payment process and so, no payment has been made yet and your email address as set in the script is <b>'.$user_email.'</b>.<br />Please <span class="ccc"><a href="index.php">click here</a></span> to try again.<br />Thank you.';
	}
    else
	{
		echo "Sorry, there was an error with the payment notification and so, the process has been terminated.<br />If you feel that something is wrong, please do not hesitate to contact us.<br />Thank you!";
	}
}
else
{
    echo "Sorry, there was an error with the payment notification and so, the process has been terminated.<br />If you feel that something is wrong, please do not hesitate to contact us.<br />Thank you!";
}

?>


</div>
</center>
</body>
</html>