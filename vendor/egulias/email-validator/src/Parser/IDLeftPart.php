<?php

namespace Isolated\Egulias\EmailValidator\Parser;

use Isolated\Egulias\EmailValidator\Result\Result;
use Isolated\Egulias\EmailValidator\Result\InvalidEmail;
use Isolated\Egulias\EmailValidator\Result\Reason\CommentsInIDRight;
class IDLeftPart extends LocalPart
{
    protected function parseComments() : Result
    {
        return new InvalidEmail(new CommentsInIDRight(), ((array) $this->lexer->token)['value']);
    }
}
