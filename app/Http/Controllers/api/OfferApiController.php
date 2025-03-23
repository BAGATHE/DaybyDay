<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Utils\ResponseUtil;

class OfferApiController extends Controller
{
    public function index(){
        try {
            $data = Offer::with('client','source')->get();
            return ResponseUtil::responseStandard('success', ["offers" => $data]);
        }catch (\Exception $e){
            return ResponseUtil::responseStandard(
                'error',
                null,
                [
                    'code' => 500,
                    'message' => 'Erreur lors de la récupération des données : ' . $e->getMessage()
                ],
                500
            );
        }
    }
}
