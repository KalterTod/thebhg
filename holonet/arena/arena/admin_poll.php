<?php

function title() {
    return 'Administration :: Arena Poll :: New Poll';
}

function auth($person) {
    global $auth_data, $hunter, $roster;

    $auth_data = get_auth_data($person);
    $hunter = $roster->GetPerson($person->GetID());
    return $auth_data['aa'];
}

function output() {
    global $auth_data, $hunter, $page, $roster, $sheet, $arena;

    arena_header();
    
    $show = true;
    
    if (isset($_REQUEST['submit'])){
	    
	    $arenap = $_REQUEST['arenaposi'];
	    $posi = $_REQUEST['posi'];
	    $divi = $_REQUEST['divi'];
	    $open = $_REQUEST['open'];
	    
	    $open_to = array();
	    
	    if (count($arenap)){
		    $open_to['aas'] = $arenap;
	    }
	    
	    if (count($posi)){
		    $open_to['positions'] = $posi;
	    }
	    
	    if (count($divi)){
		    $open_to['divisions'] = $divi;
	    }
	    
	    if (count($open)){
		    $open_to['aa'] = 1;
	    }
	    
	    $poll = $arena->NewPoll($hunter->GetID(), $_REQUEST['rpa'], $_REQUEST['question'], $_REQUEST['multiple'], $open_to, $_REQUEST['start'], $_REQUEST['end']);
	    
	    if ($poll){
		    $poll = new Poll($poll);
		    echo 'New poll added successfully<br />';
		    $poll->SetOptions($_REQUEST['option']);
	    } else {
		    NEC(208);
	    }
	    
	    hr();
    } elseif ($_REQUEST['next']){
	    $form = new Form($page);
	    $form->AddSectionTitle('Add Options');
	    $form->AddHidden('multiple', $_REQUEST['multiple']);
	    $form->AddHidden('question', $_REQUEST['question']);
	    $form->AddHidden('start', parse_date_box('start'));
	    $form->AddHidden('end', parse_date_box('end'));
	    
	    $form->StartSelect('Post as', 'rpa');
	    foreach ($arena->CanBe($hunter) as $id=>$data){
		    $form->AddOption($id, $data);
	    }
	    $form->EndSelect();
	    
	    for ($i = 1; $i <= $_REQUEST['numop']; $i++){
		    $form->AddTextBox('Option '.$i, 'option[]');
	    }
	    
	    if ($_REQUEST['restrict']){
		    $form->AddCheckBox('RP Aides Only', 'open[aa]', 1);
		    $form->StartSelect('Arena Position', 'arenaposi[]', '', 5, true);
		    foreach ($arena->ArenaPositions() as $id=>$data){
			    $form->AddOption($id, $data['desc']);
		    }
		    $form->EndSelect();
		    
		    $form->StartSelect('Position', 'posi[]', '', 5, true);
		    foreach ($roster->GetPositions() as $data){
			    $form->AddOption($data->GetID(), $data->GetName());
		    }
		    $form->EndSelect();
		    
		    $form->StartSelect('Division', 'divi[]', '', 5, true);
		    foreach ($roster->GetDivisions() as $data){
			    $form->AddOption($data->GetID(), $data->GetName());
		    }
		    $form->EndSelect();
	    }
	    
	    $form->AddSubmitButton('submit', 'Add Poll');
	    $form->EndForm();
	    $show = false;
    }
    
    if ($show){
	    $form = new Form($page);
	    $form->AddSectionTitle('Create new Poll');
	    
	    $form->AddTextBox('Question', 'question');
	    $form->AddTextBox('Number of Options', 'numop', 3, 5);
	    $form->AddCheckBox('Multiple Answers?', 'multiple', 1);
	    $form->AddCheckBox('Restrict to Certain People?', 'restrict', 1);
	    $form->AddDateBox('Starts', 'start', time());
	    $week = time()+(60*60*24*7);
	    $form->AddDateBox('Ends', 'end', $week);
	    $form->AddSubmitButton('next', 'Add Options');
	    
	    $form->EndForm();
    }
    
    admin_footer($auth_data);
}
?>