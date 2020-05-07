<!DOCTYPE html>
<html lang="en">
<head>

<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $site_config["CHARSET"]; ?>">
<!-- CSS -->
<!-- PNG FIX -->
<!--[if lte IE 6]>
<script type='text/javascript' src='<?php echo $site_config["SITEURL"]; ?>/themes/MS-PLAY/js/pngfix/supersleight-min.js'></script>
<![endif]-->

 <link rel="stylesheet" type="text/css" href="<?php echo $site_config["SITEURL"]; ?>/themes/MS-PLAY/css/green.css" />
<!-- *JS* -->


<script type="text/javascript" src="<?php echo $site_config["SITEURL"]; ?>/backend/java_klappe.js"></script>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js'></script>








<link rel="stylesheet" type="text/css" href="./ddlevelsfiles/ddlevelsmenu-base.css">
<link rel="stylesheet" type="text/css" href="./ddlevelsfiles/ddlevelsmenu-topbar.css">
<link rel="stylesheet" type="text/css" href="./ddlevelsfiles/ddlevelsmenu-sidebar.css">

<script type="text/javascript" src="./ddlevelsfiles/ddlevelsmenu.js">


</script>



<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js'></script>
		<script type="text/javascript" src="<?php echo $site_config["SITEURL"]; ?>/js/jquery.idTabs.min.js"></script>





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

</script>
<!--[if lte IE 6]>
    <script type="text/javascript" src="<?php echo $site_config["SITEURL"]; ?>/themes/MS-AZUL/js/pngfix/supersleight-min.js"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo $site_config["SITEURL"]; ?>/lytebox1/lytebox.js"></script>
<script type="text/javascript" src="<?php echo $site_config["SITEURL"]; ?>/scripts/ddaccordion.js"></script>

<script type="text/javascript" src="/min/?b=js&amp;f=jquery-1.9.1.js,jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
$( document ).tooltip({
track: true,
content: function () {
return $(this).prop('title');
}
});
});
</script>

</head>
<body>
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



}

?>

  </div>
  </div>


      	    <div class='logo'><a href='index.php'><center><img src='themes/MS-PRETO/images/logo.jpg' alt='logo' border='0' width='100%' height='150' /></center></a></div>

			
<!-- START NAV CODE -->
  <style type="text/css">#cssmenu ul,
#cssmenu li,
#cssmenu span,
#cssmenu a {
  margin: 0;
  padding: 0;
  position: relative;
}
#cssmenu {
  height: 49px;


	background: rgb(35,118,202);
	background: -moz-linear-gradient(top, rgba(35, 118, 202, 1) 0%, rgba(8, 79, 152, 1) 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, rgba(35, 118, 202, 1)), color-stop(100%, rgba(8, 79, 152, 1)));
	background: -webkit-linear-gradient(top, rgba(35, 118, 202, 1) 0%, rgba(8, 79, 152, 1) 100%);
	background: -o-linear-gradient(top, rgba(35, 118, 202, 1) 0%, rgba(8, 79, 152, 1) 100%);
	background: -ms-linear-gradient(top, rgba(35, 118, 202, 1) 0%, rgba(8, 79, 152, 1) 100%);
	background: linear-gradient(top, rgba(35, 118, 202, 1) 0%, rgba(8, 79, 152, 1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2376ca', endColorstr='#084f98', GradientType=0 );
	box-shadow: 0 0 4px 0 rgba(0, 0, 0, 0.9), 0 2px 5px 0 rgba(0, 0, 0, 0.9);
	-webkit-box-shadow: 0 0 4px 0 rgba(0, 0, 0, 0.9), 0 2px 5px 0 rgba(0, 0, 0, 0.9);
	-moz-box-shadow: 0 0 4px 0 rgba(0, 0, 0, 0.9), 0 2px 5px 0 rgba(0, 0, 0, 0.9);
  border-bottom: 2px solid #0fa1e0;
  position: relative;
}
#cssmenu:after,
#cssmenu ul:after {
  content: '';
  display: block;
  clear: both;
}
#cssmenu a {
	background: rgb(35,118,202);
	background: -moz-linear-gradient(top, rgba(35, 118, 202, 1) 0%, rgba(8, 79, 152, 1) 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, rgba(35, 118, 202, 1)), color-stop(100%, rgba(8, 79, 152, 1)));
	background: -webkit-linear-gradient(top, rgba(35, 118, 202, 1) 0%, rgba(8, 79, 152, 1) 100%);
	background: -o-linear-gradient(top, rgba(35, 118, 202, 1) 0%, rgba(8, 79, 152, 1) 100%);
	background: -ms-linear-gradient(top, rgba(35, 118, 202, 1) 0%, rgba(8, 79, 152, 1) 100%);
	background: linear-gradient(top, rgba(35, 118, 202, 1) 0%, rgba(8, 79, 152, 1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2376ca', endColorstr='#084f98', GradientType=0 );

	
  color: #ffffff;
  display: inline-block;
  font-family: Helvetica, Arial, Verdana, sans-serif;
  font-size: 12px;
  line-height: 49px;
  padding: 0 20px;
  text-decoration: none;
}
#cssmenu ul {
  list-style: none;
}
#cssmenu > ul {
  float: left;
}
#cssmenu > ul > li {
  float: left;
}
#cssmenu > ul > li:hover:after {
  content: '';
  display: block;
  width: 0;
  height: 0;
  position: absolute;
  left: 50%;
  bottom: 0;
  border-left: 10px solid transparent;
  border-right: 10px solid transparent;
  border-bottom: 10px solid #0fa1e0;
  margin-left: -10px;
}
#cssmenu > ul > li:first-child a {
  border-radius: 5px 0 0 0;
  -moz-border-radius: 5px 0 0 0;
  -webkit-border-radius: 5px 0 0 0;
}
#cssmenu > ul > li:last-child a {
  border-radius: 0 5px 0 0;
  -moz-border-radius: 0 5px 0 0;
  -webkit-border-radius: 0 5px 0 0;
}
#cssmenu > ul > li.active a {
  box-shadow: inset 0 0 3px #000000;
  -moz-box-shadow: inset 0 0 3px #000000;
  -webkit-box-shadow: inset 0 0 3px #000000;
  background: #070707;
  background: -moz-linear-gradient(top, #26262c 0%, #070707 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #26262c), color-stop(100%, #070707));
  background: -webkit-linear-gradient(top, #26262c 0%, #070707 100%);
  background: -o-linear-gradient(top, #26262c 0%, #070707 100%);
  background: -ms-linear-gradient(top, #26262c 0%, #070707 100%);
  background: linear-gradient(to bottom, #26262c 0%, #070707 100%);
  filter: progid:DXImageTransform.Microsoft.Gradient(StartColorStr='#26262c', EndColorStr='#070707', GradientType=0);
}
#cssmenu > ul > li:hover > a {
background: -moz-linear-gradient(top,  rgba(35,118,202,0.69) 0%, rgba(8,79,152,0.6) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(35,118,202,0.69)), color-stop(100%,rgba(8,79,152,0.6))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(35,118,202,0.69) 0%,rgba(8,79,152,0.6) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(35,118,202,0.69) 0%,rgba(8,79,152,0.6) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(35,118,202,0.69) 0%,rgba(8,79,152,0.6) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(35,118,202,0.69) 0%,rgba(8,79,152,0.6) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b02376ca', endColorstr='#99084f98',GradientType=0 ); /* IE6-9 */

  box-shadow: inset 0 0 3px #000000;
  -moz-box-shadow: inset 0 0 3px #000000;
  -webkit-box-shadow: inset 0 0 3px #000000;
}
#cssmenu .has-sub {
  z-index: 1;
}
#cssmenu .has-sub:hover > ul {
  display: block;
}
#cssmenu .has-sub ul {
  display: none;
  position: absolute;
  width: 200px;
  top: 100%;
  left: 0;
}
#cssmenu .has-sub ul li {
  *margin-bottom: -1px;
}
#cssmenu .has-sub ul li a {
  background: #0fa1e0;
  border-bottom: 1px dotted #6fc7ec;
  filter: none;
  font-size: 11px;
  display: block;
  line-height: 120%;
  padding: 10px;
}
#cssmenu .has-sub ul li:hover a {
  background: #0c7fb0;
}
#cssmenu .has-sub .has-sub:hover > ul {
  display: block;
}
#cssmenu .has-sub .has-sub ul {
  display: none;
  position: absolute;
  left: 100%;
  top: 0;
}
#cssmenu .has-sub .has-sub ul li a {
  background: #0c7fb0;
  border-bottom: 1px dotted #6db2d0;
}
#cssmenu .has-sub .has-sub ul li a:hover {
  background: #095c80;
}
</style>

<div id='cssmenu'>
<table  valign='bottom' align='right' height= '50px'; >
<td >
	  <form action="pesquisa.php" method="get" style="display: inline; margin: 0;" name="search" onsubmit="return validaSearch()">
	  
	  <input type="text" onfocus="if(this.value=='Pesquisar...') this.value='';" onblur="if(this.value=='') this.value='Pesquisar...';" value="Pesquisar..." style="height: 15px; width: 100px;" class="hsc" name="search" maxlength="30">
	  <select align="bottom" name="tipo" class="hsc"><option value="1">Torrents</option><option value="2">Fórum</option><option value="3">Usuários</option></select>
      <input type="submit" style="width: 30px;height: 20px;" value="OK" class="hsc">
	  </form>
	</td>	
	
	</table>
	<script>  

function validaSearch() {
d = document.search;
if (d.search.value == "Pesquisar..."){
alert("Preencha o campo corretamente.");
d.search.focus();
return false;
}
}
</script>  
<ul>
   <li><a href='index.php' ><span>Home</span></a></li>
   <li class='has-sub '><a href='forums.php'><span>Forum</span></a>
      <ul>
         <li class='has-sub '><a href='forums.php?action=search' ><span>Pesquisa</span></a>
         </li>
        
      </ul>
   </li>
   <li><a href='rules.php' ><span>Regras</span></a></li>
    <li class='has-sub '><a href='#'><span>Torrents</span></a>
      <ul>
         <li class='has-sub '><a href='geradoresdeupload.php' ><span>Enviar torrent</span></a>
		    <li class='has-sub '><a href='torrents-today.php' ><span>Torrent do dia</span></a>
			   <li class='has-sub '><a href='torrents.php' ><span>Ver todos</span></a>
			      <li class='has-sub '><a href='torrents-needseed.php' ><span>Sem seed</span></a>
				     <li class='has-sub '><a href='pedido_add.php'><span>Pedir torrent</span></a>
					    <li class='has-sub '><a href='pedidos_torrents.php' ><span>Ver pedidos</span></a>
						  <li class='has-sub '><a href='pedido_liberacao.php' ><span>Pedidos  aguardando</span></a>
						  <li class='has-sub '><a href='pendentes_liberacao.php' ><span>Pendentes a liberação</span></a>
		 
         </li>
        
      </ul>
   </li>
    
  <li><a href='top10.php' ><span>Top</span></a></li>
	    <li class='has-sub '><a href='forums.php' ><span>Perfil</span></a>
      <ul>
         <li class='has-sub '><a href='lancados.php?id=<?=$CURUSER["id"]?>' ><span>Meus Lançamentos</span></a>
		    <li class='has-sub '><a href='forumhistorico.php' ><span>Posts no fórum</span></a>
			   <li class='has-sub '><a href='comenttorrent.php' ><span>Comentários</span></a>
			      <li class='has-sub '><a href='account.php?action=edit_settings&do=edit' ><span>Editar</span></a>
				     <li class='has-sub '><a href='pkitviewrequests.php' ><span>Pedir kit</span></a>
					    <li class='has-sub '><a href='seedingbonus.php' ><span>Ms bonus</span></a>
					
		 
         </li>
        
      </ul>
   </li>  
   	    <li class='has-sub '><a href='forums.php' ><span>Ajuda</span></a>
      <ul>
         <li class='has-sub '><a href='testport.php' ><span>Teste sua porta</span></a>
		    <li class='has-sub '><a href='log.php' ><span>Log do site</span></a>
			   <li class='has-sub '><a href='tags.php' ><span>Teste seu bbcode</span></a>
			      <li class='has-sub '><a href='faq.php' ><span>Faq</span></a>
				     <li class='has-sub '><a href='staff.php' ><span>Staff</span></a>
					    <li class='has-sub '><a href='falarstaff.php' ><span>Falar com a staff</span></a>
					
		 
         </li>
        
      </ul>
   </li>  
      <li><a href='grupos_ver.php' ><span>Grupos</span></a></li>

 	 <li class='has-sub '><a href='#' ><span>Diversão</span></a>
      <ul>
         <li class='has-sub '><a href='loteria_bilhetes.php' ><span>Loteria</span></a>		
		 		<li class='has-sub '>
				  <a href="leilao_online.php" class="menuHead"><span>Leilão</span></a>	
				</li>
         </li>
        
      </ul>
   </li>    
	  
</div>





    </div>
  </div>
<!-- END NAV CODE -->


<!-- START CONTENT AREA -->

<div class=' d-shad1 b-rad2'>
</div>
  <table width='100%' border='0' cellspacing='7' cellpadding='0'>
    <tr>
<!-- START LEFT COLUM -->
<?php if ($site_config["LEFTNAV"]){?>
<td width='170' valign='top'><?php leftblocks();?></td>
<?php } //LEFTNAV ON/OFF END?>
<!-- END LEFT COLUM -->
<!-- START MAIN COLUM -->
<td valign='top'><br>
<?php
if ($site_config["MIDDLENAV"]){
	middleblocks();
} //MIDDLENAV ON/OFF END
?>