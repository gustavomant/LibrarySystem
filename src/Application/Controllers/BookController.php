<?php
namespace Src\Application\Controllers;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Src\Application\Services\BookService;

class BookController
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function createBook(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (empty($data['publication_id'])) {
            $response->getBody()->write(json_encode(['error' => 'Missing publication_id']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $bookCreated = $this->bookService->createBook($data['publication_id']);

        if ($bookCreated) {
            $response->getBody()->write(json_encode(['message' => 'Book created successfully']));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode(['error' => 'Failed to create book']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    public function listBooks(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $books = $this->bookService->getAllBooks();
        $response->getBody()->write(json_encode(['data' => $books]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getBook(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $book = $this->bookService->getBookById($args['id']);
        if ($book) {
            $response->getBody()->write(json_encode(['data' => $book]));
            return $response->withHeader('Content-Type', 'application/json');
        }
        $response->getBody()->write(json_encode(['error' => 'Book not found']));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }

    public function deleteBook(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $bookDeleted = $this->bookService->deleteBook($args['id']);
        if ($bookDeleted) {
            $response->getBody()->write(json_encode(['message' => 'Book deleted successfully']));
            return $response->withHeader('Content-Type', 'application/json');
        }
        $response->getBody()->write(json_encode(['error' => 'Book not found']));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
}