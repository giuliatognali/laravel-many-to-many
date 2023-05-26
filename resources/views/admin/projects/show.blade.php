@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <h2 class="text-secondary">{{ $project->name }}</h2>
            <div>
                @if ($project->type_id)
                    <h5>
                        Type: {{ $project->type?->name ?: 'No type defined' }} 
                    </h5> 
                @endif
                @if ($project->image)
                    <div>
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->name }}" class="w-50 my-3">
                    </div>
                @endif
                <p>{{ $project->content }}</p>
                <div>
                    <a href="{{ route('admin.projects.edit', $project) }}" class='btn btn-warning'>Edit</a>
                </div>
            </div>
        </div>
    @endsection
