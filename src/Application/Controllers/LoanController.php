<?php

namespace Src\Application\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Src\Application\Services\LoanService;
use Exception;

class LoanController
{
    private LoanService $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function createLoan(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);

            if (!isset($data['loan_duration_days'])) {
                throw new \InvalidArgumentException('Loan duration (loan_duration_days) is required.');
            }

            $this->loanService->createLoan(
                $data['user_id'],
                $data['book_id'],
                $data['loan_duration_days']
            );

            $response->getBody()->write(json_encode(['message' => 'Loan created successfully.']));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        } catch (\InvalidArgumentException $e) {
            $response->getBody()->write(json_encode(['error' => 'Invalid data', 'details' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to create loan', 'details' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }



    public function getLoanById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $loan = $this->loanService->getLoanById($args['id']);

            if ($loan) {
                $response->getBody()->write(json_encode(['loan' => $loan]));
                return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['error' => 'Loan not found.']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to retrieve loan', 'details' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function getLoansByUserId(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $loans = $this->loanService->getLoansByUserId($args['userId']);
            $response->getBody()->write(json_encode(['loans' => $loans]));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to retrieve loans', 'details' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function getLoansByBookId(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $loans = $this->loanService->getLoansByBookId($args['bookId']);
            $response->getBody()->write(json_encode(['loans' => $loans]));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to retrieve loans', 'details' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function updateLoan(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);

            $id = (int) $args['id'];

            $result = $this->loanService->markLoanAsReturned($id);

            if ($result) {
                $response->getBody()->write(json_encode(['message' => 'Loan marked as returned successfully.']));
                return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['error' => 'Failed to mark loan as returned.']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to update loan', 'details' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }


    public function deleteLoan(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $deleted = $this->loanService->deleteLoan($args['id']);

            if ($deleted) {
                $response->getBody()->write(json_encode(['message' => 'Loan deleted successfully.']));
                return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['error' => 'Loan not found.']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to delete loan', 'details' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function getAllLoans(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $loans = $this->loanService->getAllLoans();
            $response->getBody()->write(json_encode(['loans' => $loans]));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to retrieve loans', 'details' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}
