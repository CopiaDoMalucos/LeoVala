<?php
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador" ||  $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Coord.Designer" || $CURUSER["level"]=="Designer" ){

   

	$res = mysql_query("SELECT COUNT(*) FROM users  LEFT JOIN torrents ON users.id = torrents.owner WHERE users.class != '50' AND  torrents.safe = 'no'  AND torrents.seeders > '0' ");
$row = mysql_fetch_array($res);
$count = $row[0];


begin_blockt("Funções Especiais");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">



</head>
<body>


<div id="ddsidemenubar2" class="markermenu">
<ul>
<?php
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador" ||  $CURUSER["level"]=="S.Moderador"  ){
?>
<li><a href="/app.php">Libera torrentes (<?php echo $count ;?>)</a></li>
<li><a href="/painel_torrent.php">Painel de controle</a></li>


<?php } if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="Moderador" ||  $CURUSER["level"]=="S.Moderador"){ ?>
<li class='myCaption'><B><center>Staff: </center></B></li>
<?php }?>
<?php if($CURUSER["level"]=="Administrador"  || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="S.Moderador" ){ ?>
<li><a href="admincp.php">Painel admin</a></li>
<?php }?>
<?php if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador"  || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="S.Moderador"){ ?>
<li><a href="modocp.php">Painel Mod</a></li>
<?php }?>
<?php if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Coord.Designer" || $CURUSER["level"]=="Designer" ){ ?>
<li class='myCaption'><B><center>Designer: </center></B></li>
<li><a href="designermanager.php">Painel Designer</a></li>
<?php }?>

</ul>
</div>

<script type="text/javascript">
ddlevelsmenu.setup("ddsidemenubar2", "sidebar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")
</script>




<ul id="minhaconta" class="ddsubmenustyle">
<li><a href="/account-details.php?id=<?php echo $CURUSER['id'] ;?>">Visualizar</a></li>
<li><a href="/account.php?action=edit_settings&do=edit">Modificar</a></li>
<li><a href="/torrents.php?cat=106">Torrentes baixados</a></li>
<li><a href="/lancados.php?id=<?php echo $CURUSER["id"] ;?>">Torrentes lançados</a></li>
<li><a href="/bookmark.php">Meus favoritos</a></li>
<li><a href="/comenttorrent.php">Comentários</a></li>
<li><a href="/forumhistorico.php">Postagem no Fórum</a></li>
<li><a href="/forum_fav.php">Fórum favoritos</a></li>
</ul>



</body></html>
<?php

end_blockt();
}
?>
