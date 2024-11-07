<?php

namespace Isolated\Egulias\EmailValidator;

use Isolated\Egulias\EmailValidator\Result\Result;
use Isolated\Egulias\EmailValidator\Result\ValidEmail;
use Isolated\Egulias\EmailValidator\Result\InvalidEmail;
use Isolated\Egulias\EmailValidator\Result\Reason\ExpectingATEXT;
abstract class Parser
{
    /**
     * @var Warning\Warning[]
     */
    protected $warnings = [];
    /**
     * @var EmailLexer
     */
    protected $lexer;
    /**
     * id-left "@" id-right
     */
    protected abstract function parseRightFromAt() : Result;
    protected abstract function parseLeftFromAt() : Result;
    protected abstract function preLeftParsing() : Result;
    public function __construct(EmailLexer $lexer)
    {
        $this->lexer = $lexer;
    }
    public function parse(string $str) : Result
    {
        $this->lexer->setInput($str);
        if ($this->lexer->hasInvalidTokens()) {
            return new InvalidEmail(new ExpectingATEXT("Invalid tokens found"), $this->lexer->token["value"]);
        }
        $preParsingResult = $this->preLeftParsing();
        if ($preParsingResult->isInvalid()) {
            return $preParsingResult;
        }
        $localPartResult = $this->parseLeftFromAt();
        if ($localPartResult->isInvalid()) {
            return $localPartResult;
        }
        $domainPartResult = $this->parseRightFromAt();
        if ($domainPartResult->isInvalid()) {
            return $domainPartResult;
        }
        return new ValidEmail();
    }
    /**
     * @return Warning\Warning[]
     */
    public function getWarnings() : array
    {
        return $this->warnings;
    }
    protected function hasAtToken() : bool
    {
        $this->lexer->moveNext();
        $this->lexer->moveNext();
        return ((array) $this->lexer->token)['type'] !== EmailLexer::S_AT;
    }
}
