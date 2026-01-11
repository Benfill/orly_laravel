<?php

namespace App\Services;

use App\Models\Payment;

class PaymentService
{
    public function getAllPayments()
    {
        return Payment::all();
    }

    public function getPaymentById($id)
    {
        return Payment::find($id);
    }

    public function createPayment(array $data)
    {
        return Payment::create($data);
    }

    public function updatePayment($id, array $data)
    {
        $payment = Payment::find($id);
        if ($payment) {
            $payment->update($data);
            $payment->refresh();
            return $payment;
        }
        return null;
    }

    public function deletePayment($id)
    {
        $payment = Payment::find($id);
        if ($payment) {
            $payment->delete();
            return true;
        }
        return false;
    }
}
