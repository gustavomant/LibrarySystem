# Library Management System

This system manages library resources, specifically users, publications, books, and loans. Each resource is modeled to capture key information and relationships, enabling users to interact with books and manage loans effectively.

## Prerequisites

Before running the following commands, ensure that your PHP version is **8.2 or higher**. You can check your PHP version using the following command:

```bash
php -v
```

If your PHP version is below 8.2, you must update PHP to a version >= 8.2.

Additionally, make sure all project dependencies are installed by running the following command:

```bash
composer install
```

This command installs all required dependencies specified in the `composer.json` file, including libraries like Phinx and PHPUnit.

## Setup and Commands

Follow these steps to set up and run the system:

### 1. Run Database Migrations

Execute the following command to run the database migrations using [Phinx](https://phinx.org/), which will apply necessary schema changes to your database:

```bash
php vendor/bin/phinx migrate
```

- **Explanation:**
  - `php vendor/bin/phinx`: Runs the Phinx tool installed via Composer.
  - `migrate`: Applies all pending migrations to your database.

- **Usage:**
  - Ensure that your database credentials are configured in the `phinx.yml` file.
  - This command will create or update the necessary tables like `users`, `publications`, `books`, and `loans`.

### 2. Start the PHP Built-in Web Server

To start a local development server, use the following command:

```bash
php -S localhost:8000 -t public
```

- **Explanation:**
  - `php -S localhost:8000`: Starts the PHP built-in web server on `localhost` at port `8000`.
  - `-t public`: Specifies the `public` directory as the document root.

- **Usage:**
  - Once the server is running, open your browser and visit `http://localhost:8000` to access the application.
  - The server will handle requests from the `public` directory and serve the web pages defined in your project.

### 3. Run Tests with PHPUnit

To run unit tests and ensure that the core functionality works as expected, execute the following command:

```bash
./vendor/bin/phpunit tests/
```

- **Explanation:**
  - `./vendor/bin/phpunit`: Runs PHPUnit, the testing framework installed via Composer.
  - `tests/`: The directory containing your test files.

- **Usage:**
  - This command will execute all tests within the `tests/` directory, verifying that the application behaves correctly, such as managing users, books, and loans.

## System Overview

### Users

- **Table:** `users`
- Represents individuals who can interact with the library system (e.g., borrow books).
- **Columns:**
  - `name` (string): The name of the user.
  - `email` (string, unique): The user's email.
  - `created_at` (timestamp): The date the user was created.
  - `updated_at` (timestamp, nullable): The last update timestamp.

### Publications

- **Table:** `publications`
- Represents a unique edition of a book.
- **Columns:**
  - `title` (string): Title of the publication.
  - `author` (string): Author of the publication.
  - `published_year` (integer): Year of publication.
  - `isbn` (string, unique): ISBN identifier.

### Books

- **Table:** `books`
- Represents a physical or digital copy of a specific publication.
- **Columns:**
  - `publication_id` (integer, foreign key): Links to a publication.

### Loans

- **Table:** `loans`
- Represents a loan relationship between users and books.
- **Columns:**
  - `user_id` (integer, foreign key): References the user who borrows the book.
  - `book_id` (integer, foreign key): References the borrowed book.
  - `loan_date` (datetime): The date the book was borrowed.
  - `expected_return_date` (datetime): The expected return date.
  - `return_date` (datetime, nullable): The actual return date.
  - `returned` (boolean): Marks whether the book was returned.

## Relationships

- **Users → Loans:** Users can borrow multiple books. Each loan record links to a unique user.
- **Publications → Books:** Each publication represents an edition, while each book represents an individual copy of that edition.
- **Books → Loans:** Each loan links to a specific copy of a book.

This setup allows tracking users, managing individual copies of publications, and monitoring loan statuses.

## Controllers and Endpoints

### UserController

Manages operations related to users who can borrow books.

- **GET /users**: Retrieve a list of all users.
- **GET /users/{id}**: Retrieve details of a specific user by ID.
- **POST /users**: Create a new user with a name and email.
- **PUT /users/{id}**: Update an existing user's details.
- **DELETE /users/{id}**: Delete a user by ID.

### PublicationController

Handles data related to publications, representing unique editions of books.

- **GET /publications**: Retrieve a list of all publications.
- **GET /publications/{id}**: Retrieve details of a specific publication by ID.
- **POST /publications**: Create a new publication with title, author, published year, and ISBN.
- **PUT /publications/{id}**: Update an existing publication's details.
- **DELETE /publications/{id}**: Delete a publication by ID.

### BookController

Manages individual copies of publications (books) available for loan.

- **GET /books**: Retrieve a list of all books.
- **GET /books/{id}**: Retrieve details of a specific book by ID, including associated publication details.
- **POST /books**: Create a new book linked to a specific publication.
- **DELETE /books/{id}**: Delete a book by ID.

### LoanController

Handles loan operations, managing the lending of books to users.

- **GET /loans**: Retrieve a list of all loans, including details of the borrowed books and users.
- **GET /loans/{id}**: Retrieve details of a specific loan by ID.
- **POST /loans**: Create a new loan, linking a user and a book. Checks if the user has any expired pending loans.
- **PUT /loans/{id}/return**: Mark a loan as returned, updating the return date and status.
- **DELETE /loans/{id}**: Delete a loan by ID.

### LoginController

Handles the login process for the system administrator.

- **POST /login**: This route authenticates a system administrator with hardcoded credentials. The credentials (username and password) are not stored in a database but are predefined in the code itself.

  - **Admin Authentication**: When the `POST /login` endpoint is called, it verifies the credentials against the hardcoded username and password.
  - **JWT Token Generation**: Upon successful login, a JSON Web Token (JWT) is issued to allow the admin to access protected routes within the system. This token must be included in the `Authorization` header as `Bearer <token>` for accessing secure routes.

  > **Note:** This hardcoded approach is intended for simplicity and quick setup. For a production environment, it is recommended to manage admin credentials in a secure, database-backed system.

## Notes

- Each controller is structured to separate concerns, focusing on one resource type per controller.
- **Error Handling**: If certain conditions (e.g., a user with expired loans) prevent an operation, appropriate error messages and status codes are returned.
- **Validation**: Each endpoint includes basic validation for required fields and data integrity.
- **Authorization**: Endpoints protected by the admin login route require a valid JWT token in the request header for access.

These endpoints form the core of the library system, enabling management of users, publications, individual book copies, and loan operations, along with secure access for system administrators.
