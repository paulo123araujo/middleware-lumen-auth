<?php

namespace Middlewares;

use Closure;
use Exception;
use Firebase\JWT\{JWT, ExpiredException, SignatureInvalidException};
use Illuminate\Http\{JsonResponse, Request};

class Authenticate
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (env('APP_ENV') === 'testing' && env('TEST_SKIP_AUTH', true)) {
            return $next($request);
        }

        if (env('APP_ENV') === 'local' && env('SKIP_AUTH', false)) {
            return $next($request);
        }

        $jwt = $this->extractJWT($request);
        if (!$jwt) {
            return $this->return401('É necessário informar o header de Authorization com o Token JWT');
        }

        try {
            $decoded = JWT::decode($jwt, config('jwt.key'), config('jwt.alg'));
        } catch (SignatureInvalidException $exception) {
            return $this->return401('JWT inválido', $exception->getMessage());
        } catch (ExpiredException $exception) {
            return $this->return401('JWT expirado');
        } catch (Exception $exception) {
            return $this->return401('Erro ao validar o token JWT', $exception->getMessage());
        }

        $request->authenticatedUser = $decoded->user ?? null;
        return $next($request);
    }

    private function extractJWT(Request $request): string
    {
        if ($request->header('Authorization')) {
            $authHeader = $request->header('Authorization') ?? '';
            return str_replace('Bearer ', '', $authHeader);
        }

        if (env('APP_ENV') === 'local') {
            return $request->input('authorization')
                ?? $request->input('token')
                ?? $request->input('jwtKey', '');
        }

        return '';
    }

    private function return401(string $message = '', $error = ''): JsonResponse
    {
        return response()->json(array_filter([
            'status' => 'Não autorizado',
            'message' => $message ?? 'Falha no login',
            'error' => $error ?? null
        ]), 401);
    }
}
