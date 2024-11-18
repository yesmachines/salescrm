<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadCallHistory extends Model {

    use UuidTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $hidden = [
        'lead_id',
        'called_at',
        'timezone',
        'crated_at',
        'updated_at',
    ];

    public function lead() {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }
}
