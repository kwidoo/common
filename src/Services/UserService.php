<?php

namespace Velia\Common\Services;

use Velia\Common\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use LogicException;
use Reflection;
use ReflectionClass;
use Velia\Common\Concerns\Service;

class UserService implements Service
{
    protected $endpoint;

    public function __construct(protected User $user, protected User $model)
    {
        $this->endpoint = config('velia.auth.user');
    }

    /**
     * @return Response
     */
    public function getUser(): Response
    {
        return $this->retrieve()->onError(function ($response) {
            if ($response->forbidden()) {
                $response->throw();
            }
            $this->refreshToken();

            return $this->retrieve();
        });
    }

    /**
     * @return Response
     */
    protected function retrieve(): Response
    {
        return Http::withHeaders(
            ['Authorization' => 'Bearer ' . $this->user->access_token]
        )->get($this->endpoint, ['id' => $this->model]);
    }

    protected function refreshToken()
    {
        $response = Http::post('/oauth/token', [

            'client_id' => config('velia.auth.passport.client'),
            'client_secret' => config('velia.auth.passport.secret'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->user->refresh_token,
        ]);
        $this->user->api_token = $response->json('access_token');
        $this->user->save();
        $this->user->refresh();
    }
}
