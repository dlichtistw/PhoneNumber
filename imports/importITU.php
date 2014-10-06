<?php
/*
*
* This file is part of the PhoneNumber project and licensed under the terms of the BSD license.
*
* Feel free to use/modify/redistribute this. I would appreciate, if you would give feedback on your usage or even contribute improvements.
*
*/

	if (!empty($argv[1])) {
		if (!empty($argv[2])) {
			if ($src = fopen($argv[1], 'r')) {
				if ($db = new SQLite3($argv[2])) {
					$count = 0;
					$ecount = 0;
					while ($line = fgets($src)) {
						$match = array();
						if (preg_match('/^(\d+).*Area Code\s*(.*)$/', $line, $match)) {
							$count++;
							echo $match[1] . ' -> ' . $match[2];
							if (!$db -> exec('INSERT INTO area (code, country, friendly_name) VALUES (\'' . $match[1] . '\', \'DE\', \'' . SQLite3::escapeString($match[2]) . '\');')) {
								$ecount++;
								echo ': FAIL';
							}
							echo "\n";
						}
					}
					echo 'Inserted ' . ($count - $ecount) . ' out of ' . $count . ' area codes into database.';
					exit(0);
				} else {
					echo 'Could not open database ' . $argv[2] . '.';
					exit(2);
				}
			} else {
				echo 'Could not open file ' . $argv[1] . '.';
				exit(2);
			}
		} else {
			echo 'No database file given.';
			exit(1);
		}
	} else {
		echo 'No data source file given.';
		exit(1);
	}
?>