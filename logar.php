<?php
session_start();
if(isset($_POST['id'])){
	$_SESSION['id'] = (int)$_POST['id'];
	header('Location: index.php');
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Logar</title>
</head>
<body>
<form name="logar" method="post">
    <label>Id:</label>
    <input type="text" name="id" />
    <input type="submit" value="Logar">
</form>
</body>
</html>