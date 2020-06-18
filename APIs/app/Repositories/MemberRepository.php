<?php

namespace App\Repositories;

use App\Contracts\{
    ILogger,
    IQueryable
};
use App\Contracts\Repositories\{
    IMemberRepository
};
use stdClass;

class MemberRepository extends BaseRepository implements IMemberRepository
{
    public function __construct(ILogger $logger, IQueryable $queryable)
    {
        parent::__construct($logger, $queryable);
    }

    protected function toResultEntity($entity)
    {
        $result = new stdClass();
        $result->Id = $entity->id;
        $result->Title = $entity->Title;
        $result->FirstNames = $entity->FirstNames;
        $result->MiddleNames = $entity->MiddleNames;
        $result->LastNames = $entity->LastNames;
        $result->KnownAs = $entity->KnownAs;
        $result->MailName = $entity->MailName;
        $result->DOB = $entity->DOB;
        $result->PhotoFileName = $entity->PhotoFileName;
        $result->Address = $addressTemp = new stdClass();
        $addressTemp->Id = $entity->address->id;
        $addressTemp->Active = $entity->address->Active;
        $addressTemp->StartDate = $entity->address->StartDate;
        $addressTemp->EndDate = $entity->address->EndDate;
        $addressTemp->Building = $entity->address->Building;
        $addressTemp->Street = $entity->address->Street;
        $addressTemp->TownCity = $entity->address->TownCity;
        $addressTemp->PostCode = $entity->address->PostCode;
        $addressTemp->Country = $entity->address->Country;
        $result->History = [];
        $entityHistory = $entity->history;
        if (!isset($entityHistory)) {
            return $result;
        }
        foreach ($entityHistory as $history) {
            $historyTemp = new stdClass();
            $historyTemp->Id = $history->id;
            $historyTemp->Member_ID = $history->Member_ID;
            $historyTemp->Group_ID = $history->Group_ID;
            $historyTemp->Member_End_Reason_ID = $history->Member_End_Reason_ID;
            $historyTemp->StartDate = $history->StartDate;
            $historyTemp->EndDate = $history->EndDate;
            $historyTemp->Capitation = $history->Capitation;
            $historyTemp->Notes = $history->Notes;
            $historyTemp->LastModifiedDate = $history->LastModifiedDate;
            $historyTemp->LastModifiedBy = $history->LastModifiedBy;
            $entityEndReason = $history->endReason;
            if (!isset($entityEndReason)) {
                continue;
            }
            $historyTemp->EndReason = new stdClass();
            $historyTemp->EndReason->Id = $entityEndReason->id;
            $historyTemp->EndReason->Name = $entityEndReason->Name;
            $historyTemp->EndReason->Description = $entityEndReason->Description;
            $historyTemp->EndReason->LastModifiedDate = $entityEndReason->LastModifiedDate;
            $historyTemp->EndReason->LastModifiedBy = $entityEndReason->LastModifiedBy;
            $result->History[] = $historyTemp;
        }
        return $result;
    }
}
