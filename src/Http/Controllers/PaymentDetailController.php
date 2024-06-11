<?php


namespace Transave\ScolaBookstore\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\PaymentDetail\CreatePaymentDetail;
use Transave\ScolaBookstore\Actions\PaymentDetail\DeletePaymentDetail;
use Transave\ScolaBookstore\Actions\PaymentDetail\SearchPaymentDetails;
use Transave\ScolaBookstore\Actions\PaymentDetail\UpdatePaymentDetail;
use Transave\ScolaBookstore\Http\Models\PaymentDetail;

class PaymentDetailController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        return (new SearchPaymentDetails(PaymentDetail::class, ['user']))->execute();
    }

    public function show($id)
    {
        return (new SearchPaymentDetails(PaymentDetail::class, ['user'], $id))->execute();
    }

    public function store(Request $request)
    {
        return (new CreatePaymentDetail($request->all()))->execute();
    }

    public function update(Request $request, $id)
    {
        return (new UpdatePaymentDetail($request->merge(['payment_detail_id' => $id])->all()))->execute();
    }

    public function destroy($id)
    {
        return (new DeletePaymentDetail(['payment_detail_id' => $id]))->execute();
    }
}