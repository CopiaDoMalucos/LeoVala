<?php
session_start();
$_SESSION = array();

include("simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();

?>
<!doctype html>
<html>
<body>
<table>
     <tr>
        <td width="45%"><?php echo ('<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA" />'); ?></p></td>
        <td width="4%">:</td>
  
		  <form action="" method="post">
			  <input name="captcha1" type="text">
			  <input type="hidden" name="code" value="<?=$_SESSION['captcha']['code'];?>">
			  <input type="submit" name="validar" value="Validar Captcha" /> 
		  </form>

     </tr>
</table>

<?php


	$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
	if(!empty($data['validar'])):
		echo $data['captcha1'];


		if($data['captcha1'] === $data['code']):
			echo 'Validado corretamente.';
		else:
			echo 'Erro ao validar.';
		endif;
	endif;
?>

</body>
</html>