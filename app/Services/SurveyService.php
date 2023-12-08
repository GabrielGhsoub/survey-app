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

    public function getAggregatedDataForSurvey($surveyId)
    {
        $surveyData = $this->dataMapper->getSurveyById($surveyId);

        if ($surveyData === null) {
            return null;
        }

        $questions = $surveyData['questions']; // Assuming the questions are under the 'questions' key

        $aggregatedData = [];
        foreach ($questions as $data) {
            if (isset($data['type'])) {
                $strategy = $this->getStrategy($data['type']);
                if ($strategy) {
                    $aggregatedData[] = $strategy->aggregateData($data);
                }
            }
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
