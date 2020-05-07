<?php
require("backend/functions.php");
dbconn(false);

stdhead("Preview");
?>
<script language="javascript" type="text/javascript" src="js/checkbb.js"></script>
<?php
begin_framec("Preview");
?>
<center><BR><form name="formu">
<b>Stick your text here</b>  :<br /><br />
<textarea name="descr" id="descr" rows="27" cols="77"></textarea><br />
<input type="button" value="Preview" onClick="visualisation()"><input type="button" value="select all" onClick="javascript:this.form.descr.focus();this.form.descr.select();">
<input name="auto" type="checkbox" onClick="automatique()"> Auto
</form><br><br>
<span id="previsualisation">&nbsp;</span></center>
<?php
end_framec();
stdfoot();
?>