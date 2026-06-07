<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $table='jobs_applications';

    protected $fillable = [
        'job_id','user_id','employer_id','applied_dates'
    ];

    public function job(){
        return $this->belongsTo(Job::class);
    }
}
