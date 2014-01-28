<?php
ini_set('display_errors', 1);
class Justify {
	const N = 80;

	protected $_paragraphEndSymbols = array('.', '!', '?');
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
					$this->_output[] = "    {$str}";
				} else {
					$isInParagraph = TRUE;
					// $this->_output[] = $str;
					$tmp .= $str;
					if (in_array($str[strlen($str) - 1], $this->_paragraphEndSymbols)) {
						// $this->_output[] = "\n";
						// $tmp .= "\n";
						$tmp = '    ' . $tmp;
						$offset = 4;
						// var_dump(static::N);
						// var_dump(strpos($tmp, ' ', $offset));
						// while ($offset < strlen($tmp)) {
							$div = 0;
							while ($t = strpos($tmp, ' ', $offset)) {
								$offset = $t;
								// var_dump($offset / static::N);
								if (intval($offset / static::N) > $div) {
									$tmp[strrpos(substr($tmp, 0, $offset), ' ')] = "\n";
									// $tmp[$offset] = "\n";
									$div++;
								}
								$offset++;

								echo "{$offset}\n";
							}
							// var_dump(substr($tmp, 0, $offset));
							$tmp[strrpos(substr($tmp, 0, $offset), ' ')] = "\n";
							var_dump($tmp);

							// echo strpos($tmp, ' ', $offset);
							// var_dump($t);
							// var_dump(substr($tmp, 0, $offset));
							exit;
							// $offset++;
							// $this->_output[] = $tmp;
							// $isInParagraph = FALSE;
							// $tmp = '';

						// }
					}
				}
				// echo strlen($str) . "\n";
				
			}
			var_dump($this->_output);
			// var_dump($this->_output);
			// echo '/([\w\W]*)\.\n/';
			// preg_match_all('/([\w\W]*)\.\n/', $this->_input, $matches);
			// var_dump($matches);
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