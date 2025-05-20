<?php

use PhpOpenlibrary\Models\Book;
use PhpOpenlibrary\OpenLibrary;

describe('ACTUAL API', function() {

    it('can retrieve a book', function() {
        $lib = new OpenLibrary();

        $key = "OL24326103W";
        $book = $lib->findBook($key);

        expect($book)->not()->toBeNull();
        expect($book)->toBeInstanceOf(Book::class);

        expect($book->getKey())->toBe($key);
        expect($book->getTitle())->toBe("Puzzle");
        expect($book->getCover())->toBe("https://covers.openlibrary.org/b/id/10846111-M.jpg?default=false");
        expect($book->getPublishYear())->toBe(2013);
        expect($book->getRatings())->toBeGreaterThan(0);
        expect($book->getAuthors())->toHaveCount(1);
        expect($book->getAuthors()[0]->getName())->toBe("Franck Thilliez");
        expect($book->getAuthors()[0]->getKey())->toBe("OL6978429A");
    });

    it('can search a book', function() {
        $lib = new OpenLibrary();

        $books = $lib->searchBook("title:Harry Potter");
        expect($books)->toBeArray();
        expect(count($books))->toBeGreaterThan(1);
        expect($books[0]->getTitle())->toContain("Harry Potter");
        expect($books[0]->getRatings())->toBeGreaterThanOrEqual($books[1]->getRatings());
    });

    it('can list books', function() {
        $lib = new OpenLibrary();

        $key = "OL6978429A";
        $books = $lib->booksForAuthor($key);
        expect($books)->toBeArray();
        expect(count($books))->toBeGreaterThan(0);
        expect($books[0]->getAuthors()[0]->getKey())->toBe($key);
    });

    it('can retrieve an author from', function() {
        $lib = new OpenLibrary();

        $key = "OL6978429A";
        $author = $lib->findAuthor($key);
        expect($author)->not()->toBeNull();
        expect($author->getKey())->toBe($key);
    });

    it('can change options individually', function() {
        $lib = new OpenLibrary();

        $books = $lib->searchBook("title:Harry Potter", [
            'fields' => 'edition_count',
            'sort' => 'new',
            'limit' => 2
        ]);
        expect($books)->toHaveCount(2);
        expect($books[0]->getAttribute('edition_count'))->not()->toBeNull();
        expect($books[0]->getPublishYear())->toBeGreaterThanOrEqual($books[1]->getPublishYear());
    });

    it('can change options globally', function() {
        $lib = new OpenLibrary([
            'fields' => 'edition_count',
            'sort' => 'new',
            'limit' => 2
        ]);

        $books = $lib->searchBook("title:Harry Potter");
        expect($books)->toHaveCount(2);
        expect($books[0]->getAttribute('edition_count'))->not()->toBeNull();
        expect($books[0]->getPublishYear())->toBeGreaterThanOrEqual($books[1]->getPublishYear());
    });

})->skip();

