<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Silber\Bouncer\Bouncer;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Bouncer
     */
    public $bouncer;

    public function __construct(Bouncer $bouncer)
    {
        $this->bouncer = $bouncer;
    }

    public function requestUser(): User
    {
        /** @var User $user */
        $user = request()->user();
        return $user;
    }

    /**
     * Check user owner of this address
     *
     * @param $entity
     * @param string $foreign_key
     * @throws Throwable
     */
    public function checkUserOwnerOf($entity, $foreign_key = 'user_id')
    {
        throw_if($entity->{$foreign_key} != $this->requestUser()->id,
            new AuthorizationException()
        );
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return responseJson([
            'token' => $token,
            'token_type' => 'Bearer',
            'expired_in' => auth()->factory()->getTTL()
        ]);
    }
}
