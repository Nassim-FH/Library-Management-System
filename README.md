# Library Management System

A comprehensive library management system built with Symfony 6.4, featuring user authentication, book borrowing, and full admin panel for managing books, authors, and categories.

## Features

### User Features

- **Browse Books**: Search and filter books by title, author, or category
- **Book Details**: View detailed information about each book
- **Borrow Books**: Registered users can borrow available books
- **Borrowing History**: Track current and past borrowings
- **Return Books**: Easy book return system
- **User Registration & Authentication**: Secure login system

### Admin Features

- **Dashboard**: Overview of library statistics and active borrowings
- **Manage Books**: Full CRUD operations for books
- **Manage Authors**: Add, edit, and delete authors
- **Manage Categories**: Organize books into categories
- **Track Borrowings**: Monitor all active and overdue borrowings

### Technical Features

- Responsive design using Bootstrap 5
- Pagination for large datasets
- Form validation
- Many-to-many and one-to-many relationships
- Secure password hashing
- Role-based access control (ROLE_USER, ROLE_ADMIN)
- Data fixtures for sample data

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher (or MariaDB 10.3+)
- Symfony CLI (optional but recommended)

## Installation

### 1. Clone or Download the Project

Navigate to the project directory:

```powershell
cd "c:\Users\Nassim\Documents\synfony"
```

### 2. Install Dependencies

```powershell
composer install
```

### 3. Configure Database

Edit the `.env` file and update the `DATABASE_URL` with your database credentials:

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/library_db?serverVersion=8.0.32&charset=utf8mb4"
```

Replace:

- `root` with your MySQL username
- Leave password blank or add after the colon if you have one
- `library_db` with your desired database name
- `8.0.32` with your MySQL version

### 4. Create Database

```powershell
php bin/console doctrine:database:create
```

### 5. Run Migrations

```powershell
php bin/console doctrine:migrations:migrate
```

If migrations don't exist, create them:

```powershell
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### 6. Load Sample Data (Optional)

Load fixtures to populate the database with sample books, authors, categories, and users:

```powershell
php bin/console doctrine:fixtures:load
```

**Sample Credentials:**

- **Admin**:
  - Email: `admin@library.com`
  - Password: `admin123`
- **Regular User**:
  - Email: `john@example.com`
  - Password: `user123`

### 7. Start the Development Server

Using Symfony CLI (recommended):

```powershell
symfony server:start
```

Or using PHP built-in server:

```powershell
php -S localhost:8000 -t public
```

### 8. Access the Application

Open your browser and navigate to:

- **Homepage**: `http://localhost:8000`
- **Login**: `http://localhost:8000/login`
- **Register**: `http://localhost:8000/register`
- **Admin Dashboard**: `http://localhost:8000/admin` (requires admin login)

## Project Structure

```
synfony/
├── config/              # Configuration files
│   ├── packages/       # Package configurations
│   │   ├── doctrine.yaml
│   │   ├── security.yaml
│   │   ├── twig.yaml
│   │   └── ...
│   ├── routes.yaml     # Route definitions
│   └── services.yaml   # Service container config
├── public/             # Public web directory
│   └── index.php      # Front controller
├── src/
│   ├── Controller/     # Controllers
│   │   ├── Admin/     # Admin controllers
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── BookController.php
│   │   │   ├── AuthorController.php
│   │   │   └── CategoryController.php
│   │   ├── HomeController.php
│   │   ├── SecurityController.php
│   │   ├── RegistrationController.php
│   │   └── BorrowingController.php
│   ├── Entity/         # Doctrine entities
│   │   ├── User.php
│   │   ├── Book.php
│   │   ├── Author.php
│   │   ├── Category.php
│   │   └── BorrowingHistory.php
│   ├── Form/          # Form types
│   │   ├── BookType.php
│   │   ├── AuthorType.php
│   │   ├── CategoryType.php
│   │   └── RegistrationFormType.php
│   ├── Repository/    # Repository classes
│   │   ├── BookRepository.php
│   │   ├── AuthorRepository.php
│   │   ├── CategoryRepository.php
│   │   ├── UserRepository.php
│   │   └── BorrowingHistoryRepository.php
│   ├── Service/       # Business logic services
│   │   └── BorrowingService.php
│   ├── DataFixtures/  # Database fixtures
│   │   └── AppFixtures.php
│   └── Kernel.php     # Application kernel
├── templates/         # Twig templates
│   ├── base.html.twig
│   ├── home/
│   ├── admin/
│   ├── security/
│   ├── registration/
│   └── borrowing/
├── .env              # Environment variables
├── composer.json     # PHP dependencies
└── README.md        # This file
```

## Database Schema

### Entities and Relationships

**Book**

- `id`: Primary key
- `title`: String(255)
- `description`: Text
- `isbn`: String(13)
- `publicationDate`: Date
- `availableCopies`: Integer
- Relations: Many-to-Many with Authors, Many-to-One with Category

**Author**

- `id`: Primary key
- `name`: String(150)
- `biography`: Text
- Relations: Many-to-Many with Books

**Category**

- `id`: Primary key
- `name`: String(100)
- `description`: Text
- Relations: One-to-Many with Books

**User**

- `id`: Primary key
- `username`: String(100)
- `email`: String(180) - unique
- `password`: Hashed password
- `roles`: Array (ROLE_USER, ROLE_ADMIN)
- Relations: One-to-Many with BorrowingHistory

**BorrowingHistory**

- `id`: Primary key
- `borrowDate`: DateTime
- `dueDate`: DateTime
- `returnDate`: DateTime (nullable)
- `status`: String (borrowed, returned, overdue)
- Relations: Many-to-One with User, Many-to-One with Book

## Usage Guide

### For Users

1. **Register an Account**: Go to `/register` and create an account
2. **Browse Books**: Use the search and filter options on the homepage
3. **Borrow a Book**: Click on a book, then click "Borrow This Book" button
4. **View Borrowings**: Go to "My Borrowings" to see active and past borrowings
5. **Return a Book**: In "My Borrowings", click "Return Book" button

### For Administrators

1. **Login as Admin**: Use admin credentials
2. **Access Admin Panel**: Click "Admin Panel" in navigation
3. **Manage Books**: Add, edit, or delete books
4. **Manage Authors**: Add author information and biographies
5. **Manage Categories**: Organize books into categories
6. **Monitor Borrowings**: View active borrowings and overdue books

## Key Features Explained

### Search and Filter

- Search books by title, author name, or description
- Filter by category
- Filter by specific author
- Pagination for better performance

### Borrowing System

- Books are automatically unavailable when all copies are borrowed
- Due date is automatically set to 14 days from borrow date
- System tracks overdue books
- Users cannot borrow the same book twice simultaneously

### Security

- Passwords are hashed using Symfony's password hasher
- CSRF protection on forms
- Role-based access control
- Admin routes protected with `ROLE_ADMIN`
- Borrowing routes protected with `ROLE_USER`

## Troubleshooting

### Database Connection Error

- Check your `.env` file for correct database credentials
- Ensure MySQL/MariaDB is running
- Verify the database exists

### Permission Errors

- Ensure `var/` directory is writable: `chmod -R 777 var/`

### Clear Cache

```powershell
php bin/console cache:clear
```

### Regenerate Database

```powershell
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

## Development Commands

### Create New Entity

```powershell
php bin/console make:entity
```

### Create New Controller

```powershell
php bin/console make:controller
```

### Create New Form

```powershell
php bin/console make:form
```

### Create Migration

```powershell
php bin/console make:migration
```

### Debug Routes

```powershell
php bin/console debug:router
```

## Technologies Used

- **Symfony 6.4**: PHP framework
- **Doctrine ORM**: Database abstraction layer
- **Twig**: Template engine
- **Bootstrap 5**: CSS framework
- **KnpPaginatorBundle**: Pagination
- **Symfony Security**: Authentication and authorization
- **Doctrine Fixtures**: Sample data generation

## Contributing

Feel free to fork this project and submit pull requests for any improvements.

## License

This project is open-source and available under the MIT License.

## Support

For issues or questions:

1. Check the troubleshooting section
2. Review Symfony documentation: https://symfony.com/doc
3. Check Doctrine documentation: https://www.doctrine-project.org

## Author

Created as a comprehensive Library Management System demonstration project.

---

**Enjoy managing your library! 📚**
