<?php
include('header.php');
page_header();

function name_sort($a, $b) {
	if ($a->GetName() > $b->GetName()) return 1;
	elseif ($a->GetName() == $b->GetName()) return 0;
	else return -1;
}

echo '<H1>' . $part_cat_title . "</H1><HR NOSHADE SIZE=2>\n";

$store = new Store();
$types = $store->GetPartTypes();
if ($types) {
	echo '<TABLE CELLSPACING=1 CELLPADDING=2><TR><TH>Name</TH><TH>' . ucwords($mod_plural) . '</TH><TH>&nbsp;</TH></TR>';
	usort($types, name_sort);
	for ($i = 0; $i < count($types); $i++) {
		$items = $types[$i]->GetParts();
		if ($items) $num = count($items);
		else $num = 0;
		echo '<TR><TD><A HREF="parts.php?id='.$types[$i]->GetID().'">'.$types[$i]->GetName()."</A></TD><TD>$num ".($num != 1 ? $mod_plural : $mod_singular)."</TD><TD WIDTH=\"50%\">&nbsp;</TD></TR>\n";
		echo '<TR><TD COLSPAN=3><SMALL>' . $types[$i]->GetDescription() . "</SMALL></TD></TR>\n";
	}
	echo '</TABLE>';
}

page_footer();
?>
