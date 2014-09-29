<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	class PhoneNumberFormatNANP extends PhoneNumberFormat {
		protected static $intlFormat = 'ic.. aaa sss-sss';
		protected static $natFormat = 'naaa sss-ssss';
		
		protected function getIntl ($number) {
			return $this -> context -> intlPref . $number -> country . ' ' . $number -> area . ' ' . static::makeFixed($number -> subscr, '3-4');
		}
		protected function getNat ($number) {
			return $this -> context -> natPref . $number -> area . ' ' . static::makeFixed($number -> subscr, '3-4');
		}
	}
?>