<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	class PhoneNumberDatabase extends SQLite3 {
		public function __construct ($dbName) {
			parent::__construct($dbName);
		}
		
		public function fetchFirstRow ($query, $mode = SQLITE3_BOTH) {
			$res = $this -> query($query);
			$row = $res -> fetchArray($mode);
			$res -> finalize();
			return $row;
		}
	}
?>