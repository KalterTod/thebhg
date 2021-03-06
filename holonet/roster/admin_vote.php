<?php
function title() {
	global $poll, $roster;

	$sql = 'SELECT * '
	      .'FROM hn_com_poll '
	      .'WHERE id = '.intval($_REQUEST['id']);

	$result = mysql_query($sql, $roster->roster_db);

	if ($result
	 && mysql_num_rows($result)) {
		$poll = mysql_fetch_array($result);
		return 'Administration :: Vote :: '.stripslashes($poll['title']);
	}

	return 'Administration :: Vote';
}

function coders() {
	return array(666);
}

function auth($person) {
	global $auth_data, $pleb, $roster;
	$auth_data = get_auth_data($person);
	$pleb = $roster->GetPerson($person->GetID());
	return $auth_data['commission'];
}

function output() {
	global $auth_data, $pleb, $roster, $page, $poll;

	roster_header();
	$options = unserialize(stripslashes($poll['options']));

	if ($_REQUEST['submit']) {
		$sql = 'REPLACE INTO hn_com_vote '
		      .'(poll, person, vote) '
		      .'VALUES ('.$poll['id'].', '
				 .$pleb->GetID().', '
				 .$_REQUEST['vote']
		             .') ';

		$result = mysql_query($sql, $roster->roster_db);
		if ($result) {
			echo 'Vote saved successfully.'; 
			header('Location: '.str_replace('&amp;', '&', internal_link('admin_votes')));
		}
		else
			echo 'Error saving vote.';
	}
	elseif ($poll['ends'] < time()) {
		echo 'The voting period for that poll has ended.';
	}
	else {
		$sql = 'SELECT vote '
		      .'FROM hn_com_vote '
		      .'WHERE person = '.intval($pleb->GetID())
			.'AND poll = '.$poll['id'];

		$voteResult = mysql_query($sql, $roster->roster_db);
		if ($voteResult
		 && mysql_num_rows($voteResult) > 0) {
			echo 'You have already voted in this poll.';
		}
		else {
			$form = new Form($page);
			$form->AddHidden('id', $poll['id']);
			$form->StartSelect('Vote:', 'vote');
			foreach ($options as $id => $option)
				$form->AddOption($id, $option);
			$form->AddOption(-1, 'Abstain');
			$form->EndSelect();
			$form->AddSubmitButton('submit', 'Save Vote');
			$form->EndForm();
		}
	}

	admin_footer($auth_data);
}
?>
