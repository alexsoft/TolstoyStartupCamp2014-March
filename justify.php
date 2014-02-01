<?php
ini_set('display_errors', 1);

/**
 * Class Justify
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
				if (mb_strlen($str) < static::N / 2 && !$isInParagraph) {
					$this->_output[] = $this->_indent . $str;
				} else {
					$isInParagraph = TRUE;
					$tmp .= $str;
					if (in_array($str[mb_strlen($str) - 1], $this->_paragraphEndSymbols)) {

						$str = $this->_processParagraph($tmp);
						$this->_output[] = $str;

						$tmp = '';
						$isInParagraph = FALSE;
					}
				}
			}
			$o = fopen($this->_outputFileName, 'w');
			fwrite($o, implode(PHP_EOL, $this->_output));
			fclose($o);
			var_dump($this->_output);
		} else {
			die("Could not open input file: {$this->_inputFileName}\n");
		}
	}

	protected function _processParagraph($str) {
		$str = $this->_indent . $str;
		$iteration = 0;
		$start = 0;

		while ($iteration < floor(mb_strlen($str) / static::N)) {
			$c = $start + static::N;
			var_dump($c);
			if ($str[$c] === ' ') {
				$spacePos = $c;
			} else {
				$sub = mb_substr($str, $start, static::N);
				$spacePos = $start + mb_strrpos($sub, ' ');
			}
			var_dump($spacePos);
			$str[$spacePos] = PHP_EOL;
			$start = $spacePos + 1;
			$iteration++;
		}
		return $str;
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