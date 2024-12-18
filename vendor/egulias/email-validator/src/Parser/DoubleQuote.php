<?php

namespace Isolated\Egulias\EmailValidator\Parser;

use Isolated\Egulias\EmailValidator\EmailLexer;
use Isolated\Egulias\EmailValidator\Result\ValidEmail;
use Isolated\Egulias\EmailValidator\Result\InvalidEmail;
use Isolated\Egulias\EmailValidator\Warning\CFWSWithFWS;
use Isolated\Egulias\EmailValidator\Warning\QuotedString;
use Isolated\Egulias\EmailValidator\Result\Reason\ExpectingATEXT;
use Isolated\Egulias\EmailValidator\Result\Reason\UnclosedQuotedString;
use Isolated\Egulias\EmailValidator\Result\Result;
class DoubleQuote extends PartParser
{
    public function parse() : Result
    {
        $validQuotedString = $this->checkDQUOTE();
        if ($validQuotedString->isInvalid()) {
            return $validQuotedString;
        }
        $special = [EmailLexer::S_CR => \true, EmailLexer::S_HTAB => \true, EmailLexer::S_LF => \true];
        $invalid = [EmailLexer::C_NUL => \true, EmailLexer::S_HTAB => \true, EmailLexer::S_CR => \true, EmailLexer::S_LF => \true];
        $setSpecialsWarning = \true;
        $this->lexer->moveNext();
        while (((array) $this->lexer->token)['type'] !== EmailLexer::S_DQUOTE && null !== ((array) $this->lexer->token)['type']) {
            if (isset($special[((array) $this->lexer->token)['type']]) && $setSpecialsWarning) {
                $this->warnings[CFWSWithFWS::CODE] = new CFWSWithFWS();
                $setSpecialsWarning = \false;
            }
            if (((array) $this->lexer->token)['type'] === EmailLexer::S_BACKSLASH && $this->lexer->isNextToken(EmailLexer::S_DQUOTE)) {
                $this->lexer->moveNext();
            }
            $this->lexer->moveNext();
            if (!$this->escaped() && isset($invalid[((array) $this->lexer->token)['type']])) {
                return new InvalidEmail(new ExpectingATEXT("Expecting ATEXT between DQUOTE"), ((array) $this->lexer->token)['value']);
            }
        }
        $prev = $this->lexer->getPrevious();
        if ($prev['type'] === EmailLexer::S_BACKSLASH) {
            $validQuotedString = $this->checkDQUOTE();
            if ($validQuotedString->isInvalid()) {
                return $validQuotedString;
            }
        }
        if (!$this->lexer->isNextToken(EmailLexer::S_AT) && $prev['type'] !== EmailLexer::S_BACKSLASH) {
            return new InvalidEmail(new ExpectingATEXT("Expecting ATEXT between DQUOTE"), ((array) $this->lexer->token)['value']);
        }
        return new ValidEmail();
    }
    protected function checkDQUOTE() : Result
    {
        $previous = $this->lexer->getPrevious();
        if ($this->lexer->isNextToken(EmailLexer::GENERIC) && $previous['type'] === EmailLexer::GENERIC) {
            $description = 'https://tools.ietf.org/html/rfc5322#section-3.2.4 - quoted string should be a unit';
            return new InvalidEmail(new ExpectingATEXT($description), ((array) $this->lexer->token)['value']);
        }
        try {
            $this->lexer->find(EmailLexer::S_DQUOTE);
        } catch (\Exception $e) {
            return new InvalidEmail(new UnclosedQuotedString(), ((array) $this->lexer->token)['value']);
        }
        $this->warnings[QuotedString::CODE] = new QuotedString($previous['value'], ((array) $this->lexer->token)['value']);
        return new ValidEmail();
    }
}
