<?php namespace Jmem;

use Jmem\Parser\Parser;

class JsonLoader implements LoaderInterface {

    use ExceptionTrait;

	private $file;

	private $bytes;

	private $element;
	
	/**
	* $file is the path to the file that you would like to parse though.
	* If the file does not exist an exception will be thrown. Element is
	* the array of objects you would like to have broken up and returned
	* to you.
	*
	* @param String $file
	* @param String $element
	* @param int $bytes (1024 default)
	*/
	public function __construct($file, $element, $bytes = 1024) {

		$this->setFile($file);
		$this->setElement($element);
		$this->setBytes($bytes);

	}

    /**
     * Set the file and open up a steam. If the file
     * cannot be opened for reading throw an error.
     *
     * @param $file
     * @throws \Exception
     */
	public function setFile($file) {
        $f = $file;
        if (preg_match('#^compress\.(.*)://(.*)#', $file, $r)) {
            $f = $r[2];
        }
        if(is_readable($f)) {
            $open = fopen($file, "rb");
            $this->file = $open;
        } else {
            $this->handleError("{$file} is not readable.");
        }

	}

    /**
     * Set the amount of bytes that should be read in
     * at a time. 1024 bytes is the default value.
     *
     * @param $bytes
     */
	public function setBytes($bytes) {

		$this->bytes = $bytes;

	}

    /**
     * Set the element that we will look for in the json.
     * If this is not a string throw an error.
     *
     * @param $element
     * @throws \Exception
     */
	public function setElement($element) {

        if(is_string($element)) {
            $this->element = $element;
        } else {
            $this->handleError(" element {$element} is not a string.");
        }

	}

    /**
     * Return an open stream of the file that has been
     * passed in.
     *
     * @return mixed
     */
    public function getFile() {

        return $this->file;

    }

    /**
     * Return the number of bytes we will
     * be reading in at a time.
     *
     * @return Integer
     */
    public function getBytes() {

        return $this->bytes;

    }

    /**
     * Return the element that contains an array that
     * we are looking for.
     *
     * @return String
     */
    public function getElement() {

        return $this->element;

    }

    // Get ready to do some heavy lifting.
	public function parse() {

        return new Parser($this);

	}

}
