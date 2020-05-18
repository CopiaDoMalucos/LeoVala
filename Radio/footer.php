<?php
if(!defined('IN_TRACKER')) die('Hacking attempt!');

echo'<br />
		</div>
	</div>

	</div>
	<center>
	<div class="bg-footer">
		<div id="footer">
			<div class="padding">
				Copyright by abc 2010 <font color="white"><a href="http://wwe.malucos-share.net" target="_self"><strong>.:: ABC ::.</strong></a></font>  [Executed in <b> 0.0354 </b>seconds]				
				</div>

		</div>


'.$alertpm.'

'.($CURUSER['options'] && preg_match('#N1#is', $CURUSER['options']) ?'

<!-- TS Auto DST Correction Code -->

<form action="'.$BASEURL.'/usercp.php?act=auto_dst" method="post" name="dstform">

	<input type="hidden" name="act" value="auto_dst" />

</form>

<script type="text/javascript">

<!--

	var tzOffset = '.$CURUSER['tzoffset'].' + '.(preg_match('#O1#is', $CURUSER['options']) ? '1' : '0').';

	var utcOffset = new Date().getTimezoneOffset() / 60;

	if (Math.abs(tzOffset + utcOffset) == 1)

	{	// Dst offset is 1 so its changed

		document.forms.dstform.submit();

	}

//-->

</script>

<!-- TS Auto DST Correction Code -->

' : '').($GLOBALS['ts_cron_image'] ? '

<!-- TS Auto Cronjobs code -->

	<img src="'.$BASEURL.'/ts_cron.php?rand='.TIMENOW.'" alt="" title="" width="1" height="1" border="0" />

<!-- TS Auto Cronjobs code -->

' : '').'</div></center>
		
	





         

</body>
</html>
';
/*
+-------------------------------------------------------------------------------------
| You have no permission to modify this file unless you purchase a Brading Free Product!
+-------------------------------------------------------------------------------------
*/
?>