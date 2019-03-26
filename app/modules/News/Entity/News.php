<?php

namespace App\Module\News\Entity;

use App\System\Mvc\Model\Entity;

class News extends Entity
{
    public $id;
    public $title;
    public $slug;
    public $description;
    public $is_active = 0;
    public $created_at;
    public $updated_at;
    public $deleted_at;

}