<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	class PhoneNumberCountry extends PhoneNumberHelper {
		public $code;
		public $country;
		public $hasArea = false;
		public $intlPref;
		public $natPref;
	
		public function __construct ($code) {
			self::ensureInitialized();
			
			if (is_string($code)) {
				if ($row = self::$db -> fetchFirstRow('
					SELECT
						code,
						country,
						has_area,
						intl_pref,
						nat_pref
					FROM
						country_default
					WHERE
						\'' . $code . '\' IN (country, code)
					;
				', SQLITE3_ASSOC)) {
					$this -> code = $row['code'];
					$this -> country = $row['country'];
					if (!empty($row['has_area'])) {
						if ($row['has_area'] == 'YES') {
							$this -> hasArea = true;
						} else {
							$this -> hasArea = $row['has_area'];
						}
					}
					$this -> intlPref = new PhoneNumberPrefix(array(
						'prefix' => $row['intl_pref'],
						'type' => 'intl',
						'context' => $this -> country,
						'is_default' => true
					));
					$this -> natPref = new PhoneNumberPrefix(array(
						'prefix' => $row['nat_pref'],
						'type' => 'nat',
						'context' => $this -> country,
						'is_default' => true
					));
				} else {
					throw new PhoneNumberCountryException('Unknown code or country given: ' . $code . '.');
				}
			} elseif (is_array($code)) {
				if (array_key_exists('code', $code)) {
					$this -> code = $code['code'];
				} else {
					throw new PhoneNumberCountryException('Index \'code\' missing in parameter array: ' . print_r($code, true));
				}
				if (array_key_exists('country', $code)) {
					$this -> country = $code['country'];
				} else {
					throw new PhoneNumberCountryException('Index \'country\' missing in parameter array: ' . print_r($code, true));
				}
				if (!empty($code['has_area'])) {
					if ($code['has_area'] == 'YES' || $code['has_area'] === true) {
						$this -> hasArea = true;
					} else {
						$this -> hasArea = $code['has_area'];
					}
				}
				if (array_key_exists('intl_pref', $code)) {
					if (is_string($code['intl_pref'])) {
						$this -> intlPref = new PhoneNumberPrefix(array(
							'prefix' => $code['intl_pref'],
							'type' => 'intl',
							'context' => $this -> country,
							'is_default' => true
						));
					} else {
						$this -> intlPref = $code['intl_pref'];
					}
				}
				if (array_key_exists('nat_pref', $code)) {
					if (is_string($code['nat_pref'])) {
						$this -> natPref = new PhoneNumberPrefix(array(
							'prefix' => $code['nat_pref'],
							'type' => 'nat',
							'context' => $this -> country,
							'is_default' => true
						));
					} else {
						$this -> intlPref = $code['nat_pref'];
					}
				}
			} else {
				throw new PhoneNumberPrefixException('Bad instantiation parameter given: ' . print_r($code, true));
			}
		}
		
		public function __toString () {
			return $this -> code;
		}
	}
?>