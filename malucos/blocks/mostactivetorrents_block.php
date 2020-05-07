<?php
if (!$site_config["MEMBERSONLY"] || $CURUSER) {
	begin_blockl("Destaques (<a class='adenisair' href=destaques.php><b>Todos</b></a>)");
	

	$expire = 9000; // time in seconds
if (($latestuploadsrecords = $TTCache->Get("latestuploadsblock")) === false) {
		$latestuploadsquery = SQL_Query_exec("SELECT torrents.id,  torrents.category, torrents.leechers, torrents.comments, torrents.seeders, torrents.name,  torrents.size, torrents.added,  torrents.filename, torrents.owner, torrents.freeleech, torrents.screens1, torrents.filmeresolucao, torrents.filmeresolucalt, torrents.filme3d,  torrents.safe, torrents.apliversao, filmeaudio.name AS  filmeaudio_name , filmequalidade.name AS filmequalidade_name, filmeextensao.name AS  filmeextensao_name,  filmeano.name AS  filmeano_name,  jogosgenero.name AS  jogosgenero_name, apliformarq.name AS  apliformarq_name, aplicrack.name AS  aplicrack_name, revistatensao.name AS revistatensao_name, musicatensao.name AS  musicatensao_name, musicaqualidade.name AS  musicaqualidade_name, categories.name AS cat_name, categories.image AS cat_pic, categories.parent_cat AS cat_parent, users.username FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id LEFT JOIN filmeaudio ON torrents.filmeaudio =  filmeaudio.id LEFT JOIN filmequalidade ON torrents.filmequalidade = filmequalidade.id LEFT JOIN filmeextensao ON torrents. filmeextensao =  filmeextensao.id LEFT JOIN filmeano ON torrents. filmeano =  filmeano.id LEFT JOIN jogosgenero ON torrents.jogosgenero =  jogosgenero.id  LEFT JOIN apliformarq ON torrents.apliformarq =  apliformarq.id LEFT JOIN aplicrack ON torrents.aplicrack =  aplicrack.id LEFT JOIN revistatensao ON torrents.revistatensao =  revistatensao.id LEFT JOIN musicatensao ON torrents.musicatensao =  musicatensao.id LEFT JOIN musicaqualidade ON torrents.musicaqualidade =  musicaqualidade.id  WHERE banned = 'no'  AND torrents.recommended='yes' ORDER BY rand() DESC LIMIT 6");
		$latestuploadsrecords = array();
		while ($latestuploadsrecord = mysql_fetch_assoc($latestuploadsquery))
			$latestuploadsrecords[] = $latestuploadsrecord;
		$TTCache->Set("latestuploadsblock", $latestuploadsrecords);
	}
print("<table width=100% border=0 valign=top></CENTER><DIV><tr><CENTER>");
	if ($latestuploadsrecords) {
		foreach ($latestuploadsrecords as $row) { 
		
			$char1 = 18; //cut length 
			$smallname = htmlspecialchars($row["name"]);

			
			 if ($row["category"] == 47 || $row["category"] == 106 ){			  

              			     $ler = "Audio: &lt;B&gt;" .$row["filmeaudio_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["filmequalidade_name"] . "&lt;/B&gt;&lt;br&gt;Extensão: &lt;B&gt;" .$row["filmeextensao_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Resolução: &lt;B&gt;" . $row["filmeresolucao"] . "X" . $row["filmeresolucalt"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;";    
				 }

	           elseif ($row["category"] == 2 || $row["category"] == 3 || $row["category"] == 4 || $row["category"] == 5 || $row["category"] == 6 || $row["category"] == 7 || $row["category"] == 23 || $row["category"] == 24 || $row["category"] == 25 || $row["category"] == 26 || $row["category"] == 27 || $row["category"] == 33 || $row["category"] == 34 || $row["category"] == 35 || $row["category"] == 36 || $row["category"] == 37 || $row["category"] == 39 || $row["category"] == 40 || $row["category"] == 41 || $row["category"] == 42 || $row["category"] == 49 || $row["category"] == 95 || $row["category"] == 96 || $row["category"] == 97 || $row["category"] == 98 || $row["category"] == 99 || $row["category"] == 100 || $row["category"] == 101 || $row["category"] == 103 || $row["category"] == 110 || $row["category"] == 118 || $row["category"] == 114 || $row["category"] == 117 || $row["category"] == 120 || $row["category"] == 124 || $row["category"] == 112) 
	              {			  
			     $ler = "Audio: &lt;B&gt;" .$row["filmeaudio_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["filmequalidade_name"] . "&lt;/B&gt;&lt;br&gt;Extensão: &lt;B&gt;" .$row["filmeextensao_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Resolução: &lt;B&gt;" . $row["filmeresolucao"] . "X" . $row["filmeresolucalt"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;";
                        }	

				        elseif ($row["category"] == 9 || $row["category"] == 109 || $row["category"] == 113 ) 
	              {			
                        $ler = "Extensão: &lt;B&gt;" .$row["revistatensao_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;";				  
                        }	

				        elseif ($row["category"] == 111 ) 
	              {			
                         $ler = "Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;";				  
                        }	

						elseif ( $row["category"] == 104) 
	              {			
                         $ler = "Extensão: &lt;B&gt;" .$row["revistatensao_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;";				  
                        }

				       elseif ($row["category"] == 10 || $row["category"] == 11 || $row["category"] == 12 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 15 || $row["category"] == 16 || $row["category"] == 43 || $row["category"] == 44 ||   $row["category"] == 120  || $row["category"] == 121  || $row["category"] == 105)    
	              {			
                        $ler = "Plataforma: &lt;B&gt;" .$row["cat_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Genero: &lt;B&gt;" .$row["jogosgenero_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;";
                        }	

				      elseif ($row["category"] == 18 || $row["category"] == 20 || $row["category"] == 94 || $row["category"] == 115 || $row["category"] == 122 || $row["category"] == 123 ) 
	              {			  
                         $ler = "Extenção: &lt;B&gt;" .$row["apliformarq_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Crack: &lt;B&gt;" .$row["aplicrack_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;";
                        }	

				      elseif ($row["category"] == 51 || $row["category"] == 52 || $row["category"] == 82 || $row["category"] == 53 || $row["category"] == 54 || $row["category"] == 55 || $row["category"] == 56 || $row["category"] == 57 || $row["category"] == 58 || $row["category"] == 59 || $row["category"] == 60 || $row["category"] == 61 || $row["category"] == 62 || $row["category"] == 64 || $row["category"] == 65 || $row["category"] == 66 || $row["category"] == 67 || $row["category"] == 68 || $row["category"] == 69 || $row["category"] == 70 || $row["category"] == 71 || $row["category"] == 72 || $row["category"] == 73 || $row["category"] == 74 || $row["category"] == 75 || $row["category"] == 76 || $row["category"] == 78 || $row["category"] == 79 || $row["category"] == 80 || $row["category"] == 82 || $row["category"] == 83 || $row["category"] == 84 || $row["category"] == 85 || $row["category"] == 86 || $row["category"] == 87 || $row["category"] == 88 || $row["category"] == 89 || $row["category"] == 90 || $row["category"] == 91 ) 
	              {			  
                  $ler = "Extenção: &lt;B&gt;" .$row["musicatensao_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["musicaqualidade_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;";
                   	   }		
								    
					elseif ($row["category"] == 108 ) 
	              {			  
				  $ler = "Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;";	
                        }

			$img1 = "</CENTER><BR><BR><a href='{$site_config[SITEURL]}/torrents-details.php?id=$row[id]'><img class='expando' border='0'src='{$row[screens1]}' title=\"".$ler."\" alt=\"$altname / $cat\" height=150 width=120></a><br></b><br><a href='torrents-details.php?id=$row[id]' title='".htmlspecialchars($row["name"])."'>$smallname</a><br />\n";
			

				print("<table align=center cellpadding=0 cellspacing=0 border=0>");
	print("<TR><td align=center>" .$img1. "</td>");
		}
	} else {
		print("<center>".T_("NOTHING_FOUND")."</center>\n");
	}
print("</tr></table></CENTER></marquee>");

end_blockl();
}
?>