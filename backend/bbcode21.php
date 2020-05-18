<?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2012-07-13 11:04:04 +0100 (Fri, 13 Jul 2012) $
//      $LastChangedBy: torrenttrader $
//
//      Re-designed by: Nikkbu
//      http://www.torrenttrader.org
//
//

function textbbcode($form,$name,$content="") {
	//$form = form name
	//$name = textarea name
	//$content = textarea content (only for edit pages etc)
?>
<script type="text/javascript">

function BBTag(tag,s,text,form){
switch(tag)
    {
    case '[quote]':
	var start = document.forms[form].elements[text].selectionStart;
	var end = document.forms[form].elements[text].selectionEnd;
	if (start != end) {
		var body = document.forms[form].elements[text].value;
		var left = body.substr(body, start);
		var middle = "[quote]" + body.substring(start, end) + "[/quote]";
		var right = body.substr(end, body.length);
		document.forms[form].elements[text].value = left + middle + right;
	} else {
		document.forms[form].elements[text].value = document.forms[form].elements[text].value + "[quote][/quote]";
	}
        break;
    case '[img]':
	var start = document.forms[form].elements[text].selectionStart;
	var end = document.forms[form].elements[text].selectionEnd;
	if (start != end) {
		var body = document.forms[form].elements[text].value;
		var left = body.substr(body, start);
		var middle = "[img]" + body.substring(start, end) + "[/img]";
		var right = body.substr(end, body.length);
		document.forms[form].elements[text].value = left + middle + right;
	} else {
		document.forms[form].elements[text].value = document.forms[form].elements[text].value + "[img][/img]";
	}
        break;
    case '[url]':
	var start = document.forms[form].elements[text].selectionStart;
	var end = document.forms[form].elements[text].selectionEnd;
	if (start != end) {
		var body = document.forms[form].elements[text].value;
		var left = body.substr(body, start);
		var middle = "[url]" + body.substring(start, end) + "[/url]";
		var right = body.substr(end, body.length);
		document.forms[form].elements[text].value = left + middle + right;
	} else {
		document.forms[form].elements[text].value = document.forms[form].elements[text].value + "[url][/url]";
	}
        break;
    case '[*]':
        document.forms[form].elements[text].value = document.forms[form].elements[text].value+"[*]";
        break;
    case '[b]':
	var start = document.forms[form].elements[text].selectionStart;
	var end = document.forms[form].elements[text].selectionEnd;
	if (start != end) {
		var body = document.forms[form].elements[text].value;
		var left = body.substr(body, start);
		var middle = "[b]" + body.substring(start, end) + "[/b]";
		var right = body.substr(end, body.length);
		document.forms[form].elements[text].value = left + middle + right;
	} else {
		document.forms[form].elements[text].value = document.forms[form].elements[text].value + "[b][/b]";
	}
        break;
    case '[i]':
	var start = document.forms[form].elements[text].selectionStart;
	var end = document.forms[form].elements[text].selectionEnd;
	if (start != end) {
		var body = document.forms[form].elements[text].value;
		var left = body.substr(body, start);
		var middle = "[i]" + body.substring(start, end) + "[/i]";
		var right = body.substr(end, body.length);
		document.forms[form].elements[text].value = left + middle + right;
	} else {
		document.forms[form].elements[text].value = document.forms[form].elements[text].value + "[i][/i]";
	}
        break;
    case '[u]':
	var start = document.forms[form].elements[text].selectionStart;
	var end = document.forms[form].elements[text].selectionEnd;
	if (start != end) {
		var body = document.forms[form].elements[text].value;
		var left = body.substr(body, start);
		var middle = "[u]" + body.substring(start, end) + "[/u]";
		var right = body.substr(end, body.length);
		document.forms[form].elements[text].value = left + middle + right;
	} else {
		document.forms[form].elements[text].value = document.forms[form].elements[text].value + "[u][/u]";
	}
        break;
    }
    document.forms[form].elements[text].focus();
}

</script>
<br />
<div class='b-border' style="margin-left:auto; margin-right:auto;">
<table align='center' border='0' cellpadding='6' cellspacing='0'>
  <tr class='b-title'>
    <th colspan="2" align='center' valign="middle"><table border="0" align="center" cellpadding="4" cellspacing="0">
        <tr>
          <td align="center"><input style="font-weight: bold;" type="button" name="bold" value="B " onclick="javascript: BBTag('[b]','bold','<?php echo $name; ?>','<?php echo $form; ?>')" /></td>
          <td align="center"><input style="font-style: italic;" type="button" name="italic" value="I " onclick="javascript: BBTag('[i]','italic','<?php echo $name; ?>','<?php echo $form; ?>')" /></td>
          <td align="center"><input style="text-decoration: underline;" type="button" name="underline" value="U " onclick="javascript: BBTag('[u]','underline','<?php echo $name; ?>','<?php echo $form; ?>')" /></td>
          <td align="center"><input type="button" name="li" value="List " onclick="javascript: BBTag('[*]','li','<?php echo $name; ?>','<?php echo $form; ?>')" /></td>
          <td align="center"><input type="button" name="quote" value="QUOTE " onclick="javascript: BBTag('[quote]','quote','<?php echo $name; ?>','<?php echo $form; ?>')" /></td>
          <td align="center"><input type="button" name="url" value="URL " onclick="javascript: BBTag('[url]','url','<?php echo $name; ?>','<?php echo $form; ?>')" /></td>
          <td align="center"><input type="button" name="img" value="IMG " onclick="javascript: BBTag('[img]','img','<?php echo $name; ?>','<?php echo $form; ?>')" /></td>
        </tr>
    </table>
    </th>  </tr>
  <tr class='b-row'>
    <td class='bb-comment' align='center' valign='top'><textarea name="<?php echo $name; ?>" rows="10" cols="50"><?php echo $content; ?></textarea></td>
    <td class='bb-btn' width='130' align="center" valign='top'>
      <table border="0" cellpadding="3" cellspacing="3" align="center">
      	<tr>
      <td align="center"><a href="javascript:em(':smile1');" ><img border="0" alt=" " src="images/smilies/smile1.gif" width="18" height="18" /></a></td>
      <td align="center"><a href="javascript:em(':smile2');" ><img border="0" alt=" " src="images/smilies/smile2.gif" width="18" height="18" /></a></td>
      <td align="center"><a href="javascript:em(':grin');" ><img border="0" alt=" " src="images/smilies/grin.gif" width="18" height="18" /></a></td>
      <td align="center"><a href="javascript:em(':w00t');" ><img border="0" alt=" " src="images/smilies/w00t.gif" width="18" height="20" /></a></td>
    </tr>
    <tr>
      <td align="center"><a href="javascript:em(':tongue');" ><img border="0" alt=" " src="images/smilies/tongue.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':wink');" ><img border="0" alt=" " src="images/smilies/wink.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':n_oexpression');" ><img border="0" alt=" " src="images/smilies/noexpression.gif" width="18" height="18" /></a></td>
      <td align="center"><a href="javascript:em(':confused');" ><img border="0" alt=" " src="images/smilies/confused.gif" width="18" height="18" /></a></td>
    </tr>
    <tr>
      <td align="center"><a href="javascript:em(':sad');" ><img border="0" alt=" " src="images/smilies/sad.gif" width="18" height="18" /></a></td>
      <td align="center"><a href="javascript:em(':baby');" ><img border="0" alt=" " src="images/smilies/baby.gif" width="20" height="22" /></a></td>
      <td align="center"><a href="javascript:em(':ohmy');" ><img border="0" alt=" " src="images/smilies/ohmy.gif" width="18" height="18" /></a></td>
      <td align="center"><a href="javascript:em(':s_leeping');" ><img border="0" alt=" " src="images/smilies/sleeping.gif" width="20" height="27" /></a></td>
    </tr>
    <tr>
      <td align="center"><a href="javascript:em(':cool1');" ><img border="0" alt=" " src="images/smilies/cool1.gif" width="18" height="22" /></a></td>
      <td align="center"><a href="javascript:em(':unsure');" ><img border="0" alt=" " src="images/smilies/unsure.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':closedeyes');" ><img border="0" alt=" " src="images/smilies/closedeyes.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':cool2');" ><img border="0" alt=" " src="images/smilies/cool2.gif" width="20" height="20" /></a></td>
    </tr>
    <tr>
      <td align="center"><a href="javascript:em(':thumbsup');" ><img border="0" alt=" " src="images/smilies/thumbsup.gif"  /></a></td>
      <td align="center"><a href="javascript:em(':blush');" ><img border="0" alt=" " src="images/smilies/blush.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':yes');" ><img border="0" alt=" " src="images/smilies/yes.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':no');" ><img border="0" alt=" " src="images/smilies/no.gif" width="20" height="20" /></a></td>
    </tr>
    <tr>
      <td align="center"><a href="javascript:em(':love');" ><img border="0" alt=" " src="images/smilies/love.gif" width="19" height="19" /></a></td>
      <td align="center"><a href="javascript:em(':question');" ><img border="0" alt=" " src="images/smilies/question.gif" width="19" height="19" /></a></td>
      <td align="center"><a href="javascript:em(':excl');" ><img border="0" alt=" " src="images/smilies/excl.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':idea');" ><img border="0" alt=" " src="images/smilies/idea.gif" width="19" height="19" /></a></td>
    </tr>
    <tr>
      <td align="center"><a href="javascript:em(':arrow');" ><img border="0" alt=" " src="images/smilies/arrow.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':ras');" ><img border="0" alt=" " src="images/smilies/ras.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':hmm');" ><img border="0" alt=" " src="images/smilies/hmm.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':laugh');" ><img border="0" alt=" " src="images/smilies/laugh.gif" width="20" height="20" /></a></td>
    </tr>
    <tr>
      <td align="center"><a href="javascript:em(':mario');" ><img border="0" alt=" " src="images/smilies/mario.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':rolleyes');" ><img border="0" alt=" " src="images/smilies/rolleyes.gif" width="20" height="20" /></a></td>
      <td align="center"><a href="javascript:em(':kiss');" ><img border="0" alt=" " src="images/smilies/kiss.gif" width="18" height="18" /></a></td>
      <td align="center"><a href="javascript:em(':shifty');" ><img border="0" alt=" " src="images/smilies/shifty.gif" width="20" height="20" /></a></td>
	</tr>
      </table>
      <br />
      <a href="javascript:PopMoreSmiles('<?php echo $form; ?>','<?php echo $name; ?>');"><?php echo "[".T_("MORE_SMILIES")."]";?></a><br />
      <a href="javascript:PopMoreTags();"><?php echo "[".T_("MORE_TAGS")."]";?></a><br />    </td>
  </tr>
</table>
</div>
<br />
<?php
}
?>
