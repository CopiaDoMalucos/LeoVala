//ressources
var timer=0;
var ptag=String.fromCharCode(5,6,7);
function  visualisation() {
	t=document.formu.texte.value  
	t=code_to_html(t)
	if (document.getElementById) document.getElementById("previsualisation").innerHTML=t
	if (document.formu.auto.checked) timer=setTimeout(visualisation,1000)
}
function automatique() {
	if (document.formu.auto.checked) visualisation()
}
function code_to_html(t) {
	t=nl2khol(t)
// balise Center
	t=deblaie(/(\[\/center\])/g,t)
	t=remplace_tag(/\[center\](.+)\[\/center\]/g,'<center>$1</center>',t)  
	t=remblaie(t)		
// balise left
	t=deblaie(/(\[\/left\])/g,t)
	t=remplace_tag(/\[left\](.+)\[\/left\]/g,'<left>$1</left>',t)  
	t=remblaie(t)
// balise right
	t=deblaie(/(\[\/right\])/g,t)
	t=remplace_tag(/\[right\](.+)\[\/right\]/g,'<right>$1</right>',t)  
	t=remblaie(t)
// balise center	
	t=deblaie(/(\[\/center\])/g,t)
	t=remplace_tag(/\[center=(center|left|right|justify)\](.+)\[\/align]/g,'<div text-align="center">$2</align>',t)
// alignement [align=..]...[/align]
    t=deblaie(/(\[\/align\])/g,t)
    t=remplace_tag(/\[align=([a-zA-Z]+)\]((\s|.)+?)\[\/align\]/g,'<div style="text-align:$1">$2</div>',t)
    t=remblaie(t)
// video [swf]..[/swf]
    t=deblaie(/(\[\/swf\])/g,t)
    t=remplace_tag(/\[swf\]((http|https):\/\/[^<>\s]+?)\[\/swf\]/g,'<param name=movie value=$1/><embed width=470 height=310 src=$1></embed>',t)  
    t=remblaie(t)
// alignement [video=...]
    t=deblaie(/(\[\/align\])/g,t)
    t=remplace_tag(/\[video=([a-zA-Z]+)\]((\s|.)+?)\[\/video\]/g,'<param name=movie value=$1/><embed width=470 height=310 src=$1></embed>',t) 
    t=remblaie(t)
// balise video
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video\](.+)\[\/video\]/g,'<param name=movie value=$1/><embed width=470 height=310 src=$1></embed>',t)  
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
	t=remplace_tag(/\[quote\](.+)\[\/quote\]/g,'<p class="quote">$1</p>',t)  
	t=remblaie(t)
// balise code	
	t=remplace_tag(/\[code\](.+)\[\/code\]/g,'<code>$1</code>',t)  
// balise Img
	t=deblaie(/(\[\/img\])/g,t)
	t=remplace_tag(/\[img\](.+)\[\/img\]/g,'<img src="$1"/>',t)
	t=remblaie(t)
// balise IMG
	t=deblaie(/(\[\/IMG\])/g,t)
	t=remplace_tag(/\[IMG\](.+)\[\/IMG\]/g,'<img src="$1"/>',t)
	t=remblaie(t)
// balise URL
    t=deblaie(/(\[\/url\])/g,t)
    t=remplace_tag(/\[url\]([^\s<>]+)\[\/url\]/g,'<a href="$1" target="_blank">$1</a>',t)
    t=remblaie(t)
// balise URL=	
	t=deblaie(/(\[\/url\])/g,t)
    t=remplace_tag(/\[url=([^\s<>]+)\](.+)\[\/url\]/g,'<a href="$1" target="_blank">$2</a>',t)
    t=remblaie(t)
// balise URL
    t=remplace_tag(/\[\/url\]/g,'</a>',t)
    t=remblaie(t)
// balise couleur    
    t=deblaie(/(\[\/color\])/g,t)
    t=remplace_tag(/\[color=(#[a-fA-F0-9]{6})\](.+)\[\/color\]/g,'<font color="$1">$2</font>',t)
    t=remblaie(t)
    //balise font=
    t=deblaie(/(\[\/font\])/g,t)
    t=remplace_tag(/\[font=([a-zA-Z]+)\]((\s|.)+?)\[\/font\]/g,'<font face="$1">$2</font>',t)
    t=remblaie(t)
// balise size=	
	t=deblaie(/(\[\/size\])/g,t)
	t=remplace_tag(/\[size=([+-]?[0-9])\](.+)\[\/size\]/g,'<font size="$1">$2</font>',t)
	t=remblaie(t)
	t=unkhol(t)
	t=nl2br(t)
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
function selectionner()
{ document.script.texte.select(); }