<?php
 ############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################


 require_once("backend/functions.php"); 
 dbconn(); 
 loggedinonly();
 
 $id = (int) $_GET["id"];
 $res = SQL_Query_exec("SELECT * FROM teams WHERE id = '$id'");
 $row = mysql_fetch_assoc($res);
                                       
 if (mysql_num_rows($res) == 0)
     show_error_msg("Error", "Esse grupo não existe..", 1);
 
   $resultsub1 = SQL_Query_exec("SELECT * FROM usergroups WHERE uid = ".$CURUSER['id']." AND gid = ".$CURUSER['team']."") ;
$row21 = mysql_fetch_array($resultsub1);	
if ($row21["gid"] !=  $id ){
     show_error_msg("Error", "Você não tem acesso a este grupo", 1);
}
  

       
 if ($row21["status"] == 'moderadores' || $row21["status"] == 'submoderadores' )
{ 

    stdhead("Membros do grupo");
begin_framec("Membros do grupo");


 if ($_GET['do'] == "ok") { 
		if (!@count($_POST["ok"])) {
				print("<b><center>Nada selecionado!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
					}
		$ids = array_map("intval", $_POST["ok"]);
			$ids = implode(", ", $ids);				
                 if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (count($_POST['id']))
            {
                foreach ($_POST['id'] as $key)
                {
                    if (is_valid_id($key))
                    {    
		 $status = $_POST["status$key"];
		 
			
 $res1234 = mysql_query("SELECT * FROM grupoaceita LEFT JOIN users ON grupoaceita.iduser = users.id WHERE users.id = $key AND grupoaceita.simounao = 'no' AND grupoaceita.iduser IN ($ids) LIMIT 1");    
         	 $user = mysql_fetch_array($res1234);
			 
 $userid = $user["iduser"] ;
 
 $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $id . "");
             $row = mysql_fetch_array($sql);
			 

        if ($status == 'promover'){

                       $hash = md5(mt_rand(1,1000000));
		               mysql_query("UPDATE `grupoaceita` SET `simounao`='yes', invite = '$hash'  WHERE `iduser` = $userid AND `idteam` = $id  ");
		   						$message = "Você foi selecionado para ser membro do grupo ".htmlspecialchars($row['name']).", \n Para aceitar e fazer parte do grupo clique em  [url=$site_config[SITEURL]/confirmagrupo.php?convite=" . $hash . "][size=2][color=green]Sim[/color][/size][/url]. \n Staff - " . htmlspecialchars($row['name'])."";
                    			$dt = sqlesc(get_date_time()); 
                                $sub = sqlesc($row['name'] . ' Grupo');
                                $msg = sqlesc($message);
  			                    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES(0, $userid, $dt, $msg, 0, $sub)");
					   }
        if ($status == 'retira'){
							$delretira = "DELETE FROM `grupoaceita` WHERE `idteam` = $id AND simounao = 'no' AND  `iduser` = $userid  ";
							$delretirav = mysql_query($delretira);
		   						$message = 'Desculpa mas você não foi aceito pelo grupo - ' . htmlspecialchars($row['name']);
                    			$dt = sqlesc(get_date_time()); 
                                $sub = sqlesc($row['name'] . ' Grupo');
                                $msg = sqlesc($message);
  			                    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES(0, $userid, $dt, $msg, 0, $sub)");
					   }					   
					   }
					 print("<b><center>Update sucesso!!!<a href='grupo_pendentes.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
					   }
					   }

}
}



    $resultado= mysql_query("SELECT * FROM grupoaceita LEFT JOIN users ON grupoaceita.iduser = users.id WHERE grupoaceita.simounao = 'no' AND grupoaceita.idteam = $id ");    
?>	
	<script language="JavaScript" type="text/Javascript">
		function checkAll(box) {
			var x = document.getElementsByTagName('input');
			for(var i=0;i<x.length;i++) {
				if(x[i].type=='checkbox') {
					if (box.checked)
						x[i].checked = false;
					else
						x[i].checked = true;
				}
			}
			if (box.checked)
				box.checked = false;
			else
				box.checked = true;
		}
	</script>


	<?php
        if (mysql_num_rows($resultado) > 0)
        {	 

            echo("<table border='1' cellpadding='5' cellspacing='3' align='center' width='100%' class='ttable_headinner'>");
            echo("<tr>");
			echo("<td class='ttable_head' width='1%' align='center'><input type='checkbox' id='checkAll' onclick='checkAll(this)'></td>");
            echo("<td class='ttable_head' width='20%'><strong>Membros</strong></td>");
            echo("<td class='ttable_head' width='20%'><strong>Pediu para se juntar</strong></td>");
			   echo("<td class='ttable_head' width='40%'><strong>Motivo</strong></td>");
            echo("<td class='ttable_head' width='10%'><strong>Aceita</strong></td>");
            echo("</tr>");
     while ($roww = mysql_fetch_array($resultado))
            {

				echo("<form method='post' action='grupo_pendentes.php?id=$id&do=ok'>");
                echo("<input type='hidden' name='id[]' value='" . $roww["id"] . "'>");  
                
                echo("<tr>");
				echo("<td class='ttable_col2' width='1%' align='center'><input type='checkbox' name='ok[]' value='$roww[id]'></td>");
                echo("<td class='tab1_col2' width='20%'><a href='account-details.php?id=" . $roww['id'] . "'>" . htmlspecialchars($roww["username"]) . "</a></td>");  
                echo("<td class='tab1_col2' width='20%'>" . utc_to_tz($roww['datepedi']) . "</td>");
				echo("<td class='tab1_col2' width='40%'>" . $roww['motivo'] . "</td>");
                echo("<td class='tab1_col2' width='10%'><select name='status" . $roww['id'] . "'><option value=''>Escolher</option><option value='promover'>Sim</option><option value='retira'>Não</option></select></td>");       
                echo("</tr>");  
}

          echo("<tr>");
            echo("<td align='center' class='tab1_col2' colspan='5'><input type='reset' value='Redefinir'/> <input type='submit' value='Salvar alterações'/></td>");
            echo("</tr>");
            echo("</table>");
            echo("</form>");

     }
		   else {
           echo('<center>Nao temos membros no pendentes</center>'); 
        }
 print("<a href=painel_grupos.php?id=$id><font color=#FF0000><CENTER><b>[Voltar painel de grupo]</b></CENTER></font></a>");
		 end_framec();
}else{
      show_error_msg("Error", "Você não tem permissão para isso.", 1);
}
 
 
 stdfoot();
 
?>