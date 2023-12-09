<?php

namespace Tests\Feature;

use App\Http\Controllers\SurveyController;
use App\Services\SurveyService;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyControllerTest extends TestCase
{
    protected $surveyServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->surveyServiceMock = $this->mock(SurveyService::class);
    }

    public function testGetSurveyData()
    {
        $mockSurveyData = ['survey' => ['id' => 1, 'name' => 'Survey 1']];

        $this->surveyServiceMock
            ->shouldReceive('getAggregatedDataForSurvey')
            ->with(1)
            ->once()
            ->andReturn($mockSurveyData);

        $response = $this->json('GET', '/api/surveys/1');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($mockSurveyData);
    }



    public function testListAllSurveys()
    {
        $mockSurveys = [['id' => 1, 'name' => 'Survey 1'], ['id' => 2, 'name' => 'Survey 2']];

        $this->surveyServiceMock
            ->shouldReceive('getAllSurveys')
            ->once()
            ->andReturn($mockSurveys);

        $response = $this->json('GET', '/api/surveys');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($mockSurveys);
    }

}
