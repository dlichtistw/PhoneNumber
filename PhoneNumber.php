<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	class PhoneNumber extends PhoneNumberHelper {
		public $country;
		public $area;
		public $subscr;
		
		public static function stripFormat ($number) {
			$badSymbols = array(' ', '-', '/', '(', ')', '.');
			
			return str_replace($badSymbols, '', $number);
		}
		public static function getPrefix ($number, $context = self::DEFAULT_COUNTRY) {
			self::ensureInitialized();
			
			$number = self::stripFormat($number);
			
			if ($row = self::$db -> fetchFirstRow('SELECT prefix, type, context, is_default FROM prefix WHERE \'' . $number . '\' LIKE prefix || \'%\' AND context LIKE \'%' . $context . '%\' ORDER BY length(prefix) DESC;', SQLITE3_ASSOC)) {
				return new PhoneNumberPrefix($row);
			} else {
				return false;
			}
		}
		public static function getCountry ($numberFrag) {
			self::ensureInitialized();
			
			if ($row = self::$db -> fetchFirstRow('SELECT code, country, has_area, intl_pref, nat_pref FROM country_default WHERE \'' . $numberFrag . '\' LIKE code || \'%\';', SQLITE3_ASSOC)) {
				return new PhoneNumberCountry($row);
			} else {
				return false;
			}
		}
		public static function getArea ($numberFrag, $country = self::DEFAULT_COUNTRY) {
			self::ensureInitialized();
			
			if (!is_string($country)) {
				$country = $country -> country;
			}
			
			if ($row = self::$db -> fetchFirstRow('SELECT length(code) AS length, type FROM area WHERE wildcard = 0 AND country = \'' . $country . '\' AND \'' . $numberFrag . '\' LIKE code || \'%\' ORDER BY length(code) DESC, code ASC;')) {
				return new PhoneNumberArea(substr($numberFrag, 0, $row['length']), $country, $row['type']);
			} else {
				throw new PhoneNumberAreaException('No known area code in ' . $numberFrag . ' for country ' . $country);
			}
		}
		
		public function __construct ($number, $context = self::DEFAULT_COUNTRY) {
			self::ensureInitialized();
			
			if (is_string($number)) {
				$number = self::stripFormat($number);

				if ($pref = self::getPrefix($number, $context)) {
					$numberFrag = substr($number, strlen($pref));
					
					switch ($pref -> type) {
						case 'intl':
							if ($cc = self::getCountry($numberFrag)) {
								$this -> country = $cc;
								$numberFrag = substr($numberFrag, strlen($cc));
							} else {
								throw new PhoneNumberException('Unknown country code in ' . $number . ' after prefix ' . $pref . '.');
								break;
							}
						case 'nat':
							if (empty($this -> country)) {
								$this -> country = new PhoneNumberCountry($context);
							}
							
							if ($this -> country -> hasArea) {
								if (is_numeric($this -> country -> hasArea)) {
									$this -> area = substr($numberFrag, 0, $this -> country -> hasArea);
									$this -> subscr = substr($numberFrag, $this -> country -> hasArea);
								} else {
									try {
										$this -> area = self::getArea($numberFrag, $this -> country);
										$this -> subscr = substr($numberFrag, strlen($this -> area));
									} catch (PhoneNumberAreaException $e) {
										$this -> subscr = $numberFrag;
									}
								}
							} else {
								$this -> subscr = $numberFrag;
							}
						break;
						default:
							throw new PhoneNumberException('Unimplemented prefix type ' . $pref -> type . '. Please check your database.');
						break;
					}
				} else {
					throw new PhoneNumberException('No known and valid prefix in ' . $number . ' for context ' . $context);
				}
			} else {
				throw new PhoneNumberExcpetion('No string given in parameter number.');
			}
		}
		
		public function getFormat ($context = self::DEFAULT_COUNTRY) {
			$className = 'PhoneNumberFormat' . $this -> country -> country;
			return new $className($context);
		}
		
		public function __toString () {
			$r = '';
			$r .= '+' . $this -> country;
			if (!empty($this -> area)) {
				$r .= $this -> area;
			}
			$r .= $this -> subscr;
			
			return $r;
		}
	}
?>