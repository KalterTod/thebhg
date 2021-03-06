<?php
function title() {
    return 'Administration :: General :: Pay Arena Aides';
}

function auth($person) {
    global $auth_data, $hunter, $roster;

    $auth_data = get_auth_data($person);
    $hunter = $roster->GetPerson($person->GetID());
    return $auth_data['rp'];
}

function output() {
    global $arena, $auth_data, $hunter, $page, $roster;
    
    arena_header();

    if ($_REQUEST['aides']) {
	    $sql = "INSERT INTO `hn_pending_reasons` VALUES (`reason`, `person`) VALUES ('Arena Aide Salaries', '".$hunter->GetID()."')";
	    mysql_query($sql, $roster->roster_db);
	    $reason = mysql_insert_id($roster->roster_db);
		foreach ($_REQUEST['aides'] as $rid=>$credits) {
			$person = $roster->GetPerson($rid);
			$sql = "INSERT INTO `hn_pending_credits` (`person`, `credits`, `reason`) VALUES ('".$person->GetID()."', '$credits', '$reason')";
			mysql_query($sql, $roster->roster_db);
		}
		echo 'Salaries paid';
	}
	elseif ($_REQUEST['submit']) {
		$startut = parse_date_box('start');
		$endut = parse_date_box('end');
	    $aides = array();
		
		$pp = $endut - $startut;
		$adj = $pp/(60*60*24*30);
		
	    foreach ($arena->GetPayData($startut, $endut) as $pay){
		    if ($pay['end_date'] == 0 && $pay['start_date'] > $startut){
			    $time = $endut - $pay['start_date'];
		    } elseif ($pay['end_date'] == 0 && $pay['start_date'] < $startut) {
			    $time = $endut - $startut;
		    } else {
			    if ($pay['start_date'] < $startut){
				    $time = $pay['end_date'] - $startut;
			    } else {
			    	$time = $pay['end_date'] - $pay['start_date'];
		    	}
	    	}
	    	
	    	$time = $time / $pp;
	    	
	    	$creds = round($time * $pay['month_pay'] * $adj);
	    	
	      	if (isset($aides[$pay['bhg_id']])) {
	        	$aides[$pay['bhg_id']] += $creds;
	      	} else {
	        	$aides[$pay['bhg_id']] = $creds;
	      	}
		}

		$form = new Form($page);
		$form->AddSectionTitle('Arena Aide Salaries');
			foreach ($aides as $rid=>$credits) {
				$hunter = $roster->GetPerson($rid);
				$form->AddTextBox($hunter->GetName(), "aides[$rid]", $credits);
			}
		$form->AddSubmitButton('submit', 'Pay Salaries');
		$form->EndForm();
	}
	else {
		$last_month_start = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));
		$last_month_end = mktime(0, 0, 0, date('m'), 0, date('Y'));
		
		$form = new Form($page);
		$form->AddDateBox('Start Date:', 'start', $last_month_start);
		$form->AddDateBox('End Date:', 'end', $last_month_end);
		$form->AddSubmitButton('submit', 'Calculate Salaries');
		$form->EndForm();
	}

    admin_footer($auth_data);
}
?>