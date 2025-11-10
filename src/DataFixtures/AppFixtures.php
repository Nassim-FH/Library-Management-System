<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create admin user
        $admin = new User();
        $admin->setEmail('admin@library.com');
        $admin->setUsername('Admin');
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Create regular users
        $user1 = new User();
        $user1->setEmail('john@example.com');
        $user1->setUsername('John Doe');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'user123'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('jane@example.com');
        $user2->setUsername('Jane Smith');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'user123'));
        $manager->persist($user2);

        // Create categories
        $fiction = new Category();
        $fiction->setName('Fiction');
        $fiction->setDescription('Fictional works including novels and short stories');
        $manager->persist($fiction);

        $nonFiction = new Category();
        $nonFiction->setName('Non-Fiction');
        $nonFiction->setDescription('Educational and informative books based on facts');
        $manager->persist($nonFiction);

        $scienceFiction = new Category();
        $scienceFiction->setName('Science Fiction');
        $scienceFiction->setDescription('Imaginative fiction based on scientific concepts');
        $manager->persist($scienceFiction);

        $mystery = new Category();
        $mystery->setName('Mystery');
        $mystery->setDescription('Detective and suspense novels');
        $manager->persist($mystery);

        $biography = new Category();
        $biography->setName('Biography');
        $biography->setDescription('Life stories of notable people');
        $manager->persist($biography);

        $technology = new Category();
        $technology->setName('Technology');
        $technology->setDescription('Books about computers, programming, and innovation');
        $manager->persist($technology);

        // Create authors
        $jkRowling = new Author();
        $jkRowling->setName('J.K. Rowling');
        $jkRowling->setBiography('British author, best known for writing the Harry Potter fantasy series.');
        $manager->persist($jkRowling);

        $georgeOrwell = new Author();
        $georgeOrwell->setName('George Orwell');
        $georgeOrwell->setBiography('English novelist and essayist, journalist and critic. Best known for Animal Farm and 1984.');
        $manager->persist($georgeOrwell);

        $janeAusten = new Author();
        $janeAusten->setName('Jane Austen');
        $janeAusten->setBiography('English novelist known for her six major novels about British landed gentry.');
        $manager->persist($janeAusten);

        $agathaChristie = new Author();
        $agathaChristie->setName('Agatha Christie');
        $agathaChristie->setBiography('English writer known for her detective novels, short story collections, and plays.');
        $manager->persist($agathaChristie);

        $isaacAsimov = new Author();
        $isaacAsimov->setName('Isaac Asimov');
        $isaacAsimov->setBiography('American writer and professor of biochemistry, prolific author of science fiction.');
        $manager->persist($isaacAsimov);

        $stephenKing = new Author();
        $stephenKing->setName('Stephen King');
        $stephenKing->setBiography('American author of horror, supernatural fiction, suspense, crime, science-fiction, and fantasy novels.');
        $manager->persist($stephenKing);

        $robertMartin = new Author();
        $robertMartin->setName('Robert C. Martin');
        $robertMartin->setBiography('American software engineer, instructor, and author, also known as Uncle Bob.');
        $manager->persist($robertMartin);

        $walterIsaacson = new Author();
        $walterIsaacson->setName('Walter Isaacson');
        $walterIsaacson->setBiography('American author and journalist, known for biographies of prominent figures.');
        $manager->persist($walterIsaacson);

        // Create books
        $book1 = new Book();
        $book1->setTitle('1984');
        $book1->setDescription('A dystopian social science fiction novel and cautionary tale about the dangers of totalitarianism.');
        $book1->setIsbn('978-0451524935');
        $book1->setPublicationDate(new \DateTime('1949-06-08'));
        $book1->setCategory($fiction);
        $book1->addAuthor($georgeOrwell);
        $book1->setAvailableCopies(3);
        $manager->persist($book1);

        $book2 = new Book();
        $book2->setTitle('Animal Farm');
        $book2->setDescription('An allegorical novella reflecting events leading up to the Russian Revolution of 1917.');
        $book2->setIsbn('978-0452284241');
        $book2->setPublicationDate(new \DateTime('1945-08-17'));
        $book2->setCategory($fiction);
        $book2->addAuthor($georgeOrwell);
        $book2->setAvailableCopies(2);
        $manager->persist($book2);

        $book3 = new Book();
        $book3->setTitle('Pride and Prejudice');
        $book3->setDescription('A romantic novel of manners following the character development of Elizabeth Bennet.');
        $book3->setIsbn('978-0141439518');
        $book3->setPublicationDate(new \DateTime('1813-01-28'));
        $book3->setCategory($fiction);
        $book3->addAuthor($janeAusten);
        $book3->setAvailableCopies(4);
        $manager->persist($book3);

        $book4 = new Book();
        $book4->setTitle('Murder on the Orient Express');
        $book4->setDescription('A detective novel featuring the Belgian detective Hercule Poirot.');
        $book4->setIsbn('978-0062693662');
        $book4->setPublicationDate(new \DateTime('1934-01-01'));
        $book4->setCategory($mystery);
        $book4->addAuthor($agathaChristie);
        $book4->setAvailableCopies(2);
        $manager->persist($book4);

        $book5 = new Book();
        $book5->setTitle('Foundation');
        $book5->setDescription('The first novel in Isaac Asimov\'s Foundation Trilogy, a science fiction masterpiece.');
        $book5->setIsbn('978-0553293357');
        $book5->setPublicationDate(new \DateTime('1951-06-01'));
        $book5->setCategory($scienceFiction);
        $book5->addAuthor($isaacAsimov);
        $book5->setAvailableCopies(3);
        $manager->persist($book5);

        $book6 = new Book();
        $book6->setTitle('The Shining');
        $book6->setDescription('A horror novel about a family isolated in a haunted hotel during the winter.');
        $book6->setIsbn('978-0307743657');
        $book6->setPublicationDate(new \DateTime('1977-01-28'));
        $book6->setCategory($fiction);
        $book6->addAuthor($stephenKing);
        $book6->setAvailableCopies(2);
        $manager->persist($book6);

        $book7 = new Book();
        $book7->setTitle('Clean Code');
        $book7->setDescription('A handbook of agile software craftsmanship, teaching best practices for writing clean code.');
        $book7->setIsbn('978-0132350884');
        $book7->setPublicationDate(new \DateTime('2008-08-01'));
        $book7->setCategory($technology);
        $book7->addAuthor($robertMartin);
        $book7->setAvailableCopies(5);
        $manager->persist($book7);

        $book8 = new Book();
        $book8->setTitle('Steve Jobs');
        $book8->setDescription('The exclusive biography of Steve Jobs, based on more than forty interviews.');
        $book8->setIsbn('978-1451648539');
        $book8->setPublicationDate(new \DateTime('2011-10-24'));
        $book8->setCategory($biography);
        $book8->addAuthor($walterIsaacson);
        $book8->setAvailableCopies(3);
        $manager->persist($book8);

        $book9 = new Book();
        $book9->setTitle('Emma');
        $book9->setDescription('A novel about youthful hubris and romantic misunderstandings.');
        $book9->setIsbn('978-0141439587');
        $book9->setPublicationDate(new \DateTime('1815-12-23'));
        $book9->setCategory($fiction);
        $book9->addAuthor($janeAusten);
        $book9->setAvailableCopies(2);
        $manager->persist($book9);

        $book10 = new Book();
        $book10->setTitle('I, Robot');
        $book10->setDescription('A collection of nine science fiction short stories exploring the relationship between robots and humanity.');
        $book10->setIsbn('978-0553382563');
        $book10->setPublicationDate(new \DateTime('1950-12-02'));
        $book10->setCategory($scienceFiction);
        $book10->addAuthor($isaacAsimov);
        $book10->setAvailableCopies(4);
        $manager->persist($book10);

        $book11 = new Book();
        $book11->setTitle('And Then There Were None');
        $book11->setDescription('A mystery novel about ten people invited to an isolated island, where they are killed one by one.');
        $book11->setIsbn('978-0062073488');
        $book11->setPublicationDate(new \DateTime('1939-11-06'));
        $book11->setCategory($mystery);
        $book11->addAuthor($agathaChristie);
        $book11->setAvailableCopies(3);
        $manager->persist($book11);

        $book12 = new Book();
        $book12->setTitle('The Clean Coder');
        $book12->setDescription('A code of conduct for professional programmers, with practical advice on estimation, coding, refactoring, and testing.');
        $book12->setIsbn('978-0137081073');
        $book12->setPublicationDate(new \DateTime('2011-05-13'));
        $book12->setCategory($technology);
        $book12->addAuthor($robertMartin);
        $book12->setAvailableCopies(4);
        $manager->persist($book12);

        $manager->flush();
    }
}
