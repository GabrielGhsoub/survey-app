<?php
namespace App\Services;

use App\Mappers\SurveyDataMapper;
use App\Strategies\QuestionTypes\QcmStrategy;
use App\Strategies\QuestionTypes\NumberStrategy;

class SurveyService
{
    protected $dataMapper;

    public function __construct(SurveyDataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    public function getAggregatedDataForSurvey($surveyCode)
    {
        $allSurveys = $this->dataMapper->getSurveysByCode($surveyCode);
        $aggregatedData = [
            'qcm' => [],
            'numeric' => 0
        ];

        $numericCount = 0;

        foreach ($allSurveys as $survey) {
            foreach ($survey['questions'] as $question) {
                if ($question['type'] === 'qcm') {
                    foreach ($question['options'] as $index => $option) {
                        if (!isset($aggregatedData['qcm'][$option])) {
                            $aggregatedData['qcm'][$option] = 0;
                        }
                        if ($question['answer'][$index]) {
                            $aggregatedData['qcm'][$option]++;
                        }
                    }
                } elseif ($question['type'] === 'numeric') {
                    $aggregatedData['numeric'] += $question['answer'];
                    $numericCount++;
                }
            }
        }

        if ($numericCount > 0) {
            $aggregatedData['numeric'] /= $numericCount;
        }

        return $aggregatedData;
    }

    protected function getStrategy($type)
    {
        switch ($type) {
            case 'qcm':
                return new QcmStrategy();
            case 'number':
                return new NumberStrategy();
            // Add more cases for other types
        }
    }

    public function getAllSurveys()
    {
        return $this->dataMapper->getAllSurveys();
    }
}
