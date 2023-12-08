<?php

namespace App\Mappers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SurveyDataMapper
{
    public function getAllSurveys()
    {
        $surveys = [];
        $directory = storage_path('app/data');

        $files = scandir($directory);
        Log::info("Files in directory:", ['files' => $files]);

        foreach ($files as $file) {
            if (substr($file, -5) !== '.json') {
                continue;
            }

            $path = $directory . DIRECTORY_SEPARATOR . $file;
            Log::info("Processing file:", ['path' => $path]);

            $json = file_get_contents($path);
            $surveyData = json_decode($json, true);

            if (!$surveyData) {
                Log::error("JSON decode error: " . json_last_error_msg(), ['path' => $path]);
                continue;
            }

            $surveys[] = $surveyData;
        }

        return $surveys;
    }
    public function getSurveyById($id)
    {
        $allSurveys = $this->getAllSurveys();
        foreach ($allSurveys as $surveyData) {
            if (isset($surveyData['survey']['code']) && $surveyData['survey']['code'] == $id) {
                return $surveyData;
            }
        }
        return null;
    }


    public function getSurveysByType($type)
    {
        $filteredSurveys = [];
        $allSurveys = $this->getAllSurveys();
        foreach ($allSurveys as $survey) {
            foreach ($survey['questions'] as $question) {
                if ($question['type'] == $type) {
                    $filteredSurveys[] = $survey;
                    break;
                }
            }
        }
        return $filteredSurveys;
    }


    public function getQuestionTypes()
    {
        $types = [];
        $allSurveys = $this->getAllSurveys();
        foreach ($allSurveys as $survey) {
            foreach ($survey['questions'] as $question) {
                $types[$question['type']] = true;
            }
        }
        return array_keys($types);
    }

}
