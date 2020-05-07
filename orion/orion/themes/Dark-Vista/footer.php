</TD><!-- END MAIN CONTENT AREA -->

<? if ($site_config["RIGHTNAV"]){ ?>
<!-- RIGHT COLUMN -->
<TD vAlign="top" width="180">
<?rightblocks();?>
</TD>
<!-- END RIGHT COLUMN -->
<?}?>

</TR>
</TABLE>
<!-- END CONTENT CODE -->
</td>
</tr>
</table></td>
<td width="12" background="themes/Dark-Vista/images/body-mr.png"><img src="themes/Dark-Vista/images/blank.gif" width="12" height="12" /></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="14" height="30"><img src="themes/Dark-Vista/images/foot-l.png" width="14" height="30" /></td>
<td class="footer" align="center" background="themes/Dark-Vista/images/foot-bg.png">
<!-- START FOOTER CODE -->
<?
//
// *************************************************************************************************************************************
//			PLEASE DO NOT REMOVE THE POWERED BY LINE, SHOW SOME SUPPORT! WE WILL NOT SUPPORT ANYONE WHO HAS THIS LINE EDITED OR REMOVED!
// *************************************************************************************************************************************
print ("<CENTER>Powered by <a href=\"http://www.torrenttrader.org\" target=\"_blank\">TorrentTrader v".$site_config["ttversion"]."</a> - ");
$totaltime = array_sum(explode(" ", microtime())) - $GLOBALS['tstart'];
printf("Page generated in %f", $totaltime);
print ("<BR><a href=rss.php><img src=".$site_config["SITEURL"]."/images/icon_rss.gif height=11 width=11 border=0></a> <a href=rss.php>RSS Feed</a> - <a href=rss.php?custom=1>Feed Info</a> - Theme By: <a href=\"http://nikkbu.info\" target=\"_blank\">Ralphie & Nikkbu</a></CENTER>");
//
// *************************************************************************************************************************************
//			PLEASE DO NOT REMOVE THE POWERED BY LINE, SHOW SOME SUPPORT! WE WILL NOT SUPPORT ANYONE WHO HAS THIS LINE EDITED OR REMOVED!
// *************************************************************************************************************************************

?> 
<!-- END FOOTER CODE -->
</td>
<td width="14" height="30"><img src="themes/Dark-Vista/images/foot-r.png" width="14" height="30" /></td>
</tr>
</table><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="15" height="30"><img src="themes/Dark-Vista/images/body-bl.png" width="15" height="30" /></td>
<td height="30" background="themes/Dark-Vista/images/body-bm.png">&nbsp;</td>
<td width="15" height="30"><img src="themes/Dark-Vista/images/body-br.png" width="15" height="30" /></td>
</tr>
</table></td>
</tr>
</table>
</div>
</BODY>
</HTML>
<?
ob_end_flush();
?>