<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Bridge\Twig\Mime;

use Isolated\Symfony\Component\Mime\Email;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TemplatedEmail extends Email
{
    private $htmlTemplate;
    private $textTemplate;
    private $context = [];
    /**
     * @return $this
     */
    public function textTemplate(?string $template)
    {
        $this->textTemplate = $template;
        return $this;
    }
    /**
     * @return $this
     */
    public function htmlTemplate(?string $template)
    {
        $this->htmlTemplate = $template;
        return $this;
    }
    public function getTextTemplate() : ?string
    {
        return $this->textTemplate;
    }
    public function getHtmlTemplate() : ?string
    {
        return $this->htmlTemplate;
    }
    /**
     * @return $this
     */
    public function context(array $context)
    {
        $this->context = $context;
        return $this;
    }
    public function getContext() : array
    {
        return $this->context;
    }
    /**
     * @internal
     */
    public function __serialize() : array
    {
        return [$this->htmlTemplate, $this->textTemplate, $this->context, parent::__serialize()];
    }
    /**
     * @internal
     */
    public function __unserialize(array $data) : void
    {
        [$this->htmlTemplate, $this->textTemplate, $this->context, $parentData] = $data;
        parent::__unserialize($parentData);
    }
}
