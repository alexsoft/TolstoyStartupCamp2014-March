<?php
ini_set('display_errors', 1);

/**
 * Class Justify
 * $this->_outputFile = fopen($outputFileName, 'w');
 */
class Justify {
	const N = 80;

	protected $_paragraphEndSymbols = array('.', '!', '?');
	protected $_indent = '    ';
	/**
	 * @var string
	 */
	protected $_inputFileName;
	/**
	 * @var string
	 */
	protected $_outputFileName;

	protected $_input;
	protected $_output = array();

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

			$ar = explode(PHP_EOL, $this->_input);
			$isInParagraph = FALSE;
			$tmp = '';
			foreach ($ar as $str) {
				if (strlen($str) < static::N / 2 && !$isInParagraph) {
					$this->_output[] = $this->_indent . $str;
				} else {
					$isInParagraph = TRUE;
					$tmp .= $str;
					if (in_array($str[strlen($str) - 1], $this->_paragraphEndSymbols)) {
						$this->_output[] = $this->_indent . $tmp;
						$tmp = '';
						$isInParagraph = FALSE;
						/*
						$finishStr = '    ';
						$explodedString = explode(' ', $tmp);
						$divv = 0;
						foreach ($explodedString as $s) {
							$l = strlen($finishStr) + strlen($s);
							if (intval($l / static::N) < $divv) {
								$finishStr .= ' ' . $s;
							} else {
								$finishStr .= PHP_EOL;
								$divv++;
								$isInParagraph = FALSE;
							}
						}
						*/
					}
				}

			}
			 var_dump($this->_output);
		} else {
			die("Could not open input file: {$this->_inputFileName}\n");
		}
	}

	protected function _parse() {
	}
}

if (!isset($argv[1])) {
	die("Specify input and output files names\n");
} elseif (!isset($argv[2])) {
	die("Specify output file name\n");
} else {
	$j = new Justify($argv[1], $argv[2]);
	$j->justify();
}