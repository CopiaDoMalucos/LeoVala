<?php
############################################################
#######                                             ########
#######                                             ########
#######           brshares.com 2.0                  ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions_pes_torr.php");
dbconn();








stdhead(T_("BROWSE_TORRENTS"));
begin_framec(T_("BROWSE_TORRENTS"));

?>
<BR>
<center>
[
<a href="torrents-pesquisa.php"> Geral </a>|<a href="torrents-pesquisa.php"> Filmes e Seriados </a>|<a href="torrents-pesquisa.php"> Música </a>|<a href="torrents-pesquisa.php"> Jogo </a>
]
</center>
</BR>
<form method="get" action="torrents-pesquisa.php">


	
<table  width='100%' class='tab1' cellpadding='0' cellspacing='1' align='center' ><tr><td align="center" colspan="3" class="tab1_cab1">Pesquisa Avançada de Torrents</td></tr><tr><td colspan="3" class="tab1_col3"><center><b>Pesquisar por: </b> <input type="text" name="search" size="50" maxlength="100" value="<?php echo  $_GET["search"] ?>" /></center></td></tr><tr><td width="40%" class="tab1_col3" style="text-align: right;">Pequisar na(s) categoria(s):<br> 
<select style="display: none;" name="cat[]" multiple="multiple" id="sel_cat">

	<?php


	$cats = genrelist();
	$catdropdown = "";
	foreach ($cats as $cat) {
  $catdropdown .= "<option value=\"" . $cat["id"] . "\"";
  if (in_array($cat["id"],$_GET["cat"]))
   $catdropdown .= " selected=\"selected\"";
  $catdropdown .= ">" . htmlspecialchars($cat["parent_cat"]) . ": " . htmlspecialchars($cat["name"]) . "</option>";
 }
	?>
	<?php echo  $catdropdown ?>
	</select>  

<br></br>
Apenas torrents com seeders. <input type="checkbox" value="1" name="incldead"<?php if ($_GET["incldead"] == 1) { echo "checked"; } ?>> 
<br>
Apenas torrents <font color="green">Free Leech</font>. <input type="checkbox" value="2" name="freeleech"<?php if ($_GET["freeleech"] == 2) { echo "checked"; } ?>>
<br>
</td><td class="tab1_col3"><b>Pesquisar por</b><br><input type="radio" checked="checked" value="qualquer" id="qualquer" name="termos" <?php if ($_GET["termos"] == 'qualquer') { echo "checked"; } ?> ><label for="qualquer">Qualquer palavra</label><br><input type="radio" value="exata" id="exata" name="termos"<?php if ($_GET["termos"] == 'exata') { echo "checked"; } ?>><label for="exata">Busca exata</label></td><td class="tab1_col3"><b>Procurar em</b><br><input type="radio" checked="checked" value="titles" id="search_in_titles" class="radiobutton" name="search_in" <?php if ($_GET["search_in"] == 'titles') { echo "checked"; } ?>><label for="search_in_titles">Apenas no título</label><br><input type="radio" value="posts" id="search_in_posts" class="radiobutton" name="search_in" <?php if ($_GET["search_in"] == 'posts') { echo "checked"; } ?>><label for="search_in_posts">Título e Descrição</label><br><br></td></tr><tr><td align="center" colspan="3" class="tab1_col3"><input type="submit" style="width: 150px; height:30px;" value="Pesquisar!"></td></tr></table>
	<br />
</form>
<head>




<script src="javascript_pes.js"> </script>



<script>
var $$$$$ = jQuery.noConflict();
$$$$$(document).ready(function() {

	$$$$$('#content').scrollPagination({

		nop     : 20, // The number of posts per scroll to be loaded
		offset  : 0, // Initial offset, begins at 0 in this case
		error   : 'Fim da busca.', // When the user reaches the end this is the message that is
		search   : '<?php echo $_GET["search"] ;?>',
	    freeleech   : '<?php echo $_GET["freeleech"] ;?>',
		incldead   : '<?php echo $_GET["incldead"] ;?>',
		cat   : '<?php  echo json_encode($_GET["cat"]) ; ?>',
		order   : '<?php  echo $_GET["order"] ; ?>',	
		sort   : '<?php  echo $_GET["sort"] ; ?>',			
	    termos   : '<?php echo $_GET["termos"] ;?>',
	    search_in   : '<?php echo $_GET["search_in"] ;?>',		
                           // displayed. You can change this if you want.
		delay   : 500, // When you scroll down the posts will load after a delayed amount of time.
		               // This is mainly for usability concerns. You can alter this as you see fit
		scroll  : true // The main bit, if set to false posts will not load as the user scrolls. 
		               // but will still load if the user clicks.
		
	});
	
});

</script>

</head>
<?php
	global $site_config, $CURUSER, $THEME, $LANGUAGE;  //Define globals

	if ($site_config["MEMBERSONLY_WAIT"] && $site_config["MEMBERSONLY"] && in_array($CURUSER["class"], explode(",",$site_config["WAIT_CLASS"]))) {
		$gigs = $CURUSER["uploaded"] / (1024*1024*1024);
		$ratio = (($CURUSER["downloaded"] > 0) ? ($CURUSER["uploaded"] / $CURUSER["downloaded"]) : 0);
		if ($ratio < 0 || $gigs < 0) $wait = $site_config["WAITA"];
		elseif ($ratio < $site_config["RATIOA"] || $gigs < $site_config["GIGSA"]) $wait = $site_config["WAITA"];
		elseif ($ratio < $site_config["RATIOB"] || $gigs < $site_config["GIGSB"]) $wait = $site_config["WAITB"];
		elseif ($ratio < $site_config["RATIOC"] || $gigs < $site_config["GIGSC"]) $wait = $site_config["WAITC"];
		elseif ($ratio < $site_config["RATIOD"] || $gigs < $site_config["GIGSD"]) $wait = $site_config["WAITD"];
		else $wait = 0;
	}

	// Columns
	$cols = explode(",", $site_config["torrenttable_columns"]);
	$cols = array_map("strtolower", $cols);
	$cols = array_map("trim", $cols);
	$colspan = count($cols);
	// End
	//tri
          if (isset($_GET["sort"]))
                           $sort=htmlentities(urldecode($_GET["sort"]));
                  else
                          $sort="id";

                  if (isset($_GET["order"]))
                          $order=htmlentities(urldecode($_GET["order"]));
                  else
                          $order="desc";

                 if ($addparam!="")
                        $addparam.="&";

         $scriptname= $_SERVER["PHP_SELF"];
         
          if ($order=="desc")
                        $fleche="&nbsp;&#8593";
                else
                        $fleche="&nbsp;&#8595";
//fin tri
	// Expanding Area
	$expandrows = array();
	if (!empty($site_config["torrenttable_expand"])) {
		$expandrows = explode(",", $site_config["torrenttable_expand"]);
		$expandrows = array_map("strtolower", $expandrows);
		$expandrows = array_map("trim", $expandrows);
	}
	// End

if(isset($_REQUEST["cat"]) and !empty($_REQUEST['cat'])) {
 $consulta = 'category IN (';
  $i = 0; 
  foreach($_REQUEST["cat"] as $key){
  $consulta .= ($i != 0 ? ',' : '')."'".$key."'";
  $wherecatina[] = sqlesc($key);
  $addparam1 .=  "cat%5B%5D=" . $key . "&amp;";
  $thisurl .= "cat=".urlencode($key)."&amp;";
  $i++;
  }
  $consulta .= ')';
 $wherea[] = $consulta;
}

	
		 $freeleech =  $_REQUEST['freeleech'];
		 $search =  $_REQUEST['search'];
		 $incldead =  $_REQUEST['incldead'];
		 $termos =  $_REQUEST['termos'];
		 $search_in =  $_REQUEST['search_in'];		 

echo '<ul style="margin:0;padding:0;" class="alista">';
		echo '<li class="listhead">';

	foreach ($cols as $col) {
		switch ($col) {
			             case 'category':
                               echo "<div style='width: 50px;' class='divhead'><div style='width: 50px;'><font color='white'>".T_("TYPE")."</font>".($sort=="category"?$fleche:"")."</div></div>";
                        break;
                        case 'name':
                                echo "<div style='text-align: left; min-width: 200px; width: 732px;' class='divhead'><font color='white'>".T_("NAME")."</font>".($sort=="name"?$fleche:"")."</div>";
                        break;
                        case 'dl':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px;'>Down</div></div>";
                        break;
                        case 'comments':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."&search=".$search."&".$addparam1."&incldead=".$incldead."&freeleech=".$freeleech."&termos=".$termos."&search_in=".$search_in."&sort=comments&order=".($sort=="comments" && $order=="desc"?"asc":"desc")."\"><font color='white'>Com</font></a>".($sort=="comments"?$fleche:"")."</div></div>";
                        break;
                        case 'size':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."search=".$search."&".$addparam1."&incldead=".$incldead."&freeleech=".$freeleech."&termos=".$termos."&search_in=".$search_in."&sort=size&order=".($sort=="size" && $order=="desc"?"asc":"desc")."\"><font color='white'> ".T_("SIZE")."</font></a>".($sort=="size"?$fleche:"")."</div></div>";
                        break;
                        case 'seeders':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."search=".$search."&".$addparam1."&incldead=".$incldead."&freeleech=".$freeleech."&termos=".$termos."&search_in=".$search_in."&sort=seeders&order=".($sort=="seeders" && $order=="desc"?"asc":"desc")."\"><font color='white'>S</font></a>".($sort=="seeders"?$fleche:"")."</div></div>";
                        break;
                        case 'leechers':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."search=".$search."&".$addparam1."&incldead=".$incldead."&freeleech=".$freeleech."&termos=".$termos."&search_in=".$search_in."&sort=leechers&order=".($sort=="leechers" && $order=="desc"?"asc":"desc")."\"><font color='white'>L</font></a>".($sort=="leechers"?$fleche:"")."</div></div>";
                        break;
					
							
		}
	}

	  echo "</li>";
?>

<div id="content">
	


</div>

<?php

end_framec();
stdfoot();
?> 	