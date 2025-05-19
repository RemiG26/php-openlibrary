<?php

namespace PhpOpenlibrary\Models;

class Author extends Model {

    protected string $key;

    protected string $name;

    protected string $birth_date;

    protected string $bio;

    public function getKey(): string { 
        return $this->key;
    }    
    
    public function getName(): string { 
        return $this->name;
    }

    public function getBirthDate(): string|null {
        return $this->birth_date ?? null;
    }

    public function getBio(): string|null {
        return $this->bio ?? null;
    }

}