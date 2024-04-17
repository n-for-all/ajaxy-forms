<?php

namespace Ajaxy\Forms\Inc\Email;

/**
 * Email Template Parser
 */

class Parser
{

    protected $_openingTag = '{{';
    protected $_closingTag = '}}';
    protected $_emailValues;
    protected $_emailTemplate;
    protected $_isTemplateFile = false;


    /**
     * Email Template Parser Class
     * 
     * @param array $emailValues ARRAY where the KEY is the Variable name in the template and the VALUE is the replacement value.
     * @param string $emailTemplate HTML template string OR File path to a Email Template file.
     * @param boolean $isTemplateFile Optional - (Default: FALSE) - If set to TRUE, $emailTemplate is expected to hold the HTML template string.  If set to FALSE, $emailTemplate is expected to be a File Path to external file containing email template string.
     * 
     * @return string HTML with any matching variables {{varName}} replaced with their values.
     */
    public function __construct($emailValues, $emailTemplate, $isTemplateFile = false)
    {
        $this->_emailValues = $emailValues;
        $this->_isTemplateFile = $isTemplateFile;

        if ($this->_isTemplateFile) {
            // Template HTML is stored in a File
            try {
                if (file_exists($emailTemplate)) {
                    $this->_emailTemplate = file_get_contents($emailTemplate);
                } else {
                    throw new \Exception('ERROR: Invalid Email Template Filepath');
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . ' | FILE: ' . $e->getFile() . ' | LINE: ' . $e->getLine();
            }
        } else {
            try {
                // Template HTML is stored in-line in the $emailTemplate property!
                if (is_string($emailTemplate)) {
                    $this->_emailTemplate = $emailTemplate;
                } else {
                    throw new \Exception('ERROR: Invalid Email Template.  $emailTemplate must be a String');
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . ' | FILE: ' . $e->getFile() . ' | LINE: ' . $e->getLine();
            }
        }
    }


    /**
     * Returns the Parsed Email Template
     * 
     * @return string HTML with any matching variables {{varName}} replaced with there values.
     */
    public function outputPlain()
    {
        $html = $this->_emailTemplate;
        foreach ($this->_emailValues as $key => $value) {
            if (isset($value) && $value != '' && is_string($value)) {
                $html = str_replace($this->_openingTag . $key . $this->_closingTag, $value, $html);
            }
        }
        return $html;
    }


    /**
     * Returns the Parsed Email Template
     * 
     * @return string HTML with any matching variables {{varName}} replaced with there values.
     */
    public function output()
    {
        $html = $this->_emailTemplate;
        foreach ($this->_emailValues as $key => $value) {
            if (isset($value) && $value != '' && is_string($value)) {
                $html = str_replace($this->_openingTag . $key . $this->_closingTag, $value, $html);
            }
        }

        ob_start();
        include dirname(__FILE__) . '/template/header.php';
        echo $html;
        include dirname(__FILE__) . '/template/footer.php';
        return ob_get_clean();
    }
}
