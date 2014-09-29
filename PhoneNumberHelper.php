<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	abstract class PhoneNumberHelper {
		const DEFAULT_COUNTRY = 'FR';
		protected static $initialized = false;
		public static $db;
		
		// For this class to work, we need a database with phone code information
		public static function ensureInitialized() {
			if (!self::$initialized) {
				self::initialize();
			}
		}
		public static function initialize ($dbName = 'data.sqlite') {
			if (file_exists($dbName)) {
				self::$db = new PhoneNumberDatabase($dbName);
			} else {
				throw new PhoneNumberDatabaseException('Could not open database at ' . $dbName . ': File does not exist.');
			}
			
			self::$initialized = true;
		}
	}
?>