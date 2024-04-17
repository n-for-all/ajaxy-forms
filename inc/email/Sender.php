<?php

namespace Ajaxy\Forms\Inc\Email;

/**
 */

class Sender
{
    /**
     */
    public static function send($trigger, $data)
    {
        $templates = self::get_templates($trigger);
        if (count($templates) == 0) {
            return false;
        }

        add_filter(
            'wp_mail_from',
            function ($email) {
                return "wordpress@ssmc.ae";
            }
        );
        try {
            $count = 0;
            foreach ($templates as $template) {
                $recipients = get_post_meta($template->ID, '_email_recipients', true);
                $subject = new Parser($data, $template->post_title);
                $parser = new Parser($data, $template->post_content);
                $recipientparser = new Parser($data, $recipients);

                $headers = ['Content-Type: text/html; charset=UTF-8'];
                $send_mail = wp_mail($recipientparser->outputPlain(), $subject->outputPlain(), $parser->output(), $headers);
                if ($send_mail) {
                    $count++;
                }
            }
        } catch (\Exception $e) {
        }
        return $count;
    }


    public static function get_templates(string $trigger)
    {
        $posts = get_posts(array(
            'numberposts'   => -1,
            'post_type'     => 'email-template',
            'post_status'     => 'publish',
            'meta_key'      => '_email_trigger',
            'meta_value'    => $trigger
        ));
        return $posts;
    }
}
