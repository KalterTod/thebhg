<?php
if (isset($_REQUEST['id'])){
	$atn = $arena->IRCAMatch($_REQUEST['id']);
}

function title() {
    global $atn;

    $return = 'AMS Tracking Network :: IRC Arena :: Match Archive';
    
    if (is_object($atn)){
	    $return .= ' :: Match ' . $atn->GetMatchID();
    }
    
    return $return;
}

function output() {
    global $atn, $arena;

    arena_header();

    if (is_object($atn)){
    
	    $location = $atn->GetLocation();
	    $type = $atn->GetType();
	    
	    if ($atn->GetDeleted()){
	
	        echo "This match is marked as deleted and thus has no stats.";
	
	    } else {
	
	        echo '<a name="stats"></a>';
	        $table = new Table('', true);
	        $table->StartRow();
	        $table->AddHeader('IRC Arena Match Profile', 2);
	        $table->EndRow();
	        $table->AddRow('ID Number:', $atn->GetID());
	        $table->AddRow('Name:', $atn->GetName());
	        $table->AddRow('Type:', '<a href="' . internal_link('atn_arena_type', array('id'=>$type->GetID())) . '">' . $type->GetName() . '</a>');
	        $table->AddRow('Location:', '<a href="' . internal_link('atn_arena_location', array('id'=>$location->GetID(), 'table'=>$location->GetTable())) . '">' . $location->GetName() . '</a>');
	        $table->AddRow('Date:', $atn->GetMatchDate());
	        $table->AddRow('Weapons Used:', $atn->GetWeapons());
	        $table->AddRow('Total Actions:', $atn->GetActions());
	
	        $table->EndTable();
	
	        hr();
	        
	        $table = new Table();
	     	$table->StartRow();
	     	$table->AddHeader('Match Text');
	     	$table->EndRow();
	     	
	     	$table->AddRow($atn->GetMatch());
	     	
	     	$table->EndTable();
	
	        if ($atn->GetComplete()){
	
	            $fighters = $atn->GetFighters();
	
	            foreach($fighters as $value){
	                hr();
	
	                echo '<a name="outcome"></a>';
	                $table = new Table();
	                $table->StartRow();
	                $table->AddHeader('Match results for <a href="' . internal_link('atn_general', array('id'=>$value->GetID())) . '">' . $value->GetName() . '</a>', 2);
	                $table->EndRow();
	                $table->AddRow('Result:', $value->GetResult());
	                $table->AddRow('Total Credits:', number_format($value->GetCredits()).' Imperial Credits');
	                $table->AddRow('Total Experience Points:', number_format($value->GetXP()));
	                $table->EndTable();
	            }
	            
	            hr();
	            
	            $table = new Table();
	            
	            $table->StartRow();
	            $table->AddHeader('Match Comments');
	            $table->EndRow();
	            
	            $table->AddRow($atn->GetComments());
	            
	            $table->EndTable();
	
	        }
	
	    }
	    
    }

    arena_footer();
}
?>