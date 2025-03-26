<?php

namespace App\Services\Exception;

use Illuminate\Support\Facades\Log;

class ExceptionService
{
    public static function logErrors(string $filePath,$errors)
    {
        Log::error("Erreurs lors de l'importation du fichier : $filePath", $errors);
    }

}