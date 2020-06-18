<?php

namespace App\Http\Resources;

use JsonSerializable;

class MemberResource extends CollectionResource implements JsonSerializable
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $History = [];
        foreach ($this->History as $history) {
            $History[] = [
                'Id' => $history->Id,
                'Member_ID' => $history->Member_ID,
                'Group_ID' => $history->Group_ID,
                'Member_End_Reason_ID' => $history->Member_End_Reason_ID,
                'StartDate' => $history->StartDate,
                'EndDate' => $history->EndDate,
                'Capitation' => $history->Capitation,
                'Notes' => $history->Notes,
                'EndReason' => [
                    'Id' => $history->EndReason->Id,
                    'Name' => $history->EndReason->Name,
                    'Description' => $history->EndReason->Description
                ]
            ];
        }
        return [
            'Id' => $this->Id,
            'Title' => $this->Title,
            'FirstNames' => $this->FirstNames,
            'MiddleNames' => $this->MiddleNames,
            'LastNames' => $this->LastNames,
            'KnownAs' => $this->KnownAs,
            'MailName' => $this->MailName,
            'DOB' => $this->DOB,
            'PhotoFileName' => $this->PhotoFileName,
            'Address' => [
                'Id' => $this->Address->Id,
                'Active' => $this->Address->Active,
                'StartDate' => $this->Address->StartDate,
                'EndDate' => $this->Address->EndDate,
                'Building' => $this->Address->Building,
                'Street' => $this->Address->Street,
                'TownCity' => $this->Address->TownCity,
                'PostCode' => $this->Address->PostCode,
                'Country' => $this->Address->Country,
            ],
            'History' => $History
        ];
    }
}
