<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->orderByDesc('published_at')->orderByDesc('id')->paginate(15)->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Job::create($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job created successfully.');
    }

    public function edit(Job $job)
    {
        return view('admin.jobs.edit', ['job' => $job]);
    }

    public function update(Request $request, Job $job)
    {
        $validated = $this->validateData($request, $job);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($validated['status'] === 'published' && $job->status !== 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $job->update($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    protected function validateData(Request $request, ?Job $job = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:job_openings,slug'.($job ? ','.$job->id : ''),
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|in:Full-time,Part-time,Contract,Internship,Temporary',
            'experience_level' => 'nullable|in:Entry level,Mid level,Senior,Lead,Executive',
            'description' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'application_instructions' => 'nullable|string',
            'application_email' => 'nullable|email|max:255',
            'status' => 'required|in:draft,published,closed',
            'published_at' => 'nullable|date',
        ]);
    }
}
