<?php
include_once('roster.inc');
include_once 'objects/core.inc';
include_once 'objects/shell.inc';










include_once('objects/arena.inc');
include_once('library.inc');
include_once('citadel.inc');

$roster = new Roster('fight-51-me');
$mb = new MedalBoard('fight-51-me');
$arena = new Arena();
$library = new Library();
$at = new Tournament();
$iat = new IRCTournament();
$citadel = new Citadel();
$lw = new LW_Solo();
$sheet = new Sheet();

function arena_header() {
    echo '<table border=0 width="100%"><tr valign="top"><td width="90%">';
}

function coders(){
	return array(2650);
}

function rp_staff($person){
	$position = $person->GetPosition();
	return ($position->GetID() == 9 || $position->GetID() == 29);
}

function acn_nav(){
	global $at, $lw, $person, $iat;
	
	//echo '<br />General<br />';
	//echo '&nbsp;<a href="' . internal_link('acn_challenge') . '">My&nbsp;Pending&nbsp;Challenges</a><br />';
	
	if ($at->ValidSignup()){
		echo '<br />Arena Tournament<br />';
	    echo '&nbsp;<a href="' . internal_link('acn_tournament_signup') . '"><b>Signup&nbsp;For&nbsp;Tournament</b></a><br /><br />';
	}
	
	if ($iat->ValidSignup()){
		echo '<br />IRC Arena Tournament<br />';
	    echo '&nbsp;<a href="' . internal_link('acn_irc_tournament_signup') . '"><b>Signup&nbsp;For&nbsp;Tournament</b></a><br /><br />';
	}
	
	echo '<small>Arena<br />';
	echo '&nbsp;<a href="' . internal_link('acn_arena_challenge') . '">Arena&nbsp;Challenges</a><br />';
	
	/*Removed until fixed.
	echo '<br />Starfield Arena<br />';
    echo '&nbsp;<a href="' . internal_link('acn_starfield_challenge') . '">Starfield&nbsp;Arena&nbsp;Challenges</a><br />';
    */
    
    echo '<br />Solo Missions<br />';
    echo '&nbsp;<a href="' . internal_link('acn_solo_contract') . '">Request&nbsp;Contract</a><br />';
    echo '&nbsp;<a href="' . internal_link('acn_solo_dco') . '">Request&nbsp;Dead&nbsp;Contract</a><br />';
    
    echo '<br />Lone Wolf Contracts<br />';
    echo '&nbsp;<a href="' . internal_link('acn_lw_contract') . '">Request&nbsp;Contract</a><br />';
    echo '&nbsp;<a href="' . internal_link('acn_lw_dco') . '">Request&nbsp;Dead&nbsp;Contract</a><br />';
    
    echo '<br />IRC Arena<br />';
    echo '&nbsp;<a href="' . internal_link('acn_irca_submit') . '">Submit&nbsp;Match</a><br />';
    
    /*More Elite nonsense
    echo '<br />Twilight Gauntlet<br />';
    echo '&nbsp;<a href="' . internal_link('acn_ttg_challenge') . '">Request&nbsp;Challenge</a><br />';

    echo '<br />Tempestuous Group<br />';
    echo '&nbsp;<a href="' . internal_link('acn_tempy_petition') . '">Admittance&nbsp;Petition</a><br />'; 
    */
}

function atn_nav(){
	global $roster, $at, $iat;
	
	echo '<small>Arena<br />';
	echo '&nbsp;<a href="' . internal_link('atn_arena') . '">Matches</a><br />';
    echo '&nbsp;<a href="' . internal_link('atn_arena_ladder') . '">Ladder</a><br />';
    
    echo '<br />Dojo of Shadows<br />';
	echo '&nbsp;<a href="' . internal_link('atn_dojo') . '">Dojo&nbsp;Learners</a><br />';
	echo '&nbsp;<a href="' . internal_link('atn_dojo_grad') . '">Dojo&nbsp;Graduates</a><br />';
    
	/*Commented out till it's fixed.
	echo '<br />Starfield Arena<br />';
    echo '&nbsp;<a href="' . internal_link('atn_starfield') . '">Matches</a><br />';
    echo '&nbsp;<a href="' . internal_link('atn_starfield_ladder') . '">Ladder</a><br />';
    */
    
    echo '<br />Solo Missions<br />';
    echo '&nbsp;<a href="' . internal_link('atn_solo') . '">Contracts</a><br />';
    echo '&nbsp;<a href="' . internal_link('atn_solo_ladder') . '">Ladder</a><br />';
    
    echo '<br />Lone Wolf Missions<br />';
    echo '&nbsp;<a href="' . internal_link('atn_lw') . '">Contracts</a><br />';
    echo '&nbsp;<a href="' . internal_link('atn_lw_ladder') . '">Ladder</a><br />';
    
    echo '<br />Run-Ons<br />';
    echo '&nbsp;<a href="' . internal_link('atn_ro') . '">Run-Ons</a><br />';
    echo '&nbsp;<a href="' . internal_link('atn_ro_ladder') . '">Ladder</a><br />';
    
    echo '<br />IRC Arena<br />';
    echo '&nbsp;<a href="' . internal_link('atn_irca') . '">Matches</a><br />';
    echo '&nbsp;<a href="' . internal_link('atn_irca_ladder') . '">Ladder</a><br />';
    
    /*Removing the Elite RP stuff
    echo '<br />Twilight Gauntlet<br />';
    echo '&nbsp;<a href="' . internal_link('atn_ttg') . '">Members</a><br />';
    
    echo '<br />Tempestuous Group<br />';
    echo '&nbsp;<a href="' . internal_link('atn_tempy') . '">Members</a><br />';
    */
    
    echo '<br />Arena Tournament<br />';
    echo '&nbsp;<a href="' . internal_link('atn_tournament') . '">Brackets</a><br />';
    if (count($at->GetHunters())){
    	echo '&nbsp;<a href="' . internal_link('atn_tournament_signups') . '">Signups</a><br />';
	}
	echo '&nbsp;<a href="' . internal_link('atn_tournament_archive') . '">Archived Tournaments</a><br />';

	echo '<br />IRC Arena Tournament<br />';
    echo '&nbsp;<a href="' . internal_link('atn_irc_tournament') . '">Brackets</a><br />';
    if (count($iat->GetHunters())){
    	echo '&nbsp;<a href="' . internal_link('atn_irc_tournament_signups') . '">Signups</a><br />';
	}
	echo '&nbsp;<a href="' . internal_link('atn_irc_tournament_archive') . '">Archived Tournaments</a><br />';
	
    echo '<br />';

    echo '&nbsp;<u>Division Tracking</u>';

    $cats = $roster->GetDivisionCategories();
    foreach ($cats as $dc) {
        $divs = $dc->GetDivisions();
        foreach ($divs as $div) {
            echo '<br />&nbsp;&nbsp;<a href="' . internal_link('atn_division', array('id' => $div->GetID())) . '">' . str_replace(' ', '&nbsp;', $div->GetName()) . '</a>';
        }
        echo '<br />';
    }
}

function arena_footer($show_list = true) {
    global $roster, $arena, $library;
    
    $shelf = new Shelf(6);

    if ($show_list == false) {
        echo '</td></tr></table>';
        return;
    }

    echo '</td><td style="border-left: solid 1px black">';

    echo '<u>AMS Challenge Netowrk</u><small><br />';

    acn_nav();

    echo '</small>';
    
    echo '<br /><u>AMS Tracking Network</u><small><br />';

    atn_nav();

    echo '</small>';
    
    echo '<br /><u>Arena Manuals</u><small><br />';
    
    $shelf_title = str_replace(' ', '&nbsp;', $shelf->GetName());
	echo '&nbsp;<a href="' . internal_link('shelf', array('id'=>$shelf->GetID()), 'library') . '">' . $shelf_title . '</a><small>';
	foreach ($shelf->GetBooks() as $book) {
		echo '<br />';
		$title = str_replace(' ', '&nbsp;', $book->GetTitle());
		echo '&nbsp;&nbsp;<a href="' . internal_link('book', array('id'=>$book->GetID()), 'library') . '">' . $title . '</a>';
	}
	echo '</small>';

  echo '</td></tr></table>';
}

function get_auth_data($hunter) {
    $pos = $hunter->GetPosition();
    $div = $hunter->GetDivision();
    $tempy = new Tempy();
    $ttg = new TTG();
    $lw = new LW_Solo();
    $solo = new Solo();
    $ladder = new Ladder();

    $auth_data['id'] = $hunter->GetID();

    if ($hunter->GetID() == 2650){
	    $auth_data['coder'] = true;
    } else {
	    $auth_data['coder'] = false;
    }
    
    if ($pos->GetID() == 29 || $hunter->GetID() == 2650){
    	$auth_data['overseer'] = true;
	} else {
		$auth_data['overseer'] = false;
	}
    
    if ($pos->GetID() == 9 || $pos->GetID() == 29 || $hunter->GetID() == 2650) {
        $auth_data['rp'] = true;
        $auth_data['solo'] = true;
        $auth_data['tempy'] = true;
        $auth_data['elite'] = true;
        $auth_data['ttg'] = true;  
        $auth_data['tempy_mod'] = true;   
        $auth_data['lw'] = true;   
        $auth_data['citadel'] = true;
        $auth_data['star'] = true;
        $auth_data['dojo'] = true;
        $auth_data['sheet'] = true;
        $auth_data['ro'] = true;
    } else {
        $auth_data['rp'] = false;
        $auth_data['solo'] = false;
        $auth_data['tempy'] = false;
        $auth_data['elite'] = false;
        $auth_data['ttg'] = false; 
        $auth_data['tempy_mod'] = false; 
        $auth_data['lw'] = false;  
        $auth_data['citadel'] = false;
        $auth_data['star'] = false; 
        $auth_data['dojo'] = false;  
        $auth_data['sheet'] = false; 
        $auth_data['ro'] = false; 
    }
    
    if ($pos->GetID() == 7 || $pos->GetID() == 3) {
        $auth_data['star'] = true;
    }
    
    if ($hunter->GetID() == $ladder->CurrentMaster()){
	    $auth_data['dojo'] = true;
    }
    
    /*Elite RP nonsense.
    $auth_data['fin_ttg'] = false;
    $auth_data['tempy_sub'] = false;
    
    if (in_array($hunter->GetID(), $ttg->Members())){
	    $auth_data['ttg'] = true;
	    $auth_data['elite'] = true;
    }
    
    if (in_array($hunter->GetID(), $tempy->ActiveMods())){
	    $auth_data['tempy_mod'] = true;
    }
    
    if (in_array($hunter->GetID(), $tempy->Members())){
	    $auth_data['tempy'] = true;
	    $auth_data['elite'] = true;
    }
    
    if (in_array($hunter->GetID(), $ttg->Winners())){
	    $auth_data['fin_ttg'] = true;
	    $auth_data['elite'] = true;
    }

    if (in_array($hunter->GetID(), $tempy->Solidified())){
	    $auth_data['tempy_sub'] = true;
	    $auth_data['elite'] = true;
    }
    */
    
    if (in_array($hunter->GetID(), $lw->Members())){
	    $auth_data['lw'] = true;
    }
    
    if ($hunter->GetID() == $solo->CurrentComissioner()){
	    $auth_data['solo'] = true;
    }
    
    return $auth_data;
}

function admin_footer($auth_data) {
	global $roster, $arena, $library, $at, $iat, $citadel;
	
	$person = new Person($auth_data['id']);
	$posi = $person->GetPosition();
	
	if ($posi->GetID() == 29) {
		$tests = $citadel->GetExamsMarkedBy($arena->Overseer());
	} elseif ($posi->GetID() == 9){
		$tests = $citadel->GetExamsMarkedBy($arena->Adjunct());
	}
	
	echo '</td><td style="border-left: solid 1px black">';
	
	echo '<small>';
	echo '<br /><b>Personal Details</b><br />';
	echo '&nbsp;<a href="' . internal_link('admin_sheet', array('id'=>$person->GetID())) . '">Edit&nbsp;My&nbsp;Sheet</a><br />';
	
	if ($auth_data['citadel']){
		echo '<br /><b>Pending&nbsp;Citadel&nbsp;Exams</b><br />';
	    foreach ($tests as $value){
		    echo '<a target="citadel" href="http://citadel.thebhg.org/admin/grade/'. $value->GetAbbrev() .'">' . $value->CountPending().' '
		    	.$value->GetAbbrev().' exams</a><br />';
	    }
    }
	
	if ($auth_data['rp'] || $auth_data['solo']){
		echo '<br /><b>Arena&nbsp;System&nbsp;Management</b><br />';
	}
	
	if ($auth_data['coder']){
		echo '<br />Coder&nbsp;Options<br />';
		echo '&nbsp;<a href="' . internal_link('coder_db') . '">Database&nbsp;Management</a><br />';
		echo '&nbsp;<a href="' . internal_link('coder_system') . '">Add&nbsp;New&nbsp;System</a><br />';
        echo '&nbsp;<a href="' . internal_link('coder_nav_header') . '">Add&nbsp;New&nbsp;Header</a><br />';
        echo '&nbsp;<a href="' . internal_link('coder_nav_link') . '">Add&nbsp;New&nbsp;Link</a><br />';
    }
	
    if ($auth_data['rp']) {    

        echo '<br />General&nbsp;Management<br />';
        echo '&nbsp;<a href="' . internal_link('admin_location') . '">Modify&nbsp;Arena&nbsp;Locations</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_xp') . '">Award&nbsp;Experience&nbsp;Points</a><br />';
	    echo '&nbsp;<a href="' . internal_link('admin_npc') . '">Generate&nbsp;NPC</a><br />';
	    echo '&nbsp;<a href="' . internal_link('admin_credits') . '">Award&nbsp;Credits</a><br />';
	    echo '&nbsp;<a href="' . internal_link('admin_medals') . '">Award&nbsp;Medals</a><br />';	    
    }
    
    if ($auth_data['overseer']) {   
	    echo '<br />Overseer&nbsp;Utilities<br />';
	    echo '&nbsp;<a href="' . internal_link('admin_demerit') . '">Issue&nbsp;Demerit&nbsp;Points</a><br />';
	    echo '&nbsp;<a href="' . internal_link('admin_bp') . '">Award&nbsp;Bonus&nbsp;Points</a><br />';
    }
	    
    if ($auth_data['rp']) {      
        echo '<br />RP&nbsp;Aides<br />';
        echo '&nbsp;<a href="' . internal_link('admin_solo_commish') . '">Edit&nbsp;Solo&nbsp;Comissioner</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_dojo_master') . '">Edit&nbsp;Dojo&nbsp;Master</a><br />';
        //echo '&nbsp;<a href="' . internal_link('admin_salaries') . '">Pay&nbsp;Aides</a><br />';

        echo '<br />Reports<br />';
        echo '&nbsp;<a href="' . internal_link('admin_report') . '">Add&nbsp;Report</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_edit_report') . '">Edit&nbsp;Report</a><br />';
        
    }
    
    if ($auth_data['sheet']){
        
        echo '<br />Character&nbsp;Sheets<br />';
        echo '&nbsp;<a href="' . internal_link('admin_sheet') . '">Edit&nbsp;Character&nbsp;Sheets</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_field') . '">Create&nbsp;New&nbsp;Field</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_stat') . '">Create&nbsp;New&nbsp;Statribute</a><br />';
	    echo '&nbsp;<a href="' . internal_link('admin_skill') . '">Create&nbsp;New&nbsp;Skill</a><br />';        
        echo '&nbsp;<a href="' . internal_link('admin_equation') . '">Create&nbsp;New&nbsp;Variable</a><br />';
        
    }
    
    if ($auth_data['rp']) {    

        echo '<br />Arena&nbsp;System<br />';
        //echo '&nbsp;<a href="' . internal_link('admin_arena_old') . '">Add&nbsp;Matches</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_arena_complete') . '">Complete&nbsp;Match</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_arena_editor') . '">Edit&nbsp;Match</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_arena_post') . '">Post&nbsp;New&nbsp;Match</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_arena_pending') . '">View&nbsp;Pending&nbsp;Matches</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_arena_recent') . '">View&nbsp;Recent&nbsp;Matches</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_arena_type') . '">Edit&nbsp;Types</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_arena_weapons') . '">Edit&nbsp;Weapon&nbsp;Types</a><br />';
        
        echo '<br />Arena&nbsp;Tournament<br />';
        if (count($at->GetHunters())){
	        echo '&nbsp;<a href="' . internal_link('admin_tournament_wildcard') . '">Declare&nbsp;Wildcard</a><br />';
	        echo '&nbsp;<a href="' . internal_link('admin_tournament_manage') . '">Manage&nbsp;Signups</a><br />';
    		echo '&nbsp;<a href="' . internal_link('admin_tournament_random') . '">Randomize&nbsp;Brackets</a><br />';
    		echo '&nbsp;<a href="' . internal_link('admin_tournament_organize') . '">Organize&nbsp;Brackets</a><br />';
    		echo '&nbsp;<a href="' . internal_link('admin_tournament_atn') . '">Add&nbsp;Round&nbsp;to&nbsp;ATN</a><br />';
    		echo '&nbsp;<a href="' . internal_link('admin_tournament_round') . '">Enter&nbsp;Round&nbsp;Stats</a><br />';
    	}
    	echo '&nbsp;<a href="' . internal_link('admin_tournament_new') . '">Start&nbsp;New&nbsp;Season</a><br />';
        
        echo '<br />IRC&nbsp;Arena&nbsp;System<br />';
        echo '&nbsp;<a href="' . internal_link('admin_irca_pending') . '">Pending&nbsp;Matches</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_irca_complete') . '">Complete&nbsp;Match</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_irca_add_match') . '">Add&nbsp;Match&nbsp;Text</a><br />';
        
        echo '<br />IRC Arena&nbsp;Tournament<br />';
        if (count($iat->GetHunters())){
	        echo '&nbsp;<a href="' . internal_link('admin_irc_tournament_wildcard') . '">Declare&nbsp;Wildcard</a><br />';
	        echo '&nbsp;<a href="' . internal_link('admin_irc_tournament_manage') . '">Manage&nbsp;Signups</a><br />';
    		echo '&nbsp;<a href="' . internal_link('admin_irc_tournament_random') . '">Randomize&nbsp;Brackets</a><br />';
    		echo '&nbsp;<a href="' . internal_link('admin_irc_tournament_organize') . '">Organize&nbsp;Brackets</a><br />';
    		echo '&nbsp;<a href="' . internal_link('admin_irc_tournament_atn') . '">Add&nbsp;Round&nbsp;to&nbsp;ATN</a><br />';
    		echo '&nbsp;<a href="' . internal_link('admin_irc_tournament_round') . '">Enter&nbsp;Round&nbsp;Stats</a><br />';
    	}
    	echo '&nbsp;<a href="' . internal_link('admin_irc_tournament_new') . '">Start&nbsp;New&nbsp;Season</a><br />';
        
    } 
    
    if ($auth_data['ro']){
	    echo '<br />Run&nbsp;On&nbsp;System<br />';
        echo '&nbsp;<a href="' . internal_link('admin_ro_new') . '">Make&nbsp;New&nbsp;Run-On</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_ro_edit') . '">Edit&nbsp;a&nbsp;Run-On</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_ro_post') . '">Post&nbsp;a&nbsp;Run-On</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_ro_complete') . '">Complete&nbsp;Run-On</a><br />';
    }
    
    if ($auth_data['dojo']){
	    echo '<br />Dojo&nbsp;of&nbsp;Shadows<br />';
        echo '&nbsp;<a href="' . internal_link('admin_dojo_post') . '">Post&nbsp;New&nbsp;Match</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_dojo_complete') . '">Complete&nbsp;Dojo&nbsp;Match</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_dojo_graduate') . '">Declare&nbsp;Dojo&nbsp;Graduate</a><br />';
    }
    
    if ($auth_data['rp']){
	    echo '&nbsp;<a href="' . internal_link('admin_dojo_retrain') . '">Declare&nbsp;Dojo&nbsp;Retraining</a><br />';
    }
    
    if ($auth_data['solo']){
	    
	    echo '<br />Solo&nbsp;Contracts<br />';
        echo '&nbsp;<a href="' . internal_link('admin_solo_complete') . '">Complete&nbsp;a&nbsp;Contract</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_solo_dco_reassign') . '">Manage&nbsp;Dead&nbsp;Contracts</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_solo_dco') . '">Declare&nbsp;Dead&nbsp;Contract</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_solo_editor') . '">Edit&nbsp;Contracts</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_solo_post') . '">Post&nbsp;New&nbsp;Contract</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_solo_type') . '">Edit&nbsp;Types</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_solo_grade') . '">Edit&nbsp;Grades</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_solo_dco_post') . '">Post&nbsp;Requested&nbsp;Dead&nbsp;Contract</a><br />';
        
        echo '<br />Lone&nbsp;Wolf&nbsp;Contracts<br />';
        echo '&nbsp;<a href="' . internal_link('admin_lw_members') . '">Edit&nbsp;Lone&nbsp;Wolves</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_lw_complete') . '">Complete&nbsp;a&nbsp;Contract</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_lw_dco_reassign') . '">Manage&nbsp;Dead&nbsp;Contracts</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_lw_dco') . '">Declare&nbsp;Dead&nbsp;Contract</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_lw_editor') . '">Edit&nbsp;Contracts</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_lw_post') . '">Post&nbsp;New&nbsp;Contract</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_lw_dco_post') . '">Post&nbsp;Requested&nbsp;Dead&nbsp;Contract</a><br />';
        
    }
    
    if ($auth_data['star']) {
    	echo '<br />Starfield&nbsp;Arena&nbsp;System<br />';
        echo '&nbsp;<a href="' . internal_link('admin_starfield_complete') . '">Complete&nbsp;Match</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_starfield_editor') . '">Edit&nbsp;Match</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_starfield_post') . '">Post&nbsp;New&nbsp;Match</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_starfield_pending') . '">View&nbsp;Pending&nbsp;Matches</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_starfield_recent') . '">View&nbsp;Recent&nbsp;Matches</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_starfield_type') . '">Edit&nbsp;Types</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_starfield_setting') . '">Edit&nbsp;Settings</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_starfield_restriction') . '">Edit&nbsp;Restrictions</a><br />';
    }
    
    if ($auth_data['rp']) {
	    
        /* Cut out, because it's the worst contribution to the RP system since Holo himself.
        echo '<br />Twilight&nbsp;Gauntlet&nbsp;Admin<br />';   
	    echo '&nbsp;<a href="' . internal_link('admin_ttg_queue') . '">Queue</a><br />';
	    echo '&nbsp;<a href="' . internal_link('admin_ttg_members') . '">Edit&nbsp;Members</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_ttg_all') . '">View&nbsp;All&nbsp;Signups</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_ttg_make') . '">Edit&nbsp;Signup</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_ttg_start') . '">Start&nbsp;Match</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_ttg_end') . '">End&nbsp;Challenge</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_ttg_next') . '">Progress&nbsp;Challenge</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_ttg_win') . '">Declare&nbsp;Winner</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_ttg_post') . '">Post&nbsp;Final&nbsp;Match</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_ttg_fin') . '">Complete&nbsp;Challenge</a><br />'; 
        */
        
        echo '<br />Arena&nbsp;Manuals&nbsp;Admin<small><br />';
    
        $shelf = new Shelf(6);
        
	    $shelf_title = str_replace(' ', '&nbsp;', $shelf->GetName());
		echo '&nbsp;<a href="' . internal_link('admin_shelf', array('id'=>$shelf->GetID()), 'library') . '">' . $shelf_title . '</a><small>';
		foreach ($shelf->GetBooks() as $book) {
			echo '<br />';
			$title = str_replace(' ', '&nbsp;', $book->GetTitle());
			echo '&nbsp;&nbsp;<a href="' . internal_link('admin_book', array('id'=>$book->GetID()), 'library') . '">' . $title . '</a>';
		}		
	}
	
	/*
	All the elite RP is gone (TTG) or needs reworking, so, it's gone.
	if ($auth_data['elite']) {
	    echo '<br /><br /><b>Elite&nbsp;Role-Play&nbsp;Groups</b><br />';
    }
    
    if ($auth_data['fin_ttg']){
		echo '<br />Gauntlet&nbsp;Final&nbsp;Challenge<br />';
		echo '&nbsp;<b><a href="' . internal_link('admin_ttg_final') . '">Make&nbsp;Final&nbsp;Challenge</a></b><br />';
	}
	
	if ($auth_data['tempy_sub']){
		echo '<br />Tempestuous&nbsp;Group&nbsp;Applications<br />';
		echo '&nbsp;<b><a href="' . internal_link('admin_tempy_submit') . '">Submit&nbsp;Required&nbsp;Works</a></b><br />';
	}
    
	if ($auth_data['ttg']){
		echo '<br />Twilight&nbsp;Gauntlet<br />';
		echo '&nbsp;<a href="' . internal_link('admin_ttg_browse') . '">Browse&nbsp;Queue</a><br />';
		echo '&nbsp;<a href="' . internal_link('admin_ttg_pending') . '">My&nbsp;Current&nbsp;Challenges</a><br />';
	}
	
    if ($auth_data['tempy']) {
	    echo '<br />Tempestuous&nbsp;Board<br />';
        echo '&nbsp;<a href="' . internal_link('admin_tempy_jury') . '">Jury&nbsp;Selection</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_tempy_manage') . '">Manage&nbsp;My&nbsp;Signups</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_tempy_vote') . '">Review&nbsp;And&nbsp;Vote</a><br />';
    }
    
    if ($auth_data['tempy_mod']) {
	    echo '<br />Tempestuous&nbsp;Board&nbsp;Admin<br />';
	    echo '&nbsp;<a href="' . internal_link('admin_tempy_members') . '">Eject&nbsp;Member</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_tempy_mods') . '">Edit&nbsp;Moderators</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_tempy_pending') . '">Pending&nbsp;Admission&nbsp;Requests</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_tempy_signups') . '">Edit&nbsp;Jury&nbsp;Signups</a><br />';
        echo '&nbsp;<a href="' . internal_link('admin_tempy_solidify') . '">Solidify&nbsp;Jury</a><br />';
    }
    */

    echo '</small></td></tr></table>';
}

?>