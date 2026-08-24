<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // Role constants
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN       = 'admin';
    const ROLE_SUPERVISOR  = 'supervisor';
    const ROLE_INSTRUCTOR  = 'instructor';
    const ROLE_STUDENT     = 'student';
    const ROLE_FINANCE     = 'finance';
    const ROLE_SUPPORT     = 'support';

    // All valid roles
    const ROLES = [
        'super_admin' => 'سوبر أدمن',
        'admin'       => 'مدير إداري',
        'supervisor'  => 'مشرف تدريب',
        'instructor'  => 'مدرب',
        'student'     => 'متدرب',
        'finance'     => 'محاسب',
        'support'     => 'دعم فني',
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
    public function isSuperAdmin(): bool  { return $this->role === self::ROLE_SUPER_ADMIN; }
    public function isAdmin(): bool       { return $this->role === self::ROLE_ADMIN; }
    public function isSupervisor(): bool  { return $this->role === self::ROLE_SUPERVISOR; }
    public function isInstructor(): bool  { return $this->role === self::ROLE_INSTRUCTOR; }
    public function isStudent(): bool     { return $this->role === self::ROLE_STUDENT; }
    public function isFinance(): bool     { return $this->role === self::ROLE_FINANCE; }
    public function isSupport(): bool     { return $this->role === self::ROLE_SUPPORT; }
    public function hasRole(string ...$roles): bool { return in_array($this->role, $roles); }
    public function isAdminOrAbove(): bool { return in_array($this->role, ['admin', 'super_admin']); }

    // Role hierarchy — higher rank can manage strictly-lower ranks ("everyone below").
    const ROLE_RANK = [
        'super_admin' => 100,
        'admin'       => 80,
        'supervisor'  => 60,
        'finance'     => 40,
        'support'     => 40,
        'instructor'  => 40,
        'student'     => 20,
    ];

    public function roleRank(): int { return self::ROLE_RANK[$this->role] ?? 0; }

    /**
     * Whether this user may change $target's password: must be admin-or-above,
     * $target must be strictly lower in the hierarchy, and never the protected
     * system-owner account.
     */
    public function canResetPasswordFor(User $target): bool
    {
        if ($target->email === env('OWNER_EMAIL', '')) return false;
        if (!$this->isAdminOrAbove()) return false;
        return $this->roleRank() > $target->roleRank();
    }

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
