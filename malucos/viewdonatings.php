<?php 
//
//      MOD ALL CLASS DONATIONS SYSTEM :  Viewdonatings page
//      

require_once("backend/functions.php"); 
dbconn(false); 

stdhead("Donations Listing"); 
begin_framec("All Class Donations Listing"); 

// Si la variable $_GET['sort'] existe...

if(isset($_GET['sort']))	$sort = $_GET['sort'];





if(!isset($_GET['sort']))	$sort = $_POST['sort'];

elseif(empty($_GET['sort']))  $sort = "id";

print("$sort");

?>
<FORM method="post" action="viewdonatings.php">
<TABLE>
<tr> 
<td>Sort by:</td>
<td><select name="sort">
<OPTION value="">--Any--</OPTION>
<OPTION value="country">Country</OPTION>
<OPTION value="class">Class</OPTION>
<OPTION value="userid">User</OPTION>
<OPTION value="id">Don id</OPTION>
</select></td>
</tr>
<tr>
<td><INPUT type="submit" value="Sort now"></td> 
<td></td>
</tr>
</TABLE>
</FORM> 
<?php


echo "<br><B><CENTER>Please <a href=staff.php>contact</a>
 a member of staff if you would like explanations on making a donation...</CENTER></B><BR><BR>";

$messagesParPage=10;

$retour_total=mysql_query('SELECT COUNT(*) AS total FROM donatings');
 //Nous récupérons le contenu de la requête dans $retour_total

$donnees_total=mysql_fetch_assoc($retour_total); //On range retour sous la forme d'un tableau.

$total=$donnees_total['total']; //On récupère le total pour le placer dans la variable $total.

$nombreDePages=ceil($total/$messagesParPage);




if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
{
     $pageActuelle=intval($_GET['page']);
     
     if($pageActuelle>$nombreDePages) 
// Si la valeur de $pageActuelle (le numéro de la page) est plus grande que $nombreDePages...
     {
          $pageActuelle=$nombreDePages;
     }
}
else 
{
     $pageActuelle=1; // La page actuelle est la n°1    
}

$premiereEntree=($pageActuelle-1)*$messagesParPage; // On calcul la première entrée à lire

$retour_messages=mysql_query('SELECT * FROM donatings ORDER BY '.$sort.' 
ASC LIMIT '.$premiereEntree.', '.$messagesParPage.'');

print("$retour_messages");

if($nombreDePages == 0)	
 {	echo "<BR><B>No Donatings done yet</b><BR>\n";	}

else		{

print("<table align=center cellpadding=3 cellspacing=0 class=table_table width=100% border=1>");


print("<tr><td  class=table_head align=left>Don ID</td>
<td  class=table_head align=center>User ID</td>
<td  class=table_head align=center>Username</td>
<td  class=table_head align=center>Country</td>
<td  class=table_head align=center>Class</td>
<td  class=table_head align=center>Level</td>
<td  class=table_head align=center>Add</td>
<td  class=table_head align=center>Duration</td>
<td  class=table_head align=center>Expiry</td>
<td  class=table_head align=center>Reason</td>
<td  class=table_head align=center>Viped by</td>
<td  class=table_head align=center>Money</td>
<td  class=table_head align=center>Donated</td>
<td  class=table_head align=center>Total Donated</td></tr>");


while($donnees_messages=mysql_fetch_assoc($retour_messages))	{
														
$countryname = get_user_country_name($donnees_messages[country]);

$wusername = get_user_name($donnees_messages[vipedby]);

if ($donnees_messages[duration] == '1') { $durationexpressed = "LIFETIME"; }
else { $durationexpressed = $donnees_messages[duration]; }

print("<tr><td  class=table_col1 align=left>$donnees_messages[id]</td>
<td  class=table_col2 align=center>$donnees_messages[userid]</td>
<td  class=table_col1 align=center>$donnees_messages[username]</td>
<td  class=table_col2 align=center>$countryname</td>
<td class=table_col1  align=center>$donnees_messages[class]</td>
<td  class=table_col2 align=center>$donnees_messages[level]</td>
<td  class=table_col1 align=center>$donnees_messages[added]</td>
<td  class=table_col2 align=center>$durationexpressed</td>
<td  class=table_col1 align=center>$donnees_messages[expiry]</td>
<td  class=table_col2 align=center>".format_comment($donnees_messages['reason'])."</td>
<td  class=table_col1 align=center>$wusername</td>
<td  class=table_col2 align=center>$donnees_messages[money]</td>
<td  class=table_col1 align=center>$donnees_messages[donated]</td>
<td  class=table_col2 align=center>$donnees_messages[total_donated]</td></tr>\n");

													}
echo "</table>";


	}




echo '<p align="center">Page : '; //Pour l'affichage, on centre la liste des pages

for($i=1; $i<=$nombreDePages; $i++) //On fait notre boucle

{
     //On va faire notre condition

     if($i==$pageActuelle) //Si il s'agit de la page actuelle...
     {
         echo ' [ '.$i.' ] '; 
     }	
     else //Sinon...
     {
          echo ' <a href="viewdonatings.php?sort='.$sort.'&page='.$i.'">'.$i.'</a> ';
     }
}
echo '</p>';


end_framec(); 
stdfoot();
?>