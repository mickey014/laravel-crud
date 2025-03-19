<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Authenticatable
{
    use HasFactory;
    protected $table = 'students';
    protected $fillable = ['name', 'email', 'phone', 'course', 'username', 'age', 'password', 'remember_token'];

    protected $hidden = ['password', 'remember_token'];
}
