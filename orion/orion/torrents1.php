<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions1.php");
dbconn();








stdhead(T_("BROWSE_TORRENTS"));
begin_framec(T_("BROWSE_TORRENTS"));

?>
 <table width="100%">
 <tr>
     <td valign="top" align="right">
     <form id='sort' action=''>
     <b>Categorias:</b>
     <select name="cat" onchange="window.location='torrents.php?cat='+this.options[this.selectedIndex].value">
     <option value="">Todas as Categorias</option>
     <?php foreach ( genrelist() as $category ): ?>
        <option value="<?php echo $category["id"]; ?>" <?php echo ($_GET['cat'] == $category["id"] ? " selected='selected'" : ""); ?>><?php echo $category["parent_cat"] . ' > ' . $category["name"]; ?></option>
     <?php endforeach; ?>
     </select>   
     </form> 
     </td>
 </tr>
 </table>
<?php

?>
<head>




<script src="javascript.js"> </script>



<script>
var $$$$$ = jQuery.noConflict();
$$$$$(document).ready(function() {

	$$$$$('#content').scrollPagination({

		nop     : 20, // The number of posts per scroll to be loaded
		offset  : 0, // Initial offset, begins at 0 in this case
		error   : 'Fim da busca.', // When the user reaches the end this is the message that is
	    order   : '<?php echo $_GET["order"] ;?>',
		sort   : '<?php echo $_GET["sort"] ;?>',
		cat   : '<?php echo $_GET["cat"] ;?>',
	    parent_cat   : '<?php echo $_GET["parent_cat"] ;?>',
	    freeleech   : '<?php echo $_GET["freeleech"] ;?>',		
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
		 $idteam = (int) $_GET['cat'];
		 $parent_cat =  $_GET['parent_cat'];
		 $freeleech =  $_REQUEST['freeleech'];
	
echo '<ul style="margin:0;padding:0;" class="alista">';
		echo '<li class="listhead">';

	foreach ($cols as $col) {
		switch ($col) {
			             case 'category':
                               echo "<div style='width: 50px;' class='divhead'><div style='width: 50px;'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_cat."&freeleech=".$freeleech."&sort=category&order=".($sort=="category" && $order=="desc"?"asc":"desc")."\"><font color='white'>".T_("TYPE")."</font></a>".($sort=="category"?$fleche:"")."</div></div>";
                        break;
                        case 'name':
                                echo "<div style='text-align: left; min-width: 200px; width: 732px;' class='divhead'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_cat."&freeleech=".$freeleech."&sort=name&order=".($sort=="name" && $order=="desc"?"asc":"desc")."\"><font color='white'>".T_("NAME")."</font></a>".($sort=="name"?$fleche:"")."</div>";
                        break;
                        case 'dl':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px;'>Down</div></div>";
                        break;
                        case 'comments':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_cat."&freeleech=".$freeleech."&sort=comments&order=".($sort=="comments" && $order=="desc"?"asc":"desc")."\"><font color='white'>Com</font></a>".($sort=="comments"?$fleche:"")."</div></div>";
                        break;
                        case 'size':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_cat."&freeleech=".$freeleech."&sort=size&order=".($sort=="size" && $order=="desc"?"asc":"desc")."\"><font color='white'> ".T_("SIZE")."</font></a>".($sort=="size"?$fleche:"")."</div></div>";
                        break;
                        case 'seeders':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_cat."&freeleech=".$freeleech."&sort=seeders&order=".($sort=="seeders" && $order=="desc"?"asc":"desc")."\"><font color='white'>S</font></a>".($sort=="seeders"?$fleche:"")."</div></div>";
                        break;
                        case 'leechers':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_cat."&freeleech=".$freeleech."&sort=leechers&order=".($sort=="leechers" && $order=="desc"?"asc":"desc")."\"><font color='white'>L</font></a>".($sort=="leechers"?$fleche:"")."</div></div>";
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