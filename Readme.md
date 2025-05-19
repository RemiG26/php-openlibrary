php-openlibrary
================

A PHP client library for the Open Library API.

This library aims to facilitate the use of existing Open Library for READ-ONLY. It **DOES NOT** provide methods that requires authentication (create or edit works for example).

- [Installation](#installation)
- [Usage](#usage)
- [Testing](#testing)

## Installation

To install the package, use composer:
```bash
composer require remig26/php-openlibrary
```

## Usage

```php
<?php

use PhpOpenlibrary\OpenLibrary;

$api = new OpenLibrary();
$books = $api->searchBook("Name of a book");

$author = $api->findAuthor("OL234664A"); // Author's OLID
$authorsBooks = $api->booksForAuthor("OL234664A");

// Advanced search
$books = $api->searchBook("title:The,place:New York")
```

## Testing

To run test cases (from the php-openlibrary directory):
```bash
./vendor/bin/pest
```