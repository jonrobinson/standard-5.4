<?php namespace App\Helpers;

use Log;
use stdClass;
use App\Helpers\DataMocker;
use App\Models\User;
use App\Models\Yodlee_User;

class ToolKit
{
    public static function token(int $length = 64)
    {
        return bin2hex(random_bytes($length));
    }

    public static function getURL(string $endpoint)
    {
        return env('APP_URL') . $endpoint;
    }
}
