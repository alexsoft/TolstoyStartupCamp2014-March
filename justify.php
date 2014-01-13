<?php
ini_set('display_errors', 1);

if (!isset($argv[1])) {
	die("Specify input and output files names\n");
} elseif (!isset($argv[2])) {
	die("Specify output file name\n");
} else {
	define('N', 80);

	$inputFileName = $argv[1];
	$outputFileName = $argv[2];

	if (file_exists($inputFileName)) {

		$inputFile = fopen($inputFileName, 'r');
		echo "Opened input file: {$inputFileName}\n";
		$outputFile = fopen($outputFileName, 'w');
		echo "Opened output file: {$outputFileName}\n";

		$content = fread($inputFile, filesize($inputFileName));
		var_dump(strpos($content, PHP_EOL));
		/*
		$nExploded = array_map(
			function($s) {
				return trim($s);
			},
			explode("\n", $content)
		);
		*/
		// var_dump(explode('.' . PHP_EOL, $content));
		// exit;
		var_dump(explode('|', str_replace(".".PHP_EOL, '|', $content)));
		/*
		foreach ($nExploded as $str) {
			if (strlen($str) <= (N / 2)) {
				fwrite($outputFile, "    {$str}\n");
			} else {

			}
		}
		*/




		fclose($outputFile);
		fclose($inputFile);
	} else {
		die("Could not open input file: {$inputFileName}\n");
	}
}