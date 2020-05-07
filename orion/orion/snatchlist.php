<?php

############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################

require_once('backend/functions.php');
dbconn();
loggedinonly();

	  date_default_timezone_set('Etc/GMT+1');
$tid = ( int ) $_GET['tid'];

$tor = SQL_Query_exec('SELECT `name` FROM `torrents` WHERE `id` = \'' . $tid . '\' AND `banned` = \'no\' AND `external` = \'no\' AND `freeleech` = \'0\'');


$count = get_row_count('snatched_t', 'WHERE `tid` = \'' . $tid . '\'');

list($header, $footer, $limit) = pager(20, $count, 'snatchlist.php?tid=' . $tid . '&amp;');

$qry = "SELECT
                         users.id,
                         users.username,
                         users.class,
                         snatched_t.uid as uid,
                         snatched_t.tid as tid,
                         snatched_t.uload,
                         snatched_t.dload,
                         snatched_t.stime,
                         snatched_t.utime,
                         snatched_t.ltime,
                         snatched_t.completed,
                         (
                                         SELECT seeder
                                         FROM peers
                                         WHERE torrent = tid AND userid = uid LIMIT 1
                         ) AS seeding                   
                 FROM
                         snatched_t
                 INNER JOIN users ON snatched_t.uid = users.id
                 WHERE
                         users.enabled = 'yes' AND users.status = 'confirmed' AND
                         snatched_t.tid = '$tid' $limit";
                        
                 $res = SQL_Query_exec($qry);
                
                 $title = sprintf( T_("SNATCHLIST_FOR"), htmlspecialchars( $torrent[ 0 ] ) );
                
                 stdhead( Estatística );
                 begin_framec( Estatística );
				   ?>

  
  <br><p align="center"><a href="torrents-details.php?id=<?php echo $tid; ?>"><?php echo 'Voltar para a página do Torrent' ?></a></p><br>
  
  <?php
                if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="S.Moderador" ){
                 if ( mysql_num_rows($res) > 0 ): ?>
                 <table border="0" class="table_table" cellpadding="3" cellspacing="3" width="100%">
                 <tr>
                         <th class="ttable_head"><?php echo T_("USERNAME") ?></th>
                         <th class="ttable_head"><?php echo T_("CLASS") ?></th>
                         <th class="ttable_head">Upload</th>
                         <th class="ttable_head">Download</th>
                         <th class="ttable_head">Inicio</th>
                         <th class="ttable_head">Última ação</th>
                         <th class="ttable_head"><?php echo T_("COMPLETED") ?></th>
                         <th class="ttable_head"><?php echo T_("SEEDING") ?></th>
                         <th class="ttable_head">Tempo de seed</th>
                 </tr>
                 <?php while ( $row = mysql_fetch_row( $res ) ): ?>
                 <tr align="center">
                         <td class="table_col1"><a href="account-details.php?id=<?php echo $row[ 0 ]; ?>"><?php echo htmlspecialchars( $row[ 1 ] ); ?></a></td>
                         <td class="table_col2"><?php echo get_user_class_name( $row[ 2 ] ); ?></td>
                         <td class="table_col1"><?php echo mksize( $row[ 5 ] ); ?></td>
                         <td class="table_col2"><?php echo mksize( $row[ 6 ] ); ?></td>
                         <td class="table_col1"><?php echo date( 'd-m-Y \a\s H:i:s', $row[ 7 ] ); ?></td>
                         <td class="table_col2"><?php echo date( 'd-m-Y \a\s H:i:s', $row[ 8 ] ); ?></td>
                         <td class="table_col1"><?php echo ( $row[ 10 ] ) ? 'yes' : 'no'; ?></td>
                         <td class="table_col2"><?php echo ( $row[ 11 ] ) ? $row[ 11 ] : 'no'; ?></td>
                         <td class="table_col1"><?php echo ( $row[ 9 ] ) ? seedtime( $row[ 9 ] ) : '-'; ?></td>
					
                 </tr>
	
                 <?php
				 $upload = $upload +  $row[ 5 ];
$baixado = $baixado +  $row[ 6 ];
				 endwhile; 

				 ?>
                 </table>
				            <table border="0" class="table_table" cellpadding="3" cellspacing="3" width="100%">
							<TR><td width="50%"  align="right"  class="table_col1">Total Enviado</td><TD width="50%"  align="left" class="table_col1"><b><?php echo mksize($upload); ?></b></td></tr>
                            <TR><td width="50%"  align="right"  class="table_col1">Total baixado</td><TD width="50%"  align="left" class="table_col1"><b><?php echo mksize($baixado); ?></b></td></tr>							</table>
                 <?php else: ?>
                 <div align="center"><b>O Torrente escolhido não tem estatística</b></div>
                 <?php endif;

                 if ( $count > 20 ) echo $footer;
               } else{
			   
			   		show_error_msg("Error", "Desculpe, você não tem permissões de acesso a esta página!", 1);
		
			   
			   }
			   				   ?>


  <br><p align="center"><a href="torrents-details.php?id=<?php echo $tid; ?>"><?php echo 'Voltar para a página do Torrent' ?></a></p><br>
  
  <?php
                 end_framec();
                 stdfoot();

?>