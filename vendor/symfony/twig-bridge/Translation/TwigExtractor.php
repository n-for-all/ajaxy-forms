<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Bridge\Twig\Translation;

use Isolated\Symfony\Component\Finder\Finder;
use Isolated\Symfony\Component\Translation\Extractor\AbstractFileExtractor;
use Isolated\Symfony\Component\Translation\Extractor\ExtractorInterface;
use Isolated\Symfony\Component\Translation\MessageCatalogue;
use Isolated\Twig\Environment;
use Isolated\Twig\Error\Error;
use Isolated\Twig\Source;
/**
 * TwigExtractor extracts translation messages from a twig template.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TwigExtractor extends AbstractFileExtractor implements ExtractorInterface
{
    /**
     * Default domain for found messages.
     *
     * @var string
     */
    private $defaultDomain = 'messages';
    /**
     * Prefix for found message.
     *
     * @var string
     */
    private $prefix = '';
    private $twig;
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
    /**
     * {@inheritdoc}
     */
    public function extract($resource, MessageCatalogue $catalogue)
    {
        foreach ($this->extractFiles($resource) as $file) {
            try {
                $this->extractTemplate(\file_get_contents($file->getPathname()), $catalogue);
            } catch (Error $e) {
                // ignore errors, these should be fixed by using the linter
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }
    protected function extractTemplate(string $template, MessageCatalogue $catalogue)
    {
        $visitor = $this->twig->getExtension('Isolated\\Symfony\\Bridge\\Twig\\Extension\\TranslationExtension')->getTranslationNodeVisitor();
        $visitor->enable();
        $this->twig->parse($this->twig->tokenize(new Source($template, '')));
        foreach ($visitor->getMessages() as $message) {
            $catalogue->set(\trim($message[0]), $this->prefix . \trim($message[0]), $message[1] ?: $this->defaultDomain);
        }
        $visitor->disable();
    }
    /**
     * @return bool
     */
    protected function canBeExtracted(string $file)
    {
        return $this->isFile($file) && 'twig' === \pathinfo($file, \PATHINFO_EXTENSION);
    }
    /**
     * {@inheritdoc}
     */
    protected function extractFromDirectory($directory)
    {
        $finder = new Finder();
        return $finder->files()->name('*.twig')->in($directory);
    }
}
