<?php
namespace App\Http\Controllers;

use App\Services\SurveyService;

class SurveyController extends Controller
{

    protected $surveyService;

    public function __construct(SurveyService $surveyService)
    {
        $this->surveyService = $surveyService;
    }

    public function getSurveyData($id)
    {
        $data = $this->surveyService->getAggregatedDataForSurvey($id);
        return response()->json($data);
    }

    public function listAllSurveys()
    {
        $surveys = $this->surveyService->getAllSurveys();
        return response()->json($surveys);
    }


}