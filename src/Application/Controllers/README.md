# Controllers

This folder contains controllers that handle HTTP requests related to users, publications, books, loans, and admin login. Each controller provides endpoints to manage and interact with the corresponding resources.

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
