<?php


namespace Transave\ScolaBookstore\Actions\PaymentDetail;


use Illuminate\Support\Arr;
use RaadaaPartners\RaadaaBase\Actions\Paystack\BankList;
use Transave\ScolaBookstore\Actions\BaseAction;
use Transave\ScolaBookstore\Http\Models\PaymentDetail;
use Transave\ScolaBookstore\Http\Models\User;

class CreatePaymentDetail extends BaseAction
{

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->setDefault();
        $this->setAccountName();
        $this->setBankName();
        return $this->createPaymentDetail();
    }

    public function setValidationRules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'account_number' => 'required',
            'account_name' => 'sometimes|required|string|max:80',
            'account_status' => 'sometimes|required|in:active|inactive',
            'bank_code' => 'required|string',
            'is_default' => 'sometimes|required|integer|in:0,1',
        ];
    }

    private function setDefault()
    {
        if (Arr::exists($this->validatedData, 'is_default') && $this->validatedData['is_default']) {
            if ($this->validatedData['is_default'] == 1) {
                PaymentDetail::query()->where('user_id', $this->validatedData['user_id'])
                    ->update(['is_default' => 0]);
            }
        }else {
            $this->validatedData['is_default'] = 0;
        }
    }

    private function setAccountName()
    {
        if (!Arr::exists($this->validatedData, 'account_name')) {
            $user = User::query()->find($this->validatedData['user_id']);
            $this->validatedData['account_name'] = $user->first_name.' '.$user->last_name;
        }
    }

    private function setBankName()
    {
        $bankList = (new BankList())->execute();
        $bankList = json_decode($bankList, true);
        $bankCollection = collect($bankList['data']);

        $bank = $bankCollection->where('code', $this->validatedData['code'])->first();
        if (empty($bank)) abort(404, 'bank not found for bank code');
        $this->validatedData['bank_name'] = $bank['name'];
    }

    private function createPaymentDetail()
    {
        $paymentDetail = PaymentDetail::query()->create($this->validatedData);
        return $this->sendSuccess($paymentDetail->load('user'), 'payment detail created');
    }
}