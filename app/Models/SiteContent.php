<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = ['key', 'title', 'subtitle', 'button_text', 'link', 'image', 'body', 'status'];
}