<?php

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use PhpOpenlibrary\Models\Author;
use PhpOpenlibrary\Models\Book;
use PhpOpenlibrary\OpenLibrary;
use function GuzzleHttp\json_encode;

it('can retrieve and author', function () {
    $authorJson = [
        "name" => "Franck Thilliez",
        "bio" => "Biographie",
        "birth_date" => "31 July 1965",
        "created" => [
            "type" => "/type/datetime",
            "value" => "2011-09-23T17:32:42.423421"
        ],
        "photos" => [
            5543033
        ],
        "last_modified" => [
            "type" => "/type/datetime",
            "value" => "2011-09-23T17:32:42.423421"
        ],
        "latest_revision" => 1,
        "key" => "/authors/OL6978429A",
        "type" => [
            "key" => "/type/author"
        ],
        "revision" => 1
    ];
    $lib = new OpenLibrary([], getFakeClient([
        new Response(200, [], json_encode($authorJson))
    ]));
    $author = $lib->findAuthor('OL6978429A');
    expect($author)->not()->toBeNull();
    expect($author)->toBeInstanceOf(Author::class);
    expect($author->getKey())->toBe('OL6978429A');
    expect($author->getName())->toBe($authorJson["name"]);
    expect($author->getBio())->toBe($authorJson["bio"]);
    expect($author->getBirthDate())->toBe($authorJson["birth_date"]);
    expect(value: $author->getCover())->toBe("https://covers.openlibrary.org/b/id/5543033-M.jpg?default=false");
});

it('can list books of an author', function () {
    $lib = new OpenLibrary([], getFakeClient([
        new Response(200, [], json_encode([
            "docs" => [getJsonBook("OL17885396W"), getJsonBook("OL17885396Z")]
        ]))
    ]));
    $books = $lib->booksForAuthor("OL6978429A");
    expect($books)->toBeArray();
    expect($books)->toHaveCount(2);
    expect($books[0])->toBeInstanceOf(Book::class);
});

it("throws exception if API returned an error", function () {
    $lib = new OpenLibrary([], getFakeClient([
        new Response(422, [], ""),
        new Response(422, [], "")
    ]));

    expect(fn() => $lib->booksForAuthor("OL6978429A"))->toThrow(ClientException::class);
    expect(fn() => $lib->findAuthor("OL6978429A"))->toThrow(ClientException::class);
});