@extends('layouts.frontend')

@section('title', 'My Favorites - NBK Vertex')

@section('content')
<script>
    window.location.href = '{{ route("frontend.favorites") }}';
</script>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="py-32 text-center">
        <p style="color: var(--bloom-muted-foreground);">Redirecting to favorites...</p>
    </div>
</div>
@endsection
