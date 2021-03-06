<?php
class KAGSignup {
	var $id;
	var $person;
	var $event;
	var $kag;
	var $kabal;
	var $state;
	var $rank;
	var $points;
	var $submitted;
	var $content;
	var $ip;
	var $db;
	
	function KAGSignup($id, $db) {
		$this->id = $id;
		$this->db = $db;
		$this->UpdateCache();
	}

	function UpdateCache() {
		$result = mysql_query('SELECT person, event, kag, kabal, state, rank, points, ip, submitted, content FROM kag_signups WHERE id=' . $this->id, $this->db);
		if ($result && mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			foreach ($row as $field=>$val) {
				$this->$field = $val;
			}
		}
	}

	function Edit($submitted, $content){
		$sql = "UPDATE `kag_signups` SET `submitted` = '$submitted', `content` = '$content' WHERE id = '".$this->id."'";
		$query = mysql_query($sql, $this->db);
		return ($query ? true : false);
	}
	
	function GetID() {
		return $this->id;
	}
	
	function GetIP(){
		return $this->ip;
	}

	function GetPerson() {
		return new Person($this->person);
	}

	function GetEvent() {
		return new KAGEvent($this->event, $this->db);
	}

	function GetKAG() {
		return new KAG($this->kag, $this->db);
	}

	function GetKabal() {
		return new Kabal($this->kabal);
	}

	function GetState() {
		return $this->state;
	}

	function GetRank() {
		return $this->rank;
	}

	function GetPoints() {
		return $this->points;
	}
	
	function GetContent(){
	    return unserialize(base64_decode($this->content));
    }
    
    function GetSubmitted(){
	    return $this->submitted;
    }

	function GetCredits() {
		$hunter = $this->GetPerson();
		$rank = $hunter->GetRank();
		$fee = new KAGRank($rank->GetID(), $this->db);
		switch ($this->state) {
			case 1: case 4:
				switch ($this->rank) {
					case 1:
						$credits = 200000;
						break;
					case 2:
						$credits = 150000;
						break;
					case 3:
						$credits = 100000;
						break;
					default:
						$credits = 50000;
				}
				break;
			case 2:
				$credits = $fee->GetFee() * -1;
				break;
			default:
				$credits = 0;
		}
		return $credits;
	}

	function SetKabal($kabal) {
		if (is_a($kabal, 'division')) {
			$kabal = $kabal->GetID();
		}
		if (mysql_query('UPDATE kag_signups SET kabal=' . ((int) $kabal) . ' WHERE id=' . $this->id, $this->db)) {
			$this->UpdateCache();
			return true;
		}
		else {
			return false;
		}
	}
	
	function Submit($ip, $answer) {
		if (mysql_query('UPDATE kag_signups SET content="' . $answer . '", submitted = '. time(). ', ip ="'. $ip .'" WHERE id=' . $this->id, $this->db)) {
			$this->UpdateCache();
			return true;
		}
		else {			
			return false;
		}
	}

	function SetState($state) {
		$kag =& $this->GetKAG();
		if ($state == 2) {
			$points = $kag->GetDNP();
		}
		elseif ($state == 3) {
			$points = $kag->GetNoEffort();
		}
		$sql = 'UPDATE kag_signups SET state=' . ((int) $state);
		if (isset($points)) {
			$sql .= ', points=' . $points;
		}
		$sql .= ' WHERE id=' . $this->id;
		
		if (mysql_query($sql, $this->db)) {
			$this->UpdateCache();
			return true;
		}
		else {
			return false;
		}
	}

	function SetRank($rank) {
		$kag =& $this->GetKAG();
		$points = $kag->GetMaximum() - ($rank - 1);
		if ($this->GetState() == 4) {
			$points -= 5;
		} 
		
		if ($points < $kag->GetMinimum()) {
			$points = $kag->GetMinimum();
		}
		
		if ($this->getState() == 2){
			$points = $kag->GetDNP();
		} elseif ($this->getState() == 3){
			$points = $kag->GetNoEffort();
		}
		
		if (mysql_query('UPDATE kag_signups SET rank=' . ((int) $rank) . ', points=' . $points . ' WHERE id=' . $this->id, $this->db)) {
			$this->UpdateCache();
			return true;
		}
		else {
			return false;
		}
	}

	function SetPoints($points) {
		if (mysql_query('UPDATE kag_signups SET points=' . ((int) $points) . ' WHERE id=' . $this->id, $this->db)) {
			$this->UpdateCache();
			return true;
		}
		else {
			return false;
		}
	}

	function DeleteSignup() {
		if (mysql_query('DELETE FROM kag_signups WHERE id=' . $this->id, $this->db)) {
			return true;
		}
		else {
			return false;
		}
	}
}
?>
