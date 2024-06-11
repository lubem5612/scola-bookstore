<?php


namespace Transave\ScolaBookstore\Actions\PaymentDetail;


use Transave\ScolaBookstore\Actions\BaseAction;
use Transave\ScolaBookstore\Http\Models\PaymentDetail;

class DeletePaymentDetail extends BaseAction
{
    private PaymentDetail $paymentDetail;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->setPaymentDetail();
        $this->setDefaultPayment();
        return $this->deletePaymentDetail();
    }

    public function setValidationRules(): array
    {
        return [
            "payment_detail_id" => "required|exists:addresses,id"
        ];
    }

    private function setPaymentDetail()
    {
        $this->paymentDetail = PaymentDetail::query()->find($this->validatedData['payment_detail_id']);
    }

    private function setDefaultPayment()
    {
        if ($this->paymentDetail->is_default) {
            PaymentDetail::query()
                ->where('user_id', $this->paymentDetail->user_id)
                ->whereNot('id', $this->validatedData['payment_detail_id'])
                ->first()->update(['is_default', 1]);
        }
    }

    private function deletePaymentDetail()
    {
        PaymentDetail::destroy($this->validatedData['payment_detail_id']);
        return $this->sendSuccess(null, 'payment details deleted successfully');
    }
}