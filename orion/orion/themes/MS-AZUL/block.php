<?php
//BEGIN FRAME
function begin_frame($caption = "-", $align = "justify"){
    global $THEME, $site_config;
    print("<div class='myBlock'>
  <div class='myCaption'>$caption</div>
  <div class='myContent'>");
}


//END FRAME
function end_frame() {
    global $THEME, $site_config;
    print("</div>
</div>
<br>");
}
function begin_framec($caption = "-", $align = "justify"){
    global $THEME, $site_config;
    print("<div class='myBlock'><br>
  <div class='myCaptionc'>$caption</div>
  <br><div class='myContent'>");
}


//END FRAME
function end_framec() {
    global $THEME, $site_config;
    print("</div>
</div>
<br>");
}

//BEGIN BLOCK
function begin_block($caption = "-", $align = "justify"){
    global $THEME, $site_config;
    print("<div class='myBlock'>
  <div class='myCaption'>$caption</div>
  <div class='myContent'>");
}

//END BLOCK
function end_block(){
    global $THEME, $site_config;
    print("</div>
</div>
<br />");
}


//BEGIN BLOCK
function begin_blockT($caption = "-", $align = "justify"){
    global $THEME, $site_config;
    print("<div >
  <div class='myCaption'>$caption</div>
  <div class='myContentt'>");
}

//END BLOCK
function end_blockT(){
    global $THEME, $site_config;
    print("</div>
</div>
<br />");
}
//BEGIN BLOCK
function begin_blockl($caption = "-", $align = "justify"){
    global $THEME, $site_config;
    print("<div >
  <div class='myCaption'>$caption</div>
  <div class='myContentt'>");
}
 
//END BLOCK
function end_blockl(){
    global $THEME, $site_config;
    print("</div>
</div>
<br />");
}

function begin_table(){
    print("<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"ttable_headouter\" width=\"100%\"><tr><td><table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"ttable_headinner\" width=\"100%\">\n");
}

function end_table()  {
    print("</table></td></tr></table>\n");
}

function tr($x,$y,$noesc=0) {
    if ($noesc)
        $a = $y;
    else {
        $a = htmlspecialchars($y);
        $a = str_replace("\n", "<br />\n", $a);
    }
    print("<tr><td class=\"heading\" valign=\"top\" align=\"right\">$x</td><td valign=\"top\" align=left>$a</td></tr>\n");
}
?>