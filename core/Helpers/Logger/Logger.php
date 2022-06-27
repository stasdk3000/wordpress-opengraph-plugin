<?php

namespace ManuteamCore\Helpers\Logger;

class Logger {

    const EMERGENCY =  'emergency';
    const ALERT =  'alert';
    const CRITICAL =  'critical';
    const ERROR =  'error';
    const WARNING =  'warning';
    const NOTICE =  'notice';
    const INFO =  'info';
    const DEBUG =  'debug';


    public static function log($event, $message)
    {
        $date = date('Y-m-d');
        $ROOT_DIRECTORY = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/manu-logs';

        $dir = $ROOT_DIRECTORY .'/'. $date;

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists($dir . '/' . $event.'.txt')) {
            $fp = fopen($dir . '/' . $event . '.txt', 'w');
            fwrite($fp, self::message_structure($message));
            fclose($fp);
        } else {
            $fp = fopen($dir . '/' . $event . '.txt', 'a');
            fwrite($fp, self::message_structure($message));
        }
    }

    public static function message_structure($message)
    {
        $log_entry  = "\n" . '====LOG START TIME: ' . date('Y-m-d H:i:s') . '====' . "\n";
        $log_message = '====Start Log ====' . "\n" . $message . "\n";
        $log_end  = "\n" . '====LOG END ====' . "\n";
        return $log_entry . $log_message . $log_end;
    }
}