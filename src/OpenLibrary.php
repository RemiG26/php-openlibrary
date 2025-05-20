<?php

namespace PhpOpenlibrary;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PhpOpenlibrary\Models\Author;
use PhpOpenlibrary\Models\Book;

class OpenLibrary {

    private array $defaultFields = ['key','title','cover_i','author_key','author_name','first_publish_year','ratings_sortable'];

    private array $defaults = [
        'sort' => 'rating',
    ];

    private Client $client;

    public function __construct(array $options = [], ClientInterface $client = null) {

        if(array_key_exists('fields', $options)) {
            $additionalFields = explode(',', $options['fields'] ?? '');
            $this->defaultFields = array_unique(array_merge($this->defaultFields, $additionalFields));
            unset($options['fields']);
        }
        
        $this->defaults = array_merge($this->defaults, $options);

        if(!$client) {
            $client = new Client([
                'base_uri' => 'http://openlibrary.org'
            ]);
        }
        $this->client = $client;
    }

    /**
     * Search for a book with a query, by default the search will be done on everything (book title, author name, ...)
     * More details here : https://openlibrary.org/dev/docs/api/search
     * @param string $search
     * @return Book[]
     */
    public function searchBook(string $search, array $options = []): array {

        $fields = $this->defaultFields;
        if(array_key_exists('fields', $options)) {
            $additionalFiels = explode(',', $options['fields'] ?? '');
            $fields = array_unique(array_merge($fields, $additionalFiels));
            unset($options['fields']);
        }

        $response = $this->client->get('search.json', [
            'query' => [
                'q' => $search,
                'fields' => implode(',', $fields),
                ...array_merge($this->defaults, $options)
            ]
        ]);

        $books = [];
        if($response->getStatusCode() === 200) {
            $json = json_decode((string)$response->getBody(), true);
            foreach($json["docs"] as $data) {
                $authors = [];
                if(array_key_exists('author_key', $data)) {
                    foreach($data['author_key'] as $k => $key) {
                        $name = $data['author_name'][$k];
                        $authors[] = new Author(compact('key', 'name'));
                    }
                }
                $books[] = new Book([
                    ...$data,
                    'authors' => $authors
                ]);
            }
        }

        return $books;
    }

    /**
     * Get a book detail
     * @param string $key Book's ID
     * @return Book|null
     */
    public function findBook(string $key, array $options = []): Book|null {
        $books = $this->searchBook("key:/works/$key", $options);
        return count($books) == 0 ? null : $books[0];
    }

    /**
     * Get all books written by an author
     * @param string $key Author's ID
     * @return Book[]
     */
    public function booksForAuthor(string $key, array $options = []): array {
        return $this->searchBook("author_key:$key", $options);
    }

    /**
     * Get author detail
     * @param string $key Author's ID
     * @return Author|null
     */
    public function findAuthor(string $key): Author|null {
        $response = $this->client->get("authors/$key.json");
        if($response->getStatusCode() !== 200) return null;
        $json = json_decode((string) $response->getBody(), true);
        if(array_key_exists('photos', $json) && count($json['photos']) > 0) {
            $json['cover_i'] = $json['photos'][0];
        }
        return new Author($json);
    }
}