<?php
if (!$site_config["MEMBERSONLY"] || $CURUSER) {
begin_blockT("Categorias");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">



</head>
<body>


<div id="ddsidemenubar" class="markermenu">
<ul>
<li><a href="/torrents.php">Listar todos</a></li>
<li><a href="/torrents.php?parent_cat=Adultos" rel="adultos" class=""  class="rightarrowpointer">Adultos</a></li>
<li><a href="/torrents.php?parent_cat=Anime">Anime</a></li>
<li><a href="/torrents.php?parent_cat=Aplicativos" rel="aplicativos" class=""  class="rightarrowpointer">Aplicativos</a></li>
<li><a href="/torrents.php?parent_cat=Cursos" rel="cursos" class="">Cursos</a></li>
<li><a href="/torrents.php?parent_cat=Fotos">Fotos</a></li>	
<li><a href="/torrents.php?parent_cat=Filmes"  rel="filmes" class=""  class="rightarrowpointer" >Filmes</a></li>		
<li><a href="/torrents.php?parent_cat=Jogos"  rel="jogos" class=""  class="rightarrowpointer" >Jogos</a></li>
<li><a href="/torrents.php?parent_cat=Livros%2FRevist">Livros/Revistas</a></li>	
<li><a href="/torrents.php?parent_cat=Músicas" rel="musicas" class=""  class="rightarrowpointer">Músicas</a></li>
<li><a href="/torrents.php?parent_cat=Séries" rel="series" class=""  class="rightarrowpointer">Séries</a></li>
<li><a href="/torrents.php?parent_cat=Shows">Shows</a></li>		
<li><a href="/torrents.php?parent_cat=Televisão" rel="tvtv" class=""  class="rightarrowpointer">Televisão</a></li>
<li><a href="/torrents.php?parent_cat=VideoClipes">VideoClipes</a></li>	


</ul>
</div>

<script type="text/javascript">
ddlevelsmenu.setup("ddsidemenubar", "sidebar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")
</script>


<!--HTML for the Drop Down Menus associated with Top Menu Bar-->
<!--They should be inserted OUTSIDE any element other than the BODY tag itself-->
<!--A good location would be the end of the page (right above "</BODY>")-->




<ul id="adultos" class="ddsubmenustyle">
<li><a href="/torrents.php?cat=47">Filmes</a></li>
<li><a href="/torrents.php?cat=104">Fotos-xxx</a></li>
<li><a href="/torrents.php?cat=106">Filmes-Hentai</a></li>
</ul>





<!--Top Drop Down Menu 1 HTML-->

<ul id="aplicativos" class="ddsubmenustyle">
<li><a href="/torrents.php?cat=19">Mac</a></li>
<li><a href="/torrents.php?cat=18">Pc</a></li>
<li><a href="/torrents.php?cat=94">Windows</a></li>
<li><a href="/torrents.php?cat=115">Celular</a>
  <ul>
     <li><a href="/torrents.php?cat=122">Android</a></li>
     <li><a href="/torrents.php?cat=123">Symbian</a></li>
     <li><a href="/torrents.php?cat=115">Celular</a>
  </li>
	</ul>
</li>
</ul>

<!--Top Drop Down Menu 1 HTML-->

<ul id="cursos" class="ddsubmenustyle">
<li><a href="/torrents.php?cat=9">Apostilas</a></li>
<li><a href="/torrents.php?cat=113">Cursos</a></li>
<li><a href="/torrents.php?cat=111">Video-Aula</a></li>

</ul>
</li>
</ul>
<!--Top Drop Down Menu 1 HTML-->

<ul id="filmes" class="ddsubmenustyle">
<li><a href="/torrents.php?parent_cat=Filmes">Todos</a></li>

<li><B><center class="myCaption">Por qualidade: </center></B></li>


<li><a href="/pesquisa_avan.php?search=&cat=0&freeleech=0&mta=1&hd=1&incldead=0&ano=0&audio=0&extensao=0&codec1=0&codec2=0&idioma=0">HD</a></li>
<li><a href="/pesquisa_avan.php?search=&termos=qualquer&cat=0&ano=0&audio=0&extensao=0&qualidade=25&codec1=0&codec2=0&idioma=0">DVD-R</a></li>
<li><a href="/pesquisa_avan.php?search=&cat=0&freeleech=0&mta=1&3d=1&incldead=1&ano=0&audio=0&extensao=0&codec1=0&codec2=0&idioma=0">3D</a></li>
<li><a href="/pesquisa_avan.php?search=&cat=0&freeleech=0&mta=1&blu=1&incldead=1&ano=0&audio=0&extensao=0&codec1=0&codec2=0&idioma=0">BLURAY</a>
<li><a href="/torrents.php?cat=2">Xvid</a></li>
<li><B><center class="myCaption">Por genêro: </center></B></li>
<li><a href="/torrents.php?cat=4">Ação</a>
<li><a href="/torrents.php?cat=23">Aventura</a>
<li><a href="/torrents.php?cat=26">Comédia</a>
<li><a href="/torrents.php?cat=41">Romance</a>
<li><a href="/torrents.php?cat=27">Documentário</a>
<li><a href="/torrents.php?cat=33">Drama</a>
<li><a href="/torrents.php?cat=35">Ficção</a>
<li><a href="/torrents.php?cat=40">Suspense</a>
<li><a href="/torrents.php?cat=6">Terror</a>
<li><a href=""><center>Outros</center></a>
  <ul>
     <li><a href="/torrents.php?cat=24">Biografia</a></li>
     <li><a href="/torrents.php?cat=34">Esportes</a></li>
     <li><a href="/torrents.php?cat=37">Infantil </a>
	 <li><a href="/torrents.php?cat=42">Nacionais</a></li>
     <li><a href="/torrents.php?cat=39">Policial</a></li>
     <li><a href="/torrents.php?cat=5">Religiosos  </a>
     <li><a href="/torrents.php?cat=7">Western</a></li>
     <li><a href="/torrents.php?cat=114">Animação</a></li>
     <li><a href="/torrents.php?cat=25">Classicos</a>
	 <li><a href="/torrents.php?cat=117">Épico</a></li>
     <li><a href="/torrents.php?cat=36">Guerra</a></li> 
  </li>
	</ul>
</li>
</ul>

<!--Top Drop Down Menu 1 HTML-->

<ul id="jogos" class="ddsubmenustyle">
<li><a href="/torrents.php?parent_cat=14&cat=43">Todos</a></li>
<li><a href="/torrents.php?cat=14">Xbox360</a></li>
<li><a href="/torrents.php?cat=43">Ps3</a></li>
<li><a href="/torrents.php?cat=13">Xbox</a>
<li><a href="/torrents.php?cat=11">PS2</a>
<li><a href="/torrents.php?cat=15">PS1</a>
<li><a href="/torrents.php?cat=116">Nintendo DS</a>
<li><a href="/torrents.php?cat=10">PC</a>
<li><a href="/torrents.php?cat=12">PSP</a>
<li><a href="/torrents.php?cat=105">Game-cube</a>
<li><a href="/torrents.php?cat=16">Dreamcast</a>
<li><a href="/torrents.php?cat=102">Emuladores e Roms</a>
<li><a href="/torrents.php?cat=121">Celular</a>
  <ul>
     <li><a href="/torrents.php?cat=121">Celular/Tablet</a></li>
  </li>
	</ul>
</li>
</ul>

<!--Top Drop Down Menu 1 HTML-->

<ul id="musicas" class="ddsubmenustyle">
<li><a href="/torrents.php?cat=51">Axé</a></li>
<li><a href="/torrents.php?cat=57">Eletronica</a></li>
<li><a href="/torrents.php?cat=61">Funk</a></li>
<li><a href="/torrents.php?cat=64">Hard Rock</a></li>
<li><a href="/torrents.php?cat=66">Hip Hop</a></li>
<li><a href="/torrents.php?cat=52">Blues</a></li>
<li><a href="/torrents.php?cat=53">Coletânea</a></li>
<li><a href="/torrents.php?cat=54">Country</a></li>
<li><a href="/torrents.php?cat=55">Dance</a></li>
<li><a href="/torrents.php?cat=56">Discografia</a></li>
<li><a href="/torrents.php?cat=58">Enka</a></li>
<li><a href="/torrents.php?cat=59">Erudita</a></li>
<li><a href="/torrents.php?cat=60">Forró</a></li>
<li><a href="/torrents.php?cat=62">Gospel</a></li>
<li><a href="/torrents.php?cat=65">Heavy Metal </a></li>
<li><a href="/torrents.php?cat=67">House</a></li>
<li><a href="/torrents.php?cat=68">Infantil</a></li>
<li><a href="/torrents.php?cat=69">Jazz</a></li>
<li><a href="/torrents.php?cat=70">MPB</a></li>




<li><a href=""><center>Outros</center></a>
  <ul>
 <li><a href="/torrents.php?cat=107">Instrumental</a></li>
<li><a href="/torrents.php?cat=71">New Age</a></li>
<li><a href="/torrents.php?cat=72">Oldies</a></li>
<li><a href="/torrents.php?cat=73">Pagode </a></li>
<li><a href="/torrents.php?cat=74">Pop</a></li>
<li><a href="/torrents.php?cat=75">Psychedelic</a></li>
<li><a href="/torrents.php?cat=76">Punk Rock</a></li>
<li><a href="/torrents.php?cat=78">Rap</a></li>
<li><a href="/torrents.php?cat=79">Reggae</a></li>
<li><a href="/torrents.php?cat=80">Regionais</a></li>
<li><a href="/torrents.php?cat=82">Rock</a></li>
<li><a href="/torrents.php?cat=83">Samba</a></li>
<li><a href="/torrents.php?cat=84">Sertanejo</a></li>
<li><a href="/torrents.php?cat=118">Sets Mixados</a></li>
<li><a href="/torrents.php?cat=85">Soul</a></li>
<li><a href="/torrents.php?cat=86">Surf Music</a></li>
<li><a href="/torrents.php?cat=87">Techno</a></li>
<li><a href="/torrents.php?cat=88">Trance</a></li>
<li><a href="/torrents.php?cat=89">Trilha-Sonora</a></li>
<li><a href="/torrents.php?cat=90">Vocal</a></li>
<li><a href="/torrents.php?cat=91">World Music</a>
  </li>
	</ul>
</li>
</ul>

<!--Top Drop Down Menu 1 HTML-->

<ul id="series" class="ddsubmenustyle">
<li><a href="/torrents.php?cat=95">Seriados</a></li>
</ul>

<!--Top Drop Down Menu 1 HTML-->

<ul id="tvtv" class="ddsubmenustyle">
<li><a href="/torrents.php?cat=49">Tv</a></li>
</ul>

<!--Top Drop Down Menu 1 HTML-->

</body></html>
<?php

end_block();
}
?>
