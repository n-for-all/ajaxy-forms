<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\Validator;

use Isolated\Symfony\Component\Form\AbstractExtension;
use Isolated\Symfony\Component\Form\Extension\Validator\Constraints\Form;
use Isolated\Symfony\Component\Form\FormRendererInterface;
use Isolated\Symfony\Component\Validator\Constraints\Traverse;
use Isolated\Symfony\Component\Validator\Mapping\ClassMetadata;
use Isolated\Symfony\Component\Validator\Validator\ValidatorInterface;
use Isolated\Symfony\Contracts\Translation\TranslatorInterface;
/**
 * Extension supporting the Symfony Validator component in forms.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ValidatorExtension extends AbstractExtension
{
    private $validator;
    private $formRenderer;
    private $translator;
    private $legacyErrorMessages;
    public function __construct(ValidatorInterface $validator, bool $legacyErrorMessages = \true, FormRendererInterface $formRenderer = null, TranslatorInterface $translator = null)
    {
        $this->legacyErrorMessages = $legacyErrorMessages;
        $metadata = $validator->getMetadataFor('Isolated\\Symfony\\Component\\Form\\Form');
        // Register the form constraints in the validator programmatically.
        // This functionality is required when using the Form component without
        // the DIC, where the XML file is loaded automatically. Thus the following
        // code must be kept synchronized with validation.xml
        /* @var $metadata ClassMetadata */
        $metadata->addConstraint(new Form());
        $metadata->addConstraint(new Traverse(\false));
        $this->validator = $validator;
        $this->formRenderer = $formRenderer;
        $this->translator = $translator;
    }
    public function loadTypeGuesser()
    {
        return new ValidatorTypeGuesser($this->validator);
    }
    protected function loadTypeExtensions()
    {
        return [new Type\FormTypeValidatorExtension($this->validator, $this->legacyErrorMessages, $this->formRenderer, $this->translator), new Type\RepeatedTypeValidatorExtension(), new Type\SubmitTypeValidatorExtension()];
    }
}
