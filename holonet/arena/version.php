<?php
function title() {
    return 'Arena Management System :: Version';
}

function output() {
    global $arena;

    arena_header();

    echo "Current version: v3.0";
    
    hr();
    
    echo "The Arena Management System (AMS) is an all inclusive system that monitors,
records, and processes activities dealing with Role-Playing in the Bounty
Hunter's Guild. The AMS is broken down into 2 main divisions: The Arena
Management System Tracking Network (ATN), which monitors and tabulates
ladder results and tracks all matches, and the Arena Management System
Challenge Network (ACN), which allows a Hunter to challenge another hunter
to a match via the BHG Holonet. The AMS is monitored and maintained by the
Overseer and the Adjunct. It is they who manage and maintain the functions
of the AMS, to ensure the RP world runs quickly and smoothly.";
    
    hr();
    
    echo "Version Comments: First working build of the new system.";
    
    hr();
    
    echo "Last Update: Saturday 1/15/2005";
    
    hr();
    
   	echo "The Arena Management System is &copy; Ric Gravant, 2004-2005.";
	
    arena_footer(false);
}
?>