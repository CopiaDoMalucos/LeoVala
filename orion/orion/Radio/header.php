<?php
/*
************************************************
*==========[TS Special Edition v.5.6]==========*
************************************************
*              Special Thanks To               *
*        DrNet - wWw.SpecialCoders.CoM         *
*          Vinson - wWw.Decode4u.CoM           *
*    MrDecoder - wWw.Fearless-Releases.CoM     *
*           Fynnon - wWw.BvList.CoM            *
*==============================================*
*   Note: Don't Modify Or Delete This Credit   *
*     Next Target: TS Special Edition v5.7     *
*     TS SE WILL BE ALWAYS FREE SOFTWARE !     *
************************************************
*/
if(!defined('IN_TRACKER')) die('Hacking attempt!');
/* TS Special Edition Default Template by xam - v5.6 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<meta name="generator" content="<?php echo $title; ?>" />
<meta name="revisit-after" content="3 days" />
<meta name="robots" content="index, follow" />
<meta name="description" content="<?php echo $metadesc; ?>" />
<meta name="keywords" content="<?php echo $metakeywords; ?>" />
<title><?php echo $title; ?></title>
<link rel="stylesheet" href="<?php echo $BASEURL; ?>/include/templates/<?php echo $defaulttemplate; ?>/style/style.css" type="text/css" media="screen" />
<?php echo (isset($includeCSS) ? $includeCSS : ''); ?>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo $BASEURL; ?>/rss.php" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php echo $BASEURL; ?>/rss.php" />
<link rel="shortcut icon" href="<?php echo $BASEURL; ?>/favicon.ico" type="image/x-icon" />







<script type="text/javascript">
	//<![CDATA[
	var baseurl="<?php echo htmlspecialchars_uni($BASEURL); ?>";
	var dimagedir="<?php echo $BASEURL; ?>/<?php echo $pic_base_url; ?>";
	var charset="<?php echo $charset; ?>";
	var userid="<?php echo (isset($CURUSER['id']) ? (int)$CURUSER['id'] : 0); ?>";
	//]]>
</script>
<?php
$lang->load('scripts');
if (defined('NcodeImageResizer') OR (isset($CURUSER) AND $CURUSER['announce_read'] == 'no') OR THIS_SCRIPT == 'index.php')
{
	include_once(INC_PATH.'/javascript_resizer.php');
}
echo '
<script type="text/javascript" src="'.$BASEURL.'/scripts/main.js?v='.O_SCRIPT_VERSION.'"></script>
</head>

'.(isset($includescripts) ? $includescripts : '').(isset($includescripts2) ? $includescripts2 : '').'
</head>
<body class="yui-skin-sam">
'.(!$CURUSER ? '
<div id="topbar" class="subheader">
	<table width="100%">
		<tr>
			<td width="99%" class="none">'.$lang->global['unregistered'].'</td>
			<td width="1%" class="none"><a href="#" onclick="closebar(); return false"><img style="float: left;" src="'.$BASEURL.'/'.$pic_base_url.'close.gif" border="0" alt="" /></a></td>
		</tr>
	</table>
</div>'
: '').'
<div id="top">
		<div class="top_text">

	<div style="float: left; color:fff; padding: 0px 250px 0 40px; position:relative;">
		'.(isset($CURUSER) ? '
			<script type="text/javascript">
				//<![CDATA[
				function SearchPanel()
				{
					if (document.getElementById(\'search-torrent\').style.display == \'none\')
					{
						ts_show(\'search-torrent\');
					}
					else
					{
						ts_hide(\'search-torrent\');
					}
				}
				//]]>
			</script>
			<a href="#" onclick="javascript: SearchPanel(); return false;">'.$lang->global['storrent'].'</a>			
			<form method="post" action="'.$BASEURL.'/browse.php?do=search&amp;search_type=t_both">
			<input type="hidden" name="search_type" value="t_both" />
			<input type="hidden" name="do" value="search" />
			<div id="search-torrent" style="display: none; position: absolute;">
				<table border="0" cellpadding="2" cellspacing="0" width="420px;">
					<tr>
						<td class="thead"><span style="float: right; cursor: pointer;" onclick="javascript: SearchPanel(); return false;"><b>X</b></span>'.$lang->global['storrent'].'</td>
					</tr>
					<tr>
						<td>'.$lang->global['storrent2'].' <input type="text" size="40" value="" name="keywords" /> <input type="submit" value="'.$lang->global['buttonsearch'].'" />
					</tr>
				</table>				
			</div>
			</form>
			' : '
			<a href="'.$BASEURL.'/login.php">'.$lang->header['login'].'</a> | <a href="'.$BASEURL.'/signup.php">'.$lang->header['register'].'</a> | '.$lang->header['recoverpassword'].' <a href="'.$BASEURL.'/recover.php">'.$lang->header['viaemail'].'</a> | <a href="'.$BASEURL.'/recoverhint.php">'.$lang->header['viaquestion'].'</a>
			').'
		</div>
		<div class="padding" align="center">';
if (isset($CURUSER))
{
?>
	<span>
		<?php echo $lang->global['welcomeback']; ?> <a href="<?php echo ts_seo($CURUSER['id'], $CURUSER['username']); ?>"><?php echo get_user_color($CURUSER['username'],$usergroups['namestyle'],true); ?></a> <?$medaldon?> <?$warn?> (<?php echo htmlspecialchars_uni($CURUSER['ip']); ?>) <a href="<?php echo $BASEURL?>/logout.php?logouthash=<?php echo $_SESSION['hash']; ?>" onclick="return log_out()"><?php echo $lang->global['logout']; ?></a></span>&nbsp;&nbsp;&nbsp;&nbsp;

		<span>
		<?php echo $lang->global['ratio']; ?> <?php echo $ratio?>&nbsp;&nbsp;<?php echo $lang->global['bonus']; ?> <a href="<?php echo $BASEURL?>/mybonus.php"><?php echo number_format($CURUSER['seedbonus'], 2)?></a>&nbsp;&nbsp;<?php echo maxslots().$lang->global['uploaded']; ?> <font color="green"><?php echo mksize($CURUSER['uploaded'])?></font>&nbsp;&nbsp;<?php echo $lang->global['downloaded']; ?> <font color="red"><?php echo mksize($CURUSER['downloaded'])?></font></span>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
	echo ($inboxpic ? '<a href="'.$BASEURL.'/messages.php">'.$inboxpic.'</a>' : '').'
	<a href="'.$BASEURL.'/friends.php"><img border="0" alt="'.$lang->header['extrafriends'].'" title="'.$lang->header['extrafriends'].'" src="'.$BASEURL.'/include/templates/'.$defaulttemplate.'/images/group.png" /></a>
	<a href="'.$BASEURL.'/users.php"><img border="0" alt="'.$lang->header['extramembers'].'" title="'.$lang->header['extramembers'].'" src="'.$BASEURL.'/include/templates/'.$defaulttemplate.'/images/user_go.png" /></a>
	<a href="'.$BASEURL.'/getrss.php"><img border="0" alt="'.$lang->header['extrarssfeed'].'" title="'.$lang->header['extrarssfeed'].'" src="'.$BASEURL.'/include/templates/'.$defaulttemplate.'/images/rss.png" /></a>';
}
echo '
</div>
</div>


<div class="f_search">';
$dirlist = '';
$link = 0;
foreach (dir_list(INC_PATH.'/languages') as $language)
{	
	
}
if (isset($CURUSER))
{
}
?>
</div></div>
<div class="title">

<?php echo ($link > 1 ? $dirlist : '' ); unset($link, $dirlist); ?>
</div>
</div>
<div class="padding" align="center"><script type="text/javascript" src="<?php echo $BASEURL; ?>/include/templates/Radio/scripts/rainbow.js?v=5.6"></script>
</div>
<div class="bg-meniu"> 
<center>
<div id="menu">
	<!-- START TSSE MENU -->
	<ul class="TSSEMenu TSSEMenum">
<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span><img src="/_image/house.png" alt="" title="" border="0"  /><?php echo $lang->global['home']; ?></span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">

				<li class="TSSEMenui"><a class="TSSEMenui" href="<?php echo $BASEURL; ?>/index.php"><img src="/_image/go.png" alt="" title="" border="0"  /><?php echo $lang->global['home']; ?></a></li>
			
 <!--[if lte IE 6]></td></tr></table></a><![endif]-->

	</li>

							</ul>



<?php if (isset($CURUSER)) { ?>
<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span>Games</span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">

				<li class="TSSEMenui"><a class="TSSEMenui" href="/ts_blackjack.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" /> blackjack</a></li>
				<li class="TSSEMenui"><a class="TSSEMenui" href="/casino.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" /> casino</a></li>
				<li class="TSSEMenui"><a class="TSSEMenui" href="/games.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" /> games</a></li>
                            
<!--[if lte IE 6]></td></tr></table></a><![endif]-->


	</li>

							</ul>
    <li class="TSSEMenui"><a class="TSSEMenui" href="#"><span>Forum</span></a>
            <!--[if lte IE 6]><table><tr><td><![endif]-->
            <ul class="TSSEMenum">

                <li class="TSSEMenui"><a class="TSSEMenui"  href="tsf_forums"><img  src="/images/menu_images/help.png" alt="" title="" border="0"   />Forums</a></li>

 <!--[if lte IE 6]></td></tr></table></a><![endif]-->

	</li>

							</ul>
<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span>Torrent</span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">

				<li class="TSSEMenui"><a class="TSSEMenui" href="/browse.php?page=1"><img src="/images/menu_images/help.png" alt="" title="" border="0"  />Torrents</a></li>
				<li class="TSSEMenui"><a class="TSSEMenui" href="/browse.php?quick_search=show_daily_torrents"><img src="/images/menu_images/help.png" alt="" title="" border="0"  />Daily Torrents </a></li>
				<li class="TSSEMenui"><a class="TSSEMenui" href="/browse.php?quick_search=show_weekly_torrents"><img src="/images/menu_images/help.png" alt="" title="" border="0"  /> Weekly Torrents</a></li>
                           	<li class="TSSEMenui"><a class="TSSEMenui" href="/browse.php?quick_search=show_montly"><img src="/images/menu_images/help.png" alt="" title="" border="0"  />Montly Torrents</a></li>


 <!--[if lte IE 6]></td></tr></table></a><![endif]-->

	</li>

							</ul>
<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span>Requests</span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">

				<li class="TSSEMenui"><a class="TSSEMenui" href="/viewrequests.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Requests</a></li>
			  <!--[if lte IE 6]></td></tr></table></a><![endif]-->

	</li>

							</ul>

	
	<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span><?php echo $lang->global['upload']; ?></span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">

				<li class="TSSEMenui"><a class="TSSEMenui" href="/uploaderform.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Uploader form</a></li>
			        <li class="TSSEMenui"><a class="TSSEMenui" href="/upload.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Uploader torrents</a></li>
                                <li class="TSSEMenui"><a class="TSSEMenui" href="/faq.php#37"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Reglement uploads</a></li>
 <!--[if lte IE 6]></td></tr></table></a><![endif]-->

	</li>

							</ul>
	
<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span><?php echo $lang->global['usercp']; ?></span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">

				<li class="TSSEMenui"><a class="TSSEMenui" href="/usercp.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />User cp</a></li>
			        <li class="TSSEMenui"><a class="TSSEMenui" href="/messages.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Messages</a></li>
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/browse.php?special_search=mytorrents"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />My torrents</a></li>
                                <li class="TSSEMenui"><a class="TSSEMenui" href="/referrals.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Referrals</a></li>
                                <li class="TSSEMenui"><a class="TSSEMenui" href="/logout.php" onclick="return log_out()"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Logout</a></li>
 <!--[if lte IE 6]></td></tr></table></a><![endif]-->


	</li>

							</ul>
	
	<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span><?php echo $lang->global['top10']; ?></span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">

				<li class="TSSEMenui"><a class="TSSEMenui" href="/topten.php?type=1"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Users</a></li>
			        <li class="TSSEMenui"><a class="TSSEMenui" href="/topten.php?type=2"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Torrents</a></li>
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/topten.php?type=3"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Country</a></li>
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/topten.php?type=4"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Peers</a></li>
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/topten.php?type=5"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Forums</a></li>
                              
<!--[if lte IE 6]></td></tr></table></a><![endif]-->


	</li>

							</ul>
	

	<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span><?php echo $lang->global['help']; ?></span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">

				<li class="TSSEMenui"><a class="TSSEMenui" href="/rules.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Rules</a></li>
			        <li class="TSSEMenui"><a class="TSSEMenui" href="/faq.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />FAQ</a></li>
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/links.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />links</a></li>
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/ts_tutorials.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Tutorials</a></li>
                             
<!--[if lte IE 6]></td></tr></table></a><![endif]-->


	</li>

							</ul>
<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span><?php echo $lang->global['extra']; ?></span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">

				<li class="TSSEMenui"><a class="TSSEMenui" href="/userdetails.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Profile</a></li>
			        <li class="TSSEMenui"><a class="TSSEMenui" href="/users.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Members</a></li>
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/friends.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Friends</a></li>
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/ts_lottery.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Lottery</a></li>
                             <li class="TSSEMenui"><a class="TSSEMenui" href="/getrss.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />RSS</a></li>
			        <li class="TSSEMenui"><a class="TSSEMenui" href="/invite.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Invite</a></li>
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/mybonus.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Points Bonus</a></li>
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/scripts/pbar/ts_donation_status.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Donation</a></li>


<!--[if lte IE 6]></td></tr></table></a><![endif]-->


	</li>

							</ul>

<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span><?php echo $lang->global['staff']; ?></span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">

				<li class="TSSEMenui"><a class="TSSEMenui" href="/staff.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Staff Team</a></li>
			        <li class="TSSEMenui"><a class="TSSEMenui" href="/contactstaff.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />Contact staff</a></li>
                 
        
<!--[if lte IE 6]></td></tr></table></a><![endif]-->


	</li>

							</ul>

<?php
	if ($usergroups['canstaffpanel'] == 'yes' && $usergroups['cansettingspanel'] != 'yes' && $usergroups['issupermod'] != 'yes')
	{
		echo '<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span><font color="yellow">'.$lang->global['staffmenu'].'</font></span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/admin/index.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />admin panel</a></li>
		    <!--[if lte IE 6]></td></tr></table></a><![endif]-->


	</li>

							</ul> ';
	}
	elseif ($usergroups['canstaffpanel'] == 'yes' && $usergroups['cansettingspanel'] != 'yes' && $usergroups['issupermod'] == 'yes')
	{	
		echo '<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span><font color="yellow">'.$lang->global['staffmenu'].'</font></span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">
                                <li class="TSSEMenui"><a class="TSSEMenui" href="/admin/index.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />admin panel</a></li>
			        <li class="TSSEMenui"><a class="TSSEMenui" href="/admin/index.php?act=statistics"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />stats</a></li>
                       <!--[if lte IE 6]></td></tr></table></a><![endif]-->


	</li>

							</ul>';
	}
	elseif ($usergroups['cansettingspanel'] == 'yes')
	{
		echo '	<li class="TSSEMenui"><a class="TSSEMenui" href="#"><span><font color="yellow">'.$lang->global['staffmenu'].'</font></span></a>
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="TSSEMenum">
<li class="TSSEMenui"><a class="TSSEMenui" href="/admin/index.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />admin panel</a></li>
			        <li class="TSSEMenui"><a class="TSSEMenui" href="/admin/settings.php"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />admin tracker</a></li>
                               <li class="TSSEMenui"><a class="TSSEMenui" href="/admin/index.php?act=statistics"><img src="/images/menu_images/help.png" alt="" title="" border="0" class="inlineimg" />stats</a></li>
                        <!--[if lte IE 6]></td></tr></table></a><![endif]-->


	</li>

							</ul>     ';
	}
}
else
{
	echo '
	<li class="TSSEMenui"><a class= href="#top" onclick="return clickreturnvalue()" onmouseover="dropdownmenu(this, event,menu9, \'150px\')" onmouseout="delayhidemenu()">'.$lang->global['help'].'</a></li>
	
	<a href="'.$BASEURL.'/ts_tags.php">
		<script type="text/javascript">
			//<![CDATA[
			document.write(l_searchcloud);
			//]]>
		</script>
		</a>
		</ul></li>';
}
echo '

	</div></center></div>
<div class="bg-header">
<div id="header">


			

</div>
</div>
<div class="content">



<div class="torrentai">

<td class="" align="center">
				<iframe src="http://the-dragons-kingdom.org/include/templates/Radio/imagerotator.html" frameborder="0" width="1000px" height="299px" scrolling="no" align="center"></iframe>
			</td>
		
<div class="move">
<td class="" align="center"></td>
</div>
</div>
<div class="main-top">
</div>
	<div id="main">

	<div class="left_side">



';

if ($offlinemsg)
	$warnmessages[] = sprintf($lang->header['trackeroffline'], $BASEURL);

if (!$__ismod && isset($CURUSER) && $CURUSER['donoruntil'] != '0000-00-00 00:00:00' && warn_donor(strtotime($CURUSER['donoruntil']) - gmtime()))
{
	require_once(INC_PATH.'/functions_mkprettytime.php');
	$warnmessages[] = sprintf($lang->header['warndonor'], $BASEURL, mkprettytime(strtotime($CURUSER['donoruntil']) - gmtime()));
}

if($CURUSER['downloaded'] > 0 && $CURUSER['leechwarn'] == 'yes' AND strtotime($CURUSER['leechwarnuntil']) > TIMENOW)
{
	include_once(INC_PATH.'/readconfig_cleanup.php');
	require_once(INC_PATH.'/functions_mkprettytime.php');
	$warnmessages[] = sprintf($lang->header['warned'], $leechwarn_remove_ratio, mkprettytime(strtotime($CURUSER['leechwarnuntil']) - TIMENOW));
}
if (isset($CURUSER) AND $CURUSER['announce_read'] == 'no')
	$infomessages[] = '<span id="new_ann" style="display: block;"><a href="'.$BASEURL.'/clear_ann.php" title="" rel="iframe.1" rev="width:650 height:350 scrolling:yes">'.$lang->header['newann'].'</a></span>';

if ($CURUSER['pmunread'] > 0 AND $msgalert)
	$infomessages[] = '<a href="'.$BASEURL.'/messages.php">'.sprintf($lang->header['newmessage'], ts_nf($CURUSER['pmunread'])).'</a>';

if (isset($nummessages) AND $nummessages > 0)
	$infomessages[] = '<a href="'.$BASEURL.'/admin/index.php?act=staffbox">'.sprintf($lang->header['staffmess'], $nummessages).'</a>';

if (isset($numreports) AND $numreports > 0)
	$infomessages[] = '<a href="'.$BASEURL.'/admin/index.php?act=reports">'.sprintf($lang->header['newreport'], $numreports).'</a>';

if (isset($warnmessages))
{
	echo show_notice(implode('<br />',$warnmessages), true);
	unset($warnmessages);
}

if (isset($infomessages))
{
	echo show_notice(implode('<br />',$infomessages));
	unset($infomessages);
}

if (!defined('DISABLE_ADS') AND ($ads = @file_get_contents(TSDIR.'/admin/ads.txt')))
{
	$str  = '<table class="main" border="1" cellspacing="0" cellpadding="0" width="100%"><tr><td class="text">';
	if (strstr($ads, '[TS_ADS]'))
	{
		$ts_ads_count = explode('[TS_ADS]', $ads);
		$random_ts_ads = rand(0, (count($ts_ads_count) -1));
		$str .= $ts_ads_count[$random_ts_ads];
	}
	else
		$str .= $ads;
	$str .= '</td></tr></table><br />';
	echo $str;
	unset($ads, $str);
}
?>