<?php
function title() {
	return 'Administration :: Add New Vote';
}

function auth($person) {
	global $auth_data, $pleb, $roster;
	$auth_data = get_auth_data($person);
	$pleb = $roster->GetPerson($person->GetID());
	return $auth_data['underlord'];
}

function coders() {
	return array(666);
}

function output() {
	global $auth_data, $pleb, $roster, $page;

	roster_header();

	if (isset($_POST['submit'])) {
		$ends = parse_date_box('ends');
		
		$sql = 'INSERT INTO hn_com_poll '
		      .'(title, ends, options) '
		      .'VALUES ("'.addslashes($_POST['title']).'", '
			      .'"'.$ends.'", '
			      .'"'.addslashes(serialize(explode(',', $_POST['options']))).'"'
			     .')';
		
		$result = mysql_query($sql, $roster->roster_db);
		if ($result) {
			echo 'Vote added.';
			header('Location: '.str_replace('&amp;', '&', internal_link('admin_vote_admin')));
		}
		else
			echo 'Error adding vote.';
	}
	else {
		$form = new Form($page);
		$form->AddTextBox('Title:', 'title', '', 40);
		$form->AddDateBox('Ends At:', 'ends', time() + 604800, true);
		$form->AddTextBox('Options (comma-separated):', 'options', 'Yes,No', 40);
		$form->AddSubmitButton('submit', 'Create Vote');
		$form->EndForm();
	}
	
	admin_footer($auth_data);
}
?>
