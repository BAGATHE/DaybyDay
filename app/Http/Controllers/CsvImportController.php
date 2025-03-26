<?php
namespace App\Http\Controllers;

use App\Services\import\MasterService;
use App\Services\import\ProjectClientService;
use App\Services\import\ProjectTaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;

class CsvImportController extends Controller
{
    protected $projectClientService;
    protected $projectTaskService;
    protected $masterService;
    public function __construct(ProjectClientService $projectClientService, ProjectTaskService $projectTaskService,MasterService $masterService)
    {
        $this->projectClientService = $projectClientService;
        $this->projectTaskService = $projectTaskService;
        $this->masterService = $masterService;
    }



    public function index()
    {
        return view('import.projects.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'importation_1' => 'required|mimes:csv,txt|max:102400',
            'importation_2' => 'nullable|mimes:csv,txt|max:102400',
        ]);

        $errors = [];

        // Traitement du premier fichier
        $importation1File = $request->file('importation_1');
        $importation1Result = $this->projectClientService->importProjectClient($importation1File->getPathname(), $importation1File->getClientOriginalName());

        if ($importation1Result['status'] === 'error') {
            $errors = array_merge($errors, $importation1Result['errors']);
        }

        $importation2Result = array();
        // Traitement du deuxième fichier (optionnel)
        if ($request->hasFile('importation_2')) {
            $importation2File = $request->file('importation_2');
            $importation2Result = $this->projectTaskService->importProjectTask(
                $importation2File->getPathname(),
                $importation2File->getClientOriginalName()
            );

            if ($importation2Result['status'] === 'error') {
                $errors = array_merge($errors, $importation2Result['errors']);
            }
        }

        // Traitement du troisieme fichier (optionnel)
        $importation3Result = array();
        if ($request->hasFile('importation_3')) {
            $importation3File = $request->file('importation_3');
            $importation3Result = $this->masterService->importMaster(
                $importation3File->getPathname(),
                $importation3File->getClientOriginalName()
            );

            if ($importation3Result['status'] === 'error') {
                $errors = array_merge($errors, $importation3Result['errors']);
            }
        }



        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors(new MessageBag($errors));
        }else{
            DB::beginTransaction();
            try {
                $this->projectClientService->generateProjectAndClient($importation1Result['data']);
                $this->projectTaskService->generateTaskProject($importation2Result['data']);
                $this->masterService->generateMasterData($importation3Result['data']);
                DB::commit();
                return redirect()->back()->with('success', 'Importation réussie !');
            }catch (\Exception $exception){
                DB::rollBack();
                Log::error("Erreur lors de la création du projet : " . $exception->getMessage());
                return redirect()->back()->with('error', "Échec de l'importation : " . $exception->getMessage());
            }
        }
    }
}