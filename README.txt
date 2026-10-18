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

**Security release, update as soon as you can.**

* Form entries could be exported by anyone, with no login and no nonce, because the handler ran on admin_init which is reachable through admin-ajax.php. It now requires the capability and a nonce.
* Submitted values, and the user agent, referer and address recorded alongside them, were written into the entries screens as markup. Anything a visitor sent ran in the browser of whoever opened those screens. Everything is escaped now.
* Uploads were handed to WordPress with its file type checking turned off, so a form with a file field accepted any extension. The checking is back on, and executable extensions are refused whatever the field allows.
* Every admin handler asks for the capability now rather than relying on its nonce alone.
* The public endpoint behind the term and posts field only answers for public post types and published posts, caps how many it returns, and no longer takes meta queries from the caller.
* Saved credentials are no longer printed back into the page, and the address recorded with an entry is taken from the connection rather than from headers the sender controls.
* Deleting several entries at once deleted only the first.
* The Enabled switch on a form action is honoured. An action that had been set up and then switched off still ran, and one that was half filled in could fail the whole submission.
* An SMS or Whatsapp that Twilio accepted was reported back as a failure, because the delivery state it returns was read as though it were an http code.
* The message of an email or an SMS accepts {{data[FIELD_NAME].value}} the way the subject always has. It was handed the raw submission, so those came out empty.

* The phone field shows the dial code rather than the full country name, so the selector stays narrow and the number gets the rest of the row. The country name is still available as a field setting.
* Editing a form highlights Forms in the admin menu instead of New Form, and viewing a single entry highlights Entries instead of nothing.
* A required checkbox now blocks the submission. It was validated with Not Blank, which accepts the "0" an unticked box holds, so the form went through regardless.
* The Actions tab lists the placeholders this form accepts, one per field, click to copy.
* The email To field says it takes several addresses, and the headers example no longer shows its own HTML tags.

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
