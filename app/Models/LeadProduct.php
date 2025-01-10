<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadProduct extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lead_id',
        'supplier_id',
        'product_id',
        'notes',
        'area_id',
        'manager_id',
        'assistant_id',
    ];

    public function lead() {
        return $this->belongsTo(Lead::class, 'id', 'lead_id');
    }
}
