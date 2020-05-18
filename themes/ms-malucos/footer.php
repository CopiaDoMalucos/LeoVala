
</td>
<script type="text/javascript" src="js/scrolltopcontrol.js"></script>
<!-- END MAIN COLUM -->
<!-- START RIGHT COLUMN -->
<?php if ($site_config["RIGHTNAV"]){ ?>
<td width='50' valign='top'><?php rightblocks(); ?></td>
<?php } ?>
<!-- END RIGHT COLUMN -->

    </tr>
  </table>
  <br />
<!-- START FOOTER CODE -->
<?php
//
// *************************************************************************************************************************************
//			PLEASE DO NOT REMOVE THE POWERED BY LINE, SHOW SOME SUPPORT! WE WILL NOT SUPPORT ANYONE WHO HAS THIS LINE EDITED OR REMOVED!
// *************************************************************************************************************************************
print ("<CENTER>Powered by <a href=\"http://www.malucos-share.org\" target=\"_blank\">Malucos-share v".$site_config["ttversion"]."</a> - ");
$totaltime = array_sum(explode(" ", microtime())) - $GLOBALS['tstart'];
printf("Page generated in %f", $totaltime);
print (" - <a href='rss.php'><img src='".$site_config["SITEURL"]."/images/icon_rss.gif' border='0' width='13' height='13' alt='' /> - <a href=rss.php?custom=1>Feed Info</a> - Theme By: <a href=\"http://malucos-share.org\" target=\"_blank\">malucos-share</a></CENTER>");
//
// *************************************************************************************************************************************
//			PLEASE DO NOT REMOVE THE POWERED BY LINE, SHOW SOME SUPPORT! WE WILL NOT SUPPORT ANYONE WHO HAS THIS LINE EDITED OR REMOVED!
// *************************************************************************************************************************************
//
?>
<!-- END FOOTER CODE -->
<br />
</div>
<br /><br />
<div align="center" >
<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.pt_BR"><img alt="Licença Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/3.0/88x31.png" /></a><br />Este obra foi licenciado sob uma Licença <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.pt_BR">Licença Creative Commons</a>.
	</div>
</body>
</html>
