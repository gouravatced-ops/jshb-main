<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentMaster extends Model
{
    use HasFactory;
    protected $connection = 'adms_allottees';
    protected $table = 'document_master';

    protected $fillable = [
        'document_name',
        'document_key',
        'document_category',
        'sort_order',
        'status'
    ];
}
