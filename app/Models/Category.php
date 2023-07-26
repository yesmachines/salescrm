<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Quotation;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'status'
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function childs()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->with('childs')->orderBy('id', 'DESC');
    }
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
