# Library Management System

This system manages library resources, specifically users, publications, books, and loans. Each resource is modeled to capture key information and relationships, enabling users to interact with books and manage loans effectively.

## Resources Overview

### Users
- **Table:** `users`
- **Description:** Represents individuals who can interact with the library system, such as borrowing books.
- **Columns:**
  - `name` (string, max 50): The name of the user.
  - `email` (string, max 100, unique): The email of the user.
  - `created_at` (timestamp): Timestamp when the user was created.
  - `updated_at` (timestamp, nullable): Timestamp for the last update on the user.

### Publications
- **Table:** `publications`
- **Description:** Represents a specific edition of a book, with details unique to that edition.
- **Columns:**
  - `title` (string, max 255): Title of the publication.
  - `author` (string, max 100): Author of the publication.
  - `published_year` (integer, 4 digits): The year the edition was published.
  - `isbn` (string, max 13, unique): ISBN identifier of the publication.

### Books
- **Table:** `books`
- **Description:** Represents a physical or digital copy of a specific publication. Each book record is linked to a unique publication.
- **Columns:**
  - `publication_id` (integer, foreign key): References a `publication` record, linking the book to its edition.

### Loans
- **Table:** `loans`
- **Description:** Establishes a relationship between users and books, tracking loans of specific books to users.
- **Columns:**
  - `user_id` (integer, foreign key): References a `user` record, linking the loan to a specific user.
  - `book_id` (integer, foreign key): References a `book` record, indicating which book was borrowed.
  - `loan_date` (datetime): The date and time when the loan was created.
  - `expected_return_date` (datetime): The expected date for returning the loaned book.
  - `return_date` (datetime, nullable): The actual date the book was returned.
  - `returned` (boolean, default false): Indicates if the loaned book has been returned.

## Relationships

- **Users → Loans**: Users can borrow multiple books, and each loan record links to a unique user.
- **Publications → Books**: Each publication represents a unique edition, while each book represents an individual copy of that edition.
- **Books → Loans**: Each loan is linked to a specific copy of a publication.

This setup allows tracking users, managing individual copies of publications, and monitoring loan statuses.
