<?php
############################################################
#######                                             ########
#######                                             ########
#######           brshares.com 2.0                  ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
dbconn(false);
loggedinonly();

stdhead(T_("UP_MANAGER"));

 if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Sysop" ){

	
#-----------------------------------------------
# ADD
#-----------------------------------------------

if ($_POST['gig'])
{
	if ( !is_numeric($_POST['class']))
	{
		SQL_Query_exec("UPDATE users SET uploaded = uploaded + ".strtobytes($_POST['gig'])." WHERE status = 'confirmed'");
		$res = SQL_Query_exec("SELECT id FROM users");
		$arr1[0] = T_("ALL1");
	}
	else
	{
		SQL_Query_exec("UPDATE users SET uploaded = uploaded + ".strtobytes($_POST['gig'])." WHERE class=".$_POST['class']." AND status = 'confirmed'");
		$res = SQL_Query_exec("SELECT id FROM users WHERE class=".$_POST['class']." AND status = 'confirmed'");
		$res1 = SQL_Query_exec("SELECT level FROM groups WHERE group_id=".$_POST['class']."");
		$arr1 = mysql_fetch_row($res1);
	}
	
	$sender = ( $_POST["fromsystem"] == "yes" ) ? "0" : $CURUSER["id"];		
	$subject = T_("SUBJECT_ADD");
	$msg = sprintf( T_("MSG_ADD"), $_POST['gig'], $arr1[0]);
	
	while($arr = mysql_fetch_row($res))
	{
		SQL_Query_exec("INSERT INTO messages (sender, receiver, added, subject, msg, poster) VALUES ($sender, $arr[0], '".get_date_time()."', ".sqlesc($subject).", ".sqlesc($msg).", $sender)");
	}
	///write_logstaff( ( T_("UP_ADDED"), $CURUSER["username"], $_POST['gig'], htmlspecialchars($arr1[0]) ) );
	autolink("upload-bonus.php", T_("UP_UPDATED"));
}
#-----------------------------------------------
# DEDUCT
#-----------------------------------------------
if($_POST['gig2'])
{
if ( !is_numeric($_POST['class']))
{
$res = SQL_Query_exec("SELECT id,uploaded FROM users");
$arr1[0] = T_("ALL1");
}
else
{
$res = SQL_Query_exec("SELECT id,uploaded FROM users WHERE class=".$_POST['class']." AND status = 'confirmed'");
$res1 = SQL_Query_exec("SELECT level FROM groups WHERE group_id=".$_POST['class']."");
$arr1 = mysql_fetch_row($res1);
}

$sender = ( $_POST["fromsystem"] == "yes" ) ? "0" : $CURUSER["id"];
$subject = T_("SUBJECT_DED");
$msg = sprintf( T_("MSG_DED"), $_POST['gig2'], $arr1[0]);

while($arr = mysql_fetch_row($res))
{
	 $up_value = strtobytes($_POST['gig2']);
	 if (($arr[1]-$up_value) >= 0)
	 SQL_Query_exec("UPDATE users SET uploaded = uploaded - ".$up_value." WHERE id=".$arr[0]." AND status = 'confirmed'");
	 else
	 SQL_Query_exec("UPDATE users SET uploaded = 0 WHERE id=".$arr[0]." AND status = 'confirmed'");

SQL_Query_exec("INSERT INTO messages (sender, receiver, added, subject, msg, poster) VALUES ($sender, $arr[0], '".get_date_time()."', ".sqlesc($subject).", ".sqlesc($msg).", $sender)");
}

write_log( sprintf(T_("UP_DEDUCTED"), $CURUSER["username"], $_POST['gig2'], htmlspecialchars($arr1[0])) );
autolink("upload-bonus.php", T_("UP_UPDATED"));
}

#-----------------------------------------------
# MULTIPLY
#-----------------------------------------------
if($_POST['gig3'])
{
	if ( !is_numeric($_POST['class']))
	{
		SQL_Query_exec("UPDATE users SET uploaded = uploaded * ".$_POST['gig3']." WHERE status = 'confirmed'");
		$res = SQL_Query_exec("SELECT id FROM users");
		$arr1[0] = T_("ALL1");
	}
	else
	{
		SQL_Query_exec("UPDATE users SET uploaded = uploaded * ".$_POST['gig3']." WHERE class=".$_POST['class']." AND status = 'confirmed'");
		$res = SQL_Query_exec("SELECT id FROM users WHERE class=".$_POST['class']." AND status = 'confirmed'");
		$res1 = SQL_Query_exec("SELECT level FROM groups WHERE group_id=".$_POST['class']."");
		$arr1 = mysql_fetch_row($res1);
	}

	$sender = ( $_POST["fromsystem"] == "yes" ) ? "0" : $CURUSER["id"];
	$subject = T_("SUBJECT_MUL");
	$msg = sprintf( T_("MSG_MUL"), $_POST['gig3'], $arr1[0]);

	while($arr = mysql_fetch_row($res))
	{
		SQL_Query_exec("INSERT INTO messages (sender, receiver, added, subject, msg, poster) VALUES ($sender, $arr[0], '".get_date_time()."', ".sqlesc($subject).", ".sqlesc($msg).", $sender)");
	}
	
	write_logstaff( sprintf(T_("UP_MULTIPLIED"), class_user($CURUSER["username"]), $_POST['gig3'], htmlspecialchars($arr1[0])) );
	autolink("upload-bonus.php", T_("UP_UPDATED"));
}

#-----------------------------------------------
# DIVIDE
#-----------------------------------------------
if($_POST['gig4'])
{
	if ( !is_numeric($_POST['class']))
	{
		$res = SQL_Query_exec("SELECT id,uploaded FROM users WHERE status = 'confirmed'");
		$arr1[0] = T_("ALL1");
	}
	else
	{
		$res = SQL_Query_exec("SELECT id,uploaded FROM users WHERE class=".$_POST['class']." AND status = 'confirmed'");
		$res1 = SQL_Query_exec("SELECT level FROM groups WHERE group_id=".$_POST['class']."");
		$arr1 = mysql_fetch_row($res1);
	}
	
	$sender = ( $_POST["fromsystem"] == "yes" ) ? "0" : $CURUSER["id"];
	$subject = T_("SUBJECT_DIV");
	$msg = sprintf( T_("MSG_DIV"), $_POST['gig4'], $arr1[0]);

	while($arr = mysql_fetch_row($res))
	{
	    $up_value = $_POST['gig4'];
        if (($arr[1]/$up_value) >= 0)
        SQL_Query_exec("UPDATE users SET uploaded = uploaded / (".$_POST['gig4'].") WHERE id=".$arr[0]." AND status = 'confirmed'");
        else
        SQL_Query_exec("UPDATE users SET uploaded = 0 WHERE id=".$arr[0]." AND status = 'confirmed'");

    	SQL_Query_exec("INSERT INTO messages (sender, receiver, added, subject, msg, poster) VALUES ($sender, $arr[0], '".get_date_time()."', ".sqlesc($subject).", ".sqlesc($msg).", $sender)");
	}
	
	write_logstaff( sprintf(T_("UP_DIVIDED"), class_user($CURUSER["username"]), $_POST['gig4'], htmlspecialchars($arr1[0])) );
	autolink("upload-bonus.php", T_("UP_UPDATED"));
	
}

#-----------------------------------------------
# END
#-----------------------------------------------
	
begin_frame(T_("UP_MANAGER"));

echo T_("INFO_UP_BONUS");
?>

<form method="POST" action="upload-bonus.php">

	<table class="table_table" align="center" width="200" border="0" cellpadding="2">
		<tr>
			<td class="table_col1" width="170"><b><li style="margin-left:20px">  <?php echo T_("MEMBER"); ?></b></li></td>
			<td class="table_col2" width="30" align="center"><input name="class" type="radio" value="1"></td>
        </tr>
        <tr>
			<td class="table_col2" width="170"><b><li style="margin-left:20px"> <?php echo T_("POWER_USER"); ?></b></li></td>
			<td class="table_col1" width="30" align="center"><input name="class" type="radio" value="2"></td>
        </tr>
        <tr>
			<td class="table_col1" width="170"><b><li style="margin-left:20px"> <?php echo T_("VIP"); ?></b></li></td>
			<td class="table_col2" width="30" align="center"><input name="class" type="radio" value="3"></td>
        </tr>
        <tr>
			<td class="table_col2" width="170"><b><li style="margin-left:20px"> <?php echo T_("UPLOADER"); ?></b></li></td>
			<td class="table_col1" width="30" align="center"><input name="class" type="radio" value="4"></td>
        </tr>
        <tr>
			<td class="table_col1" width="170"><b><li style="margin-left:20px"> <?php echo T_("MODERATOR"); ?></b></li></td>
			<td class="table_col2" width="30" align="center"><input name="class" type="radio" value="5"></td>
        </tr>
        <tr>
			<td class="table_col2" width="170"><b><li style="margin-left:20px"> <?php echo T_("SUPER_MODERATOR"); ?></b></li></td>
			<td class="table_col1" width="30" align="center"><input name="class" type="radio" value="6"></td>
        </tr>
        <tr>
			<td class="table_col1" width="170"><b><li style="margin-left:20px"> <?php echo T_("ADMINISTRATOR"); ?></b></li></td>
			<td class="table_col2" width="30" align="center"><input name="class" type="radio" value="7"></td>
        </tr>
		<tr>
			<td class="table_col2" width="170"><b><li style="margin-left:20px"> <?php echo T_("Sysop"); ?></b></li></td>
			<td class="table_col1" width="30" align="center"><input name="class" type="radio" value="8"></td>
        </tr>
		<tr>
			<td class="table_col1" width="170"><b><li style="margin-left:20px"> <?php echo T_("TO_ALL_MEMBERS"); ?></b></li></td>
			<td class="table_col2" width="30" align="center"><input name="class" type="radio" value="A" checked></td>
        </tr>
    </table>
	  
		<br />
	  
	<table class="table_table" align="center" width="400" border="0" cellpadding="4">
		<tr>
			<td class="table_head" width="200" colspan="2" align="center"><?php echo T_("UP_ADD"); ?></td>
			<td class="table_head" width="200" colspan="2" align="center"><?php echo T_("UP_DED"); ?></td>
		</tr>
		<tr>
			<td class="table_col2" width="100" align="center"><input type="submit" name="gig" value="1 GB" /></td>
			<td class="table_col1" width="100" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
	  
			<td class="table_col2" width="100" align="center"><input type="submit" name="gig2" value="1 GB" /></td>
			<td class="table_col1" width="100" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col1" align="center"><input type="submit" name="gig" value="2 GB" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
      
			<td class="table_col1" align="center"><input type="submit" name="gig2" value="2 GB" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col2" align="center"><input type="submit" name="gig"value="5 GB" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
			
			<td class="table_col2" align="center"><input type="submit" name="gig2" value="5 GB" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col1" align="center"><input type="submit" name="gig"value="10 GB" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
			
			<td class="table_col1" align="center"><input type="submit" name="gig2" value="10 GB" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col2" align="center"><input type="submit" name="gig"value="15 GB" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
      
			<td class="table_col2" align="center"><input type="submit" name="gig2" value="15 GB" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col1" align="center"><input type="submit" name="gig" value="25 GB" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
			
			<td class="table_col1" align="center"><input type="submit" name="gig2" value="25 GB" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col2" align="center"><input type="submit" name="gig" value="50 GB" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
      
			<td class="table_col2" align="center"><input type="submit" name="gig2" value="50 GB" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col1" align="center"><input type="submit" name="gig" value="75 GB" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
      
			<td class="table_col1" align="center"><input type="submit" name="gig2" value="75 GB" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col2" align="center"><input type="submit" name="gig" value="100 GB" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
      
			<td class="table_col2" align="center"><input type="submit" name="gig2" value="100 GB" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
	</table>
  
		<br />
  
	<table class="table_table" align="center" width="400" border="0" cellpadding="4">
		<tr>
			<td class="table_head" width="200" colspan="2" align="center"><b><?php echo T_("UP_MUL"); ?></td>
			<td class="table_head" width="200" colspan="2" align="center"><b><?php echo T_("UP_DIV"); ?></td>
		</tr>
		<tr>
			<td class="table_col2" width="100" align="center"><input type="submit" name="gig3" value="2" /></td>
			<td class="table_col1" width="100" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
			
			<td class="table_col2" width="100" align="center"><input type="submit" name="gig4"value="2" /></td>
			<td class="table_col1" width="100" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col1" align="center"><input type="submit" name="gig3" value="4" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
      
			<td class="table_col1" align="center"><input type="submit" name="gig4"value="4" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col2" align="center"><input type="submit" name="gig3" value="5" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
      
			<td class="table_col2" align="center"><input type="submit" name="gig4" value="5" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col1" align="center"><input type="submit" name="gig3" value="6" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
      
			<td class="table_col1" align="center"><input type="submit" name="gig4" value="6" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col2" align="center"><input type="submit" name="gig3" value="8" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
      
			<td class="table_col2" align="center"><input type="submit" name="gig4" value="8" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col1" align="center"><input type="submit" name="gig3" value="10" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
      
			<td class="table_col1" align="center"><input type="submit" name="gig4" value="10" /></td>
			<td class="table_col2" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
		<tr>
			<td class="table_col2" align="center"><input type="submit" name="gig3" value="15" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
      
			<td class="table_col2" align="center"><input type="submit" name="gig4" value="15" /></td>
			<td class="table_col1" align="center"><input type="checkbox" name="fromsystem" value="yes" /> System</td>
		</tr>
	</table>
</form>

<?php 
}
else{
	show_error_msg(T_("ERROR"), T_("SORRY_NO_RIGHTS_TO_ACCESS"), 1);
}
end_frame(); 
stdfoot(); 

?>