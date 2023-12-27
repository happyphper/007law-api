<?php

namespace App\Packages\GPT;

class DFANode
{
    public bool $isEndOfWord;
    public array $children;

    public function __construct()
    {
        $this->isEndOfWord = false;
        $this->children = [];
    }
}
