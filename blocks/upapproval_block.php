
<?php
if ($CURUSER["level"]=="Uploader" ) {
	$uploader = "AND torrents.owner = '".$CURUSER["id"]."'";
$approves = mysql_num_rows(mysql_query("select name from torrents where  safe='no' ".$uploader." "));
if ($approves > 0) {
begin_blockt("Torrents Aprovação  ");
	?> 
	Olá <b><?php echo $CURUSER["username"];?></b>,<br>
	Há <?php echo $approves;?> Torrentes para ser Aprovado.<br><br>
	<center><a href="aprovar.php"><blink><b>Aprovar!</b></blink></b></a></center>
	
	<?php
	

end_blockt();
}
}
?>