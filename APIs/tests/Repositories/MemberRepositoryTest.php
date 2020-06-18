<?php

use App\Exceptions\{
    DatabaseException,
    InvalidTokenException,
    TokenMissingException
};
use App\Repositories\MemberRepository;

include_once __DIR__ . '/../../app/Http/Resources/Constants.php';
include_once __DIR__ . '/../../tests/Repositories/RepositoriesTestCase.php';

class MemberRepositoryTest extends RepositoriesTestCase
{
    protected function initialise()
    {
        parent::initialise();

        $this->mockRepositoryName = MemberRepository::class;
    }

    public function testMemberRepository_ModelQuery_All_Return_Null()
    {
        $this->repositoryReturnNull([
            'modelName' => \App\Models\Member::class,
            'modelFunctionName' => 'allEntities',
            'repositoryFunctionName' => 'all',
            'isArray' => true
        ]);
    }

    public function testMemberRepository_ModelQuery_One_Return_Null()
    {
        $this->repositoryReturnNull([
            'modelName' => \App\Models\Member::class,
            'modelFunctionName' => 'findEntity',
            'modelFunctionExpectArgument' => 1,
            'repositoryFunctionName' => 'one',
            'repositoryFunctionParameter' => 1
        ]);
    }

    public function testMemberRepository_ModelQuery_All()
    {
        $mock = Mockery::spy(\App\Models\Member::class);
        $address = Mockery::spy(\App\Models\MemberAddressHistory::class);
        $mock->shouldReceive('getAttribute')
            ->with('address')
            ->andReturn($address);
        $groupHistory = Mockery::spy(\App\Models\MemberGroupHistory::class);
        $mock->shouldReceive('getAttribute')
            ->with('history')
            ->andReturn([$groupHistory]);

        $endReason = Mockery::spy(\App\Models\MemberEndReason::class);
        $groupHistory->shouldReceive('getAttribute')
            ->with('endReason')
            ->andReturn($endReason);

        $expect = $this->makeResponseResourceInputDataForMember();

        $this->allShouldSame([
            'modelName' => \App\Models\Member::class,
            'modelFunctionName' => 'allEntities',
            'modelReturnData' => [$mock],
            'repositoryFunctionName' => 'all',
            'expect' => [$expect]
        ]);
    }
    public function testMemberRepository_ModelQuery_One()
    {
        $mock = Mockery::spy(\App\Models\Member::class);
        $address = Mockery::spy(\App\Models\MemberAddressHistory::class);
        $mock->shouldReceive('getAttribute')
            ->with('address')
            ->andReturn($address);
        $groupHistory = Mockery::spy(\App\Models\MemberGroupHistory::class);
        $mock->shouldReceive('getAttribute')
            ->with('history')
            ->andReturn([$groupHistory]);

        $endReason = Mockery::spy(\App\Models\MemberEndReason::class);
        $groupHistory->shouldReceive('getAttribute')
            ->with('endReason')
            ->andReturn($endReason);

        $expect = $this->makeResponseResourceInputDataForMember();

        $this->allShouldSame([
            'modelName' => \App\Models\Member::class,
            'modelFunctionName' => 'findEntity',
            'modelFunctionExpectArgument' => 1,
            'modelReturnData' => $mock,
            'repositoryFunctionName' => 'one',
            'repositoryFunctionParameter' => 1,
            'expect' => $expect
        ]);
    }

    public function testMemberRepository_ModelQuery_All_Throw_DatabaseException()
    {
        $this->unknownExceptionThrewByModel([
            'modelName' => \App\Models\Member::class,
            'modelFunctionName' => 'allEntities',
            'repositoryFunctionName' => 'all',
        ]);
    }

    public function testMemberRepository_ModelQuery_One_Throw_DatabaseException()
    {
        $this->repositoryReturnNull([
            'modelName' => \App\Models\Member::class,
            'modelFunctionName' => 'findEntity',
            'modelFunctionExpectArgument' => 1,
            'repositoryFunctionName' => 'one',
            'repositoryFunctionParameter' => 1
        ]);
    }

    public function testMemberModel_Member()
    {
        $this->modelShouldHave(\App\Models\Member::class, [
            'Title',
            'FirstNames',
            'MiddleNames',
            'LastNames',
            'KnownAs',
            'MailName',
            'DOB',
            'PhotoFileName',
            'LastModifiedDate',
            'LastModifiedBy',
            'address',
            'history'
        ]);
    }

    public function testMemberModel_MemberAddressHistory()
    {
        $this->modelShouldHave(\App\Models\MemberAddressHistory::class, [
            'Member_ID',
            'Active',
            'StartDate',
            'EndDate',
            'Building',
            'Street',
            'TownCity',
            'PostCode',
            'Country'
        ]);
    }

    public function testMemberModel_MemberGroupHistory()
    {
        $this->modelShouldHave(\App\Models\MemberGroupHistory::class, [
            'Member_ID',
            'Group_ID',
            'Member_End_Reason_ID',
            'StartDate',
            'EndDate',
            'Capitation',
            'Notes',
            'LastModifiedDate',
            'LastModifiedBy',
            'endReason'
        ]);
    }

    public function testMemberModel_MemberEndReason()
    {
        $this->modelShouldHave(\App\Models\MemberEndReason::class, [
            'Name',
            'Description',
            'LastModifiedDate',
            'LastModifiedBy'
        ]);
    }
}
