<?php
/*
+ ----------------------------------------------------------------------------+
|     MS-VERDE
|     ©Malucos-share 2012
|     Site: http://Malucos-share.org
|     
|    
|     Date: 27/08/2012
|     Author: malucos-share
+----------------------------------------------------------------------------+
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link type="text/css" rel="stylesheet" href="/min/?b=themes/MS-VERDE&amp;f=theme.css,ddlevelsfiles/ddlevelsmenu-base.css,ddlevelsfiles/ddlevelsmenu-topbar.css,ddlevelsfiles/ddlevelsmenu-sidebar.css" />
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $site_config["CHARSET"]; ?>" />
<meta name="author" content="Malucos, Malucos-share" />
<meta name="generator" content="Malucos-share <?php echo $site_config['ttversion']; ?>" />
<meta name="description" content="Malucos-share is a feature packed and highly customisable PHP/MySQL Based BitTorrent tracker. Featuring intergrated forums, and plenty of administration options. Please visit www.malucos-share.org for the support forums. " />
<meta name="keywords" content="http://malucos-share.org, http://www.malucos-share.org" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $site_config["SITEURL"]; ?>/themes/MS-VERDE/css/dropdown/dropdown.css" />
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-35280991-1', 'auto');
  ga('send', 'pageview');

</script>
<!-- CSS -->
<!-- Theme css -->
<link rel="shortcut icon" href="<?php echo $site_config["SITEURL"]; ?>/themes/MS-VERDE/images/favicon.ico" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $site_config["SITEURL"]; ?>/themes/MS-VERDE/css/style.css" />



<!--[if IE]>
    <link rel="stylesheet" type="text/css" href="<?php echo $site_config["SITEURL"]; ?>/themes/MS-VERDE/css/ie.css" />
<![endif]-->

<!-- JS -->



<style type="text/css">
.menutitle{
cursor:pointer;
margin-bottom: 5px;
background: rgb(135,224,253); /* Old browsers */
background: -moz-radial-gradient(center, ellipse cover,  rgba(135,224,253,1) 0%, rgba(83,203,241,1) 40%, rgba(5,171,224,1) 100%); /* FF3.6+ */
background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(135,224,253,1)), color-stop(40%,rgba(83,203,241,1)), color-stop(100%,rgba(5,171,224,1))); /* Chrome,Safari4+ */
background: -webkit-radial-gradient(center, ellipse cover,  rgba(135,224,253,1) 0%,rgba(83,203,241,1) 40%,rgba(5,171,224,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-radial-gradient(center, ellipse cover,  rgba(135,224,253,1) 0%,rgba(83,203,241,1) 40%,rgba(5,171,224,1) 100%); /* Opera 12+ */
background: -ms-radial-gradient(center, ellipse cover,  rgba(135,224,253,1) 0%,rgba(83,203,241,1) 40%,rgba(5,171,224,1) 100%); /* IE10+ */
background: radial-gradient(ellipse at center,  rgba(135,224,253,1) 0%,rgba(83,203,241,1) 40%,rgba(5,171,224,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#87e0fd', endColorstr='#05abe0',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
color:#000000;
width:140px;
padding:2px;
text-align:center;
font-weight:bold;
/*/*/border:1px solid #000000;/* */
}

.submenu{
margin-bottom: 0.5em;
}
</style>
<script type="text/javascript">

/***********************************************
* Switch Menu script- by Martial B of http://getElementById.com/
* Modified by Dynamic Drive for format & NS4/IE4 compatibility
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

var persistmenu="yes" //"yes" or "no". Make sure each SPAN content contains an incrementing ID starting at 1 (id="sub1", id="sub2", etc)
var persisttype="sitewide" //enter "sitewide" for menu to persist across site, "local" for this page only

if (document.getElementById){ //DynamicDrive.com change
document.write('<style type="text/css">\n')
document.write('.submenu{display: none;}\n')
document.write('</style>\n')
}

function SwitchMenu(obj){
	if(document.getElementById){
	var el = document.getElementById(obj);
	var ar = document.getElementById("masterdiv").getElementsByTagName("span"); //DynamicDrive.com change
		if(el.style.display != "block"){ //DynamicDrive.com change
			for (var i=0; i<ar.length; i++){
				if (ar[i].className=="submenu") //DynamicDrive.com change
				ar[i].style.display = "none";
			}
			el.style.display = "block";
		}else{
			el.style.display = "none";
		}
	}
}

function get_cookie(Name) { 
var search = Name + "="
var returnvalue = "";
if (document.cookie.length > 0) {
offset = document.cookie.indexOf(search)
if (offset != -1) { 
offset += search.length
end = document.cookie.indexOf(";", offset);
if (end == -1) end = document.cookie.length;
returnvalue=unescape(document.cookie.substring(offset, end))
}
}
return returnvalue;
}

function onloadfunction(){
if (persistmenu=="yes"){
var cookiename=(persisttype=="sitewide")? "switchmenu" : window.location.pathname
var cookievalue=get_cookie(cookiename)
if (cookievalue!="")
document.getElementById(cookievalue).style.display="block"
}
}

function savemenustate(){
var inc=1, blockid=""
while (document.getElementById("sub"+inc)){
if (document.getElementById("sub"+inc).style.display=="block"){
blockid="sub"+inc
break
}
inc++
}
var cookiename=(persisttype=="sitewide")? "switchmenu" : window.location.pathname
var cookievalue=(persisttype=="sitewide")? blockid+";path=/" : blockid
document.cookie=cookiename+"="+cookievalue
}

if (window.addEventListener)
window.addEventListener("load", onloadfunction, false)
else if (window.attachEvent)
window.attachEvent("onload", onloadfunction)
else if (document.getElementById)
window.onload=onloadfunction

if (persistmenu=="yes" && document.getElementById)
window.onunload=savemenustate
function ShoutPOP() {
var ShoutPOP = window.open("shoutbox.php", "Smilies","width=700,height=485,resizable=yes,scrollbars=yes,toolbar=no,location=no,directories=no,status=no");
} 
</script>
<!--[if lte IE 6]>
    <script type="text/javascript" src="<?php echo $site_config["SITEURL"]; ?>/themes/MS-VERDE/js/pngfix/supersleight-min.js"></script>
<![endif]-->

<script type="text/javascript" src="/min/?f=js/jquery.min.js,backend/java_klappe.js,ddlevelsfiles/ddlevelsmenu.js,js/jquery.js,js/jquery.idTabs.min.js"></script>
<script type="text/javascript" src="/min/?f=lytebox1/lytebox.js,scripts/ddaccordion.js"></script>
<script type="text/javascript" src="/min/?b=js&amp;f=jquery-1.9.1.js,jquery-ui.js"></script>
<script type="text/javascript" src="/js/verifica_mensagens.js"></script>

<script type="text/javascript" src="scripts/jquery.multiselect.js"></script>
<script type="text/javascript">
var $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ = jQuery.noConflict();
		$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$(function () {
			$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$("#sel_cat").multiselect({
				header: true,
				selectedText: '# categorias selecionadas',
				selectedList: 3,
				noneSelectedText: 'Todas as categorias'
			});
			$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$("#sel_ano").multiselect({
				header: true,
				selectedList: 5,
				noneSelectedText: 'Todos'
			});
			$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$("#sel_audio").multiselect({
				header: true,
				selectedList: 5,
				noneSelectedText: 'Todos'
			});
			$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$("#sel_extensao").multiselect({
				header: true,
				selectedList: 5,
				noneSelectedText: 'Todos'
			});
			$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$("#sel_qualidade").multiselect({
				header: true,
				selectedList: 5,
				noneSelectedText: 'Todos'
			});
			$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$("#sel_codec1").multiselect({
				header: true,
				selectedList: 5,
				noneSelectedText: 'Todos'
			});
			$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$("#sel_codec2").multiselect({
				header: true,
				selectedList: 5,
				noneSelectedText: 'Todos'
			});
			$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$("#sel_idioma").multiselect({
				header: true,
				selectedList: 5,
				noneSelectedText: 'Todos'
			});
		});
		
		</script>
<script type="text/javascript">
var $$$$$$$$$$$$$$$$$$$$ = jQuery.noConflict();
/**
************************************************************
*************** THIS IS THE NAVIGATION CODE ****************
************************************************************
**/

$$$$$$$$$$$$$$$$$$$$(function() {
    // Stick the #nav to the top of the window
    var nav = $$$$$$$$$$$$$$$$$$$$('#nav');
    var navHomeY = nav.offset().top;
    var isFixed = false;
    var $$$$$$$$$$$$$$$$$$$$w = $$$$$$$$$$$$$$$$$$$$(window);
    $$$$$$$$$$$$$$$$$$$$w.scroll(function() {
        var scrollTop = $$$$$$$$$$$$$$$$$$$$w.scrollTop();
        var shouldBeFixed = scrollTop > navHomeY;
        if (shouldBeFixed && !isFixed) {
            nav.css({
                position: 'fixed',
                top: 0,
                left: nav.offset().left,
                width: nav.width()
            });
            isFixed = true;
        }
        else if (!shouldBeFixed && isFixed)
        {
            nav.css({
                position: 'static'
            });
            isFixed = false;
        }
    });
});


/**
************************************************************
*** THIS IS THE ADD-IN SMOOTH SCROLLING CODE ***************
*** (see: http://www.dwuser.com/education/content/quick-guide-adding-smooth-scrolling-to-your-webpages/ )
************************************************************
**/


/**
 * SmoothScroll
 * This helper script created by DWUser.com.  Copyright 2012 DWUser.com.  
 * Dual-licensed under the GPL and MIT licenses.  
 * All individual scripts remain property of their copyrighters.
 * Date: 10-Sep-2012
 * Version: 1.0.1
 */
if (!window['jQuery']) alert('The jQuery library must be included before the smoothscroll.js file.  The plugin will not work propery.');

/**
 * jQuery.ScrollTo - Easy element scrolling using jQuery.
 * Copyright (c) 2007-2012 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * @author Ariel Flesler
 * @version 1.4.3.1
 */
;(function($){var h=$.scrollTo=function(a,b,c){$(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=$(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

/**
 * jQuery.LocalScroll
 * Copyright (c) 2007-2010 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * Date: 05/31/2010
 * @author Ariel Flesler
 * @version 1.2.8b
 **/
;(function(b){function g(a,e,d){var h=e.hash.slice(1),f=document.getElementById(h)||document.getElementsByName(h)[0];if(f){a&&a.preventDefault();var c=b(d.target);if(!(d.lock&&c.is(":animated")||d.onBefore&&!1===d.onBefore(a,f,c))){d.stop&&c._scrollable().stop(!0);if(d.hash){var a=f.id==h?"id":"name",g=b("<a> </a>").attr(a,h).css({position:"absolute",top:b(window).scrollTop(),left:b(window).scrollLeft()});f[a]="";b("body").prepend(g);location=e.hash;g.remove();f[a]=h}c.scrollTo(f,d).trigger("notify.serialScroll",
[f])}}}var i=location.href.replace(/#.*/,""),c=b.localScroll=function(a){b("body").localScroll(a)};c.defaults={duration:1E3,axis:"y",event:"click",stop:!0,target:window,reset:!0};c.hash=function(a){if(location.hash){a=b.extend({},c.defaults,a);a.hash=!1;if(a.reset){var e=a.duration;delete a.duration;b(a.target).scrollTo(0,a);a.duration=e}g(0,location,a)}};b.fn.localScroll=function(a){function e(){return!!this.href&&!!this.hash&&this.href.replace(this.hash,"")==i&&(!a.filter||b(this).is(a.filter))}
a=b.extend({},c.defaults,a);return a.lazy?this.bind(a.event,function(d){var c=b([d.target,d.target.parentNode]).filter(e)[0];c&&g(d,c,a)}):this.find("a,area").filter(e).bind(a.event,function(b){g(b,this,a)}).end().end()}})(jQuery);

// Initialize all .smoothScroll links
jQuery(function($){ $.localScroll({filter:'.smoothScroll'}); });

</script>
<script type="text/javascript">
var $$$$ = jQuery.noConflict();
$$$$(function() {
$$$$( document ).tooltip({
track: true,
content: function () {
return $$$$(this).prop('title');
}
});
});
</script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/prototype.js"></script>

<script type="text/javascript" src="js/cycle.js"></script>
<script type="text/javascript">
var $$ = jQuery.noConflict();
$$(document).ready(function(){
	$$('.list_noticias').cycle({ 
		fx:     'fade', 
		speed:  'fast', 
		timeout: 10000, 
		next:   '.next', 
		prev:   '.prev' ,
		width: '800px',
		height: '800px'
	});
	
	$$('body').delegate('.fechar_feed','click',function(){
		  $$(this).parent().fadeOut();
		  $$('#mostrar').fadeIn();
		  setCookie('feed',365);
	});

	$$('body').delegate('#mostrar','click',function(){
		  $$('#mostrar').fadeOut();
		  $$('#noticias').fadeIn();
		  eraseCookie('feed');
	});
	
	function setCookie(name,exdays){
		var expires;
		var date; 
		var value;
		date = new Date(); 
		date.setTime(date.getTime()+(exdays*24*60*60*1000));
		expires = date.toUTCString();
		value = false;
		document.cookie = name+"="+value+"; expires="+expires+"; path=/";
	}
	
	function eraseCookie(name){ 
		setCookie(name,-1);
	}
	
});
</script>
<!---------- ADICIONADO  ----------->
<?php
 if(isset($_COOKIE['feed'])){
	 ?>
     <style type="text/css">
	 	#noticias{
			display:none;
		}
		
		#mostrar{
			display:inline;
		}
	 </style>
     <?php
 }
?>
<!---------- ADICIONADO FIM  ----------->
</head>
<body>
<table width="99%" cellspacing="2" cellpadding="0" border="1" align="center" >
<tr style="height: 180px; background-repeat: repeat-x;">
<td class="header" colspan="15">
<div id='wrapper'>

    <div id="infobar">

    <?php
			if (!$CURUSER){
				echo "[<a href=\"account-login.php\">".T_("LOGIN")."</a>]<B> ".T_("OR")." </B>[<a href=\"account-signup.php\">".T_("SIGNUP")."</a>]";

}else{
 if ($CURUSER["downloaded"] > 0){
				$userratio = number_format($CURUSER["uploaded"] / $CURUSER["downloaded"], 2);
		}else{
				if ($CURUSER["uploaded"] > 0)
					$userratio = "Inf.";
				else
					$userratio = "NA";
		}


		 print("<font color=#22ff00>" .  mksize($CURUSER[uploaded]) . "</font>&nbsp;-&nbsp;");
	 print("<font color=#ff0000>" .  mksize($CURUSER[downloaded]) . "</font>&nbsp;&nbsp;");
	 	print "Ratio ".$userratio."&nbsp;&nbsp;";

$res = mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " and unread='yes' AND location IN ('in','both')") or print(mysql_error());

	$arr = mysql_fetch_row($res);

	$unreadmail = $arr[0];
	
		if ($unreadmail){

$novamp = "(<font color=red><B>$unreadmail</B></font>)";

	}

}

?>

  </div>
  </div>


      	    <div ><a href='index.php'><center><img src='themes/MS-VERDE/images/logo.jpg' alt='logo' border='0' width='100%' height='200' /></center></a></div>

	
</table>

<table width="99%" cellspacing="0" cellpadding="0" border="0" align="center" id="nav" style="z-index: 999;" >

<div class="horizontal-centering"><div><div>

	<tr class="menutopo">
	<td class="headmenu">
		  <ul class="dropdown">
		  <li class="">
		  <a href="index.php" class="menuHead">MS</a>
		  <ul class="submenu" >
		  <li class="">
		  <a href="index.php" class="menuHead">Home</a>
		  </li>
		  <li class="">
		  <a href="forums.php" class="menuHead">Fórum  <font style="float: right; color: #aaaaaa;">»&nbsp;&nbsp;</font></a>
			  <ul>
				<li>
				  <a href="forums.php" class="menuHead">Índice</a>
				</li>
				<li>
				  <a href="forums.php?action=search" class="menuHead">Pesquisa</a>
				</li>
				<li>
				  <a href="forums.php?action=viewunread" class="menuHead">Posts Recentes</a>
				</li>
			  </ul>
		  </li>
		  <li>
		  <a href="javascript:ShoutPOP();" class="menuHead">Shoutbox</a>
		  </li>
		   <li>
		  <a href="http://img.malucos-share.org/" target="_blank" class="menuHead">Hospedar Imagens</a>
		  </li>
		  <li>
		  <a href="grupos_ver.php" class="menuHead">Grupos  <font style="float: right; color: #aaaaaa;">»&nbsp;&nbsp;</font></a>
			  <ul>
				<li>
				  <a href="grupos_entrevista.php" class="menuHead">Solicitar Grupo</a>
				</li>
				 <li>
				  <a href="grupos_ver.php" class="menuHead">Ver grupos</a>
				</li>
			  </ul>
		  </li>
		  <li>
		  <a href="#" class="menuHead">Diversão  <font style="float: right; color: #aaaaaa;">»&nbsp;&nbsp;</font></a>
			  <ul>
				<li>
				  <a href="loteria_bilhetes.php" class="menuHead">Loteria</a>
				</li>
						<li>
				  <a href="leilao_online.php" class="menuHead">Leilão</a>
				</li>
			  </ul>
		  </li>
		  <li>
		  <a href="#" class="menuHead">Design  <font style="float: right; color: #aaaaaa;">»&nbsp;&nbsp;</font></a>
			  <ul>
				<li>
				  <a href="/pedirkit.php" class="menuHead">Pedir Kit</a>
				</li>
			  </ul>
		  </li>
		  </ul>
		  </li>
		  </ul>
	</td>
    <td class="separator"></td>


  
    <td class="headmenu">
		  <ul class="dropdown">
		  <li class="">
			<a href="torrents.php" class="menuHead">Torrents</a>
			<ul class="submenu" >
			<li class="">
			  <a href="torrents.php" class="menuHead">Ver todos</a>
			</li>
			<li class="">
			  <a href="geradoresdeupload.php" class="menuHead">Enviar Torrent</a>
			</li>
			<li>
			  <a href="torrents-today.php" class="menuHead">Lançados Hoje</a>
			</li>
			<li>
			  <a href="torrents.php?freeleech=1" class="menuHead">Torrents FREE</a>
			</li>
			<li>
			  <a href="pendentes_liberacao.php" class="menuHead">Pendentes</a>
			</li>
			<li>
			  <a href="bookmark.php" class="menuHead">Favoritos</a>
			</li>
			<li>
	           <a href="top10.php" class="menuHead">Top 10 <font style="float: right; color: #aaaaaa;">»&nbsp;&nbsp;</font></a>
			  <ul>
				<li>
				  <a href="top10.php" class="menuHead">Geral</a>
				</li>
				<li>
				  <a href="top10.php?&duration=2&cat=" class="menuHead">Mensal</a>
				</li>
				<li>
				  <a href="top10.php?&duration=1&cat=" class="menuHead">Semanal</a>
				</li>
			  </ul>
			</li>
			<li>
			  <a href="pedidos_torrents.php" class="menuHead">Pedidos <font style="float: right; color: #aaaaaa;">»&nbsp;&nbsp;</font></a>
			  <ul>
				<li>
				  <a href="pedidos_torrents.php" class="menuHead">Ver Pedidos</a>
				</li>
					<li>
				  <a href="pedido_liberacao.php" class="menuHead">Pedidos aguardando</a>
				</li>
				<li>
				  <a href="pedido_add.php" class="menuHead">Fazer Pedido</a>
				</li>
			  </ul>
			</li>
			<li>
			  <a href="torrents-pesquisa.php" class="menuHead">Pesquisa Avançada <font style="float: right; color: #aaaaaa;">»</font></a>
			  <ul style="top: -130px;">
				<li>
				  <a href="torrents-pesquisa.php" class="menuHead">Filmes &amp; Séries</a>
				</li>
				<li>
				  <a href="#" class="menuHead">Música</a>
				</li>
				<li>
				  <a href="#" class="menuHead">Jogos</a>
				</li>
				<li>
				  <a href="#" class="menuHead">Aplicativos</a>
				</li>
			  </ul>
			</li>
			
		    </ul>
		  </li>
		  
		  </ul>
	</td>
    <td class="separator"></td>
		  

	
	
		<td class="headmenu">
		  <ul class="dropdown">
		  <li class="">
		  <a href="entrada.php" class="menuHead">Mensagens <?=$novamp?></a>
		  <ul class="submenu" >
		  <li>
		  <a href="entrada.php?Entrada" class="menuHead">Entrada</a>
		  </li>
		  <li>
		  <a href="entrada.php?cx=salvas" class="menuHead">Salvas</a>
		  </li>
		  <li>
		  <a href="saida.php" class="menuHead">Enviadas</a>
		  </li>
		  <li>
		  <a href="enviarmp.php" class="menuHead">Nova Mensagem</a>
		  </li>
		  </ul>
		  </li>
		  </ul>
	</td>
    <td class="separator"></td>

	<td class="headmenu">
		  <ul class="dropdown">
		  <li class="">
		  <a class="menuHead">Informações</a>
		  <ul class="submenu" >
		  		  <li class="">
		  <a href="log.php" class="menuHead">Log do Site</a>
		  </li>
		  <li class="">
		  <a href="rss.php?custom=1" class="menuHead">Saída RSS</a>
		  </li>
		  </ul>
		  </li>
		  </ul>
	</td>
    <td class="separator"></td>

	<td class="headmenu">
		  <ul class="dropdown">
		  <li class="">
		  <a href="faq.php" class="menuHead">Ajuda</a>
		  <ul class="submenu" >
		  <li class="">
		  <a href="faq.php" class="menuHead">Perguntas Frequentes</a>
		  </li>
		  <li>
		  <a href="rules.php" class="menuHead">Regras</a>
		  </li>
		  	  <li>
		  <a href="falarstaff.php" class="menuHead">Falar Com Equipe MS</a>
		  </li>
		  <li>
		  <a href="staff.php" class="menuHead">Equipe MS</a>
		  </li>
		  </ul>
		  </li>
		  </ul>
	</td>
    <td class="separator"></td>

	

	
	<td class="headsearch">
	  <form action="pesquisa.php" method="get" name="search" onsubmit="return validaSearch()">
	  <input type="text" onfocus="if(this.value=='Pesquisar...') this.value='';" onblur="if(this.value=='') this.value='Pesquisar...';" value="Pesquisar..." style="height: 17px; width: 100px;" class="hsc" name="search" maxlength="30">
	  <select align="bottom" name="tipo" class="hsc"><option value="1">Torrents</option><option value="2">Fórum</option><option value="3">Usuários</option></select>
      <input type="submit" style="width: 30px;height: 22px;" value="OK" class="hsc">
	  </form>
	</td>

  </tr>

</table>




    </div>
  </div>
<!-- END NAV CODE -->
  <div class='d-shad'></div>
<table width="99%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
<!-- START LEFT COLUM -->
<?php if ($site_config["LEFTNAV"]){?>
<td id="left_side">
<div id="left_outer">
<div id="left_inner"><?php leftblocks();?></div></div></td>
<?php } //LEFTNAV ON/OFF END?>
<!-- END LEFT COLUM -->
<!-- START MAIN COLUM -->
<td id="main_body">
<div id="body_outer">
	<div id="mensagens" >
    	<ul>

        </ul>
    </div>

	<div id="conteudo">
    
    </div>
<?php
if ($site_config["MIDDLENAV"]){
	middleblocks();
} //MIDDLENAV ON/OFF END
?>