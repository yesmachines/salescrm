<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadShare extends Model {

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
        'shared_by',
        'shared_to',
        'notes',
    ];

    public function lead() {
        return $this->belongsTo(Lead::class, 'id', 'lead_id');
    }
}
