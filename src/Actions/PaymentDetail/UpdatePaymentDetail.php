<?php


namespace Transave\ScolaBookstore\Actions\PaymentDetail;


use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Actions\BaseAction;
use Transave\ScolaBookstore\Http\Models\PaymentDetail;

class UpdatePaymentDetail extends BaseAction
{
    private ?PaymentDetail $paymentDetail;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->setPaymentDetail();
        $this->setDefault();
        return $this->updatePaymentDetail();
    }

    public function setValidationRules(): array
    {
        return [
            'payment_detail_id' => 'required|exists:payment_details,id',
            'account_number' => 'sometimes|required',
            'account_name' => 'sometimes|required|string|max:80',
            'account_status' => 'sometimes|required|in:active|inactive',
            'bank_name' => 'sometimes|required|string',
            'bank_code' => 'sometimes|required|string',
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

    private function setPaymentDetail()
    {
        $this->paymentDetail = PaymentDetail::query()->find($this->validatedData['payment_detail_id']);
    }

    private function updatePaymentDetail()
    {
        $this->paymentDetail->fill($this->validatedData)->save();
        return $this->sendSuccess($this->paymentDetail->refresh()->load('user'), 'payment detail updated');
    }
}