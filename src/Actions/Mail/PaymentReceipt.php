<?php

namespace Transave\ScolaBookstore\Actions\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Transave\ScolaBookstore\Http\Models\Order;


class PaymentReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $totalAmount;


    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->totalAmount = $order->orderItems->sum('total_amount');
    }


    public function build()
    {
        $subject = 'Receipt for Order #' . $this->order->id;

        return $this->subject($subject)
            ->view('scola-bookstore:: emails.receipt')
            ->attachData($this->generatePdf(), 'Receipt.pdf', [
                'mime' => 'application/pdf',
            ]);
    }



    protected function generatePdf()
    {
        $pdf = \PDF::loadView('scola-bookstore::emails.receipt');
        return $pdf->output();
    }
}