<?php
ini_set('display_errors', 1);
class Justify {
	const N = 80;

	protected $_paragraphEndSymbols = array('\.', '!', '?');
	/**
	 * @var string
	 */
	protected $_inputFileName;
	/**
	 * @var string
	 */
	protected $_outputFileName;

	protected $_input;
	protected $_output;

	/**
	 * Open input and output files
	 * @param string $inputFileName
	 * @param string $outputFileName
	 */
	public function __construct($inputFileName, $outputFileName) {
		$this->_inputFileName = $inputFileName;
		$this->_outputFileName = $outputFileName;
	}

	public function justify() {
		if (file_exists($this->_inputFileName)) {
			$file = fopen($this->_inputFileName, 'r');
			$this->_input = fread($file, filesize($this->_inputFileName));
			fclose($file);
			echo '/([\w\W]*)\.\n/';
			preg_match_all('/([\w\W]*)\.\n/', $this->_input, $matches);
			var_dump($matches);
//			var_dump(explode('.'.PHP_EOL, $this->_input));
		} else {
			die("Could not open input file: {$this->_inputFileName}\n");
		}
	}

	protected function _parse() {

	}

	/**
	 * @param string $inputFileName
	 * @param string $outputFileName
	 */
	protected function _openFiles($inputFileName, $outputFileName) {
		if (file_exists($inputFileName)) {
			$this->_inputFile = fopen($inputFileName, 'r');
			echo "Opened input file: {$inputFileName}\n";
			$this->_outputFile = fopen($outputFileName, 'w');
		} else {
			die("Could not open input file: {$inputFileName}\n");
		}
	}
}

if (!isset($argv[1])) {
	die("Specify input and output files names\n");
} elseif (!isset($argv[2])) {
	die("Specify output file name\n");
} else {
	$j = new Justify($argv[1], $argv[2]);
	$j->justify();


//		$content = fread($inputFile, filesize($inputFileName));
//		var_dump(strpos($content, PHP_EOL));
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
//		var_dump(explode('|', str_replace(".".PHP_EOL, '|', $content)));
		/*
		foreach ($nExploded as $str) {
			if (strlen($str) <= (N / 2)) {
				fwrite($outputFile, "    {$str}\n");
			} else {

			}
		}
		*/


//		fclose($outputFile);
//		fclose($inputFile);
}