<?php
namespace App\Strategies\QuestionTypes;

interface QuestionTypeStrategy
{
    public function aggregateData(array $surveyData);
}
