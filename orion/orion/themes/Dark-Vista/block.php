<?
//BEGIN FRAME
function begin_frame($caption = "-", $align = "justify"){
    global $THEME;
    global $site_config;
    print("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td width=\"7\" height=\"25\"><img src=\"themes/Dark-Vista/images/box-tl.png\" width=\"7\" height=\"25\"></td>
<td/ width=\"100%\" class=\"b-title\" height=\"25\" background=\"themes/Dark-Vista/images/box-tm.png\">$caption</td>
<td width=\"7\" height=\"25\"><img src=\"themes/Dark-Vista/images/box-tr.png\" width=\"7\" height=\"25\"></td>
</tr>
</table><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td width=\"100%\" valign=\"top\" class=\"b-content\">");
}


//END FRAME
function end_frame() {
    global $THEME;
    global $site_config;
    print("</td>
</tr>
</table>
</td>
</tr>
</table>
<BR>");
}

//BEGIN BLOCK
function begin_block($caption = "-", $align = "justify"){
    global $THEME;
    global $site_config;
    print("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td width=\"7\" height=\"25\"><img src=\"themes/Dark-Vista/images/box-tl.png\" width=\"7\" height=\"25\"></td>
<td/ width=\"100%\" class=\"b-title\" height=\"25\" background=\"themes/Dark-Vista/images/box-tm.png\">$caption</td>
<td width=\"7\" height=\"25\"><img src=\"themes/Dark-Vista/images/box-tr.png\" width=\"7\" height=\"25\"></td>
</tr>
</table><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td width=\"100%\" valign=\"top\" class=\"b-content\">");
}

//END BLOCK
function end_block(){
    global $THEME;
    global $site_config;
    print("</td>
</tr>
</table>
</td>
</tr>
</table>
<BR>");
}

function begin_table(){
    print("<table align=center cellpadding=\"0\" cellspacing=\"0\" class=\"ttable_headouter\" width=100%><tr><td><table align=center cellpadding=\"0\" cellspacing=\"0\" class=\"ttable_headinner\" width=100%>\n");
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