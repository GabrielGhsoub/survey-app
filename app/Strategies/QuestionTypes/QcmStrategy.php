<?php
namespace App\Strategies\QuestionTypes;

class QcmStrategy implements QuestionTypeStrategy
{
    public function aggregateData(array $surveyData)
    {
        return $surveyData;
    }
}
