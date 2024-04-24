<?php


namespace Transave\ScolaBookstore\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Support\Str;
use Transave\ScolaBookstore\Helpers\ResponseHelper;

class ConfigController extends Controller
{
    use ResponseHelper;
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function paystack()
    {
        try {
            $data['paystack_reference'] = 'bookstore-'.Carbon::now()->format('YmdHi').'-'.strtolower(Str::random(6));
            $data['paystack_secret_key'] = config('raadaa.paystack.secret_key');
            $data['paystack_public_key'] = config('raadaa.paystack.public_key');
            $data['paystack_base_url'] = config('raadaa.paystack.base_url');

            return $this->sendSuccess($data, 'paystack merchant details retrieved');
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }
}