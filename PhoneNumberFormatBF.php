<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	class PhoneNumberFormatBF extends PhoneNumberFormatFR {
		protected static $intlFormat = 'ic.. aa.. ss..';
		protected static $natFormat = 'naa.. ss..';
		
		public function getIntl ($number) {
			return $this -> context -> intlPref . $number -> country . ' ' . static::makePairs($number -> area) . ' ' . static::makePairs($number -> subscr);
		}
		public function getNat ($number) {
			return $this -> context -> natPref . static::makePairs($number -> area) . ' ' . static::makePairs($number -> subscr);
		}
	}
?>
