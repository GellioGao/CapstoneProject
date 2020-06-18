<?php

use Illuminate\Testing\TestResponse;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return Illuminate\Testing\TestResponse
     */
    private $testHelper;

    /**
     * Creates the mock logger.
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface
     */
    protected $mockLogger;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpLogger();

        $this->setUpTokenWorker();

        $this->setUpRepositories();

        $this->initialise();
    }

    protected function setUpLogger()
    {
        $this->mockLogger = Mockery::spy('App\Contracts\ILogger');
        $this->app->instance('App\Contracts\ILogger', $this->mockLogger);
    }

    protected function setUpTokenWorker($setUpMock = null, $useMock = null)
    {
        $this->mock('App\Contracts\ITokenDecoder', function ($mock) use ($setUpMock) {
            $data = new stdClass();
            $data->MemberID = 12345;
            $data->EmailAddress  = 'a@b.com';

            $mock->shouldReceive('decode')
                ->with('token_example')
                ->andReturn((array) json_decode(json_encode([
                    "iss" => 'iss',
                    "aud" => 'aud',
                    "iat" => 'iat',
                    "nbf" => 'nbf',
                    "exp" => 'exp',
                    "data" => [
                        'MemberID' => 12345,
                        'EmailAddress'  => 'a@b.com'
                    ]
                ])));
            if (isset($setUpMock)) {
                $setUpMock($mock);
            }
        }, $useMock);
    }

    protected function setUpRepositories()
    {
    }

    protected function initialise()
    {
        $this->testHelper = new TestResponse(null);
    }

    protected function mock($abstract, $setUpMock = null, $useMock = null)
    {
        $mock = Mockery::mock($abstract);
        if (isset($setUpMock) && is_callable($setUpMock)) {
            $setUpMock($mock);
        }

        if (isset($useMock) && is_callable($useMock)) {
            $useMock($mock);
        }

        $this->app->instance($abstract, $mock);
    }

    protected function assertJsonStructure($jsonStructure, $jsonArray)
    {
        $this->testHelper->assertJsonStructure($jsonStructure, $jsonArray);
    }

    protected function callFunction($instance, $method, $arg = null)
    {
        if (method_exists($instance, $method)) {
            if ($arg) {
                return $instance->$method($arg);
            }
            return $instance->$method();
        } else {
            return null;
        }
    }

    /// Response Resources Input Data
    protected function makeResponseResourceInputDataForMember()
    {
        $data = new stdClass();
        $data->Id = null;
        $data->Title = null;
        $data->FirstNames = null;
        $data->MiddleNames = null;
        $data->LastNames = null;
        $data->KnownAs = null;
        $data->MailName = null;
        $data->DOB = null;
        $data->PhotoFileName = null;
        $data->Address = $addressTemp = new stdClass();
        $addressTemp->Id = null;
        $addressTemp->Active = null;
        $addressTemp->StartDate = null;
        $addressTemp->EndDate = null;
        $addressTemp->Building = null;
        $addressTemp->Street = null;
        $addressTemp->TownCity = null;
        $addressTemp->PostCode = null;
        $addressTemp->Country = null;
        $data->History = [$historyTemp = new stdClass()];
        $historyTemp->Id = null;
        $historyTemp->Member_ID = null;
        $historyTemp->Group_ID = null;
        $historyTemp->Member_End_Reason_ID = null;
        $historyTemp->StartDate = null;
        $historyTemp->EndDate = null;
        $historyTemp->Capitation = null;
        $historyTemp->Notes = null;
        $historyTemp->LastModifiedDate = null;
        $historyTemp->LastModifiedBy = null;
        $historyTemp->EndReason = $endReasonTemp = new stdClass();
        $endReasonTemp->Id = null;
        $endReasonTemp->Name = null;
        $endReasonTemp->Description = null;
        $endReasonTemp->LastModifiedDate = null;
        $endReasonTemp->LastModifiedBy = null;
        return $data;
    }

    /// Repositories Input Data
    protected function makeRepositoryInputDataForMember()
    {
        $data = new stdClass();
        $data->id = null;
        $data->Title = null;
        $data->FirstNames = null;
        $data->MiddleNames = null;
        $data->LastNames = null;
        $data->KnownAs = null;
        $data->MailName = null;
        $data->DOB = null;
        $data->PhotoFileName = null;
        $data->Address = $addressTemp = new stdClass();
        $addressTemp->id = null;
        $addressTemp->Active = null;
        $addressTemp->StartDate = null;
        $addressTemp->EndDate = null;
        $addressTemp->Building = null;
        $addressTemp->Street = null;
        $addressTemp->TownCity = null;
        $addressTemp->PostCode = null;
        $addressTemp->Country = null;
        $data->history = [$historyTemp = new stdClass()];
        $historyTemp->id = null;
        $historyTemp->Member_ID = null;
        $historyTemp->Group_ID = null;
        $historyTemp->Member_End_Reason_ID = null;
        $historyTemp->StartDate = null;
        $historyTemp->EndDate = null;
        $historyTemp->Capitation = null;
        $historyTemp->Notes = null;
        $historyTemp->LastModifiedDate = null;
        $historyTemp->LastModifiedBy = null;
        $historyTemp->endReason = $endReasonTemp = new stdClass();
        $endReasonTemp->id = null;
        $endReasonTemp->Name = null;
        $endReasonTemp->Description = null;
        $endReasonTemp->LastModifiedDate = null;
        $endReasonTemp->LastModifiedBy = null;
        return $data;
    }
}
