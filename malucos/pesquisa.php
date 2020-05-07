<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
 
 
      

                  
  $tipo = (int) $_GET["tipo"];

  $search =  $_GET["search"];

if ($tipo == 1) 
header("Location: ../torrents-pesquisa.php?search=$search&cat=0&termos=qualquer&search_in=titles");
  
if  ($tipo == 2) 
header("Location: ../forums.php?action=search&keywords=$search");

if ($tipo == 3) 
header("Location: ../memberlist.php?Usuario=$search");

  
   
  
  
  
  
  
?>