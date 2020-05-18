<?php
//BEGIN FRAME
function begin_frame($caption = "-", $align = "justify"){
    global $THEME, $site_config;
    print("<div class='myFrame' width='200'>
  <div class='myFrame-head'>
    <div class='myFrame-Caption'>$caption</div><br>
  </div>
  <div class='myFrame-content'>");
}


//END FRAME
function end_frame() {
    global $THEME, $site_config;
    print("</div>
</div>");
}
function begin_framec($caption = "-", $align = "justify"){
    global $THEME, $site_config;
    print("<div class='myFrame' width='200'>
  <div class='myFrame-head'>
    <div class='myFrame-Caption'>$caption</div><br>
  </div>
  <div class='myFrame-content'>");
}


//END FRAME
function end_framec() {
    global $THEME, $site_config;
    print("</div>
</div>");
}
//BEGIN BLOCK
function begin_block($caption = "-", $align = "justify"){
    global $THEME, $site_config;
	global $blockfilename;
    print("<div id='$blockfilename' class='lcol d-shad1 b-rad2 '>
  <div class='myBlock-head b-rad2'>
    <div class='myBlock-Caption grad2 i-shad b-rad1'>$caption</div>
  </div>
  <div class='myBlock-content'>");
}

//END BLOCK
function end_block(){
    global $THEME, $site_config;
    print("</div>
</div>");
}

//BEGIN BLOCK
function begin_blockt($caption = "-", $align = "justify"){
    global $THEME, $site_config;
	global $blockfilename;
    print("<div id='$blockfilename' class='lcol d-shad1 b-rad2 '>
  <div class='myBlock-head b-rad2'>
    <div class='myBlock-Caption grad2 i-shad b-rad1'>$caption</div>
  </div>
  <div class='myBlock-content'>");
}
//BEGIN BLOCK
function begin_blockl($caption = "-", $align = "justify"){
    global $THEME, $site_config;
 print("<div id='$blockfilename' class='lcol d-shad1 b-rad2 '>
  <div class='myBlock-head b-rad2'>
    <div class='myBlock-Caption grad2 i-shad b-rad1'>$caption</div>
  </div>
  <div class='myBlock-content' >");
}

//END BLOCK
function end_blockl(){
    global $THEME, $site_config;
    print("</div>
</div>
<br />");
}
//END BLOCK
function end_blockt(){
    global $THEME, $site_config;
    print("</div>
</div>");
}
function begin_table(){
    print("<div class='ttable_headouter'><table align=center cellpadding=\"0\" cellspacing=\"0\" class=\"ttable_headinner\" width=100%>\n");
}

function end_table()  {
    print("</table></div>\n");
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