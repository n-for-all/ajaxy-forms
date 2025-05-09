<?php

namespace Isolated\Egulias\EmailValidator\Warning;

abstract class Warning
{
    public const CODE = 0;
    /**
     * @var string
     */
    protected $message = '';
    /**
     * @var int
     */
    protected $rfcNumber = 0;
    /**
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
    /**
     * @return int
     */
    public function code()
    {
        return self::CODE;
    }
    /**
     * @return int
     */
    public function RFCNumber()
    {
        return $this->rfcNumber;
    }
    public function __toString()
    {
        return $this->message() . " rfc: " . $this->rfcNumber . "internal code: " . static::CODE;
    }
}
