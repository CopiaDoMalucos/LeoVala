<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################

require "backend/functions.php";
require ("backend/conexao.php");
dbconn();
loggedinonly();

global $CURUSER;
$pdo = conectar();

	
stdhead("Pedido apagar");


if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador"){
		  
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (count($_POST['id']))
            {
                foreach ($_POST['id'] as $key)
                {
                    if (is_valid_id($key))
                    {
                        $status = $_POST["status$key"];
                        
                        if ($status == 'aceita'){
							 
                             $res2 = $pdo->prepare("SELECT id, request, descr, userid, torrid, atenid FROM requests WHERE  id = :id"); 
                             $res2->bindParam(':id', $key );
                             $res2->execute(); 
				             $row = $res2->fetch(PDO::FETCH_ASSOC);
							 
							 $select_row=$pdo->prepare("SELECT id, name, owner FROM torrents WHERE id= :id");
                             $select_row->bindParam(':id', $row["torrid"]);
                             $select_row->execute();
	                         $row_select = $select_row->fetch(PDO::FETCH_ASSOC);  
                             
							 $select_posts=$pdo->prepare("UPDATE users SET  seedbonus= seedbonus + '100', quantpe= quantpe + '1' where id= :id");
                             $select_posts->bindParam(':id', $row_select["owner"]);
                             $select_posts->execute();							 
	$msg = "Prezado usuário,\n

Você fez um pedido de torrent que acaba de ser atendido. \n

Nome do pedido: ".$row["request"]." \n
Torrent lançado: [url=$site_config[SITEURL]/torrents-details.php?id=" . $row_select["id"] . "][b]" . $row_select["name"] . "[/b][/url] \n

Atenciosamente,\n

Equipe BR \n";
    
    $added = get_date_time();
    $subject  = "Pedidos";
	
	$row_user=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_user->bindParam(':userid', $row["userid"]);
    $row_user->bindParam(':added', $added);
	$row_user->bindParam(':msg', $msg);
    $row_user->bindParam(':subject', $subject);				  
    $row_user->execute();   

		 $select_row1=$pdo->prepare("SELECT userid FROM addedrequests WHERE requestid= :id");
                             $select_row1->bindParam(':id', $key);
                             $select_row1->execute();

					while ($row_select1 = $select_row1->fetch(PDO::FETCH_ASSOC)) 
{		 
	$msg1 = "Prezado usuário,\n

Você votou num pedido de torrent que acaba de ser atendido. \n

Nome do pedido: ".$row["request"]." \n
Torrent lançado: [url=$site_config[SITEURL]/torrents-details.php?id=" . $row_select["id"] . "][b]" . $row_select["name"] . "[/b][/url] \n

Atenciosamente,\n

Equipe BR \n";
    
    $added1 = get_date_time();
    $subject1  = "Pedidos";
	
	$row_user1=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_user1->bindParam(':userid', $row_select1["userid"]);
    $row_user1->bindParam(':added', $added1);
	$row_user1->bindParam(':msg', $msg1);
    $row_user1->bindParam(':subject', $subject1);				  
    $row_user1->execute();  
	}
	
		$msg2 = "Prezado usuário,\n

O torrent que você indicou [url=$site_config[SITEURL]/torrents-details.php?id=" . $row_select["id"] . "][b]" . $row_select["name"] . "[/b][/url] para atender ao seguinte pedido: ".$row["request"]." correspondia com o que o usuário desejava, por isso o pedido será atendido.. \n

Atenciosamente, \n

Equipe BR \n";
    
    $added2 = get_date_time();
    $subject2  = "Pedidos";
	
	$row_user2=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_user2->bindParam(':userid', $row["atenid"]);
    $row_user2->bindParam(':added', $added2);
	$row_user2->bindParam(':msg', $msg2);
    $row_user2->bindParam(':subject', $subject2);				  
    $row_user2->execute();  
	
			 $select_user=$pdo->prepare("SELECT id, username FROM users WHERE id= :id");
                             $select_user->bindParam(':id', $row["userid"]);
                             $select_user->execute();
	                         $row_user = $select_user->fetch(PDO::FETCH_ASSOC);  
							 
			$msgbo = "Prezado Usuário,\n

Em atendimento ao pedido de torrent  [url=$site_config[SITEURL]/torrents-details.php?id=" . $row_select["id"] . "][b]" . $row_select["name"] . "[/b][/url] , do usuário: [url=$site_config[SITEURL]/account-details.php?id=" . $row_user["id"] . "][b]" . $row_user["username"] . "[/b][/url]. \n

validado pela moderação do site, está sendo acrecido 100 BR bonus a sua conta, pelo atendimento do pedido. \n

Obrigado,\n
Equipe BR \n";
    
    $addedbonus = get_date_time();
    $subjebonus  = "Pedidos";
	
	$row_bonus=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_bonus->bindParam(':userid', $row_select["owner"]);
    $row_bonus->bindParam(':added', $addedbonus);
	$row_bonus->bindParam(':msg', $msgbo);
    $row_bonus->bindParam(':subject', $subjebonus);				  
    $row_bonus->execute();  
	
			 $select_row3=$pdo->prepare("SELECT userid FROM addedrequests WHERE requestid= :id");
                             $select_row3->bindParam(':id', $key);
                             $select_row3->execute();

					while ($row_select3 = $select_row3->fetch(PDO::FETCH_ASSOC)) 
					{

$delprepare2 = "DELETE FROM addedrequests WHERE requestid IN ($key)"; 
$del2 = $pdo->prepare($delprepare2); 
$del2->execute(); 
}
	$delprepare = "DELETE FROM requests WHERE id IN ($key)"; 
$del1 = $pdo->prepare($delprepare); 
$del1->execute(); 
}
                        if ($status == 'retira'){

							 $res5 = $pdo->prepare("SELECT id, request, descr, userid, torrid, atenid FROM requests WHERE  id = :id"); 
                             $res5->bindParam(':id', $key );
                             $res5->execute(); 
				    
							 					while ($row5 = $res5->fetch(PDO::FETCH_ASSOC)) 
{	
							 $select_row5=$pdo->prepare("SELECT id, name, owner FROM torrents WHERE id= :id");
                             $select_row5->bindParam(':id', $row5["torrid"]);
                             $select_row5->execute();
	                         $row_select5 = $select_row5->fetch(PDO::FETCH_ASSOC);  
							 
								$msgn = "Prezado usuário,\n
								O torrent que você indicou [url=$site_config[SITEURL]/torrents-details.php?id=" . $row_select5["id"] . "][b]" . $row_select5["name"] . "[/b][/url] para atender ao seguinte pedido: ".$row5["request"]." não correspondia com o que o usuário desejava, por isso o pedido não será atendido. \n

Atenciosamente, \n

Equipe BR \n";
    
    $added5 = get_date_time();
    $subject5  = "Pedidos";
	
	$row_user5=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msgn,:subject)");
	$row_user5->bindParam(':userid', $row5["atenid"]);
    $row_user5->bindParam(':added', $added5);
	$row_user5->bindParam(':msgn', $msgn);
    $row_user5->bindParam(':subject', $subject5);				  
    $row_user5->execute();   
							 
							 $select_posts1=$pdo->prepare("UPDATE requests SET  liberado = 'no', torrid = '0'  where id= :id"); 
                             $select_posts1->bindParam(':id', $key);
                             $select_posts1->execute();			 
							 }
							 
}
}
}

		show_error_msg("Sucesso", 'Torrent verificado com sucesso!<br>


<br>Obrigado!.

<br><a href=pedido_liberacao.php><b>Continuar</b></a>', 1);

}
}
}else
{

			show_error_msg("Erro", 'Ops acesso negado', 1);	
		
		
		}




stdfoot();



?>
