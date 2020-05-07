<?php
// ADDON Calculadora by BrayanP
require_once("backend/functions.php");
dbconn(false);
loggedinonly();

stdhead("Calculadora de Bytes");
	begin_framec("Calculadora de Bytes");
?>
<form name="bandwidth">

  <p><input type="text" name="original" size="20" value="Digite o valor"> <select size="1" name="units">
    <option value="Bytes">Bytes</option>
    <option value="Kb">Kb</option>
    <option value="Mb">Mb</option>
    <option value="Gb">Gb</option>
  </select> <input type="button" value="Calcular" name="B1" onClick="calculate()"></p>
</form>

<p>

<script>


var bytevalue=0
function calculate(){
var invalue=document.bandwidth.original.value
var selectunit=document.bandwidth.units.options[document.bandwidth.units.selectedIndex].value
if (selectunit=="Bytes")
bytevalue=invalue
else if (selectunit=="Kb")
bytevalue=invalue*1024
else if (selectunit=="Mb")
bytevalue=invalue*1024*1024
else if (selectunit=="Gb")
bytevalue=invalue*1024*1024*1024

alert (invalue+" "+selectunit+" é igual a:\n\n- "+bytevalue+" Bytes\n- "+Math.round(bytevalue/1024)+" Kb\n- "+Math.round(bytevalue/1024/1024)+" Mb\n- "+Math.round(bytevalue/1024/1024/1024)+" Gb\n")
}

</script>
<?php
end_framec();
stdfoot();