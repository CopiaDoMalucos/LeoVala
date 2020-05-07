<?php

 
  chdir( './../' );
 
require_once("backend/functionsindextor.php");
  dbconn();
  

  if ( ! isset( $_GET[ 'tid' ], $_SERVER[ 'HTTP_TT' ] ) )
  {
       autolink( './../index.php', 'The page you requested is not available.' );
  }
  if ($_GET["sort"] || $_GET["order"]) {

        switch ($_GET["sort"]) {
                case 'category': $sort = "torrents.category";break;
                case 'name': $sort = "torrents.name";break;
                case 'completed':       $sort = "torrents.times_completed";break;
                case 'seeders': $sort = "torrents.seeders";break;
                case 'leechers': $sort = "torrents.leechers";break;
                case 'comments': $sort = "torrents.comments";break;
                case 'size': $sort = "torrents.size";break;
                default: $sort = "torrents.id";
        }

        if ($_GET["order"] == "asc" || ($_GET["sort"] != "id" && !$_GET["order"])) {
                $sort .= " ASC";
                
        } else {
                $sort .= " DESC";
                
        }

          $orderby = "ORDER BY $sort";

        }else{
                $orderby = "ORDER BY torrents.markdate DESC";
                $_GET["sort"] = "markdate";
                $_GET["order"] = "desc";
        }
  $where = Array();
  
  switch ( $_GET[ 'tid' ] )
  {
      default:
      case 1:  $where[] = "torrents.category IN (2,4,5,6,7,18,19,11,121,13,12,14,10,15,116,44,16,105,102,43, 23,24,25,26,27,33,34,35,36,37,39,40,41,42,49,93,94,95,108,109,110,112,114,115,117,119,120,122,123,124)";
           break;
           
      case 2:  $where[] = "torrents.category IN (51,52,53,54,55,56,57,58,59,60,61,62,64,65,66,67,68,69,70,71,72,73,74,75,76,78,79,80,82,83,84,85,86,87,88,89,90,91,107,118)";
           break;
           
      case 3:  $where[] = "torrents.category IN (11,121,13,12,14,10,15,116,44,16,105,102,43)";
           break;
		   
	  case 4:  $where[] = "torrents.category IN (9,111,113)";
           break;   
		   
	  case 5:  $where[] = "torrents.category IN (47,104,106)";
           break;   
  }
  
$where[] = "safe = 'yes'";


if ($CURUSER["ver_xxx"]!="yes") {
    $where[] = "torrents.category != '106'";
     $where[] = "torrents.category != '104'";
    $where[] = "torrents.category != '47'";

}
  
  $where = implode( ' AND ', $where );
  
  
 $torrentesp = $CURUSER["torrentesp"];

  $query = "SELECT torrents.id,  torrents.category, torrents.leechers, torrents.banned, torrents.comments, torrents.seeders, torrents.name,  torrents.size, torrents.added,  torrents.filename, torrents.owner, torrents.freeleech, torrents.screens1, torrents.filmeresolucao, torrents.filmeresolucalt, torrents.filme3d,  torrents.safe, torrents.apliversao, filmeaudio.name AS  filmeaudio_name , filmequalidade.name AS filmequalidade_name, filmeextensao.name AS  filmeextensao_name,  filmeano.name AS  filmeano_name,  jogosgenero.name AS  jogosgenero_name, apliformarq.name AS  apliformarq_name, aplicrack.name AS  aplicrack_name, revistatensao.name AS revistatensao_name, musicatensao.name AS  musicatensao_name, musicaqualidade.name AS  musicaqualidade_name, users.freeleechuser AS freeleechuser,  categories.name AS cat_name, categories.image AS cat_pic, categories.parent_cat AS cat_parent, users.username FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id LEFT JOIN filmeaudio ON torrents.filmeaudio =  filmeaudio.id LEFT JOIN filmequalidade ON torrents.filmequalidade = filmequalidade.id LEFT JOIN filmeextensao ON torrents. filmeextensao =  filmeextensao.id LEFT JOIN filmeano ON torrents. filmeano =  filmeano.id LEFT JOIN jogosgenero ON torrents.jogosgenero =  jogosgenero.id  LEFT JOIN apliformarq ON torrents.apliformarq =  apliformarq.id LEFT JOIN aplicrack ON torrents.aplicrack =  aplicrack.id LEFT JOIN revistatensao ON torrents.revistatensao =  revistatensao.id LEFT JOIN musicatensao ON torrents.musicatensao =  musicatensao.id LEFT JOIN musicaqualidade ON torrents.musicaqualidade =  musicaqualidade.id  WHERE  seeders AND ". $where ."   $orderby LIMIT 0, $torrentesp";
      $res = mysql_query($query);
  if ( mysql_num_rows( $res ) )
  {
       torrenttable( $res );
         print("<div style='text-align: center;'>[ <a href='torrents.php'>Ver mais lançamentos</a> ]</div>");
       return;
  }
  
  echo 'Nada Encontrado.';
         print("<div style='text-align: center;'>[ <a href='torrents.php'>Ver mais lançamentos</a> ]</div>");
