<?
function textbbcode1($form,$name,$dossier1,$content="") {
?>
<script language=javascript>
function SmileIT(smile,form,text){
document.forms[form].elements[text].value = document.forms[form].elements[text].value+" "+smile+" ";
document.forms[form].elements[text].focus();
function Reply(smile,form,text){document.forms[form].elements[text].value=document.forms[
form].elements[text].value+" "+smile+" ";document.forms[form].elements[text].focus();}
}
</script>
<script language=javascript>
	function bbcomment(repdeb, repfin)
	{
  var input = document.forms["<?=$form?>"].elements["<?=$name?>"];
  input.focus();
  if(typeof document.selection != 'undefined') 
  		{
    var range = document.selection.createRange();
    var insText = range.text;
    range.text = repdeb + insText + repfin;
    range = document.selection.createRange();
    	if (insText.length == 0) 
			{	
      range.move('character', -repfin.length);
   			} 
		else{
      range.moveStart('character', repdeb.length + insText.length + repfin.length);
    		}
    range.select();
  		}
  else if(typeof input.selectionStart != 'undefined')
  		{
    var start = input.selectionStart;
    var end = input.selectionEnd;
    var insText = input.value.substring(start, end);
    input.value = input.value.substr(0, start) + repdeb + insText + repfin + input.value.substr(end);
    var pos;
    	if (insText.length == 0) 
			{
      pos = start + repdeb.length;
    		} 
		else{
      pos = start + repdeb.length + insText.length + repfin.length;
    		}
    input.selectionStart = pos;
    input.selectionEnd = pos;
  		}
  else
  		{
    var pos;
    var re = new RegExp('^[0-9]{0,3}$');
    while(!re.test(pos)){
      pos = prompt("Insertion Ã  la position (0.." + input.value.length + "):", "0");
    					}
    	if(pos > input.value.length) 
			{
      pos = input.value.length;
    		}
    var insText = prompt("Veuillez entrer le texte Ã  formater:");
    input.value = input.value.substr(0, pos) + repdeb + insText + repfin + input.value.substr(pos);
  		}
	}
	
	function wrap(v,r,e)
	{
		var r = r ? r : "";
		var v = v ? v : "";
		var e = e ? e : "";
		
		var obj = document.getElementById("<?=$name?>");
		var len = obj.value.length;
		var start = obj.selectionStart;
		var end = obj.selectionEnd;
		var sel = obj.value.substring(start, end);
		
		obj.value =  obj.value.substring(0,start) + "[" + v +(e ? "="+e : "")+"]" + (r ? r : sel) + "[/" + v + "]" + obj.value.substring(end,len);
		obj.focus();
	}
	function clink()
	{
		var linkTitle;
		var linkAddr;
		
		linkAddr = prompt("<?echo"".Please_enter_the_full_URL."";?>","http://");
		if(linkAddr && linkAddr != "http://")
		linkTitle = prompt("<?echo"".Please_enter_the_title."";?>", " ");
		
	  if(linkAddr && linkTitle)
		wrap('url',linkTitle,linkAddr);
	  
	}
	function cimage()
	{
		var link;
				link = prompt("<?echo"".Please_enter_the_full_URL_for_your_image."";?>\n<?echo"".Only."";?> .png, .jpg, .gif images","http://");
		var re_text = /\.jpg|\.gif|\.png|\.jpeg/i;
		if(re_text.test(link) == false && link != "http://" && link) {
				alert("<?echo"".Image_not_allowed_only."";?> .jpg .gif .png .jpeg");
				link = prompt("<?echo"".Please_enter_the_full_URL_for_your_image."";?>\n<?echo"".Only."";?> .png, .jpg, .gif images","http://");
				}
	  if(link != "http://" && link)
		wrap('img',link,'');
	  
	}
	function tag(v)
	{
		wrap(v,'','');
	}
	function mail()
	{
		var email = ""; 
		email = prompt("<?echo"".Please_enter_the_email_addres."";?>"," ");
		var filter = /^[\w.-]+@([\w.-]+\.)+[a-z]{2,6}$/i;
		if (!filter.test(email) && email.length > 1) {
			alert("<?echo"".Please_provide_a_valid_email_address."";?>");
			email = prompt("<?echo"".Please_enter_the_email_addres."";?>"," ");
		}
		if(email.length > 1)
		wrap('mail',email,'');
	}
	function text(to)
	{
		var obj = document.getElementById("<?=$name?>");
		
		if (document.selection)
		{
			var str = document.selection.createRange().text;
			obj.focus();
			var sel = document.selection.createRange();
			sel.text = (to == 'up' ? str.toUpperCase() : str.toLowerCase())
        }
			else 
		{
			var len = obj.value.length;
			var start = obj.selectionStart;
			var end = obj.selectionEnd;
			var sel = obj.value.substring(start, end);
			obj.value =  obj.value.substring(0,start) + (to == 'up' ? sel.toUpperCase() : sel.toLowerCase()) + obj.value.substring(end,len);
		}
		obj.focus();
	
	}
	function fonts(w)
	{
		var fmin = 12; var fmax = 24;
		var obj = document.getElementById("<?=$name?>");
		var size = obj.style.fontSize;
		size = (parseInt(size));
			var nsize ;
		if(w == 'up' && (size+1 < fmax))
			nsize = (size+1)+"px";
		if(w == 'down' && (size-1 > fmin))
			nsize = (size-1)+"px";
		
		obj.style.fontSize = nsize;
		obj.focus();
	}
	
	
	function em(f)
	{
			var obj = document.getElementById("<?=$name?>");
			var len = obj.value.length;
			var start = obj.selectionStart;
			var end = obj.selectionEnd;
			var sel = obj.value.substring(start, end);
			obj.value =  obj.value.substring(0,start) +f+ obj.value.substring(end,len);
			obj.focus();
			
	}
	
	function PopMoreSmiles(form,name) 
	{
         link='backend/smilies.php?action=display&form='+form+'&text='+name
         newWin=window.open(link,'moresmile','height=600,width=160,resizable=no,scrollbars=yes');
         if (window.focus) {newWin.focus()}
	}
	
	function bbcouleur(couleur) 
	{
		bbcomment("[color="+couleur+"]", "[/color]");
	}
	
	function bbfont(font) 
	{
		bbcomment("[font="+font+"]", "[/font]");
	}
	
	function bbsize(taille) 
	{
		bbcomment("[size="+taille+"]", "[/size]");
	}
	function bbimgvar(tailleimgvar) 
	{
		bbcomment("[fil="+tailleimgvar+"]", "[/fil]");
	}
	var timer=0;
	var ptag=String.fromCharCode(5,6,7);
	
	function  visualisation() {
	t=document.forms["<?=$form?>"].elements["<?=$name?>"].value  
	t=code_to_html(t)
	if (document.getElementById) document.getElementById("previsualisation").innerHTML=t
	if (document.formu.auto.checked) timer=setTimeout(visualisation,1000)
								}

function code_to_html(t) {

	t=nl2khol(t)
// balise Center
	t=deblaie(/(\[\/center\])/g,t)
	t=remplace_tag(/\[center\](.+)\[\/center\]/g,'<center>$1</center>',t)  
	t=remblaie(t)		
// balise Gras
	t=deblaie(/(\[\/b\])/g,t)
	t=remplace_tag(/\[b\](.+)\[\/b\]/g,'<b>$1</b>',t)  
	t=remblaie(t)
// balise Italique
	t=deblaie(/(\[\/i\])/g,t)
	t=remplace_tag(/\[i\](.+)\[\/i\]/g,'<i>$1</i>',t)  
	t=remblaie(t)
// balise Underline
	t=deblaie(/(\[\/u\])/g,t)
	t=remplace_tag(/\[u\](.+)\[\/u\]/g,'<u>$1</u>',t)  
	t=remblaie(t)
// balise quote
	t=deblaie(/(\[\/quote\])/g,t)
	t=remplace_tag(/\[quote\](.+)\[\/quote\]/g,'<p class=sub><b>Citation:</b></p><table class=main border=1 cellspacing=0 cellpadding=10><tr><td style="border: 1px black dotted">$1</td></tr></table>',t)  
	t=remblaie(t)

// balise blink	
	t=deblaie(/(\[\/blink\])/g,t)
	t=remplace_tag(/\[blink\](.+)\[\/blink\]/g,'<blink>$1</blink>',t)  
	t=remblaie(t)
// balise df	
	t=deblaie(/(\[\/df\])/g,t)
	t=remplace_tag(/\[df\](.+)\[\/df\]/g,'<marquee>$1</marquee>',t)  
	t=remblaie(t)
// balise [audio]..[/audio]
	t=deblaie(/(\[\/audio\])/g,t)
	t=remplace_tag(/\[audio\]((www.|http:\/\/|https:\/\/)[^\s]+(\.mp3))\[\/audio\]/g,'<param name=movie value=$1/><embed width=470 height=310 src=$1></embed>',t)  
	t=remblaie(t)	
	
	

// balise [swf]..[/swf]
	t=deblaie(/(\[\/swf\])/g,t)
	t=remplace_tag(/\[swf\]((http|https):\/\/[^<>\s]+?)\[\/swf\]/g,'<embed autostart=false loop=false controller=true width=400 height=200 src=$1></embed>',t)  
	t=remblaie(t)

// balise [video]..[/video] pour youtube
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video\][^\s'\"<>]*youtube.com.*v=([^\s'\"<>]+)\[\/video\]/img,'<object width="680" height="440"><param name="movie" value="http://www.youtube.com/v/$1"></param><embed src="http://www.youtube.com/v/$1" type="application/x-shockwave-flash" width="680" height="440"></embed></object>',t)  
	t=remblaie(t)
// balise [video=...] pour youtube
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video=[^\s'\"<>]*youtube.com.*v=([^\s'\"<>]+)\]/img,'<object width="680" height="440"><param name="movie" value="http://www.youtube.com/v/$1"></param><embed src="http://www.youtube.com/v/$1" type="application/x-shockwave-flash" width="680" height="440"></embed></object>',t)  
	t=remblaie(t)
// balise [video]..[/video] pour mp4
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video\]((www.|http:\/\/|https:\/\/)[^\s]+(\.mp4))\[\/video\]/g,'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="680" height="440" id="player1" name="player1"><param name="movie" value="$1"><param name="allowfullscreen" value="true"><param name="allowscriptaccess" value="always"><embed  src="$1" name="player1"  width="680"  height="440" allowscriptaccess="always" allowfullscreen="true"></embed></object>',t)  
	t=remblaie(t)
// balise [video]..[/video] pour wmv
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video\]((www.|http:\/\/|https:\/\/)[^\s]+(\.wmv))\[\/video\]/g,'<param name=filename value=$1/><embed width=680 height=440 src=$1></embed>',t)  
	t=remblaie(t)	
// balise [video]..[/video] pour dailymotion
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video\][^\s'\"<>]*dailymotion.com\/video\/([^\s'\"<>]+)\[\/video\]/img,'<object width="680" height="440"><param name="movie" value="http://www.dailymotion.com/swf/$1"></param><embed src="http://www.dailymotion.com/swf/$1" type="application/x-shockwave-flash" width="680" height="440"></embed></object>',t)  
	t=remblaie(t)	
// balise [video]..[/video] pour google video
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video\][^\s'\"<>]*video.google.com.*docid=(-?[0-9]+).*\[\/video\]/img,'<embed style="width:680px; height:440px;" id="VideoPlayback" align="middle" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=$1" allowScriptAccess="sameDomain" quality="best" bgcolor="#ffffff" scale="noScale" wmode="window" salign="TL"  FlashVars="playerMode=embedded"></embed>',t)  
	t=remblaie(t)	
// balise font	
	t=deblaie(/(\[\/font\])/g,t)
	t=remplace_tag(/\[font=(#[a-fA-F0-9]{6})\](.+)\[\/font\]/g,'<font face="$1">$2</font>',t)
	t=remblaie(t)
	t=deblaie(/(\[\/font\])/g,t)
    t=remplace_tag(/\[font=([a-zA-Z]+)\]((\s|.)+?)\[\/font\]/g,'<font face="$1">$2</font>',t)
    t=remblaie(t)
//balise fil 
	t=deblaie(/(\[\/fil\])/g,t)
	t=remplace_tag(/\[fil=([0-9]{1,3})\](http|https:\/\/[^\s'\"<>]+(\.(jpg|jpeg|gif|png)))\[\/fil\]/g,'<img src="$2"" width="$1">',t)
	t=remblaie(t)
	t=deblaie(/(\[\/color\])/g,t)
// balise code	
	t=deblaie(/(\[\/code\])/g,t)
	t=remplace_tag(/\[code\](.+)\[\/code\]/g,'<p class=sub><b>Code:</b></p><table class=main border=1 cellspacing=0 cellpadding=10><tr><td style="border: 1px black dotted">$1</td></tr></table>',t)  
	t=remblaie(t)
// balise Img
	t=deblaie(/(\[\/img\])/g,t)
	t=remplace_tag(/\[img\](.+)\[\/img\]/g,'<img src="$1" />',t)
	t=remblaie(t)
	
// balise Img
	t=deblaie(/(\[\/screem\])/g,t)
	t=remplace_tag(/\[screem\](.+)\[\/screem\]/g,'<a href="$1" target=_blank><img style=max-width:400px; src="$1" /><br><small>[ Ver em tamanho real ]</small></a>',t)
	t=remblaie(t)
		

	
	
	
	
	
// balise URL	
	t=deblaie(/(\[\/url\])/g,t)
    t=remplace_tag(/\[url=([^\s<>]+)\](.+)\[\/url\]/g,'<a href="$1" target="_blank">$2</a>',t)
    t=remblaie(t)

    t=deblaie(/(\[\/url\])/g,t)
    t=remplace_tag(/\[url\]([^\s<>]+)\[\/url\]/g,'<a href="$1" target="_blank">$1</a>',t)
    t=remblaie(t)

    t=remplace_tag(/\[\/url\]/g,'</a>',t)
    t=remblaie(t)
// balise Couleur	
	t=deblaie(/(\[\/color\])/g,t)
	t=remplace_tag(/\[color=(#[a-fA-F0-9]{6})\](.+)\[\/color\]/g,'<font color="$1">$2</font>',t)
	t=remblaie(t)
	t=deblaie(/(\[\/color\])/g,t)
    t=remplace_tag(/\[color=([a-zA-Z]+)\]((\s|.)+?)\[\/color\]/g,'<font color="$1">$2</font>',t)
    t=remblaie(t)
// alignement
	t=deblaie(/(\[\/align\])/g,t)
    t=remplace_tag(/\[align=([a-zA-Z]+)\]((\s|.)+?)\[\/align\]/g,'<div style="text-align:$1">$2</div>',t)
    t=remblaie(t)
// balise size	
	t=deblaie(/(\[\/size\])/g,t)
	t=remplace_tag(/\[size=([+-]?[0-9])\](.+)\[\/size\]/g,'<font size="$1">$2</font>',t)
	t=remblaie(t)
	t=unkhol(t)
	t=nl2br(t)
// balise mail
	t=deblaie(/(\[\/mail\])/g,t)
	t=remplace_tag(/\[mail\](.+)\[\/mail\]/g,'<a href="mailto:$1" target="_blank">$1</a>',t)  
	t=remblaie(t)
//smilies
	t=remplace_tag(/:smile1/g,'<img src="images/smilies/smile1.gif" alt="" />',t) 
	t=remplace_tag(/:smile2/g,'<img src="images/smilies/smile2.gif" alt="" />',t) 
	t=remplace_tag(/:grin/g,'<img src="images/smilies/grin.gif" alt="" />',t) 
	t=remplace_tag(/:w00t/g,'<img src="images/smilies/w00t.gif" alt="" />',t) 
	
	t=remplace_tag(/:tongue/g,'<img src="images/smilies/tongue.gif" alt="" />',t) 
	t=remplace_tag(/:wink/g,'<img src="images/smilies/wink.gif" alt="" />',t) 
	t=remplace_tag(/n_oexpression/g,'<img src="images/smilies/noexpression.gif" alt="" />',t) 
	t=remplace_tag(/:confused/g,'<img src="images/smilies/confused.gif" alt="" />',t) 
	
	t=remplace_tag(/:sad/g,'<img src="images/smilies/sad.gif" alt="" />',t)
	t=remplace_tag(/:baby/g,'<img src="images/smilies/baby.gif" alt="" />',t) 
	t=remplace_tag(/:ohmy/g,'<img src="images/smilies/ohmy.gif" alt="" />',t) 
	t=remplace_tag(/:s_leeping/g,'<img src="images/smilies/sleeping.gif" alt="" />',t) 
	
	t=remplace_tag(/:cool1/g,'<img src="images/smilies/cool1.gif" alt="" />',t) 
	t=remplace_tag(/:unsure/g,'<img src="images/smilies/unsure.gif" alt="" />',t) 
	t=remplace_tag(/:closedeyes/g,'<img src="images/smilies/closedeyes.gif" alt="" />',t) 
	t=remplace_tag(/:cool2/g,'<img src="images/smilies/cool2.gif" alt="" />',t) 
	
	t=remplace_tag(/:thumbsup/g,'<img src="images/smilies/thumbsup.gif" alt="" />',t) 
	t=remplace_tag(/:blush/g,'<img src="images/smilies/blush.gif" alt="" />',t) 
	t=remplace_tag(/:yes/g,'<img src="images/smilies/yes.gif" alt="" />',t) 
	t=remplace_tag(/:no/g,'<img src="images/smilies/no.gif" alt="" />',t) 
	
	t=remplace_tag(/:love/g,'<img src="images/smilies/love.gif" alt="" />',t) 
	t=remplace_tag(/:question/g,'<img src="images/smilies/question.gif" alt="" />',t) 
	t=remplace_tag(/:excl/g,'<img src="images/smilies/excl.gif" alt="" />',t) 
	t=remplace_tag(/:idea/g,'<img src="images/smilies/idea.gif" alt="" />',t) 
	
	t=remplace_tag(/:arrow/g,'<img src="images/smilies/arrow.gif" alt="" />',t) 
	t=remplace_tag(/:ras/g,'<img src="images/smilies/ras.gif" alt="" />',t) 
	t=remplace_tag(/:hmm/g,'<img src="images/smilies/hmm.gif" alt="" />',t) 
	t=remplace_tag(/:laugh/g,'<img src="images/smilies/laugh.gif" alt="" />',t) 
    
	t=remplace_tag(/:mario/g,'<img src="images/smilies/mario.gif" alt="" />',t) 
	t=remplace_tag(/:rolleyes/g,'<img src="images/smilies/rolleyes.gif" alt="" />',t) 
	t=remplace_tag(/:kiss/g,'<img src="images/smilies/kiss.gif" alt="" />',t) 
	t=remplace_tag(/:shifty/g,'<img src="images/smilies/shifty.gif" alt="" />',t) 
	
	t=remblaie(t)
	
	return t

	}

function deblaie(reg,t) {
	texte=new String(t);
	return texte.replace(reg,'$1\n');
}
function remblaie(t) {
	texte=new String(t);
	return texte.replace(/\n/g,'');
}
function remplace_tag(reg,rep,t) {
	texte=new String(t);
	return texte.replace(reg,rep);
}
function nl2br(t) {
	texte=new String(t);
	return texte.replace(/\n/g,'<br/>');
}
function nl2khol(t) {
	texte=new String(t);
	return texte.replace(/\n/g,ptag);
}
function unkhol(t) {
	texte=new String(t);
	return texte.replace(new RegExp(ptag,'g'),'\n');
}


	
	</script>
<table cellpadding="5" cellspacing="0" align="center"  border="1" class="bb_holder">
  <tr>
    <td width="100%" style="background:#CCCCCC; padding:0" colspan="2">
    	
		<div style="float:left;padding:4px 0px 0px 2px;"> 
        
		<img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_bold1.gif" onclick="bbcomment('[b]', '[/b]')" title="Negrito" alt="Gras"/>
        <img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_italic.gif"  onclick="bbcomment('[i]', '[/i]')" title="Italico" alt="Italique" /> 
        <img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_underline.gif"  onclick="bbcomment('[u]', '[/u]')" title="Sublinhado" alt="Souligne" /> 
        <img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_blink.gif" onclick="bbcomment('[blink]', '[/blink]')" title="Pisca" alt="Blink" /> 
        <img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_marquee.gif" onclick="bbcomment('[df]', '[/df]')" title="Defile" alt="Defile" />
		
		<img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_url.gif" onclick="clink()" title="Link" alt="Lien" /> 
        <img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_image.gif" onclick="cimage()" title="Adcionar imagem" alt="Image"/> 
        <img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_email.gif" onclick="mail()" title="Adcionar Email" alt="Email" /> 
		<img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_flash.gif" onclick="bbcomment('[swf]', '[/swf]')" title="Adcionar swf" alt="swf" /> 
   		<img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_code.gif" onclick="bbcomment('[code]', '[/code]')" title="Adcionar Código" alt="code" /> 
		<img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_quote.gif" onclick="bbcomment('[quote]', '[/quote]')" title="Adcionar quote" alt="quote" /> 
  		<img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_audio.gif" onclick="bbcomment('[audio]', '[/audio]')" title="Adcionar audio" alt="audio" /> 
		<img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_video.gif" onclick="bbcomment('[video]', '[/video]')" title="Adcionar video" alt="video" /> 
   		</div>
   
      	<div style="float:right;padding:4px 2px 0px 0px;"> 
		
		<img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_center.gif" onclick="bbcomment('[align=center]','[/align]')" title="Align - centre" alt="Centre" />
		<img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_left.gif" onclick="bbcomment('[align=left]','[/align]')" title="Align - gauche" alt="Gauche" /> 
        <img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_justify.gif" onclick="bbcomment('[align=justify]','[/align]')" title="Align - justifie" alt="justifie" />
		<img class="bb_icon" src="images/bbcode/<? echo $dossier1;?>/bbcode_right.gif" onclick="bbcomment('[align=right]','[/align]')" title="Align - Droite" alt="Droite" /> 
      	
		</div>
     </td>
  </tr>
  
  <tr>
    <td width="100%" style="background:#CCCCCC; padding:0;" colspan="2">
    	<div style="float:left;padding:4px 0px 0px 2px;">
    		<select name="font"   class="bb_icon" style="padding-bottom:3px;" onChange="bbfont(this.value);" title="Font">
          		<option value="Arial">Font</option>
 	         	<option value="Arial" style="font-family: Arial;">Arial</option>
    	      	<option value="Arial Black" style="font-family: Arial Black;">Arial Black</option>
        	  	<option value="Comic Sans MS" style="font-family: Comic Sans MS;">Comic Sans MS</option>
          		<option value="Courier New" style="font-family: Courier New;">Courier New</option>
	          	<option value="Franklin Gothic Medium" style="font-family: Franklin Gothic Medium;">Franklin Gothic Medium</option>
	          	<option value="Georgia" style="font-family: Georgia;">Georgia</option>
   		       	<option value="Helvetica" style="font-family: Helvetica;">Helvetica</option>
        	  	<option value="Impact" style="font-family: Impact;">Impact</option>
        	  	<option value="Lucida Console" style="font-family: Lucida Console;">Lucida Console</option>
          		<option value="Lucida Sans Unicode" style="font-family: Lucida Sans Unicode;">Lucida Sans Unicode</option>
          		<option value="Microsoft Sans Serif" style="font-family: Microsoft Sans Serif;">Microsoft Sans Serif</option>
          		<option value="Palatino Linotype" style="font-family: Palatino Linotype;">Palatino Linotype</option>
          		<option value="Tahoma" style="font-family: Tahoma;">Tahoma</option>
          		<option value="Times New Roman" style="font-family: Times New Roman;">Times New Roman</option>
          		<option value="Trebuchet MS" style="font-family: Trebuchet MS;">Trebuchet MS</option>
          		<option value="Verdana" style="font-family: Verdana;">Verdana</option>
          		<option value="Symbol" style="font-family: Symbol;">Symbol</option>
        	</select>
		
			<select name="size"  class="bb_icon" style="padding-bottom:3px;" onchange="bbsize(this.value);" title="Taille">
				<option value="1"><? echo"".Tamanho."";?></option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				
        	</select>
        
			<select name="couleur" class="bb_icon" style="padding-bottom:3px;" onChange="bbcouleur(this.value);">
				<option ><? echo"".Cores."";?></option>
				<option value=blue style=color:blue>Azul</option>
				<option value=darkblue style=color:darkblue>Azul Escuro</option>
				<option value=indigo style=color:indigo>Roxo</option>
				<option value=sienna style=color:sienna>Marrom</option>
				<option value=red style=color:red>Vermelho</option>
				<option value=orange style=color:orange>Laranja</option>
				<option value=deeppink style=color:deeppink>Rosa</option>
				<option value=green style=color:green>Verde</option>
				<option value=silver style=color:silver>Cinza</option>
			</select>
    		
			<select name="imgvar"  class="bb_icon" style="padding-bottom:3px;" onchange="bbimgvar(this.value);">
				<option value="50"><? echo"".Tamanho_da_imagem."";?></option>
				<option value="50">50</option>
				<option value="100">100</option>
				<option value="150">150</option>
				<option value="200">200</option>
				<option value="250">250</option>
				<option value="300">300</option>
				
        	</select>
		
		
		</div>
     	 <div style="float:right;padding:4px 2px 0px 0px;"> 
      		<img class="bb_icon" src="images/bbcode/text_uppercase.png" onclick="text('up')" title="Caixa Alta" alt="Up" /> 
        	<img class="bb_icon" src="images/bbcode/text_lowercase.png" onclick="text('low')" title="Caixa Baixa" alt="Low" /> 
        	<img class="bb_icon" src="images/bbcode/zoom_in.png" onclick="fonts('up')" title="Zoom +" alt="S up" /> 
        	<img class="bb_icon" src="images/bbcode/zoom_out.png" onclick="fonts('down')" title="Zoom -" alt="S down" />
     		<a href="javascript: PopMoreSmiles('<? echo $form; ?>','<? echo $name; ?>')"><img  class="bb_icon" src=images/bbcode/<? echo $dossier1;?>/bbcode_smilie.gif border=0 alt='Plus emoticone'></a>
		</div>
	</td>
  </tr>
  
  <tr>
    <td><textarea id="<?=$name?>" name="<?=$name?>" style="width:560px; height:230px;font-size:12px;"><?=$content?></textarea></td>
	<td align="center" valign="top">
		<table width="0" cellpadding="2" border="1" class="em_holder" cellspacing="2">
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
	</td>
  </tr>
  <tr>
    <td width="100%" colspan="2">
<center><input type="button" value="<? echo"".Visualizar."";?>" onClick="visualisation()"></center>
	</td>
	</tr>
</table>
<table align="center" width="50%">
	<tr>
    	<td>
			<span id="previsualisation"></span>
		</td>
	</tr>
</table>
<?
}
?>
