<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'allDay',
        'start',
        'end',
        'tipo',
        'persona_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'allDay' => 'boolean',
        'start' => 'datetime',
        'end' => 'datetime',
        'persona_id' => 'integer',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
