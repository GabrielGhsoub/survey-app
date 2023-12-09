<?php
namespace App\Strategies\QuestionTypes;

class QcmStrategy implements QuestionTypeStrategy
{
    public function aggregateData(array $surveyData)
    {
        $optionCounts = [];

        foreach ($surveyData as $data) {
            if (
                isset($data['options']) && is_array($data['options']) &&
                isset($data['answer']) && is_array($data['answer'])
            ) {
                foreach ($data['options'] as $index => $option) {
                    if (!isset($optionCounts[$option])) {
                        $optionCounts[$option] = 0;
                    }
                    if (isset($data['answer'][$index]) && $data['answer'][$index]) {
                        $optionCounts[$option]++;
                    }
                }
            }
        }

        return $optionCounts;
    }
}

