@extends('admin.layouts.app')

@php
    $isEdit = isset($job);
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Careers', 'url' => route('admin.jobs.index')],
        ['label' => $isEdit ? 'Edit: '.$job->title : 'New Job Opening', 'url' => null],
    ];
@endphp

@section('content')
    @if(session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="pointer-events-none fixed top-4 right-4 z-50 max-w-sm rounded-lg border border-emerald-200 bg-emerald-50 p-4 shadow-lg dark:border-emerald-800 dark:bg-emerald-900/30">
        <div class="flex items-center gap-3">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-800/50">
                <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            </div>
            <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">{{ $isEdit ? 'Edit Job Opening' : 'New Job Opening' }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Listings appear on the public careers page once published.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">All Jobs</a>
            <button type="submit" form="job-form" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                {{ $isEdit ? 'Update Job' : 'Create Job' }}
            </button>
        </div>
    </div>

    <form id="job-form" action="{{ $isEdit ? route('admin.jobs.update', $job) : route('admin.jobs.store') }}" method="POST">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Position Details</h3>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Job Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $job->title ?? '') }}" required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            @error('title')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $job->slug ?? '') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                placeholder="auto-generated-from-title">
                        </div>
                        <div>
                            <label for="department" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Department</label>
                            <input type="text" name="department" id="department" value="{{ old('department', $job->department ?? '') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="location" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                            <input type="text" name="location" id="location" value="{{ old('location', $job->location ?? '') }}" placeholder="e.g. Remote / Kathmandu"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="employment_type" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Employment Type</label>
                            <select name="employment_type" id="employment_type"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                @foreach (['Full-time', 'Part-time', 'Contract', 'Internship', 'Temporary'] as $type)
                                    <option value="{{ $type }}" {{ old('employment_type', $job->employment_type ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="experience_level" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Experience Level</label>
                            <select name="experience_level" id="experience_level"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                @foreach (['Entry level', 'Mid level', 'Senior', 'Lead', 'Executive'] as $level)
                                    <option value="{{ $level }}" {{ old('experience_level', $job->experience_level ?? '') === $level ? 'selected' : '' }}>{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Job Description</label>
                            <textarea name="description" id="description" rows="7"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                placeholder="Overview of the role...">{{ old('description', $job->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Role Requirements</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="responsibilities" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Responsibilities</label>
                            <textarea name="responsibilities" id="responsibilities" rows="5"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('responsibilities', $job->responsibilities ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">One item per line.</p>
                        </div>
                        <div>
                            <label for="requirements" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Requirements</label>
                            <textarea name="requirements" id="requirements" rows="5"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('requirements', $job->requirements ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">One item per line.</p>
                        </div>
                        <div>
                            <label for="benefits" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Benefits</label>
                            <textarea name="benefits" id="benefits" rows="5"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('benefits', $job->benefits ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">One item per line.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Publishing</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="draft" {{ old('status', $job->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $job->status ?? 'draft') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="closed" {{ old('status', $job->status ?? 'draft') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div>
                            <label for="published_at" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Published Date</label>
                            <input type="date" name="published_at" id="published_at" value="{{ old('published_at', $job?->published_at?->format('Y-m-d')) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Auto-set when first published.</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Application</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="application_email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Application Email</label>
                            <input type="email" name="application_email" id="application_email" value="{{ old('application_email', $job->application_email ?? '') }}" placeholder="careers@example.com"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            @error('application_email')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="application_instructions" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Application Instructions</label>
                            <textarea name="application_instructions" id="application_instructions" rows="4"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                placeholder="Send your resume and portfolio to...">{{ old('application_instructions', $job->application_instructions ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
