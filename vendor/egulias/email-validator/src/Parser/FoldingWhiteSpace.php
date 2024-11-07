<?php

namespace Isolated\Egulias\EmailValidator\Parser;

use Isolated\Egulias\EmailValidator\EmailLexer;
use Isolated\Egulias\EmailValidator\Warning\CFWSNearAt;
use Isolated\Egulias\EmailValidator\Result\InvalidEmail;
use Isolated\Egulias\EmailValidator\Warning\CFWSWithFWS;
use Isolated\Egulias\EmailValidator\Result\Reason\CRNoLF;
use Isolated\Egulias\EmailValidator\Result\Reason\AtextAfterCFWS;
use Isolated\Egulias\EmailValidator\Result\Reason\CRLFAtTheEnd;
use Isolated\Egulias\EmailValidator\Result\Reason\CRLFX2;
use Isolated\Egulias\EmailValidator\Result\Reason\ExpectingCTEXT;
use Isolated\Egulias\EmailValidator\Result\Result;
use Isolated\Egulias\EmailValidator\Result\ValidEmail;
class FoldingWhiteSpace extends PartParser
{
    public const FWS_TYPES = [EmailLexer::S_SP, EmailLexer::S_HTAB, EmailLexer::S_CR, EmailLexer::S_LF, EmailLexer::CRLF];
    public function parse() : Result
    {
        if (!$this->isFWS()) {
            return new ValidEmail();
        }
        $previous = $this->lexer->getPrevious();
        $resultCRLF = $this->checkCRLFInFWS();
        if ($resultCRLF->isInvalid()) {
            return $resultCRLF;
        }
        if (((array) $this->lexer->token)['type'] === EmailLexer::S_CR) {
            return new InvalidEmail(new CRNoLF(), ((array) $this->lexer->token)['value']);
        }
        if ($this->lexer->isNextToken(EmailLexer::GENERIC) && $previous['type'] !== EmailLexer::S_AT) {
            return new InvalidEmail(new AtextAfterCFWS(), ((array) $this->lexer->token)['value']);
        }
        if (((array) $this->lexer->token)['type'] === EmailLexer::S_LF || ((array) $this->lexer->token)['type'] === EmailLexer::C_NUL) {
            return new InvalidEmail(new ExpectingCTEXT(), ((array) $this->lexer->token)['value']);
        }
        if ($this->lexer->isNextToken(EmailLexer::S_AT) || $previous['type'] === EmailLexer::S_AT) {
            $this->warnings[CFWSNearAt::CODE] = new CFWSNearAt();
        } else {
            $this->warnings[CFWSWithFWS::CODE] = new CFWSWithFWS();
        }
        return new ValidEmail();
    }
    protected function checkCRLFInFWS() : Result
    {
        if (((array) $this->lexer->token)['type'] !== EmailLexer::CRLF) {
            return new ValidEmail();
        }
        if (!$this->lexer->isNextTokenAny(array(EmailLexer::S_SP, EmailLexer::S_HTAB))) {
            return new InvalidEmail(new CRLFX2(), ((array) $this->lexer->token)['value']);
        }
        //this has no coverage. Condition is repeated from above one
        if (!$this->lexer->isNextTokenAny(array(EmailLexer::S_SP, EmailLexer::S_HTAB))) {
            return new InvalidEmail(new CRLFAtTheEnd(), ((array) $this->lexer->token)['value']);
        }
        return new ValidEmail();
    }
    protected function isFWS() : bool
    {
        if ($this->escaped()) {
            return \false;
        }
        return \in_array(((array) $this->lexer->token)['type'], self::FWS_TYPES);
    }
}
