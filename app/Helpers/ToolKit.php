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

    public static function getRoundup(int $amount)
    {
        $cents = substr($amount, -2);
        if ($cents == '00') {
            return 0;
        }
        $roundup = 100 - $cents;
        return $roundup;
    }
    
    /***************************************************************************************
     ** YODLEE
     ***************************************************************************************/

    public static function getYodleeWebhookUrl()
    {
        $endpoint = "/webhooks/yodlee/refresh";
        
        // Production Or Staging
        $url = self::getUrl($endpoint);
        if (!strpos($url, 'dev')) {
            return $url;
        }

        // local test point to staging
        return env('STAGING_URL') . $endpoint;
    }

    /**
     * GENERATE YODLEE_PASSWORD
     * Requirements:
     * (1) Min 8 characters
     * (2) No More Than 2 Recurring Characters
     */
    public static function generateYodleePassword()
    {
        // generate password
        $token = self::token(8);
        
        // (sanity check) make sure it's the min length
        if (strlen($token) < 8) {
            return self::generateYodleePassword();
        }

        // make sure there aren't more than 2 recurring characters
        $valid = true;
        $lc_token = strtolower($token);
        foreach (self::recurringStrings() as $string) {
            if (substr_count($lc_token, $string)) {
                $valid = false;
                break;
            }
        }
        if ($valid) {
            return $token;
        }

        // if invalid run again
        return self::generateYodleePassword();
    }

    public static function recurringStrings()
    {
        return [
            'aaa', 'bbb', 'ccc', 'ddd', 'eee', 'fff', 'ggg', 'hhh', 'iii', 'jjj', 'kkk', 'lll', 'mmm', 'nnn', 'ooo', 'ppp', 'qqq', 'rrr', 'sss', 'ttt', 'uuu', 'vvv', 'www', 'xxx', 'yyy', 'zzz',
            '111', '222', '333', '444', '555', '666', '777', '888', '999', '000',
        ];
    }

    public static function bankIsCentsSupported(stdClass $bankObj)
    {
        // US AND CANADA ONLY
        if (property_exists($bankObj, 'countryISOCode') && !in_array($bankObj->countryISOCode, ['US', 'CA'])) {
            return false;
        }

        // CREDITCARD, BANK, or LOAN ONLY
        $container_names = property_exists($bankObj, 'containerNames') ? $bankObj->containerNames : [];
        $supported = ['creditCard', 'bank', 'loan',];
        foreach ($supported as $name) {
            if (in_array($name, $container_names)) {
                return true;
            }
        }
        return false;
    }

    /***************************************************************************************
     ** YODLEE DATA PROCESSING
     ***************************************************************************************/

    public static function yodleeDataHasCaptcha(stdClass $data)
    {
        if (!property_exists($data, 'providerAccount') || !property_exists($data->providerAccount, 'loginForm')) {
            return false;
        }
        $loginForm = $data->providerAccount->loginForm;
        foreach ($loginForm->row as $row) {
            foreach ($row->field as $field) {
                if ($field->id == 'image' && is_array($field->image)) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function getYodleeCAPTCHA(stdClass $loginForm)
    {
        foreach ($loginForm->row as $row) {
            foreach ($row->field as $field) {
                if ($field->id == 'image' && is_array($field->image)) {
                    return $field->image;
                }
            }
        }
    }

    public static function processYodleeImage(array $imageValues)
    {
        $data = [];
        foreach ($imageValues as $value) {
            $data[] = chr($value % 256);
        }
        return base64_encode(implode($data));
    }

    /***************************************************************************************
     ** TESTING
     ***************************************************************************************/

    public static function makeYodleeUser(User $user)
    {
        $yodleeObj = DataMocker::yodleeUser();
        $password = env('YODLEE_SANDBOX_USER_1_PASSWORD');
        return Yodlee_User::makeOne($user, $yodleeObj, $password);        
    }
}
