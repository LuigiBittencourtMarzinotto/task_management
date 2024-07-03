<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'status';
    protected $fillable = [
        'st_nome',
        'user_id',
        'st_ativo'
    ];
    public function rules() {
        return [
            'nome' => 'required'
        ];
    }

    public function feedback(){
        return [
            'required' => 'O campo Nome é obrigatório'
        ];
    }
    
}
