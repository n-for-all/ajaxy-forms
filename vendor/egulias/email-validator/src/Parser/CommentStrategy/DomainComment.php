<?php

namespace Isolated\Egulias\EmailValidator\Parser\CommentStrategy;

use Isolated\Egulias\EmailValidator\EmailLexer;
use Isolated\Egulias\EmailValidator\Result\Result;
use Isolated\Egulias\EmailValidator\Result\ValidEmail;
use Isolated\Egulias\EmailValidator\Result\InvalidEmail;
use Isolated\Egulias\EmailValidator\Result\Reason\ExpectingATEXT;
class DomainComment implements CommentStrategy
{
    public function exitCondition(EmailLexer $lexer, int $openedParenthesis) : bool
    {
        if ($openedParenthesis === 0 && $lexer->isNextToken(EmailLexer::S_DOT)) {
            // || !$internalLexer->moveNext()) {
            return \false;
        }
        return \true;
    }
    public function endOfLoopValidations(EmailLexer $lexer) : Result
    {
        //test for end of string
        if (!$lexer->isNextToken(EmailLexer::S_DOT)) {
            return new InvalidEmail(new ExpectingATEXT('DOT not found near CLOSEPARENTHESIS'), ((array) $lexer->token)['value']);
        }
        //add warning
        //Address is valid within the message but cannot be used unmodified for the envelope
        return new ValidEmail();
    }
    public function getWarnings() : array
    {
        return [];
    }
}
