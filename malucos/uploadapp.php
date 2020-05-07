<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################

require_once("backend/functions.php");
dbconn(false);
loggedinonly();
stdhead("Upload Application");
//require_once("backend/admin-functions.php");

?>
<STYLE>
.popup
{
CURSOR: help;
TEXT-DECORATION: none
}
</STYLE><?php

if ($_POST["form"]=="") {
 if($CURUSER["class"] < 5)
   $CURUSER["uploadpos"] == 'no'?0:2;
else
  $form=10;
} else
$form=$_POST["form"];
if($form==0) {
 $res=mysql_query("SELECT * FROM uploadapp WHERE userid=".$CURUSER["id"]) or die (mysql_error());
 if(mysql_num_rows($res)) {
  $row=mysql_fetch_array($res);
  $form=4;
 }
}

$debug=0;
$upreq = 10;
$upreqn = $upreq * 1073741824;


if($debug) {
begin_framec("<font color=2fdceb>Debug Box</font>");
print("<table>");
 print("<form action=\"uploadapp.php\" method=\"post\" enctype=\"multipart/form-data\" name=\"debug\" id=\"uploadapp\">");
tr("User Class","&nbsp;&nbsp;".get_user_class_name($CURUSER["class"]),1);
tr("Variables",
 "form = " . $form. "<br>".
 "user = " . $_POST["user"]. "<br>".
 "groupacct = " . $_POST["groupacct"]. "<br>".
 "grouname = " . unesc($_POST["groupname"]). "<br>".
 "groupdes = " . unesc($_POST["groupdes"]). "<br>".
 "joined = " . unesc($_POST["joined"]). "<br>".
 "ratio = " . unesc($_POST["ratio"]). "<br>".
 "upk =". unesc($_POST["upk"]). "<br>".
 "rbseed = ".unesc($_POST["rbseed"]).
  "<br>rbrelease = ".unesc($_POST["rbrelease"]).
  "<br>rbstime = ".unesc($_POST["rbstime"]) . "<br>".
 "plans =". unesc($_POST["plans"]). "<br>".
 "comment =". unesc($_POST["comment"]). "<br>".
 "",1);
 tr("View forms","<input type=\"radio\" name=\"form\" value=\"0\" ". ($form==0?"checked":""). ">Upload App".
 "<input name=\"form\" type=\"radio\" value=\"3\" ".($form==3?"checked":"").">Moderator+ Page".
 "<input type=\"submit\" name=\"SubmitD\" value=\"Change Forms\">",1);
 print("</table> </form>");

end_framec();
}
if($form>=10 && $CURUSER["class"]<5) {
show_error_msg("Error!","Invalid Request!",1);
} else if($form==0) {
begin_framec("<font color=2fdceb>Pedido para ser Upload</font>");
?>
<Center><BR><BR><b>Por favor, utilize o formulário abaixo para solicitar o direito de enviar torrents para este tracker<br>Após ter enviado o pedido favor aguarda o voto do pessoal.<br><BR>Você receberá um PM, uma vez que a votação for concluída.<BR><BR></b></center>
 <form action="uploadapp.php" method="post" enctype="multipart/form-data" name="uploadapp" id="uploadapp">
 <center><table border=1 style='border-collapse: collapse' bordercolor=#646262 cellpadding=4>
<?php

if ($CURUSER["downloaded"] > 0)
$ratio = $CURUSER['uploaded'] / $CURUSER['downloaded'];
else if ($CURUSER["uploaded"] > 0)
$ratio = 1;
else
$ratio = 0;

tr("Usuário","&nbsp;&nbsp;<input name=\"user\" type=\"hidden\" value=\"". $CURUSER['id']."\">".$CURUSER['username'],1);
//tr("Is this a Group Account?","<input type=\"radio\" name=\"groupacct\" value=\"1\">Yes".
// "<input name=\groupacct\" type=\"radio\" value=\"0\" checked>No",1);
//tr("","<h2><center>For Group Applications only</center></h2>",1);
//tr("Group Name","<input name=\"groupname\" type=\"text\" id=\"groupname\" size=\"50\" maxlength=\"50\">",1);
//tr("Group ID<br>(3 char designator)","<input name=\"groupdes\" type=\"text\" id=\"groupdes\" size=\"7\" maxlength=\"3\">",1);
//tr("","<h2><center>For All Applicants</center></h2>",1);
tr("Data","&nbsp;&nbsp;<input name=\"joined\" type=\"hidden\" value=\"".$CURUSER['added']."\">".$CURUSER['added'],1);
tr("Meu Ratio é igual ou superior 1.0","&nbsp;&nbsp;<input name=\"ratio\" type=\"hidden\" value=\"".($ratio>=1?"ok":"not ok")."\">".($ratio>=1?"Sim":"Não"),1);
$upreqm=$CURUSER['uploaded']>=$upreqn;
tr("I atender ou exceder ". $upreq ."GB carregado de transferência","&nbsp;&nbsp;<input name=\"upk\" type=\"hidden\" 
value=\"".($upreqm?"yes":"no")."\">".($upreqm?"Sim":"Não"),1);
tr("Conteúdo que estou pensando em fazer upload<br>(não se restringe ao)","<textarea name=\"plans\" cols=\"50\" rows=\"2\" wrap=\"VIRTUAL\"></textarea>",1);
tr("Por que eu deveria ser dado acesso pra ser upload","<textarea name=\"comment\" cols=\"50\" rows=\"4\" wrap=\"VIRTUAL\"></textarea>",1);
?>
</table></center>
<p>Eu sei como deixar como sementes (incluindo a criação de arquivos torrent) torrents?<br>
<input type="radio" name="rbseed" value="1">
 Sim<br>
 <input name="rbseed" type="radio" value="0" checked>
Não</p>        
<p>Eu entendo que eu não estou autorizado a enviar releases proibido, ou libera outro grupo que não são oficialmente permitidos no tracker.<br>
<input type="radio" name="rbrelease" value="1">
 Sim<br>
 <input name="rbrelease" type="radio" value="0" checked>
Não</p>
<p>Eu entendo que eu tenho que deixar o torrent de semente para torrents com pelo menos 24 horas, ou pelo menos, duas outras se tornaram leechers seeders.<br>
<input type="radio" name="rbstime" value="1">
Sim<br>
<input name="rbstime" type="radio" value="0" checked>
Não</p>
<br>
<input name="form" type="hidden" value="1">
<center><input type="submit" name="Submit" value="Send Application"><center>
</form><br><BR>
<?php
end_framec();

} else if ($form==1) {

begin_framec("<font color=2fdceb>Pedido Uploaders Aplicação</font>");
$qry="INSERT INTO uploadapp (userid,applied,grpacct,grpname,grpdes,content,comment,seeding,othergrps,seedtime) ".
 "VALUES (". $_POST["user"].", ".
  implode(",",array_map("sqlesc",array(
    get_date_time(),
   $_POST["groupacct"],
   $_POST["groupname"],
   $_POST["groupdes"],
   $_POST["plans"],
   $_POST["comment"],
    $_POST["rbseed"],
   $_POST["rbrelease"],
   $_POST["rbstime"]))).")";
$ret=mysql_query($qry);
$dt = sqlesc(get_date_time());
$res50=mysql_query("SELECT users.id, users.username FROM users WHERE users.class>='6'");
$admin50=mysql_result($res50, 0);
mysql_query("INSERT INTO messages (poster, sender, receiver, added, msg) VALUES(0, 0, $admin50, $dt, 'Um [url=".$site_config["SITEURL"]."/uploadapp.php][color=red]Pedido Uploader[/color][/url] está à espera de aprovação')") or die(mysql_error());
if (!$ret) {
  if (mysql_errno() == 1062)
   print("Application already on file<br>");
  else
  print("mysql puked: ".mysql_error());
} else
 print("<center><h2>Seu pedido foi enviado com sucesso para o conselho de revisão</h2><br><BR>Você pode verificar para trás em seu progresso de voto a qualquer momento, seguindo este link novamente.<br></center>");
 

end_framec();
} else if($form==2) {
begin_framec("<font color=2fdceb>Uploaders Application Request</font>");
print("<h2>You already have upload capabilities</h2>");
end_framec();
} else if($form==4) {
begin_framec("<font color=2fdceb>Seu Pedido de Uploaders</font>");
  $votesyes=$votesno==0;
   if($row["votes"]!="") {
    $votes=explode(" ",$row["votes"]);
    for($i=0;$i<count($votes);$i++)
    {
     $votei=explode(":",$votes[$i]);
     $votei[1]?$votesyes++:$votesno++;
    }
   }
   print("Pedido Upload ainda está em revisão:<br><BR><b>Atual status de votação:</b> Sim = ".$votesyes." &nbsp;&nbsp;Não = ".$votesno);
   print("<br>As pesquisa está ".($row["active"]=="0"?"Fechada":"Abertar").".");
end_framec();
} else if($form>=10) {
begin_framec("<font color=2fdceb>Uploaders cabine de votação</font>");
//adminmenu();
if($form==11) {
 $res=mysql_query("SELECT * FROM uploadapp WHERE id=".$_POST["pollid"]) or die (mysql_error());
 $row=mysql_fetch_array($res);
  $votesyes=$votesno=$voted=0;
   if($row["votes"]!="") {
    $votes=explode(" ",$row["votes"]);
    for($i=0;$i<count($votes);$i++)
    {
     $votei=explode(":",$votes[$i]);
     if($CURUSER["id"]==$votei[0]) $voted++;
     $votei[1]?$votesyes++:$votesno++;
    }
   }
 if($_POST["ballet"] && $voted==0) {
  $votes=($row["votes"]!=""?$row["votes"]." ":"").implode(":",array($CURUSER["id"],$_POST["ballet"]=="Yes"?1:0));
  mysql_query("UPDATE uploadapp SET votes='".$votes."' WHERE id=".$_POST["pollid"]);
  print("Voto para ".$_POST["pollid"]." recebeu (".$_POST["ballet"].")<br>");
 } else if($_POST["closepoll"]) {
   print("Pedido de Pesquisa para fechar ".$_POST["pollid"]." recebeu<br>");
   if(count($votes)<1) {
     print("Pedido negado, exige 5 votos para fechar");
   } else {
    mysql_query("UPDATE uploadapp SET active='0' WHERE id=".$_POST["pollid"]);
    $tvotes=$votesyes+$votesno;
    $votea=$votesyes>$votesno;
    $modcomment = gmdate("Y-m-d") . " - Upload Application: ".($votea?"Accepted":"Denied")." (Yes = ".$votesyes." No = ".$votesno." (".
     number_format((($votea?$votesyes:$votesno)/$tvotes)*100,3)."%)";
    print($modcomment."<br>");
    if($votea) {
     $mq="UPDATE users SET uploadpos='yes',class='4',modcomment=CONCAT(modcomment,".sqlesc($modcomment."\n").") WHERE id=".$row["userid"];
     mysql_query($mq);
     print("Updating User Records...<br>");
    $dt = sqlesc(get_date_time());
    $msg = sqlesc("Congrats, You have been accepted as a new Uploader!.\n");
    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, ".$row["userid"].", $dt, $msg, 0)") or die (mysql_error());
    } else {
     $mq="UPDATE users SET modcomment=CONCAT(modcomment,".sqlesc($modcomment."\n").") WHERE id=".$row["userid"];
     mysql_query($mq);
    $dt = sqlesc(get_date_time());
    $msg = sqlesc("sorry, You have been denied as a new Uploader.\n");
    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, ".$row["userid"].", $dt, $msg, 0)") or die (mysql_error());
    }
  }
 } else if($_POST["removepoll"]) {
  mysql_query("DELETE FROM uploadapp where id=".$_POST["pollid"]);
  print("Poll ".$_POST["pollid"]." removed from database");
 } else if($_POST["addcomment"]) {
  print ("Comment: ".sqlesc($_POST["newcomments"])."<br>");
   if(($_POST["newcomments"])) {
    $un=sqlesc($CURUSER["username"].": ".$_POST["newcomments"]."\n");
   $mq="UPDATE uploadapp SET modcomments=CONCAT(modcomments,".$un.") WHERE id=".$_POST["pollid"];
   mysql_query($mq);
   print("Added coment to poll ".$_POST["pollid"]);
  }
 }
   
}
print("<h1>Aplicações Recentes de Upload</h1>");
$res=mysql_query("SELECT * FROM uploadapp ORDER BY applied DESC") or die (mysql_error());
if(!mysql_num_rows($res)) print("<h1>Nome");
else {
?>
 <table border=1 style='border-collapse: collapse' bordercolor=#646262>
 <tr><td>Votação#</td>
 <td>Usuário</td>
 <td><span title=" Application Date &amp; Join Date " class="popup">Data Add</span></td>
 <td><span title=" Group Affiliated Application " class="popup">Grupo</span></td>
 <td><span title=" Purposed Content to deliver" class="popup">Conteúdo</span></td>
 <td><span title=" Comments Left to us ops to sway us to vote yes" class="popup">Comentário</span></td>
 <td><span title=" Does User Pass Ratio Requirement?" class="popup">Ratio</span></td>
 <td><span title=" Does User Pass Upload Transmission Requirement?" class="popup"><?php echo $upreq ;?>GB+</span></td>
 <td><span title=" Does User know how to seed torrents?" class="popup">Seeding</span></td>
 <td><span title=" Does User acknowledge other groups right to only upload their titles?" class="popup">Grupo</span></td>
 <td><span title=" Does User acknowledge minimal seeding times?" class=popup">Seeder<br>Tempo</span></td>
 <td>Votação Enquete</td>
 </tr>
<?php  
 while($row=mysql_fetch_array($res))
 {
   $resu=mysql_query("SELECT * FROM users where id = ".$row["userid"]) or die (mysql_error());
   $rowu=mysql_fetch_array($resu);
   $voted=$tvotes=$votesyes=$votesno=0;
   if($row["votes"]!="") {
    $votes=explode(" ",$row["votes"]);
    for($i=0;$i<count($votes);$i++)
    {
     $votei=explode(":",$votes[$i]);
     if($CURUSER["id"]==$votei[0]) $voted++;
     $votei[1]?$votesyes++:$votesno++;
     $tvotes++;
    }
   }
 if ($rowu["downloaded"] > 0)
  $ratio = $rowu['uploaded'] / $rowu['downloaded'];
 else if ($rowu["uploaded"] > 0)
  $ratio = 1;
 else
  $ratio = 0;
?>    
 <tr>
   <form action="uploadapp.php" method="post" enctype="multipart/form-data" name="poll<?php echo $row["id"] ;?>" id="uploadapp">
   <input name="form" type="hidden" value="11">
 <input name="pollid" type="hidden" value="<?php echo $row["id"] ;?>">
 <td><?php echo $row["id"] ;?></td>
 <td><a href=account-details.php?id=<?php echo $row["userid"] ;?>><?php echo $rowu["username"] ;?></a></td>
 <td><?php echo $row["applied"] ;?></td>
 <td <?php echo ($row["grpacct"]?"bgcolor=\"#FFFF00\">(".unesc($row["grpdes"]).")&nbsp;".unesc($row["grpname"]):">N/A") ;?></td>
 <td><?php echo $row["content"] ;?></td>
 <td><?php echo $row["comment"] ;?></td>
 <td bgcolor="<?php echo ($ratio>=1?"#00FF00":"#FF0000") ;?>"></td>
 <td bgcolor="<?php echo ($rowu["uploaded"]>=$upreqn?"#00FF00":"#FF0000") ;?>"></td>
 <td bgcolor="<?php echo ($row["seeding"]?"#00FF00":"#FF0000") ;?>"></td>
 <td bgcolor="<?php echo ($row["othergrps"]?"#00FF00":"#FF0000")?>"></td>
  <td bgcolor="<?php echo ($row["seedtime"]?"#00FF00":"#FF0000") ;?>"></td>
  <td rowspan="2"><?php($voted||!$row["active"]?$votesyes." Sim<br>".$votesno." Não"
  :"Votes: ".$tvotes."<br>".
  "<input name=\"ballet\" type=\"submit\" value=\"Yes\">".
 "<input name=\"ballet\" type=\"submit\" value=\"No\">").
 (($CURUSER["class"]>=7&&$row["active"])?"<input name=\"closepoll\" type=\"submit\" value=\"Fechar votação\">":
  "<br>".($row["active"]?"<br><font color=#00FF00>Enquete Open</font>":"<br><font color=#FF0000>Poll Closed</font>")).
 ($CURUSER["class"]>=7?"<br><input name=\"removepoll\" type=\"submit\" value=\"remover enquete\">":"")
 ?></td></tr>  </form><tr>
   <form action="uploadapp.php" method="post" enctype="multipart/form-data" name="poll<?=$row["id"]?>" id="uploadapp">
   <input name="form" type="hidden" value="11">
 <input name="pollid" type="hidden" value="<?php echo $row["id"] ;?>">
 <td>&nbsp</td>
 <td>Comentários dos Mods</td>
 <td colspan="4"><textarea name="modcomments" rows="5" cols="50"><?php echo $row["modcomments"] ;?></textarea></td>
 <td colspan="5"><input name="newcomments" type="text" value="" maxlength="80"><br><input type="submit" name="addcomment" value="Enviar comentário"></td>
   </form>
 </tr>
<?php  
  }
  print("</table>");
 }


end_framec();
}    
//end_framec();
stdfoot();
?>