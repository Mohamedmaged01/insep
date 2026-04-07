<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    // Role constants
    const ROLE_ADMIN      = 'admin';
    const ROLE_INSTRUCTOR = 'instructor';
    const ROLE_STUDENT    = 'student';
    const ROLE_FINANCE    = 'finance';
    const ROLE_SUPPORT    = 'support';

    // All valid roles
    const ROLES = [
        'admin'      => 'مدير إداري',
        'instructor' => 'مدرب',
        'student'    => 'متدرب',
        'finance'    => 'محاسب',
        'support'    => 'دعم فني',
    ];

    protected $fillable = [
        'name', 'name_ar', 'name_en', 'email', 'password', 'phone', 'role',
        'birth_date', 'status', 'avatar', 'specialty', 'rating', 'salary',
    ];

    protected $hidden = ['password'];

    public $rememberTokenName = null;

    protected $casts = [
        'rating' => 'float',
    ];

    // Role helpers
    public function isAdmin(): bool      { return $this->role === self::ROLE_ADMIN; }
    public function isInstructor(): bool { return $this->role === self::ROLE_INSTRUCTOR; }
    public function isStudent(): bool    { return $this->role === self::ROLE_STUDENT; }
    public function isFinance(): bool    { return $this->role === self::ROLE_FINANCE; }
    public function isSupport(): bool    { return $this->role === self::ROLE_SUPPORT; }
    public function hasRole(string ...$roles): bool { return in_array($this->role, $roles); }

    // Role label (Arabic)
    public function roleLabelAr(): string { return self::ROLES[$this->role] ?? $this->role; }

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
