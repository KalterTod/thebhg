<?php
function title() {
    return 'Arena Management System :: Version';
}

function output() {
    global $arena;

    arena_header();

    echo "Current version: v2.1";
    
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
    
    echo "Version Comments: Added control options for the Solo Commissioner. Made not suck the way the ACN reads Dojo missions, so now, hunters can request a location for the Holoprojectors in the Dojo. Big updates to the ATN. Most notably the Arena Distinctions bar, which depicts graphical representations of people's Arena achivements. The Award tracker is also running now on all scripts, and 4 more Arena Aide positions for tracking/access purposes. They are the Registrar [Character Sheets], Mission Master [Run-Ons], Steward [Arena], and Skipper [Starfield Arena].";
    
    hr();
    
    echo "Last Update: October 29, 2004";
    
    hr();
    
   	echo "The Arena Management System is &copy; Ric Gravant, 2004.";

   	hr();
   	
   	echo 'Did you find an NEC Error while using the AMS and want to know what it is? Check <a href="' . internal_link('nec') . '">here [NEC]</a> for more information on the error.';
   	
    arena_footer();
}
?>