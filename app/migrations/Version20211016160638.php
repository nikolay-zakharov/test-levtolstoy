<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211016160638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $sql =<<<SQL
create table authors_books (
    id bigint unsigned auto_increment primary key,
    author_id bigint unsigned not null,
    book_id bigint unsigned not null,
    constraint authors_books_author_id_book_id_uindex unique (author_id, book_id),
    
    constraint authors_books_book_id__fk
        foreign key (book_id) references books (id)
            on delete cascade,
    constraint authors_books_author_id_fk
        foreign key (author_id) references authors (id)
            on delete cascade
);
SQL;


        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("drop table authors_books");
    }
}
