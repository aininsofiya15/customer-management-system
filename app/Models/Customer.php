<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Columns that can be mass-assigned (e.g. Customer::create([...]))
    protected $fillable = [
        'name',
        'email',
        'address',
        'phoneNumber',
        'gender',
        'birthday',
    ];

    // Convert birthday into a Carbon date object automatically
    protected function casts(): array
    {
        return [
            'birthday' => 'date',
        ];
    }
}
