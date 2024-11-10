<?php

namespace Src\Infrastructure\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware
{
    // This is a hard-coded username for demonstration purposes.
    // In a production environment, it should be replaced with a more secure and dynamic approach
    // such as validating against a database or an identity provider.
    private const HARDCODED_USERNAME = 'admin';
    
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request, RequestHandler $handler) : Response
    {
        $response = $this->responseFactory->createResponse();
        $token = null;

        if ($request->hasHeader('Authorization')) {
            $authHeader = $request->getHeaderLine('Authorization');
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }

        if (!$token) {
            return $response->withStatus(401);
        }

        try {
            $decoded = JWT::decode($token, new Key('your_secret_key', 'HS256'));

            if ($decoded->username !== self::HARDCODED_USERNAME) {
                return $response->withStatus(401);
            }
        } catch (\Exception $e) {
            return $response->withStatus(401);
        }

        return $handler->handle($request);
    }
}
