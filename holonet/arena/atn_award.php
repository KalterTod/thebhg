<?php
if (isset($_REQUEST['id'])){
	$hunter = new Person($_REQUEST['id']);
}

function title() {
    global $hunter;

    $return = 'AMS Tracking Network :: Awarding History';
    
    if (is_object($hunter)){
	    $return .= ' :: ' . $hunter->GetName();
    }
    
    return $return;
}

function output() {
    global $arena, $hunter, $sheet, $roster;

    arena_header();

    if (is_object($hunter)){
    
	    $comiss = new Comissioner($hunter->GetID());
	    $master = new Master($hunter->GetID());
	    $regist = new Registrar($hunter->GetID());
	    $missio = new MissionMaster($hunter->GetID());
	    $overse = new Overseer($hunter->GetID());
	    $adjunc = new Adjunct($hunter->GetID());
		
	    if ($overse->GetStatus()){
			
			$table = new Table();
			$table->StartRow();
		    $table->AddCell('image here', 1, 5);
		    $table->EndRow();
		    $table->StartRow();
		    $table->AddHeader('Overseer of the Guild', 2);
		    $table->EndRow();
		    $table->AddRow('Credits Awarded:', number_format($overse->GetCreds()).' Imperial Credits');
		    $table->AddRow('Experience Points Awarded:', number_format($overse->GetXP()));
		    $table->AddRow('Medals Awarded:', number_format($overse->GetMedals()));
		    $table->EndTable();
		    echo '<br />';
		    
		    hr();
	    }
	    
	    if ($adjunc->GetStatus()){
			
			$table = new Table();
		    $table->StartRow();
		    $table->AddHeader('Adjunct of the Guild', 2);
		    $table->EndRow();
		    $table->AddRow('Credits Awarded:', number_format($adjunc->GetCreds()).' Imperial Credits');
		    $table->AddRow('Experience Points Awarded:', number_format($adjunc->GetXP()));
		    $table->AddRow('Medals Awarded:', number_format($adjunc->GetMedals()));
		    $table->EndTable();
		    echo '<br />';
		    
		    hr();
	    }
	    
		if ($comiss->GetStatus()){
			
			$table = new Table();
		    $table->StartRow();
		    $table->AddHeader('Commissioner of the Bounty Office', 2);
		    $table->EndRow();
		    $table->AddRow('Contracts Overseen:', $comiss->GetContracts());
		    $table->AddRow('Credits Awarded:', number_format($comiss->GetCreds()).' Imperial Credits');
		    $table->AddRow('Experience Points Awarded:', number_format($comiss->GetXP()));
		    $table->AddRow('Medals Awarded:', number_format($comiss->GetMedals()));
		    $table->EndTable();
		    echo '<br />';
		    
		    hr();
	    }
	    
	    if ($master->GetStatus()){
			
			$table = new Table();
		    $table->StartRow();
		    $table->AddHeader('Master of the Dojo of Shadows', 2);
		    $table->EndRow();
		    $table->AddRow('Matches Run:', $master->GetMatches());
		    $table->AddRow('Credits Awarded:', number_format($master->GetCreds()).' Imperial Credits');
		    $table->AddRow('Experience Points Awarded:', number_format($master->GetXP()));
		    $table->AddRow('Medals Awarded:', number_format($master->GetMedals()));
		    $table->EndTable();
		    echo '<br />';
		    
		    hr();
	    }
	    
	    if ($regist->GetStatus()){
			
			$table = new Table();
		    $table->StartRow();
		    $table->AddHeader('Office of Character Development Registrar', 2);
		    $table->EndRow();
		    $table->AddRow('Credits Awarded:', number_format($regist->GetCreds()).' Imperial Credits');
		    $table->AddRow('Experience Points Awarded:', number_format($regist->GetXP()));
		    $table->AddRow('Medals Awarded:', number_format($regist->GetMedals()));
		    $table->EndTable();
		    echo '<br />';
		    
		    hr();
	    }
	    
	    if ($missio->GetStatus()){
			
			$table = new Table();
		    $table->StartRow();
		    $table->AddHeader('Mission Master of Run-Ons', 2);
		    $table->EndRow();
		    $table->AddRow('Run-Ons Moderated:', $missio->GetROs());
		    $table->AddRow('Credits Awarded:', number_format($missio->GetCreds()).' Imperial Credits');
		    $table->AddRow('Experience Points Awarded:', number_format($missio->GetXP()));
		    $table->AddRow('Medals Awarded:', number_format($missio->GetMedals()));
		    $table->EndTable();
		    echo '<br />';
		    
		    hr();
	    }
		
	}
    
    arena_footer();
}
?>