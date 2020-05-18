


<?php
$fp = fsockopen ("187.45.245.85", 8000, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br>\n";
} else {
    fputs ($fp, "GET / / HTTP/1.0\r\nUser-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)\r\nHost: 88.171.19.27:8000\r\n\r\n");
    while (!feof($fp)) {
        $line = fgets ($fp,21048);
    eregi( "<font class=default>Song atual: </font></td><td><font class=default><b>(.*)</b></td></tr></table>", $line, $regs );
    $currentsong = htmlspecialchars($regs[1]);
     }
fclose($fp);
}
echo " <font size='1pt'><font color='white'> $currentsong </font></font>";
?>
