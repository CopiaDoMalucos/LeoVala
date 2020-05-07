<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
dbconn();

if ($site_config["MEMBERSONLY"])
    loggedinonly();

stdhead("Mensagens Para a Staff");
begin_framec("Enviar uma mensagem para a Staff");
if((!(isset($_POST["msg"]))) & (!(isset($_POST["sub"]))))
{
?>

	
	<div align="justify" class="framecentro"><form action="falarstaff.php" method="POST" name="message"  >

<table cellspacing="1" cellpadding="0" align="center" class="tab1">
<tbody><tr><td align="center" class="tab1_cab1">Mensagem para Staff</td></tr>

       <tr>
              <td align="center" class="tab1_col2">Assunto:<input type="text" size="83" style="width: 60%;" name="sub" ></td>
                </tr>      
<tr><td align="center" class="tab1_col2">Mensagem:<textarea style="width: 60%; height: 100px;" size="83" name="msg"></textarea></td></tr>

	<tr><td align="center" class="tab1_col1"><input type="submit"  size="83" value="Confirmar" style="width:150px; height: 30px;"></td></tr></tbody></table></form></div>
<?php

}
else
{
    $msg = trim($_POST["msg"]);
    $sub = trim($_POST["sub"]);
    

    $error_msg = "";
    if (!$msg)
        $error_msg = $error_msg . "Nenhuma mensagem</br>";
    if (!$sub)
        $error_msg =  $error_msg . "Sem assunto</br>";
        
    if($error_msg != "")
    {
        echo "<center><h3 style=\"color:red;\">Sua mensagem não pôde ser enviada:</br></br>";
        echo $error_msg . "</h3></br></br><a href=\"falarstaff.php\">Voltar</a></center>";
    }
    else
    {
        $sql_msg = sqlesc($msg);
        $sql_sub = sqlesc($sub);
        $added = "'" . get_date_time() . "'";
        $userid = $CURUSER['id'];
        $REQ = mysql_query("INSERT INTO staffmessages (sender, added, msg, subject) VALUES($userid, $added, $sql_msg, $sql_sub)");
        if($REQ)
        {
            echo "<center><h3 style=\"color:green;\">Sua mensagem foi enviada com sucesso</h3></br></br><a href=\"index.php\">Voltar</a></center>";
        }
        else
        {
            echo "<center><h3 style=\"color:red;\">Erro ao enviar mensagem. Por favor, tente mais tarde</h3></br></br><a href=\"index.php\">Back</a></center>";
        }
    }
    
}
end_framec();
stdfoot();
?>