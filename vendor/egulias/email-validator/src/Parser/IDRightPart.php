<?php

namespace Isolated\Egulias\EmailValidator\Parser;

use Isolated\Egulias\EmailValidator\EmailLexer;
use Isolated\Egulias\EmailValidator\Result\Result;
use Isolated\Egulias\EmailValidator\Result\ValidEmail;
use Isolated\Egulias\EmailValidator\Result\InvalidEmail;
use Isolated\Egulias\EmailValidator\Result\Reason\ExpectingATEXT;
class IDRightPart extends DomainPart
{
    protected function validateTokens(bool $hasComments) : Result
    {
        $invalidDomainTokens = [EmailLexer::S_DQUOTE => \true, EmailLexer::S_SQUOTE => \true, EmailLexer::S_BACKTICK => \true, EmailLexer::S_SEMICOLON => \true, EmailLexer::S_GREATERTHAN => \true, EmailLexer::S_LOWERTHAN => \true];
        if (isset($invalidDomainTokens[((array) $this->lexer->token)['type']])) {
            return new InvalidEmail(new ExpectingATEXT('Invalid token in domain: ' . ((array) $this->lexer->token)['value']), ((array) $this->lexer->token)['value']);
        }
        return new ValidEmail();
    }
}
