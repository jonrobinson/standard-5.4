<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

use App\Helpers\Responder;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $required_error_message;

    public function success($data = [], string $message = '') 
    {
        return Responder::success($data, $message);
    }

    public function error($data = [], string $message = '', $responseCode = 200) 
    {
        return Responder::error($data, $message, $responseCode);
    }

    /**
     * @param Request
     * @param Array of strings matching the input to check for (i.e. ['first_name', 'last_name'])
     * @return 
     */
    protected function checkRequired(Request $request, array $params)
    {
        $items_to_validate = [];
        $required = [];
        foreach ($params as $param) {
            $items_to_validate[$param] = $request->input($param);
            $required[$param] = 'required';
        }
        $validator = Validator::make($items_to_validate, $required);
        if ($validator->fails()) {
            $this->required_error_message = $validator->errors()->first();
            return false;
        }
        return true;
    }

    public function requiredError()
    {
        return $this->error([], $this->required_error_message);
    }
}
