<?php

require_once("backend/functions.php");
dbconn(false);
loggedinonly();
require ("backend/conexao.php");

$pdo = conectar();

if($CURUSER["edit_users"]!="yes")
	show_error_msg(T_("ACCESS_DENIED"),T_("YOU_DONT_HAVE_EDIT_USER_PERM"),1);

$action = $_POST["action"];

if (!$action)
	show_error_msg(T_("ERROR"), T_("TASK_NOT_FOUND"), 1);

#####################################
#####       ADVANCED DONATER     #####
#####                        #####
#####################################

if ($action == 'adddonater')								{

$don_row = $pdo->prepare("SELECT `donated` AS `donated`, `total_donated` AS `total_donated`, `username` AS `username`, `country` AS `country`, `class` AS `class` FROM users WHERE id = :userid"); 
$don_row->bindParam(':userid', $_POST['userid']);
$don_row->execute(); 
$row_select = $don_row->fetch(PDO::FETCH_ASSOC);  
   

$donated = $row_select["donated"];
$total_donated = $row_select["total_donated"];
$username = $row_select["username"];
$country = $row_select["country"];

$class = mysql_real_escape_string($_POST["class"]);

$level = get_user_class_name($class);
$vipedby = $_POST["vipedby"]; 
$userid = (int)$_POST["userid"];
$reason = mysql_real_escape_string($_POST["reason"]);
$expiry = (int)$_POST["expiry"];
$duration = $expiry;
$money = mysql_real_escape_string($_POST["money"]);

$timenow = get_date_time();

if (!is_valid_id($userid))
        show_error_msg("Editing Failed", "Invalid UserID",1);

if (!$reason|| !$expiry || !$money || !$class)		{
       

 show_error_msg("Error", "Missing form data.",1);
}


if  ($expiry == "1") {    $expiretime = get_date_time(gmtime() + (30001201120000));	} 
else   {    $expiretime = get_date_time(gmtime() + (86400 * $expiry));		}



    if ($donated == 0)  {   $total_donated = $money;   }
	else            {   $total_donated = $total_donated + $money;
			    $donated = $money;	       }
        $moneynew = $money;


// update donatings table
			  $row_donatings=$pdo->prepare("INSERT INTO donatings (userid, reason, added, expiry, vipedby, money,  username, country, class, level, duration, donated, total_donated)     
			   VALUES (:userid, :reason, :timenow, :expiretime, ".$CURUSER['id'].", :money, :username, :country, :class, :level, :duration, :donated, :total_donated)");
			  $row_donatings->bindParam(':userid', $_POST['userid']);
              $row_donatings->bindParam(':reason', $_POST['reason']);
			  $row_donatings->bindParam(':timenow', $timenow);
			  $row_donatings->bindParam(':expiretime', $expiretime);
              $row_donatings->bindParam(':money', $_POST['money']);	
              $row_donatings->bindParam(':username', $username);	
              $row_donatings->bindParam(':country', $country );	
              $row_donatings->bindParam(':class', $_POST['class']);	
              $row_donatings->bindParam(':level', $level);	
              $row_donatings->bindParam(':duration', $duration);	
              $row_donatings->bindParam(':donated', $donated);				
              $row_donatings->bindParam(':total_donated', $total_donated);				  
              $row_donatings->execute();

// update monthly donatings



$year = strftime("%y");
$month = strftime("%m");
$donated = $moneynew;
$month_target = ".$site_config[MONTH_TARGET].";
$total_month_donated = $total_month_donated + $donated;


$check_row = $pdo->prepare("SELECT diff as diff FROM donatings_monthly WHERE month = :month ORDER by id"); 
$check_row->bindParam(':month', $month);
$check_row->execute(); 
$val_row = $check_row->fetch(PDO::FETCH_ASSOC);  

if ($val_row[diff] == "0")	{	$diff = $month_target - $total_month_donated;	}

else	{	$diff = $month_target - ($total_month_donated + $donated);	}


	 $row_ret=$pdo->prepare("INSERT INTO donatings_monthly (year, month, donated, attente, month_target, total_month_donated, diff)
	 VALUES (:year, :month, :donated, :month_target, :total_month_donated, :diff)");
	$row_ret->bindParam(':year', $year);
    $row_ret->bindParam(':month', $month);
	$row_ret->bindParam(':donated', $donated);
    $row_ret->bindParam(':month_target', $month_target);
    $row_ret->bindParam(':total_month_donated', $total_month_donated);	
    $row_ret->bindParam(':diff', $diff );				  
    $row_ret->execute();

//update user account
  	$row_do_user=$pdo->prepare("UPDATE users SET donated = :moneynew, total_donated = :total_donated, donator='y', freeleechuser='yes', class= :class WHERE id= :userid");
    $row_do_user->bindParam(':moneynew', $moneynew);
    $row_do_user->bindParam(':total_donated', $total_donated);
    $row_do_user->bindParam(':class', $class);	
    $row_do_user->bindParam(':userid', $userid);
    $row_do_user->execute();
			  
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
// Plano Mensal maluco vip

if($donated==5){
  	$row_plano5=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations5");
    $row_plano5->bindParam(':ed_donations5', $ed_donations5);
    $row_plano5->execute();

	$row_plano5_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano5_user->bindParam(':expiretime', $expiretime);
    $row_plano5_user->bindParam(':uploaded', $gigas5);
    $row_plano5_user->bindParam(':invites', $inv5);	
	$row_plano5_user->bindParam(':seedbonus', $points5);
	$row_plano5_user->bindParam(':userid', $userid);
    $row_plano5_user->execute();
	
	$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n caso você ainda não conheça as vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] .\n\n Aproveite da Melhor forma o site, e mais durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents que baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a garantir a permanência e manutenção do nosso site no ar.\n\n Agora você é um usuário diferenciado.\n\n\ Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n 
	[color=blue]*** Seu Plano termina em :[/color] ".$expiretime."\n\n Em Caso de dúvidas procura nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url]\n\n Atenciosamente,\n\n\n Malucos Share. ";
    
    $added = get_date_time();
    $subject  = "Doação Malucos VIP";
	
	$row_plano_user5=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user5->bindParam(':userid', $userid);
    $row_plano_user5->bindParam(':added', $added);
	$row_plano_user5->bindParam(':msg', $msg);
    $row_plano_user5->bindParam(':subject', $subject);				  
    $row_plano_user5->execute();   
    header("Location: account-details.php?id=$userid");
    die;
	}
	// Plano Mensal Maluco vip bronze
	elseif ($donated==10){
	$row_plano10=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations10");
    $row_plano10->bindParam(':ed_donations10', $ed_donations10);
    $row_plano10->execute();
	
	$row_plano10_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano10_user->bindParam(':expiretime', $expiretime);
    $row_plano10_user->bindParam(':uploaded', $gigas10);
    $row_plano10_user->bindParam(':invites', $inv10);	
	$row_plano10_user->bindParam(':seedbonus', $points10);
	$row_plano10_user->bindParam(':userid', $userid);
    $row_plano10_user->execute();
	
$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n Agora você é um usuário com privilégios diferentes dos demais, se você ainda não conheçe suas vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] e saiba como aproveitar da melhor forma o site, e ainda durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents para baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a manter o site no ar. Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n Em caso de dúvidas não deixe de procurar a nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url] \n\n Atenciosamente, \n Equipe Malucos-Share.\n\n
	[color=blue]*** Seu Plano expira em :[/color] ".$expiretime.". ";
    $added = get_date_time();
    $subject  = "Promovido";
	
	$row_plano_user10=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user10->bindParam(':userid', $userid);
    $row_plano_user10->bindParam(':added', $added);
	$row_plano_user10->bindParam(':msg', $msg);
    $row_plano_user10->bindParam(':subject', $subject);				  
    $row_plano_user10->execute();   

    header("Location: account-details.php?id=$userid");
    die;
	}
		// (Maluco vip prata), (Plano Mensal)
	elseif ($donated==20){
	$row_plano20=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations20");
    $row_plano20->bindParam(':ed_donations20', $ed_donations20);
    $row_plano20->execute();

	$row_plano20_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano20_user->bindParam(':expiretime', $expiretime);
    $row_plano20_user->bindParam(':uploaded', $gigas20);
    $row_plano20_user->bindParam(':invites', $inv20);	
	$row_plano20_user->bindParam(':seedbonus', $points20);
	$row_plano20_user->bindParam(':userid', $userid);
    $row_plano20_user->execute();
	
$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n Agora você é um usuário com privilégios diferentes dos demais, se você ainda não conheçe suas vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] e saiba como aproveitar da melhor forma o site, e ainda durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents para baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a manter o site no ar. Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n Em caso de dúvidas não deixe de procurar a nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url] \n\n Atenciosamente, \n Equipe Malucos-Share.\n\n
	[color=blue]*** Seu Plano expira em :[/color] ".$expiretime.". ";
    $added = get_date_time();
    $subject  = "Promovido";
	
	$row_plano_user20=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user20->bindParam(':userid', $userid);
    $row_plano_user20->bindParam(':added', $added);
	$row_plano_user20->bindParam(':msg', $msg);
    $row_plano_user20->bindParam(':subject', $subject);				  
    $row_plano_user20->execute(); 
    
    header("Location: account-details.php?id=$userid");
    die;
	}
		// (Maluco vip ouro), (Plano Mensal)
	elseif ($donated==50){
	
	$row_plano50=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations50");
    $row_plano50->bindParam(':ed_donations50', $ed_donations50);
    $row_plano50->execute();
	
	$row_plano50_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano50_user->bindParam(':expiretime', $expiretime);
    $row_plano50_user->bindParam(':uploaded', $gigas50);
    $row_plano50_user->bindParam(':invites', $inv50);	
	$row_plano50_user->bindParam(':seedbonus', $points50);
	$row_plano50_user->bindParam(':userid', $userid);
    $row_plano50_user->execute();
	
$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n Agora você é um usuário com privilégios diferentes dos demais, se você ainda não conheçe suas vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] e saiba como aproveitar da melhor forma o site, e ainda durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents para baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a manter o site no ar. Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n Em caso de dúvidas não deixe de procurar a nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url] \n\n Atenciosamente, \n Equipe Malucos-Share.\n\n
	[color=blue]*** Seu Plano expira em :[/color] ".$expiretime.". ";
    $added = get_date_time();
    $subject  = "Promovido";
	
	$row_plano_user50=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user50->bindParam(':userid', $userid);
    $row_plano_user50->bindParam(':added', $added);
	$row_plano_user50->bindParam(':msg', $msg);
    $row_plano_user50->bindParam(':subject', $subject);				  
    $row_plano_user50->execute(); 
    
    header("Location: account-details.php?id=$userid");
    die;
	}
		// (Maluco vip), (Plano Trimestral)
	elseif ($donated==15){
	$row_plano15=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations15");
    $row_plano15->bindParam(':ed_donations15', $ed_donations15);
    $row_plano15->execute();
	
	$row_plano15_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano15_user->bindParam(':expiretime', $expiretime);
    $row_plano15_user->bindParam(':uploaded', $gigas15);
    $row_plano15_user->bindParam(':invites', $inv15);	
	$row_plano15_user->bindParam(':seedbonus', $points15);
	$row_plano15_user->bindParam(':userid', $userid);
    $row_plano15_user->execute();

$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n Agora você é um usuário com privilégios diferentes dos demais, se você ainda não conheçe suas vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] e saiba como aproveitar da melhor forma o site, e ainda durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents para baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a manter o site no ar. Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n Em caso de dúvidas não deixe de procurar a nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url] \n\n Atenciosamente, \n Equipe Malucos-Share.\n\n
	[color=blue]*** Seu Plano expira em :[/color] ".$expiretime.". ";
    $added = get_date_time();
    $subject  = "Promovido";
	
    $row_plano_user15=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user15->bindParam(':userid', $userid);
    $row_plano_user15->bindParam(':added', $added);
	$row_plano_user15->bindParam(':msg', $msg);
    $row_plano_user15->bindParam(':subject', $subject);				  
    $row_plano_user15->execute(); 
    
    header("Location: account-details.php?id=$userid");
    die;
	}
		// (Maluco vip bronze), (Plano Trimestral)
	elseif ($donated==25){
	$row_plano25=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations25");
    $row_plano25->bindParam(':ed_donations25', $ed_donations25);
    $row_plano25->execute();
	
	$row_plano25_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano25_user->bindParam(':expiretime', $expiretime);
    $row_plano25_user->bindParam(':uploaded', $gigas25);
    $row_plano25_user->bindParam(':invites', $inv25);	
	$row_plano25_user->bindParam(':seedbonus', $points25);
	$row_plano25_user->bindParam(':userid', $userid);
    $row_plano25_user->execute();
	

$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n Agora você é um usuário com privilégios diferentes dos demais, se você ainda não conheçe suas vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] e saiba como aproveitar da melhor forma o site, e ainda durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents para baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a manter o site no ar. Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n Em caso de dúvidas não deixe de procurar a nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url] \n\n Atenciosamente, \n Equipe Malucos-Share.\n\n
	[color=blue]*** Seu Plano expira em :[/color] ".$expiretime.". ";
    $added = get_date_time();
    $subject  = "Promovido";
	
	$row_plano_user25=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user25->bindParam(':userid', $userid);
    $row_plano_user25->bindParam(':added', $added);
	$row_plano_user25->bindParam(':msg', $msg);
    $row_plano_user25->bindParam(':subject', $subject);				  
    $row_plano_user25->execute(); 
    
    header("Location: account-details.php?id=$userid");
    die;
	}
		// (Maluco vip prata), (Plano Trimestral)
	elseif ($donated==30){
	$row_plano30=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations30");
    $row_plano30->bindParam(':ed_donations30', $ed_donations30);
    $row_plano30->execute();
	
	$row_plano30_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano30_user->bindParam(':expiretime', $expiretime);
    $row_plano30_user->bindParam(':uploaded', $gigas30);
    $row_plano30_user->bindParam(':invites', $inv30);	
	$row_plano30_user->bindParam(':seedbonus', $points30);
	$row_plano30_user->bindParam(':userid', $userid);
    $row_plano30_user->execute();
	
$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n Agora você é um usuário com privilégios diferentes dos demais, se você ainda não conheçe suas vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] e saiba como aproveitar da melhor forma o site, e ainda durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents para baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a manter o site no ar. Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n Em caso de dúvidas não deixe de procurar a nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url] \n\n Atenciosamente, \n Equipe Malucos-Share.\n\n
	[color=blue]*** Seu Plano expira em :[/color] ".$expiretime.". ";
    $added = get_date_time();
    $subject  = "Promovido";
	
	$row_plano_user30=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user30->bindParam(':userid', $userid);
    $row_plano_user30->bindParam(':added', $added);
	$row_plano_user30->bindParam(':msg', $msg);
    $row_plano_user30->bindParam(':subject', $subject);				  
    $row_plano_user30->execute(); 
    
    header("Location: account-details.php?id=$userid");
    die;
	}
		// (Maluco vip ouro), (Plano Trimestral)
	elseif ($donated==70){
	$row_plano70=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations70");
    $row_plano70->bindParam(':ed_donations70', $ed_donations70);
    $row_plano70->execute();
	
	$row_plano70_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano70_user->bindParam(':expiretime', $expiretime);
    $row_plano70_user->bindParam(':uploaded', $gigas70);
    $row_plano70_user->bindParam(':invites', $inv70);	
	$row_plano70_user->bindParam(':seedbonus', $points70);
	$row_plano70_user->bindParam(':userid', $userid);
    $row_plano70_user->execute();	
	
$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n Agora você é um usuário com privilégios diferentes dos demais, se você ainda não conheçe suas vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] e saiba como aproveitar da melhor forma o site, e ainda durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents para baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a manter o site no ar. Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n Em caso de dúvidas não deixe de procurar a nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url] \n\n Atenciosamente, \n Equipe Malucos-Share.\n\n
	[color=blue]*** Seu Plano expira em :[/color] ".$expiretime.". ";
     $added = get_date_time();
    $subject  = "Promovido";
	
	$row_plano_user70=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user70->bindParam(':userid', $userid);
    $row_plano_user70->bindParam(':added', $added);
	$row_plano_user70->bindParam(':msg', $msg);
    $row_plano_user70->bindParam(':subject', $subject);				  
    $row_plano_user70->execute(); 
    
    header("Location: account-details.php?id=$userid");
    die;
	}
		// (Maluco vip), (Plano Semestral)
	elseif ($donated==35){
	$row_plano35=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations35");
    $row_plano35->bindParam(':ed_donations35', $ed_donations35);
    $row_plano35->execute();
	
	$row_plano35_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano35_user->bindParam(':expiretime', $expiretime);
    $row_plano35_user->bindParam(':uploaded', $gigas35);
    $row_plano35_user->bindParam(':invites', $inv35);	
	$row_plano35_user->bindParam(':seedbonus', $points35);
	$row_plano35_user->bindParam(':userid', $userid);
    $row_plano35_user->execute();

$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n Agora você é um usuário com privilégios diferentes dos demais, se você ainda não conheçe suas vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] e saiba como aproveitar da melhor forma o site, e ainda durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents para baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a manter o site no ar. Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n Em caso de dúvidas não deixe de procurar a nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url] \n\n Atenciosamente, \n Equipe Malucos-Share.\n\n
	[color=blue]*** Seu Plano expira em :[/color] ".$expiretime.". ";
    $added = get_date_time();
    $subject  = "Promovido";
	$row_plano_user35=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user35->bindParam(':userid', $userid);
    $row_plano_user35->bindParam(':added', $added);
	$row_plano_user35->bindParam(':msg', $msg);
    $row_plano_user35->bindParam(':subject', $subject);				  
    $row_plano_user35->execute(); 	
   
    header("Location: account-details.php?id=$userid");
    die;
	}
		// (Maluco vip bronze), (Plano Semestral)
	elseif ($donated==55){
	$row_plano55=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations55");
    $row_plano55->bindParam(':ed_donations55', $ed_donations55);
    $row_plano55->execute();
	
	$row_plano55_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano55_user->bindParam(':expiretime', $expiretime);
    $row_plano55_user->bindParam(':uploaded', $gigas55);
    $row_plano55_user->bindParam(':invites', $inv55);	
	$row_plano55_user->bindParam(':seedbonus', $points55);
	$row_plano55_user->bindParam(':userid', $userid);
    $row_plano55_user->execute();	

$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n Agora você é um usuário com privilégios diferentes dos demais, se você ainda não conheçe suas vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] e saiba como aproveitar da melhor forma o site, e ainda durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents para baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a manter o site no ar. Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n Em caso de dúvidas não deixe de procurar a nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url] \n\n Atenciosamente, \n Equipe Malucos-Share.\n\n
	[color=blue]*** Seu Plano expira em :[/color] ".$expiretime.". ";
    $added = get_date_time();
    $subject  = "Promovido";
	
	$row_plano_user55=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user55->bindParam(':userid', $userid);
    $row_plano_user55->bindParam(':added', $added);
	$row_plano_user55->bindParam(':msg', $msg);
    $row_plano_user55->bindParam(':subject', $subject);				  
    $row_plano_user55->execute(); 
    
    header("Location: account-details.php?id=$userid");
    die;
	}
		// (Maluco vip prata), (Plano Semestral)
	elseif ($donated==75){
	$row_plano75=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations75");
    $row_plano75->bindParam(':ed_donations75', $ed_donations75);
    $row_plano75->execute();
	
	$row_plano75_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano75_user->bindParam(':expiretime', $expiretime);
    $row_plano75_user->bindParam(':uploaded', $gigas75);
    $row_plano75_user->bindParam(':invites', $inv75);	
	$row_plano75_user->bindParam(':seedbonus', $points75);
	$row_plano75_user->bindParam(':userid', $userid);
    $row_plano75_user->execute();	

$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n Agora você é um usuário com privilégios diferentes dos demais, se você ainda não conheçe suas vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] e saiba como aproveitar da melhor forma o site, e ainda durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents para baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a manter o site no ar. Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n Em caso de dúvidas não deixe de procurar a nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url] \n\n Atenciosamente, \n Equipe Malucos-Share.\n\n
	[color=blue]*** Seu Plano expira em :[/color] ".$expiretime.". ";
    $added = get_date_time();
    $subject  = "Promovido";
	
	$row_plano_user75=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user75->bindParam(':userid', $userid);
    $row_plano_user75->bindParam(':added', $added);
	$row_plano_user75->bindParam(':msg', $msg);
    $row_plano_user75->bindParam(':subject', $subject);				  
    $row_plano_user75->execute(); 	
    
    header("Location: account-details.php?id=$userid");
    die;
	}
		// (Maluco vip ouro), (Plano Semestral)
	elseif ($donated==100){
	$row_plano100=$pdo->prepare("UPDATE site_settings SET donations = donations + :ed_donations100");
    $row_plano100->bindParam(':ed_donations100', $ed_donations100);
    $row_plano100->execute();
	
	$row_plano100_user=$pdo->prepare("UPDATE users SET freeleechexpire = :expiretime, uploaded = uploaded + :uploaded, invites = invites + :invites, seedbonus = seedbonus + :seedbonus  WHERE id= :userid");
    $row_plano100_user->bindParam(':expiretime', $expiretime);
    $row_plano100_user->bindParam(':uploaded', $gigas100);
    $row_plano100_user->bindParam(':invites', $inv100);	
	$row_plano100_user->bindParam(':seedbonus', $points100);
	$row_plano100_user->bindParam(':userid', $userid);
    $row_plano100_user->execute();		
	
$msg = "Prezado usuário, \n\n Parabéns, você acaba de se tornar um usuário [color=blue][size=2]Malucos Vip!!![/size][/color]\n\n Agora você é um usuário com privilégios diferentes dos demais, se você ainda não conheçe suas vantagens clique [url=http://www.malucos-share.org/donate.php]Aqui[/url] e saiba como aproveitar da melhor forma o site, e ainda durante a vigência do seu plano [color=blue][size=2]VIP[/size][/color] todos torrents para baixar são [size=2] [color=greem]FREE[/size][/color] .\n\n Além disso a sua doação ajuda a manter o site no ar. Agradecemos a sua ajuda e esperamos que esteja satisfeito com o desenvolvimento do site. \n\n Em caso de dúvidas não deixe de procurar a nossa [url=http://www.malucos-share.org/staff.php]Staff.[/url] \n\n Atenciosamente, \n Equipe Malucos-Share.\n\n
	[color=blue]*** Seu Plano expira em :[/color] ".$expiretime.". ";
    $added = get_date_time();
    $subject  = "Promovido";
	$row_plano_user100=$pdo->prepare("INSERT INTO messages (sender, receiver, added, msg, subject)
	 VALUES (0,:userid,:added,:msg,:subject)");
	$row_plano_user100->bindParam(':userid', $userid);
    $row_plano_user100->bindParam(':added', $added);
	$row_plano_user100->bindParam(':msg', $msg);
    $row_plano_user100->bindParam(':subject', $subject);				  
    $row_plano_user100->execute(); 	

    header("Location: account-details.php?id=$userid");
    die;
	}
    header("Location: account-details.php?id=$userid");
	}
################################
if ($action == 'edituser'){
	$userid = $_POST["userid"];
	$title = $_POST["title"];
	$seedbonus = $_POST["seedbonus"];
	$signature = $_POST["signature"];
	$avatar = $_POST["avatar"];
	$ip = $_POST["ip"];
	$class = (int) $_POST["class"];
	$donated =  $_POST["donated"];
	$password = $_POST["password"];
	$warned = $_POST["warned"];
	$forumbanned = $_POST["forumbanned"];
	$hideshoutbox = $_POST["hideshoutbox"];
	$dj = $_POST["dj"];
	$djstaff = $_POST["djstaff"];
	$modcomment = $_POST["modcomment"];
	$enabled = $_POST["enabled"];
	$invites =(int) $_POST["invites"];
	$class = (int)$_POST["class"];
		if($CURUSER["level"]=="Administrador" ){
	$email = $_POST["email"];
	
		if (!validemail($email))
		show_error_msg(T_("EDITING_FAILED"), T_("EMAIL_ADDRESS_NOT_VALID"), 1);
		}
$freeleechuser = $_POST["freeleechuser"];
$freeleechexpire = $_POST["freeleechexpire"];
	if (!is_valid_id($userid))
		show_error_msg("Edição de Falha", "Invalido UserID",1);
$query = mysql_query("SELECT `freeleechuser`, `freeleechexpire` FROM `users` WHERE `id` = $userid");
    $row = mysql_fetch_array($query);
    
    if ($row["freeleechuser"] == "no" && $freeleechuser == "yes")
    {
        if ($freeleechexpire == "0000-00-00 00:00:00")
            $type = "will not expire unless demoted.";
        else
            $type = "will expire on: $freeleechexpire.";
        
        $message  = sqlesc("$CURUSER[username] has made you a freeleech user which $type");
        $datetime = sqlesc(get_date_time());
            
        mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $message, $datetime)"); 
    }
    
    if ($row["freeleechuser"] == "yes" && $freeleechuser == "no")
    {
        if ($freeleechexpire != "0000-00-00 00:00:00")
            $freeleechexpire  = "0000-00-00 00:00:00";
            
        $message  = sqlesc("$CURUSER[username] has demoted you from being a freeleech user.");
        $datetime = sqlesc(get_date_time());
        
        mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $message, $datetime)");
    }
    
        mysql_query("UPDATE `users` SET `freeleechuser` = " . sqlesc($freeleechuser) . ", `freeleechexpire` = " . sqlesc($freeleechexpire) . " WHERE `id` = $userid");


	if (!is_valid_id($userid))
		show_error_msg(T_("EDITING_FAILED"), T_("INVALID_USERID"),1);


	//change user class
	$res = SQL_Query_exec("SELECT class FROM users WHERE id=$userid");
	$arr = mysql_fetch_row($res);
	$uc = $arr[0];

	// skip if class is same as current
	if ($uc != $class && $class > 0) {
		if ($userid == $CURUSER["id"]) {
			show_error_msg(T_("EDITING_FAILED"), T_("YOU_CANT_DEMOTE_YOURSELF"),1);
		} elseif ($uc >= get_user_class()) {
			show_error_msg(T_("EDITING_FAILED"), T_("YOU_CANT_DEMOTE_SOMEONE_SAME_LVL"),1);
		} else {
			@SQL_Query_exec("UPDATE users SET class=$class WHERE id=$userid");
			// Notify user
			$prodemoted = ($class > $uc ? "promovido" : "rebaixado");
			$msg = sqlesc("Prezado usuário, 
			
			Você foi $prodemoted a '" . get_user_class_name($class) . "' por " . $CURUSER["username"] . "
			Parabéns e boa sorte.
			
			Equipe MS");
			$added = sqlesc(get_date_time());
			@SQL_Query_exec("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)");
write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] promoveu o usuário [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url] para '" . get_user_class_name($class) . "'\n");	
		}
	}
	//continue updates
   if ($seedbonus > 0 )
   {
	write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] adicionou + ".$seedbonus." pontos para o usuário [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");

   }
      if ($invites > 0 )
   {
	write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] adicionou + ".$invites." convites para o usuário [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");

   }
         $r = @SQL_Query_exec("SELECT * FROM users WHERE id=$userid");
$user1 = mysql_fetch_array($r);
$baniruserf = $user1['username'];
$added_forum = get_date_time();
   $enabled_log = $_POST['enabled']=='no' ? true : false;
   if ($enabled_log) {
	$row_enabled=$pdo->prepare("UPDATE users SET enabled = :enabled WHERE id= :userid ");
    $row_enabled->bindParam(':enabled', $enabled);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] desativou a conta do [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
   }
      $enabled_log = $_POST['enabled']=='yes' ? true : false;
   if ($enabled_log) {
   	$row_enabled=$pdo->prepare("UPDATE users SET enabled = :enabled WHERE id= :userid ");
    $row_enabled->bindParam(':enabled', $enabled);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] ativou a conta do [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
   }
   
   $warned_log = $_POST['warned']=='no' ? true : false;
   if ($warned_log) {
	$row_enabled=$pdo->prepare("UPDATE users SET warned = :warned WHERE id= :userid ");
    $row_enabled->bindParam(':warned', $warned);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] removeu a advertência do [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
   }
      $warned_log = $_POST['warned']=='yes' ? true : false;
   if ($warned_log) {
   	$row_enabled=$pdo->prepare("UPDATE users SET warned = :warned WHERE id= :userid ");
    $row_enabled->bindParam(':warned', $warned);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] advertiu o [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
   }   
      $forumbanned_log = $_POST['forumbanned']=='no' ? true : false;
   if ($forumbanned_log) {
	$row_enabled=$pdo->prepare("UPDATE users SET forumbanned = :forumbanned WHERE id= :userid ");
    $row_enabled->bindParam(':forumbanned', $forumbanned);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] liberou o forúm ao usuário [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
      SQL_Query_exec("DELETE FROM usermoderado WHERE tipo = 'forum' AND uid = $userid ");	
   }
      $forumbanned_log = $_POST['forumbanned']=='yes' ? true : false;
   if ($forumbanned_log) {
   	$row_enabled=$pdo->prepare("UPDATE users SET forumbanned = :forumbanned WHERE id= :userid ");
    $row_enabled->bindParam(':forumbanned', $forumbanned);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
	$tipo_forum = 'forum';
	$forumbanneduser =    $CURUSER["username"]; 
	$forumbannedname =    $CURUSER["id"]; 
	$forumbannedmod=$pdo->prepare("INSERT INTO usermoderado (uid, username, uidmod, usernamemod, added, tipo ) VALUES (:uid, :username, :uidmod, :usernamemod, :added, :tipo )");
	$forumbannedmod->bindParam(':uid', $userid);
    $forumbannedmod->bindParam(':username', $baniruserf);
    $forumbannedmod->bindParam(':added', $added_forum);	
    $forumbannedmod->bindParam(':usernamemod', $forumbanneduser);	
    $forumbannedmod->bindParam(':uidmod', $forumbannedname);		
    $forumbannedmod->bindParam(':tipo', $tipo_forum);	
    $forumbannedmod->execute(); 		
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] baniu o usuário [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url] do fórum \n");
   }  
   
         $hideshoutbox_log = $_POST['hideshoutbox']=='no' ? true : false;
   if ($hideshoutbox_log) {
	$row_enabled=$pdo->prepare("UPDATE users SET hideshoutbox = :hideshoutbox WHERE id= :userid ");
    $row_enabled->bindParam(':hideshoutbox', $hideshoutbox);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] liberou o shoutbox ao usuário [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
   
   SQL_Query_exec("DELETE FROM usermoderado WHERE tipo = 'shoutbox' AND uid = $userid ");	
   }
   
      $hideshoutbox_log = $_POST['hideshoutbox']=='yes' ? true : false;
   if ($hideshoutbox_log) {
   	$row_enabled=$pdo->prepare("UPDATE users SET hideshoutbox = :hideshoutbox WHERE id= :userid ");
    $row_enabled->bindParam(':hideshoutbox', $hideshoutbox);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
	$tipo_forum = 'shoutbox';
	$forumbanneduser =    $CURUSER["username"]; 
	$forumbannedname =    $CURUSER["id"]; 
	$forumbannedmod=$pdo->prepare("INSERT INTO usermoderado (uid, username, uidmod, usernamemod, added, tipo ) VALUES (:uid, :username, :uidmod, :usernamemod, :added, :tipo )");
	$forumbannedmod->bindParam(':uid', $userid);
    $forumbannedmod->bindParam(':username', $baniruserf);
    $forumbannedmod->bindParam(':added', $added_forum);	
    $forumbannedmod->bindParam(':usernamemod', $forumbanneduser);	
    $forumbannedmod->bindParam(':uidmod', $forumbannedname);		
    $forumbannedmod->bindParam(':tipo', $tipo_forum);	
    $forumbannedmod->execute(); 			
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] baniu o usuário [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url] do shoutbox \n");
   }    

   if ($user1['email'] != $email){
      write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] alterou o e-mail do [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
   }
 
   if ($user1['avatar'] != $avatar){
      write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] alterou o avatar do [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
   
   }
   if ($user1['title'] != $title){
      write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] alterou o título do [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
   
   }
   if ($user1['signature'] != $signature){
      write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] alterou a assinatura do [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
   
   }
   if ($user1['donated'] != $donated){
      write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] alterou o valor de doação do [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
   
   }   
      if ($user1['modcomment'] != $modcomment){
      write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] alterou o comentário moderação do [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
   
   }   
	if($CURUSER["level"]=="Administrador" ){
   	$row_enabled=$pdo->prepare("UPDATE users SET title = :title, email = :email, signature = :signature, avatar = :avatar, ip = :ip, donated = :donated, dj = :dj, djstaff = :djstaff,  modcomment= :modcomment,  seedbonus = seedbonus + :seedbonus,  invites = invites + :invites  WHERE id= :userid ");
    $row_enabled->bindParam(':title', $title);
	$row_enabled->bindParam(':email', $email);
	$row_enabled->bindParam(':signature', $signature);
	$row_enabled->bindParam(':avatar', $avatar);
	$row_enabled->bindParam(':ip', $ip);	
	$row_enabled->bindParam(':donated', $donated);	
	$row_enabled->bindParam(':dj', $dj);	
	$row_enabled->bindParam(':djstaff', $djstaff);	
    $row_enabled->bindParam(':modcomment', $modcomment);	
    $row_enabled->bindParam(':seedbonus', $seedbonus);	
    $row_enabled->bindParam(':invites', $invites);			
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
	
	}else
	{
	
	   	$row_enabled=$pdo->prepare("UPDATE users SET title = :title, signature = :signature, avatar = :avatar, ip = :ip, donated = :donated, dj = :dj, djstaff = :djstaff,  modcomment= :modcomment,  seedbonus = seedbonus + :seedbonus,  invites = invites + :invites  WHERE id= :userid ");
    $row_enabled->bindParam(':title', $title);
	$row_enabled->bindParam(':signature', $signature);
	$row_enabled->bindParam(':avatar', $avatar);
	$row_enabled->bindParam(':ip', $ip);	
	$row_enabled->bindParam(':donated', $donated);	
	$row_enabled->bindParam(':dj', $dj);	
	$row_enabled->bindParam(':djstaff', $djstaff);	
    $row_enabled->bindParam(':modcomment', $modcomment);	
    $row_enabled->bindParam(':seedbonus', $seedbonus);	
    $row_enabled->bindParam(':invites', $invites);			
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
	}
	



	$chgpasswd = $_POST['chgpasswd']=='yes' ? true : false;
	if ($chgpasswd) {
		$passreq = SQL_Query_exec("SELECT password FROM users WHERE id=$userid");
		$passres = mysql_fetch_assoc($passreq);
		if($password != $passres['password']){
			$password = passhash($password);
			SQL_Query_exec("UPDATE users SET password='$password' WHERE id=$userid");
			write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] mudou a senha do usuário [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");

		}
	}
  
  header("Location: account-details.php?id=$userid");
  die;
}
#################### ADVANCED DONATER

if ($action == 'adddonater'){
    $userid = (int)$_POST["userid"];
    $reason = mysql_real_escape_string($_POST["type"]);
    $expiry = (int)$_POST["expiry"];
    $type = mysql_real_escape_string($_POST["money"]);
        $typevip = mysql_real_escape_string($_POST["typevip"]);

    if (!is_valid_id($userid))
        show_error_msg("Editing Failed", "Invalid UserID",1);

    if (!$type|| !$expiry || !$money || !$typevip){
        show_error_msg("Error", "Missing form data.",1);
    }

    $timenow = get_date_time();

    $expiretime = get_date_time(gmtime() + (86400 * $expiry));

    $ret = mysql_query("INSERT INTO donatings (userid, type, added, expiry, vipedby, money, typevip) VALUES ('$userid','$reason','$timenow','$expiretime','".$CURUSER['id']."','$money','$typevip')");

    mysql_query("UPDATE users SET donated='$money' WHERE id=$userid");
    $ret = mysql_query("UPDATE users SET donator='y' WHERE id='$userid'");
   if ($typevip == 1) {
    mysql_query("UPDATE users SET class=2 WHERE id=$userid");}#vip
    if ($typevip == 2) {
    mysql_query("UPDATE users SET class=3 WHERE id=$userid");}#supervip
    if ($typevip == 3) {
    mysql_query("UPDATE users SET class=4 WHERE id=$userid");}#goldenvip
    if ($typevip == 4) {
    mysql_query("UPDATE users SET class=5 WHERE id=$userid");}#ultravip
    if ($typevip == 5) {
    mysql_query("UPDATE users SET class=6 WHERE id=$userid");}#mastervip

    $msg = sqlesc("You have been donated by " . $CURUSER["username"] . " - Reason: ".$reason." - Expiry: ".$expiretime."");
    $added = sqlesc(get_date_time());
    @mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)");

    
    header("Location: account-details.php?id=$userid");
    die;
}

##################### END
if ($action == 'addwarning'){
	$userid = (int)$_POST["userid"];
	$reason = mysql_real_escape_string($_POST["reason"]);
	$expiry = (int)$_POST["expiry"];
	$type = mysql_real_escape_string($_POST["type"]);

	if (!is_valid_id($userid))
		show_error_msg(T_("EDITING_FAILED"), T_("INVALID_USERID"),1);

	if (!$reason || !$expiry || !$type){
		show_error_msg(T_("ERROR"), T_("MISSING_FORM_DATA").".", 1);
	}

	$timenow = get_date_time();

	$expiretime = get_date_time(gmtime() + (86400 * $expiry));

	$ret = SQL_Query_exec("INSERT INTO warnings (userid, reason, added, expiry, warnedby, type) VALUES ('$userid','$reason','$timenow','$expiretime','".$CURUSER['id']."','$type')");

	$ret = SQL_Query_exec("UPDATE users SET warned='yes' WHERE id='$userid'");

	$msg = sqlesc("Você foi avisado por " . $CURUSER["username"] . " - razão: ".$reason." - expiração: ".$expiretime."");
	$added = sqlesc(get_date_time());
	@SQL_Query_exec("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)");
write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] adicionou um aviso para o usuário [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
	header("Location: account-details.php?id=$userid");
	die;
}


if ($action == "deleteaccount"){
    
    if ($CURUSER["delete_users"] != "yes")//only allow admins to delete users
		show_error_msg(T_("ERROR"), T_("TASK_ADMIN"),1);

	$userid = (int)$_POST["userid"];
	$username = sqlesc($_POST["username"]);
	$delreason = sqlesc($_POST["delreason"]);

	if (!is_valid_id($userid))
		show_error_msg(T_("FAILED"), T_("INVALID_USERID"),1);

    if ($CURUSER["id"] == $userid) 
        show_error_msg("Error", "You cannot delete yourself.", 1);
        
	if (!$delreason){
		show_error_msg(T_("ERROR"), T_("MISSING_FORM_DATA"), 1);
	}

	deleteaccount($userid);
write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] apagou a conta do [url=http://www.malucos-share.org/account-details.php?id=".$userid."]".$userid."[/url]\n");
	show_error_msg(T_("COMPLETED"), T_("USER_DELETE"), 1);
	die;
}

/*

if ($action == "banuser")
{
  $userid = $_POST["userid"];
  $what = $_POST["what"];
  if (!is_valid_id($userid))
    genbark("Not a vaild Userid");
  $comment = $_POST['comment'];
  if (!$comment)
    genbark("".T_("ERROR").":", "Please explain why you are banning this user!");
  $r = SQL_Query_exec("SELECT username,ip FROM users WHERE id=$userid") or sqlerr();
  $a = mysql_fetch_assoc($r);
  $username = $a["username"];
  $ip = $a["ip"];
  if ($what == "subnet")
  	$ip = substr($ip, 0, strrpos($ip, ".")) . ".*";
  else
    if ($what == 'ip')
      $extra = " OR ip='" . substr($ip, 0, strrpos($ip, ".")) . ".*'";
    else
      genbark("Heh", "Select what to ban!");
  $r = SQL_Query_exec("SELECT * FROM bans WHERE ip='$ip'$extra") or sqlerr();
  if (mysql_num_rows($r) > 0)
    genbark(T_("ERROR"), "IP/subnet is already banned");
  else {
    $dt = get_date_time();
    $comment = sqlesc($comment);
    SQL_Query_exec("INSERT INTO bans (userid, first, last, added, addedby, comment) VALUES($userid, '$ip', '$ip', '$dt', $CURUSER[id], $comment)") or sqlerr();
    SQL_Query_exec("UPDATE users SET secret='' WHERE id=$userid") or sqlerr();
    $returnto = $_POST["returnto"];
    header("Location: $returnto");
    die;
  }
}

if ($action == "enableaccount")
{
  $userid = $_POST["id"];
  $res = SQL_Query_exec("SELECT * FROM users WHERE id='$userid'") or sqlerr();
  if (mysql_num_rows($res) != 1)
    genbark("User $userid not found!");
  $secret = sqlesc(mksecret());
  SQL_Query_exec("UPDATE users SET secret=" . $secret . " WHERE id=$userid") or sqlerr();
  header("Location: account-details.php?id=$userid");
  die;
}
*/
?>
