<?php

namespace App\Http\Controllers;

use App\Constante\Constante;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Integration;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Invoice\GenerateInvoiceStatus;
use App\Services\Invoice\InvoiceCalculator;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Payment $payment)
    {
        if (!auth()->user()->can('payment-delete')) {
            session()->flash('flash_message', __("You don't have permission to delete a payment"));
            return redirect()->back();
        }
        $api = Integration::initBillingIntegration();
        if ($api) {
            $api->deletePayment($payment);
        }

        $payment->delete();
        session()->flash('flash_message', __('Payment successfully deleted'));
        return redirect()->back();
    }

    public function addPayment(PaymentRequest $request, Invoice $invoice)
    {
        if (!$invoice->isSent()) {
            session()->flash('flash_message_warning', __("Can't add payment on Invoice"));
            return redirect()->route('invoices.show', $invoice->external_id);
        }

        //Gestion exception prix
        $invoiceCalculator = new InvoiceCalculator($invoice);
        if($invoiceCalculator->getAmountDue()->getAmount() < $request->amount * Constante::COEFFICIENT) {
            $montant = $request->amount * Constante::COEFFICIENT - $invoiceCalculator->getAmountDue()->getAmount() ;
            session()->flash('flash_message_warning', __("Le montant inserer est superieur de $montant  du  montant qui doit etre payé!!!!"));
            return redirect()->route('invoices.show', $invoice->external_id);
        }

        $payment = Payment::create([
            'external_id' => Uuid::uuid4()->toString(),
            'amount' => $request->amount * Constante::COEFFICIENT,
            'payment_date' => Carbon::parse($request->payment_date),
            'payment_source' => $request->source,
            'description' => $request->description,
            'invoice_id' => $invoice->id
        ]);
        $api = Integration::initBillingIntegration();
        if ($api && $invoice->integration_invoice_id) {
            $result = $api->createPayment($payment);
            $payment->integration_payment_id = $result["Guid"];
            $payment->integration_type = get_class($api);
            $payment->save();
        }
        app(GenerateInvoiceStatus::class, ['invoice' => $invoice])->createStatus();

        session()->flash('flash_message', __('Payment successfully added'));
        return redirect()->back();
    }
}
