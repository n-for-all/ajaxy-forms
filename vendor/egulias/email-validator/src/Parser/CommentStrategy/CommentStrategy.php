<?php

namespace Isolated\Egulias\EmailValidator\Parser\CommentStrategy;

use Isolated\Egulias\EmailValidator\EmailLexer;
use Isolated\Egulias\EmailValidator\Result\Result;
interface CommentStrategy
{
    /**
     * Return "true" to continue, "false" to exit
     */
    public function exitCondition(EmailLexer $lexer, int $openedParenthesis) : bool;
    public function endOfLoopValidations(EmailLexer $lexer) : Result;
    public function getWarnings() : array;
}
