<?php

ini_set('include_path', ini_get('include_path').':/home/virtual/site5/fst/usr/share/roster3/include');

include_once('roster.inc');

$roster = new Roster();

$divisions = $roster->GetDivisions();

$lists = array();

foreach ($divisions as $division) {

  $list = $division->GetMailingList();

  if ($list == 'none@thebhg.org' || $list == 'none') {

    continue;

  }

  if (!is_array($lists[$list])) {
    
    $lists[$list] = array();

  }

  if ($list != 'commission@thebhg.org' && $list != 'commission') {

    $lists[$list][] = 'hcispy@thebhg.org';

  } else {

		$lists[$list][] = 'adam_ashley@softhome.net';

	}

  $people = $division->GetMembers();

  foreach ($people as $person) {

    $lists[$list][] = $person->GetEmail();

  }

}

foreach ($lists as $name => $addresses) {

  $name = str_replace('@thebhg.org',
                      '',
                      $name);

  if (!is_numeric($name)
      && $name > ""
      && $name != 'none') {

    $fp = fopen('/home/virtual/site5/fst/var/lib/majordomo/listtemp/'.$name, 
                'w');

    $output = implode($addresses, "\n");

    fwrite($fp, $output."\n");

    fclose($fp);

  }

}
?>
