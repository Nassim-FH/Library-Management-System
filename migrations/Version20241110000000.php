<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Initial migration - creates all tables for the Library Management System
 */
final class Version20241110000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates all tables for Library Management System';
    }

    public function up(Schema $schema): void
    {
        // User table
        $this->addSql('CREATE TABLE `user` (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(180) NOT NULL,
            username VARCHAR(100) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Category table
        $this->addSql('CREATE TABLE category (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(100) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Author table
        $this->addSql('CREATE TABLE author (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(150) NOT NULL,
            biography LONGTEXT DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Book table
        $this->addSql('CREATE TABLE book (
            id INT AUTO_INCREMENT NOT NULL,
            category_id INT DEFAULT NULL,
            title VARCHAR(255) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            isbn VARCHAR(13) NOT NULL,
            publication_date DATE DEFAULT NULL,
            available_copies INT NOT NULL,
            INDEX IDX_CBE5A33112469DE2 (category_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Book-Author junction table
        $this->addSql('CREATE TABLE book_author (
            book_id INT NOT NULL,
            author_id INT NOT NULL,
            INDEX IDX_9478D34516A2B381 (book_id),
            INDEX IDX_9478D345F675F31B (author_id),
            PRIMARY KEY(book_id, author_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // BorrowingHistory table
        $this->addSql('CREATE TABLE borrowing_history (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            book_id INT NOT NULL,
            borrow_date DATETIME NOT NULL,
            return_date DATETIME DEFAULT NULL,
            status VARCHAR(20) NOT NULL,
            due_date DATETIME DEFAULT NULL,
            INDEX IDX_5C5174D5A76ED395 (user_id),
            INDEX IDX_5C5174D516A2B381 (book_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Foreign keys
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D34516A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D345F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE borrowing_history ADD CONSTRAINT FK_5C5174D5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE borrowing_history ADD CONSTRAINT FK_5C5174D516A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33112469DE2');
        $this->addSql('ALTER TABLE book_author DROP FOREIGN KEY FK_9478D34516A2B381');
        $this->addSql('ALTER TABLE book_author DROP FOREIGN KEY FK_9478D345F675F31B');
        $this->addSql('ALTER TABLE borrowing_history DROP FOREIGN KEY FK_5C5174D5A76ED395');
        $this->addSql('ALTER TABLE borrowing_history DROP FOREIGN KEY FK_5C5174D516A2B381');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE book_author');
        $this->addSql('DROP TABLE borrowing_history');
    }
}
