<?php

use App\Exceptions\{
    DatabaseException
};

include_once __DIR__ . '/../../app/Http/Resources/Constants.php';

abstract class ControllersTestCase extends TestCase
{
    protected $mockRepositoryName;
    protected $mockControllerName;

    protected function notFound_One($modelName, $repositoryFunctionName, $repositoryFunctionParameter, $controllerFunctionName, $controllerFunctionParameter = null)
    {
        $expectStatusCode = 404;
        $expectMessage = sprintf('{"result":"FAILED","access":"ALLOWED","message":"No %s data for the ID: %d.","error":null,"data":null}', $modelName, $repositoryFunctionParameter);
        $this->mock($this->mockRepositoryName, function ($mock) use ($repositoryFunctionName, $repositoryFunctionParameter) {
            $mock->shouldReceive($repositoryFunctionName)
                ->with($repositoryFunctionParameter)
                ->andReturn(null);
        });
        $mockedController = $this->app->make($this->mockControllerName);
        $response = $this->callFunction($mockedController, $controllerFunctionName, $controllerFunctionParameter);
        $this->assertEquals($expectStatusCode, $response->getStatusCode());
        $this->assertEquals($expectMessage, $response->getContent());
    }

    protected function databaseException($repositoryFunctionName, $repositoryFunctionParameter, $controllerFunctionName, $controllerFunctionParameter = null)
    {
        $expectStatusCode = 500;
        $expectMessage = '{"result":"FAILED","access":"ALLOWED","message":"Server fault was happened.","error":null,"data":null}';
        $this->mockLogger->allows()
            ->error(Mockery::type(DatabaseException::class))
            ->once();
        $this->mock($this->mockRepositoryName, function ($mock) use ($repositoryFunctionName, $repositoryFunctionParameter) {
            if ($repositoryFunctionParameter === null) {
                $mock->shouldReceive($repositoryFunctionName)
                    ->andThrow(new DatabaseException(new Exception));
            } else {
                $mock->shouldReceive($repositoryFunctionName)
                    ->with($repositoryFunctionParameter)
                    ->andThrow(new DatabaseException(new Exception));
            }
        });
        $mockedController = $this->app->make($this->mockControllerName);
        $response = $this->callFunction($mockedController, $controllerFunctionName, $controllerFunctionParameter);
        $this->assertEquals($expectStatusCode, $response->getStatusCode());
        $this->assertEquals($expectMessage, $response->getContent());
    }

    protected function unknownException($repositoryFunctionName, $repositoryFunctionParameter, $controllerFunctionName, $controllerFunctionParameter = null)
    {
        $expectStatusCode = 500;
        $expectMessage = '{"result":"FAILED","access":"ALLOWED","message":"Server fault was happened.","error":"","data":null}';
        $this->mockLogger->allows()
            ->error(Mockery::type(LogicException::class))
            ->once();
        $this->mock($this->mockRepositoryName, function ($mock) use ($repositoryFunctionName, $repositoryFunctionParameter) {
            if ($repositoryFunctionParameter === null) {
                $mock->shouldReceive($repositoryFunctionName)
                    ->andThrow(new LogicException());
            } else {
                $mock->shouldReceive($repositoryFunctionName)
                    ->with($repositoryFunctionParameter)
                    ->andThrow(new LogicException());
            }
        });
        $mockedController = $this->app->make($this->mockControllerName);
        $response = $this->callFunction($mockedController, $controllerFunctionName, $controllerFunctionParameter);
        $this->assertEquals($expectStatusCode, $response->getStatusCode());
        $this->assertEquals($expectMessage, $response->getContent());
    }


    protected function allShouldSame($data, $expectMessage, $repositoryFunctionName, $repositoryFunctionParameter, $controllerFunctionName, $controllerFunctionParameter = null)
    {
        $expectStatusCode = 200;
        $this->mock($this->mockRepositoryName, function ($mock) use ($repositoryFunctionName, $repositoryFunctionParameter, $data) {
            if ($repositoryFunctionParameter === null) {
                $mock->shouldReceive($repositoryFunctionName)
                    ->andReturn($data);
            } else {
                $mock->shouldReceive($repositoryFunctionName)
                    ->with($repositoryFunctionParameter)
                    ->andReturn($data);
            }
        });
        $mockedController = $this->app->make($this->mockControllerName);
        $response = $this->callFunction($mockedController, $controllerFunctionName, $controllerFunctionParameter);
        $this->assertEquals($expectStatusCode, $response->getStatusCode());
        $this->assertEquals($expectMessage, $response->getContent());
    }
}
