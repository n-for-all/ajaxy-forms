<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Guess;

/**
 * Contains a guessed class name and a list of options for creating an instance
 * of that class.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TypeGuess extends Guess
{
    private $type;
    private $options;
    /**
     * @param string $type       The guessed field type
     * @param array  $options    The options for creating instances of the
     *                           guessed class
     * @param int    $confidence The confidence that the guessed class name
     *                           is correct
     */
    public function __construct(string $type, array $options, int $confidence)
    {
        parent::__construct($confidence);
        $this->type = $type;
        $this->options = $options;
    }
    /**
     * Returns the guessed field type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Returns the guessed options for creating instances of the guessed type.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
