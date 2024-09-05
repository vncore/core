<?php
use Vncore\Core\Mail\SendMail;
use Vncore\Core\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Mail;

/**
 * Function send mail
 * Mail queue to run need setting crontab for php artisan schedule:run
 *
 * @param   [string]  $view            Path to view
 * @param   array     $dataView        Content send to view
 * @param   array     $emailConfig     to, cc, bbc, subject..
 * @param   array     $attach      Attach file
 *
 * @return  mixed
 */
if (!function_exists('vncore_send_mail') && !in_array('vncore_send_mail', config('vncore_functions_except', []))) {
    function vncore_send_mail($view, array $dataView = [], array $emailConfig = [], array $attach = [])
    {
        //Check email action mode is enable
        if (!empty(vncore_config('email_action_mode'))) {
            // Check email action queue is enable
            if (!empty(vncore_config('email_action_queue'))) {
                dispatch(new SendEmailJob($view, $dataView, $emailConfig, $attach));
            } else {
                vncore_process_send_mail($view, $dataView, $emailConfig, $attach);
            }
        } else {
            return false;
        }
    }
}
/**
 * Process send mail
 *
 * @param   [type]  $view         [$view description]
 * @param   array   $dataView     [$dataView description]
 * @param   array   $emailConfig  [$emailConfig description]
 * @param   array   $attach       [$attach description]
 *
 * @return  [][][]                [return description]
 */
if (!function_exists('vncore_process_send_mail') && !in_array('vncore_process_send_mail', config('vncore_functions_except', []))) {
    function vncore_process_send_mail($view, array $dataView = [], array $emailConfig = [], array $attach = [])
    {
        try {
            Mail::send(new SendMail($view, $dataView, $emailConfig, $attach));
        } catch (\Throwable $e) {
            vncore_report("Sendmail view: " . $view . PHP_EOL . $e->getMessage());
        }
    }
}


/**
 * Send email reset password
 */
if (!function_exists('vncore_admin_sendmail_reset_notification') && !in_array('vncore_admin_sendmail_reset_notification', config('vncore_functions_except', []))) {
    function vncore_admin_sendmail_reset_notification(string $token, string $emailReset)
    {
        $url = vncore_route_admin('admin.password_reset', ['token' => $token]);
        $dataView = [
            'title' => vncore_language_render('email.forgot_password.title'),
            'reason_sendmail' => vncore_language_render('email.forgot_password.reason_sendmail'),
            'note_sendmail' => vncore_language_render('email.forgot_password.note_sendmail', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]),
            'note_access_link' => vncore_language_render('email.forgot_password.note_access_link', ['reset_button' => vncore_language_render('email.forgot_password.reset_button'), 'url' => $url]),
            'reset_link' => $url,
            'reset_button' => vncore_language_render('email.forgot_password.reset_button'),
        ];

        $config = [
            'to' => $emailReset,
            'subject' => vncore_language_render('email.forgot_password.reset_button'),
        ];

        vncore_send_mail(config('vncore-config.admin.path_view').'::email.forgot_password', $dataView, $config, $dataAtt = []);
    }
}
