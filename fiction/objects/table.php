<?php
class Table {
	var $alternate;
	var $highlight;
	
	function Table($alternate = true) {
		$this->alternate = $alternate;
		$this->highlight = false;
		echo '<table border=0 cellspacing=1 cellpadding=2 style="background: black;">' . "\n";
	}

	function EndTable() {
		echo '</table>' . "\n";
	}

	function StartRow() {
		if ($this->alternate) {
			if ($this->highlight) {
				$this->highlight = false;
			}
			else {
				$this->highlight = true;
			}
		}
		echo '<tr valign="top">';
	}

	function EndRow() {
		echo '</tr>' . "\n";
	}

	function AddRow() {
		$this->StartRow();
		$args = func_get_args();
		foreach ($args as $cell) {
			$this->AddCell($cell);
		}
		$this->EndRow();
	}

	function AddCell($cell, $colspan = 1, $rowspan = 1, $width = -1, $background = false) {
		echo '<td style="font-size: 12px; color: black; background: ';
		if ($background) {
			echo $background;
		}
		elseif ($this->highlight) {
			echo '#888888';
		}
		else {
			echo '#999999';
		}
		echo '" colspan=' . $colspan . ' rowspan=' . $rowspan . ($width != -1 ? ' width="' . $width . '%"' : '') . '>' . $cell . '</td>' . "\n";
	}

	function AddHeader($cell, $colspan = 1, $rowspan = 1, $width = -1) {
		echo '<th colspan=' . $colspan . ' rowspan=' . $rowspan . ($width != -1 ? ' width="' . $width . '%"' : '') . '>' . $cell . '</th>' . "\n";
	}
}
?>
