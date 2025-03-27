<?php

namespace App\Http\Controllers\api;

use App\DTOs\ClientDto;
use App\DTOs\ContactDto;
use App\DTOs\InvoiceDto;
use App\DTOs\InvoiceLineDto;
use App\DTOs\LeadDto;
use App\DTOs\OfferDto;
use App\DTOs\ProjectDto;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportApiClientController extends Controller
{
    public function import(Request $request){
        DB::beginTransaction();
        $jsonData = request()->all();
        $data  = $jsonData[0];
        try {
            //copie de client
            $clientdto = new ClientDto($data['address'], $data['zipcode'], $data['city'], $data['company_name'], $data['vat'], $data['company_type'], $data['client_number'], $data['user_id'], $data['industry_id']);
           $client = $clientdto->generateClient();

            //copie de contact
            $contactdto = new ContactDto($client->company_name, $data['contacts'][0]['email'], $client->id);
            $contact = $contactdto->generateContactClient($client->id);

            //copie de projet
             $projectDto = new ProjectDto($data['projects'][0]['title'], $data['projects'][0]['description'], $data['projects'][0]['status_id'], $data['projects'][0]['user_assigned_id'], $data['projects'][0]['user_created_id'], $client->id, $data['projects'][0]['invoice_id'], $data['projects'][0]['deadline']);
             $project = $projectDto->generateProject();

            foreach ($data['leads'] as $lead) {

                //copie du lead
               $leadDto = new LeadDto($lead['title'], $lead['description'], $lead['status_id'], $lead['user_assigned_id'], $lead['client_id'], $lead['user_created_id'], $lead['qualified'], $lead['result'], $lead['deadline']);
               $instance_lead = $leadDto->generateLead();

                foreach ($lead['offers'] as $offer) {
                    //copie de l'offre
                    $offerDto = new OfferDto($offer['sent_at'], $offer['source_type'], $instance_lead->id, $instance_lead->client_id, $offer['status']);
                    $instanceoffer = $offerDto->generateOffer();

                    $invoiceOriginal = Invoice::where([
                        ['offer_id', '=', $offer['id']],
                        ['source_id', '=', $lead['id']],
                        ['client_id', '=', $data['id']]
                    ])->first();
                    //generation invoice et invoice lien si id_lead et offre n'est pas null implique status offre won donx il faut generer invoice et invoice line
                    if ($invoiceOriginal) {
                        $sentAt = date('Y-m-d', time());
                        $invoiceDto = new InvoiceDto($invoiceOriginal->status, 0, null, $sentAt, null, null, null, 'App\Models\Lead', $instance_lead->id, $client->id, $instanceoffer->id);
                        $invoice = $invoiceDto->generateInvoice();

                        $invoice_lineOriginal = InvoiceLine::where('invoice_id', $invoiceOriginal->id)->first();

                        if ($invoice_lineOriginal) {
                            $title_1 = ' offer ' . $instanceoffer->id . ' du ' . $instance_lead->title.' pas encore valider';
                            $invoice_lineDto_1 = new InvoiceLineDto($title_1, $title_1, $invoice_lineOriginal->price, null, $instanceoffer->id, $invoice_lineOriginal->type, $invoice_lineOriginal->quantity, $invoice_lineOriginal->product_id);
                            $invoice_lineDto_1->generateInvoiceLine();

                            $title_1 = ' offer ' . $instanceoffer->id . ' du ' . $instance_lead->title.'  valider';
                            $invoice_lineDto_1 = new InvoiceLineDto($title_1, $title_1, $invoice_lineOriginal->price,$invoice->id, null, $invoice_lineOriginal->type, $invoice_lineOriginal->quantity, $invoice_lineOriginal->product_id);
                            $invoice_lineDto_1->generateInvoiceLine();
                        }else{
                            $title_1 = ' offer ' . $instanceoffer->id . ' du ' . $instance_lead->title.' pas encore valider';
                            $invoice_lineDto_1 = new InvoiceLineDto($title_1, $title_1, $invoice_lineOriginal->price, null, $instanceoffer->id, $invoice_lineOriginal->type, $invoice_lineOriginal->quantity, $invoice_lineOriginal->product_id);
                            $invoice_lineDto_1->generateInvoiceLine();

                        }
                    }

                }
            }
            DB::commit();
            return ResponseUtil::responseStandard('success', ["message" => "duplication reussie"]);
            //return redirect()->back()->with('success', 'Importation réussie !');
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error("Erreur lors de la création du projet : " . $exception->getMessage());
            return ResponseUtil::responseStandard('error', ["message" => "erreur de duplication : " . $exception->getMessage()]);
            //return redirect()->back()->with('error', "Échec de l'importation : " . $exception->getMessage());
        }
    }
}
