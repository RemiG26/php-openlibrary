<?php

namespace PhpOpenlibrary\Models;

class Book extends Model {

    protected string $key;

    protected string $title;

    protected int $first_publish_year;

    protected float $ratings_average;

    /**
     * @var Author[]
     */
    protected array $authors;

    public function getKey(): string {
        return $this->key;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getPublishYear(): int|null {
        return $this->first_publish_year ?? null;
    }

    public function getRatings(): float|null {
        return $this->ratings_average ?? null;
    }

    public function getAuthors(): array {
        return $this->authors ?? [];
    }


}