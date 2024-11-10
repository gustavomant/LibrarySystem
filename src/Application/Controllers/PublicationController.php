<?php

namespace Src\Application\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Src\Domain\ValueObjects\ISBN;
use Src\Application\Services\PublicationService;

class PublicationController
{
    private $publicationService;

    public function __construct(PublicationService $publicationService)
    {
        $this->publicationService = $publicationService;
    }

    public function createPublication(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);

            if (empty($data['title']) || empty($data['author']) || empty($data['published_year']) || empty($data['isbn'])) {
                $response->getBody()->write(json_encode(['message' => 'Missing required publication details']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            $isbn = new ISBN($data['isbn']);

            if ($this->publicationService->createPublication($data['title'], $data['author'], $data['published_year'], $isbn)) {
                $response->getBody()->write(json_encode(['message' => 'Publication created successfully']));
                return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['message' => 'Failed to create publication']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function listPublications(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $publications = $this->publicationService->getAllPublications();

            if ($publications === false) {
                $response->getBody()->write(json_encode(['error' => 'Failed to encode publications']));
                return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode($publications));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function getPublication(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        try {
            $publication = $this->publicationService->findPublication($args['id']);

            if ($publication) {
                $response->getBody()->write(json_encode($publication));
                return $response->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['message' => 'Publication not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function updatePublication(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);

            if (empty($data['title']) || empty($data['author']) || empty($data['published_year']) || empty($data['isbn'])) {
                $response->getBody()->write(json_encode(['message' => 'Missing required publication details']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            $isbn = new ISBN($data['isbn']);

            if ($this->publicationService->updatePublication($args['id'], $isbn, $data['title'], $data['author'], $data['published_year'])) {
                $response->getBody()->write(json_encode(['message' => 'Publication updated successfully']));
                return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['message' => 'Publication not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function deletePublication(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        try {
            if ($this->publicationService->deletePublication($args['id'])) {
                $response->getBody()->write(json_encode(['message' => 'Publication deleted successfully']));
                return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['message' => 'Publication not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
