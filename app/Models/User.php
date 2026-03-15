<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role',
        'birth_date', 'status', 'avatar', 'specialty', 'rating', 'salary',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'rating' => 'float',
    ];

    // JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'sub' => $this->id,
            'email' => $this->email,
            'role' => $this->role,
        ];
    }

    // Relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'student_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class, 'student_id');
    }

    public function resources()
    {
        return $this->hasMany(Resource::class, 'instructor_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function points()
    {
        return $this->hasMany(Point::class, 'student_id');
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('awarded_at');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class, 'student_id');
    }
}
