<?php

namespace App\Http\Controllers\Seeker;

use App\Enums\SkillLevel;
use App\Enums\SkillType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Seeker\Concerns\EnsuresOwnership;
use App\Http\Requests\Seeker\SkillRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillController extends Controller
{
    use EnsuresOwnership;

    public function index(Request $request): View
    {
        return view('seeker.skills.index', [
            'skillsByType' => $request->user()->skills()->orderBy('name')->get()->groupBy(fn (Skill $skill) => $skill->type->value),
            'types' => SkillType::options(),
            'levels' => SkillLevel::options(),
        ]);
    }

    public function store(SkillRequest $request): RedirectResponse
    {
        $request->user()->skills()->create($request->validated());

        return redirect()->route('seeker.skills.index')->with('success', 'Skill berhasil ditambahkan.');
    }

    public function edit(Skill $skill): View
    {
        $this->ensureOwnedByCurrentUser($skill);

        return view('seeker.skills.edit', [
            'skill' => $skill,
            'types' => SkillType::options(),
            'levels' => SkillLevel::options(),
        ]);
    }

    public function update(SkillRequest $request, Skill $skill): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($skill);

        $skill->update($request->validated());

        return redirect()->route('seeker.skills.index')->with('success', 'Skill berhasil diperbarui.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($skill);

        $skill->delete();

        return redirect()->route('seeker.skills.index')->with('success', 'Skill berhasil dihapus.');
    }
}
