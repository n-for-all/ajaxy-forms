<?php

namespace Isolated\Egulias\EmailValidator\Result;

use Isolated\Egulias\EmailValidator\Result\Reason\SpoofEmail as ReasonSpoofEmail;
class SpoofEmail extends InvalidEmail
{
    public function __construct()
    {
        $this->reason = new ReasonSpoofEmail();
        parent::__construct($this->reason, '');
    }
}
