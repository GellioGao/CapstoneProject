<?php

include_once __DIR__ . '/../app/Http/Resources/Constants.php';

use App\Exceptions\InvalidTokenException;
use App\Models\User;

class TokenValidatorTest extends TestCase
{
    public function testTokenValidatorWith_EmptyToken_Should_Throw_InvalidTokenException()
    {
        $this->expectException(InvalidTokenException::class);
        $emptyToken = '';
        $this->mock('App\Contracts\ITokenDecoder');

        $this->tokenValidatorHelper(null, $emptyToken);
    }

    public function testTokenValidatorWith_WrongToken_Should_Throw_InvalidTokenException()
    {
        $this->expectException(InvalidTokenException::class);
        $wrongToken = 'wrong_token';

        $this->mock('App\Contracts\ITokenDecoder', function ($mock) use ($wrongToken) {
            $mock->allows()->decode($wrongToken)->andThrow(new UnexpectedValueException);
        });
        $this->tokenValidatorHelper(null, $wrongToken);
    }

    public function testTokenValidatorWith_Token_CanBeDecoded_But_DecodedData_Structure_NotMatch_Should_Throw_InvalidTokenException()
    {
        $this->expectException(InvalidTokenException::class);
        $canBeDecodedToken = 'can_be_decoded_token';

        $this->mock('App\Contracts\ITokenDecoder', function ($mock) use ($canBeDecodedToken) {
            $mock->allows()->decode($canBeDecodedToken)->andReturn([]);
        });
        $this->tokenValidatorHelper(null, $canBeDecodedToken, false);
    }

    public function testTokenValidatorWith_Token_CanBeDecoded_But_DecodedData_FieldCount_NotMatch_Should_Throw_InvalidTokenException()
    {
        $this->expectException(InvalidTokenException::class);
        $canBeDecodedToken = 'can_be_decoded_token';

        $this->mock('App\Contracts\ITokenDecoder', function ($mock) use ($canBeDecodedToken) {
            $mock->allows()->decode($canBeDecodedToken)->andReturn([]);
        });

        $this->mockLogger
            ->allows()
            ->warning("The token passed in was can be decoded, but the parsed data is not as the API expected.\nDecoded data:[]")
            ->once();

        $this->tokenValidatorHelper(null, $canBeDecodedToken);
    }

    public function testTokenValidatorWith_Token_But_UserDoseNotExists_Should_Return_Null()
    {
        $token = 'token';

        config(['app.api_check_member_exists' => true]);
        $this->mock('App\Contracts\ITokenDecoder', function ($mock) use ($token) {
            $data = new stdClass();
            $data->MemberID = 1;
            $data->EmailAddress = 'email';
            $mock->allows()->decode($token)->andReturn([
                "iss" => '',
                "aud" => '',
                "iat" => '',
                "nbf" => '',
                "exp" => '',
                "data" => $data
            ]);
        });

        $this->tokenValidatorHelper(null, $token, true, false);
    }

    public function testTokenValidatorWith_Token_Should_Return_User()
    {
        $token = 'token';

        $this->mock('App\Contracts\ITokenDecoder', function ($mock) use ($token) {
            $data = new stdClass();
            $data->MemberID = 1;
            $data->EmailAddress = 'email';
            $mock->allows()->decode($token)->andReturn([
                "iss" => '',
                "aud" => '',
                "iat" => '',
                "nbf" => '',
                "exp" => '',
                "data" => $data
            ]);
        });
        $expect = json_encode(new User(['id' => 1, 'email' => 'email']));
        $this->tokenValidatorHelper($expect, $token);
    }

    private function tokenValidatorHelper($expect, $token, $isArrayStructureSame = true, $userExists = true)
    {
        $this->mock('App\Contracts\Repositories\IMemberRepository', function ($mock) use ($userExists) {
            $return = $userExists === true ? new stdClass() : null;
            $mock->allows()->one(Mockery::any())->andReturn($return);
        });

        $this->mock('App\Contracts\IArrayStructureChecker', function ($mock) use ($isArrayStructureSame) {
            $mock->allows()->checkStructure(Mockery::any(), Mockery::any())->andReturn($isArrayStructureSame);
        });

        $tokenValidator = $this->app->make('App\Contracts\ITokenValidator');
        $actual = $tokenValidator->validate($token);

        if (!is_null($expect)) {
            $actual = json_encode($actual);
        }
        $this->assertEquals($expect, $actual);
    }
}
