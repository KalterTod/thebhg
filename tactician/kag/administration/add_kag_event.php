<?php
include_once('header.php');

page_header('Add Event To KAG');

if ($level == 3) {
	if ($_REQUEST['submit']) {
		
		if ($_REQUEST['event']){
			$type = new KAGType($_REQUEST['event'], $db);
			
			$content['questions'] = $_REQUEST['question'];
			$content['answers'] = $_REQUEST['answer'];
				
			$type_id = $type->GetID();
		} else {
			$type_id = 0;
			$content = array();
		}
		
    	$kag =& $ka->GetKAG($_REQUEST['id']);
    		
    	$event = $kag->AddEvent($_REQUEST['name'], parse_date_box('start'), parse_date_box('end'), parse_date_box('wstart'), parse_date_box('wend'), false, $ka->ConditionContent($content), $type_id);

		if ($event) {
			if (is_object($type)){
				if ($type->HasImage()){
					$uploadfile = $uploaddir . $type->GetAbbr(). '-'. $_REQUEST['id'] . '-' . $event->GetID() . '.jpg';
	        		if (!(move_uploaded_file($_FILES['uploadpic']['tmp_name'][1], $uploadfile)) && !(chmod($uploadfile, 0777))){
		        		echo 'Uploaded file is invalid. Follow all rules and try again. If you\'re sure you did, please report this to a Coder.<br />';
	        		}
	    		}
    		}
			echo 'Event added successfully.';
		}
		else {
			echo 'Error adding event.';
		}
	} 
	elseif ($_REQUEST['next']){
		$form = new Form($_SERVER['PHP_SELF'], 'post', '', 'multipart/form-data');
		
		$form->AddHidden('id', $_REQUEST['id']);
		
		$submit = false;
		
		if ($_REQUEST['timed']){
			if ($_REQUEST['event']){
				$form->AddHidden('event', $_REQUEST['event']);
				$submit = true;
				$type = new KAGType($_REQUEST['event'], $db);
				$form->table->AddRow('KAG:', roman($_REQUEST['id']));
				$form->table->AddRow('Event Type', $type->GetName().' ('.$type->GetAbbr().')');
				$form->table->AddRow('Event Description', $type->GetDesc());
				
				if ($type->HasImage()){
					$form->AddHidden('MAX_FILE_SIZE', 300000);
					for ($i = 1; $i <= $type->GetQuestions(); $i++) {
						$form->AddFile('Upload Picture (.jpg and under 300KB)', 'uploadpic['.$i.']');
						$form->AddTextBox('Hunt Answer '.$i.'/'.$type->GetAnswers(), 'answer['.$i.']', '', 70);
					}
				} else {
					if ($type->GetQuestions() == $type->GetAnswers()){
						for ($i = 1; $i <= $type->GetAnswers(); $i++) {
							$form->AddTextBox('Hunt Question '.$i.'/'.$type->GetQuestions(), 'question['.$i.']', '', 70);
					        $form->AddTextBox('Hunt Answer '.$i.'/'.$type->GetAnswers(), 'answer['.$i.']', '', 70);
				        }
			        } else {
				        for ($i = 1; $i <= $type->GetQuestions(); $i++) {
							$form->AddTextBox('Hunt Question '.$i.'/'.$type->GetQuestions(), 'question['.$i.']', '', 70);
				        }
				        for ($i = 1; $i <= $type->GetAnswers(); $i++) {
					        $form->AddTextBox('Hunt Answer '.$i.'/'.$type->GetAnswers(), 'answer['.$i.']', '', 70);
				        }
			        }
		        }					
			} else {
				$form->AddHidden('timed', 1);
				$form->StartSelect('Event Type', 'event');
				foreach ($ka->GetTypes() as $type){
					$form->AddOption($type->GetID(), $type->GetName().' ('.$type->GetAbbr().')');
				}
				$form->EndSelect();
				$form->AddSubmitButton('next', 'Enter Event Details');
			}
		} else {
			$form->table->AddRow('KAG:', roman($_REQUEST['id']));
			$form->AddTextBox('Name:', 'name');
			$submit = true;
		}
		
		if ($submit){
			$form->AddDateBox('Start Date:', 'start', false, true);
			$form->AddDateBox('End Date:', 'end', false, true);
			if ($_REQUEST['timed']){
				$form->AddDateBox('Window Start Date:', 'wstart', false, true);
				$form->AddDateBox('Window End Date:', 'wend', false, true);
			}
			$form->AddSubmitButton('submit', 'Add Event');
		}
			$form->EndForm();
	}
	else {
		$form = new Form($_SERVER['PHP_SELF']);
		$form->StartSelect('KAG:', 'id');
		foreach (array_reverse($ka->GetKAGs()) as $kag) {
			$form->AddOption($kag->GetID(), roman($kag->GetID()));
		}
		$form->EndSelect();
		$form->AddCheckBox('Timed Event?', 'timed', 1);
		$form->AddSubmitButton('next', 'Make Event >>');
		$form->EndForm();
	}
	hr();
	echo '<div><h2>Critical</h2>As of KAG 24, all <b>TIMED</b> events require a "release window". This allows for the events to be released at a static time (You set when it will actually be released), but the system will display a window only of when it will be released. <b>PLEASE NOTE: <i>THE SYSTEM WILL NOT CHECK TO ENSURE YOUR RELEASE TIME IS ACTUALLY IN YOUR WINDOW</i></b>. You get paid a salary, so if you muck it all up, it\'s on your head, not the code. I just want that known.</div>';
	
}
else {
	echo 'You are not authorised to access this page.';
}

page_footer();
?>
