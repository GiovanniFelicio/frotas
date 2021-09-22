<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/17/19
 * Time: 9:25 AM
 */
namespace App\Helpers;
use Request;
use App\LogActitvity;

class LogActivity
{
    public static function addToLog($subject)
    {
        $log = [];
        $log['subject'] = $subject;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['ip'] = Request::ip();
        $log['agent'] = Request::header('user-agent');
        $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
        LogActitvity::create($log);
    }
    public static function logActivityLists()
    {
        return LogActitvity::latest()->get();
    }
}
