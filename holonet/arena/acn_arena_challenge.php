<?php

function title() {
	global $hunter, $arena;
	
	$area = (in_array($hunter->GetID(), $arena->GetApproved()) ? 'Arena' : 'Dojo of Shadows');
    return 'AMS Challenge Network :: '.$area.' :: Make Challenge';
}

function auth($person) {
    global $hunter, $roster, $auth_data;
    
    $auth_data = get_auth_data($person);
    $hunter = $roster->GetPerson($person->GetID());
    $div = $person->GetDivision();
    return ($div->GetID() != 0 && $div->GetID() != 16);
}

function output() {
    global $arena, $hunter, $roster, $page, $auth_data, $citadel;

    arena_header();

    $ladder = new Ladder();
	$ttg = new TTG();
    $sheet = new Sheet();
	
    echo 'Welcome, ' . $hunter->GetName() . '.<br><br>';

    if ($sheet->HasSheet($hunter->GetID())){
    
	    $challenges = $ladder->Pending($hunter->GetID());
	
	    if (count($challenges)) {
	        $table = new Table('Pending Challenges', true);
	        $table->StartRow();
	        $table->AddHeader('Challenger');
	        $table->AddHeader('Match Type');
	        $table->AddHeader('Location');
	        $table->AddHeader('Weapon Type');
	        $table->AddHeader('Num. of Weapons');
	        $table->AddHeader('Posts');
	        /*if (in_array($hunter->GetID(), $ttg->Members())){
		        $table->AddHeader('Gauntlet Match');
	        }*/
	        $table->AddHeader('&nbsp;', 2);
	        $table->EndRow();
	        foreach($challenges as $value) {
	            $type = $value->GetType();
	            $challenger = $value->GetChallenger();
	            $weapon = $value->GetWeaponType();
	            $location = $value->GetLocation();
	            /*$gauntlet = 'No';
	            
	            if ($value->IsTTG()){
		            $gauntlet = 'Yes';
	            }*/
	            
	            $table->StartRow();
	            $table->AddCell('<a href="' . internal_link('hunter', array('id'=>$challenger->GetID()), 'roster') . '">' . $challenger->GetName() . '</a>');
	            $table->AddCell($type->GetName());
	            $table->AddCell($location->GetName());
	            $table->AddCell($weapon->GetWeapon());
	            $table->AddCell($value->GetWeapons());
	            $table->AddCell($value->GetPosts());
	            /*if (in_array($hunter->GetID(), $ttg->Members())){
			        $table->AddCell($gauntlet);
		        }*/
	            $table->AddCell('<a href="' . internal_link('acn_arena_accept', array('id'=>$value->GetID())) . '">Accept</a>');
	            $table->AddCell('<a href="' . internal_link('acn_arena_decline', array('id'=>$value->GetID())) . '">Decline</a>');
	            $table->EndRow();
	        }
	        $table->EndTable();
	    }
	    else {
	        echo 'You have no challenges pending.';
	    }
	
	    hr();
	
	    $i = 1;
	    $wtypes = $ladder->WeaponTypes();
	    $locations = $ladder->Locations();
	    $types = $ladder->Rules();
	    
	    $me = $citadel->GetPersonsResults($hunter, CITADEL_PASSED);
	    $mytest = array();
	    foreach ($me as $test){
		    $exam = $test->GetExam();
		    $mytest[] = $exam->GetID();
	    }
	    
	    if ($_REQUEST['challengee']){
		    $pers = $_REQUEST['challengee'];
	    } else {
		    $pers = 1;
	    }
	    
	    $them = $citadel->GetPersonsResults($pers, CITADEL_PASSED);
	    $themtest = array();
	    foreach ($them as $test){
		    $exam = $test->GetExam();
		    $themtest[] = $exam->GetID();
	    }
	    
	    $exam = $citadel->GetExambyAbbrev('AT');
	    
	    $tests = (in_array($exam->GetID(), $mytest) && in_array($exam->GetID(), $themtest));
	    
	    if ($_REQUEST['submit']){
		    if (in_array($hunter->GetID(), $arena->GetApproved()) && in_array($_REQUEST['challengee'], $arena->GetApproved()) && $tests){
			    $page_to = 'acn_arena_confirm';
			    $dojo = false;
		    } else {
			    $page_to = 'acn_dojo_confirm';
			    $dojo = true;
		    }
	    } else {
		    $page_to = $page;
	    }
	    
	    $form = new Form($page_to, 'post', '', '', 'Challenge Another Hunter');
	    $form->table->StartRow();
	    $form->table->AddHeader('Has another hunter irritated you? Perhaps they\'ve stolen your ship. Or, worse, your lawn gnome. Now you can challenge them to a fight in the Arena!', 2);
	    $form->table->EndRow();
	
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
		    	$plebsheet[$kabal->GetID()][] = $person;
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
	
		if ($_REQUEST['submit']){
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
		
		    if (!$dojo){
			    $form->StartSelect('Location:', 'location', $locations[array_rand($locations)]);
			    foreach ($locations as $lid=>$lname) {
			        $form->AddOption($lid, $lname);
			    }
			    $form->EndSelect();
			
			    $form->StartSelect('Rules:', 'rules');
			    foreach($types as $value) {
			        $form->AddOption($value->GetID(), $value->GetName());
			    }
			    $form->EndSelect();
		    }
		
		    $form->StartSelect('Number of Posts:', 'posts');
		    while ($i <= 5) {
		        $form->AddOption($i, $i);
		        $i++;
		    }
		    $form->EndSelect();
	    }
	
	    $form->AddSubmitButton('submit', 'Challenge');
	    $form->EndForm();
	    
	    hr();
	
	    $table = new Table('Explanation of Rules', true);
	    $table->AddRow('Name', 'Description', 'Damage Allowed');
	    foreach($types as $value) {
	        $table->AddRow($value->GetName(), $value->GetDesc(), $value->GetRules());
	    }
	    $table->EndTable();
	    
    } else {	    
	    echo 'You need a Character Sheet to challenge anyone. <a href="'.internal_link('admin_sheet', array('id'=>$hunter->GetID())).'"><b>Make one now!</b></a>';
    }

    arena_footer();

}
?>
