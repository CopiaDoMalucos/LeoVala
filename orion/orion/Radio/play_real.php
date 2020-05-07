<? include "getinfo.php";?>
<html><head></head><body>

<OBJECT
ID=audio1
CLASSID="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA"
HEIGHT=31 WIDTH=378>
<PARAM NAME="controls" VALUE="ControlPanel">
<PARAM NAME="console" VALUE="Clip1">
<param name="_ExtentX" value="10001">
<param name="_ExtentY" value="820">
<param name="AUTOSTART" value="1">
<param name="SHUFFLE" value="0">
<param name="PREFETCH" value="0">
<param name="NOLABELS" value="0">
<param name="LOOP" value="0">
<param name="NUMLOOP" value="0">
<param name="CENTER" value="0">
<param name="MAINTAINASPECT" value="0">
<param name="BACKGROUNDCOLOR" value="#ffffff">
<param name="SRC" ref value="http://<?=$scip;?>:<?=$scport;?>/listen.pls">
<EMBED type="audio/x-pn-realaudio-plugin" CONSOLE="Clip1" CONTROLS="ControlPanel"
HEIGHT=35 WIDTH=275 AUTOSTART=true></OBJECT>

</body>
</html>