<?php
if($CURUSER){
	begin_block("Pedidos");
///////LATEST REQUESTS//////

?>
<?php if($site_config["REQUESTSON"]){


$categ = (int)$_GET["category"];
$requestorid = (int)$_GET["requestorid"];
$sort = $_GET["sort"];
$search = $_GET["search"];
$filter = $_GET["filter"];

$search = " AND requests.request like '%$search%' ";




if ($sort == "votes")
$sort = " order by hits desc ";
else if ($sort == "request")
$sort = " order by request ";
else
$sort = " order by filled asc ";


if ($filter == "true")
$filter = " AND requests.filledby = 0 ";
else
$filter = "";


if ($requestorid <> NULL)
{
if (($categ <> NULL) && ($categ <> 0))
$categ = "WHERE requests.cat = " . $categ . " AND requests.userid = " . $requestorid;
else
$categ = "WHERE requests.userid = " . $requestorid;
}



else if ($categ == 0)
$categ = '';
else
$categ = "WHERE requests.cat = " . $categ;

/*
if ($categ == 0)
$categ = 'WHERE requests.cat > 0 ';
else
$categ = "WHERE requests.cat = " . $categ;
*/


$res = mysql_query("SELECT count(requests.id) FROM requests inner join categories on requests.cat = categories.id inner join users on requests.userid = users.id  $categ $filter $search") or die(mysql_error());
$row = mysql_fetch_array($res);
$count = $row[0];

$perpage = 10;



$res = mysql_query("SELECT users.downloaded, users.uploaded, users.username, users.privacy, requests.filled, requests.comments,
requests.filledby, requests.id, requests.userid, requests.request, requests.added, requests.hits, categories.parent_cat as parent_cat, categories.name AS cat_name, categories.image AS cat_pic
FROM requests inner join categories on requests.cat = categories.id inner join users on requests.userid = users.id  $categ
$filter $search $sort $limit") or sqlerr();

$num = mysql_num_rows($res);



echo $pagertop;

echo "<Table border=0 width=100% cellspacing=0 cellpadding=0><TR><TD width=50% align=left valign=bottom>";



print("<form method=get action=viewrequests.php>");
?>
</td><td width=100% align=right valign=bottom>

<?php

print("</form></td></tr></table>");


print("<table width=100% cellspacing=0 cellpadding=3 class=table_table>\n");
print("<tr><td class=table_head align=left>Torrentes</td>


</tr>\n");
for ($i = 0; $i < $num; ++$i)
{

$arr = mysql_fetch_assoc($res);

$privacylevel = $arr["privacy"];

if ($arr["downloaded"] > 0)
   {
         $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
         $ratio = "<font color=" . get_ratio_color($ratio) . "><b>$ratio</b></font>";
   }
   else if ($arr["uploaded"] > 0)
           $ratio = "Inf.";
   else
           $ratio = "---";


$res2 = mysql_query("SELECT username from users where id=" . $arr[filledby]);
$arr2 = mysql_fetch_assoc($res2);  
if ($arr2[username])
$filledby = $arr2[username];
else
$filledby = " ";        

if ($privacylevel == "strong"){
                if (get_user_class() >= 5){
                        $addedby = "<td class=table_col2 align=center><a href=account-details.php?id=$arr[userid]><b>".$arr[username]." ($ratio)</b></a></td>";
                }else{
                        $addedby = "<td class=table_col2 align=center><a href=account-details.php?id=$arr[userid]><b>".$arr[username]." (----)</b></a></td>";
                }
}else{
                $addedby = "<td class=table_col2 align=center><a href=account-details.php?id=$arr[userid]><b>".$arr[username]." ($ratio)</b></a></td>";
}

$filled = $arr[filled];
if ($filled){
$filled = "<a href=$filled><font color=green><b>Yes</b></font></a>";
$filledbydata = "<a href=account-details.php?id=$arr[filledby]><b>".$arr2[username]."</b></a>";
}
else{
$filled = "<a href=reqdetails.php?id=$arr[id]><font color=red><b>No</b></font></a>";
$filledbydata  = "<i>nobody</i>";
}


print("<tr><td class=table_col1 align=left><a href=reqdetails.php?id=$arr[id]><b>".htmlspecialchars($arr["request"])."</b></a></td>" .
"<td class=table_col2 align=center>
");

print("</tr>\n");

}

print("</table>\n");


print("</form>");

echo $pagerbottom;
}else{
echo "<b><font color=red>Sorry, requests are currently disabled.<br><Br>";
}

////////////END LATEST REQUESTS//////////
	end_block();
}
?>