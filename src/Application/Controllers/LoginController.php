<?php
namespace Src\Application\Controllers;

use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginController
{
    public function login(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $hardcodedUsername = 'admin';
        $hardcodedPassword = 'secret123';

        if ($username === $hardcodedUsername && $password === $hardcodedPassword) {
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600;
            $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'username' => $username
            ];

            $jwt = JWT::encode($payload, 'your_secret_key', 'HS256');

            $response->getBody()->write(json_encode([
                'message' => 'Login successful',
                'token' => $jwt
            ]));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode(['error' => 'Invalid credentials']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}
