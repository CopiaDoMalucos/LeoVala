</div>
</td>
<script type="text/javascript" src="js/scrolltopcontrol.js"></script>
<!-- END MAIN COLUM -->
<!-- START RIGHT COLUMN -->
<?php if ($site_config["RIGHTNAV"]){ ?>

<td width='170' valign='top' id='righcolumn'>
<div id="right_outer">
<?php rightblocks(); ?>
</div>
</td>
<?php } ?>
<!-- END RIGHT COLUMN -->

    </tr>
  </table>
<?php
$totaltime = array_sum(explode(" ", microtime())) - $GLOBALS['tstart'];
?>

  <table width="99%" border="0" cellspacing="2" cellpadding="0" align="center">
    <tr>
<td align="center" id="copyright" >
<?php printf("Página carregada em %.4f", $totaltime); ?>
<br>
MS - Some Rights Reserved. | <a href="http://www.malucos-share.org/licenca.php">Termo de uso</a>| <a href="http://creativecommons.org/licenses/by-nc-nd/2.5/br/">Creative Commons License</a>

	</td>
	    </tr>
  </table>
  <br>
</body>
</html>
