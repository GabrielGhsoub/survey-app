<?php
namespace App\Strategies\QuestionTypes;

class NumberStrategy implements QuestionTypeStrategy
{
    public function aggregateData(array $surveyData)
    {
        $total = 0;
        $count = 0;

        foreach ($surveyData as $data) {
            if (isset($data['answer']) && is_numeric($data['answer'])) {
                $total += $data['answer'];
                $count++;
            }
        }

        return $count > 0 ? $total / $count : 0;
    }
}

