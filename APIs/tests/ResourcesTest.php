<?php

use App\Http\Resources\ResponseResources\{
    BadResponseResource,
    MemberCollectionResponseResource,
    MemberResponseResource
};

include_once __DIR__ . '/../app/Http/Resources/Constants.php';

class ResourcesTest extends TestCase
{
    public function testBadResponseResource_JSON_Structure()
    {
        $expectJsonStructure = [
            RESPONSE_FIELD_RESULT,
            RESPONSE_FIELD_ACCESS,
            RESPONSE_FIELD_MESSAGE,
            RESPONSE_FIELD_ERROR,
        ];


        $data = $this->makeResponseResourceInputDataForMember();

        $this->checkJsonStructureHelper($expectJsonStructure, new BadResponseResource('result', 'access', 'message', 'error'));
    }

    public function testMemberCollectionResponseResource_JSON_Structure()
    {
        $expectJsonStructure = [
            RESPONSE_FIELD_RESULT,
            RESPONSE_FIELD_ACCESS,
            RESPONSE_FIELD_MESSAGE,
            RESPONSE_FIELD_ERROR,
            RESPONSE_FIELD_DATA_MEMBERS => [
                '*' => [
                    'Id',
                    'Title',
                    'FirstNames',
                    'MiddleNames',
                    'LastNames',
                    'KnownAs',
                    'MailName',
                    'DOB',
                    'PhotoFileName',
                    'Address' => [
                        'Id',
                        'Active',
                        'StartDate',
                        'EndDate',
                        'Building',
                        'Street',
                        'TownCity',
                        'PostCode',
                        'Country',
                    ],
                    'History' => [
                        '*' => [
                            'Id',
                            'Member_ID',
                            'Group_ID',
                            'Member_End_Reason_ID',
                            'StartDate',
                            'EndDate',
                            'Capitation',
                            'Notes',
                            'EndReason' => [
                                'Id',
                                'Name',
                                'Description'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $data = $this->makeResponseResourceInputDataForMember();

        $this->checkJsonStructureHelper($expectJsonStructure, new MemberCollectionResponseResource([$data]));
    }
    public function testMemberResponseResource_JSON_Structure()
    {
        $expectJsonStructure = [
            RESPONSE_FIELD_RESULT,
            RESPONSE_FIELD_ACCESS,
            RESPONSE_FIELD_MESSAGE,
            RESPONSE_FIELD_ERROR,
            RESPONSE_FIELD_DATA_MEMBER => [
                'Id',
                'Title',
                'FirstNames',
                'MiddleNames',
                'LastNames',
                'KnownAs',
                'MailName',
                'DOB',
                'PhotoFileName',
                'Address' => [
                    'Id',
                    'Active',
                    'StartDate',
                    'EndDate',
                    'Building',
                    'Street',
                    'TownCity',
                    'PostCode',
                    'Country',
                ],
                'History' => [
                    '*' => [
                        'Id',
                        'Member_ID',
                        'Group_ID',
                        'Member_End_Reason_ID',
                        'StartDate',
                        'EndDate',
                        'Capitation',
                        'Notes',
                        'EndReason' => [
                            'Id',
                            'Name',
                            'Description'
                        ]
                    ]
                ]
            ]
        ];

        $data = $this->makeResponseResourceInputDataForMember();

        $this->checkJsonStructureHelper($expectJsonStructure, new MemberResponseResource($data));
    }

    private function checkJsonStructureHelper($jsonStructure, $jsonResponse)
    {
        $jsonArray = $jsonResponse->jsonSerialize();
        $this->assertJsonStructure($jsonStructure, $jsonArray);
    }
}
