# INSTALLATION GUIDE

## Quick Start

1. **Install Dependencies**

   ```powershell
   composer install
   ```

2. **Configure Database**

   - Edit `.env` file
   - Update `DATABASE_URL` with your database credentials:

   ```
   DATABASE_URL="mysql://root:@127.0.0.1:3306/library_db?serverVersion=8.0.32&charset=utf8mb4"
   ```

3. **Run Setup Script**

   ```powershell
   .\setup.ps1
   ```

   This will:

   - Create the database
   - Run migrations
   - Load sample data
   - Clear cache

4. **Start the Server**

   ```powershell
   symfony server:start
   ```

   OR

   ```powershell
   php -S localhost:8000 -t public
   ```

5. **Access the Application**
   - Homepage: http://localhost:8000
   - Admin Panel: http://localhost:8000/admin

## Sample Credentials

**Admin Account:**

- Email: admin@library.com
- Password: admin123

**User Account:**

- Email: john@example.com
- Password: user123

## Manual Setup (Alternative)

If you prefer to set up manually:

```powershell
# 1. Install dependencies
composer install

# 2. Create database
php bin/console doctrine:database:create

# 3. Create and run migrations
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# 4. Load sample data
php bin/console doctrine:fixtures:load

# 5. Clear cache
php bin/console cache:clear

# 6. Start server
symfony server:start
```

## Troubleshooting

**Database Connection Error:**

- Verify MySQL is running
- Check database credentials in `.env`

**Permission Errors:**

- Ensure `var/` directory is writable

**Port Already in Use:**

- Use a different port: `php -S localhost:8001 -t public`

For more details, see README.md
