<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','category_id','job_types_id','salary','vacancy','description','location',
        'benefits','qualifications','keywords','responsibility','experience','company_name',
        'company_location','company_website','status','isfeatured'
    ];
}
