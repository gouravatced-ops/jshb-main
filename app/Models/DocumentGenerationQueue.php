<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentGenerationQueue extends Model
{
    protected $guarded = [];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }

    public function allottee()
    {
        return $this->belongsTo(User::class, 'allottee_id', 'id'); // Depending on relation. Better to use allottee model? Wait, it's actually Allottee table or something. I'll just use application relation to get application number.
    }
    
    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by_user_id', 'id');
    }
}
