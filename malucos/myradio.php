<?php
$showradioinfo="page";
include("radio-info.php");
stdhead();
begin_framec("Live Radio Feed 24/7");
?>
<p align="center"><b><br>
<br>
<a href="JavaScript:radio_player()">Click here to tune in and listen now.</a></b></p>
<div align="center">
 <center>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%">
 <tr>
   <td width="50%">
   <div align="center">
     <center>
     <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
       <tr>
         <td width="50%" align="center"><a href="JavaScript:radio_player()">
         <img border="0" src="radio/win.png" width="64" height="64"></a></td>
         <td width="50%" align="center"><a href="JavaScript:radio_player()">
         <img border="0" src="radio/real.png" width="80" height="70"></a></td>
       </tr>
       <tr>
         <td width="50%" align="center">
         <img border="0" src="radio/radio1.gif" width="170" height="44"></td>
         <td width="50%" align="center">
         <img border="0" src="radio/radio1.gif" width="170" height="44"></td>
       </tr>
     </table>
     </center>
   </div>
   </td>
   <td width="50%">
   <table border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
   <tr>
     <td align="left"><b>Status :</b> </td>
     <td><img border="0" src="<?php echo $radioimg; ?>"></td>
   </tr>
   <tr>
     <td align="left"><b>Currently Playing :&nbsp; </b></td>
     <td><?php echo $song[0]; ?></td>
   </tr>
   <tr>
     <td align="left"><b>Members Tuned In :&nbsp; </b></td>
     <td><?php echo $currentlisteners; ?></td>
   </tr>
   <tr>
     <td align="left" valign="top"><b>Last 5 Tracks Played..&nbsp; </b></td>
     <td><?php echo $song[1]."<BR>"; ?><?php echo $song[2]."<BR>"; ?><?php echo $song[3]."<BR>"; ?><?php echo $song[4]."<BR>"; ?><?php echo $song[5]."<BR>"; ?></td>
   </tr>
 </table>
   <p>&nbsp;</td>
 </tr>
</table>
 </center>
</div>
<p>&nbsp;</p>
<div align="center">
 <center>
 
 <p></p>
 
 </center>
</div>


<p align="center">Radio Powered by spank-d-monkey.com</p>


<?php
end_framec();

?>