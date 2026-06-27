<?php

namespace App\Models;

class Page
{
    public int $id;
    public string $slug;
    public string $title;
    public ?string $subtitle;
    public ?string $content;
    public ?string $meta_title;
    public ?string $meta_description;
    public string $section;
    public bool $is_active;
    public int $sort_order;
    public string $created_at;
    public ?string $updated_at;
}
