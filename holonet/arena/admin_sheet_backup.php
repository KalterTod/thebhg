<?php

function title() {
	
	$return = '';
	
	if (isset($_REQUEST['id'])){
		$person = new Person($_REQUEST['id']);
    	$return .= $person->GetName().'\'s ';
	} 
	
	$return .= 'Character Sheet :: Backup Utility';
	
	return $return;
}

function auth($person) {
    global $auth_data, $hunter, $roster;

    $auth_data = get_auth_data($person);
    $hunter = $roster->GetPerson($person->GetID());
    return true;
}

function output() {
    global $auth_data, $hunter, $page, $roster, $sheet;
    
    arena_header();

    $character = new Character($hunter->GetID());
    
    $values = array();
    $show = true;
    
    if (isset($_REQUEST['submit'])){
	    echo $character->Backup($_REQUEST['save'], $_REQUEST['sheet']);
	    hr();		     
    }
    
    if ($_REQUEST['goload']){
	    echo $character->LoadBackup($_REQUEST['sheet']);
	    hr();
    }
    
    if (isset($_REQUEST['delete'])){
	    if ($character->ValidLoad($_REQUEST['sheet'], 1)){
		    if ($_REQUEST['confirm']){
			    echo $character->DeleteBackup($_REQUEST['sheet']);
			    hr();		
		    } else {
			    $form = new Form($page);
			    $form->AddHidden('sheet', $_REQUEST['sheet']);
			    $form->AddHidden('confirm', 1);
			    echo '<input type="submit" name="delete" value="Confirm Delete">';
			    $form->EndForm();
			    $show = false;
		    }
	    }
    }
    
    if (isset($_REQUEST['delshare'])){
	    if ($character->ValidLoad($_REQUEST['sheet'], 1)){
		    echo $character->RemoveShare($_REQUEST['hunt'], $_REQUEST['sheet']);
		    hr();		
	    }
    }
    
    if (isset($_REQUEST['share'])){
	    if ($character->ValidLoad($_REQUEST['sheet'], 1)){
		    if ($_REQUEST['challengee']){
			    echo $character->Share($_REQUEST['challengee'], $_REQUEST['sheet']);
			    hr();		
		    } else {
			    $form = new Form($page);
			    $form->AddSectionTitle('Choose Hunter');
			    $form->AddHidden('sheet', $_REQUEST['sheet']);
			    $kabals_result = $roster->GetDivisions();
	    
			$kabals = array();
	    
			foreach ($kabals_result as $kabal) {
	      
			      if ($kabal->GetID() != 9 && $kabal->GetID() != 16) {
			        
			        $kabals[$kabal->GetName()] = "<option value=\"".$kabal->GetID()."\">"
			          .$kabal->GetName()."</option>\n";
			      }
	      
	    	}
	    
			$kabals = implode('', $kabals);
			
			$hunters = array();
			$plebsheet = array();
			
			foreach ($sheet->SheetHolders() as $char) {
			     $hunters[$char->GetName()] = new Person($char->GetID());
	    	}
	    	
	    	ksort($hunters);
	    	
	    	foreach ($hunters as $name=>$person){
		    	$kabal = $person->GetDivision();
		    	if ($person->GetID() != $hunter->GetID()){
		    		$plebsheet[$kabal->GetID()][] = $person;
	    		}
	    	}
	
		?>
		<script language="JavaScript1.1" type="text/javascript">
		<!--
		function person(id, name) {
			this.id = id;
			this.name = name;
		}
	
		<?php
	  
			reset($kabals_result);
	    
		  $commindex = 0;
	    
			foreach ($kabals_result as $kabal) {
	      
				if ($kabal->GetID() == 16) {
	        
					continue;
	        
				}
	      
				echo 'roster' . $kabal->GetID() . " = new Array();\n";
	      
				$plebs = $plebsheet[$kabal->GetID()];
	      
		    if (is_array($plebs)) {
	        
		      $plebindex = 0;
	        
	        foreach ($plebs as $pleb) {
	          
	          $div_peeps[$pleb->GetName().':'.$plebindex] = 
	            'roster'
	            .(($kabal->GetID() == 9) 
	              ? '10' 
	              : $kabal->GetID()) 
	            .'['.
	            (($kabal->GetID() == 9 || $kabal->GetID() == 10) 
	              ? $commindex++ 
	              : $plebindex++)
	            .'] = new person('.$pleb->GetID().', \''
	            .str_replace("'", "\\'", shorten_string($pleb->GetName(), 40))
	            ."');\n";
	            
	        }
	        
	        echo implode('', $div_peeps);
	        
	        unset($div_peeps);
	        
		    }
	      
			}
	    
		?>
	
		function swap_kabal(frm) {
			var kabal_list = eval("frm.kabal");
			var person_list = eval("frm.challengee");
			var kabal = kabal_list.options[kabal_list.options.selectedIndex].value;
			if (kabal > 0) {
				var kabal_array = eval("roster" + kabal);
				var new_length = kabal_array.length;
				person_list.options.length = new_length;
				for (i = 0; i < new_length; i++) {
					person_list.options[i] = new Option(kabal_array[i].name, kabal_array[i].id, false, false);
				}
			}
			else {
				person_list.options.length = 1;
				person_list.options[0] = new Option("N/A", -1, false, false);
			}
		}
	
		// -->
		</script>
		<noscript>
		This page requires JavaScript to function properly.
		</noscript>
	<?
	
	if ($_REQUEST['submit']){
		$form->AddHidden('challengee', $_REQUEST['challengee']);
	} else {
	        $form->table->StartRow();
	        $form->table->AddCell("<select name=\"kabal\" "
	        ."onChange=\"swap_kabal(this.form)\">"
	        ."<option value=\"-1\">N/A</option>$kabals</select>");
	
	    $cell = "<select name=\"challengee\">";
	    
				$cell .= "<option value=\"-1\" selected>N/A</option>\n";
	    
			$cell .= "</select>";
	    
			$form->table->AddCell($cell);
	
			$form->table->EndRow();
		}
			    $form->AddSubmitButton('share', 'Share Sheet');
			    $form->EndForm();
			    $show = false;
		    }
	    }
    }
    
    if ($show){
	    if ($_REQUEST['load']){
	    
		    if ($character->ValidLoad($_REQUEST['sheet'])){
		    
			    $form = new Form($page);
			    
			    $form->AddHidden('sheet', $_REQUEST['sheet']);
			    $form->table->StartRow();
			    $form->table->AddHeader('Upload Backup');
			    $form->table->EndRow();
			    $form->table->AddRow('<input type="submit" value="Load Backup as Edit Sheet" name="goload">');
			    
			    $form->EndForm();
			    hr();
			    
			    $character->ParseSheet('backups', $_REQUEST['sheet'], 'id');
		    } else {
			    echo 'This is an invlaid load. You can only load your own Backup sheets.';
		    }
		    
	    } elseif ($_REQUEST['view']){
		    
		    $form = new Form($page);
		    $form->AddSectionTitle('Backup Resource');
		    $form->AddTextBox('Save name:', 'save');
		    $form->AddHidden('sheet', $_REQUEST['sheet']);
		    $form->AddSubmitButton('submit', 'Save Sheet');
		    $form->EndForm();
		    
		    hr();
		    
		    $character->ParseSheet($_REQUEST['sheet']);
	    } else {    
		    if ($character->HasValue('values')){
			    $values['My Approved Sheet'] = 'values';
		    }
		    if ($character->HasValue('pending')){
			    $values['My Editing Sheet'] = 'pending';
		    }
		    
		    if (count($values)){
			    $form = new Form($page);
			    $form->AddSectionTitle('Save a Sheet Backup');
			    $form->StartSelect('Sheet to Save', 'sheet');
			    foreach ($values as $name=>$value){
				    $form->AddOption($value, $name);
			    }
			    $form->EndSelect();
			    $form->AddSubmitButton('view', 'View This Sheet');
			    $form->EndForm();
		    } else {
			    echo 'You have no sheets to backup.';
		    }
		    
		    $saves = $character->GetBackups();
		    
		    if (count($saves)){
			    hr();
			    $table = new Table('', true);
			    $table->StartRow();
			    $table->AddHeader('Sheet Backups', 5);
			    $table->EndRow();
			    
			    $table->AddRow('Save Name', 'Date', '&nbsp', '&nbsp', '&nbsp');
			    
			    foreach ($saves as $data){
				    $table->AddRow($data['name'], $data['date'], 
				    	'<a href="'.internal_link($page, array('load'=>1, 'sheet'=>$data['id'])).'">Load Sheet</a>', 
				    	($data['share'] ? '' : '<a href="'.internal_link($page, array('delete'=>1, 'sheet'=>$data['id'])).'">Delete</a>'), 
				    	($data['share'] ? '' : '<a href="'.internal_link($page, array('share'=>1, 'sheet'=>$data['id'])).'">Share</a>'));
			    }
			    
			    $table->EndTable();
		    }
		    
		    $shares = $character->MyShares();
		    
		    if (count($shares)){
			    hr();
			    $table = new Table('', true);
			    $table->StartRow();
			    $table->AddHeader('Shared Sheets', 5);
			    $table->EndRow();
			    
			    $table->AddRow('Sheet', 'Person', '&nbsp');
			    
			    foreach ($saves as $data){
				    $hunt = new Person($data['hunter']);
				    $table->AddRow($data['name'], '<a href="'.internal_link('atn_general', array('id'=>$data['hunter'])).'">'.$hunt->GetName().'</a>',
				    '<a href="'.internal_link($page, array('delshare'=>1, 'sheet'=>$data['id'], 'hunt'=>$data['hunter'])).'">Delete Share</a>');
			    }
			    
			    $table->EndTable();
		    }
	    }
    }
	
	admin_footer($auth_data);

}
?>