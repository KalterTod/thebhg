<?php

class bhg_roster_position extends bhg_core_base {

	// {{{ __construct()

	public function __construct($id) {
		parent::__construct('roster_position', $id);
		$this->__addBooleanFields(array('trainee', 'emailalias'));
		$this->__addDefaultCodePermissions('set', 'god');
	}

	// }}}

}

?>
