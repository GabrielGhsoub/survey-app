<?php

namespace Tests\Unit;

use App\Mappers\SurveyDataMapper;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SurveyDataMapperTest extends TestCase
{
    protected $surveyDataMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->surveyDataMapper = new SurveyDataMapper();
    }

    public function testGetAllSurveys()
    {
        Storage::fake('local');

        // Sample survey data
        $sampleSurvey = [
            "survey" => [
                "name" => "Paris",
                "code" => "XX1"
            ],
            "questions" => [
                [
                    "type" => "qcm",
                    "label" => "What best sellers are available in your store?",
                    "options" => ["Product 1", "Product 2", "Product 3", "Product 4", "Product 5", "Product 6"],
                    "answer" => [false, true, true, false, true, false]
                ],
                [
                    "type" => "numeric",
                    "label" => "Number of products?",
                    "options" => null,
                    "answer" => 670
                ]
            ]
        ];

        // Creating a fake JSON file in the fake storage
        Storage::disk('local')->put('data/survey1.json', json_encode($sampleSurvey));

        // Call the method
        $surveys = $this->surveyDataMapper->getAllSurveys();

        // Assertions
        $this->assertIsArray($surveys);
        $this->assertCount(1, $surveys);
        $this->assertEquals('Paris', $surveys[0]['survey']['name']);
        $this->assertEquals('XX1', $surveys[0]['survey']['code']);
        $this->assertEquals('qcm', $surveys[0]['questions'][0]['type']);
        $this->assertEquals('numeric', $surveys[0]['questions'][1]['type']);
    }

    public function testGetSurveyById()
    {
        $sampleSurveyData = [
            [
                "survey" => ["name" => "Paris", "code" => "XX1"],
                "questions" => [
                    ["type" => "qcm", "label" => "Best sellers", "options" => ["Product 1", "Product 2"], "answer" => [false, true]],
                    ["type" => "numeric", "label" => "Number of products?", "options" => null, "answer" => 670]
                ]
            ],
        ];

        $this->mock(SurveyDataMapper::class, function ($mock) use ($sampleSurveyData) {
            $mock->shouldReceive('getAllSurveys')->andReturn($sampleSurveyData);
        });

        $survey = $this->surveyDataMapper->getSurveyById('XX1');
        $this->assertIsArray($survey);
        $this->assertEquals('Paris', $survey['survey']['name']);
        $this->assertEquals('XX1', $survey['survey']['code']);
    }

    public function testGetSurveysByType()
    {
        $sampleSurveyData = [
            [
                "survey" => ["name" => "Paris", "code" => "XX1"],
                "questions" => [
                    ["type" => "qcm", "label" => "Best sellers", "options" => ["Product 1", "Product 2"], "answer" => [false, true]],
                    ["type" => "numeric", "label" => "Number of products?", "options" => null, "answer" => 670]
                ]
            ],
        ];

        $this->mock(SurveyDataMapper::class, function ($mock) use ($sampleSurveyData) {
            $mock->shouldReceive('getAllSurveys')->andReturn($sampleSurveyData);
        });

        $surveys = $this->surveyDataMapper->getSurveysByType('qcm');
        $this->assertIsArray($surveys);

    }

    public function testGetQuestionTypes()
    {
        $sampleSurveyData = [
            [
                "survey" => ["name" => "Paris", "code" => "XX1"],
                "questions" => [
                    ["type" => "qcm", "label" => "Best sellers", "options" => ["Product 1", "Product 2"], "answer" => [false, true]],
                    ["type" => "numeric", "label" => "Number of products?", "options" => null, "answer" => 670]
                ]
            ],
        ];

        $this->mock(SurveyDataMapper::class, function ($mock) use ($sampleSurveyData) {
            $mock->shouldReceive('getAllSurveys')->andReturn($sampleSurveyData);
        });

        $types = $this->surveyDataMapper->getQuestionTypes();
        $this->assertIsArray($types);
        $this->assertContains('qcm', $types);
        $this->assertContains('numeric', $types);
    }

}
