<?php

if (isset($_REQUEST['id'])){
	$activity = new Obj('ams_activities', $_REQUEST['id'], 'holonet');
	if (!$activity->Get('name')){
		$activity = false;
	} else {
		$type = new Obj('ams_types', $activity->Get('type'), 'holonet');
	}
}

function title() {
    global $activity;

    $return = 'AMS Tracking Network';

    if (is_object($activity)){
	    $return .= ' :: Activity :: '.$activity->Get('name');
    }
    
    return $return;
}

function output() {
    global $activity, $arena, $type, $page;

    $sheet = new Sheet();
    
    arena_header();

    if (is_object($activity)){
		
	    $at = new Tournament($activity->Get('id'));
	    
			$table = new Table('', true);
	    $table->StartRow();
	    $table->AddHeader('Topic ID');
	    $table->AddHeader('Name');
	    $table->EndRow();
	    
	    if (isset($_REQUEST['nexter'])) {
		    $grtrt = $_REQUEST['last'];
		    $fst = 'id` > \''.$grtrt.'\' AND `';
	    } elseif (isset($_REQUEST['nextdw'])) {
		    $grtrt = $_REQUEST['first'];
		    $fst = 'id` < \''.$grtrt.'\' AND `';
	    } 
	    
	    $pending = $arena->Search(array('table'=>'ams_match', 'search'=>array('type'=>$activity->Get('id'), 'accepted'=>1, 'started` > 0 AND `date_deleted'=>0), 'limit'=>20));
	    $pendings = array();
	    
	    foreach ($pending as $obj){
		    if (!count($pendings)){
			    $first = $obj->Get('id');
		    }
			$pendings[] = $obj;
	
		    foreach ($arena->Search(array('table'=>'ams_records', 'search'=>array('date_deleted'=>'0', 'match'=>$obj->Get('id')))) as $yarm){
					$chal[$obj->Get('id')][$yarm->Get('challenger')] = new Person($yarm->Get('bhg_id'));
		    }
		    $last = $obj->Get('id');
	    }
	    
	    foreach ($pendings as $ja=>$match){
		    $data = unserialize($match->Get('specifics'));
		    $table->StartRow();
		    $table->AddCell(($match->Get('mbid') ? mb_link($match->Get('mbid')) : 'Unposted'));
		    $table->AddCell('<a href="'.internal_link('atn_stats', array('id'=>$match->Get('id'))).'">'.($match->Get('name') ? $match->Get('name') : 'No Name').'</a>');
		    $table->EndRow();
	    }
	    
	    $table->EndTable();
	    
	    $pending = $arena->Search(array('table'=>'ams_match', 'search'=>array('id` > \''.$last.'\' AND `started` > 0 AND `type'=>$activity->Get('id'), 'date_deleted'=>0), 'limit'=>20), 0, 1);
	    $denbo = $arena->Search(array('table'=>'ams_match', 'search'=>array('id` < \''.$first.'\' AND `started` > 0 AND `type'=>$activity->Get('id'), 'date_deleted'=>0), 'limit'=>20), 0, 1);

	    $table->EndTable();

			if ($pending || $denbo) {
				
				$form = new Form($page);
				
				$form->AddHidden('id', $_REQUEST['id']);

				if (isset($_REQUEST['op']))
					$form->AddHidden('op', $_REQUEST['op']);
				
				$form->table->StartRow();
				
				if ($denbo){
					$form->AddHidden('first', $first);
					$form->table->AddCell('<input type="submit" name="nextdw" value="<< Last 20">');
				}
				
				if ($pending){
					$form->AddHidden('last', $last);
					$form->table->AddCell('<input type="submit" name="nexter" value="Next 20 >>">');
				} 
		    $form->table->EndRow();
		    $form->EndForm();

	    }
	    
	    hr();
	    
	    if (count($at->Seasons())){
		    $table = new Table();
	    
		    $table->StartRow();
		    $table->AddHeader('Tournaments', 2);
		    $table->EndRow();
		    
		    foreach ($at->Seasons() as $id){
			    $table->AddRow('<a href="'.internal_link('atn_tourney', array('act'=>$activity->Get('id'), 'season'=>$id)).'">Season '.$id).'</a>';
		    }
		    
		    $table->EndTable();

	    	hr();
    	}
	    
	    $table = new Table();
	    
	    $table->StartRow();
	    $table->AddHeader('Ladder', 2);
	    $table->EndRow();
	    
	    foreach ($arena->Ladder($activity->Get('id')) as $id=>$place){
		    $person = new Person($id);
		    $table->AddRow($place, '<a href="'.internal_link('atn_general', array('id'=>$id)).'">'.$person->GetName()).'</a>';
	    }
	    
	    $table->EndTable();
	    
    }

    arena_footer();
}

?>
