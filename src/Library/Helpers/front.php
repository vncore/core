<?php
use Vncore\Core\Admin\Models\AdminEmailTemplate;

/**
 * Function process mapping validate contact form
 */
if (!function_exists('vncore_contact_mapping_validate') && !in_array('vncore_contact_mapping_validate', config('vncore_functions_except', []))) {
    function vncore_contact_mapping_validate():array
    {
        $validate = [
            'name' => 'required',
            'title' => 'required',
            'content' => 'required',
            'email' => 'required|email',
            'phone' => config('validation.customer.phone_required', 'required|regex:/^0[^0][0-9\-]{6,12}$/'),
        ];
        $messages = [
            'name.required'    => vncore_language_render('validation.required', ['attribute' => vncore_language_render('contact.name')]),
            'content.required' => vncore_language_render('validation.required', ['attribute' => vncore_language_render('contact.content')]),
            'title.required'   => vncore_language_render('validation.required', ['attribute' => vncore_language_render('contact.subject')]),
            'email.required'   => vncore_language_render('validation.required', ['attribute' => vncore_language_render('contact.email')]),
            'email.email'      => vncore_language_render('validation.email', ['attribute' => vncore_language_render('contact.email')]),
            'phone.required'   => vncore_language_render('validation.required', ['attribute' => vncore_language_render('contact.phone')]),
            'phone.regex'      => vncore_language_render('customer.phone_regex'),
        ];
        $dataMap['validate'] = $validate;
        $dataMap['messages'] = $messages;

        return $dataMap;
    }
}


/**
 * Send email contact form
 */
if (!function_exists('vncore_contact_form_sendmail') && !in_array('vncore_contact_form_sendmail', config('vncore_functions_except', []))) {
    function vncore_contact_form_sendmail(array $data)
    {
        if (vncore_config('contact_to_admin')) {
            $checkContent = (new AdminEmailTemplate)
                ->where('group', 'contact_to_admin')
                ->where('status', 1)
                ->first();
            if ($checkContent) {
                $content = $checkContent->text;
                $dataFind = [
                    '/\{\{\$title\}\}/',
                    '/\{\{\$name\}\}/',
                    '/\{\{\$email\}\}/',
                    '/\{\{\$phone\}\}/',
                    '/\{\{\$content\}\}/',
                ];
                $dataReplace = [
                    $data['title'],
                    $data['name'],
                    $data['email'],
                    $data['phone'],
                    $data['content'],
                ];
                $content = preg_replace($dataFind, $dataReplace, $content);
                $dataView = [
                    'content' => $content,
                ];

                $config = [
                    'to' => vncore_store('email'),
                    'replyTo' => $data['email'],
                    'subject' => $data['title'],
                ];
                vncore_send_mail('Vncore.Templates.' . vncore_store('template') . '.mail.contact_to_admin', $dataView, $config, []);
            }
        }
    }
}