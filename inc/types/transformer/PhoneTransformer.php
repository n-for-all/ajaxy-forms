<?php

namespace Ajaxy\Forms\Inc\Types\Transformer;

use Ajaxy\Forms\Inc\Types\Data\Countries;
use Isolated\Symfony\Component\Form\DataTransformerInterface;
use Isolated\Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Maps a phone number in international format onto the country and the
 * national number the widget is made of, and back.
 *
 * The stored value is always "+<calling code><national number>", the national
 * number being what is left once the trunk prefix is dropped, so that a value
 * read back into the form and submitted again comes out unchanged.
 */
class PhoneTransformer implements DataTransformerInterface
{
    /**
     * Countries whose national numbers keep their leading zero
     */
    const KEEPS_TRUNK_ZERO = ['IT', 'VA'];

    private $default_country;

    public function __construct($default_country = '')
    {
        $this->default_country = Countries::has($default_country) ? \strtoupper($default_country) : '';
    }

    /**
     * {@inheritdoc}
     *
     * @param string|null $value A number in international format, +971501234567
     *
     * @return array The country and the national number
     */
    public function transform($value)
    {
        $empty = ['country' => $this->default_country, 'number' => ''];

        if (null === $value || '' === $value) {
            return $empty;
        }

        if (\is_array($value)) {
            return \array_merge($empty, $value);
        }

        $digits = \preg_replace('/[^0-9]/', '', (string) $value);
        if ('' === $digits) {
            return $empty;
        }

        $country = Countries::find_by_number($digits);
        if (!$country) {
            return ['country' => $this->default_country, 'number' => $digits];
        }

        return [
            'country' => $country,
            'number' => \substr($digits, \strlen(Countries::get_dial_code($country))),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @param array $value The submitted country and national number
     *
     * @return string The number in international format
     */
    public function reverseTransform($value)
    {
        if (!\is_array($value)) {
            return '';
        }

        $country = isset($value['country']) ? \strtoupper((string) $value['country']) : '';
        $entered = isset($value['number']) ? \trim((string) $value['number']) : '';
        $number = \preg_replace('/[^0-9]/', '', $entered);

        if ('' === $number) {
            return '';
        }

        if (!Countries::has($country)) {
            throw new TransformationFailedException('Please choose the country of the phone number.');
        }

        $dial = Countries::get_dial_code($country);

        // Visitors often type the country code in as well, only treat it as
        // one when it is written the way a country code is written.
        if (0 === \strpos($entered, '+') || 0 === \strpos($number, '00')) {
            $number = \ltrim($number, '0');
            if (0 === \strpos($number, $dial)) {
                $number = \substr($number, \strlen($dial));
            }
        }

        // The trunk prefix belongs to the national format only.
        if (!\in_array($country, self::KEEPS_TRUNK_ZERO)) {
            $number = \preg_replace('/^0+/', '', $number);
        }

        $full = $dial . $number;
        if ('' === $number || \strlen($full) < 7 || \strlen($full) > 15) {
            throw new TransformationFailedException('Please enter a valid phone number.');
        }

        return '+' . $full;
    }
}
