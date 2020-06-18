<?php

use App\Exceptions\{
    DatabaseException,
    InvalidTokenException,
    TokenMissingException
};
use App\Http\Resources\ResponseResources\{
    BadResponseResource,
    MemberCollectionResponseResource,
    MemberResponseResource
};
use App\Models\{User};

include_once __DIR__ . '/../../app/Http/Resources/Constants.php';
include_once __DIR__ . '/../../tests/controllers/ControllersTestCase.php';

class MemberControllerTest extends ControllersTestCase
{
    protected function initialise()
    {
        parent::initialise();

        $this->mockRepositoryName = 'App\Contracts\Repositories\IMemberRepository';
        $this->mockControllerName = 'App\Http\Controllers\MemberController';
    }

    public function testMember_IdIs1_NotFound_Should_Response_404()
    {
        parent::notFound_One('member', 'one', 1, 'getMember', 1);
    }

    public function testMember_Get_All_DatabaseException()
    {
        parent::databaseException('all', null, 'getAll');
    }

    public function testMember_Get_One_DatabaseException()
    {
        parent::databaseException('one', 1, 'getMember', 1);
    }

    public function testMember_All_Should_Same()
    {
        $data = $this->makeResponseResourceInputDataForMember();
        
        $expectMessage = json_encode(new MemberCollectionResponseResource([$data]));

        parent::allShouldSame([$data], $expectMessage, 'all', null, 'getAll');
    }

    public function testMember_One_IdIs1_Should_Same()
    {
        $data = $this->makeResponseResourceInputDataForMember();

        $expectMessage = json_encode(new MemberResponseResource($data));

        parent::allShouldSame($data, $expectMessage, 'one', 1, 'getMember', 1);
    }
}
