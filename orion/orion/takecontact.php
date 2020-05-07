<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
if ($HTTP_SERVER_VARS["REQUEST_METHOD"] != "POST")
 show_error_msg("Error", "Method");
   dbconn();
       $msg = trim($_POST["msg"]);
       $subject = trim($_POST["subject"]);
       if (!$msg)
    show_error_msg("Erro","Escreva alguma coisa!");
       if (!$subject)
    stderr("Erro","Você deve escrever um assunto!");
     $added = "'" . get_date_time() . "'";
     $userid = $CURUSER['id'];
     $message = sqlesc($msg);
     $subject = sqlesc($subject);
 SQL_Query_exec("INSERT INTO staffmessages (sender, added, msg, subject) VALUES($userid, $added, $message, $subject)") or sqlerr(__FILE__, __LINE__);
       if ($_POST["returnto"])
 {
   header("Location: " . $_POST["returnto"]);
   die;
 }
  stdhead();
  show_error_msg("Completo", "A mensagem foi enviada com sucesso!");
       stdfoot();
       exit;
?>