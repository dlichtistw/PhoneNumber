<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	class PhoneNumberFormat extends PhoneNumberHelper {
		const USE_PLUS = true;
		
		public $context;
		
		protected static $intlFormat = 'ic.. a.. s..';
		protected static $natFormat = 'na.. s..';
		
		public static function getFormat ($type = 'intl') {
			switch ($type) {
				case 'intl':
					return static::$intlFormat;
				break;
				case 'nat':
					return static::$natFormat;
				break;
				case 'both':
				case 'all':
					return static::$intlFormat . ';' . static::$natFormat;
				break;
				default:
					throw new PhoneNumberFormatException('Trying to get unknown format type ' . $type);
				break;
			}
		}
		
		protected static function makeFixed ($str, $form) {
			$len = strlen($form);
			$r = '';
			$j = 0;
			for ($i = 0; $i < $len; $i++) {
				if (is_numeric($form[$i])) {
					$r .= substr($str, $j, $form[$i]);
					$j += $form[$i];
				} else {
					$r .= $form[$i];
				}
			}
			return $r;
		}

		public function __construct ($context = self::DEFAULT_COUNTRY) {
			self::ensureInitialized();
			
			if (is_string($context)) {
				$this -> context = new PhoneNumberCountry($context);
			} else {
				$this -> context = $context;
			}
			
			if (self::USE_PLUS) {
				$this -> context -> intlPref = new PhoneNumberPrefix(0);
			}
		}
		
		protected function prefReplace ($str) {
			return str_replace(array('i', 'n'), array($this -> context -> intlPref -> prefix, $this -> context -> natPref -> prefix), $str);
		}
		protected function getIntl ($number) {
			return $this -> context -> intlPref . $number -> country . ' ' . $number -> area . ' ' . $number -> subscr;
		}
		protected function getNat ($number) {
			return $this -> context -> natPref . $number -> area . ' ' . $number -> subscr;
		}
		
		public function intlFormat ($number = null) {
			if (empty($number)) {
				return $this -> prefReplace(static::getFormat('intl'));
			} else {
				if (is_string($number)) {
					$number = new PhoneNumber($number);
				}
				
				return $this -> getIntl($number);
			}
		}
		public function natFormat ($number = null, $force = false) {
			if (empty($number)) {
				return $this -> prefReplace(static::getFormat('nat'));
			} else {
				if (is_string($number)) {
					$number = new PhoneNumber($number);
				}
				
				if ($this -> context -> country == $number -> country -> country || $force) {
					return $this -> getNat($number);
				} else {
					throw new PhoneNumberFormatException('Trying to get national format (' . $context -> country . ') for foreign number (' . $number -> country -> country . ').');
				}
			}
		}
		public function contFormat ($number, $context = null) {
			if (empty($context)) {
				$context = $this -> context;
			}
			if (is_string($number)) {
				$number = new PhoneNumber($number, $context -> country);
			}
			
			if ($this -> context -> country == $number -> country -> country) {
				return $this -> natFormat($number);
			} else {
				return $this -> intlFormat($number);
			}
		}
	}
?>