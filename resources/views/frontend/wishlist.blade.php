@extends('layouts.frontend')

@section('title', 'My Favorites - NBK Vertex')

@section('content')
<script>
    window.location.href = '{{ route("frontend.favorites") }}';
</script>
<div class="section py-8">
    <div class="py-32 text-center">
        <p class="text-secondary-500 dark:text-secondary-400">Redirecting to favorites...</p>
    </div>
</div>
@endsection
