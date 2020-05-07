<?php
//
//      MOD ALL CLASS DONATIONS SYSTEM :  Takedeldon page
// 


require "backend/functions.php";
dbconn(true);
global $CURUSER;

stdhead("Delete a Donation");
begin_framec("Excluir uma Doação");

if (get_user_class($CURUSER) > 13)	{

if (empty($_POST["deldon"]))	{
print("<CENTER>Você deve selecionar pelo menos uma doação para apagar.</CENTER>");
end_framec();
stdfoot();
die;
						}

$userid = $_GET['userid'];

$moneyless = $_POST['moneyless'];

$delid = htmlspecialchars($_GET['delid'], ENT_QUOTES);

$do="DELETE FROM donatings WHERE id IN (" . implode(", ", $_POST[deldon]) . ")";
$res=mysql_query($do);
print ("$moneyless..........$userid");

///// ajouter l effacement des fields concernes dans la table users !!!!!!!!!




$do1="SELECT * FROM users WHERE  id = $userid";
$res1=mysql_query($do1);
$arr1=mysql_fetch_assoc($res1);

if($arr1[donated] - $moneyless <= '0')	
{	$donatorupdate='n'; $freeleech='n';		}




$do2="UPDATE user WHERE id = $userid SET   total_donated = total_donated - moneyless 
donator = donatorupdate class = '1' ";
$res2=mysql_query($do2);
mysql_query("UPDATE users SET freeleechuser='no', freeleechexpire='0000-00-00 00:00:00:', donated=0 WHERE id=$userid");


print("<CENTER>Doação Excluídos OK.........Conta de Usuário Atualizado em OK.<br><br>Redirecionando Você......<br><br>");
print("<BR><a href=account-details.php?id=$userid>Clique aqui</a> para voltar.</CENTER>");
		header("Refresh: 3;url=account-details.php?id=$userid");

echo "<BR><BR>";
								}

						else		 {
print("<CENTER>Sem permissão para excluir Doação ID $del_don</CENTER>");
								}



end_framec();
stdfoot();