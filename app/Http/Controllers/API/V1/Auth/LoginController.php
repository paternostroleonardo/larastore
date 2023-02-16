<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Requests\Requests\Auth\RegisterRequest;
use App\Http\Requests\Requests\Auth\LoginRequest;
use App\Http\Controllers\API\V1\ApiController;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class LoginController extends ApiController
{
    use ApiResponser;

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $validateData = $request->validated();

            $user = User::create($validateData);

            if ($validateData['role'] == 'root') {
                $user->assignRole(User::ROLES['root']);
            }

            if ($validateData['role'] == 'inspector') {
                $user->assignRole(User::ROLES['inspector']);
            }

            $user->assignRole(User::ROLES['seller']);

            $credentials = $this->buildCredentials([
                'username' => $validateData["email"],
                'password' => $validateData["password"]
            ]);

            $response = $this->makeRequest($credentials);
            $response['user'] = $user;

            Log::info('user register success' . $user->id . '-' . $user->email);

            return $this->showLoginInfo([
                'me' => $user,
                'tokens' => $response,
                'status' => 'SUCCESS',
            ]);
        } catch (\Throwable $error) {
            Log::debug('Register failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $this->buildCredentials(request(['username', 'password']));
            $response = $this->makeRequest($credentials);
            $user = User::whereEmail($request->username)->get();

            Log::info('user login with exit' . $user->id . '-' . $user->email);
    
            return $this->showLoginInfo(
                array_merge(
                    $response,
                    [
                        'user' => $user
                    ]
                )
            );
        }  catch (\Throwable $error) {
            Log::debug('Login failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }

    private function buildCredentials(array $validatedData = [], $grantType = 'password')
    {
        $validatedData = collect($validatedData);
        $credentials = $validatedData->except('directive')->toArray();
        $credentials['client_id'] = $validatedData->get('client_id', config('services.auth.client_id'));
        $credentials['client_secret'] = $validatedData->get('client_secret', config('services.auth.client_secret'));
        $credentials['grant_type'] = $grantType;

        return $credentials;
    }

    public function makeRequest(array $credentials): mixed
    {
        $request = Request::create('oauth/token', 'POST', $credentials, [], [], [
            'HTTP_Accept' => 'application/json',
        ]);
        $response = app()->handle($request);
        $decodedResponse = json_decode($response->getContent(), true);

        if ($response->getStatusCode() != 200) {
            if ($decodedResponse['message'] === 'The provided authorization grant (e.g., authorization code, resource owner credentials) or refresh token is invalid, expired, revoked, does not match the redirection URI used in the authorization request, or was issued to another client.') {
                throw new AuthenticationException(__('Incorrect username or password'));
            }
            throw new AuthenticationException(__($decodedResponse['message']));
        }

        $decodedResponse['expires_at'] = Carbon::now()->addSeconds($decodedResponse['expires_in']);

        return $decodedResponse;
    }
}
