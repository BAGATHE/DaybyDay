<?php

namespace App\Services\import;

use App\DTOs\MasterDto;
use App\Enums\OfferStatus;
use App\Models\Client;
use App\Services\client\ClientService;
use App\Services\generator\Generator;
use App\Services\Invoice\InvoiceService;
use App\Services\Lead\LeadService;
use App\Services\Offer\OfferService;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class MasterService
{
    protected $clientService;
    protected $leadService;
    protected $productService;
    protected $offerService;
    protected $invoiceService;

    private const OFFER = 'offers';
    private const INVOICE = 'invoice';
    public function __construct(ClientService $clientService,LeadService $leadService,ProductService  $productService,OfferService $offerService,InvoiceService  $invoiceService)
    {
        $this->clientService = $clientService;
        $this->leadService = $leadService;
        $this->productService = $productService;
        $this->offerService = $offerService;
        $this->invoiceService = $invoiceService;
    }
    private  $errors = [];

    public function importMaster(string $filePath, string $fileName)
    {
        $data =array();
        if (!file_exists($filePath)) {
            throw new \Exception("Le fichier '$filePath' est introuvable.");
        }

        $file = fopen($filePath, 'r');
        $lineNumber = 0;

        // Ignorer la première ligne (en-tête)
        fgetcsv($file);

        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            $lineNumber++;

            try {
                $dto = new MasterDto(
                    $row[0],
                    $row[1],
                    $row[2],
                    $row[3],
                    $row[4],
                    $row[5]);
                $data[] = $dto;



            } catch (\InvalidArgumentException $e) {
                $this->errors[] = "Fichier : $fileName | Ligne $lineNumber : " . $e->getMessage();
            }
        }

        fclose($file);

        // Vérification finale des erreurs
        if (!empty($this->errors)) {
            $this->logErrors($fileName);
            return [
                'status' => 'error',
                'message' => 'Des erreurs ont été détectées.',
                'errors' => $this->errors
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Importation réussie.',
            'data' =>$data,
        ];
    }

    private function logErrors(string $fileName)
    {
        Log::error("Erreurs lors de l'importation du fichier : $fileName", $this->errors);
    }

    public function generateMasterData($masterDTOs)
    {
        foreach ($masterDTOs as $masterDTO) {
            $client = Client::where('company_name','like',$masterDTO->getClientName())->first();

            $lead = $this->leadService->findOrCreateLead($masterDTO->getLeadTitle(),$client->id);

            $product = $this->productService->findOrCreateProduct($masterDTO->getProduit(),$masterDTO->getPrix());

            $offer = $this->offerService->generateOffre($lead->id,$client->id,OfferStatus::inProgress());

            if($masterDTO->getType()==self::OFFER){
                $this->invoiceService->generateInvoiceLinesForOffers($offer->id,$product->id,$lead->id,$masterDTO->getPrix(),$masterDTO->getQuantite());
            }

            if($masterDTO->getType()==self::INVOICE){
                $this->invoiceService->generateInvoiceLinesForOffers($offer->id,$product->id,$lead->id,$masterDTO->getPrix(),$masterDTO->getQuantite());

                $invoice = $this->invoiceService->generateInvoice($client->id,$lead->id,$offer->id);

                $this->invoiceService->generateInvoiceLinesForInvoices($invoice->id,$product->id,$lead->id,$masterDTO->getPrix(),$masterDTO->getQuantite());
                $offer->status = OfferStatus::won();
                $offer->save();
            }


        }
    }
}