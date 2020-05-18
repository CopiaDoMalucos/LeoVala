<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn(true);

loggedinonly();  
stdhead(T_("HOME"));







if ( $CURUSER["id"] ==1 || $CURUSER["id"] ==28023) {

// legenda
if (!$site_config["MEMBERSONLY"] || $CURUSER) {
begin_framec(T_("LEGENDA_CLASSES"));
echo '<center style="background-color:#FFFFFF;" >


<font color="#FFFFFF">&nbsp;&nbsp;</font>
<font color="#FF0000"><B>Sysop</B></font><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#FF0000"><B>Administrador</B></font><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#8B1A1A"><B>Super Moderador</B></font><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#000000"><B>Moderador</B></font><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#800080"><B>Colaborador</B></font><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#16ADAD"><B>Liberador de Torrents</B></font><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#FFD700"><B>Coord.Designer</B></font><font color="FFFFFF">&nbsp;|&nbsp;</font>
<font color="#0000CD"><B>Designer</B></font><font color="FFFFFF">&nbsp;|&nbsp;</font>
<font color="#B8860B"><B>Moderador de Grupo</B></font><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#56690B"><B>Sub Moderador de Grupo</B></font><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#B8860B"><B>Membro de Grupo</B></font><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#B8860B"><B>Uploader</B></font><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#0000FF"><B>VIP Ouro</B></font>&nbsp;<img src="images/legenda/estrelagold.png" border="0" alt=""><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#0000FF"><B>VIP Prata</B></font>&nbsp;<img src="images/legenda/estrelaprata.png" border="0" alt=""><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#0000FF"><B>VIP Bronze</B></font>&nbsp;<img src="images/legenda/browserPreview.png" border="0" alt=""><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#0000FF"><B>VIP</B></font>&nbsp;<img src="images/legenda/estrelavipcomum.png" border="0" alt=""><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#2F4F4F"><B>Super User</B></B></font><font color="FFFFFF">&nbsp;|&nbsp;</font>
<font color="#0000FF"><B>Usuário</B></font><font color="#FFFFFF">&nbsp;|&nbsp;</font>
<font color="#0000FF"><B>Advertido</B></font>&nbsp;<img src="images/legenda/advertido.png" border="0" alt="">
<font color="#0000FF">&nbsp;&nbsp;</font>
<br><br>

</center>
';
end_framec();
}



//if(isset($CURUSER) && $CURUSER[id]>0){
//USERS ONLINE






$members2 = number_format(get_row_count("users", "WHERE UNIX_TIMESTAMP('" . get_dt_num() . "') - UNIX_TIMESTAMP(last_access) < 999"));
begin_framec(" " . $members2. " Online");
$resew = mysql_query("SELECT id, username, class, donated, warned, dj FROM users WHERE UNIX_TIMESTAMP(" . get_dt_num() . ") - UNIX_TIMESTAMP(last_access) < 999  ORDER BY class  DESC, warned ");
while ($arr = mysql_fetch_assoc($resew))
{
    if ($todayactive)
        $todayactive .= ", ";
    switch ($arr["class"])
    {
	case 100:
      $arr["username"] = "<font color=#FF0000>" . $arr["username"] . "</font>";
      break;
	case 95:
      $arr["username"] = "<font color=#FF0000>" . $arr["username"] . "</font>";
      break;
	case 86:
      $arr["username"] = "<font color=#8b1a1a>" . $arr["username"] . "</font>";
      break;
    case 90:
      $arr["username"] = "<font color=#800000>" . $arr["username"] . "</font>";
      break;        
    case 85:
      $arr["username"] = "<font color=#000000>" . $arr["username"] . "</font>";
      break;      
    case 80:
      $arr["username"] = "<font color=#800080>" . $arr["username"] . "</font>";
      break;
    case 71:
      $arr["username"] = "<font color=#ffd700>" . $arr["username"] . "</font>";
      break;
	case 75:
      $arr["username"] = "<font color=#16ADAD>" . $arr["username"] . "</font>";
      break;
	case 70:
      $arr["username"] = "<font color=#0000CD>" . $arr["username"] . "</font>";
      break;
	case 65:
      $arr["username"] = "<font color=#ffd700>" . $arr["username"] . "</font>";
      break;
	case 60:
      $arr["username"] = "<font color=#B8860B>" . $arr["username"] . "</font>";
      break;
    case 55:
      $arr["username"] = "<font color=#0000FF>" . $arr["username"] . "</font>";
      break;
    case 50:
      $arr["username"] = "<font color=#B8860B>" . $arr["username"] . "</font>";
      break;
    case 45:
      $arr["username"] = "<font color=#0000FF>" . $arr["username"] . "</font>";
      break;
    case 40:
      $arr["username"] = "<font color=#0000FF>" . $arr["username"] . "</font>";
      break;
    case 35:
      $arr["username"] = "<font color=#0000FF>" . $arr["username"] . "</font>";
      break;    
    case 30:
      $arr["username"] = "<font color=#0000FF>" . $arr["username"] . "</font>";
      break; 
    case 25:
      $arr["username"] = "<font color=#0000FF>" . $arr["username"] . "</font>";
      break; 
    case 1:
      $arr["username"] = "<font color=#0000FF>" . $arr["username"] . "</font>";
      break;       
    }
         
       $donator = $arr["donated"] > 0;
    if ($CURUSER) {
        $todayactive .= "<a href=account-details.php?id=" . $arr["id"] . ">" . $arr["username"] . "</a></a>";
    } else {
        $todayactive .= "<a href=account-details.php?id=" . $arr["id"] . ">" . $arr["username"] . "</a></a>";
    }
    $warned = $arr["warned"] == "yes";
    if ($warned) {
        $todayactive .= "<img src=\"images/warned.gif\">";
    }
    $dj = $arr["dj"] == "yes";
    if ($dj) {
        $todayactive .= "<img src=\"images/dj.png\">";
    }
    $usersactivetoday++;

         }
      echo "<div style='background-color:#FFFFFF;'  align='left'><b>" . $todayactive . "</b></div>";
end_framec();

}
$members21 = number_format(get_row_count("users", "WHERE UNIX_TIMESTAMP('" . get_dt_num() . "') - UNIX_TIMESTAMP(last_access) < 172800"));
begin_framec(" " . $members21. " (Online)");
$resew1 = mysql_query("SELECT id, username, class, donated, warned, dj FROM users WHERE UNIX_TIMESTAMP(" . get_dt_num() . ") - UNIX_TIMESTAMP(last_access) < 172800 ORDER BY class  DESC, warned ");
while ($arr1 = mysql_fetch_assoc($resew1))
{
    if ($todayactive1)
        $todayactive1 .= ", ";
    switch ($arr1["class"])
    {
	case 100:
      $arr1["username"] = "<font color=#FF0000>" . $arr1["username"] . "</font>";
      break;
	case 95:
      $arr1["username"] = "<font color=#FF0000>" . $arr1["username"] . "</font>";
      break;  
	case 86:
      $arr1["username"] = "<font color=#8b1a1a>" . $arr1["username"] . "</font>";
      break;   
    case 90:
      $arr1["username"] = "<font color=#800000>" . $arr1["username"] . "</font>";
      break;        
    case 85:
      $arr1["username"] = "<font color=#000000>" . $arr1["username"] . "</font>";
      break;      
    case 80:
      $arr1["username"] = "<font color=#800080>" . $arr1["username"] . "</font>";
      break;
    case 75:
      $arr1["username"] = "<font color=#16ADAD>" . $arr1["username"] . "</font>";
      break;
	case 71:
      $arr1["username"] = "<font color=#ffd700>" . $arr1["username"] . "</font>";
      break;  
	case 70:
      $arr1["username"] = "<font color=#0000CD>" . $arr1["username"] . "</font>";
      break;
	case 65:
      $arr["username"] = "<font color=#ffd700>" . $arr1["username"] . "</font>";
      break;
	case 60:
      $arr1["username"] = "<font color=#B8860B>" . $arr1["username"] . "</font>";
      break;
    case 55:
      $arr1["username"] = "<font color=#0000FF>" . $arr1["username"] . "</font>";
      break;
    case 50:
      $arr1["username"] = "<font color=#B8860B>" . $arr1["username"] . "</font>";
      break;
    case 45:
      $arr1["username"] = "<font color=#0000FF>" . $arr1["username"] . "</font>";
      break;
    case 40:
      $arr1["username"] = "<font color=#0000FF>" . $arr1["username"] . "</font>";
      break;
    case 35:
      $arr1["username"] = "<font color=#0000FF>" . $arr1["username"] . "</font>";
      break;    
    case 30:
      $arr["username"] = "<font color=#0000FF>" . $arr1["username"] . "</font>";
      break; 
    case 25:
      $arr1["username"] = "<font color=#0000FF>" . $arr1["username"] . "</font>";
      break; 
    case 1:
      $arr1["username"] = "<font color=#0000FF>" . $arr1["username"] . "</font>";
      break;       
    }
         
       $donator1 = $arr1["donated"] > 0;
    if ($CURUSER) {
        $todayactive1 .= "<a href=account-details.php?id=" . $arr1["id"] . ">" . $arr1["username"] . "</a></a>";
    } else {
        $todayactive1 .= "<a href=account-details.php?id=" . $arr1["id"] . ">" . $arr1["username"] . "</a></a>";
    }
    $warned1 = $arr1["warned"] == "yes";
    if ($warned1) {
        $todayactive1 .= "<img src=\"images/warned.gif\">";
    }
    $dj1 = $arr1["dj"] == "yes";
    if ($dj1) {
        $todayactive1 .= "<img src=\"images/dj.png\">";
    }
    $usersactivetoday1++;

         }
      echo "<div style='background-color:#FFFFFF;'  align='left'><b>" . $todayactive1 . "</b></div>";

end_framec();






stdfoot();
?>