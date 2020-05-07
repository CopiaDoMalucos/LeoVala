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

stdhead(T_("USERCP"));

function navmenu(){
?>
<br>
<div align="center" class="framecentro">
<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
<tbody>
<tr>
<td align="center" width="10%" style="padding-right: 3px; padding-left: 3px;"><a href="account.php?action=edit_settings&do=edit"><img border="0" alt="" src="images/editar-perfil.png"><br>Conta</a></td>

<td align="center" width="10%" style="padding-right: 3px; padding-left: 3px;"><a href="account.php?action=edit_avatar&do=edit"><img border="0" alt="" src="images/avatasing.png"><br>Avatar / Assinatura</a></td>

<td align="center" width="10%" style="padding-right: 3px; padding-left: 3px;"><a href="account.php?action=edit_dados&do=edit"><img border="0" alt="" src="images/dadosuser.png"><br>Dados Pessoais</a></td>


<td align="center" width="10%" style="padding-right: 3px; padding-left: 3px;"><a href="account.php?action=changepw"><img border="0" alt="" src="images/editar-senha.png"><br>Senha</a></td>

<td align="center" width="10%" style="padding-right: 3px; padding-left: 3px;"><a href="account.php?action=mytorrents"><img border="0" alt="" src="images/torrent.png"><br>Torrentes</a></td>

<td align="center" width="10%" style="padding-right: 3px; padding-left: 3px;"><a href="account.php?action=bbcode"><img border="0" alt="" src="images/bbicones.png"><br>BBcode</a></td>

</tr>

</tbody>
</table>
</div>
<br>
    <?php
}//end func

$action = $_REQUEST["action"];
$do = $_REQUEST["do"];



/////////////// MY TORRENTS ///////////////////

if ($action=="mytorrents"){
begin_framec(T_("YOUR_TORRENTS"));
navmenu();
//page numbers
$page = (int) $_GET['page'];
$perpage = 20;

$res = SQL_Query_exec("SELECT COUNT(*) FROM torrents WHERE torrents.owner = " . $CURUSER["id"] ."");
$arr = mysql_fetch_row($res);
$pages = floor($arr[0] / $perpage);
if ($pages * $perpage < $arr[0])
  ++$pages;

if ($page < 1)
  $page = 1;
else
  if ($page > $pages)
    $page = $pages;

for ($i = 1; $i <= $pages; ++$i)
  if ($i == $page)
    $pagemenu .= "$i\n";
  else
    $pagemenu .= "<a href='account.php?action=mytorrents&amp;page=$i'>$i</a>\n";

if ($page == 1)
  $browsemenu .= "<a href='account.php?action=mytorrents&amp;page=" . ($page - 1) . "'>[Página Anterior]</a>";
else
  $browsemenu .= "<a href='account.php?action=mytorrents&amp;page=" . ($page - 1) . "'>[Página Anterior]</a>";

$browsemenu .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

if ($page == $pages)
  $browsemenu .= "<a href='account.php?action=mytorrents&amp;page=" . ($page + 1) . "'>[Próxima Página]</a>";
else
  $browsemenu .= "<a href='account.php?action=mytorrents&amp;page=" . ($page + 1) . "'>[Próxima Página]</a>";

$offset = ($page * $perpage) - $perpage;
//end page numbers


$where = "WHERE torrents.owner = " . $CURUSER["id"] ."";
$orderby = "ORDER BY added DESC";

$query = SQL_Query_exec("SELECT torrents.id, torrents.category, torrents.name, torrents.added, torrents.hits, torrents.banned, torrents.comments, torrents.seeders, torrents.leechers, torrents.times_completed, categories.name AS cat_name, categories.image AS cat_pic, categories.parent_cat AS cat_parent FROM torrents LEFT JOIN categories ON category = categories.id $where $orderby LIMIT $offset,$perpage");

$allcats = mysql_num_rows($query);
	if($allcats == 0) {
		echo '<div class="f-border comment"><br /><b>'.T_("NO_UPLOADS").'</b></div>';
	}else{
		print("<p align='center'>$pagemenu<br />$browsemenu</p><br>");
?>
	<table class='tab1' cellpadding='0' cellspacing='1' align='center'>
    <tr>
        <th class="ttable_head" width='60'><?php echo T_("TYPE"); ?></th>
        <th class="ttable_head"><?php echo T_("NAME"); ?></th>
        <th class="ttable_head"><?php echo T_("COMMENTS"); ?></th>
        <th class="ttable_head"><?php echo T_("HITS"); ?></th>
        <th class="ttable_head"><?php echo T_("SEEDS"); ?></th>
        <th class="ttable_head"><?php echo T_("LEECHERS"); ?></th>
        <th class="ttable_head"><?php echo T_("COMPLETED"); ?></th>
        <th class="ttable_head"><?php echo T_("ADDED"); ?></th>
        <th class="ttable_head"><?php echo T_("EDIT"); ?></th>
    </tr>
    
<?php
  
		while($row = mysql_fetch_assoc($query))
			{
			$char1 = 70; //cut length 
			$smallname = CutName(htmlspecialchars($row["name"]), $char1);
			echo "<tr><td class='tab1_col3' align='center'><img border=\"0\"src=\"" . $site_config['SITEURL'] . "/images/categories/" . $row["cat_pic"] . "\" alt=\"" . $row["cat_name"] . "\" /></td><td class='tab1_col3' align='left'><a href='torrents-details.php?id=$row[id]'>$smallname</a></td><td class='tab1_col3' align='center'><a href='comments.php?type=torrent&amp;id=$row[id]'>".number_format($row["comments"])."</a></td><td class='tab1_col3' align='center'>".number_format($row["hits"])."</td><td class='tab1_col3' align='center'>".number_format($row["seeders"])."</td><td class='tab1_col3' align='center'>".number_format($row["leechers"])."</td><td class='tab1_col3' align='center'>".number_format($row["times_completed"])."</td><td class='tab1_col3' align='center'>".get_elapsed_time(sql_timestamp_to_unix_timestamp($row["added"]))."</td><td class='tab1_col3'><a href='torrents-edit.php?id=$row[id]'>Editar</a></td></tr>\n";
			}
		echo "</table><br />";
		print("<p align='center'>$pagemenu<br />$browsemenu</p>");
	}

end_framec();
}

///bbcode
if ($action=="bbcode")
        {

    if ($do=="changer")    {
    
$bbcode = sqlesc($_POST["bbcode"]);

   $res_b = mysql_query("SELECT points_bbcode,bbcode FROM users WHERE id = " . $CURUSER["id"] . " AND status = 'confirmed'");
    $row_b = mysql_fetch_array($res_b);
    $niveau_b = $row_b["points_bbcode"];
	
        $res_i = mysql_query("SELECT nom,niveau FROM icone WHERE nom = " . $bbcode . " ");   
		  $row_i = mysql_fetch_array($res_i);
		   $niveau_i = $row_i["niveau"];
		  echo"".$niveau_b." ".$niveau_i."";
		        if ( $niveau_b >= $niveau_i)
        {
mysql_query("UPDATE users SET bbcode = " . $bbcode . "  WHERE id = " . $CURUSER["id"]."") or die(mysql_error());
 autolink("account.php?action=bbcode","<br/><font color=#ff0000>Redirecionamento daqui 3 segundos...<br/></font>Clique aqui Para voltar:<a href='account.php?action=bbcode'>Voltar</a></center>");

        }
else
{

       show_error_msg("".T_("ERRO")."", "bbcode erro", 1);
}
		
		
		
		
		


    
                        }//do
    
begin_framec("BBcode"); 
navmenu();
    $res = mysql_query("SELECT points_bbcode,bbcode FROM users WHERE id = " . $CURUSER["id"] . " AND status = 'confirmed'");
    $row = mysql_fetch_array($res);
    $niveau = $row["points_bbcode"];
    
    $icone = mysql_query("SELECT nom FROM icone WHERE niveau <= ".$niveau." ");
    $touticone = mysql_query("SELECT nom FROM icone order by niveau");

print ("
      	<table class='tab1' cellpadding='0' cellspacing='1' align='center'>

            <tr>
        ");

while ($gets = mysql_fetch_assoc($touticone))
        {
        
    print("        <td>
                    <img src='images/bbcode/".$gets["nom"]."/bbcode_video.gif'>
                 </td>
        ");
                                
        }
print("
            </tr>
        </table><br/>
    ");    
    
    $bbcode = "<select name=\"bbcode\">\n";
        while ($liste = mysql_fetch_assoc($icone))
            {
    $bbcode .= "<option value=\"".$liste[nom]."\"";
        if ($liste["nom"] == $row["bbcode"])
                $bbcode .= " selected=\"selected\"";
       $bbcode .= ">".$liste[nom]."</option>\n";
                }
       $bbcode .= "</select>\n";    

     
    print    ("
    <form method=post action=account.php>
    <input type='hidden' name='action' value='bbcode'>
    <input type='hidden' name='do' value='changer'>
       	<table class='tab1' cellpadding='0' cellspacing='1' align='center'>
            <tr>
                 <td align=center>".You_can_change_the_color_of_your_bbcode_icon_depending_on_your_level."<br/>
                    ".Your_current_level_is.":<font color=#00ffff>$row[points_bbcode]</font><br/>
                      ".Exchange_bonus_points_to_level_up."
                </td>

            </tr>
            
            <tr>
                <td><center>".your_selection.":<br/> $bbcode</center></td>
            </tr>
            
            <tr>
                <td colspan=2 align=center><input type=submit value=" . Enviar . " style=height: 25px></td>
            </tr>
        </table>
    </form>
            ");
    
    
end_framec();
        }
/////////////////////// EDIT SETTINGS ////////////////
if ($action=="edit_settings"){

	if ($do=="edit"){
	begin_framec(T_("EDIT_SETTINGS"));

	navmenu();
	echo"<center><b>Sua passkey: <font color='red'> ".$CURUSER['passkey']."</font></b></center>";
	?>
	<form enctype="multipart/form-data" method="post" action="account.php">
	<input type="hidden" name="action" value="edit_settings" />
	<input type="hidden" name="do" value="save_settings" />
	<table class='tab1' cellpadding='0' cellspacing='1' align='center'>
				<td align='center' colspan='2' class='tab1_cab1'>Modificar Conta</td>
	<?php

	$ss_r = SQL_Query_exec("SELECT * from stylesheets");
	$ss_sa = array();
	while ($ss_a = mysql_fetch_array($ss_r))
	{
	  $ss_id = $ss_a["id"];
	  $ss_name = $ss_a["name"];
	  $ss_sa[$ss_name] = $ss_id;
	}
	ksort($ss_sa);
	reset($ss_sa);
	while (list($ss_name, $ss_id) = each($ss_sa))
	{
	  if ($ss_id == $CURUSER["stylesheet"]) $ss = " selected='selected'"; else $ss = "";
	  $stylesheets .= "<option value='$ss_id'$ss>$ss_name</option>\n";
	}

	  $teams = "<option value='0'>--- ".T_("NONE_SELECTED")." ----</option>\n";
	$sashok = SQL_Query_exec("SELECT id,name FROM teams ORDER BY name");
	while ($sasha = mysql_fetch_array($sashok))
		$teams .= "<option value='$sasha[id]'" . ($CURUSER["team"] == $sasha['id'] ? " selected='selected'" : "") . ">$sasha[name]</option>\n"; 




	$acceptpms = $CURUSER["acceptpms"] == "yes";
	print ("<tr><td align='right' class='tab1_col3'><b>" . T_("ACCEPT_PMS") . ":</b> </td><td class='tab1_col3'><input type='radio' name='acceptpms'" . ($acceptpms ? " checked='checked'" : "") .
	  " value='yes' /><b>".T_("FROM_ALL")."</b> <input type='radio' name='acceptpms'" .
	  ($acceptpms ? "" : " checked='checked'") . " value='no' /><b>" . T_("FROM_STAFF_ONLY") . "</b><br /><i>".T_("ACCEPTPM_WHICH_USERS")."</i></td></tr>");


	 
	 	$torrentesp = "<option value='10'" . ($CURUSER["torrentesp"] == "10" ? " selected='selected'" : "") . ">10</option>\n"
		 ."<option value='20'" . ($CURUSER["torrentesp"] == "20" ? " selected='selected'" : "") . ">20</option>\n"
		  ."<option value='30'" . ($CURUSER["torrentesp"] == "30" ? " selected='selected'" : "") . ">30</option>\n";
	 
	 
	// START CAT LIST SQL
	$r = SQL_Query_exec("SELECT id,name,parent_cat FROM categories ORDER BY parent_cat ASC, sort_index ASC");
	if (mysql_num_rows($r) > 0)
	{
		$categories .= "<table><tr>\n";
		$i = 0;
		while ($a = mysql_fetch_assoc($r))
		{
		  $categories .=  ($i && $i % 2 == 0) ? "</tr><tr>" : "";
		  $categories .= "<td class='bottom' style='padding-right: 5px'><input name='cat$a[id]' type=\"checkbox\" " . (strpos($CURUSER['notifs'], "[cat$a[id]]") !== false ? " checked='checked'" : "") . " value='yes' />&nbsp;" .htmlspecialchars($a["parent_cat"]).": " . htmlspecialchars($a["name"]) . "</td>\n";
		  ++$i;
		}
		$categories .= "</tr></table>\n";
	}

	// END CAT LIST SQL
	function priv($name, $descr) {
		global $CURUSER;
		if ($CURUSER["privacy"] == $name)
			return "<input type=\"radio\" name=\"privacy\" value=\"$name\" checked=\"checked\" /> $descr";
		return "<input type=\"radio\" name=\"privacy\" value=\"$name\" /> $descr";
	}


	   //print("<tr><td align=right class=tab1_col3 valign=top><b>".T_("CATEGORY_FILTER").": </b></td><td align=left class=tab1_col3><i>The system will only display the following categories when browsing (uncheck all to disable filter).</i><br />".$categories."</td></tr>");


	print("<tr><td align='right' class='tab1_col3'><b>" . T_("PREFERRED_CLIENT") .":</b> </td><td align='left' class='tab1_col3'><input type='text' size='20' maxlength='20' name='client' value=\"" . htmlspecialchars($CURUSER["client"]) . "\" /></td></tr>");

	



	print("<tr><td align='right' class='tab1_col3'><b>" . T_("CUSTOM_TITLE") . ":</b> </td><td align='left' class='tab1_col3'><input type='text' name='title' size='50' value=\"" . strip_tags($CURUSER["title"]) .
	  "\" /><br />\n <i>" . T_("HTML_NOT_ALLOWED") . "</i></td></tr>");


	print("<tr><td align='right' class='tab1_col3'><b>".T_("RESET_PASSKEY").":</b> </td><td align='left' class='tab1_col3'><input type='checkbox' name='resetpasskey' value='1' />&nbsp;<i>".T_("RESET_PASSKEY_MSG").".</i></td></tr>");

    print("<tr><td align='right' class='tab1_col3'><b>".T_("HIDE_SHOUTBOX").":</b></td><td align='left' class='tab1_col3'><input type='checkbox' name='hideshoutbox' value='yes' ".($CURUSER['hideshoutbox'] == 'yes' ? 'checked="checked"' : '')." />&nbsp;<i>".T_("HIDE_SHOUTBOX_MENSAGEM")."</i></td></tr> ");
     print("<tr><td align='right' class='tab1_col3'><b>".T_("HIDE_ADULTOS").":</b></td><td align='left' class='tab1_col3'><input type='checkbox' name='ver_xxx' value='yes' ".($CURUSER['ver_xxx'] == 'yes' ? 'checked' : '')." />&nbsp;<i>Sim, quero ver o conteúdo adulto.</i></td></tr> ");
      
	  
	  print("<tr><td align='right' class='tab1_col3'><b>Torrents Lançados:</b></td><td align='left' class='tab1_col3'><input type='checkbox' name='ver_lancados' value='yes' ".($CURUSER['ver_lancados'] == 'yes' ? 'checked' : '')." />&nbsp;<i>Sim. Quero mostrar os torrents que lancei.</i></td></tr> ");
      print("<tr><td align='right' class='tab1_col3'><b>Torrents Baixados:</b></td><td align='left' class='tab1_col3'><input type='checkbox' name='ver_baixados' value='yes' ".($CURUSER['ver_baixados'] == 'yes' ? 'checked' : '')." />&nbsp;<i>Sim. Quero mostrar os torrents que baixei.</i></td></tr> ");
    

	print("<tr><td align='right' class='tab1_col3'><b>Notificação de comentários:</b></td><td align='left' class='tab1_col3'><input type='checkbox' name='ver_com' value='yes' ".($CURUSER['ver_com'] == 'yes' ? 'checked' : '')." />&nbsp;<i>Marcando a opção, você receberá uma MP toda vez que alguem comentar em seus torrents..</i></td></tr> ");
         print("<tr><td align='right' class='tab1_col3'><b>Ver Banners:</b></td><td align='left' class='tab1_col3'><input type='checkbox' name='grupo_cyber' value='yes' ".($CURUSER['grupo_cyber'] == 'yes' ? 'checked' : '')." />&nbsp;<i>Cyber Torrents.</i><br><input type='checkbox' name='grupo_peace' value='yes' ".($CURUSER['grupo_peace'] == 'yes' ? 'checked' : '')." />&nbsp;<i>Peace Torrents.</i><br><input type='checkbox' name='grupo_files' value='yes' ".($CURUSER['grupo_files'] == 'yes' ? 'checked' : '')." />&nbsp;<i>File Share.</i><br><input type='checkbox' name='grupo_lord' value='yes' ".($CURUSER['grupo_lord'] == 'yes' ? 'checked' : '')." />&nbsp;<i>Lord Torrents.</i><br><br>Marque os banners que você deseja ver na página inicial do site.</tr> ");
		 print("<tr><td align='right' class='tab1_col3'><b>Torrents por página:</b> </td><td align='left' class='tab1_col3'><select size='1' name='torrentesp'>\n$torrentesp\n</select></td></tr>");






	?>
	<tr><td colspan="2" align="center"><input type="submit" value="<?php echo T_("SUBMIT");?>" /> <input type="reset" value="<?php echo T_("REVERT");?>" /></td></tr>
	</table></form>

	<?php
	end_framec();
	}

	

	
	
	
	
	
	
	
	
	

	if ($do == "save_settings"){
	begin_framec(T_("EDIT_ACCOUNT_SETTINGS"));

	navmenu();
		$set = array();
		  $updateset = array();


		  $acceptpms = $_POST["acceptpms"];
		  $pmnotif = $_POST["pmnotif"];
		  $privacy = $_POST["privacy"];
		  $notifs = ($pmnotif == 'yes' ? "[pm]" : "");
		  $r = SQL_Query_exec("SELECT id FROM categories");
		  $rows = mysql_num_rows($r);
		  for ($i = 0; $i < $rows; ++$i) {
				$a = mysql_fetch_assoc($r);
				if ($_POST["cat$a[id]"] == 'yes')
				  $notifs .= "[cat$a[id]]";
		  }

		  if ($_POST['resetpasskey']) $updateset[] = "passkey=''";
          

          
         
          
		  $title = strip_tags($_POST["title"]);


		  $language = $_POST["language"];
		  $client = strip_tags($_POST["client"]);

		  $torrentesp = $_POST["torrentesp"];


		  if (is_valid_id($language))
			$updateset[] = "language = '$language'";
	

			  if (is_valid_id($torrentesp))
			$updateset[] = "torrentesp = $torrentesp";
		  if ($acceptpms == "yes")
			$acceptpms = 'yes';
		  else
			$acceptpms = 'no';
		  if (is_valid_id($age))
				$updateset[] = "age = '$age'";
          
          $hideshoutbox = ($_POST["hideshoutbox"] == "yes") ? "yes" : "no";

		  $grupo_cyber = ($_POST["grupo_cyber"] == "yes") ? "yes" : "no";
		  $grupo_peace = ($_POST["grupo_peace"] == "yes") ? "yes" : "no";
          $grupo_files = ($_POST["grupo_files"] == "yes") ? "yes" : "no";
		  $grupo_lord = ($_POST["grupo_lord"] == "yes") ? "yes" : "no";
		 
		  
                  if ($_POST["ver_xxx"] == 'yes')
                $ver_xxx = 'yes';
            else
                $ver_xxx = 'no';  
				 if ($_POST["ver_lancados"] == 'yes')
                $ver_lancados = 'yes';
            else
                $ver_lancados = 'no';  
				 if ($_POST["ver_baixados"] == 'yes')
                $ver_baixados = 'yes';
            else
                $ver_baixados = 'no';  				
			    if ($_POST["ver_com"] == 'yes')
                $ver_com = 'yes';
            else
                $ver_com = 'no';  

			$updateset[] = "hideshoutbox = ".sqlesc($hideshoutbox);   	
            $updateset[] = "grupo_peace = ".sqlesc($grupo_peace);  
            $updateset[] = "grupo_cyber = ".sqlesc($grupo_cyber);  
            $updateset[] = "grupo_files = ".sqlesc($grupo_files);
            $updateset[] = "grupo_lord = ".sqlesc($grupo_lord);	
			
    		$updateset[] = "ver_xxx = ".sqlesc($ver_xxx); 
			$updateset[] = "ver_lancados = ".sqlesc($ver_lancados); 
			$updateset[] = "ver_baixados = ".sqlesc($ver_baixados); 
            $updateset[] = "ver_com = ".sqlesc($ver_com);			
			$updateset[] = "acceptpms = ".sqlesc($acceptpms);
			$updateset[] = "commentpm = " . sqlesc($pmnotif == "yes" ? "yes" : "no");
			$updateset[] = "notifs = ".sqlesc($notifs);
			$updateset[] = "privacy = ".sqlesc($privacy);
		
			$updateset[] = "client = ".sqlesc($client);

			$updateset[] = "title = ".sqlesc($title);


		  /* ****** */

		// message
					SQL_Query_exec("UPDATE users SET " . implode(",", $updateset) . " WHERE id = " . $CURUSER["id"]."");
			$edited=1;
	echo "<br /><br /><center><b><font color='#ff0000'>Updated OK</font></b></center><br /><br />";

		end_framec();
	}// end do

}//end action

/////////////////////// EDIT SETTINGS ////////////////
if ($action=="edit_avatar"){

	if ($do=="edit"){
	begin_framec(T_("EDIT_SETTINGS"));

	navmenu();

	?>
	<form enctype="multipart/form-data" method="post" action="account.php">
	<input type="hidden" name="action" value="edit_avatar" />
	<input type="hidden" name="do" value="save_avatar" />
	<table class='tab1' cellpadding='0' cellspacing='1' align='center'>
				<td align='center' colspan='2' class='tab1_cab1'>Alterar Avatar / Assinatura</td>
	<?php


	print("<tr><td align='right' width='30%'  class='tab1_col3'><b>" . T_("AVATAR_UPLOAD") . ":</b> </td><td align='left' width='70%'  class='tab1_col3'><input type='text' name='avatar' size='50' value=\"" . htmlspecialchars($CURUSER["avatar"]) .
	  "\" /><br />\n<i>" . T_("AVATAR_LINKIMAGE") . "</i><br /></td></tr>");

	print("<tr><td align='right' width='30%'  class='tab1_col3' valign='top'><b>" . T_("SIGNATURE") . ":</b> </td><td align='left' width='70%'  class='tab1_col3'><textarea name='signature' cols='50' rows='10'>" . htmlspecialchars($CURUSER["signature"]) .
	  "</textarea><br />\n <i>".sprintf(T_("MAX_CHARS"), 150).", " . T_("HTML_NOT_ALLOWED") . "</i></td></tr>");
	?>
	<tr><td colspan="2" align="center"><input type="submit" value="<?php echo T_("SUBMIT");?>" /> <input type="reset" value="<?php echo T_("REVERT");?>" /></td></tr>
	</table></form>

	<?php
	end_framec();
	}

	if ($do == "save_avatar"){
	begin_framec(T_("EDIT_ACCOUNT_SETTINGS"));

	navmenu();
		$set = array();
		  $updateset = array();







          
          $avatar = strip_tags( $_POST["avatar"] );
          
          if ( $avatar != null )
          {    
               # Allowed Image Extenstions.
               $allowed_types = &$site_config["allowed_image_types"];    
              
               # We force http://
               if ( !preg_match( "#^\w+://#i", $avatar ) ) $avatar = "http://" . $avatar;

               # Clean Avatar Path.
               $avatar = cleanstr( $avatar );
               
               # Validate Image.
               $im = @getimagesize( $avatar );
               
               if ( !$im[ 2 ] || !@array_key_exists( $im['mime'], $allowed_types ) )
                     $message = "Erro: O url avatar estava determinado a ser inválido.";
                     
               # Save New Avatar.
               $updateset[] = "avatar = " . sqlesc($avatar);
          }
          		
		  $signature = $_POST["signature"];
		$updateset[] = "signature = ".sqlesc($signature);

		  /* ****** */

		// message
					SQL_Query_exec("UPDATE users SET " . implode(",", $updateset) . " WHERE id = " . $CURUSER["id"]."");
			$edited=1;
	echo "<br /><br /><center><b><font color='#ff0000'>Updated OK</font></b></center><br /><br />";

		end_framec();
	}// end do

}//end action























/////////////////////// EDIT SETTINGS ////////////////
if ($action=="edit_dados"){

	if ($do=="edit"){
	begin_framec(T_("EDIT_SETTINGS"));

	navmenu();

	?>
	<form enctype="multipart/form-data" method="post" action="account.php">
	<input type="hidden" name="action" value="edit_dados" />
	<input type="hidden" name="do" value="save_dados" />
	<table class='tab1' cellpadding='0' cellspacing='1' align='center'>
				<td align='center' colspan='2' class='tab1_cab1'>Alterar Dados Pessoais</td>
	<?php
	
	$gender = "<option value='Male'" . ($CURUSER["gender"] == "Male" ? " selected='selected'" : "") . ">" . T_("Male") . "</option>\n"
		 ."<option value='Female'" . ($CURUSER["gender"] == "Female" ? " selected='selected'" : "") . ">" . T_("Female") . "</option>\n";
	$countries = "<option value='0'>----</option>\n";
	$ct_r = SQL_Query_exec("SELECT id,name from countries ORDER BY name");
	while ($ct_a = mysql_fetch_array($ct_r))
	  $countries .= "<option value='$ct_a[id]'" . ($CURUSER["country"] == $ct_a['id'] ? " selected='selected'" : "") . ">$ct_a[name]</option>\n";
	  
	  	  	  $estados = "<option value=0>----</option>\n";
	$ct_r1 = SQL_Query_exec("SELECT id,name from estados ORDER BY name");
	while ($ct_a1 = mysql_fetch_array($ct_r1))
	  $estados .= "<option value=$ct_a1[id]" . ($CURUSER["estado"] == $ct_a1['id'] ? " selected" : "") . ">$ct_a1[name]</option>\n";
	  
		 
$dia = "<option value=''" . ($CURUSER["dia"] == "" ? " selected='selected'" : "") . "></option>\n"
		."<option value='01'" . ($CURUSER["dia"] == "01" ? " selected='selected'" : "") . ">01</option>\n"
		."<option value='02'" . ($CURUSER["dia"] == "02" ? " selected='selected'" : "") . ">02</option>\n"
		."<option value='03'" . ($CURUSER["dia"] == "03" ? " selected='selected'" : "") . ">03</option>\n"
		."<option value='04'" . ($CURUSER["dia"] == "04" ? " selected='selected'" : "") . ">04</option>\n"
		."<option value='05'" . ($CURUSER["dia"] == "05" ? " selected='selected'" : "") . ">05</option>\n"
		."<option value='06'" . ($CURUSER["dia"] == "06" ? " selected='selected'" : "") . ">06</option>\n"
		."<option value='07'" . ($CURUSER["dia"] == "07" ? " selected='selected'" : "") . ">07</option>\n"
		."<option value='08'" . ($CURUSER["dia"] == "08" ? " selected='selected'" : "") . ">08</option>\n"
		."<option value='09'" . ($CURUSER["dia"] == "09" ? " selected='selected'" : "") . ">09</option>\n"
		."<option value='10'" . ($CURUSER["dia"] == "10" ? " selected='selected'" : "") . ">10</option>\n"
		."<option value='11'" . ($CURUSER["dia"] == "11" ? " selected='selected'" : "") . ">11</option>\n"
		."<option value='12'" . ($CURUSER["dia"] == "12" ? " selected='selected'" : "") . ">12</option>\n"
		."<option value='13'" . ($CURUSER["dia"] == "13" ? " selected='selected'" : "") . ">13</option>\n"
		."<option value='14'" . ($CURUSER["dia"] == "14" ? " selected='selected'" : "") . ">14</option>\n"
		."<option value='15'" . ($CURUSER["dia"] == "15" ? " selected='selected'" : "") . ">15</option>\n"
		."<option value='16'" . ($CURUSER["dia"] == "16" ? " selected='selected'" : "") . ">16</option>\n"
		."<option value='17'" . ($CURUSER["dia"] == "17" ? " selected='selected'" : "") . ">17</option>\n"
		."<option value='18'" . ($CURUSER["dia"] == "18" ? " selected='selected'" : "") . ">18</option>\n"
		."<option value='19'" . ($CURUSER["dia"] == "19" ? " selected='selected'" : "") . ">19</option>\n"
		."<option value='20'" . ($CURUSER["dia"] == "20" ? " selected='selected'" : "") . ">20</option>\n"
		."<option value='21'" . ($CURUSER["dia"] == "21" ? " selected='selected'" : "") . ">21</option>\n"
		."<option value='22'" . ($CURUSER["dia"] == "22" ? " selected='selected'" : "") . ">22</option>\n"
		."<option value='23'" . ($CURUSER["dia"] == "23" ? " selected='selected'" : "") . ">23</option>\n"
		."<option value='24'" . ($CURUSER["dia"] == "24" ? " selected='selected'" : "") . ">24</option>\n"
		."<option value='25'" . ($CURUSER["dia"] == "25" ? " selected='selected'" : "") . ">25</option>\n"
		."<option value='26'" . ($CURUSER["dia"] == "26" ? " selected='selected'" : "") . ">26</option>\n"
		."<option value='27'" . ($CURUSER["dia"] == "27" ? " selected='selected'" : "") . ">27</option>\n"
		."<option value='28'" . ($CURUSER["dia"] == "28" ? " selected='selected'" : "") . ">28</option>\n"
		."<option value='29'" . ($CURUSER["dia"] == "29" ? " selected='selected'" : "") . ">29</option>\n"
		."<option value='30'" . ($CURUSER["dia"] == "30" ? " selected='selected'" : "") . ">30</option>\n"
		."<option value='31'" . ($CURUSER["dia"] == "31" ? " selected='selected'" : "") . ">31</option>\n"
		;
	 
	 	$mes = "<option value=''" . ($CURUSER["mes"] == "" ? " selected='selected'" : "") . "></option>\n"
		."<option value='01'" . ($CURUSER["mes"] == "01" ? " selected='selected'" : "") . ">01</option>\n"
		 ."<option value='02'" . ($CURUSER["mes"] == "02" ? " selected='selected'" : "") . ">02</option>\n"
		 ."<option value='03'" . ($CURUSER["mes"] == "03" ? " selected='selected'" : "") . ">03</option>\n"
		 ."<option value='04'" . ($CURUSER["mes"] == "04" ? " selected='selected'" : "") . ">04</option>\n"
		 ."<option value='05'" . ($CURUSER["mes"] == "05" ? " selected='selected'" : "") . ">05</option>\n"
		 ."<option value='06'" . ($CURUSER["mes"] == "06" ? " selected='selected'" : "") . ">06</option>\n"
		 ."<option value='07'" . ($CURUSER["mes"] == "07" ? " selected='selected'" : "") . ">07</option>\n"
		 ."<option value='08'" . ($CURUSER["mes"] == "08" ? " selected='selected'" : "") . ">08</option>\n"
		 ."<option value='09'" . ($CURUSER["mes"] == "09" ? " selected='selected'" : "") . ">09</option>\n"
		 ."<option value='10'" . ($CURUSER["mes"] == "10" ? " selected='selected'" : "") . ">10</option>\n"
		 ."<option value='11'" . ($CURUSER["mes"] == "11" ? " selected='selected'" : "") . ">11</option>\n"
		 ."<option value='12'" . ($CURUSER["mes"] == "12" ? " selected='selected'" : "") . ">12</option>\n";
	 
	    $ano = "<option value=''" . ($CURUSER["ano"] == "" ? " selected='selected'" : "") . "></option>\n"
		 ."<option value='2005'" . ($CURUSER["ano"] == "2005" ? " selected='selected'" : "") . ">2005</option>\n"
         ."<option value='2004'" . ($CURUSER["ano"] == "2004" ? " selected='selected'" : "") . ">2004</option>\n"
         ."<option value='2003'" . ($CURUSER["ano"] == "2003" ? " selected='selected'" : "") . ">2003</option>\n"
		 ."<option value='2002'" . ($CURUSER["ano"] == "2002" ? " selected='selected'" : "") . ">2002</option>\n"
		 ."<option value='2001'" . ($CURUSER["ano"] == "2001" ? " selected='selected'" : "") . ">2001</option>\n"
		 ."<option value='2000'" . ($CURUSER["ano"] == "2000" ? " selected='selected'" : "") . ">2000</option>\n"
		 ."<option value='1999'" . ($CURUSER["ano"] == "1999" ? " selected='selected'" : "") . ">1999</option>\n"
		 ."<option value='1998'" . ($CURUSER["ano"] == "1998" ? " selected='selected'" : "") . ">1998</option>\n"
		 ."<option value='1997'" . ($CURUSER["ano"] == "1997" ? " selected='selected'" : "") . ">1997</option>\n"
		 ."<option value='1996'" . ($CURUSER["ano"] == "1996" ? " selected='selected'" : "") . ">1996</option>\n"
		 ."<option value='1995'" . ($CURUSER["ano"] == "1995" ? " selected='selected'" : "") . ">1995</option>\n"
		 ."<option value='1994'" . ($CURUSER["ano"] == "1994" ? " selected='selected'" : "") . ">1994</option>\n"
		 ."<option value='1993'" . ($CURUSER["ano"] == "1993" ? " selected='selected'" : "") . ">1993</option>\n"
		 ."<option value='1992'" . ($CURUSER["ano"] == "1992" ? " selected='selected'" : "") . ">1992</option>\n"
		 ."<option value='1991'" . ($CURUSER["ano"] == "1991" ? " selected='selected'" : "") . ">1991</option>\n"
		 ."<option value='1990'" . ($CURUSER["ano"] == "1990" ? " selected='selected'" : "") . ">1990</option>\n"
		 ."<option value='1989'" . ($CURUSER["ano"] == "1989" ? " selected='selected'" : "") . ">1989</option>\n"
		 ."<option value='1988'" . ($CURUSER["ano"] == "1988" ? " selected='selected'" : "") . ">1988</option>\n"
		 ."<option value='1987'" . ($CURUSER["ano"] == "1987" ? " selected='selected'" : "") . ">1987</option>\n"
		 ."<option value='1986'" . ($CURUSER["ano"] == "1986" ? " selected='selected'" : "") . ">1986</option>\n"
		 ."<option value='1985'" . ($CURUSER["ano"] == "1985" ? " selected='selected'" : "") . ">1985</option>\n"
		 ."<option value='1984'" . ($CURUSER["ano"] == "1984" ? " selected='selected'" : "") . ">1984</option>\n"
		 ."<option value='1983'" . ($CURUSER["ano"] == "1983" ? " selected='selected'" : "") . ">1983</option>\n"
		 ."<option value='1982'" . ($CURUSER["ano"] == "1982" ? " selected='selected'" : "") . ">1982</option>\n"
		 ."<option value='1981'" . ($CURUSER["ano"] == "1981" ? " selected='selected'" : "") . ">1981</option>\n"
		 ."<option value='1980'" . ($CURUSER["ano"] == "1980" ? " selected='selected'" : "") . ">1980</option>\n"
		 ."<option value='1979'" . ($CURUSER["ano"] == "1979" ? " selected='selected'" : "") . ">1979</option>\n"
		 ."<option value='1978'" . ($CURUSER["ano"] == "1978" ? " selected='selected'" : "") . ">1978</option>\n"
		 ."<option value='1977'" . ($CURUSER["ano"] == "1977" ? " selected='selected'" : "") . ">1977</option>\n"
		 ."<option value='1976'" . ($CURUSER["ano"] == "1976" ? " selected='selected'" : "") . ">1976</option>\n"
		 ."<option value='1975'" . ($CURUSER["ano"] == "1975" ? " selected='selected'" : "") . ">1975</option>\n"
		 ."<option value='1974'" . ($CURUSER["ano"] == "1974" ? " selected='selected'" : "") . ">1974</option>\n"
		 ."<option value='1973'" . ($CURUSER["ano"] == "1973" ? " selected='selected'" : "") . ">1973</option>\n"
		 ."<option value='1972'" . ($CURUSER["ano"] == "1972" ? " selected='selected'" : "") . ">1972</option>\n"
		 ."<option value='1971'" . ($CURUSER["ano"] == "1971" ? " selected='selected'" : "") . ">1971</option>\n"
		 ."<option value='1970'" . ($CURUSER["ano"] == "1970" ? " selected='selected'" : "") . ">1970</option>\n"
		 ."<option value='1969'" . ($CURUSER["ano"] == "1969" ? " selected='selected'" : "") . ">1969</option>\n"
		 ."<option value='1968'" . ($CURUSER["ano"] == "1968" ? " selected='selected'" : "") . ">1968</option>\n"
		 ."<option value='1967'" . ($CURUSER["ano"] == "1967" ? " selected='selected'" : "") . ">1967</option>\n"
		 ."<option value='1966'" . ($CURUSER["ano"] == "1966" ? " selected='selected'" : "") . ">1966</option>\n"
		 ."<option value='1965'" . ($CURUSER["ano"] == "1965" ? " selected='selected'" : "") . ">1965</option>\n"
		 ."<option value='1964'" . ($CURUSER["ano"] == "1964" ? " selected='selected'" : "") . ">1964</option>\n"
		 ."<option value='1963'" . ($CURUSER["ano"] == "1963" ? " selected='selected'" : "") . ">1963</option>\n"
		 ."<option value='1962'" . ($CURUSER["ano"] == "1962" ? " selected='selected'" : "") . ">1962</option>\n"
		 ."<option value='1961'" . ($CURUSER["ano"] == "1961" ? " selected='selected'" : "") . ">1961</option>\n"
		 ."<option value='1960'" . ($CURUSER["ano"] == "1960" ? " selected='selected'" : "") . ">1960</option>\n"
		 ."<option value='1959'" . ($CURUSER["ano"] == "1959" ? " selected='selected'" : "") . ">1959</option>\n"
		 ."<option value='1958'" . ($CURUSER["ano"] == "1958" ? " selected='selected'" : "") . ">1958</option>\n"
		 ."<option value='1957'" . ($CURUSER["ano"] == "1957" ? " selected='selected'" : "") . ">1957</option>\n"
		 ."<option value='1956'" . ($CURUSER["ano"] == "1956" ? " selected='selected'" : "") . ">1956</option>\n"
		 ."<option value='1955'" . ($CURUSER["ano"] == "1955" ? " selected='selected'" : "") . ">1955</option>\n"
		 ."<option value='1954'" . ($CURUSER["ano"] == "1954" ? " selected='selected'" : "") . ">1954</option>\n"
		 ."<option value='1953'" . ($CURUSER["ano"] == "1953" ? " selected='selected'" : "") . ">1953</option>\n"
		 ."<option value='1952'" . ($CURUSER["ano"] == "1952" ? " selected='selected'" : "") . ">1952</option>\n"
		 ."<option value='1951'" . ($CURUSER["ano"] == "1951" ? " selected='selected'" : "") . ">1951</option>\n"
		 ."<option value='1950'" . ($CURUSER["ano"] == "1950" ? " selected='selected'" : "") . ">1950</option>\n"
		 ."<option value='1949'" . ($CURUSER["ano"] == "1949" ? " selected='selected'" : "") . ">1949</option>\n"
		 ."<option value='1948'" . ($CURUSER["ano"] == "1948" ? " selected='selected'" : "") . ">1948</option>\n"
		 ."<option value='1947'" . ($CURUSER["ano"] == "1947" ? " selected='selected'" : "") . ">1947</option>\n"
		 ."<option value='1945'" . ($CURUSER["ano"] == "1945" ? " selected='selected'" : "") . ">1945</option>\n"
		 ."<option value='1944'" . ($CURUSER["ano"] == "1944" ? " selected='selected'" : "") . ">1944</option>\n"
		 ."<option value='1943'" . ($CURUSER["ano"] == "1943" ? " selected='selected'" : "") . ">1943</option>\n"
		 ."<option value='1942'" . ($CURUSER["ano"] == "1942" ? " selected='selected'" : "") . ">1942</option>\n"
		 ."<option value='1941'" . ($CURUSER["ano"] == "1941" ? " selected='selected'" : "") . ">1941</option>\n"
		 ."<option value='1940'" . ($CURUSER["ano"] == "1940" ? " selected='selected'" : "") . ">1940</option>\n"
		 ."<option value='1939'" . ($CURUSER["ano"] == "1939" ? " selected='selected'" : "") . ">1939</option>\n"
		 ."<option value='1938'" . ($CURUSER["ano"] == "1938" ? " selected='selected'" : "") . ">1938</option>\n"
		 ."<option value='1937'" . ($CURUSER["ano"] == "1937" ? " selected='selected'" : "") . ">1937</option>\n"
		 ."<option value='1936'" . ($CURUSER["ano"] == "1936" ? " selected='selected'" : "") . ">1936</option>\n"
		 ."<option value='1935'" . ($CURUSER["ano"] == "1935" ? " selected='selected'" : "") . ">1935</option>\n"
		 ."<option value='1934'" . ($CURUSER["ano"] == "1934" ? " selected='selected'" : "") . ">1934</option>\n"
		 ."<option value='1933'" . ($CURUSER["ano"] == "1933" ? " selected='selected'" : "") . ">1933</option>\n"
		 ."<option value='1932'" . ($CURUSER["ano"] == "1932" ? " selected='selected'" : "") . ">1932</option>\n"
		 ."<option value='1931'" . ($CURUSER["ano"] == "1931" ? " selected='selected'" : "") . ">1931</option>\n"
		 ."<option value='1930'" . ($CURUSER["ano"] == "1930" ? " selected='selected'" : "") . ">1930</option>\n"
		 ."<option value='1929'" . ($CURUSER["ano"] == "1929" ? " selected='selected'" : "") . ">1929</option>\n"
		 ."<option value='1928'" . ($CURUSER["ano"] == "1928" ? " selected='selected'" : "") . ">1928</option>\n"
		 ."<option value='1927'" . ($CURUSER["ano"] == "1927" ? " selected='selected'" : "") . ">1927</option>\n"
		 ."<option value='1926'" . ($CURUSER["ano"] == "1926" ? " selected='selected'" : "") . ">1926</option>\n"
		 ."<option value='1925'" . ($CURUSER["ano"] == "1925" ? " selected='selected'" : "") . ">1925</option>\n"
		 ."<option value='1924'" . ($CURUSER["ano"] == "1924" ? " selected='selected'" : "") . ">1924</option>\n"
		 ."<option value='1923'" . ($CURUSER["ano"] == "1923" ? " selected='selected'" : "") . ">1923</option>\n"
		 ."<option value='1922'" . ($CURUSER["ano"] == "1922" ? " selected='selected'" : "") . ">1922</option>\n"
		 ."<option value='1921'" . ($CURUSER["ano"] == "1921" ? " selected='selected'" : "") . ">1921</option>\n"
		 ."<option value='1920'" . ($CURUSER["ano"] == "1920" ? " selected='selected'" : "") . ">1920</option>\n"
		 ."<option value='1919'" . ($CURUSER["ano"] == "1919" ? " selected='selected'" : "") . ">1919</option>\n"
		 ."<option value='1918'" . ($CURUSER["ano"] == "1918" ? " selected='selected'" : "") . ">1918</option>\n"
		 ."<option value='1917'" . ($CURUSER["ano"] == "1917" ? " selected='selected'" : "") . ">1917</option>\n"
		 ."<option value='1916'" . ($CURUSER["ano"] == "1916" ? " selected='selected'" : "") . ">1916</option>\n"
		 ."<option value='1915'" . ($CURUSER["ano"] == "1915" ? " selected='selected'" : "") . ">1915</option>\n"
		 ."<option value='1914'" . ($CURUSER["ano"] == "1914" ? " selected='selected'" : "") . ">1914</option>\n"
		 ."<option value='1913'" . ($CURUSER["ano"] == "1913" ? " selected='selected'" : "") . ">1913</option>\n"
		 ."<option value='1912'" . ($CURUSER["ano"] == "1912" ? " selected='selected'" : "") . ">1912</option>\n"
		 ."<option value='1911'" . ($CURUSER["ano"] == "1911" ? " selected='selected'" : "") . ">1911</option>\n"
		 ."<option value='1910'" . ($CURUSER["ano"] == "1910" ? " selected='selected'" : "") . ">1910</option>\n"
		 ."<option value='1909'" . ($CURUSER["ano"] == "1909" ? " selected='selected'" : "") . ">1909</option>\n"
		 ."<option value='1908'" . ($CURUSER["ano"] == "1908" ? " selected='selected'" : "") . ">1908</option>\n"
		 ."<option value='1907'" . ($CURUSER["ano"] == "1907" ? " selected='selected'" : "") . ">1907</option>\n"
		 ."<option value='1906'" . ($CURUSER["ano"] == "1906" ? " selected='selected'" : "") . ">1906</option>\n"
		 ."<option value='1905'" . ($CURUSER["ano"] == "1905" ? " selected='selected'" : "") . ">1905</option>\n"
		 ."<option value='1904'" . ($CURUSER["ano"] == "1904" ? " selected='selected'" : "") . ">1904</option>\n"
		 ."<option value='1903'" . ($CURUSER["ano"] == "1903" ? " selected='selected'" : "") . ">1903</option>\n"
		 ."<option value='1902'" . ($CURUSER["ano"] == "1902" ? " selected='selected'" : "") . ">1902</option>\n"
		 ."<option value='1901'" . ($CURUSER["ano"] == "1901" ? " selected='selected'" : "") . ">1901</option>\n"
		 ."<option value='1900'" . ($CURUSER["ano"] == "1900" ? " selected='selected'" : "") . ">1900</option>\n"
		 ;
	print("<tr><td align='right' class='tab1_col3'><b>Nascimento:</b> </td><td align='left' class='tab1_col3'><select size='1' name='dia'>\n$dia\n</select>/<select size='1' name='mes'>\n$mes\n</select>/<select size='1' name='ano'>\n$ano\n</select> <BR> Formato: dd/mm/aaaa</td></tr>");
	print("<tr><td align='right' class='tab1_col3'><b>" . T_("AGE") . ":</b> </td><td align='left' class='tab1_col3'><input type='text' size='3' maxlength='2' name='age' value=\"" . htmlspecialchars($CURUSER["age"]) . "\" /></td></tr>");
	print("<tr><td align='right' class='tab1_col3'><b>" . T_("GENDER") . ":</b> </td><td align='left' class='tab1_col3'><select size='1' name='gender'>\n$gender\n</select></td></tr>");
	    print("<TR><TD align=right class=tab1_col3><b>" . T_("ESTADO_BR") . ":</b> </td><TD align=left class=tab1_col3><select name=estado>\n$estados\n</select></td></tr>");

	print("<tr><td align='right' class='tab1_col3'><b>" . T_("COUNTRY") . ":</b> </td><td align='left' class='tab1_col3'><select name='country'>\n$countries\n</select></td></tr>");
		ksort($tzs);
	reset($tzs);
	while (list($key, $val) = each($tzs)) {
	if ($CURUSER["tzoffset"] == $key)
		$tz .= "<option value=\"$key\" selected='selected'>$val[0]</option>\n";
	else
		$tz .= "<option value=\"$key\">$val[0]</option>\n";
	}
	
	print("<tr><td align='right' class='tab1_col3'><b>".T_("TIMEZONE").":</b> </td><td align='left' class='tab1_col3'><select name='tzoffset'>$tz</select></td></tr>");

	?>
	<tr><td colspan="2" align="center"><input type="submit" value="<?php echo T_("SUBMIT");?>" /> <input type="reset" value="<?php echo T_("REVERT");?>" /></td></tr>
	</table></form>

	<?php
	end_framec();
	}

	if ($do == "save_dados"){
	begin_framec(T_("EDIT_ACCOUNT_SETTINGS"));

	navmenu();
		$set = array();
		  $updateset = array();
	  $country = $_POST["country"];
		   $estado = $_POST["estado"];
		  if (is_valid_id($country))
			$updateset[] = "country = $country";
		  if (is_valid_id($estado))
			$updateset[] = "estado = $estado";
            $gender= $_POST["gender"];
				  $age = $_POST["age"];
			  $dia= $_POST["dia"];
		      $mes= $_POST["mes"];
		      $ano= $_POST["ano"];
		  
		    $timezone = (int)$_POST['tzoffset'];
			$updateset[] = "tzoffset = $timezone";
			
	        $updateset[] = "gender = ".sqlesc($gender);
			$updateset[] = "dia = ".sqlesc($dia);
			$updateset[] = "mes = ".sqlesc($mes);
			$updateset[] = "ano = ".sqlesc($ano);

		  /* ****** */

		// message
					SQL_Query_exec("UPDATE users SET " . implode(",", $updateset) . " WHERE id = " . $CURUSER["id"]."");
			$edited=1;
	echo "<br /><br /><center><b><font color='#ff0000'>Updated OK</font></b></center><br /><br />";

		end_framec();
	}// end do

}//end action



























if ($action=="changepw"){

	if ($do=="newpassword"){

        $chpassword = $_POST['chpassword'];
        $passagain = $_POST['passagain'];

        if ($chpassword != "") {

					if (strlen($chpassword) < 6)
						$message = T_("PASS_TOO_SHORT");
					if ($chpassword != $passagain)
						$message = T_("PASSWORDS_NOT_MATCH");
					$chpassword = passhash($chpassword);
                    $secret = mksecret();
		}

		if ((!$chpassword) || (!$passagain))
			$message = "You must enter something!";

	
		navmenu();

		if (!$message){
			SQL_Query_exec("UPDATE users SET password = " . sqlesc($chpassword) . ", secret = " . sqlesc($secret) . "  WHERE id = " . $CURUSER["id"]."") or die(mysql_error());
			autolink("index.php","".T_("PASSWORD_CHANGED_OK")."");
			logoutcookie();
		}else{
		begin_framec();
			echo "<br /><br /><b><center>".$message."</center></b><br /><br />";
			end_framec();
		}


	
		stdfoot();
		die();
	}//do

	begin_framec(T_("CHANGE_YOUR_PASS"));
	navmenu();
	?>
    
	<form method="post" action="account.php?action=changepw">
	<input type="hidden" name="do" value="newpassword" />
    <div >
	<table class='tab1' cellpadding='0' cellspacing='1' align='center'>
    <tr>
        <td class='tab1_col3' align="right"><b><?php echo T_("NEW_PASSWORD"); ?>:</b></td>
        <td class='tab1_col3' align="left"><input type="password" name="chpassword" size="50" /></td>
    </tr>
    <tr>
        <td class='tab1_col3' align="right"><b><?php echo T_("REPEAT"); ?>:</b></td>
        <td class='tab1_col3' align="left"><input type="password" name="passagain" size="50" /></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
        <input type="reset" value="<?php echo T_("REVERT"); ?>" />
        <input type="submit" value="<?php echo T_("SUBMIT"); ?>" />
        </td>
    </tr>
    </table>
    </div>
	</form>
    
	<?php
	end_framec();
}

stdfoot();
?>