<?php

use App\Exceptions\{
    DatabaseException
};

include_once __DIR__ . '/../../app/Http/Resources/Constants.php';

abstract class RepositoriesTestCase extends TestCase
{
    protected $mockRepositoryName;

    /**
     * Repositories test helper.
     *
     * @param  array  $args The args must contains: [\
     *                          modelName,\
     *                          modelFunctionName,\
     *                          repositoryFunctionName,\
     * ]\
     *                          Options: \
     *                          isArray\
     *                          modelFunctionExpectArgument\
     *                          repositoryFunctionParameter
     * @return void
     */
    protected function repositoryReturnNull($args)
    {
        $args['modelReturnData'] = null;
        if (array_key_exists('isArray', $args) && $args['isArray'] === true) {
            $args['expect'] = 0;
        } else {
            $args['expect'] = null;
        }
        $actual = $this->repositoryTestHelper($args);

        if (array_key_exists('isArray', $args) && $args['isArray'] === true) {
            $this->assertEquals($args['expect'], count($actual));
        } else {
            $this->assertEquals($args['expect'], $actual);
        }
    }

    /**
     * Repositories test helper.
     *
     * @param  array  $args The args must contains: [\
     *                          modelName,\
     *                          modelFunctionName,\
     *                          modelReturnData,\
     *                          repositoryFunctionName,\
     *                          expect \
     * ]\
     *                          Options: \
     *                          modelFunctionExpectArgument\
     *                          repositoryFunctionParameter
     * @return void
     */
    protected function allShouldSame($args)
    {
        $actual = $this->repositoryTestHelper($args);

        $expectJson = json_encode($args['expect']);
        $actualJson = json_encode($actual);
        $this->assertEquals($expectJson, $actualJson);
    }

    /**
     * Repositories test helper.
     *
     * @param  array  $args The args must contains: [\
     *                          modelName,\
     *                          modelFunctionName,\
     *                          modelReturnData,\
     *                          repositoryFunctionName,\
     *                          expect \
     * ]\
     *                          Options: \
     *                          modelFunctionExpectArgument\
     *                          repositoryFunctionParameter
     * @return mixed
     */
    protected function repositoryTestHelper($args)
    {
        $mock = Mockery::spy($args['modelName']);
        $this->app->instance($args['modelName'], $mock);
        if (array_key_exists('modelFunctionExpectArgument', $args)) {
            if (is_array($args['modelFunctionExpectArgument'])) {
                $mock->shouldReceive($args['modelFunctionName'])
                    ->once()
                    ->withArgs($args['modelFunctionExpectArgument'])
                    ->andReturn($args['modelReturnData']);
            } else {
                $mock->shouldReceive($args['modelFunctionName'])
                    ->once()
                    ->with($args['modelFunctionExpectArgument'])
                    ->andReturn($args['modelReturnData']);
            }
        } else {
            $mock->shouldReceive($args['modelFunctionName'])
                ->once()
                ->andReturn($args['modelReturnData']);
        }
        $mockRepository = $this->app->make($this->mockRepositoryName);

        if (array_key_exists('repositoryFunctionParameter', $args)) {
            $data = $this->callFunction($mockRepository, $args['repositoryFunctionName'], $args['repositoryFunctionParameter']);
        } else {
            $data = $this->callFunction($mockRepository, $args['repositoryFunctionName']);
        }
        return $data;
    }

    /**
     * Repositories exception test helper.
     *
     * @param  array  $args The args must contains: [\
     *                          modelName,\
     *                          modelFunctionName,\
     *                          repositoryFunctionName\
     * ]\
     *                          Options: \
     *                          modelFunctionExpectArgument\
     *                          repositoryFunctionParameter
     * @return void
     */
    protected function unknownExceptionThrewByModel($args)
    {
        $str = 'From test code -- test' . $this->mockRepositoryName . '_Throw_DatabaseException';
        $args['modelFunctionThrow'] = new Exception($str);
        $args['expectException'] = DatabaseException::class;
        $args['expectExceptionMessage'] = $str;
        $this->expectExceptionFromRepository($args);
    }

    /**
     * Repositories exception test helper.
     *
     * @param  array  $args The args must contains: [\
     *                          modelName,\
     *                          modelFunctionName,\
     *                          modelFunctionThrow,\
     *                          repositoryFunctionName,\
     *                          expectException,\
     *                          expectExceptionMessage\
     * ]\
     *                          Options: \
     *                          modelFunctionExpectArgument\
     *                          repositoryFunctionParameter
     * @return void
     */
    protected function expectExceptionFromRepository($args)
    {
        $this->expectException($args['expectException']);
        $this->expectExceptionMessage($args['expectExceptionMessage']);

        $mock = Mockery::spy($args['modelName']);
        $this->app->instance($args['modelName'], $mock);
        if (array_key_exists('modelFunctionExpectArgument', $args)) {
            $mock->shouldReceive($args['modelFunctionName'])
                ->once()
                ->with($args['modelFunctionExpectArgument'])
                ->andThrow($args['modelFunctionThrow']);
        } else {
            $mock->shouldReceive($args['modelFunctionName'])
                ->once()
                ->andThrow($args['modelFunctionThrow']);
        }
        $mockRepository = $this->app->make($this->mockRepositoryName);
        if (array_key_exists('repositoryFunctionParameter', $args)) {
            $this->callFunction($mockRepository, $args['repositoryFunctionName'], $args['repositoryFunctionParameter']);
        } else {
            $this->callFunction($mockRepository, $args['repositoryFunctionName']);
        }
    }

    protected function modelShouldHave(string $modelName, array $properties)
    {
        $model = $this->app->make($modelName);

        foreach ($properties as $property) {
            $this->assertTrue($model->isFillable($property) || method_exists($model, $property), "Property is $property");
        }
    }
}
