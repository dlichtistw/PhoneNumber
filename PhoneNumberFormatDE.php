<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	class PhoneNumberFormatDE extends PhoneNumberFormat {
		protected static $intlFormat = 'ic.. a.. ss..';
		protected static $natFormat = 'na.. ss..';
		
		protected function getIntl ($number) {
			return $this -> context -> intlPref . $number -> country . ' ' . $number -> area . ' ' . static::makePairs($number -> subscr);
		}
		protected function getNat ($number) {
			return $this -> context -> natPref . $number -> area . ' ' . static::makePairs($number -> subscr);
		}
		
		protected static function makePairs ($str) {
			if (strlen($str) < 3) {
				return $str;
			} else {
				return static::makePairs(substr($str, 0, -2)) . ' ' . substr($str, -2);
			}
		}
	}
?>