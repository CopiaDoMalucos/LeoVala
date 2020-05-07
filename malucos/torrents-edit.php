<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn();
loggedinonly();

$temposeed = $_POST["temposeed"];
$id = (int) $_REQUEST["id"];
if (!is_valid_id($id)) show_error_msg(T_("ERROR"), T_("INVALID_ID"), 1);
$action = $_REQUEST["action"];

$row = mysql_fetch_assoc(SQL_Query_exec("SELECT `owner` FROM `torrents` WHERE id=$id"));
if($CURUSER["edit_torrents"]=="no" && $CURUSER['id'] != $row['owner'])
    show_error_msg(T_("ERROR"), T_("NO_TORRENT_EDIT_PERMISSION"), 1);

if ($row["safe"] == "yes") {	
if($CURUSER["level"]!="Administrador" || $CURUSER["level"]!="Sysop" || $CURUSER["level"]!="Moderador" || $CURUSER["level"]!="S.Moderador" || $CURUSER["level"]!="Liberador"){

    show_error_msg(T_("ERROR"), T_("NO_TORRENT_EDIT_PERMISSION"), 1);
}
}
	
function temposeed($atual){
	return'
  <select name="temposeed" size="1">
<option '.($atual=="Madrugada (0:00 as 6:00)"?" selected=\"selected\"":"").' value="Madrugada (0:00 as 6:00)">Madrugada (0:00 as 6:00)</option>
<option '.($atual=="Manha (6:00 as 12:00)"?" selected=\"selected\"":"").' value="Manha (6:00 as 12:00)">Manha (6:00 as 12:00)</option>
<option '.($atual=="Tarde (12:00 as 18:00)"?" selected=\"selected\"":"").' value="Tarde (12:00 as 18:00)">Tarde (12:00 as 18:00)</option>
<option '.($atual=="Noite (18:00 as 00:00)"?" selected=\"selected\"":"").' value="Noite (18:00 as 00:00)">Noite (18:00 as 00:00)</option>
<option '.($atual=="Madrugada e Manha"?" selected=\"selected\"":"").' value="Madrugada e Manha">Madrugada e Manha</option>
<option '.($atual=="Manha e Tarde"?" selected=\"selected\"":"").' value="Manha e Tarde">Manha e Tarde</option>
<option '.($atual=="Tarde e Noite"?" selected=\"selected\"":"").' value="Tarde e Noite">Tarde e Noite</option>
<option '.($atual=="Noite e Madrugada"?" selected=\"selected\"":"").' value="Noite e Madrugada">Noite e Madrugada</option>
<option '.($atual=="24 Horas"?" selected=\"selected\"":"").' value="24 Horas">24 Horas</option>
<option '.($atual=="Outro(especificar no torrent)"?" selected=\"selected\"":"").' value="Outro(especificar no torrent)">Outro(especificar no torrent)</option>
	</select>
		';
					}
function uploadimage($x, $imgname, $tid) {
    global $site_config;

    $imagesdir = $site_config["torrent_dir"]."/images";

    $allowed_types = &$site_config["allowed_image_types"];  

    if ( !( $_FILES["image$x"]["name"] == "" ) ) {
        if ($imgname != "") {
            $img = "$imagesdir/$imgname";
            $del = unlink($img);
        }

        $y = $x + 1;
 
	$im = getimagesize($_FILES["image$x"]["tmp_name"]);

	if (!$im[2])
		show_error_msg("Error", "Invalid Image $y.", 1);

	if (!array_key_exists($im['mime'], $allowed_types))
		show_error_msg(T_("ERROR"), T_("INVALID_FILETYPE_IMAGE"), 1);

        if ($_FILES["image$x"]["size"] > $site_config['image_max_filesize'])
            show_error_msg(T_("ERROR"), sprintf(T_("INVAILD_FILE_SIZE_IMAGE"), $y), 1);

        $uploaddir = "$imagesdir/";

	    $ifilename = $tid . $x . $allowed_types[$im['mime']];
                                              
        $copy = copy($_FILES["image$x"]["tmp_name"], $uploaddir.$ifilename);

        if (!$copy)
            show_error_msg(T_("ERROR"), sprintf(T_("ERROR_UPLOADING_IMAGE"), $y), 1);

        return $ifilename;
    }
}//end func


//GET DATA FROM DB
$res = SQL_Query_exec("SELECT * FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row){
    show_error_msg(T_("ERROR"), T_("TORRENT_ID_GONE"), 1);
}

$torrent_dir = $site_config["torrent_dir"];    
$nfo_dir = $site_config["nfo_dir"];    

//DELETE TORRENT

if ($action=="deleteit"){
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Liberador"){
    $torrentid = (int) $_POST["torrentid"];
    $delreason = sqlesc($_POST["delreason"]);
    $torrentname = $_POST["torrentname"];

    if (!is_valid_id($torrentid))
        show_error_msg(T_("FAILED"), T_("INVALID_TORRENT_ID"), 1);

    if (!$delreason){
        show_error_msg(T_("ERROR"), T_("MISSING_FORM_DATA"), 1);
    }




	
    deletetorrent($torrentid);


	$msg = "Torrent deletado!\n\n O torrent [size=2][color=green]$torrentname [/color][/size] que você estava baixando foi deletado!!! \n\n Razão: $delreason.\n";
$sql = "INSERT INTO messages (sender, receiver, msg, added, subject) VALUES (0, ".$row['owner'].", \"". stripslashes ($msg)."\", '".get_date_time()."', ' Torrent deletado')";
mysql_query($sql);


	write_loguser("Torrents-deletados","#FF0000","O torrent [url=http://www.malucos-share.org/torrents-details.php?id=".$id."]".$row["name"]."[/url] foi deletado por [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url] Razão: $delreason\n");

						$ts="INSERT INTO `apppdel` (`uid`, `app`, `aprovado`, `added`, `infohash`) VALUES ('".$CURUSER['id']."', '$torrentname', '1','".get_date_time()."','$torrentid')";
                         @mysql_query($ts);
    show_error_msg(T_("COMPLETED"), htmlspecialchars($torrentname)." ".T_("HAS_BEEN_DEL_DB"),1);
    die;
	}
}

//DO THE SAVE TO DB HERE
if ($action=="doedit"){
    $updateset = array();

    $nfoaction = $_POST['nfoaction'];
    if ($nfoaction == "update"){
      $nfofile = $_FILES['nfofile'];
      if (!$nfofile) die("No data " . var_dump($_FILES));
      if ($nfofile['size'] > 65535)
        show_error_msg("NFO is too big!", "Max 65,535 bytes.",1);
      $nfofilename = $nfofile['tmp_name'];
      if (@is_uploaded_file($nfofilename) && @filesize($nfofilename) > 0){
            @move_uploaded_file($nfofilename, "$nfo_dir/$id.nfo");
            $updateset[] = "nfo = 'yes'";
        }//success
    }

    if (!empty($_POST["name"]))
         $updateset[] = "name = " . sqlesc($_POST["name"]);
   
	
	///screenshot
	$updateset[] = "screens1 = " . sqlesc($_POST["screens1"]);
	$updateset[] = "screens2 = " . sqlesc($_POST["screens2"]);
	$updateset[] = "screens3 = " . sqlesc($_POST["screens3"]);
	$updateset[] = "screens4 = " . sqlesc($_POST["screens4"]);
	$updateset[] = "screens5 = " . sqlesc($_POST["screens5"]);

	    ///screenshot end
	$updateset[] = "descr = " . sqlesc($_POST["descr"]);
	$updateset[] = "filmesinopse = " . sqlesc($_POST["filmesinopse"]);
    $updateset[] = "category = " . (int) $_POST["type"];
	$updateset[] = "filmeano = " . (int) $_POST["filmeanofil"];
	$updateset[] = "filmeaudio = " . (int) $_POST["filmeaudiofil"];
	$updateset[] = "filmeextensao = " . (int) $_POST["filmeextensaofil"];
	$updateset[] = "filmequalidade = " . (int) $_POST["filmequalidadefil"];
	$updateset[] = "filme3d = " . (int) $_POST["filme3dfil"];
	$updateset[] = "legenda = " . (int) $_POST["legendafil"];
	$updateset[] = "filmecodecvid = " . (int) $_POST["filmecodecvidfil"];
	$updateset[] = "filmecodecaud = " . (int) $_POST["filmecodecaudfil"];
	$updateset[] = "filmeidiomaorigi = " . (int) $_POST["filmeidiomaorigifil"];
	$updateset[] = "filmeduracaoh = " . (int) $_POST["filmeduracaohfil"];
	$updateset[] = "aplicrack = " . (int) $_POST["aplicrackapl"];
	$updateset[] = "apliformarq = " . (int) $_POST["apliformarqapl"];
	$updateset[] = "filmeduracaomi = " . (int) $_POST["filmeduracaomifil"];
		$updateset[] = "filmeresolucao = " . sqlesc($_POST["filmeresolucao"]);
				$updateset[] = "apliversao = " . sqlesc($_POST["apliversao"]);
		$updateset[] = "temposeed = " . sqlesc($_POST["temposeed"]);
	$updateset[] = "filmeresolucalt = " . (int) $_POST["filmeresolucalt"];
	$updateset[] = "musicaqualidade = " . (int) $_POST["musicaqualidadeedit"];
	$updateset[] = "musicatensao = " . (int) $_POST["musicatensaoedit"];
	$updateset[] = "jogosformato = " . (int) $_POST["jogosformatoedit"];
	$updateset[] = "jogosgenero = " . (int) $_POST["jogosgeneroedit"];
	$updateset[] = "jogosmultiplay = " . (int) $_POST["jogosmultiplaydit"];
	$updateset[] = "revistatensao = " . (int) $_POST["revistatensaodit"];
	$updateset[] = "musicalinkloja = " . sqlesc($_POST["musicalinkloja"]);
	$updateset[] = "musicalbum = " . sqlesc($_POST["musicalbum"]);
    $updateset[] = "musicalautor = " . sqlesc($_POST["musicalautor"]);
	if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador"){
	$updateset[] = "recommended = '" . ($_POST["recommended"] ? "yes" : "no") . "'";
	}
	$updateset[] = "jogosgenero = " . (int) $_POST["jogosgeneroedit"];
	$updateset[] = "jogosgenero = " . (int) $_POST["jogosgeneroedit"];
	$updateset[] = "jogosgenero = " . (int) $_POST["jogosgeneroedit"];
	$updateset[] = "torrentlang = " . (int) $_POST["language"];

	$chgpasswd = $_POST['moderarok']=='yes' ? true : false;
	if ($chgpasswd) {
		
	
	
    $res123 = SQL_Query_exec("SELECT name, owner FROM torrents WHERE id=$id");
$arr123 = mysql_fetch_array($res123);
	$res12 = SQL_Query_exec("SELECT id, username, ver_com FROM users WHERE id=$CURUSER[id]");
					$arr12 = MYSQL_FETCH_ARRAY($res12);
					
$testeuser = mysql_query("SELECT * from moderation WHERE infohash=$id");
            $testeapro = mysql_fetch_array($testeuser);
			
			
$tentativas = 1;

$datetime = sqlesc(get_date_time());
SQL_Query_exec("UPDATE moderation SET   dataremodera=$datetime, verifica='Yes', tentativas = tentativas + $tentativas, aceito='yes'  WHERE infohash = $id") or exit(mysql_error());

  
 


$msg = "O torrent [url=". $site_config[SITEURL]."/torrents-details.php?id=$id]" . $arr123[name] . "[/url] foi corrigido :) ";

SQL_Query_exec("INSERT INTO messages (poster, sender, receiver, msg, added,subject) VALUES('0','0', " . $testeapro['uid'] . ", " .sqlesc($msg) . ", '" . get_date_time() . "','Torrent moderação!')") or die (mysql_error());


		
	}
	

	write_loguser("Torrents-editados","#FF0000","O torrent [url=http://www.malucos-share.org/torrents-details.php?id=".$id."]".$row["name"]."[/url] foi editado por Por [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url]\n");

    $updateset[] = "visible = '" . ($_POST["visible"] ? "yes" : "no") . "'";
	 if (!empty($_POST['tube']))
    $tube = unesc($_POST['tube']);
    $updateset[] = "tube = " . sqlesc($tube);
    if ($CURUSER["edit_torrents"] == "yes")
        $updateset[] = "freeleech = '".$_POST["freeleech"]."'";
  
    $updateset[] = "anon = '" . ($_POST["anon"] ? "yes" : "no") . "'";

    //update images
    $img1action = $_POST['img1action'];
    if ($img1action == "update")
        $updateset[] = "image1 = " .sqlesc(uploadimage(0, $row["image1"], $id));
    if ($img1action == "delete") {
        if ($row[image1]) {
            $del = unlink($site_config["torrent_dir"]."/images/$row[image1]");
            $updateset[] = "image1 = ''";
        }
    }

    $img2action = $_POST['img2action'];
    if ($img2action == "update")
        $updateset[] = "image2 = " .sqlesc(uploadimage(1, $row["image2"], $id));
    if ($img2action == "delete") {
        if ($row[image2]) {
            $del = unlink($site_config["torrent_dir"]."/images/$row[image2]");
            $updateset[] = "image2 = ''";
        }
    }


    SQL_Query_exec("UPDATE torrents SET " . join(",", $updateset) . " WHERE id = $id");

    $returl = "torrents-edit.php?id=$id&edited=1";
    if (isset($_POST["returnto"])){
        $returl = $_POST["returnto"];
		
    }


    header("Location: torrents-details.php?id=$id");
    die();
}//END SAVE TO DB

//UPDATE CATEGORY DROPDOWN
$catdropdown = "<select name=\"type\">\n";
$cats = genrelist();
    foreach ($cats as $catdropdownubrow) {
        $catdropdown .= "<option value=\"" . $catdropdownubrow["id"] . "\"";
        if ($catdropdownubrow["id"] == $row["category"])
            $catdropdown .= " selected=\"selected\"";
        $catdropdown .= ">" . htmlspecialchars($catdropdownubrow["parent_cat"]) . ": " . htmlspecialchars($catdropdownubrow["name"]) . "</option>\n";
    }
$catdropdown .= "</select>\n";
//END CATDROPDOWN

//UPDATE TORRENTLANG DROPDOWN
$langdropdown = "<select name=\"language\"><option value='0'>Unknown</option>\n";
$lang = langlist();
foreach ($lang as $lang) {
    $langdropdown .= "<option value=\"" . $lang["id"] . "\"";
    if ($lang["id"] == $row["torrentlang"])
        $langdropdown .= " selected=\"selected\"";
    $langdropdown .= ">" . htmlspecialchars($lang["name"]) . "</option>\n";
}
$langdropdown .= "</select>\n";
//END TORRENTLANG

//// update Ano de lançamento
$filmeanodown = "<select name=\"filmeanofil\"><option value=0>Escolher</option>\n";
$anoid = anoslist();

foreach ($anoid as $anoid) {
    $filmeanodown .= "<option value=\"" . $anoid["id"] . "\"";
    if ($anoid["id"] == $row["filmeano"])
        $filmeanodown .= " selected=\"selected\"";
    $filmeanodown .= ">" . htmlspecialchars($anoid["name"]) . "</option>\n";
}
$filmeanodown .= "</select>\n";

//// fim Ano de lançamento
//// update Audio de lançamento
$filmeaudiodown = "<select name=\"filmeaudiofil\"><option value=0>Escolher</option>\n";
$filmeaudioid = filmeaudilist();

foreach ($filmeaudioid as $filmeaudioid) {
    $filmeaudiodown .= "<option value=\"" . $filmeaudioid["id"] . "\"";
    if ($filmeaudioid["id"] == $row["filmeaudio"])
        $filmeaudiodown .= " selected=\"selected\"";
    $filmeaudiodown .= ">" . htmlspecialchars($filmeaudioid["name"]) . "</option>\n";
}
$filmeaudiodown .= "</select>\n";

//// fim Audio de lançamento

//// update Extensão de lançamento
$filmeextensaodown = "<select name=\"filmeextensaofil\"><option value=0>Escolher</option>\n";
$filmeextensaoid = filmeextelist();

foreach ($filmeextensaoid as $filmeextensaoid) {
    $filmeextensaodown .= "<option value=\"" . $filmeextensaoid["id"] . "\"";
    if ($filmeextensaoid["id"] == $row["filmeextensao"])
        $filmeextensaodown .= " selected=\"selected\"";
    $filmeextensaodown .= ">" . htmlspecialchars($filmeextensaoid["name"]) . "</option>\n";
}
$filmeextensaodown .= "</select>\n";

//// fim Extensão de lançamento
//// update Qualidade de lançamento
$filmequalidadedown = "<select name=\"filmequalidadefil\"><option value=0>Escolher</option>\n";
$filmequalidadeid = filmequalidlist();

foreach ($filmequalidadeid as $filmequalidadeid) {
    $filmequalidadedown .= "<option value=\"" . $filmequalidadeid["id"] . "\"";
    if ($filmequalidadeid["id"] == $row["filmequalidade"])
        $filmequalidadedown .= " selected=\"selected\"";
    $filmequalidadedown .= ">" . htmlspecialchars($filmequalidadeid["name"]) . "</option>\n";
}
$filmequalidadedown .= "</select>\n";

//// fim Qualidade de lançamento

//// update em 3D de lançamento
$filme3ddown = "<select name=\"filme3dfil\"><option value=0>Escolher</option>\n";
$filme3did = filme3dlist();

foreach ($filme3did as $filme3did) {
    $filme3ddown .= "<option value=\"" . $filme3did["id"] . "\"";
    if ($filme3did["id"] == $row["filme3d"])
        $filme3ddown .= " selected=\"selected\"";
    $filme3ddown .= ">" . htmlspecialchars($filme3did["name"]) . "</option>\n";
}
$filme3ddown .= "</select>\n";

//// fim em 3D de lançamento
//// update legenda
$legendadown = "<select name=\"legendafil\"><option value=0>Escolher</option>\n";
$legendaid = legendalist();

foreach ($legendaid as $legendaid) {
    $legendadown .= "<option value=\"" . $legendaid["id"] . "\"";
    if ($legendaid["id"] == $row["filme3d"])
        $legendadown .= " selected=\"selected\"";
    $legendadown .= ">" . htmlspecialchars($legendaid["name"]) . "</option>\n";
}
$legendadown .= "</select>\n";

//// fim legenda
//// update Codecs de Vídeo de lançamento
$filmecodecviddown = "<select name=\"filmecodecvidfil\"><option value=0>Escolher</option>\n";
$filmecodecvidid = filmecodvilist();

foreach ($filmecodecvidid as $filmecodecvidid) {
    $filmecodecviddown .= "<option value=\"" . $filmecodecvidid["id"] . "\"";
    if ($filmecodecvidid["id"] == $row["filmecodecvid"])
        $filmecodecviddown .= " selected=\"selected\"";
    $filmecodecviddown .= ">" . htmlspecialchars($filmecodecvidid["name"]) . "</option>\n";
}
$filmecodecviddown .= "</select>\n";

//// fim Codecs de Vídeo de lançamento
//// update Audio de lançamento
$filmecodecauddown = "<select name=\"filmecodecaudfil\"><option value=0>Escolher</option>\n";
$filmecodecaudid = filmecodecaudlist();

foreach ($filmecodecaudid as $filmecodecaudid) {
    $filmecodecauddown .= "<option value=\"" . $filmecodecaudid["id"] . "\"";
    if ($filmecodecaudid["id"] == $row["filmecodecaud"])
        $filmecodecauddown .= " selected=\"selected\"";
    $filmecodecauddown .= ">" . htmlspecialchars($filmecodecaudid["name"]) . "</option>\n";
}
$filmecodecauddown .= "</select>\n";

//// fim Audio de lançamento
//// update Idioma Original de lançamento
$filmeidiomaorigidown = "<select name=\"filmeidiomaorigifil\"><option value=0>Escolher</option>\n";
$filmeidiomaorigiid = filmeidiorilist();

foreach ($filmeidiomaorigiid as $filmeidiomaorigiid) {
    $filmeidiomaorigidown .= "<option value=\"" . $filmeidiomaorigiid["id"] . "\"";
    if ($filmeidiomaorigiid["id"] == $row["filmeidiomaorigi"])
        $filmeidiomaorigidown .= " selected=\"selected\"";
    $filmeidiomaorigidown .= ">" . htmlspecialchars($filmeidiomaorigiid["name"]) . "</option>\n";
}
$filmeidiomaorigidown .= "</select>\n";

//// fim Idioma Original de lançamento
//// update Duração hora de lançamento
$filmeduracaohdown = "<select name=\"filmeduracaohfil\"><option value=0>Escolher</option>\n";
$filmeduracaohid = filmedurhorlist();

foreach ($filmeduracaohid as $filmeduracaohid) {
    $filmeduracaohdown .= "<option value=\"" . $filmeduracaohid["id"] . "\"";
    if ($filmeduracaohid["id"] == $row["filmeduracaoh"])
        $filmeduracaohdown .= " selected=\"selected\"";
    $filmeduracaohdown .= ">" . htmlspecialchars($filmeduracaohid["name"]) . "</option>\n";
}
$filmeduracaohdown .= "</select>\n";

//// fim Duração hora de lançamento

//// update Duração minutos de lançamento
$filmeduracaomidown = "<select name=\"filmeduracaomifil\"><option value=0>Escolher</option>\n";
$filmeduracaomiid = filmeduraminulist();

foreach ($filmeduracaomiid as $filmeduracaomiid) {
    $filmeduracaomidown .= "<option value=\"" . $filmeduracaomiid["id"] . "\"";
    if ($filmeduracaomiid["id"] == $row["filmeduracaomi"])
        $filmeduracaomidown .= " selected=\"selected\"";
    $filmeduracaomidown .= ">" . htmlspecialchars($filmeduracaomiid["name"]) . "</option>\n";
}
$filmeduracaomidown .= "</select>\n";

//// fim Duração minutos de lançamento
//// update aplicativos crack
$aplicrackapldown = "<select name=\"aplicrackapl\"><option value=0>Escolher</option>\n";
$aplicrackaplid = jogosmultiplaytilist();

foreach ($aplicrackaplid as $aplicrackaplid) {
    $aplicrackapldown .= "<option value=\"" . $aplicrackaplid["id"] . "\"";
    if ($aplicrackaplid["id"] == $row["aplicrack"])
        $aplicrackapldown .= " selected=\"selected\"";
    $aplicrackapldown .= ">" . htmlspecialchars($aplicrackaplid["name"]) . "</option>\n";
}
$aplicrackapldown .= "</select>\n";

//// fim aplicativos crack

//// update aplicativos 
$apliformarqapldown = "<select name=\"apliformarqapl\"><option value=0>Escolher</option>\n";
$apliformarqaplid = aplicatilist();

foreach ($apliformarqaplid as $apliformarqaplid) {
    $apliformarqapldown .= "<option value=\"" . $apliformarqaplid["id"] . "\"";
    if ($apliformarqaplid["id"] == $row["apliformarq"])
        $apliformarqapldown .= " selected=\"selected\"";
    $apliformarqapldown .= ">" . htmlspecialchars($apliformarqaplid["name"]) . "</option>\n";
}
$apliformarqapldown .= "</select>\n";

//// fim aplicativos 


//// update Extensão músicas 
$musicasexdown = "<select name=\"musicatensaoedit\"><option value=0>Escolher</option>\n";
$musicasexid = musicasextlist();

foreach ($musicasexid as $musicasexid) {
    $musicasexdown .= "<option value=\"" . $musicasexid["id"] . "\"";
    if ($musicasexid["id"] == $row["musicatensao"])
        $musicasexdown .= " selected=\"selected\"";
    $musicasexdown .= ">" . htmlspecialchars($musicasexid["name"]) . "</option>\n";
}
$musicasexdown .= "</select>\n";

//// fim Extensão músicas 

//// update Qaulidade músicas 
$muqualidadedown = "<select name=\"musicaqualidadeedit\"><option value=0>Escolher</option>\n";
$muqualiid = musicasqualilist();

foreach ($muqualiid as $muqualiid) {
    $muqualidadedown .= "<option value=\"" . $muqualiid["id"] . "\"";
    if ($muqualiid["id"] == $row["musicaqualidade"])
        $muqualidadedown .= " selected=\"selected\"";
    $muqualidadedown .= ">" . htmlspecialchars($muqualiid["name"]) . "</option>\n";
}
$muqualidadedown .= "</select>\n";

//// fim Qaulidade músicas 
//// update jogos formato
$jogosformatodown = "<select name=\"jogosformatoedit\"><option value=0>Escolher</option>\n";
$jogosformatoid = jogosformatlist();

foreach ($jogosformatoid as $jogosformatoid) {
    $jogosformatodown .= "<option value=\"" . $jogosformatoid["id"] . "\"";
    if ($jogosformatoid["id"] == $row["jogosformato"])
        $jogosformatodown .= " selected=\"selected\"";
    $jogosformatodown .= ">" . htmlspecialchars($jogosformatoid["name"]) . "</option>\n";
}
$jogosformatodown .= "</select>\n";

//// fim jogos formato

//// update jogos genero
$jogosgenerodown = "<select name=\"jogosgeneroedit\"><option value=0>Escolher</option>\n";
$jogosgeneroid = jogosgenerolist();

foreach ($jogosgeneroid as $jogosgeneroid) {
    $jogosgenerodown .= "<option value=\"" . $jogosgeneroid["id"] . "\"";
    if ($jogosgeneroid["id"] == $row["jogosgenero"])
        $jogosgenerodown .= " selected=\"selected\"";
    $jogosgenerodown .= ">" . htmlspecialchars($jogosgeneroid["name"]) . "</option>\n";
}
$jogosgenerodown .= "</select>\n";

//// fim jogos genero
//// update jogos multi
$jogosmultiplaydown = "<select name=\"jogosmultiplaydit\"><option value=0>Escolher</option>\n";
$jogosmultiplayid = jogosmultiplaytilist();

foreach ($jogosmultiplayid as $jogosmultiplayid) {
    $jogosmultiplaydown .= "<option value=\"" . $jogosmultiplayid["id"] . "\"";
    if ($jogosmultiplayid["id"] == $row["jogosmultiplay"])
        $jogosmultiplaydown .= " selected=\"selected\"";
    $jogosmultiplaydown .= ">" . htmlspecialchars($jogosmultiplayid["name"]) . "</option>\n";
}
$jogosmultiplaydown .= "</select>\n";

//// fim jogos multi

//// update jogos multi
$revistatensaodown = "<select name=\"revistatensaodit\"><option value=0>Escolher</option>\n";
$revistatensaoid = revistalist();

foreach ($revistatensaoid as $revistatensaoid) {
    $revistatensaodown .= "<option value=\"" . $revistatensaoid["id"] . "\"";
    if ($revistatensaoid["id"] == $row["revistatensao"])
        $revistatensaodown .= " selected=\"selected\"";
    $revistatensaodown .= ">" . htmlspecialchars($revistatensaoid["name"]) . "</option>\n";
}
$revistatensaodown .= "</select>\n";

//// fim jogos multi

$char1 = 55;
$shortname = CutName(htmlspecialchars($row["name"]), $char1);

if ($_GET["edited"]){
    show_error_msg("Edited OK", T_("TORRENT_EDITED_OK"), 1);
}

stdhead(T_("EDIT_TORRENT")." \"$shortname\"");

begin_framec(T_("EDIT_TORRENT")." \"$shortname\"");
print("<table class='tab1' cellpadding='0' cellspacing='1' align='center'>");
print("<form method='post' name=\"bbform\" enctype=\"multipart/form-data\" action=\"torrents-edit.php?action=doedit\">");
print("<input type=\"hidden\" name=\"id\" value=\"$id\" />\n");

if (isset($_GET["returnto"]))
    print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");


echo "<tr><td  width=50%  align=right  class=tab1_col3 ><B>".T_("NAME").": </b></td><td width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row["name"]) . "\" size=\"60\" /></TD></TR>";



///filmes
 if ($row["category"] == 23 || $row["category"] == 4 || $row["category"] == 39  || $row["category"] == 24 || $row["category"] == 3 || $row["category"] == 34 || $row["category"] == 35 || $row["category"] == 37 || $row["category"] == 42 || $row["category"] == 5 || $row["category"] == 40 || $row["category"] == 7 || $row["category"] == 28 || $row["category"] == 1 || $row["category"] == 25 || $row["category"] == 26 || $row["category"] == 2 || $row["category"] == 27 || $row["category"] == 33 || $row["category"] == 36 || $row["category"] == 50 || $row["category"] == 41 || $row["category"] == 6 || $row["category"] == 117 || $row["category"] == 124) 
{

echo "<tr><td width=50%  align=right  class=tab1_col3 ><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3  >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";

echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeaudiodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeextensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Qualidade: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmequalidadedown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Filme em 3D: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filme3ddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Legenda Embutidas: </b></TD><TD width=50%  align=left class=tab1_col3 >".$legendadown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Codecs de Vídeo: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecviddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Codecs de Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecauddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Idioma Original: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Duração: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeduracaohdown." horas e ".$filmeduracaomidown." minutos.</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Resolução: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='filmeresolucao' value='" . $row["filmeresolucao"] . "' size='10' />pixels de largura por<input type='text' name='filmeresolucalt' value='" . $row["filmeresolucalt"] . "' size='10' />pixels de altura.</td></TR>";  
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 4: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens5\" value=\"" . htmlspecialchars($row["screens5"]) . "\" size=\"60\" /></TD></TR>";
}


///anime
 if ($row["category"] == 120) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3 ><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeaudiodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeextensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Qualidade: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmequalidadedown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Filme em 3D: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filme3ddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Legenda Embutidas: </b></TD><TD width=50%  align=left class=tab1_col3 >".$legendadown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Codecs de Vídeo: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecviddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Codecs de Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecauddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Idioma Original: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Duração: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeduracaohdown." horas e ".$filmeduracaomidown." minutos.</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Resolução: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='filmeresolucao' value='" . $row["filmeresolucao"] . "' size='10' />pixels de largura por<input type='text' name='filmeresolucalt' value='" . $row["filmeresolucalt"] . "' size='10' />pixels de altura.</td></TR>";  
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 4: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens5\" value=\"" . htmlspecialchars($row["screens5"]) . "\" size=\"60\" /></TD></TR>";
}


///applicativos
 if ($row["category"] == 20 || $row["category"] == 19 || $row["category"] == 18 || $row["category"] == 94 || $row["category"] == 115  || $row["category"] == 123 || $row["category"] == 122) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3 ><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Idioma Original: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Formato do Arquivo: </b></TD><TD width=50%  align=left class=tab1_col3 >".$apliformarqapldown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Crack: </b></TD><TD width=50%  align=left class=tab1_col3 >".$aplicrackapldown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Versão: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='apliversao' value='" . $row["apliversao"] . "' size='10' /></td>"; 
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
}


////musica
 if ($row["category"] == 51 || $row["category"] == 57 || $row["category"] == 61 || $row["category"] == 63 || $row["category"] == 64 || $row["category"] == 66 || $row["category"] == 81 || $row["category"] == 52 || $row["category"] == 53 || $row["category"] == 54 || $row["category"] == 55 || $row["category"] == 56 || $row["category"] == 58 || $row["category"] == 59 || $row["category"] == 60 || $row["category"] == 62 || $row["category"] == 65 || $row["category"] == 67 || $row["category"] == 68 || $row["category"] == 69 || $row["category"] == 70 || $row["category"] == 107 || $row["category"] == 71 || $row["category"] == 72 || $row["category"] == 73 || $row["category"] == 74 || $row["category"] == 75 || $row["category"] == 76 || $row["category"] == 77 || $row["category"] == 78 || $row["category"] == 79 || $row["category"] == 80 || $row["category"] == 81 || $row["category"] == 82 || $row["category"] == 83 || $row["category"] == 84 || $row["category"] == 85 || $row["category"] == 86 || $row["category"] == 87 || $row["category"] == 88 || $row["category"] == 89  || $row["category"] == 91 || $row["category"] == 118) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3 ><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$musicasexdown."</TD></TR>";

echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Qualidade: </b></TD><TD width=50%  align=left class=tab1_col3 >".$muqualidadedown."</TD></TR>";


echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Artista / Grupo:</b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='musicalautor' value='" . $row["musicalautor"] . "' size='50' /></td>";  
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Album: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='musicalbum' value='" . $row["musicalbum"] . "' size='50' /></td>";  
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Link de loja/site oficial: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='musicalinkloja' value='" . $row["musicalinkloja"] . "' size='50' /></td>";  


}
////////jogos
if ($row["category"] == 102 || $row["category"] == 105 ||  $row["category"] == 108 || $row["category"] == 10 || $row["category"] == 15 || $row["category"] == 11 || $row["category"] == 43 || $row["category"] == 12 || $row["category"] == 44 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 16 || $row["category"] == 121 || $row["category"] == 116)
{
echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Genero: </b></TD><TD width=50%  align=left class=tab1_col3 >".$jogosgenerodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";
}
//// video clipes
 if ($row["category"] == 112 )
{

echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeaudiodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeextensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Qualidade: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmequalidadedown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Video Clipe em 3D: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filme3ddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Legenda Embutidas: </b></TD><TD width=50%  align=left class=tab1_col3 >".$legendadown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Vídeo: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecviddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecauddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma Original: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Duração: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeduracaohdown." horas e ".$filmeduracaomidown." minutos.</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Resolução: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='filmeresolucao' value='" . $row["filmeresolucao"] . "' size='10' />pixels de largura por<input type='text' name='filmeresolucalt' value='" . $row["filmeresolucalt"] . "' size='10' />pixels de altura.</td></TR>";  
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";

}
//// video tv
  if ($row["category"] == 49 || $row["category"] == 101 || $row["category"] == 99 || $row["category"] == 100 || $row["category"] == 103) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeaudiodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeextensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Qualidade: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmequalidadedown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Video Tv em 3D: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filme3ddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Legenda Embutidas: </b></TD><TD width=50%  align=left class=tab1_col3 >".$legendadown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Vídeo: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecviddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecauddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma Original: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Duração: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeduracaohdown." horas e ".$filmeduracaomidown." minutos.</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Resolução: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='filmeresolucao' value='" . $row["filmeresolucao"] . "' size='10' />pixels de largura por<input type='text' name='filmeresolucalt' value='" . $row["filmeresolucalt"] . "' size='10' />pixels de altura.</td></TR>";  
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 4: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens5\" value=\"" . htmlspecialchars($row["screens5"]) . "\" size=\"60\" /></TD></TR>";
}
//// show
  if ($row["category"] == 110 ) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeaudiodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeextensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Qualidade: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmequalidadedown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Series em 3D: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filme3ddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Legenda Embutidas: </b></TD><TD width=50%  align=left class=tab1_col3 >".$legendadown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Vídeo: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecviddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecauddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma Original: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Duração: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeduracaohdown." horas e ".$filmeduracaomidown." minutos.</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Resolução: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='filmeresolucao' value='" . $row["filmeresolucao"] . "' size='10' />pixels de largura por<input type='text' name='filmeresolucalt' value='" . $row["filmeresolucalt"] . "' size='10' />pixels de altura.</td></TR>";  
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";

}
  if ($row["category"] == 95 || $row["category"] == 98 || $row["category"] == 97 || $row["category"] == 96 ) 
{

echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeaudiodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeextensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Qualidade: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmequalidadedown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Show em 3D: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filme3ddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Legenda Embutidas: </b></TD><TD width=50%  align=left class=tab1_col3 >".$legendadown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Vídeo: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecviddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecauddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma Original: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Duração: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeduracaohdown." horas e ".$filmeduracaomidown." minutos.</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Resolução: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='filmeresolucao' value='" . $row["filmeresolucao"] . "' size='10' />pixels de largura por<input type='text' name='filmeresolucalt' value='" . $row["filmeresolucalt"] . "' size='10' />pixels de altura.</td></TR>";  
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 4: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens5\" value=\"" . htmlspecialchars($row["screens5"]) . "\" size=\"60\" /></TD></TR>";

}

///filmes
 if ($row["category"] == 109 ) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$revistatensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Autor: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='musicalinkloja' value='" . $row["musicalinkloja"] . "' size='30' /></td>";   
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";

}
 if ($row["category"] == 93 ) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$revistatensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Autor: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='musicalbum' value=\"" . htmlspecialchars($row["musicalbum"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";







}
 if ($row["category"] == 9   ) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3  >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$revistatensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Autor: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='musicalinkloja' value='" . $row["musicalinkloja"] . "' size='30' /></td>";   
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3  ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";
}
 if ($row["category"] == 111 ) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Autor: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='musicalinkloja' value='" . $row["musicalinkloja"] . "' size='30' /></td>";   
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3  ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
}
///filmes adultos
 if ($row["category"] == 47 ) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeaudiodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeextensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Qualidade: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmequalidadedown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Filme em 3D: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filme3ddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Legenda Embutidas: </b></TD><TD width=50%  align=left class=tab1_col3 >".$legendadown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Vídeo: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecviddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecauddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma Original: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Duração: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeduracaohdown." horas e ".$filmeduracaomidown." minutos.</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Resolução: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='filmeresolucao' value='" . $row["filmeresolucao"] . "' size='10' />pixels de largura por<input type='text' name='filmeresolucalt' value='" . $row["filmeresolucalt"] . "' size='10' />pixels de altura.</td></TR>";  
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";


}
 if ($row["category"] == 106 ) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeaudiodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeextensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Qualidade: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmequalidadedown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Filme em 3D: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filme3ddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Legenda Embutidas: </b></TD><TD width=50%  align=left class=tab1_col3 >".$legendadown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Vídeo: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecviddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecauddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma Original: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Duração: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeduracaohdown." horas e ".$filmeduracaomidown." minutos.</TD></TR>";

echo "<tr><td width=50%  align=right  class=tab1_col3><B>Resolução: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='filmeresolucao' value='" . $row["filmeresolucao"] . "' size='10' />pixels de largura por<input type='text' name='filmeresolucalt' value='" . $row["filmeresolucalt"] . "' size='10' />pixels de altura.</td></TR>";  
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";



}
 if ($row["category"] == 104 || $row["category"] == 113) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$revistatensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Autor: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='musicalinkloja' value='" . $row["musicalinkloja"] . "' size='30' /></td>";   
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";







}




 if ($row["category"] == 114 ) 
{
echo "<tr><td width=50%  align=right  class=tab1_col3><b>".T_("CATEGORIES").": </b></td><td width=50%  align=left class=tab1_col3 >".$catdropdown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Horário de Seed: </b></TD><TD width=50%  align=left class=tab1_col3 >".temposeed($row["temposeed"])."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Ano de lançamento: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeanodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeaudiodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Extensão: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeextensaodown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Qualidade: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmequalidadedown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Filme em 3D: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filme3ddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Legenda Embutidas: </b></TD><TD width=50%  align=left class=tab1_col3 >".$legendadown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Vídeo: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecviddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Codecs de Audio: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmecodecauddown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Idioma Original: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeidiomaorigidown."</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Duração: </b></TD><TD width=50%  align=left class=tab1_col3 >".$filmeduracaohdown." horas e ".$filmeduracaomidown." minutos.</TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3><B>Resolução: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type='text' name='filmeresolucao' value='" . $row["filmeresolucao"] . "' size='10' />pixels de largura por<input type='text' name='filmeresolucalt' value='" . $row["filmeresolucalt"] . "' size='10' />pixels de altura.</td></TR>";  
///screenshots
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Capa: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens1\" value=\"" . htmlspecialchars($row["screens1"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 1: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens2\" value=\"" . htmlspecialchars($row["screens2"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 2: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens3\" value=\"" . htmlspecialchars($row["screens3"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 3: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens4\" value=\"" . htmlspecialchars($row["screens4"]) . "\" size=\"60\" /></TD></TR>";
echo "<tr><td width=50%  align=right  class=tab1_col3 ><B>Screen 4: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"text\" name=\"screens5\" value=\"" . htmlspecialchars($row["screens5"]) . "\" size=\"60\" /></TD></TR>";

}
///screenshots


// END RECOMMENDED TORRENTS ///

if ($row["external"] != "yes"){

    echo "<tr><td width=50%  align=right  class=tab1_col3><B>Torrent free: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"checkbox\" name=\"freeleech\"" . (($row["freeleech"] == "1") ? " checked=\"checked\"" : "" ) . " value=\"1\" />Free</TD></TR>";
    
/// START RECOMMENDED TORRENTS ///
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Sysop" ){
    echo "<tr><td width=50%  align=right  class=tab1_col3><B>Recomendar: </b></TD><TD width=50%  align=left class=tab1_col3 ><input type=\"checkbox\" name=\"recommended\"" . (($row["recommended"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" />Destaques Malucos</TD></TR>";
}
// END RECOMMENDED TORRENTS ///
	}

if ($site_config['ANONYMOUSUPLOAD']) {
	echo "<tr><td width=50%  align=right  class=tab1_col3><B>Anonymous Upload: </b></TD><TD><input type=\"checkbox\" name=\"anon\"" . (($row["anon"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" />(Seu nome não será associado a este torrent)";
}
require_once("backend/bbcodecerto.php");
/// filmes
 if ($row["category"] == 23 || $row["category"] == 4 || $row["category"] == 39 || $row["category"] == 24 || $row["category"] == 3 || $row["category"] == 34 || $row["category"] == 35 || $row["category"] == 37 || $row["category"] == 42 || $row["category"] == 5 || $row["category"] == 40 || $row["category"] == 7 || $row["category"] == 28 || $row["category"] == 1 || $row["category"] == 25 || $row["category"] == 26 || $row["category"] == 2 || $row["category"] == 27 || $row["category"] == 33 || $row["category"] == 36 || $row["category"] == 50 || $row["category"] == 41 || $row["category"] == 6 || $row["category"] == 117 || $row["category"] == 124) 
{
echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Sinopse: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","filmesinopse",$dossier1,"" . htmlspecialchars($row["filmesinopse"]) . "")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Ficha Técnica: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";
}






 elseif ($row["category"] == 120) 
{

echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Sinopse: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","filmesinopse",$dossier1,"" . htmlspecialchars($row["filmesinopse"]) . "")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Ficha Técnica: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";
}

 elseif ($row["category"] == 20 || $row["category"] == 19 || $row["category"] == 18 || $row["category"] == 94 || $row["category"] == 115 || $row["category"] == 122  || $row["category"] == 123)  
{

echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Descrição / Ficha Técnica: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","filmesinopse",$dossier1,"" . htmlspecialchars($row["filmesinopse"]) . "")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Configurações Mínimas: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";
}
 elseif ($row["category"] == 114 )  
{
echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Descrição / Ficha Técnica: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","filmesinopse",$dossier1,"" . htmlspecialchars($row["filmesinopse"]) . "")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Configurações Mínimas: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";

}
elseif ($row["category"] == 102 || $row["category"] == 105 || $row["category"] == 10 || $row["category"] == 15 || $row["category"] == 11 || $row["category"] == 43 || $row["category"] == 12 || $row["category"] == 44 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 16 || $row["category"] == 121 || $row["category"] == 116)
{

echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Descrição / Ficha Técnica: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","filmesinopse",$dossier1,"" . htmlspecialchars($row["filmesinopse"]) . "")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Informações adicionais: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";

}
 elseif ($row["category"] == 112 )
{

echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Sinopse: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","filmesinopse",$dossier1,"" . htmlspecialchars($row["filmesinopse"]) . "")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Ficha Técnica: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";

}
 elseif ($row["category"] == 109 )
{

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Ficha Técnica: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";

}
  elseif ($row["category"] == 49 || $row["category"] == 101 || $row["category"] == 99 || $row["category"] == 100 || $row["category"] == 103) 
{
echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Sinopse: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","filmesinopse",$dossier1,"" . htmlspecialchars($row["filmesinopse"]) . "")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Ficha Técnica: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";

}
  elseif ($row["category"] == 110 ) 
{

echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Sinopse: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","filmesinopse",$dossier1,"" . htmlspecialchars($row["filmesinopse"]) . "")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Ficha Técnica: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";

}
  elseif ($row["category"] == 95 || $row["category"] == 98 || $row["category"] == 97 || $row["category"] == 96 ) 
{

echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Sinopse: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","filmesinopse",$dossier1,"" . htmlspecialchars($row["filmesinopse"]) . "")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Ficha Técnica: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";

}
  elseif ($row["category"] == 47 ) 
{

echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Sinopse: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","filmesinopse",$dossier1,"" . htmlspecialchars($row["filmesinopse"]) . "")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Ficha Técnica: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";

}
  elseif ($row["category"] == 106 ) 
{

echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Sinopse: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","filmesinopse",$dossier1,"" . htmlspecialchars($row["filmesinopse"]) . "")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Ficha Técnica: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";

}

else  {


echo "<TR ><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Descrição: *";
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("bbform","descr",$dossier,"" . htmlspecialchars($row["descr"]) . "")."");
echo "</TD></TR>";

}  


$testeuser = mysql_query("SELECT * from moderation WHERE infohash=$id");
            $testeapro = mysql_fetch_array($testeuser);
			if ($row["safe"] == "no") {
	if($CURUSER["class"]== 1 || $CURUSER["class"]== 25 || $CURUSER["class"]== 30 || $CURUSER["class"]== 35 || $CURUSER["class"]== 40 || $CURUSER["class"]== 45 || $CURUSER["class"]== 55 || $CURUSER["class"]== 60 || $CURUSER["class"]== 65 || $CURUSER["class"]== 70 || $CURUSER["class"]== 75 || $CURUSER["class"]== 80 || $CURUSER["class"]== 85 || $CURUSER["class"]== 95  ){		
	if(mysql_num_rows($testeuser)>0) {


	if ( $testeapro["verifica"] == "no"){
if ($row["owner"] == $CURUSER["id"] ) {

echo "<tr><td width=50%  align=right  class=tab1_col3><center><B>Para que os MODs possam liberar seu torrrent favor deixar o checkbox marcado.</b></TD><TD width=50%  align=left class=tab1_col3 ><input type='checkbox' name='moderarok' value='yes'/>checkbox</center></TD></TR>";
}
	}	
	}
}
	}
print("<BR><tr><td width=100%  align=center colspan=2  class=tab1_col3><CENTER><input type=\"submit\" value='Enviar' style='height: 25px; width: 110px'> <input type=reset value='".UNDO."' style='height: 25px; width: 105px'></CENTER></TD></TR>\n");
print("</form>\n");
  print("</table>\n");
end_framec();
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador"){
begin_framec(T_("DELETE_TORRENT"));
print("<table class='tab1' cellpadding='0' cellspacing='1' align='center'>");
        print("<tr><td width=100%  align=center colspan=2  class=tab1_col3><center><form method='post' action='torrents-edit.php?action=deleteit&amp;id=$id'>\n");
        print("<input type='hidden' name='torrentid' value='$id' />\n");
        print("<input type='hidden' name='torrentname' value='".htmlspecialchars($row["name"])."' />\n");
        echo "<b>".T_("REASON_FOR_DELETE")."</b><input type='text' size='30' name='delreason' />";
        echo "&nbsp;<input type='submit' value='".T_("DELETE_TORRENT")."' /></form></center></TD></TR>";
		  print("</table>\n");
end_framec();
}
end_framec();

stdfoot();

?>