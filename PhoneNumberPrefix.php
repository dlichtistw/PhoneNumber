<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	class PhoneNumberPrefix extends PhoneNumberHelper {
		public $prefix;
		public $type;
		public $context;
		public $isDefault = false;
		
		public static function getDefaultPrefix ($context = self::DEFAULT_COUNTRY, $type = 'nat') {
			self::ensureInitialized();
			
			if ($row = self::$db -> fetchFirstRow('SELECT prefix, type, context, is_default FROM prefix WHERE is_default = 1 AND type = \'' . $type . '\' AND context LIKE \'%' . $context . '%\';', SQLITE3_ASSOC)) {
				return new self($row);
			} else {
				return false;
			}
		}
		
		public function __construct ($id) {
			self::ensureInitialized();
			
			if (is_numeric($id)) {
				if ($row = self::$db -> fetchFirstRow('SELECT prefix, type, context, is_default FROM prefix WHERE id = ' . $id . ';', SQLITE3_ASSOC)) {
					$this -> prefix = $row['prefix'];
					$this -> type = $row['type'];
					$this -> context = $row['context'];
					$this -> isDefault = (bool) $row['is_default'];
				} else {
					throw new PhoneNumberPrefixException('The database does not contain any prefix with id ' . $id);
				}
			} elseif (is_array($id)) {
				if (array_key_exists('prefix', $id)) {
					$this -> prefix = $id['prefix'];
				} else {
					throw new PhoneNumberPrefixException('Index \'prefix\' missing in parameter array: ' . print_r($id, true));
				}
				if (array_key_exists('type', $id)) {
					$this -> type = $id['type'];
				} else {
					throw new PhoneNumberPrefixException('Index \'type\' missing in parameter array: ' . print_r($id, true));
				}
				if (array_key_exists('context', $id)) {
					$this -> context = $id['context'];
				} else {
					throw new PhoneNumberPrefixException('Index \'context\' missing in parameter array: ' . print_r($id, true));
				}
				if (!empty($id['is_default'])) {
					$this -> isDefault = $id['is_default'];
				}
			} else {
				throw new PhoneNumberPrefixException('Bad instantiation parameter given: ' . print_r($id, true));
			}
		}
		
		public function __toString () {
			return (string) $this -> prefix;
		}
	}
?>