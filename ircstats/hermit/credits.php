<?php
$admin = true;
$jer_only = true;
if (empty($_POST['month'])) {
	$title = 'Monthly Credits';
	include('header.php');

	echo <<<EOF
<FORM NAME="person" ACTION="$PHP_SELF" METHOD="post">
<TABLE CELLSPACING=1 CELLPADDING=1>
<TR><TH>Month:</TH><TD>
<SELECT NAME="month">
EOF;
	for ($i = 1; $i <= 12; $i++) {
		echo "<OPTION VALUE='$i'" . ($i == date('m', time()) ? ' SELECTED' : '') . '>';
		$date = mktime(0, 0, 0, $i);
		echo date('F', $date);
		echo '</OPTION>';
	}
	$year = date('Y', time());
	echo <<<EOF
</SELECT></TD></TR>
<TR><TH>Year:</TH><TD><INPUT TYPE="text" NAME="year" VALUE="$year" SIZE=5></TD></TR>
<TR><TD COLSPAN=2><INPUT TYPE="checkbox" NAME="add" VALUE="on"> Add credits to Roster</TD></TR>
<TR><TD COLSPAN=2><DIV ALIGN="right"><INPUT TYPE="submit" VALUE="Go!"></DIV></TD></TR>
</TABLE>
</FORM>
EOF;
}
else {
	import_request_variables('p');
	
	$title = 'Monthly Credits :: ' . date('F', mktime(0, 0, 0, $month)) . " $year";
	include('header.php');

	$start = mktime(0, 0, 0, $month, 1, $year);
	$end = mktime(23, 59, 59, $month + 1, 0, $year);
	
	$result = mysql_query("SELECT person, SUM(words) AS words FROM irc_stats WHERE date BETWEEN $start AND $end GROUP BY person ORDER BY words DESC", $db);
	if ($result && mysql_num_rows($result)) {
		echo '<TABLE CELLSPACING=1 CELLPADDING=2><TR><TH>Name</TH><TH>Words</TH><TH>Credits</TH></TR>';
		while ($row = mysql_fetch_array($result)) {
			$pleb = $roster->GetPerson($row['person']);
			$day_words = $row['words'];
			echo '<TR><TD><A HREF="http://holonet.thebhg.org/index.php?module=roster&amp;page=hunter&amp;id=' . $row['person'] . '">' . $pleb->GetName() . '</A></TD><TD><DIV ALIGN="right">' . ($day_words ? number_format($day_words) : '0') . '</DIV></TD><TD><DIV ALIGN="right">' . number_format(calculate_credits($day_words)) . '</DIV></TD></TR>';
			if ($add == 'on') {
				$pleb->AddCredits(calculate_credits($day_words), 'IRC Credits');
			}
		}
		echo '</TABLE>';
	}
	else echo 'No matching records found.';
}

include('footer.php');
?>
