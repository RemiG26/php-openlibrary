<?php

use GuzzleHttp\Psr7\Response;
use PhpOpenlibrary\Models\Book;
use PhpOpenlibrary\OpenLibrary;
use function GuzzleHttp\json_encode;

function getJsonBook(string $key): array {
    return [
        "key" => "/works/$key",
        "title" => "Bred to kill",
        "cover_i" => 8196511,
        "first_publish_year" => 2015,
        "author_key" => [
            "OL6978429A"
        ],
        "author_name" => [
            "Franck Thilliez"
        ],
        "ratings_average" => 2.0
    ];
}

it('Can search for books', function () {
    $key = "OL17885396W";
    $book = getJsonBook($key);
    $lib = new OpenLibrary(getLib([
        new Response(200, [], json_encode([
            "docs" => [
                $book,
                $book
            ]
        ]))
    ]));

    $books = $lib->searchBook('Thilliez');

    expect($books)->toBeArray();
    expect($books)->toHaveCount(2);
    expect($books[0])->toBeInstanceOf(Book::class);

    expect($books[0]->getKey())->toBe($key);
    expect($books[0]->getTitle())->toBe($book["title"]);
    expect($books[0]->getCover())->toBe("https://covers.openlibrary.org/b/id/8196511-M.jpg?default=false");
    expect($books[0]->getPublishYear())->toBe($book["first_publish_year"]);
    expect($books[0]->getRatings())->toBe($book["ratings_average"]);
    expect($books[0]->getAuthors())->toBeArray();
    expect($books[0]->getAuthors())->toHaveCount(1);
    expect($books[0]->getAuthors()[0]->getName())->toBe($book["author_name"][0]);
    expect($books[0]->getAuthors()[0]->getKey())->toBe($book["author_key"][0]);
});

it('Can retrieve an author', function() {
    $key = "OL17885396W";
    $originalBook = getJsonBook($key);
    $lib = new OpenLibrary(getLib([
        new Response(200, [], json_encode([
            "docs" => [$originalBook]
        ]))
    ]));

    $book = $lib->findBook($key);

    expect($book)->not()->toBeNull();
    expect($book)->toBeInstanceOf(Book::class);

    expect($book->getKey())->toBe($key);
    expect($book->getTitle())->toBe($originalBook["title"]);
    expect($book->getCover())->toBe("https://covers.openlibrary.org/b/id/8196511-M.jpg?default=false");
    expect($book->getPublishYear())->toBe($originalBook["first_publish_year"]);
    expect($book->getRatings())->toBe($originalBook["ratings_average"]);
    expect($book->getAuthors())->toBeArray();
    expect($book->getAuthors())->toHaveCount(1);
    expect($book->getAuthors()[0]->getName())->toBe($originalBook["author_name"][0]);
    expect($book->getAuthors()[0]->getKey())->toBe($originalBook["author_key"][0]);
});