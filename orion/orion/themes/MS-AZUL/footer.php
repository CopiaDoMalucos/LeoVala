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
</td>
    </tr>
  </table>
<table width="99%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td class="foooter" valign="bottom" colspan="0">
<div align="center" >
<CENTER><?php $totaltime = array_sum(explode(" ", microtime())) - $GLOBALS['tstart'];  printf("Página carregada em %.4f segundos.", $totaltime); ?><br>
BR - Some Rights Reserved. | <a href="http://www.brshares.com/licenca.php">Termo de uso</a>| <a href="http://creativecommons.org/licenses/by-nc-nd/2.5/br/">Creative Commons License</a>

</CENTER>



	
	
	</div>
	</tr>
</td>

  </table>

</body>
</html>
