<?php

namespace App\Http\Controllers;

use App\Contracts\{ILogger};
use App\Contracts\Repositories\{IMemberRepository};
use App\Exceptions\{DatabaseException, EntityNotFoundException};
use App\Http\Resources\ResponseResources\{
    BadResponseResource,
    MemberCollectionResponseResource,
    MemberResponseResource
};

use Illuminate\Http\Request;

use Exception;

class MemberController extends Controller
{
    protected IMemberRepository $memberRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IMemberRepository $memberRepository, ILogger $logger)
    {
        parent::__construct($logger);
        //
        $this->memberRepository = $memberRepository;
    }

    /**
     * Get the member data for the user of the request.
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getAll()
    {
        try {
            $data = $this->memberRepository->all();
            if (!$data) {
                $data = [];
            }
            return response()->json(new MemberCollectionResponseResource($data));
        } catch (DatabaseException $dbe) {
            $this->logger->error($dbe);
            return response(new BadResponseResource(
                FAILED_RESULT_RESPONSE,
                ALLOWED_ACCESS_RESPONSE,
                SERVER_FAULT_MESSAGE,
                null
            ), 500);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $this->logger->error($ex);
            return response(new BadResponseResource(
                FAILED_RESULT_RESPONSE,
                ALLOWED_ACCESS_RESPONSE,
                SERVER_FAULT_MESSAGE,
                $message
            ), 500);
        }
    }

    /**
     * Get the member data for the user of the request.
     *
     * @param  int  $id
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getMember($id)
    {
        try {
            $data = $this->memberRepository->one($id);
            if (!$data) {
                throw new EntityNotFoundException(sprintf(MEMBER_NOT_FOUND_EXCEPTION_MESSAGE, $id));
            }
            return response()->json(new MemberResponseResource($data));
        } catch (EntityNotFoundException $mnfe) {
            $message = $mnfe->getMessage();
            $this->logger->info($message);
            return response(new BadResponseResource(
                FAILED_RESULT_RESPONSE,
                ALLOWED_ACCESS_RESPONSE,
                sprintf(MEMBER_NOT_FOUND_MESSAGE, $id),
                null
            ), 404);
        } catch (DatabaseException $dbe) {
            $this->logger->error($dbe);
            return response(new BadResponseResource(
                FAILED_RESULT_RESPONSE,
                ALLOWED_ACCESS_RESPONSE,
                SERVER_FAULT_MESSAGE,
                null
            ), 500);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $this->logger->error($ex);
            return response(new BadResponseResource(
                FAILED_RESULT_RESPONSE,
                ALLOWED_ACCESS_RESPONSE,
                SERVER_FAULT_MESSAGE,
                $message
            ), 500);
        }
    }
}
