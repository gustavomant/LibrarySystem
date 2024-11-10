<?php
namespace Src\Application\Controllers;

use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginController
{
    public function login(Request $request, Response $response): Response
    {
        // Retrieve the request data
        $data = json_decode($request->getBody()->getContents(), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $hardcodedUsername = 'admin';
        $hardcodedPassword = 'secret123';

        // Validate username and password
        if ($username === $hardcodedUsername && $password === $hardcodedPassword) {
            // Token generation
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600;  // 1 hour expiration
            $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'username' => $username
            ];

            // Encode JWT token (pass 3 params directly: payload, secret, algorithm)
            $jwt = JWT::encode($payload, 'your_secret_key', 'HS256');  // Directly pass 3 arguments

            // Respond with token
            $response->getBody()->write(json_encode([
                'message' => 'Login successful',
                'token' => $jwt
            ]));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        }

        // Invalid credentials response
        $response->getBody()->write(json_encode(['error' => 'Invalid credentials']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}
