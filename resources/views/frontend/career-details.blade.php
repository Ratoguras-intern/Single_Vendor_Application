@extends('layouts.frontend')

@section('title', $seoTitle . ' - ' . site_name())

@include('frontend.partials.seo-meta', [
    'title' => $seoTitle,
    'description' => $seoDescription,
])

@section('content')
<section class="relative overflow-hidden bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-900 dark:from-secondary-950 dark:via-secondary-900 dark:to-secondary-950">
    <div class="absolute inset-0 opacity-[0.07]" aria-hidden="true">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary-400 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 rounded-full bg-primary-500 blur-3xl"></div>
    </div>
    <div class="relative section py-10 sm:py-14">

        <span class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.22em] text-primary-400 dark:text-primary-300 mb-3">Open Position</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight mb-3">{{ $job->title }}</h1>

        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-secondary-300 dark:text-secondary-400">
            @if ($job->department)
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                    {{ $job->department }}
                </span>
            @endif
            @if ($job->employment_type)
                <span>{{ $job->employment_type }}</span>
            @endif
            @if ($job->experience_level)
                <span>{{ $job->experience_level }}</span>
            @endif
            @if ($job->location)
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    {{ $job->location }}
                </span>
            @endif
            @if ($job->published_at)
                <span>Posted {{ $job->published_at->format('M j, Y') }}</span>
            @endif
        </div>
    </div>
</section>

<section class="py-12 sm:py-16 bg-secondary-50 dark:bg-secondary-950">
    <div class="section max-w-4xl mx-auto grid lg:grid-cols-[minmax(0,1fr)_280px] gap-8 lg:gap-10 items-start">

        <article class="space-y-6 min-w-0">
            @if ($job->description)
                <div class="card !p-6 sm:!p-8">
                    <h2 class="text-lg font-bold text-secondary-900 dark:text-white mb-3">About the Role</h2>
                    <p class="text-sm text-secondary-600 dark:text-secondary-400 leading-relaxed whitespace-pre-line">{{ $job->description }}</p>
                </div>
            @endif

            @if ($job->responsibilitiesList())
                <div class="card !p-6 sm:!p-8">
                    <h2 class="text-lg font-bold text-secondary-900 dark:text-white mb-3">Responsibilities</h2>
                    <ul class="space-y-2">
                        @foreach ($job->responsibilitiesList() as $item)
                            <li class="flex items-start gap-2.5 text-sm text-secondary-600 dark:text-secondary-400 leading-relaxed">
                                <svg class="h-4 w-4 mt-0.5 shrink-0 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($job->requirementsList())
                <div class="card !p-6 sm:!p-8">
                    <h2 class="text-lg font-bold text-secondary-900 dark:text-white mb-3">Requirements</h2>
                    <ul class="space-y-2">
                        @foreach ($job->requirementsList() as $item)
                            <li class="flex items-start gap-2.5 text-sm text-secondary-600 dark:text-secondary-400 leading-relaxed">
                                <svg class="h-4 w-4 mt-0.5 shrink-0 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($job->benefitsList())
                <div class="card !p-6 sm:!p-8">
                    <h2 class="text-lg font-bold text-secondary-900 dark:text-white mb-3">Benefits</h2>
                    <ul class="grid sm:grid-cols-2 gap-2">
                        @foreach ($job->benefitsList() as $item)
                            <li class="flex items-start gap-2.5 text-sm text-secondary-600 dark:text-secondary-400 leading-relaxed">
                                <svg class="h-4 w-4 mt-0.5 shrink-0 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </article>

        <aside class="lg:sticky lg:top-24 space-y-5 w-full">
            <div class="card !p-6">
                <h2 class="font-bold text-secondary-900 dark:text-white mb-2">Apply Now</h2>
                @if ($job->application_instructions || $job->application_email)
                    <p class="text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed mb-4">
                        {{ $job->application_instructions ?: ('Send your resume and a short introduction to ' . $job->application_email . '.') }}
                    </p>
                    @if ($job->application_email)
                        <a href="mailto:{{ $job->application_email }}?subject=Application: {{ rawurlencode($job->title) }}" class="btn-primary btn-sm w-full">
                            Email Application
                        </a>
                    @endif
                @else
                    <p class="text-sm text-secondary-500 dark:text-secondary-400">Application details coming soon.</p>
                @endif
            </div>

            @if ($otherJobs->isNotEmpty())
                <div class="card !p-6">
                    <h2 class="font-bold text-secondary-900 dark:text-white mb-3">Other Openings</h2>
                    <ul class="space-y-2">
                        @foreach ($otherJobs as $other)
                            <li>
                                <a href="{{ route('frontend.careers.show', $other) }}" class="block rounded-lg border border-secondary-200 dark:border-secondary-700 px-4 py-3 hover:border-primary-300 hover:bg-primary-50/40 dark:hover:bg-primary-950/20 transition-colors">
                                    <span class="block text-sm font-medium text-secondary-800 dark:text-secondary-200">{{ $other->title }}</span>
                                    <span class="block text-xs text-secondary-400 mt-0.5">{{ $other->department }} · {{ $other->location }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <a href="/careers" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline underline-offset-2">
                        View all positions
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
            @endif
        </aside>
    </div>
</section>
@endsection
