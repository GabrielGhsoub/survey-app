<?php
namespace App\Strategies\QuestionTypes;

class NumberStrategy implements QuestionTypeStrategy
{
    public function aggregateData(array $surveyData)
    {
        
        return $surveyData;
    }
}
