<?php
function title() {
    return 'Administration :: Tournament :: Add Matches to ATN';
}

function auth($person) {
    global $auth_data, $hunter, $roster;

    $auth_data = get_auth_data($person);
    $hunter = $roster->GetPerson($person->GetID());
    if (in_array($_REQUEST['act'], $auth_data['activities'])){
    	return $auth_data['aide'];
	}
	
	return false;
}

function output() {
    global $arena, $hunter, $roster, $auth_data, $page;

    arena_header();
    
    $at = new Tournament($_REQUEST['act']);

    if (isset($_REQUEST['submit'])) {
    
	    if ($at->AddToATN($_REQUEST['location'], $_REQUEST['rules'], $_REQUEST['posts'], $_REQUEST['num_weapon'], $_REQUEST['type_weapon'])){
	        echo "Matches Added to Arena Tracking Network.";
	    } else {
	        echo 'Error';
	    }
	    
    } else {
    
	    $ladder = new Ladder();

	    $i = 1;
	    $wtypes = $ladder->WeaponTypes();
	    $locations = $ladder->Locations();
	    $types = $ladder->Rules();
	    
    	$form = new Form($page);
	
    	$form->AddSectionTitle('Set Tournament Round Rules');
    	
	    $form->StartSelect('Number of Weapons:', 'num_weapon');
	    while ($i <= 5) {
	        $form->AddOption($i, $i);
	        $i++;
	    }
	    $i = 3;
	    $form->EndSelect();
	    $form->StartSelect('Weapon Type:', 'type_weapon');
	    foreach($wtypes as $value) {
	        $form->AddOption($value->GetID(), $value->GetWeapon());
	    }
	    $form->EndSelect();
	
	    $form->StartSelect('Location:', 'location', $locations[array_rand($locations)]);
	    $form->AddOption(0, 'Random Locations');
	    foreach ($locations as $lid=>$lname) {
	        $form->AddOption($lid, $lname);
	    }
	    $form->EndSelect();
	
	    $form->StartSelect('Rules:', 'rules');
	    foreach($types as $value) {
	        $form->AddOption($value->GetID(), $value->GetName());
	    }
	    $form->EndSelect();
	
	    $form->StartSelect('Number of Posts:', 'posts');
	    while ($i <= 5) {
	        $form->AddOption($i, $i);
	        $i++;
	    }
	    $form->EndSelect();
	
	    $form->AddSubmitButton('submit', 'Enter Round Stats');
	    $form->EndForm();
    
	}
    
    admin_footer($auth_data);

}
?>