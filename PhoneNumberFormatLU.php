<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	class PhoneNumberFormatLU extends PhoneNumberFormatFR {
		protected static $intlFormat = 'ic.. a.. ss../sss..';
		protected static $natFormat = 'na.. ss../sss..';
		
		protected function getIntl ($number) {
			return $this -> context -> intlPref . $number -> country . ' ' . $number -> area . ' ' . static::getSubscrFrag($number);
		}
		protected function getNat ($number) {
			return $this -> context -> natPref . $number -> area . ' ' . static::getSubscrFrag($number);
		}
		
		protected function getSubscrFrag ($number) {
			switch ($number -> area -> type) {
				case 'mob':
					return static::makeTriple($number -> subscr);
				break;
				case 'land':
				default:
					return static::makePairs($number -> subscr);
				break;
			}
		}
		
		protected static function makeTriple ($str) {
			if (strlen($str) < 4) {
				return $str;
			} else {
				return substr($str, 0, 3) . ' ' . static::makeTriple(substr($str, 3));
			}
		}
	}
?>