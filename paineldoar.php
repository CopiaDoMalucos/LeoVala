<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################

 require_once("backend/functions.php"); 

 dbconn(); 
 loggedinonly();
  require ("backend/conexao.php");
 $pdo = conectar();
 

if ($CURUSER["id"] !=  1 ){
   show_error_msg("Error", "Você não tem permissão para isso.", 1);
}
  

  stdhead("Painel doação");
  begin_framec("Painel doação");
  
  
  
  
  
  
   $action = $_REQUEST["action"] ;
  if ($action=="vipdeletar"){
  if ($_GET['do'] == "apa") { 
		if (!@count($_POST["apa"])) {
				print("<b><center>Nada selecionado!!!<a href='paineldoar.php?action=confirmados&do=ok'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
					}
		$ids = array_map("intval", $_POST["apa"]);
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
			
					   if ($status == 'apagar'){  
					   
					   	$select_row = $pdo->prepare("SELECT * FROM donatevip WHERE uid IN (:userid) LIMIT 1"); 
                        $select_row->bindParam(':userid', $ids);
                        $select_row->execute();  
                  while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {
								
							$row_plano5=$pdo->prepare("UPDATE site_settings SET donations = donations - :valor");
                            $row_plano5->bindParam(':valor', $row_select["valor"]);
                            $row_plano5->execute();	
						
							$expiretime = "000-00-00 00:00:00";
							$freeleechuser = 'no';		
                            $donated = "0.00";
							$row_plano5_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded - :uploaded, invites = invites - :invites, seedbonus = seedbonus - :seedbonus, total_donated = total_donated - :total_donated, donated = :donated  WHERE id = :userid LIMIT 1");
                            $row_plano5_user->bindParam(':expiretime', $expiretime);
							$row_plano5_user->bindParam(':freeleechuser', $freeleechuser);
                            $row_plano5_user->bindParam(':uploaded', $row_select["gigas"]);
                            $row_plano5_user->bindParam(':invites', $row_select["convites"]);	
	                        $row_plano5_user->bindParam(':seedbonus', $row_select["pontos"]);
	                        $row_plano5_user->bindParam(':total_donated', $row_select["valor"]);	
	                        $row_plano5_user->bindParam(':donated', $donated);							
	                        $row_plano5_user->bindParam(':userid', $row_select["uid"]);
                            $row_plano5_user->execute();
						
						    $msg = "Prezado usuário, \n\n O seu plano vip acaba de ser cancelado, \n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares Share. ";

                            $added = get_date_time();
                            $subject  = "Doação BRShares VIP";
							$row_plano_user10=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	                        $row_plano_user10->bindParam(':userid', $row_select["uid"]);
                            $row_plano_user10->bindParam(':added', $added);
	                        $row_plano_user10->bindParam(':msg', $msg);
                            $row_plano_user10->bindParam(':subject', $subject);				  
                            $row_plano_user10->execute(); 
			                
							$delretira = "DELETE FROM donatevip WHERE uid = ".$row_select["uid"]." LIMIT 1 ";
						    $delretirav = mysql_query($delretira);
							}
							
					 	                       }


											   }
					   }
					 print("<b><center>Update sucesso!!!<a href='paineldoar.php?action=confirmados&do=ok'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
					   }
					   }
} 
}
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  ////////////////////////////////////////////////
  $action = $_REQUEST["action"] ;
  if ($action=="aprovar"){
  if ($_GET['do'] == "del") { 
		if (!@count($_POST["del"])) {
				print("<b><center>Nada selecionado!!!<a href='paineldoar.php'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
					}
		$ids = array_map("intval", $_POST["del"]);
			$ids = implode(", ", $ids);				
                 if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (count($_POST['id']))
            {
                foreach ($_POST['id'] as $key)
                {
                    if (is_valid_id($key))
                    {
$points5   = '5';
$inv5   = '0';
$gigas5   = '5368709120';
$ed_donations5 = '5';
///
$points10  = '25';
$inv10   = '1';
$gigas10   = '10737418240';
$ed_donations10 = '10';
///
$points20   = '30';
$inv20   = '3';
$gigas20   = '26843545600';
$ed_donations20 = '20';
///
$points50   = '50';
$inv50   = '5';
$gigas50   = '32212254720';
$ed_donations50 = '50';
///
$points15   = '30';
$inv15   = '2';
$gigas15   = '16106127360';
$ed_donations15 = '15';
///
$points25   = '45';
$inv25   = '4';
$gigas25   = '21474836480';
$ed_donations25 = '25';
///
$points30   = '60';
$inv30   = '8';
$gigas30   = '32212254720';
$ed_donations30 = '30';
///
$points70   = '90';
$inv70  = '12';
$gigas70   = '48318382080';
$ed_donations70 = '70';
///
$points35   = '50';
$inv35  = '5';
$gigas35   = '26843545600';
$ed_donations35 = '35';
///
$points55   = '80';
$inv55  = '10';
$gigas55  = '37580963840';
$ed_donations55 = '55';
///
$points75   = '120';
$inv75  = '15';
$gigas75   = '53687091200';
$ed_donations75 = '75';
///
$points100   = '200';
$inv100  = '25';
$gigas100   = '107374182400';
$ed_donations100 = '100';
///		
		 $status = $_POST["status$key"];
			
					   if ($status == 'apagar'){  
					   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
							$delretirav = mysql_query($delretira);
					 	                       }
////////////////////////BRShares vip mensal inicio											
					   if ($status == 'Maluco_vip_Mensal'){
	$expiretime = date('Y-m-d H:i:s', strtotime("+31 days"));	
	$freeleechuser = 'yes';		
	$expiretimemen = date('d-m-Y H:i:s', strtotime("+31 days"));
	$row_plano5=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations5");
    $row_plano5->bindParam(':ed_donations5', $ed_donations5);
    $row_plano5->execute();

	$row_plano5_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano5_user->bindParam(':expiretime', $expiretime);
	$row_plano5_user->bindParam(':freeleechuser', $freeleechuser);
    $row_plano5_user->bindParam(':uploaded', $gigas5);
    $row_plano5_user->bindParam(':invites', $inv5);	
	$row_plano5_user->bindParam(':seedbonus', $points5);
	$row_plano5_user->bindParam(':total_donated', $ed_donations5);		
	$row_plano5_user->bindParam(':userid', $ids);
    $row_plano5_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares Share. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user5=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user5->bindParam(':userid', $row_select["id"]);
    $row_plano_user5->bindParam(':added', $added);
	$row_plano_user5->bindParam(':msg', $msg);
    $row_plano_user5->bindParam(':subject', $subject);				  
    $row_plano_user5->execute();  

    $row_plano_user5=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas, valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user5->bindParam(':userid', $row_select["id"]);
	$row_plano_user5->bindParam(':username', $row_select["username"]);
    $row_plano_user5->bindParam(':added', $added);
	$row_plano_user5->bindParam(':expiretime', $expiretime);	
    $row_plano_user5->bindParam(':pontos', $points5);	
    $row_plano_user5->bindParam(':convites', $inv5);	
    $row_plano_user5->bindParam(':gigas', $gigas5);		
	$row_plano_user5->bindParam(':valor', $ed_donations5);	
	$row_plano_user5->bindParam(':status', $status);
    $row_plano_user5->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
					 	                       }	
///////////////////////////////////////////////vip mensal termina	
//////////////////////////////////////////////vip bronze inicio										   
					   if ($status == 'Maluco_vip_bronze_Mensal'){  
				
				$expiretime = date('Y-m-d H:i:s', strtotime("+31 days"));
	            $freeleechuser = 'yes';						
		        $expiretimemen = date('d-m-Y H:i:s', strtotime("+31 days"));
	$row_plano10=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations10");
    $row_plano10->bindParam(':ed_donations10', $ed_donations10);
    $row_plano10->execute();

	$row_plano10_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano10_user->bindParam(':expiretime', $expiretime);
	$row_plano10_user->bindParam(':freeleechuser', $freeleechuser);
    $row_plano10_user->bindParam(':uploaded', $gigas10);
    $row_plano10_user->bindParam(':invites', $inv10);	
	$row_plano10_user->bindParam(':seedbonus', $points10);
	$row_plano10_user->bindParam(':total_donated', $ed_donations10);	
	$row_plano10_user->bindParam(':userid', $ids);
    $row_plano10_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares Share. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user10=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user10->bindParam(':userid', $row_select["id"]);
    $row_plano_user10->bindParam(':added', $added);
	$row_plano_user10->bindParam(':msg', $msg);
    $row_plano_user10->bindParam(':subject', $subject);				  
    $row_plano_user10->execute();  

    $row_plano_user10=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas, valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user10->bindParam(':userid', $row_select["id"]);
	$row_plano_user10->bindParam(':username', $row_select["username"]);
    $row_plano_user10->bindParam(':added', $added);
	$row_plano_user10->bindParam(':expiretime', $expiretime);	
    $row_plano_user10->bindParam(':pontos', $points10);	
    $row_plano_user10->bindParam(':convites', $inv10);	
    $row_plano_user10->bindParam(':gigas', $gigas10);	
    $row_plano_user10->bindParam(':valor', $ed_donations10);		
	$row_plano_user10->bindParam(':status', $status);
    $row_plano_user10->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
		
					 	                       }	
///////////////////////////////////////////////////////////////////////// vip bronze termina	
///////////////////////////////////////////////////////////////////////// vip prata inicio										   
					   if ($status == 'Maluco_vip_prata_Mensal'){  
$expiretime = date('Y-m-d H:i:s', strtotime("+31 days"));	
$freeleechuser = 'yes';		
$expiretimemen = date('d-m-Y H:i:s', strtotime("+31 days"));	
	$row_plano20=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations20");
    $row_plano20->bindParam(':ed_donations20', $ed_donations20);
    $row_plano20->execute();

	$row_plano20_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano20_user->bindParam(':expiretime', $expiretime);
	$row_plano20_user->bindParam(':freeleechuser', $freeleechuser);	
    $row_plano20_user->bindParam(':uploaded', $gigas20);
    $row_plano20_user->bindParam(':invites', $inv20);	
	$row_plano20_user->bindParam(':seedbonus', $points20);
	$row_plano20_user->bindParam(':total_donated', $ed_donations20);	
	$row_plano20_user->bindParam(':userid', $ids);
    $row_plano20_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user20=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user20->bindParam(':userid', $row_select["id"]);
    $row_plano_user20->bindParam(':added', $added);
	$row_plano_user20->bindParam(':msg', $msg);
    $row_plano_user20->bindParam(':subject', $subject);				  
    $row_plano_user20->execute();  

    $row_plano_user20=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas, valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user20->bindParam(':userid', $row_select["id"]);
	$row_plano_user20->bindParam(':username', $row_select["username"]);
    $row_plano_user20->bindParam(':added', $added);
	$row_plano_user20->bindParam(':expiretime', $expiretime);	
    $row_plano_user20->bindParam(':pontos', $points20);	
    $row_plano_user20->bindParam(':convites', $inv20);	
    $row_plano_user20->bindParam(':gigas', $gigas20);	
    $row_plano_user20->bindParam(':valor', $ed_donations20);	
	$row_plano_user20->bindParam(':status', $status);
    $row_plano_user20->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
					 	                       }	
///////////////////////////////////////////	vip prata fim	
///////////////////////////////////////////	vip ouro inicio										   
					   if ($status == 'Maluco_vip_ouro_Mensal'){  
$expiretime = date('Y-m-d H:i:s', strtotime("+31 days"));	
$freeleechuser = 'yes';		
$expiretimemen = date('d-m-Y H:i:s', strtotime("+31 days"));		
	$row_plano50=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations50");
    $row_plano50->bindParam(':ed_donations50', $ed_donations50);
    $row_plano50->execute();

	$row_plano50_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano50_user->bindParam(':expiretime', $expiretime);
	$row_plano50_user->bindParam(':freeleechuser', $freeleechuser);	
    $row_plano50_user->bindParam(':uploaded', $gigas50);
    $row_plano50_user->bindParam(':invites', $inv50);	
	$row_plano50_user->bindParam(':seedbonus', $points50);
	$row_plano50_user->bindParam(':total_donated', $ed_donations50);
	$row_plano50_user->bindParam(':userid', $ids);
    $row_plano50_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRSharesShare. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user50=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user50->bindParam(':userid', $row_select["id"]);
    $row_plano_user50->bindParam(':added', $added);
	$row_plano_user50->bindParam(':msg', $msg);
    $row_plano_user50->bindParam(':subject', $subject);				  
    $row_plano_user50->execute();  

    $row_plano_user50=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas, valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user50->bindParam(':userid', $row_select["id"]);
	$row_plano_user50->bindParam(':username', $row_select["username"]);
    $row_plano_user50->bindParam(':added', $added);
	$row_plano_user50->bindParam(':expiretime', $expiretime);	
    $row_plano_user50->bindParam(':pontos', $points50);	
    $row_plano_user50->bindParam(':convites', $inv50);	
    $row_plano_user50->bindParam(':gigas', $gigas50);	
    $row_plano_user50->bindParam(':valor', $ed_donations50);		
	$row_plano_user50->bindParam(':status', $status);
    $row_plano_user50->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
					 	                       }
//////////////////////////////////////////// maluco vip ouro fim
//////////////////////////////////////////// maluco vip trimestral
					   if ($status == 'Maluco_vip_Trimestral'){  
$expiretime = date('Y-m-d H:i:s', strtotime("+93 days"));	
$freeleechuser = 'yes';	
$expiretimemen = date('d-m-Y H:i:s', strtotime("+93 days"));	
	$row_plano15=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations15");
    $row_plano15->bindParam(':ed_donations15', $ed_donations15);
    $row_plano15->execute();

	$row_plano15_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano15_user->bindParam(':expiretime', $expiretime);
	$row_plano15_user->bindParam(':freeleechuser', $freeleechuser);	
    $row_plano15_user->bindParam(':uploaded', $gigas15);
    $row_plano15_user->bindParam(':invites', $inv15);	
	$row_plano15_user->bindParam(':seedbonus', $points15);
	$row_plano15_user->bindParam(':total_donated', $ed_donations15);
	$row_plano15_user->bindParam(':userid', $ids);
    $row_plano15_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user15=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user15->bindParam(':userid', $row_select["id"]);
    $row_plano_user15->bindParam(':added', $added);
	$row_plano_user15->bindParam(':msg', $msg);
    $row_plano_user15->bindParam(':subject', $subject);				  
    $row_plano_user15->execute();  

    $row_plano_user15=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas, valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user15->bindParam(':userid', $row_select["id"]);
	$row_plano_user15->bindParam(':username', $row_select["username"]);
    $row_plano_user15->bindParam(':added', $added);
	$row_plano_user15->bindParam(':expiretime', $expiretime);	
    $row_plano_user15->bindParam(':pontos', $points15);	
    $row_plano_user15->bindParam(':convites', $inv15);	
    $row_plano_user15->bindParam(':gigas', $gigas15);
    $row_plano_user15->bindParam(':valor', $ed_donations15);	
	$row_plano_user15->bindParam(':status', $status);
    $row_plano_user15->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
					 	                       }
//////////////////////////////////////////// BRSHARES vip trimestral fim
/////////////////////////////////////////////////BRSHARES vip bronze trimenstral inicio
					   if ($status == 'Maluco_vip_bronze_Trimestral'){  
$expiretime = date('Y-m-d H:i:s', strtotime("+93 days"));	
$freeleechuser = 'yes';	
$expiretimemen = date('d-m-Y H:i:s', strtotime("+93 days"));		
	$row_plano25=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations25");
    $row_plano25->bindParam(':ed_donations25', $ed_donations25);
    $row_plano25->execute();

	$row_plano25_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano25_user->bindParam(':expiretime', $expiretime);
	$row_plano25_user->bindParam(':freeleechuser', $freeleechuser);	
    $row_plano25_user->bindParam(':uploaded', $gigas25);
    $row_plano25_user->bindParam(':invites', $inv25);	
	$row_plano25_user->bindParam(':seedbonus', $points25);
	$row_plano25_user->bindParam(':total_donated', $ed_donations25);
	$row_plano25_user->bindParam(':userid', $ids);
    $row_plano25_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares Share. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user25=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user25->bindParam(':userid', $row_select["id"]);
    $row_plano_user25->bindParam(':added', $added);
	$row_plano_user25->bindParam(':msg', $msg);
    $row_plano_user25->bindParam(':subject', $subject);				  
    $row_plano_user25->execute();  

    $row_plano_user25=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas, valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user25->bindParam(':userid', $row_select["id"]);
	$row_plano_user25->bindParam(':username', $row_select["username"]);
    $row_plano_user25->bindParam(':added', $added);
	$row_plano_user25->bindParam(':expiretime', $expiretime);	
    $row_plano_user25->bindParam(':pontos', $points25);	
    $row_plano_user25->bindParam(':convites', $inv25);	
    $row_plano_user25->bindParam(':gigas', $gigas25);	
    $row_plano_user25->bindParam(':valor', $ed_donations25);		
	$row_plano_user25->bindParam(':status', $status);
    $row_plano_user25->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
					 	                       }
/////////////////////////////////////////////////////BRShares vip bronze trimenstral fim
/////////////////////////////////////////////////////BRShares vip prata trimenstral inicio
					   if ($status == 'Maluco_vip_prata_Trimestral'){  
$expiretime = date('Y-m-d H:i:s', strtotime("+93 days"));	
$freeleechuser = 'yes';	
$expiretimemen = date('d-m-Y H:i:s', strtotime("+93 days"));	
	$row_plano30=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations30");
    $row_plano30->bindParam(':ed_donations30', $ed_donations30);
    $row_plano30->execute();

	$row_plano30_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano30_user->bindParam(':expiretime', $expiretime);
	$row_plano30_user->bindParam(':freeleechuser', $freeleechuser);	
    $row_plano30_user->bindParam(':uploaded', $gigas30);
    $row_plano30_user->bindParam(':invites', $inv30);	
	$row_plano30_user->bindParam(':seedbonus', $points30);
	$row_plano30_user->bindParam(':total_donated', $ed_donations30);	
	$row_plano30_user->bindParam(':userid', $ids);
    $row_plano30_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares Share. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user30=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user30->bindParam(':userid', $row_select["id"]);
    $row_plano_user30->bindParam(':added', $added);
	$row_plano_user30->bindParam(':msg', $msg);
    $row_plano_user30->bindParam(':subject', $subject);				  
    $row_plano_user30->execute();  

    $row_plano_user30=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas, valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user30->bindParam(':userid', $row_select["id"]);
	$row_plano_user30->bindParam(':username', $row_select["username"]);
    $row_plano_user30->bindParam(':added', $added);
	$row_plano_user30->bindParam(':expiretime', $expiretime);	
    $row_plano_user30->bindParam(':pontos', $points30);	
    $row_plano_user30->bindParam(':convites', $inv30);	
    $row_plano_user30->bindParam(':gigas', $gigas30);	
    $row_plano_user30->bindParam(':valor', $ed_donations30);		
	$row_plano_user30->bindParam(':status', $status);
    $row_plano_user30->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
					 	                       }
/////////////////////////////////////////////////////BRShares vip prata trimenstral fim
/////////////////////////////////////////////////////BRShares vip ouro trimenstral inicio
					   if ($status == 'Maluco_vip_ouro_Trimestral'){  
$expiretime = date('Y-m-d H:i:s', strtotime("+93 days"));	
$freeleechuser = 'yes';	
$expiretimemen = date('d-m-Y H:i:s', strtotime("+93 days"));		
	$row_plano70=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations70");
    $row_plano70->bindParam(':ed_donations70', $ed_donations70);
    $row_plano70->execute();

	$row_plano70_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano70_user->bindParam(':expiretime', $expiretime);
	$row_plano70_user->bindParam(':freeleechuser', $freeleechuser);	
    $row_plano70_user->bindParam(':uploaded', $gigas70);
    $row_plano70_user->bindParam(':invites', $inv70);	
	$row_plano70_user->bindParam(':seedbonus', $points70);
	$row_plano70_user->bindParam(':total_donated', $ed_donations70);
	$row_plano70_user->bindParam(':userid', $ids);
    $row_plano70_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares Share. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user70=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user70->bindParam(':userid', $row_select["id"]);
    $row_plano_user70->bindParam(':added', $added);
	$row_plano_user70->bindParam(':msg', $msg);
    $row_plano_user70->bindParam(':subject', $subject);				  
    $row_plano_user70->execute();  

    $row_plano_user70=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas, valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user70->bindParam(':userid', $row_select["id"]);
	$row_plano_user70->bindParam(':username', $row_select["username"]);
    $row_plano_user70->bindParam(':added', $added);
	$row_plano_user70->bindParam(':expiretime', $expiretime);	
    $row_plano_user70->bindParam(':pontos', $points70);	
    $row_plano_user70->bindParam(':convites', $inv70);	
    $row_plano_user70->bindParam(':gigas', $gigas70);	
    $row_plano_user70->bindParam(':valor', $ed_donations70);	
	$row_plano_user70->bindParam(':status', $status);
    $row_plano_user70->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
					 	                       }
/////////////////////////////////////////////////////BRShares vip ouro trimestral fim
/////////////////////////////////////////////////////BRShares vip  semestral inicio
					   if ($status == 'Maluco_vip_Semestral'){  
$expiretime = date('Y-m-d H:i:s', strtotime("+186 days"));	
$freeleechuser = 'yes';	
$expiretimemen = date('d-m-Y H:i:s', strtotime("+186 days"));	
	$row_plano35=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations35");
    $row_plano35->bindParam(':ed_donations35', $ed_donations35);
    $row_plano35->execute();

	$row_plano35_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano35_user->bindParam(':expiretime', $expiretime);
	$row_plano35_user->bindParam(':freeleechuser', $freeleechuser);	
    $row_plano35_user->bindParam(':uploaded', $gigas35);
    $row_plano35_user->bindParam(':invites', $inv35);	
	$row_plano35_user->bindParam(':seedbonus', $points35);
	$row_plano35_user->bindParam(':total_donated', $ed_donations35);
	$row_plano35_user->bindParam(':userid', $ids);
    $row_plano35_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares Share. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user35=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user35->bindParam(':userid', $row_select["id"]);
    $row_plano_user35->bindParam(':added', $added);
	$row_plano_user35->bindParam(':msg', $msg);
    $row_plano_user35->bindParam(':subject', $subject);				  
    $row_plano_user35->execute();  

    $row_plano_user35=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas, valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user35->bindParam(':userid', $row_select["id"]);
	$row_plano_user35->bindParam(':username', $row_select["username"]);
    $row_plano_user35->bindParam(':added', $added);
	$row_plano_user35->bindParam(':expiretime', $expiretime);	
    $row_plano_user35->bindParam(':pontos', $points35);	
    $row_plano_user35->bindParam(':convites', $inv35);	
    $row_plano_user35->bindParam(':gigas', $gigas35);	
    $row_plano_user35->bindParam(':valor', $ed_donations35);		
	$row_plano_user35->bindParam(':status', $status);
    $row_plano_user35->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
					 	                       }
/////////////////////////////////////////////////////BRShares vip  semestral fim
/////////////////////////////////////////////////////BRShares vip bronze semetral inicio											   
					   if ($status == 'Maluco_vip_bronze_Semestral'){  
$expiretime = date('Y-m-d H:i:s', strtotime("+186 days"));	
$freeleechuser = 'yes';	
$expiretimemen = date('d-m-Y H:i:s', strtotime("+186 days"));		
	$row_plano55=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations55");
    $row_plano55->bindParam(':ed_donations55', $ed_donations55);
    $row_plano55->execute();

	$row_plano55_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano55_user->bindParam(':expiretime', $expiretime);
	$row_plano55_user->bindParam(':freeleechuser', $freeleechuser);	
    $row_plano55_user->bindParam(':uploaded', $gigas55);
    $row_plano55_user->bindParam(':invites', $inv55);	
	$row_plano55_user->bindParam(':seedbonus', $points55);
	$row_plano55_user->bindParam(':total_donated', $ed_donations55);
	$row_plano55_user->bindParam(':userid', $ids);
    $row_plano55_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares Share. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user55=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user55->bindParam(':userid', $row_select["id"]);
    $row_plano_user55->bindParam(':added', $added);
	$row_plano_user55->bindParam(':msg', $msg);
    $row_plano_user55->bindParam(':subject', $subject);				  
    $row_plano_user55->execute();  

    $row_plano_user55=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas,  valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user55->bindParam(':userid', $row_select["id"]);
	$row_plano_user55->bindParam(':username', $row_select["username"]);
    $row_plano_user55->bindParam(':added', $added);
	$row_plano_user55->bindParam(':expiretime', $expiretime);	
    $row_plano_user55->bindParam(':pontos', $points55);	
    $row_plano_user55->bindParam(':convites', $inv55);	
    $row_plano_user55->bindParam(':gigas', $gigas55);	
    $row_plano_user55->bindParam(':valor', $ed_donations55);		
	$row_plano_user55->bindParam(':status', $status);
    $row_plano_user55->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
					 	                       }	
/////////////////////////////////////////////////////BRShares vip bronze semetral fim
/////////////////////////////////////////////////////BRShares vip prata semetral inicio											   
					   if ($status == 'Maluco_vip_prata_Semestral'){  
$expiretime = date('Y-m-d H:i:s', strtotime("+186 days"));	
$freeleechuser = 'yes';	
$expiretimemen = date('d-m-Y H:i:s', strtotime("+186 days"));		
	$row_plano75=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations75");
    $row_plano75->bindParam(':ed_donations75', $ed_donations75);
    $row_plano75->execute();

	$row_plano75_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano75_user->bindParam(':expiretime', $expiretime);
	$row_plano75_user->bindParam(':freeleechuser', $freeleechuser);	
    $row_plano75_user->bindParam(':uploaded', $gigas75);
    $row_plano75_user->bindParam(':invites', $inv75);	
	$row_plano75_user->bindParam(':seedbonus', $points75);
	$row_plano75_user->bindParam(':total_donated', $ed_donations75);	
	$row_plano75_user->bindParam(':userid', $ids);
    $row_plano75_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares Share. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user75=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user75->bindParam(':userid', $row_select["id"]);
    $row_plano_user75->bindParam(':added', $added);
	$row_plano_user75->bindParam(':msg', $msg);
    $row_plano_user75->bindParam(':subject', $subject);				  
    $row_plano_user75->execute();  

    $row_plano_user75=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas, valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user75->bindParam(':userid', $row_select["id"]);
	$row_plano_user75->bindParam(':username', $row_select["username"]);
    $row_plano_user75->bindParam(':added', $added);
	$row_plano_user75->bindParam(':expiretime', $expiretime);	
    $row_plano_user75->bindParam(':pontos', $points75);	
    $row_plano_user75->bindParam(':convites', $inv75);	
    $row_plano_user75->bindParam(':gigas', $gigas75);	
    $row_plano_user75->bindParam(':valor', $ed_donations75);	
	$row_plano_user75->bindParam(':status', $status);
    $row_plano_user75->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
					 	                       }
/////////////////////////////////////////////////////BRShares vip prata semetral fim
/////////////////////////////////////////////////////BRShares vip ouro semetral inicio											   
					   if ($status == 'Maluco_vip_ouro_Semestral'){  
$expiretime = date('Y-m-d H:i:s', strtotime("+186 days"));	
$freeleechuser = 'yes';	
$expiretimemen = date('d-m-Y H:i:s', strtotime("+186 days"));		
	$row_plano100=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations100");
    $row_plano100->bindParam(':ed_donations100', $ed_donations100);
    $row_plano100->execute();

	$row_plano100_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, freeleechuser = :freeleechuser, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus, total_donated = total_donated + :total_donated, donated = :total_donated WHERE id IN (:userid) LIMIT 1");
    $row_plano100_user->bindParam(':expiretime', $expiretime);
	$row_plano100_user->bindParam(':freeleechuser', $freeleechuser);	
    $row_plano100_user->bindParam(':uploaded', $gigas100);
    $row_plano100_user->bindParam(':invites', $inv100);	
	$row_plano100_user->bindParam(':seedbonus', $points100);
	$row_plano100_user->bindParam(':total_donated', $ed_donations100);
	$row_plano100_user->bindParam(':userid', $ids);
    $row_plano100_user->execute();

	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]BRShares Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.brshares.com/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretimemen."\n\n Em Caso de dúvidas procura nossa [url=http://www.brshares.com/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n BRShares Share. ";

    $added = get_date_time();
    $subject  = "Doação BRShares VIP";
	
	$select_row = $pdo->prepare("SELECT * FROM users WHERE id IN (:userid) LIMIT 1"); 
    $select_row->bindParam(':userid', $ids);
    $select_row->execute();  
    while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)) {

	$row_plano_user100=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,:userid,:added,:msg,:subject) ");
	$row_plano_user100->bindParam(':userid', $row_select["id"]);
    $row_plano_user100->bindParam(':added', $added);
	$row_plano_user100->bindParam(':msg', $msg);
    $row_plano_user100->bindParam(':subject', $subject);				  
    $row_plano_user100->execute();  

    $row_plano_user100=$pdo->prepare("INSERT INTO donatevip (uid, username, added, termina, pontos, convites, gigas, valor, status) VALUES (:userid,:username,:added,:expiretime,:pontos, :convites, :gigas, :valor, :status) ");
	$row_plano_user100->bindParam(':userid', $row_select["id"]);
	$row_plano_user100->bindParam(':username', $row_select["username"]);
    $row_plano_user100->bindParam(':added', $added);
	$row_plano_user100->bindParam(':expiretime', $expiretime);	
    $row_plano_user100->bindParam(':pontos', $points100);	
    $row_plano_user100->bindParam(':convites', $inv100);	
    $row_plano_user100->bindParam(':gigas', $gigas100);	
    $row_plano_user100->bindParam(':valor', $ed_donations100);		
	$row_plano_user100->bindParam(':status', $status);
    $row_plano_user100->execute();  
	
		   $delretira = "DELETE FROM donate WHERE uid IN ($ids) LIMIT 1 ";
		   $delretirav = mysql_query($delretira);
		}
					 	                       }
/////////////////////////////////////////////////////BRShares vip ouro semetral fim

											   }
					   }
					 print("<b><center>Update sucesso!!!<a href='paineldoar.php'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
					   }
					   }
} 
}
	function navmenu(){


?>
<div id="body_outer">

<table cellspacing="1" cellpadding="0" align="center" id="tabela1">
	<tbody><tr>
	<td align="center" colspan="4" class="tab1_cab1">Painel da Moderação de Torrents</td>
	</tr>
	<tr>
	<td width="25%" align="center" class="ttable_col2">
	<a href="/paineldoar.php?action=aguardar&do=ok"><img border="0" alt=""  height="48" width="48" src="/images/doadores-pendentes.png"><br>Doadores pendentes</a>
	</td>
	<td width="25%" align="center" class="ttable_col2">
<a href="/paineldoar.php?action=confirmados&do=ok"><img border="0" alt=""  height="48" width="48" src="/images/doadores.png"><br>Doadores</a>
	</td>
	<td width="25%" align="center" class="ttable_col2">
	<a href="paineldoar.php?action=doadores&do=ok"><img border="0" alt="" src="/images/doadores-rank.png"><br>Rank de doação</a>
	</td>
	<td width="25%" align="center" class="ttable_col2">
	<a href="#"><img border="0" alt="" src="/images/torrent/modlog.png"><br>Log Completo</a>
	</td>
	</tr>
	</tbody></table>
	
				<div class="clr"></div>

			</div>
<?php
}
 end_framec();

 
 
$action = $_REQUEST["action"] ;

$do = $_REQUEST["do"] ;
if ($action=="aguardar" || !$_GET['action']){
begin_framec("Painel Designer MS");
navmenu();

  $res = mysql_query("SELECT * FROM donate ");  
echo"<BR>";
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
 ?>
<table align="center" cellpadding="0" cellspacing="0" class="ttable_headinner" width="90%">

 <tr>
 
 <td class='ttable_head' width='1%' align='center'><input type='checkbox' id='checkAll' onclick='checkAll(this)'></td>
     <th class="ttable_head" width="1%"  align="center">Posição</th>
     <th class="ttable_head"  align="center" >Membro</th>
	 <th class="ttable_head"  align="center" >Data</th>
     <th class="ttable_head" width="20%"  align="center" >Plano escolhido</th>
	 <th class="ttable_head"  align="center" >Método de pagamento</th>
     <th class="ttable_head" width="40%"  align="center" >Identificação do pagamento</th>
	  <th class="ttable_head"  align="center" >Status</th>
 </tr>
 <?php $i = 1; while ($row = mysql_fetch_assoc($res)): ?>
 <tr>
 <form method='post' action='paineldoar.php?action=aprovar&id=1&do=del'>
      <input type='hidden' name='id[]' value='<?php echo $row["uid"]; ?>'> 
 <td class='ttable_col2' width='1%' align='center'><input type='checkbox' name='del[]' value='<?php echo $row["uid"]; ?>'></td>
     <td class="ttable_col2"  align="center" ><?php echo $i; ?></td>
     <td class="ttable_col2"  align="center" ><a href="account-details.php?id=<?php echo $row['uid']; ?>"><?php echo $row["username"]; ?></a></td>
	 <td class="ttable_col2"  align="center" ><?php echo date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) ?></td>
     <td class="ttable_col2"  align="center" ><?php echo $row["plano"]; ?> </td>
	 <td class="ttable_col2"  align="center" ><?php echo $row["metudo"]; ?> </td>
	 <td class="ttable_col2"  align="center" ><?php echo $row["idkey"]; ?> </td>
	  <?php
	 $query3 = mysql_query("SHOW COLUMNS FROM donate WHERE Field = 'status'");
$row3 = mysql_fetch_array($query3);
$enum = str_replace("enum(", "", $row3['Type']);
$enum = str_replace("'", "", $enum);
$enum = substr($enum, 0, strlen($enum) - 1);
$enum = explode(",", $enum);
echo "<td class=ttable_col2 width=10% align=center><select  name='status" . $row['uid'] . "'>";
foreach ($enum as $chave => $campo) {
if($campo == $row['status'] ){
  echo '<option selected="selected" value="'.$campo.'">'.$campo.'</option>';
 }else{
  echo '<option value="'.$campo.'">'.$campo.'</option>';
 }
}

echo '</select></td>';
?>
 </tr>
 <?php $i++; endwhile; ?>
 <?php if ( mysql_num_rows($res) == 0 ): ?>
 <tr>
     <td class="ttable_col2" colspan="8" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
 <tr><td width=100% align=center colspan=8 class=ttable_col2 ><input type='submit' value='Salvar alterações'/><input type='reset' value='Redefinir'/></td>
 </table>
</form>
 <?php 
end_framec();
}
 


if ($action=="confirmados" ){



begin_framec("Painel Designer MS");
navmenu();
 
  $res = mysql_query("SELECT * FROM donatevip ");  
echo"<BR>";
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
 ?>
<table align="center" cellpadding="0" cellspacing="0" class="ttable_headinner" width="90%">

 <tr>
 
 <td class='ttable_head' width='1%' align='center'><input type='checkbox' id='checkAll' onclick='checkAll(this)'></td>
     <th class="ttable_head" width="1%"  align="center">Posição</th>
     <th class="ttable_head"  align="center" >Membro</th>
	 <th class="ttable_head"  align="center" >Data início</th>
     <th class="ttable_head" width="20%"  align="center" >Data fim</th>
	 <th class="ttable_head"  align="center" >Pontos</th>
     <th class="ttable_head"   align="center" >Convites</th>
	 <th class="ttable_head"   align="center" >Gigas</th>
	 <th class="ttable_head"   align="center" >Valor</th>	 
	 <th class="ttable_head"  align="center" >Status</th>
 </tr>
 <?php $i = 1; while ($row = mysql_fetch_assoc($res)): ?>
 <tr>
 <form method='post' action='paineldoar.php?action=vipdeletar&id=1&do=apa'>
      <input type='hidden' name='id[]' value='<?php echo $row["uid"]; ?>'> 
 <td class='ttable_col2' width='1%' align='center'><input type='checkbox' name='apa[]' value='<?php echo $row["uid"]; ?>'></td>
     <td class="ttable_col2"  align="center" ><?php echo $i; ?></td>
     <td class="ttable_col2"  align="center" ><a href="account-details.php?id=<?php echo $row['uid']; ?>"><?php echo $row["username"]; ?></a></td>
	 <td class="ttable_col2"  align="center" ><?php echo date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) ?></td>
     <td class="ttable_col2"  align="center" ><?php echo date("d/m/y", utc_to_tz_time($row['termina']))." às ". date("H:i:s", utc_to_tz_time($row['termina'])) ?></td>
	 <td class="ttable_col2"  align="center" ><?php echo $row["pontos"]; ?> </td>
	 <td class="ttable_col2"  align="center" ><?php echo $row["convites"]; ?> </td>
	 <td class="ttable_col2"  align="center" ><?php echo mksize($row["gigas"]); ?> </td>
	 <td class="ttable_col2"  align="center" ><?php echo $row["valor"]; ?> </td>
	  <?php
	 $query3 = mysql_query("SHOW COLUMNS FROM donatevip WHERE Field = 'status'");
$row3 = mysql_fetch_array($query3);
$enum = str_replace("enum(", "", $row3['Type']);
$enum = str_replace("'", "", $enum);
$enum = substr($enum, 0, strlen($enum) - 1);
$enum = explode(",", $enum);
echo "<td class=ttable_col2 width=10% align=center><select  name='status" . $row['uid'] . "'>";
foreach ($enum as $chave => $campo) {
if($campo == $row['status'] ){
  echo '<option selected="selected" value="'.$campo.'">'.$campo.'</option>';
 }else{
  echo '<option value="'.$campo.'">'.$campo.'</option>';
 }
}

echo '</select></td>';
?>
 </tr>
 <?php $i++; endwhile; ?>
 <?php if ( mysql_num_rows($res) == 0 ): ?>
 <tr>
     <td class="ttable_col2" colspan="10" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
 <tr><td width=100% align=center colspan=10 class=ttable_col2 ><input type='submit' value='Salvar alterações'/><input type='reset' value='Redefinir'/></td>
 </table>
</form>
 <?php 

 end_framec();


}


if ($action=="doadores" ){



begin_framec("Painel Designer MS");
navmenu();
 
  $res = mysql_query("SELECT * FROM users WHERE donated > 0  ORDER BY total_donated DESC ");  
echo"<BR>";
?>	

	<?php
 ?>
<table align="center" cellpadding="0" cellspacing="0" class="ttable_headinner" width="90%">

 <tr>
     <th class="ttable_head" width="1%"  align="center">Posição</th>
     <th class="ttable_head"  align="center" >Membro</th>
	 <th class="ttable_head"  align="center" >Data de cadastro</th>
     <th class="ttable_head"   align="center" >Expira</th>
	 <th class="ttable_head"  align="center" >Valor doado</th>
     <th class="ttable_head"   align="center" >Total doado</th>
 </tr>
 <?php $i = 1; while ($row = mysql_fetch_assoc($res)): ?>
 <?php if ($row["freeleechexpire"] ) 
 
 ?>
 <tr>
     <td class="ttable_col2"  align="center" ><?php echo $i; ?></td>
     <td class="ttable_col2"  align="center" ><a href="account-details.php?id=<?php echo $row['id']; ?>"><?php echo $row["username"]; ?></a></td>
	 <td class="ttable_col2"  align="center" ><?php echo date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) ?></td>
     <td class="ttable_col2"  align="center" ><?php echo $row["freeleechexpire"]; ?> </td>
	 <td class="ttable_col2"  align="center" ><?php echo $row["donated"]; ?> </td>
	 <td class="ttable_col2"  align="center" ><?php echo $row["total_donated"]; ?> </td>
 </tr>
 <?php $i++; endwhile; ?>
 <?php if ( mysql_num_rows($res) == 0 ): ?>
 <tr>
     <td class="ttable_col2" colspan="8" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>

 </table>

 <?php 
end_framec();
}


 stdfoot();
 
?>