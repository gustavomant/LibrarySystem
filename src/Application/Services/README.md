# Services

The `services` folder contains service classes that encapsulate the business logic of the application. Each service provides specific functionalities, enabling interaction with domain entities and repositories while enforcing business rules.

## LoanService

The `LoanService` class handles operations related to loans. It interacts with `LoanRepositoryInterface` and `UserRepositoryInterface` to manage loan records and enforce rules associated with loan creation and return.

### Loan Creation Rule

When a new loan is created, `LoanService` applies a business rule to check if the user has any overdue loans. This rule is implemented to prevent users with overdue books from borrowing additional ones until they return the overdue items.

#### Steps in `createLoan` Method:

1. **User Verification**: The service first checks if the user exists. If not, an exception is thrown.
2. **Check for Expired Loans**: The service uses the `hasExpiredPendingLoans` method to verify if the user has any pending loans that are past their expected return date. If so, a `DomainException` is thrown, indicating that the user cannot borrow new books.
3. **Loan Creation**: If the user passes the checks, a new loan record is created with the specified `userId`, `bookId`, and `loanDurationDays`, setting the `loanDate` and calculating the `expectedReturnDate` accordingly.

### Additional Methods in LoanService

- **markLoanAsReturned**: Marks a loan as returned by updating the `return_date` and `returned` status.
- **getLoanById**: Retrieves a loan by its ID.
- **getLoansByUserId**: Retrieves all loans associated with a user.
- **getLoansByBookId**: Retrieves all loans associated with a specific book.
- **deleteLoan**: Deletes a loan by its ID.
- **getAllLoans**: Retrieves all loans in the system.

### `hasExpiredPendingLoans` Method

This private method determines if the specified user has any overdue loans. It checks each loan associated with the user and verifies:
- **Return Status**: If the loan is still pending (i.e., `returned` is `false`).
- **Expected Return Date**: If the `expectedReturnDate` has already passed.

If there is at least one overdue loan, the method returns `true`, preventing the user from creating a new loan.

### Error Handling

The `LoanService` uses exceptions to enforce business rules and validation. For example:
- `InvalidArgumentException` is thrown when a user does not exist.
- `DomainException` is thrown if the user has overdue loans, enforcing the rule before proceeding with the loan creation.

---

These services are designed to provide a clear separation of concerns, keeping business logic isolated from controllers and promoting a clean architecture for the application.
