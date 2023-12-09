<?php

namespace Tests\Unit;

use App\Mappers\SurveyDataMapper;
use App\Services\SurveyService;
use PHPUnit\Framework\TestCase;

class SurveyServiceTest extends TestCase
{
    protected $dataMapperMock;
    protected $surveyService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dataMapperMock = $this->createMock(SurveyDataMapper::class);
        $this->surveyService = new SurveyService($this->dataMapperMock);
    }

    public function testGetAggregatedDataForSurvey()
    {
        $sampleSurveys = [
            [
                'survey' => ['name' => 'Survey 1', 'code' => 'SC001'],
                'questions' => [
                    ['type' => 'qcm', 'options' => ['Option 1', 'Option 2'], 'answer' => [true, false]],
                    ['type' => 'numeric', 'answer' => 100]
                ]
            ],
            [
                'survey' => ['name' => 'Survey 2', 'code' => 'SC001'],
                'questions' => [
                    ['type' => 'qcm', 'options' => ['Option 1', 'Option 3'], 'answer' => [false, true]],
                    ['type' => 'numeric', 'answer' => 200]
                ]
            ]
        ];

        $this->dataMapperMock
            ->method('getSurveysByCode')
            ->with('SC001')
            ->willReturn($sampleSurveys);

        $aggregatedData = $this->surveyService->getAggregatedDataForSurvey('SC001');

        $this->assertEquals(1, $aggregatedData['qcm']['Option 1']);
        $this->assertEquals(0, $aggregatedData['qcm']['Option 2']);
        $this->assertEquals(1, $aggregatedData['qcm']['Option 3']);
        $this->assertEquals(150, $aggregatedData['numeric']);

    }

}
