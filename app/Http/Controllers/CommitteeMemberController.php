<?php

namespace App\Http\Controllers;

use App\Models\CommitteeMember;
use Illuminate\Http\JsonResponse;

class CommitteeMemberController extends Controller
{
    public function index(): JsonResponse
    {
        $members = CommitteeMember::orderBy('order')->get()
            ->map(fn($m) => [
                'id'             => $m->id,
                'name'           => $m->name,
                'title'          => $m->title,
                'specialization' => $m->specialization,
                'bio'            => $m->bio,
                'image'          => $m->image
                    ? (str_starts_with($m->image, 'http')
                        ? $m->image
                        : asset('storage/' . ltrim($m->image, '/')))
                    : null,
            ]);

        return response()->json($members);
    }
}
