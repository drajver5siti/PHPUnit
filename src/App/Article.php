<?php

namespace App;

class Article
{
    public string $title = "";

    public function getSlug(): string
    {
        $slug = $this->title;

        $slug = preg_replace("/\s+/", '_', $slug);
        $slug = preg_replace("/[^\w]/", '', $slug);

        $slug = trim($slug, "_");

        $slug = strtolower($slug);

        return $slug;
    }
}