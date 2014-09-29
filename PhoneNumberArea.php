<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	class PhoneNumberArea extends PhoneNumberHelper {
		public $code;
		public $country;
		public $type;
		
		public function __construct ($code, $country, $type = null) {
			self::ensureInitialized();
			
			if (empty($code)) {
				throw new PhoneNumberAreaException('Invoking PhoneNumberArea::__construct() using empty $code parameter');
			} else {
				$this -> code = $code;
			}
			if (empty($country)) {
				throw new PhoneNumberAreaException('Invoking PhoneNumberArea::__construct() using empty $country parameter');
			} else {
				$this -> country = $country;
			}
			if (empty($type)) {
				if ($row = self::$db -> fetchFirstRow('SELECT type FROM area WHERE code = \'' . $code . '\' AND country = \'' . $country . '\' LIMIT 1;')) {
					$this -> type = $row['type'];
				} else {
					throw new PhoneNumberAreaException('Invoking PhoneNumberArea::__construct() using unknown $code-$country combination');
				}
			} else {
				$this -> type = $type;
			}
		}
		
		public function __toString () {
			return $this -> code;
		}
	}
?>