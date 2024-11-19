<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadCallHistory extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'lead_id',
        'called_at',
        'call_status',
        'remarks',
        'timezone'
    ];
    protected $hidden = [
        'lead_id',
        'called_at',
        'timezone',
        'crated_at',
        'created_at',
        'updated_at',
    ];

    public function lead() {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }
}
