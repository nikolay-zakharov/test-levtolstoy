<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211016163957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        foreach (range(1, 10000) as $authorId) {
            $this->addSql("insert into authors (id, name) values (:id, :name)", ['id' => $authorId, 'name' => 'Лев Толстой']);
        }
        foreach (range(1, 10000) as $bookId) {
            $this->addSql("insert into books (id, name) values (:id, :name)", ['id' => $bookId, 'name' => 'Война и мир']);
            $this->addSql("insert into authors_books (author_id, book_id) values (:author_id, :book_id)", [
                'author_id' => $bookId,
                'book_id' => $bookId,
            ]);
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
