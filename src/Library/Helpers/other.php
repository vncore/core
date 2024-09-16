<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Configuration\Exceptions;
/*
String to Url
 */
if (!function_exists('vncore_word_format_url') && !in_array('vncore_word_format_url', config('vncore_functions_except', []))) {
    function vncore_word_format_url($str = ""):string
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return strtolower(preg_replace(
            array('/[\s\-\/\\\?\(\)\~\.\[\]\%\*\#\@\$\^\&\!\'\"\`\;\:]+/'),
            array('-'),
            strtolower($str)
        ));
    }
}


if (!function_exists('vncore_url_render') && !in_array('vncore_url_render', config('vncore_functions_except', []))) {
    /*
    url render
     */
    function vncore_url_render($string = ""):string
    {
        $arrCheckRoute = explode('route::', $string);
        $arrCheckUrl = explode('admin::', $string);

        if (count($arrCheckRoute) == 2) {
            $arrRoute = explode('::', $string);
            if (Str::startsWith($string, 'route::admin')) {
                if (isset($arrRoute[2])) {
                    return vncore_route_admin($arrRoute[1], explode(',', $arrRoute[2]));
                } else {
                    return vncore_route_admin($arrRoute[1]);
                }
            } else {
                if (function_exists('vncore_route_front')) {
                    if (isset($arrRoute[2])) {
                        return vncore_route_front($arrRoute[1], explode(',', $arrRoute[2]));
                    } else {
                        return vncore_route_front($arrRoute[1]);
                    }
                } else {
                    if (isset($arrRoute[2])) {
                        return route($arrRoute[1], explode(',', $arrRoute[2]));
                    } else {
                        return route($arrRoute[1]);
                    }
                }

            }
        }

        if (count($arrCheckUrl) == 2) {
            $string = Str::start($arrCheckUrl[1], '/');
            $string = VNCORE_ADMIN_PREFIX . $string;
            return url($string);
        }
        return url($string);
    }
}


if (!function_exists('vncore_html_render') && !in_array('vncore_html_render', config('vncore_functions_except', []))) {
    /*
    Html render
     */
    function vncore_html_render($string)
    {
        if(!is_string($string)) {
            return $string;
        }
        $string = htmlspecialchars_decode($string);
        return $string;
    }
}

if (!function_exists('vncore_word_format_class') && !in_array('vncore_word_format_class', config('vncore_functions_except', []))) {
    /*
    Format class name
     */
    function vncore_word_format_class($word = "")
    {
        if(!is_string($word)) {
            return $word;
        }
        $word = Str::camel($word);
        $word = ucfirst($word);
        return $word;
    }
}

if (!function_exists('vncore_word_limit') && !in_array('vncore_word_limit', config('vncore_functions_except', []))) {
    /*
    Truncates words
     */
    function vncore_word_limit($word = "", int $limit = 20, string $arg = ''):string
    {
        $word = Str::limit($word, $limit, $arg);
        return $word;
    }
}

if (!function_exists('vncore_token') && !in_array('vncore_token', config('vncore_functions_except', []))) {
    /*
    Create random token
     */
    function vncore_token(int $length = 32)
    {
        $token = Str::random($length);
        return $token;
    }
}

if (!function_exists('vncore_report') && !in_array('vncore_report', config('vncore_functions_except', []))) {
    /*
    Handle report
     */
    function vncore_report($msg = "", array $ext = [])
    {
        if (is_array($msg)) {
            $msg = json_encode($msg);
        }
        $msg = vncore_time_now(config('app.timezone')).' ('.config('app.timezone').'):'.PHP_EOL.$msg.PHP_EOL;
        if (!in_array('slack', $ext)) {
            if (config('logging.channels.slack.url')) {
                try {
                    \Log::channel('slack')->emergency($msg);
                } catch (\Throwable $e) {
                    $msg .= $e->getFile().'- Line: '.$e->getLine().PHP_EOL.$e->getMessage().PHP_EOL;
                }
            }
        }
        \Log::error($msg);
    }
}


if (!function_exists('vncore_handle_exception') && !in_array('vncore_handle_exception', config('vncore_functions_except', []))) {
    /*
    Process msg exception
     */
    function vncore_handle_exception(\Throwable $exception)
    {
        $msg = "```". $exception->getMessage().'```'.PHP_EOL;
        $msg .= "```IP:```".request()->ip().PHP_EOL;
        $msg .= "*File* `".$exception->getFile()."`, *Line:* ".$exception->getLine().", *Code:* ".$exception->getCode().PHP_EOL.'URL= '.url()->current();
        if (function_exists('vncore_report') && $msg) {
            vncore_report(msg:$msg);
        }
    }
}


if (!function_exists('vncore_push_include_view') && !in_array('vncore_push_include_view', config('vncore_functions_except', []))) {
    /**
     * Push view
     *
     * @param   [string]  $position
     * @param   [string]  $pathView
     *
     */
    function vncore_push_include_view($position = "", string $pathView = "")
    {
        $includePathView = config('vncore_include_view.'.$position, []);
        $includePathView[] = $pathView;
        config(['vncore_include_view.'.$position => $includePathView]);
    }
}


if (!function_exists('vncore_push_include_script') && !in_array('vncore_push_include_script', config('vncore_functions_except', []))) {
    /**
     * Push script
     *
     * @param   [string]  $position
     * @param   [string]  $pathScript
     *
     */
    function vncore_push_include_script($position, $pathScript)
    {
        $includePathScript = config('vncore_include_script.'.$position, []);
        $includePathScript[] = $pathScript;
        config(['vncore_include_script.'.$position => $includePathScript]);
    }
}


/**
 * convert datetime to date
 */
if (!function_exists('vncore_datetime_to_date') && !in_array('vncore_datetime_to_date', config('vncore_functions_except', []))) {
    function vncore_datetime_to_date($datetime, $format = 'Y-m-d')
    {
        if (empty($datetime)) {
            return null;
        }
        return  date($format, strtotime($datetime));
    }
}


if (!function_exists('admin') && !in_array('admin', config('vncore_functions_except', []))) {
    /**
     * Admin login information
     */
    function admin()
    {
        return auth()->guard('admin');
    }
}

if (!function_exists('vncore_time_now') && !in_array('vncore_time_now', config('vncore_functions_except', []))) {
    /**
     * Return object carbon
     */
    function vncore_time_now($timezone = null)

    {
        return (new \Carbon\Carbon)->now($timezone);
    }
}

if (!function_exists('vncore_request') && !in_array('vncore_request', config('vncore_functions_except', []))) {
    /**
     * Return object carbon
     */
    function vncore_request($key = "", $default = "", string $type = "")
    {

        if ($type == 'string') {
            if (is_array(request($key, $default))) {
                return 'array';
            }
        }

        if ($type == 'array') {
            if (is_string(request($key, $default))) {
                return [request($key, $default)];
            }
        }

        return request($key, $default);
    }
}
