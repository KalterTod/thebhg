<?php
function title() {
    return 'Index';
}

function output() {

	if (isset($_COOKIE["bhg_hashk"])){
	
		echo 'Cannot open terminal. Resource locked out by specialist for endangering guild holonet integrity.';
		exit;
		
	}
	
	echo '<script language="javascript" type="text/javascript" src="/rubicon/type.js"></script>';
   
	echo '<DIV style="width: 700; height: 500; background-color: black; color: green;" ID="textDestination"></DIV>';
	
	?><SCRIPT LANGUAGE="JavaScript">
<!--
var yes = 1;
var pause = 0;
<?php
if (isset($_COOKIE['bhg_nhashafvasl'])){
?>
	var text = "<tt>holonet::__loadCache(\'/local.trcrt.log\')";
<?
} else {
?>
	var text = "<tt>holonet::__loadBootModule('/specialist/rubicon.ijc')<br />...<br />...<br />...<br />Done<br /><br />holonet::__cacheHold('/specialist/traceroute.iio', 'trcrt')<br /><br />...<br />...<br />...<br />Done<br /><br />execute rubiconTerminal.kib<br />...<br />Done<br /><br /><br /><br />Welcome to the Rubicon Terminal, root.<br /><br />executeCache('trcrt')<br /><br />Executing the TraceRoute Program.<br />...<br />...<br />Please Wait<br />...<br />...<br />...<br />...<be />..."
<? } ?>

startTyping(text, 50, "textDestination");
//-->
	</script><?
}
?>
