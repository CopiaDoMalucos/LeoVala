<?php

############################################################
#######                                             ########
#######                                             ########
#######           Malucos-share.net 2.0             ########
#######                                             ########
#######                                             ########
############################################################
  
  require_once("backend/functions.php");
  dbconn(false);
  loggedinonly();
  
  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
      $option = (int)$_POST["option"];
      $art = $_POST["art"];

      $sql = mysql_query("SELECT * FROM `bonus` WHERE `id` = '$option'");
      $row = mysql_fetch_array($sql);
      
      if (!$row && $option != "friend")
	  show_error_msg(T_("SEED_BONUS"), 1);

           
      $points = $row["points"];
      $up     = $row["menge"];
      $inv    = $row["menge"];
      
      if ($CURUSER["seedbonus"] >= $points)
      {
          if ($art == "traffic")
          {
              mysql_query("UPDATE `users` SET `uploaded` = `uploaded` + '$up', `seedbonus` = `seedbonus` - '$points' WHERE `id` = '" . $CURUSER["id"] . "'") or exit(mysql_error());
              show_error_msg("".T_("SUCCESS")."", T_("SEED_BONUS_ATUALIZAR"), 1);
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
              mysql_query("UPDATE `users` SET `invites` = `invites` + $inv, `seedbonus` = `seedbonus` - $points WHERE `id` = '" . $CURUSER["id"] . "'");
              show_error_msg("".T_("SUCCESS")."", T_("SEED_BONUS_ATUALIZADA"), 1);
          }
          
          if ($art == "friend")
          {
              $query = mysql_query("SELECT * FROM `users` WHERE `username` = " . sqlesc($_POST["username"]) . "");
              $row   = mysql_fetch_array($query);
              
              if (!$row)
			       show_error_msg("".T_("ERRO")."", T_("SEED_BONUS_NOMEUSER"), 1);
                               
              $username = $row["username"];
              $points   = (int)$_POST["points"];
              
              mysql_query("UPDATE `users` SET `seedbonus` = `seedbonus` + $points  WHERE `id` = '" . $row["id"] . "'");
              mysql_query("UPDATE `users` SET `seedbonus` = `seedbonus`- '$points' WHERE `id` = '" . $CURUSER["id"] . "'") or exit(mysql_error());
			  	show_error_msg(T_("SUCCESS")," $points " . T_("SEED_BONUS_SUCESSO")." $username ",1);
             }
      }
      else
    show_error_msg("".T_("ERRO")."", T_("SEED_BONUS_SUFICIENTES"), 1);
  }
stdhead(T_("SEED_BONUS_MS"));
	begin_frame(T_("SISTEMAS_BONUS_MS"));
  print("<CENTER>(".T_("MS_BONUS_TEM")."<font color=#B00> " . number_format($CURUSER["seedbonus"], 2) . "</font>)".T_("MS_BONUS_PONTOS")."</CENTER><br />");
  echo("<table cellpadding='5' cellspacing='0' width='100%' align='center' class='ttable_headinner'>");
  echo("<tr>");
  echo("<td >- ".T_("MS_BONUS_PONTOS_SEMEAR")." <br> ".T_("MS_BONUS_PONTOS_UPLOAD")."
<br>  ".T_("MS_BONUS_PONTOS_AMIGOS")." <br> ".T_("MS_BONUS_PONTOS_TEMPO")." <br> ");
  

  echo("</tr>");
  echo("</table>");
  
  echo("<table cellpadding='5' cellspacing='0' width='100%' align='center' class='ttable_headinner'>");
  echo("<tr>");
  echo("<td class='ttable_head' align='center'>".T_("MS_BONUS_PONTOS_AQUI")." <br />(  ".T_("MS_BONUS_PONTOS_BOTAO")."  )</td>");
  echo("</tr>");
  echo("</table>");
  
  echo("<table cellpadding='5' cellspacing='0' width='100%' align='center' class='ttable_headinner'>");
  echo("<tr>");
  echo("<td class='ttable_head' align='center'>".T_("MS_BONUS_PONTOS_OPCAO1")."</td>");
  echo("<td class='ttable_head' align='center'>".T_("MS_BONUS_PONTOS_DESCRICAO1")."?</td>");  
  echo("<td class='ttable_head' align='center'>".T_("MS_BONUS_PONTOS")."</td>");  
  echo("<td class='ttable_head' align='center'>".T_("MS_BONUS_PONTOS_TROCAR1")."</td>");  
  echo("</tr>");

  $sql = mysql_query("SELECT * FROM `bonus` ORDER BY `id`");
  
  while ($row = mysql_fetch_array($sql))
  {
  
  
        echo("<form method='post' action='seedingbonus.php'>");
        echo("<input type='hidden' name='option' value='" . $row["id"] . "'>");
        echo("<input type='hidden' name='art' value='" . $row["art"] . "'>");
        echo("<tr>");
        echo("<td class='ttable_col1' align='center'>" . htmlspecialchars($row["bonusname"]) . "</td>");
        echo("<td class='ttable_col2' align='center'>".T_("MS_BONUS_PONTOS_BONUS1")." " . htmlspecialchars($row["description"]) . " ".T_("MS_BONUS_PONTOS_SEEDING1")." " . htmlspecialchars($row["bonusname"]) . " ".T_("MS_BONUS_PONTOS_SEEDING3")."</td>");
        echo("<td class='ttable_col1' align='center'>" . number_format($row["points"], 2) . "</td>");
        
        if ($CURUSER["seedbonus"] >= $row["points"])
            echo("<td class='ttable_col2' align='center'><input type='submit' value='".T_("MS_BONUS_PONTOS_TROCAR1")."!'></td>");
        else
            echo("<td class='ttable_col2' align='center'><input type='submit' value='".T_("MS_BONUS_PONTOS_TROCAR123")."' disabled='disabled'></td>");
            
        echo("</tr>");
        echo("</form>");
  }

  echo("</table>");
  echo("<br />");
  
  echo("<strong>".T_("MS_BONUS_PONTOS_COMO_FACO")."?</strong>");
  echo("<br />");
  echo("".T_("MS_BONUS_PONTOS_COMO_RECEBE")."");
  echo("<br />");
  echo("".T_("MS_BONUS_PONTOS_COMO_RESGATAR")."");
  echo("<br />");
  
  if ($CURUSER["seedbonus"] > 0)
      $submit = "<input type='submit' value='".T_("MS_BONUS_PONTOS_DOAR")."!'>";
  else
      $submit = "<input type='submit' value='".T_("MS_BONUS_PONTOS_DOAR")."!' disabled='disabled'>";
  
  echo("<br />");
  echo("<strong>".T_("MS_BONUS_PONTOS_DOAR_PONTOS")."!</strong>");
  echo("<form method='post' action='seedingbonus.php'>");
          echo("<input type='hidden' name='option' value='friend'>");
        echo("<input type='hidden' name='art' value='friend'>");
  echo("<table cellpadding='5' cellspacing='0' width='100%' align='center' class='ttable_headinner'>");
  echo("<tr>");
  echo("<td class='ttable_head' align='center'>".T_("MS_BONUS_PONTOS_DOAR_USUARIO").": <input type='text' name='username' size='30'/>".T_("MS_BONUS_PONTOS")." : <input type='text' name='points' size='4'> $submit</td>");
  echo("</tr>");
  echo("</table>");
  echo("</form>");
  
  end_frame();
  stdfoot();
  
?>