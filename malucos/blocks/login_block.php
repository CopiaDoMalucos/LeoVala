<?php
if (!$site_config["MEMBERSONLY"] || $CURUSER) {

$sair = "<a class='adenisair' href=\"account-logout.php\">Sair</a>";
begin_blockt("Olá,".$CURUSER["username"]."!(".$sair.")");
$bonus = $CURUSER["seedbonus"];
  $avatar = htmlspecialchars($CURUSER["avatar"]);
  if (!$avatar)
    $avatar = "".$site_config["SITEURL"]."/images/default_avatar.gif";        
    print ("<center><img width=100height=100 src=$avatar></center>");
	  $invites = ($CURUSER["invites"]);

	
?>





<div id="ddsidemenubar9" class="markermenu">
<ul>
<li><a href="/account-details.php?id=<?php echo $CURUSER['id'] ;?>" rel="minhaconta1" class=""  class="rightarrowpointer">Minha conta</a></li>
<li><a href="/convites.php">Convites (<?php echo $invites ;?>)</a></li>
<li><a href="/seedingbonus.php">Ms bônus (<?php echo $bonus ;?>)</a></li>
<li><a href="/friend.php">Meus amigos</a></li>
</ul>
</div>

<script type="text/javascript">
ddlevelsmenu.setup("ddsidemenubar9", "sidebar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")
</script>




<ul id="minhaconta1" class="ddsubmenustyle">
<li><a href="/account-details.php?id=<?php echo $CURUSER['id'] ;?>">Visualizar</a></li>
<li><a href="/account.php?action=edit_settings&do=edit">Modificar</a></li>
<li><a href="/baixados.php?id=<?php echo $CURUSER["id"] ;?>">Torrentes baixados</a></li>
<li><a href="/lancados.php?id=<?php echo $CURUSER["id"] ;?>">Torrentes lançados</a></li>
<li><a href="/bookmark.php">Meus favoritos</a></li>
<li><a href="/comenttorrent.php">Comentários</a></li>
<li><a href="/forumhistorico.php">Postagem no Fórum</a></li>
<li><a href="/forum_fav.php">Fórum favoritos</a></li>
</ul>

<?php

end_blockt();
}
?>
