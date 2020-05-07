<?php
/* ////////////////////////////////////////////////////////////////////
  [TT All] Receiver choice from list in new PM - by r@lphii
/////////////////////////////////////////////////////////////////////*/

require "backend/functions.php";
dbconn();
loggedinonly();

if (isset($_GET['action']) && $_GET['action'])
            $action=$_GET['action'];
else $action = '';;

if ($action!="find")
   {
?>

<form action="userfind_to_nick.php?action=find" name="users" method="post">
<div vlign="bottom">
  <table cellspacing=0 cellpadding=0 border=0 align=left width=100% bgcolor="#dddddd">
  <tr>
     <td class=col1 align=center><?php echo "<b>Pseudo</b>";?>:</td>
     <td class=col1 align=center><input type="text" name="user" size="30" /></td>
     <td class=col2 align=center><input type="submit" name="confirm" value="Search" /></td>
  </tr>
  </table>
</div>
</form>
<?php
}
else
{
  $res=mysql_query("SELECT username FROM users WHERE username LIKE '%".mysql_escape_string($_POST["user"])."%' ORDER BY username");
  if (!$res or mysql_num_rows($res)==0)
      {
?>
<table cellspacing=0 cellpadding=0 border=0 align=left width=100% bgcolor="#dddddd">
<tr><td><p align=center>Found <font color=red><b>0</b></font> members. Try Again.<br />
<a href=userfind_to_pm.php><b>[Retry]</b></a></p></td></tr></table>
<?php	}
  else {
	$subres = mysql_query("SELECT COUNT(*) FROM users WHERE username LIKE '%".mysql_escape_string($_POST["user"])."%'");
	$subrow = mysql_fetch_array($subres);
	$count = $subrow[0];

if ($count == "1"){?>
<p align=center>Found <b>1</b> member</p>
<?php }elseif ($count >= "500"){?>
<p align=center>Found <b> <?php echo $count ;?></b> members. Try to be more specific.</p>
<?php }else{?>
<p align=center>Found <b><?php echo $count ;?></b> members - Choose Recip.</p>
<?php }?>
<div align="center">
  <form name=result>
<table bgcolor="dddddd" align=left width=100% cellspacing=0 cellpadding=0 border=0>
  <tr>
     <td align=center>
<?php
print("<b>Pseudo</b>");
?>
:</td>
<?php
     print("<td align=center><select name=\"name\" size=\"1\">");
     while($result=mysql_fetch_array($res))
     print("<option name=uname value=" . $result["username"]. " />" . $result["username"] . "</option>");
     print("</select></td>");
?>
<script language=javascript>

function addusertopm(){
    window.opener.document.forms['edit'].elements['receiver'].value = document.forms['result'].elements['name'].options[document.forms['result'].elements['name'].options.selectedIndex].value;
    window.close();
}
</script>
<?php
     print("<td><input type=\"button\" name=\"confirm\" onclick=\"javascript:addusertopm()\" value=\"Choisir Destinataire\"></td>");
?>
  </tr>
</table>
</form>
</div>
<?php
   }
}
?>