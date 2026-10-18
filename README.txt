=== Ajaxy Forms ===
Contributors: n-for-all
Donate link: http://ajaxy.org/
Version: 1.0.6
Tags: form, ajax, survey, contact, entry
Requires at least: 6.2
Tested up to: 7.1
Stable tag: 1.0.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Ajaxy Forms empowers developers to craft powerful and dynamic WordPress forms with complete code-first control. 

== Description ==

Effortless form creation for developers. Code your way to a seamless user experience.

This plugin leverages Ajax technology for lightning-fast form submissions, ensuring a seamless user experience, Built upon Symfony forms and featuring more that 30+ fields

https://www.youtube.com/watch?v=GHp9gHl_-k4

== Screenshots ==

1. Simple drag and drop builder.
2. Simple form actions from storage to emails
3. Import and Export forms as JSON
4. Sample form action settings supporting twig language

== Changelog ==

= 1.0.6 =
* The phone field shows the dial code rather than the full country name, so the selector stays narrow and the number gets the rest of the row. The country name is still available as a field setting.

= 1.0.5 =
* Added Cloudflare Turnstile as a captcha field.
* Added a phone field with a country selector, storing the number in international format.
* Added Radio Buttons and Checkbox Group fields, and an Expanded option on the Choice field.
* Fixed the choices of a select being keyed the wrong way round, which showed the value in place of the label and submitted the label in place of the value.
* Fixed submitted values being compared against the stored ones while still slashed by WordPress, which rejected any choice containing a quote with "This value is not valid".
* The captcha is now reset after every submission, a failed attempt no longer leaves a spent token behind.
* Fixed saving a form not redirecting, the redirect was sent after the page had started printing.
* Added symfony/intl, so the Country, Locale, Timezone and Currency fields work, and brought back the Language field.
* Settings a field does not support are now ignored instead of taking the whole form down, and the Sanitize Html setting now actually sanitizes.
