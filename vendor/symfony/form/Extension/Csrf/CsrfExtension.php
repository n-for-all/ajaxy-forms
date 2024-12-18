<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\Csrf;

use Isolated\Symfony\Component\Form\AbstractExtension;
use Isolated\Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Isolated\Symfony\Contracts\Translation\TranslatorInterface;
/**
 * This extension protects forms by using a CSRF token.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CsrfExtension extends AbstractExtension
{
    private $tokenManager;
    private $translator;
    private $translationDomain;
    public function __construct(CsrfTokenManagerInterface $tokenManager, TranslatorInterface $translator = null, string $translationDomain = null)
    {
        $this->tokenManager = $tokenManager;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }
    /**
     * {@inheritdoc}
     */
    protected function loadTypeExtensions()
    {
        return [new Type\FormTypeCsrfExtension($this->tokenManager, \true, '_token', $this->translator, $this->translationDomain)];
    }
}
