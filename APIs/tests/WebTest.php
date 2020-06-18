<?php

use App\Exceptions\{
    InvalidTokenException,
    TokenMissingException
};
use App\Http\Resources\ResponseResources\{
    BadResponseResource,
    MemberCollectionResponseResource,
    MemberResponseResource
};

include_once __DIR__ . '/../app/Http/Resources/Constants.php';

class WebTest extends TestCase
{
    protected function setUpTokenWorker($setUpMock = null, $useMock = null)
    {
        parent::setUpTokenWorker(function ($mock) use ($setUpMock) {
            $mock->shouldReceive('decode')
                ->with('invalid_token')
                ->andReturn((array) json_decode(json_encode([
                    'wrong_field' => 'wrong',
                    "iss" => 'wrong',
                    "aud" => 'wrong',
                    "iat" => 'wrong',
                    "nbf" => 'wrong',
                    "exp" => 'wrong',
                    "data" => [
                        'MemberID' => 'wrong',
                        'EmailAddress' => 'wrong'
                    ]
                ])));

            $mock->shouldReceive('decode')
                ->with('invalid_token_wrong_number')
                ->andThrow(new UnexpectedValueException('Wrong number of segments'));

            if (isset($setUpMock)) {
                $setUpMock($mock);
            }
        }, $useMock);
    }

    /**
     * Test default router.
     *
     * @return void
     */
    public function testDefaultRouter()
    {
        $this->get('/');

        $this->assertEquals(
            API_INFO,
            $this->response->getContent()
        );
    }

    public function testMemberRouter_All_Without_Token_Should_Unauthorized()
    {
        $this->withoutTokenTestHelper('/v1/member', false);
    }

    public function testMemberRouter_Single_IdIs1_Without_Token_Should_Unauthorized()
    {
        $this->withoutTokenTestHelper('/v1/member/1', false);
    }

    public function testMemberRouter_All_Invalid_Token_Should_Unauthorized()
    {
        $this->withInvalidTokenTestHelper('/v1/member', false);
    }

    public function testMemberRouter_Single_IdIs1_Invalid_Token_Should_Unauthorized()
    {
        $this->withInvalidTokenTestHelper('/v1/member/1', false);
    }

    public function testMemberRouter_All_Invalid_Token_Wrong_Number_Should_Unauthorized()
    {
        $this->withInvalidTokenWrongNumberTestHelper('/v1/member', false);
    }

    public function testMemberRouter_Single_IdIs1_Invalid_Token_Wrong_Number_Should_Unauthorized()
    {
        $this->withInvalidTokenWrongNumberTestHelper('/v1/member/1', false);
    }

    public function testMemberRouter_All_With_Token()
    {
        $data = $this->makeResponseResourceInputDataForMember();

        $this->mock('App\Contracts\Repositories\IMemberRepository', function ($mock) use ($data) {
            $mock->allows()
                ->all()
                ->andReturn([$data]);
        });
        $expectArray = (new MemberCollectionResponseResource([$data]))->jsonSerialize();
        $this->jsonResponseHelperWithToken('get', '/v1/member', $expectArray);
    }

    public function testMemberRouter_Single_IdIs1_With_Token()
    {
        $data = $this->makeResponseResourceInputDataForMember();

        $this->mock('App\Contracts\Repositories\IMemberRepository', function ($mock) use ($data) {
            $mock->allows()
                ->one(1)
                ->andReturn($data);
        });
        $expectArray = (new MemberResponseResource($data))->jsonSerialize();
        $this->jsonResponseHelperWithToken('get', '/v1/member/1', $expectArray);
    }

    private function unauthorizedGetTestHelper($uri, array $headers = [])
    {
        $this->get($uri, $headers);
        $this->assertEquals(401, $this->response->status());
    }
    private function authorizedGetTestHelper($uri, array $headers = [])
    {
        $this->get($uri, $headers);
        $this->assertNotEquals(401, $this->response->status());
    }

    private function withoutTokenTestHelper($uri, $authorized)
    {
        if ($authorized === true) {
            $this->authorizedGetTestHelper($uri);
        } else {
            $this->unauthorizedGetTestHelper($uri);
            $expectArray = (new BadResponseResource(FAILED_RESULT_RESPONSE, DENIED_ACCESS_RESPONSE, UNAUTHORIZED_MESSAGE, (new TokenMissingException())->getMessage()))->jsonSerialize();
            $this->seeJsonEquals($expectArray);
        }
    }

    private function withInvalidTokenTestHelper($uri, $authorized)
    {
        $headers = [AUTHORIZATION_HEADER => AUTHORIZATION_TYPE . ' invalid_token'];
        if ($authorized === true) {
            $this->authorizedGetTestHelper($uri, $headers);
        } else {
            $this->unauthorizedGetTestHelper($uri, $headers);
            $expectArray = (new BadResponseResource(FAILED_RESULT_RESPONSE, DENIED_ACCESS_RESPONSE, UNAUTHORIZED_MESSAGE, (new InvalidTokenException())->getMessage()))->jsonSerialize();
            $this->seeJsonEquals($expectArray);
        }
    }

    private function withInvalidTokenWrongNumberTestHelper($uri, $authorized)
    {
        $headers = [AUTHORIZATION_HEADER => AUTHORIZATION_TYPE . ' invalid_token_wrong_number'];
        if ($authorized === true) {
            $this->authorizedGetTestHelper($uri, $headers);
        } else {
            $this->unauthorizedGetTestHelper($uri, $headers);
            $expectArray = (new BadResponseResource(FAILED_RESULT_RESPONSE, DENIED_ACCESS_RESPONSE, UNAUTHORIZED_MESSAGE, (new InvalidTokenException(INVALID_TOKEN_EXCEPTION_WRONG_NUMBER_MESSAGE))->getMessage()))->jsonSerialize();
            $this->seeJsonEquals($expectArray);
        }
    }

    private function jsonResponseHelperWithToken(string $method, string $route, array $json, array $data = [], array $headers = [])
    {
        if (!isset($headers[AUTHORIZATION_HEADER])) {
            $headers[AUTHORIZATION_HEADER] = AUTHORIZATION_TYPE . ' token_example';
        }
        $this->jsonResponseHelper($method, $route, $json, $data, $headers);
    }

    private function jsonResponseHelper(string $method, string $route, array $json, array $data = [], array $headers = [])
    {
        $this->json($method, $route, $data, $headers)
            ->seeJsonEquals($json);
    }
}
