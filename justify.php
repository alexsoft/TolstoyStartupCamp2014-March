<?php

/**
 * Для запуска требуется PHP >= 5.3.0
 * (php5-cli для запуска из командной строки)
 * 
 * Запускается следующей командой
 * php justify.php input output
 * где input - имя файла с входными данными,
 * output - имя файла, куда будут записаны выходные данные.
 * 
 * Если файла не существует, он будет создан.
 * При повторном запуске output файл будет перезаписан.
 */

/**
 * Class Justify
 */
class Justify {
	/**
	 * Length of lines
	 */
	const N = 80;

	/**
	 * Punctuation of paragraph end
	 * @var array
	 */
	protected $_paragraphEndSymbols = array('.', '!', '?');

	/**
	 * Indent for paragraphs
	 * @var string
	 */
	protected $_indent = '    ';

	/**
	 * Input file name
	 * @var string
	 */
	protected $_inputFileName;

	/**
	 * Output file name
	 * @var string
	 */
	protected $_outputFileName;

	/**
	 * Input text
	 * @var string
	 */
	protected $_input;

	/**
	 * Array of paragraphs for output file
	 * @var array
	 */
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

	/**
	 * Justifies input and writes to output file
	 * @return bool Was writing to file successfull
	 */
	public function justify() {
		if (file_exists($this->_inputFileName)) {
			$file = fopen($this->_inputFileName, 'r');
			$this->_input = fread($file, filesize($this->_inputFileName));
			fclose($file);

			$strings = explode(PHP_EOL, $this->_input);
			$isInParagraph = FALSE;
			$tmp = '';
			foreach ($strings as $str) {
				// If length of line is less than N/2 and we are not processing
				// paragraph then it is heading
				if (mb_strlen($str) < static::N / 2 && !$isInParagraph) {
					$this->_output[] = $this->_indent . $str;
				} else {
					$isInParagraph = TRUE;
					$tmp .= $str;
					// If we get to the end of line and any of paragraph ending
					// punctuation then we need to add this paragraph to array
					// of paragraphs and start a new one
					if (in_array($str[mb_strlen($str) - 1], $this->_paragraphEndSymbols)) {
						$this->_output[] = $this->_processParagraph($tmp);
						$tmp = '';
						$isInParagraph = FALSE;
					}
				}
			}

			// Write to output file
			$file = fopen($this->_outputFileName, 'w');
			$isSuccessful = fwrite($file, implode(PHP_EOL, $this->_output));
			fclose($file);

			return $isSuccessful;
		} else {
			die("Could not open input file: {$this->_inputFileName}\n");
		}
	}

	/**
	 * Adds indent for paragraph then
	 * splits paragraph into lines then
	 * adds spaces for justifying
	 * @param  string $str Paragraph to be processed
	 * @return string      Processed paragraph
	 */
	protected function _processParagraph($str) {
		$str = $this->_indent . $str;
		$iteration = 0;
		$start = 0;

		while ($iteration < floor(mb_strlen($str) / static::N)) {
			$c = $start + static::N;
			if ($str[$c] === ' ') {
				$spacePos = $c;
			} else {
				$sub = mb_substr($str, $start, static::N);
				$spacePos = $start + mb_strrpos($sub, ' ');
			}
			$str[$spacePos] = PHP_EOL;
			$start = $spacePos + 1;
			$iteration++;
		}

		$str = $this->_justifyParagraph($str);
		return $str;
	}

	/**
	 * Adds spaces for justifying
	 * @param  string $str Paragraph to which we need to add spaces
	 * @return string      Justified paragraph
	 */
	protected function _justifyParagraph($str) {
		$rows = explode(PHP_EOL, $str);
		$iteration = 0;
		foreach ($rows as &$row) {
			if ($iteration != count($rows) - 1) {
				$end = ($iteration == 0) ? mb_strlen($this->_indent) : 0;

				for ($i = mb_strlen($row) - 1; $i >= $end; $i--) {
					if (mb_strlen($row) >= static::N) {
						break;
					} else {
						if ($row[$i] === ' ') {
							$row = mb_substr($row, 0, $i) . '  ' . mb_substr($row, $i+1);
						}
					}
				}
			}
			$iteration++;
		}
		return implode(PHP_EOL, $rows);
	}
}

if (!isset($argv[1])) {
	die("Specify input and output files names\n");
} elseif (!isset($argv[2])) {
	die("Specify output file name\n");
} else {
	$j = new Justify($argv[1], $argv[2]);
	if ($j->justify()) {
		echo "Text was successfully justified!\n";
	};
}