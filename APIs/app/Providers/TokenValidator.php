<?php

namespace App\Providers;

use App\Contracts\{
    IArrayStructureChecker,
    ITokenDecoder,
    ILogger,
    ITokenValidator
};
use App\Contracts\Repositories\IMemberRepository;
use App\Models\User;
use App\Exceptions\InvalidTokenException;
use UnexpectedValueException;

class TokenValidator extends RecordableProvider implements ITokenValidator
{
    private const tokenStructure = [
        "iss",
        "aud",
        "iat",
        "nbf",
        "exp",
        "data" => [
            'MemberID',
            'EmailAddress'
        ]
    ];

    protected ITokenDecoder $tokenDecoder;
    protected IArrayStructureChecker $arrayStructureChecker;
    protected IMemberRepository $memberRepository;

    public function __construct(
        ILogger $logger,
        ITokenDecoder $tokenDecoder,
        IArrayStructureChecker $arrayStructureChecker,
        IMemberRepository $memberRepository
    ) {
        parent::__construct($logger);
        $this->tokenDecoder = $tokenDecoder;
        $this->arrayStructureChecker = $arrayStructureChecker;
        $this->memberRepository = $memberRepository;
    }

    /**
     * Get the user by pass a token.
     *
     * @param  string  $token
     * @return App\Models\User|null
     */
    public function validate(string $token): ?User
    {
        if (!$this->checkToken($token)) {
            throw new InvalidTokenException(EMPTY_TOKEN_EXCEPTION_MESSAGE);
        }

        try {
            $decodedData = (array) $this->tokenDecoder->decode($token);

            if (!$this->isValidToken($decodedData)) {
                throw new InvalidTokenException();
            }

            return $this->getUser($decodedData);
        } catch (UnexpectedValueException $uve) {
            throw new InvalidTokenException($uve->getMessage());
        }
    }

    private function checkToken(string $token): bool
    {
        return isset($token) && !empty($token);
    }

    private function isValidToken(array $decodedData): bool
    {
        $tokenStructure = TokenValidator::tokenStructure;
        $structureMatch = $this->arrayStructureChecker->checkStructure($tokenStructure, $decodedData);
        $fieldCountMatch = count($decodedData) === count($tokenStructure) &&
            isset($decodedData['data']) &&
            count((array) $decodedData['data']) === count($tokenStructure['data']);
        if (!$fieldCountMatch) {
            $decodedDataJson = json_encode($decodedData);
            $this->logger->warning("The token passed in was can be decoded, but the parsed data is not as the API expected.\nDecoded data:{$decodedDataJson}");
        }
        return $structureMatch && $fieldCountMatch;
    }

    private function getUser(array $decodedData): ?User
    {
        $memberId =  $decodedData['data']->MemberID;
        $email = $decodedData['data']->EmailAddress;
        if ($this->memberExists($memberId)) {
            return new User(['id' => $memberId, 'email' => $email]);
        }
        return null;
    }

    private function memberExists($memberId): bool
    {
        if (config('app.api_check_member_exists') === true) {
            $member = $this->memberRepository->one($memberId);
            return !is_null($member);
        }
        // Member exists if do not need to check with database.
        return true;
    }
}
