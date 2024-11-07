<?php

namespace Isolated\Egulias\EmailValidator\Parser\CommentStrategy;

use Isolated\Egulias\EmailValidator\EmailLexer;
use Isolated\Egulias\EmailValidator\Result\Result;
use Isolated\Egulias\EmailValidator\Result\ValidEmail;
use Isolated\Egulias\EmailValidator\Warning\CFWSNearAt;
use Isolated\Egulias\EmailValidator\Result\InvalidEmail;
use Isolated\Egulias\EmailValidator\Result\Reason\ExpectingATEXT;
class LocalComment implements CommentStrategy
{
    /**
     * @var array
     */
    private $warnings = [];
    public function exitCondition(EmailLexer $lexer, int $openedParenthesis) : bool
    {
        return !$lexer->isNextToken(EmailLexer::S_AT);
    }
    public function endOfLoopValidations(EmailLexer $lexer) : Result
    {
        if (!$lexer->isNextToken(EmailLexer::S_AT)) {
            return new InvalidEmail(new ExpectingATEXT('ATEX is not expected after closing comments'), ((array) $lexer->token)['value']);
        }
        $this->warnings[CFWSNearAt::CODE] = new CFWSNearAt();
        return new ValidEmail();
    }
    public function getWarnings() : array
    {
        return $this->warnings;
    }
}
