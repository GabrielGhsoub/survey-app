<?php

namespace App\Mappers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SurveyDataMapper
{
    public function getAllSurveys()
    {
        $surveys = [];
        $files = Storage::disk('local')->files('data'); // 'local' is the disk, 'data' is the directory

        foreach ($files as $file) {
            if (substr($file, -5) !== '.json') {
                continue;
            }

            try {
                $json = Storage::disk('local')->get($file);
                $surveyData = json_decode($json, true);

                if (!$surveyData) {
                    Log::error("JSON decode error: " . json_last_error_msg(), ['file' => $file]);
                    continue;
                }

                $surveys[] = $surveyData;
            } catch (\Exception $e) {
                Log::error("Error reading file: " . $e->getMessage(), ['file' => $file]);
            }
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
