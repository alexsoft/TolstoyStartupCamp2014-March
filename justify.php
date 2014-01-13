<?php
ini_set('display_errors', 1);

if (!isset($argv[1])) {
	die("Specify input and output files names\n");
} elseif (!isset($argv[2])) {
	die("Specify output file name\n");
} else {
	define('N', 80, FALSE);

	$inputFileName = $argv[1];
	$outputFileName = $argv[2];

	if (file_exists($inputFileName)) {

		$inputFile = fopen($inputFileName, 'r');
		echo "Opened input file: {$inputFileName}\n";

		while(!feof($inputFile)) {
			$str = fgets($inputFile);
			$q = new SplQueue();

			$q->enqueue(2);
			$q->enqueue(3);
			var_dump($q);
			exit;
			if (strlen($str) < (N / 2)) {
				var_dump('HEADER');
			} else {
				var_dump('Simple');
			}
			// var_dump(strlen(fgets($inputFile)));
		}

		$outputFile = fopen($outputFileName, 'w');
		// write to output file here
		fclose($outputFile);

		fclose($inputFile);
	} else {
		die("Could not open input file: {$inputFileName}\n");
	}
}