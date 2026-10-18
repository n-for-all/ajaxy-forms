<?php

namespace Ajaxy\Forms\Inc\Types\Data;

/**
 * ISO 3166-1 alpha-2 countries with their E.164 calling codes.
 *
 * Shipped with the plugin rather than read from the intl extension, which
 * exposes country names but no calling codes.
 */
class Countries
{
    /**
     * Default country used when a stored number cannot be traced back to one
     */
    const FALLBACK = 'US';

    /**
     * The country to assume when several share a calling code
     */
    private static $primary = [
        '1' => 'US',
        '7' => 'RU',
        '39' => 'IT',
        '44' => 'GB',
        '47' => 'NO',
        '61' => 'AU',
        '212' => 'MA',
        '262' => 'RE',
        '358' => 'FI',
        '590' => 'GP',
        '599' => 'CW',
    ];

    private static $countries = [
        'AF' => ['name' => 'Afghanistan', 'dial' => '93'],
        'AL' => ['name' => 'Albania', 'dial' => '355'],
        'DZ' => ['name' => 'Algeria', 'dial' => '213'],
        'AS' => ['name' => 'American Samoa', 'dial' => '1'],
        'AD' => ['name' => 'Andorra', 'dial' => '376'],
        'AO' => ['name' => 'Angola', 'dial' => '244'],
        'AI' => ['name' => 'Anguilla', 'dial' => '1'],
        'AG' => ['name' => 'Antigua and Barbuda', 'dial' => '1'],
        'AR' => ['name' => 'Argentina', 'dial' => '54'],
        'AM' => ['name' => 'Armenia', 'dial' => '374'],
        'AW' => ['name' => 'Aruba', 'dial' => '297'],
        'AU' => ['name' => 'Australia', 'dial' => '61'],
        'AT' => ['name' => 'Austria', 'dial' => '43'],
        'AZ' => ['name' => 'Azerbaijan', 'dial' => '994'],
        'BS' => ['name' => 'Bahamas', 'dial' => '1'],
        'BH' => ['name' => 'Bahrain', 'dial' => '973'],
        'BD' => ['name' => 'Bangladesh', 'dial' => '880'],
        'BB' => ['name' => 'Barbados', 'dial' => '1'],
        'BY' => ['name' => 'Belarus', 'dial' => '375'],
        'BE' => ['name' => 'Belgium', 'dial' => '32'],
        'BZ' => ['name' => 'Belize', 'dial' => '501'],
        'BJ' => ['name' => 'Benin', 'dial' => '229'],
        'BM' => ['name' => 'Bermuda', 'dial' => '1'],
        'BT' => ['name' => 'Bhutan', 'dial' => '975'],
        'BO' => ['name' => 'Bolivia', 'dial' => '591'],
        'BA' => ['name' => 'Bosnia and Herzegovina', 'dial' => '387'],
        'BW' => ['name' => 'Botswana', 'dial' => '267'],
        'BR' => ['name' => 'Brazil', 'dial' => '55'],
        'IO' => ['name' => 'British Indian Ocean Territory', 'dial' => '246'],
        'VG' => ['name' => 'British Virgin Islands', 'dial' => '1'],
        'BN' => ['name' => 'Brunei', 'dial' => '673'],
        'BG' => ['name' => 'Bulgaria', 'dial' => '359'],
        'BF' => ['name' => 'Burkina Faso', 'dial' => '226'],
        'BI' => ['name' => 'Burundi', 'dial' => '257'],
        'KH' => ['name' => 'Cambodia', 'dial' => '855'],
        'CM' => ['name' => 'Cameroon', 'dial' => '237'],
        'CA' => ['name' => 'Canada', 'dial' => '1'],
        'CV' => ['name' => 'Cape Verde', 'dial' => '238'],
        'BQ' => ['name' => 'Caribbean Netherlands', 'dial' => '599'],
        'KY' => ['name' => 'Cayman Islands', 'dial' => '1'],
        'CF' => ['name' => 'Central African Republic', 'dial' => '236'],
        'TD' => ['name' => 'Chad', 'dial' => '235'],
        'CL' => ['name' => 'Chile', 'dial' => '56'],
        'CN' => ['name' => 'China', 'dial' => '86'],
        'CX' => ['name' => 'Christmas Island', 'dial' => '61'],
        'CC' => ['name' => 'Cocos (Keeling) Islands', 'dial' => '61'],
        'CO' => ['name' => 'Colombia', 'dial' => '57'],
        'KM' => ['name' => 'Comoros', 'dial' => '269'],
        'CG' => ['name' => 'Congo - Brazzaville', 'dial' => '242'],
        'CD' => ['name' => 'Congo - Kinshasa', 'dial' => '243'],
        'CK' => ['name' => 'Cook Islands', 'dial' => '682'],
        'CR' => ['name' => 'Costa Rica', 'dial' => '506'],
        'HR' => ['name' => 'Croatia', 'dial' => '385'],
        'CU' => ['name' => 'Cuba', 'dial' => '53'],
        'CW' => ['name' => 'Curaçao', 'dial' => '599'],
        'CY' => ['name' => 'Cyprus', 'dial' => '357'],
        'CZ' => ['name' => 'Czechia', 'dial' => '420'],
        'CI' => ['name' => 'Côte d\'Ivoire', 'dial' => '225'],
        'DK' => ['name' => 'Denmark', 'dial' => '45'],
        'DJ' => ['name' => 'Djibouti', 'dial' => '253'],
        'DM' => ['name' => 'Dominica', 'dial' => '1'],
        'DO' => ['name' => 'Dominican Republic', 'dial' => '1'],
        'EC' => ['name' => 'Ecuador', 'dial' => '593'],
        'EG' => ['name' => 'Egypt', 'dial' => '20'],
        'SV' => ['name' => 'El Salvador', 'dial' => '503'],
        'GQ' => ['name' => 'Equatorial Guinea', 'dial' => '240'],
        'ER' => ['name' => 'Eritrea', 'dial' => '291'],
        'EE' => ['name' => 'Estonia', 'dial' => '372'],
        'SZ' => ['name' => 'Eswatini', 'dial' => '268'],
        'ET' => ['name' => 'Ethiopia', 'dial' => '251'],
        'FK' => ['name' => 'Falkland Islands', 'dial' => '500'],
        'FO' => ['name' => 'Faroe Islands', 'dial' => '298'],
        'FJ' => ['name' => 'Fiji', 'dial' => '679'],
        'FI' => ['name' => 'Finland', 'dial' => '358'],
        'FR' => ['name' => 'France', 'dial' => '33'],
        'GF' => ['name' => 'French Guiana', 'dial' => '594'],
        'PF' => ['name' => 'French Polynesia', 'dial' => '689'],
        'GA' => ['name' => 'Gabon', 'dial' => '241'],
        'GM' => ['name' => 'Gambia', 'dial' => '220'],
        'GE' => ['name' => 'Georgia', 'dial' => '995'],
        'DE' => ['name' => 'Germany', 'dial' => '49'],
        'GH' => ['name' => 'Ghana', 'dial' => '233'],
        'GI' => ['name' => 'Gibraltar', 'dial' => '350'],
        'GR' => ['name' => 'Greece', 'dial' => '30'],
        'GL' => ['name' => 'Greenland', 'dial' => '299'],
        'GD' => ['name' => 'Grenada', 'dial' => '1'],
        'GP' => ['name' => 'Guadeloupe', 'dial' => '590'],
        'GU' => ['name' => 'Guam', 'dial' => '1'],
        'GT' => ['name' => 'Guatemala', 'dial' => '502'],
        'GG' => ['name' => 'Guernsey', 'dial' => '44'],
        'GN' => ['name' => 'Guinea', 'dial' => '224'],
        'GW' => ['name' => 'Guinea-Bissau', 'dial' => '245'],
        'GY' => ['name' => 'Guyana', 'dial' => '592'],
        'HT' => ['name' => 'Haiti', 'dial' => '509'],
        'HN' => ['name' => 'Honduras', 'dial' => '504'],
        'HK' => ['name' => 'Hong Kong SAR China', 'dial' => '852'],
        'HU' => ['name' => 'Hungary', 'dial' => '36'],
        'IS' => ['name' => 'Iceland', 'dial' => '354'],
        'IN' => ['name' => 'India', 'dial' => '91'],
        'ID' => ['name' => 'Indonesia', 'dial' => '62'],
        'IR' => ['name' => 'Iran', 'dial' => '98'],
        'IQ' => ['name' => 'Iraq', 'dial' => '964'],
        'IE' => ['name' => 'Ireland', 'dial' => '353'],
        'IM' => ['name' => 'Isle of Man', 'dial' => '44'],
        'IL' => ['name' => 'Israel', 'dial' => '972'],
        'IT' => ['name' => 'Italy', 'dial' => '39'],
        'JM' => ['name' => 'Jamaica', 'dial' => '1'],
        'JP' => ['name' => 'Japan', 'dial' => '81'],
        'JE' => ['name' => 'Jersey', 'dial' => '44'],
        'JO' => ['name' => 'Jordan', 'dial' => '962'],
        'KZ' => ['name' => 'Kazakhstan', 'dial' => '7'],
        'KE' => ['name' => 'Kenya', 'dial' => '254'],
        'KI' => ['name' => 'Kiribati', 'dial' => '686'],
        'KW' => ['name' => 'Kuwait', 'dial' => '965'],
        'KG' => ['name' => 'Kyrgyzstan', 'dial' => '996'],
        'LA' => ['name' => 'Laos', 'dial' => '856'],
        'LV' => ['name' => 'Latvia', 'dial' => '371'],
        'LB' => ['name' => 'Lebanon', 'dial' => '961'],
        'LS' => ['name' => 'Lesotho', 'dial' => '266'],
        'LR' => ['name' => 'Liberia', 'dial' => '231'],
        'LY' => ['name' => 'Libya', 'dial' => '218'],
        'LI' => ['name' => 'Liechtenstein', 'dial' => '423'],
        'LT' => ['name' => 'Lithuania', 'dial' => '370'],
        'LU' => ['name' => 'Luxembourg', 'dial' => '352'],
        'MO' => ['name' => 'Macao SAR China', 'dial' => '853'],
        'MG' => ['name' => 'Madagascar', 'dial' => '261'],
        'MW' => ['name' => 'Malawi', 'dial' => '265'],
        'MY' => ['name' => 'Malaysia', 'dial' => '60'],
        'MV' => ['name' => 'Maldives', 'dial' => '960'],
        'ML' => ['name' => 'Mali', 'dial' => '223'],
        'MT' => ['name' => 'Malta', 'dial' => '356'],
        'MH' => ['name' => 'Marshall Islands', 'dial' => '692'],
        'MQ' => ['name' => 'Martinique', 'dial' => '596'],
        'MR' => ['name' => 'Mauritania', 'dial' => '222'],
        'MU' => ['name' => 'Mauritius', 'dial' => '230'],
        'YT' => ['name' => 'Mayotte', 'dial' => '262'],
        'MX' => ['name' => 'Mexico', 'dial' => '52'],
        'FM' => ['name' => 'Micronesia', 'dial' => '691'],
        'MD' => ['name' => 'Moldova', 'dial' => '373'],
        'MC' => ['name' => 'Monaco', 'dial' => '377'],
        'MN' => ['name' => 'Mongolia', 'dial' => '976'],
        'ME' => ['name' => 'Montenegro', 'dial' => '382'],
        'MS' => ['name' => 'Montserrat', 'dial' => '1'],
        'MA' => ['name' => 'Morocco', 'dial' => '212'],
        'MZ' => ['name' => 'Mozambique', 'dial' => '258'],
        'MM' => ['name' => 'Myanmar (Burma)', 'dial' => '95'],
        'NA' => ['name' => 'Namibia', 'dial' => '264'],
        'NR' => ['name' => 'Nauru', 'dial' => '674'],
        'NP' => ['name' => 'Nepal', 'dial' => '977'],
        'NL' => ['name' => 'Netherlands', 'dial' => '31'],
        'NC' => ['name' => 'New Caledonia', 'dial' => '687'],
        'NZ' => ['name' => 'New Zealand', 'dial' => '64'],
        'NI' => ['name' => 'Nicaragua', 'dial' => '505'],
        'NE' => ['name' => 'Niger', 'dial' => '227'],
        'NG' => ['name' => 'Nigeria', 'dial' => '234'],
        'NU' => ['name' => 'Niue', 'dial' => '683'],
        'NF' => ['name' => 'Norfolk Island', 'dial' => '672'],
        'KP' => ['name' => 'North Korea', 'dial' => '850'],
        'MK' => ['name' => 'North Macedonia', 'dial' => '389'],
        'MP' => ['name' => 'Northern Mariana Islands', 'dial' => '1'],
        'NO' => ['name' => 'Norway', 'dial' => '47'],
        'OM' => ['name' => 'Oman', 'dial' => '968'],
        'PK' => ['name' => 'Pakistan', 'dial' => '92'],
        'PW' => ['name' => 'Palau', 'dial' => '680'],
        'PS' => ['name' => 'Palestinian Territories', 'dial' => '970'],
        'PA' => ['name' => 'Panama', 'dial' => '507'],
        'PG' => ['name' => 'Papua New Guinea', 'dial' => '675'],
        'PY' => ['name' => 'Paraguay', 'dial' => '595'],
        'PE' => ['name' => 'Peru', 'dial' => '51'],
        'PH' => ['name' => 'Philippines', 'dial' => '63'],
        'PL' => ['name' => 'Poland', 'dial' => '48'],
        'PT' => ['name' => 'Portugal', 'dial' => '351'],
        'PR' => ['name' => 'Puerto Rico', 'dial' => '1'],
        'QA' => ['name' => 'Qatar', 'dial' => '974'],
        'RO' => ['name' => 'Romania', 'dial' => '40'],
        'RU' => ['name' => 'Russia', 'dial' => '7'],
        'RW' => ['name' => 'Rwanda', 'dial' => '250'],
        'RE' => ['name' => 'Réunion', 'dial' => '262'],
        'BL' => ['name' => 'Saint Barthélemy', 'dial' => '590'],
        'SH' => ['name' => 'Saint Helena', 'dial' => '290'],
        'KN' => ['name' => 'Saint Kitts and Nevis', 'dial' => '1'],
        'LC' => ['name' => 'Saint Lucia', 'dial' => '1'],
        'MF' => ['name' => 'Saint Martin', 'dial' => '590'],
        'PM' => ['name' => 'Saint Pierre and Miquelon', 'dial' => '508'],
        'VC' => ['name' => 'Saint Vincent and the Grenadines', 'dial' => '1'],
        'WS' => ['name' => 'Samoa', 'dial' => '685'],
        'SM' => ['name' => 'San Marino', 'dial' => '378'],
        'SA' => ['name' => 'Saudi Arabia', 'dial' => '966'],
        'SN' => ['name' => 'Senegal', 'dial' => '221'],
        'RS' => ['name' => 'Serbia', 'dial' => '381'],
        'SC' => ['name' => 'Seychelles', 'dial' => '248'],
        'SL' => ['name' => 'Sierra Leone', 'dial' => '232'],
        'SG' => ['name' => 'Singapore', 'dial' => '65'],
        'SX' => ['name' => 'Sint Maarten', 'dial' => '1'],
        'SK' => ['name' => 'Slovakia', 'dial' => '421'],
        'SI' => ['name' => 'Slovenia', 'dial' => '386'],
        'SB' => ['name' => 'Solomon Islands', 'dial' => '677'],
        'SO' => ['name' => 'Somalia', 'dial' => '252'],
        'ZA' => ['name' => 'South Africa', 'dial' => '27'],
        'KR' => ['name' => 'South Korea', 'dial' => '82'],
        'SS' => ['name' => 'South Sudan', 'dial' => '211'],
        'ES' => ['name' => 'Spain', 'dial' => '34'],
        'LK' => ['name' => 'Sri Lanka', 'dial' => '94'],
        'SD' => ['name' => 'Sudan', 'dial' => '249'],
        'SR' => ['name' => 'Suriname', 'dial' => '597'],
        'SJ' => ['name' => 'Svalbard and Jan Mayen', 'dial' => '47'],
        'SE' => ['name' => 'Sweden', 'dial' => '46'],
        'CH' => ['name' => 'Switzerland', 'dial' => '41'],
        'SY' => ['name' => 'Syria', 'dial' => '963'],
        'ST' => ['name' => 'São Tomé and Príncipe', 'dial' => '239'],
        'TW' => ['name' => 'Taiwan', 'dial' => '886'],
        'TJ' => ['name' => 'Tajikistan', 'dial' => '992'],
        'TZ' => ['name' => 'Tanzania', 'dial' => '255'],
        'TH' => ['name' => 'Thailand', 'dial' => '66'],
        'TL' => ['name' => 'Timor-Leste', 'dial' => '670'],
        'TG' => ['name' => 'Togo', 'dial' => '228'],
        'TK' => ['name' => 'Tokelau', 'dial' => '690'],
        'TO' => ['name' => 'Tonga', 'dial' => '676'],
        'TT' => ['name' => 'Trinidad and Tobago', 'dial' => '1'],
        'TN' => ['name' => 'Tunisia', 'dial' => '216'],
        'TM' => ['name' => 'Turkmenistan', 'dial' => '993'],
        'TC' => ['name' => 'Turks and Caicos Islands', 'dial' => '1'],
        'TV' => ['name' => 'Tuvalu', 'dial' => '688'],
        'TR' => ['name' => 'Türkiye', 'dial' => '90'],
        'VI' => ['name' => 'U.S. Virgin Islands', 'dial' => '1'],
        'UG' => ['name' => 'Uganda', 'dial' => '256'],
        'UA' => ['name' => 'Ukraine', 'dial' => '380'],
        'AE' => ['name' => 'United Arab Emirates', 'dial' => '971'],
        'GB' => ['name' => 'United Kingdom', 'dial' => '44'],
        'US' => ['name' => 'United States', 'dial' => '1'],
        'UY' => ['name' => 'Uruguay', 'dial' => '598'],
        'UZ' => ['name' => 'Uzbekistan', 'dial' => '998'],
        'VU' => ['name' => 'Vanuatu', 'dial' => '678'],
        'VA' => ['name' => 'Vatican City', 'dial' => '39'],
        'VE' => ['name' => 'Venezuela', 'dial' => '58'],
        'VN' => ['name' => 'Vietnam', 'dial' => '84'],
        'WF' => ['name' => 'Wallis and Futuna', 'dial' => '681'],
        'EH' => ['name' => 'Western Sahara', 'dial' => '212'],
        'YE' => ['name' => 'Yemen', 'dial' => '967'],
        'ZM' => ['name' => 'Zambia', 'dial' => '260'],
        'ZW' => ['name' => 'Zimbabwe', 'dial' => '263'],
        'AX' => ['name' => 'Åland Islands', 'dial' => '358'],
    ];

    /**
     * All countries, keyed by their alpha-2 code
     *
     * @return array
     */
    public static function all()
    {
        return self::$countries;
    }

    /**
     * Check whether the given alpha-2 code is known
     *
     * @param string $code
     *
     * @return boolean
     */
    public static function has($code)
    {
        return isset(self::$countries[\strtoupper((string) $code)]);
    }

    /**
     * The calling code of a country, without the leading plus
     *
     * @param string $code
     *
     * @return string
     */
    public static function get_dial_code($code)
    {
        $code = \strtoupper((string) $code);

        return isset(self::$countries[$code]) ? self::$countries[$code]['dial'] : '';
    }

    /**
     * Choices for a select, keyed by alpha-2
     *
     * The dial code alone is not enough to tell the entries apart, 25 of them
     * share +1, so the country it belongs to is named either in full or by
     * its two letter code.
     *
     * @param string $format Either "code" or "name"
     *
     * @return array
     */
    public static function get_choices($format = 'code')
    {
        $choices = [];
        foreach (self::$countries as $code => $country) {
            $label = 'name' === $format
                ? \sprintf('%s (+%s)', $country['name'], $country['dial'])
                : \sprintf('+%s (%s)', $country['dial'], $code);

            $choices[$label] = $code;
        }

        return $choices;
    }

    /**
     * Find the country a number in international format belongs to
     *
     * Several countries share a calling code, the longest match wins and the
     * first country declaring it is returned, which is enough to put the
     * select on a sensible entry when a stored value is edited again.
     *
     * @param string $number A number starting with a plus sign
     *
     * @return string|null
     */
    public static function find_by_number($number)
    {
        $number = \preg_replace('/[^0-9]/', '', (string) $number);
        if ('' === $number) {
            return null;
        }

        $match = null;
        $length = 0;
        foreach (self::$countries as $code => $country) {
            $dial = $country['dial'];
            if (\strlen($dial) > $length && 0 === \strpos($number, $dial)) {
                $match = isset(self::$primary[$dial]) ? self::$primary[$dial] : $code;
                $length = \strlen($dial);
            }
        }

        return $match;
    }
}
