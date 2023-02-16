<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $request->user()
            ->tokens
            ->each(function ($token, $key) {
                $this->revokeAccessAndRefreshTokens($token->id);
            });
        return $this->successResponse('Logged out successfully', 200);
        } catch (\Throwable $error) {
            Log::debug('Logout failed' . $error->getMessage());
            return $this->errorResponse($error->getMessage());
        }
    }
    
    protected function revokeAccessAndRefreshTokens($tokenId)
    {
        $tokenRepository = app('Laravel\Passport\TokenRepository');
        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');
        $tokenRepository->revokeAccessToken($tokenId);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);
    }
}
