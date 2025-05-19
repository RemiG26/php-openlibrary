<?php

namespace PhpOpenlibrary\Models;

abstract class Model {

    /**
     * @var int $cover_i Common property containing cover ID
     */
    protected int $cover_i;

    public function __construct(array $data)
    {
        // Automatically hydrate model property with ar array
        foreach($data as $k => $v) {
            if(property_exists($this, $k)) {
                if($k === 'key') {
                    $exploded = explode('/', $v);
                    $this->$k = end($exploded);
                } else {
                    $this->$k = $v;
                }
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

}