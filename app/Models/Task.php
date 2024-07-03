<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = 'task';
    protected $fillable = [
        'user_id',
        'tas_nome',
        'status_id',
        'tas_data_inicio',
        'tas_data_final',
        'tas_observacao',
        'tas_ativo'
    ];

    public function rules() {
        return [
            'nome' => 'required',
            'status' => 'required|exists:status,id',
            'data_inicio' => 'required'
        ];
    }

    public function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'exists' => "O codigo de vinculo tem que ser um valido "
        ];
    }
    

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
    
}
