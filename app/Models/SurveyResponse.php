<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'survey_id', 'mobile_no', 'vidhansabha_id', 'caste'];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function vidhansabha()
    {
        return $this->belongsTo(Vidhansabha::class);
    }
}
