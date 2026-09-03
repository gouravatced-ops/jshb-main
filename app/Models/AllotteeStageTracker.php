<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllotteeStageTracker extends Model
{
    protected $connection = 'adms_allottees';
    protected $fillable = [
        'allottee_id',
        'application_no',
        'stage_type',
        'status',
        'action_by',
        'remarks',
    ];

    public function allottee()
    {
        return $this->belongsTo(Allottee::class, 'allottee_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_no', 'application_no');
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
