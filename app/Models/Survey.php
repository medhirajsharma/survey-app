<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'uuid', 'meta_description', 'meta_image', 'vidhansabha_id', 'results_visibility', 'results_visible_from'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'results_visible_from' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($survey) {
            $survey->uuid = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function vidhansabha()
    {
        return $this->belongsTo(Vidhansabha::class);
    }
}
