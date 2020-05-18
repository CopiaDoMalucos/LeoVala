<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################

function T_ ($s) {
	GLOBAL $LANG;
    
    $s = str_replace(" ", "_", strtoupper($s));
	
	if ($ret = $LANG[$s]) {
		return $ret;
	}

	if ($ret = $LANG["{$s}[0]"]) {
		return $ret;
	}

	return $s;
}

function P_ ($s, $num) {
	GLOBAL $LANG;

	$num = (int) $num;

	$plural = str_replace("n", $num, $LANG["PLURAL_FORMS"]);
	$i = eval("return intval($plural);");

	if ($ret = $LANG["{$s}[$i]"])
		return $ret;

	return $s;
}

?>