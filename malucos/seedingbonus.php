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
require ("backend/conexao.php");

$pdo = conectar();

  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
      $option = (int)$_POST["option"];
      $art = $_POST["art"];

		$niveau = $CURUSER['points_bbcode'];
		$iduser =    $CURUSER["id"]; 	
		  $doapontos  = (int)$_POST["points"];  
	      $namesuer =     $_POST['username'];


// Preparando statement 
$stmt = $pdo->prepare("SELECT * FROM bonus WHERE id = ?"); 

   $stmt->bindParam(1,$option);

// Executando statement 
$stmt->execute(); 

// Obter linha consultada 

   $row = $stmt->fetch(PDO::FETCH_ASSOC);  
   	   $bbcode = $row['menge']; 
	    $points = $row["points"];
        $up     = $row["menge"];
        $inv    = $row["menge"];
 

		
		
$stm = $pdo->query( 'SELECT COUNT(*) AS `id` FROM `icone`;' );



$niveaumax = (int) $stm->fetchColumn( 0 );
		

	


 
          if ($art == "traffic")
          {
		  
	

		  if ($points > $CURUSER["seedbonus"])
			  show_error_msg("".T_("ERRO")."","Você não tem pontos suficiente", 1);
			  

$select_posts=$pdo->prepare("UPDATE users SET uploaded= uploaded + :uploaded, seedbonus= seedbonus - :seedbonus where id= :id");
$select_posts->bindParam(':uploaded', $up);
$select_posts->bindParam(':seedbonus', $points);
$select_posts->bindParam(':id', $iduser);
$select_posts->execute();
			
            show_error_msg("".T_("SUCCESS")."", T_("SEED_BONUS_ATUALIZADA"), 1);
          }
			  
		 if ($art == "bbcode" && $niveau < $niveaumax )
          { 
		  	  if ($points > $CURUSER["seedbonus"])
			  show_error_msg("".T_("ERRO")."","Você não tem pontos suficiente", 1);
			  
$select_bbcode=$pdo->prepare("UPDATE users SET points_bbcode= points_bbcode + :bbcode, seedbonus= seedbonus - :seedbonus where id= :id");
$select_bbcode->bindParam(':bbcode', $bbcode);
$select_bbcode->bindParam(':seedbonus', $points);
$select_bbcode->bindParam(':id', $iduser);
$select_bbcode->execute();
				 show_error_msg("Success", "Account updated.");
              }
              if ($art == "bbcode" &&  $niveau >= $niveaumax){
			       show_error_msg("".T_("ERRO")."", T_("MAXIMO_DE_BBCODE_ERRO"), 1);
                               

  }			
        if ($art == "invite")
          {
              if ($CURUSER["uploaded"] > 0 && $CURUSER["downloaded"] == 0)
                  $userratio = "Inf.";
              elseif ($CURUSER["downloaded"] > 0)
                  $userratio = number_format($CURUSER["uploaded"] / $CURUSER["downloaded"], 2);
              else
                  $userratio = "---";
                  
              if ($userratio == "---" || $userratio == "Inf." || $userratio < "0.90")
              {
			  show_error_msg("".T_("ERRO")."", T_("SEED_BONUS_RATIO"), 1);
                
              }
	  if ($points > $CURUSER["seedbonus"])
			  show_error_msg("".T_("ERRO")."","Você não tem pontos suficiente", 1);
			  
			  $select_invite=$pdo->prepare("UPDATE users SET invites= invites + :invites, seedbonus= seedbonus - :seedbonus where id= :id");
              $select_invite->bindParam(':invites', $inv);
              $select_invite->bindParam(':seedbonus', $points);
              $select_invite->bindParam(':id', $iduser);
              $select_invite->execute();
			  
              show_error_msg("".T_("SUCCESS")."", T_("SEED_BONUS_ATUALIZADA"), 1);
          }
          
          if ($art == "friend")
          {
            
$select_row = $pdo->prepare("SELECT * FROM users WHERE username = :username"); 
$select_row->bindParam(':username', $_POST['username']);
$select_row->execute(); 
   $row_select = $select_row->fetch(PDO::FETCH_ASSOC);  


              if (!$row_select)
			       show_error_msg("".T_("ERRO")."", T_("SEED_BONUS_NOMEUSER"), 1);
                            
              $username = $row_select["username"];
            $userid = $row_select["id"];
			  
              if ($row_select["id"]  ==  $CURUSER["id"]  )
			  show_error_msg("".T_("ERRO")."",T_("SEED_BONUS_NOMEUSER1"), 1);
			  
			  if ($doapontos > $CURUSER["seedbonus"])
			  show_error_msg("".T_("ERRO")."","Você não tem pontos suficiente", 1);
			  
			if ($doapontos == '50' || $doapontos == '100' || $doapontos == '200' || $doapontos == '300' || $doapontos == '500' || $doapontos == '600' || $doapontos == '1000'){
			   
			  $select_bonus=$pdo->prepare("UPDATE users SET seedbonus= seedbonus + :seedbonus where id= :id");
              $select_bonus->bindParam(':seedbonus', $doapontos);
              $select_bonus->bindParam(':id', $row_select['id']);
              $select_bonus->execute();

			  $select_bonus1=$pdo->prepare("UPDATE users SET seedbonus= seedbonus - :seedbonus where id= :idco");
              $select_bonus1->bindParam(':seedbonus', $doapontos);
			  $select_bonus1->bindParam(':idco', $iduser);
              $select_bonus1->execute();
			  write_logstaff("Bonus","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url] enviou ".$doapontos." pontos para o [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$username."[/url] \n");

			  $msg = "Prezado usuário,\n\nO usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url] fez uma doação de ".$doapontos." MS Pontos para você!\n\nOs pontos já foram adicionados em sua conta.\n\n Atenciosamente,\n\n Equipe MS ";

			  
			      $added = get_date_time();
    $subject  = "Promovido";
	
	$mpbonus=$pdo->prepare("INSERT INTO messages (poster, sender, receiver, msg, added,subject) VALUES ('0','0', " . $userid . ", " .sqlesc($msg) . ", '" . get_date_time() . "','MS Pontos!')");			  
    $mpbonus->execute();   
			  

			  	show_error_msg(T_("SUCCESS")," $doapontos " . T_("SEED_BONUS_SUCESSO")." $username ",1);
	
			}
				
				else{
				
							show_error_msg("".T_("ERRO")."","erro", 1);
				}
             }
      

  
  
  }
  
stdhead(T_("SEED_BONUS_MS"));
	begin_framec(T_("SISTEMAS_BONUS_MS"));
  print("<CENTER>(".T_("MS_BONUS_TEM")."<font color=#B00> " . number_format($CURUSER["seedbonus"], 2) . "</font>)".T_("MS_BONUS_PONTOS")."</CENTER><br />");
  echo("<table>");
  echo("<tr>");
  echo("<td ><b><font size='2'>Como funciona?</font></b><br><br> 

A cada <b>1 hora</b> que você estiver semeando arquivos no MS, você ganhará <b>1 MS Ponto</b>. Você também pode ganhar MS Pontos fazendo lançamentos de torrents: 2 MS Pontos (arquivos até 10mb); 4 MS Pontos (arquivos até 100mb); 5 MS Pontos (arquivos acima de 100mb).
<br><br>
Acumulando MS Pontos, você poderá trocá-los por <b>GB de upload</b> ou <b>convites</b>!<br><br><b><font size='2'>Observações</font></b><br><br> 
Aqui você pode trocar seus Pontos, ( Após escolher basta clicar no botão trocar , aguarde alguns instantes para que o sistema atualize o banco de dados e pronto. se o botão de troca estiver inativo, é porque você não tem a quantidade de pontos suficientes para a troca. Trocas de pontos por convites, somente ocorrerão se seu ratio estiver acima de 0.90, ratios inferiores a 0.90 fica apenas permitido trocas por GB de upload) ");
  

  echo("</tr>");
  echo("</table>");
   echo("<br><br>");

  
  echo("<table id='tabela1' cellpadding='0' cellspacing='1'  width='100%' align='center'>");
  echo("<tr>");
  echo("<td class='tab1_cab1' align='center' colspan='3'>Trocar pontos por upload/convites/BBcode</td>");
    echo("</tr>");
    echo("<tr>");
  echo("<td class='ttable_head'  align='center'>Aquisição</td>");

  echo("<td class='ttable_head'  align='center'>Pontos necessários</td>");  

  echo("<td class='ttable_head'  align='center' width='25'>".T_("MS_BONUS_PONTOS_TROCAR1")."</td>");  


      echo("</tr>");

$sql = $pdo->query("SELECT * FROM bonus ORDER BY id");
	
	while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
  
        echo("<form method='post' action='seedingbonus.php'>");
        echo("<input type='hidden' name='option' value='" . $row["id"] . "'>");
        echo("<input type='hidden' name='art' value='" . $row["art"] . "'>");
        echo("<tr>");
        echo("<td class='tab1_col3' align='center'><b>" . htmlspecialchars($row["bonusname"]) . "</b></td>");
        echo("<td class='tab1_col3' align='center'><b>" . htmlspecialchars($row["description"]). "</b></td>");
        
        if ($CURUSER["seedbonus"] >= $row["points"] )
            echo("<td class='tab1_col3' align='center'><input type='submit' value='".T_("MS_BONUS_PONTOS_TROCAR1")."!'></td>");
        else
            echo("<td class='tab1_col3' align='center'><input type='submit' value='".T_("MS_BONUS_PONTOS_TROCAR123")."' disabled='disabled'></td>");
            
			
        echo("</tr>");
        echo("</form>");
  }

  echo("</table>");
  echo("<br>");
    echo("<table id='tabela1' cellpadding='0' cellspacing='1'  width='100%' align='center'>");
  echo("<td class='tab1_cab1' align='center' colspan='3'>Doar MS Pontos</td>");
  
  if ($CURUSER["seedbonus"] > 99)
      $submit = "<input type='submit' value='      Doar!      '>";
  else
      $submit = "<input type='submit' value='      Doar!      ' disabled='disabled'>";
  
  echo("<br>");
  echo("<form method='post' action='seedingbonus.php'>");
          echo("<input type='hidden' name='option' value='".$CURUSER["id"]."'>");
        echo("<input type='hidden' name='art' value='friend'>");

  echo("<tr>");

  echo("<td class='tab1_col3' align='center'>Doar <select name='points' id='points'><option value='50'>50</option> 
  <option value='100'>100</option>
  <option value='200' >200</option>
  <option  value='300'>300</option>
  <option value='500'>500</option>
    <option value='600'>600</option>
	  <option value='1000'>1000</option>
</select> MS Pontos para o usuário <input type='text' name='username' size='30'/></td>");
echo("</tr>");
echo("<tr>");
  echo("<td class='tab1_col3' align='center'>$submit</td>");
  echo("</tr>");
  echo("</table>");
  echo("</form>");
  
  
  
  end_framec();
  stdfoot();

  
  
  
  
?>