<?php

############################################################
#######                                             ########
#######                                             ########
#######           Malucos-share.net 2.0             ########
#######                                             ########
#######                                             ########
############################################################


@error_reporting(0);

if($CURUSER)  {   ///////////////if user

begin_blockT("Métodos de doação");






///////////////////progress bar///////////////////////
$res9 = mysql_query("SELECT * FROM site_settings ") or sqlerr(__FILE__, __LINE__);
$arr9 = mysql_fetch_assoc($res9);
$mothlydonated = $arr9['donations'];
$requireddonations = $arr9['requireddonations'];


    if ($mothlydonated > 0 && $requireddonations ==0)
        $perc = 0;
    elseif ($mothlydonated > 0)
        $perc = number_format($mothlydonated / $requireddonations, 2) * 100;
    else
        $perc = 0;     

    
$donatein = $perc;

?>
<br/>
<table border=0 width=100% cellspacing=0 cellpadding=0>
    <td align="center"><center>
   

	  <font color="red">Vencimento todo dia 1</font></a></b></font><br>

      
      <table class=main border=0 width=100><tr><td style='padding: 0px; background-image: url(images/loadbarbg.gif); background-repeat: repeat-x'><?php if ($perc<= 1) {$pic = ""; $width = "100";} elseif ($perc<= 40) { $pic = "images/loadbarred.gif"; $width = $perc; } elseif ($perc<= 80) { $pic = "images/loadbaryellow.gif"; $width = $perc;  } else { $pic = "images/loadbargreen.gif"; $width = "100"; } echo "<img height=15 width=$width src=\"$pic\" alt='($donatein)%'><br><font size='1'><center>$perc%</center></font></td></tr></table>";?><b>Objetivo: <font color=red>R$ <?php print("$requireddonations") ;?></b></font><br><b>Doações: <font color=green>R$ <?php print("$mothlydonated") ;?></b></font><br /><b>Muito Obrigado</b><br /><br /></center></td>
</tr></table>
<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">



</head>
<body>


<div id="ddsidemenubar2" class="markermenu">
<ul>
<li><a href="/donate.php">Faça uma doação </a></li>
<li><a href="/faq.php#section69">Duvidas doações</a></li>
<li><a href="/doacao_confirma.php">Confirmar doação </a></li>
</ul>
</div>

<script type="text/javascript">
ddlevelsmenu.setup("ddsidemenubar2", "sidebar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")
</script>







</body></html>
<?php





end_block();


}//////////if user
?>

