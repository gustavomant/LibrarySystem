<?php

namespace Src\Presentation\Routes;

use Src\Application\Controllers\LoginController;
use Src\Application\Controllers\BookController;
use Src\Application\Controllers\UserController;
use Src\Application\Controllers\PublicationController;
use Src\Application\Controllers\LoanController;
use Src\Application\Services\UserService;
use Src\Application\Services\BookService;
use Src\Application\Services\PublicationService;
use Src\Application\Services\LoanService;
use Src\Infrastructure\Persistence\UserRepository;
use Src\Infrastructure\Persistence\BookRepository;
use Src\Infrastructure\Persistence\PublicationRepository;
use Src\Infrastructure\Persistence\LoanRepository;
use Src\Infrastructure\Persistence\DatabaseConnection;
use Src\Infrastructure\Middleware\AuthMiddleware;

$db = DatabaseConnection::getConnection();

$bookRepository = new BookRepository($db);
$userRepository = new UserRepository($db);
$publicationRepository = new PublicationRepository($db);
$loanRepository = new LoanRepository($db);

$userService = new UserService($userRepository);
$bookService = new BookService($bookRepository, $publicationRepository);
$publicationService = new PublicationService($publicationRepository);
$loanService = new LoanService($loanRepository, $userRepository, $bookRepository);

$bookController = new BookController($bookService);
$userController = new UserController($userService);
$publicationController = new PublicationController($publicationService);
$loanController = new LoanController($loanService);

$loginController = new LoginController();

$authMiddleware = new AuthMiddleware($app->getResponseFactory());

$app->post('/login', [$loginController, 'login']);

$app->group('', function () use ($app, $bookController, $userController, $publicationController, $loanController) {
    $app->post('/books', [$bookController, 'createBook']);
    $app->get('/books', [$bookController, 'listBooks']);
    $app->get('/books/{id}', [$bookController, 'getBook']);
    $app->delete('/books/{id}', [$bookController, 'deleteBook']);

    $app->post('/users', [$userController, 'createUser']);
    $app->get('/users', [$userController, 'listUsers']);
    $app->get('/users/{id}', [$userController, 'getUser']);
    $app->put('/users/{id}', [$userController, 'updateUser']);
    $app->delete('/users/{id}', [$userController, 'deleteUser']);

    $app->post('/publications', [$publicationController, 'createPublication']);
    $app->get('/publications', [$publicationController, 'listPublications']);
    $app->get('/publications/{id}', [$publicationController, 'getPublication']);
    $app->put('/publications/{id}', [$publicationController, 'updatePublication']);
    $app->delete('/publications/{id}', [$publicationController, 'deletePublication']);

    $app->post('/loans', [$loanController, 'createLoan']);
    $app->get('/loans/{id}', [$loanController, 'getLoanById']);
    $app->get('/loans/user/{userId}', [$loanController, 'getLoansByUserId']);
    $app->get('/loans/book/{bookId}', [$loanController, 'getLoansByBookId']);
    $app->put('/loans/{id}', [$loanController, 'updateLoan']);
    $app->delete('/loans/{id}', [$loanController, 'deleteLoan']);
    $app->get('/loans', [$loanController, 'getAllLoans']);
})->add($authMiddleware);
