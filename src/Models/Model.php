<?php

namespace PhpOpenlibrary\Models;

abstract class Model {

    /**
     * Common property containing cover ID
     * @var int $cover_i 
     */
    protected int $cover_i;

    /**
     * Additionnal information requested
     * @var array $attributes
     */
    protected array $attributes = [];

    public function __construct(array $data)
    {
        // Automatically hydrate model property with array
        foreach($data as $k => $v) {
            if(property_exists($this, $k)) {
                if($k === 'key') {
                    $exploded = explode('/', $v);
                    $this->$k = end($exploded);
                } else {
                    $this->$k = $v;
                }
            } else {
                $this->attributes[$k] = $v;
            }
        }

    }

    /**
     * Generate the Cover asset URL
     * @return string|null
     */
    public function getCover(string $size = 'M'): string|null {
        if(!$this->cover_i) return null;
        if(!in_array($size, ['S', 'M', 'L'])) $size = 'M'; // By default gather medium cover size
        return "https://covers.openlibrary.org/b/id/$this->cover_i-$size.jpg?default=false";
    }

    /**
     * @param string $attribute Name of the attribute
     * @return mixed Null if the attribute does not exists, mixed otherwise
     */
    public function getAttribute(string $attribute): mixed {
        return array_key_exists($attribute, $this->attributes) ? $this->attributes[$attribute] : null;
    }

}