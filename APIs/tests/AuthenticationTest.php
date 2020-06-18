<?php

include_once __DIR__ . '/../app/Http/Resources/Constants.php';

class AuthenticationTest extends TestCase
{
    public function testMemberWith_EmptyToken_Should_Unauthorized()
    {
        $emptyToken = '';
        $this->authenticationHelper($emptyToken, function ($mock) {
            $mock->shouldReceive('validate')->never();
        });
    }

    public function testMemberWith_WrongToken_Should_Unauthorized()
    {
        $wrongToken = 'wrong_token';
        $this->authenticationHelper($wrongToken, function ($mock) use ($wrongToken) {
            $mock->allows()->validate($wrongToken)->andReturn(null);
        });
    }

    private function authenticationHelper($token, $mockSetup, $expectStatus = 401)
    {
        $this->mock('App\Contracts\ITokenValidator', function ($mock) use ($mockSetup) {
            if (isset($mockSetup)) {
                $mockSetup($mock);
            }
        });

        $this->get('/v1/Member', [AUTHORIZATION_HEADER => AUTHORIZATION_TYPE . ' ' . $token]);
        $this->assertEquals($expectStatus, $this->response->status());
    }
}
