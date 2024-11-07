<?php

namespace Isolated\Egulias\EmailValidator\Validation;

use Isolated\Egulias\EmailValidator\EmailLexer;
use Isolated\Egulias\EmailValidator\MessageIDParser;
use Isolated\Egulias\EmailValidator\Result\InvalidEmail;
use Isolated\Egulias\EmailValidator\Result\Reason\ExceptionFound;
class MessageIDValidation implements EmailValidation
{
    /**
     * @var array
     */
    private $warnings = [];
    /**
     * @var ?InvalidEmail
     */
    private $error;
    public function isValid(string $email, EmailLexer $emailLexer) : bool
    {
        $parser = new MessageIDParser($emailLexer);
        try {
            $result = $parser->parse($email);
            $this->warnings = $parser->getWarnings();
            if ($result->isInvalid()) {
                /** @psalm-suppress PropertyTypeCoercion */
                $this->error = $result;
                return \false;
            }
        } catch (\Exception $invalid) {
            $this->error = new InvalidEmail(new ExceptionFound($invalid), '');
            return \false;
        }
        return \true;
    }
    public function getWarnings() : array
    {
        return $this->warnings;
    }
    public function getError() : ?InvalidEmail
    {
        return $this->error;
    }
}
