<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'display_name',
        'daily_cigarettes',
        'cigarette_pack_cost',
        'quit_date',
    ];

    protected $casts = [
        'quit_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
