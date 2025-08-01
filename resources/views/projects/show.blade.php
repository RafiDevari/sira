@extends('layouts.app')

@section('content')
<style>
    .dots-btn {
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .dots-btn:hover {
        border: 1px solid #d0d0d0; /* Light gray border */
        background-color: #f8f9fa; /* Light gray background */
    }

    .dropdown-item:hover {
        background-color: #f1f1f1; /* Light gray background on hover */
    }

    .modal-header {
        padding: 0.75rem 1.25rem;
    }

    .modal-title {
        font-size: 1.1rem;
    }

    .modal-body {
        background: #fff;
    }

    .modal-body h6 {
        font-size: 0.9rem;
    }

    .form-control {
        border-radius: 6px;
    }

    .btn-primary {
        background-color: #007bff; /* Bootstrap's primary color */
        border: none;
    }

    .btn-primary:hover {
        background-color: #0069d9; /* Darker blue on hover */
    }

    .card-toggle {
        max-height: 0;
        transition: max-height 0.4s ease, padding 0.3s ease;
    }

    .card-toggle.expanded {
        max-height: 490px; 
    }


    .arrow-icon {
        transition: transform 0.3s ease;
    }

    .arrow-icon.rotated {
        transform: rotate(90deg);
    }

    .arrow-icon:hover {
        transform: rotate(45deg);
    }

    .arrow-icon:hover path {
        stroke: #1d4ed8; /* Tailwind's blue-700 */
    }

    .hover-card {
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    .hover-card:hover {
        background-color: #f1f5f9; /* Light gray */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .editbtn {
        transition: border 0.2s ease, padding 0.2s ease;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .editbtn:hover {
        border: 1px solid #6c757d; /* Bootstrap's "text-secondary" color */
        background-color: rgba(108, 117, 125, 0.05);
        cursor: pointer;
    }

    @media (max-width: 992px) {
        .editbtn {
            display: none !important; /* Hide on smaller screens */
        }
    }

    .task-name {
        max-width: 400px;
    }

    @media (max-width: 992px) {
        .task-name {
            max-width: 200px;
        }
    }
    
    @media (max-width: 768px) {
        .task-name {
            max-width: 240px;
        }
    }

    @media (max-width: 400px) {
        .task-name {
            max-width: 180px;
        }
    }

    .hover-underline:hover {
        text-decoration: underline;
        
    }

    .offcanvas.task-detail {
        --bs-offcanvas-width: 500px; 
    }

    .tooltip.show {
        
    }

</style>

<div class="container">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
        <!-- Project Name & Timeline -->
        <div>
            <h2 class="mb-1">{{ $project->nama }}</h2>
            <div class="text-muted small d-flex align-items-center">
                <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                </svg>
                {{ \Carbon\Carbon::parse($project->waktu_mulai)->format('d F') }} - 
                {{ \Carbon\Carbon::parse($project->waktu_selesai)->format('d F') }}
            </div>
        </div>
    </div>

    <!-- Project Description -->
    <div>
        <p class="text-muted">{{ $project->deskripsi }}</p>
    </div>

    <!-- Search & Add Section -->
    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between gap-3 mb-4">
        <!-- Search & Filter Form -->
        <form method="GET" class="d-flex flex-column flex-md-row gap-2 flex-grow-1 w-100 align-items-stretch">

            <!-- Search bar -->
            <div class="search-bar d-flex align-items-center border border-1 border-secondary rounded px-2"
                style="height: 38px; max-width: 100%; width: 100%; max-width: 280px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="me-2 text-secondary">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
                <input type="text" name="search"
                    value="{{ request('search') }}"
                    placeholder="Search task by name..."
                    class="form-control border-0 shadow-none p-0"
                    style="font-size: 0.875rem; line-height: 1.5;" />
            </div>

            <!-- Filter + Search -->
            <div class="d-flex flex-row gap-2 flex-wrap w-100 w-md-auto align-items-stretch">

                <!-- Filter dropdown -->
                <div class="dropdown" style="width: 100%; max-width: 180px;">
                    <input type="hidden" name="type" id="typeInput" value="{{ request('type') }}">
                    <button id="typeDropdownBtn"
                            class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center w-100"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg class="me-2" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
                        </svg>
                        <span id="typeLabel">{{ request('type') ?: 'Filter by type' }}</span>
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><button class="dropdown-item" type="button" onclick="setTypeFilter('')">All types</button></li>
                        <li><button class="dropdown-item" type="button" onclick="setTypeFilter('Bug')">Bug</button></li>
                        <li><button class="dropdown-item" type="button" onclick="setTypeFilter('Feature')">Feature</button></li>
                        <li><button class="dropdown-item" type="button" onclick="setTypeFilter('Request')">Request</button></li>
                        <li><button class="dropdown-item" type="button" onclick="setTypeFilter('Story')">Story</button></li>
                        <li><button class="dropdown-item" type="button" onclick="setTypeFilter('Task')">Task</button></li>
                    </ul>
                </div>

                <!-- Search Button -->
                <button type="submit" class="btn btn-primary fw-semibold">Search</button>
            </div>
        </form>

        <!-- Add Sprint Button -->
        <div class="flex-shrink-0">
            <button class="btn btn-success fw-semibold w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#addSprintModal">
                + Add Sprint
            </button>
        </div>
    </div>


    <!-- Sprints Section -->
    @if ($project->sprints->isEmpty())
        <p class="text-muted">No sprints available yet. Please add one.</p>
    @else
        @foreach($project->sprints as $sprint)
            <div class="bg-light p-3 mb-3 rounded">
                <div class="card border-0">
                    <div class="card-header bg-light d-flex flex-wrap align-items-start gap-2">
                        <!-- Toggle Collapse -->
                        <div class="toggle-header me-2" onclick="toggleCard(this)">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white arrow-icon transition" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m9 5 7 7-7 7"/>
                            </svg>
                        </div>

                        <!-- Sprint Info-->
                        <div class="flex-grow-1 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <div>
                                <strong class="d-block">{{ $sprint->nama }}</strong>
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $deadline = \Carbon\Carbon::parse($sprint->waktu_selesai);
                                @endphp
                                @if ($now->greaterThan($deadline) && $sprint->status === 'IN PROGRESS')
                                    <small class="d-block text-danger">
                                        {{ \Carbon\Carbon::parse($sprint->waktu_mulai)->format('d F') }} - 
                                        {{ \Carbon\Carbon::parse($sprint->waktu_selesai)->format('d F') }}
                                        <span class="fw-semibold">&nbsp;&nbsp;&nbsp;Overdue</span>
                                    </small>
                                @elseif ($sprint->status === 'COMPLETED')
                                    <small class="d-block text-success">
                                        {{ \Carbon\Carbon::parse($sprint->waktu_mulai)->format('d F') }} - 
                                        {{ \Carbon\Carbon::parse($sprint->waktu_selesai)->format('d F') }}
                                    </small>
                                @else
                                    <small class="d-block text-secondary">
                                        {{ \Carbon\Carbon::parse($sprint->waktu_mulai)->format('d F') }} - 
                                        {{ \Carbon\Carbon::parse($sprint->waktu_selesai)->format('d F') }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <!-- Toggle Sprint Status -->
                        <form method="POST" action="{{ url('/sprints/' . $sprint->id . '/toggle-status') }}" class="mt-2 mt-md-0 ms-md-4">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                    style="margin-top: 1.5px"
                                    class="btn btn-sm px-3 py-1 fw-semibold toggle-status-btn {{ $sprint->status == 'COMPLETED' ? 'btn-success' : 'btn-outline-secondary' }}"
                                    onclick="this.querySelector('input[name=status]').value = '{{ $sprint->status == 'COMPLETED' ? 'IN PROGRESS' : 'COMPLETED' }}'">
                                {{ $sprint->status == 'COMPLETED' ? '☑️ Completed' : '⬜ In Progress' }}
                                <input type="hidden" name="status" value="">
                            </button>
                        </form>
                        <!-- Right-side buttons (Add Task + Dropdown) -->
                        <div class="d-flex align-items-center ms-auto mt-2 mt-md-0">
                            <button class="btn btn-sm btn-outline-primary  me-2" data-bs-toggle="modal" data-bs-target="#addTaskModal-{{ $sprint->id }}">
                                + Add Task
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-sm dots-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg class="w-6 h-6 text-gray-800" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                            d="M6 12h.01m6 0h.01m5.99 0h.01"/>
                                    </svg>
                                </button>
                                <ul class="dropdown-menu shadow-sm">
                                    <li>
                                        <button type="button"
                                                class="dropdown-item d-flex align-items-center edit-sprint-btn"
                                                data-bs-toggle="modal" data-bs-target="#addSprintModal"
                                                data-title="Edit Sprint"
                                                data-action="{{ url('/sprints/' . $sprint->id . '/update') }}"
                                                data-nama="{{ $sprint->nama }}"
                                                data-start="{{ $sprint->waktu_mulai }}"
                                                data-end="{{ $sprint->waktu_selesai }}"
                                                data-status="{{ $sprint->status }}">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white me-2" xmlns="http://www.w3.org/2000/svg"
                                                width="18" height="18" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                            </svg>
                                            <span>Edit</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button"
                                                class="dropdown-item d-flex align-items-center"
                                                onclick="confirmDelete(this)" data-id="{{ $sprint->id }}" data-name="{{ $sprint->nama }}" data-type="sprint">
                                            <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 15v3c0 .5523.44772 1 1 1h16c.5523 0 1-.4477 1-1v-3M3 15V6c0-.55228.44772-1 1-1h16c.5523 0 1 .44772 1 1v9M3 15h18M8 15v4m4-4v4m4-4v4M12 10l1.5-1.5M12 10l-1.5-1.5M12 10l1.5 1.5M12 10l-1.5 1.5"/>
                                            </svg>
                                            <span>Delete</span>
                                        </button>
                                        <form id="delete-form-sprint-{{ $sprint->id }}" action="{{ url('/sprints/' . $sprint->id . '/delete') }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Sprint Tasks -->
                    <div class="card-body p-0 shadow-sm card-toggle collapsed ">
                        <ul class="list-group list-group-flush">
                            @if($sprint->tasks->isEmpty())
                                <li class="list-group-item text-center text-muted ">No tasks found</li>
                            @else
                                @foreach($sprint->tasks as $task)
                                    <li class="list-group-item hover-card p-2">
                                        <!-- Task -->
                                        <div class="d-flex flex-wrap flex-md-nowrap justify-content-between align-items-center w-100 " style="gap: 1rem;">
                                            <div class="row flex-nowrap align-items-center gx-2" >
                                                <!-- Task Type Dropdown -->
                                                <div class="col-auto dropdown flex-shrink-0">
                                                    <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="taskTypeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                        @if ($task->type === 'Bug')
                                                            <svg title="Bug" class="w-6 h-6 text-danger dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M18 17h-.09c.058-.33.088-.665.09-1v-1h1a1 1 0 0 0 0-2h-1.09a5.97 5.97 0 0 0-.26-1H17a2 2 0 0 0 2-2V8a1 1 0 1 0-2 0v2h-.54a6.239 6.239 0 0 0-.46-.46V8a3.963 3.963 0 0 0-.986-2.6l.693-.693A1 1 0 0 0 16 4V3a1 1 0 1 0-2 0v.586l-.661.661a3.753 3.753 0 0 0-2.678 0L10 3.586V3a1 1 0 1 0-2 0v1a1 1 0 0 0 .293.707l.693.693A3.963 3.963 0 0 0 8 8v1.54a6.239 6.239 0 0 0-.46.46H7V8a1 1 0 0 0-2 0v2a2 2 0 0 0 2 2h-.65a5.97 5.97 0 0 0-.26 1H5a1 1 0 0 0 0 2h1v1a6 6 0 0 0 .09 1H6a2 2 0 0 0-2 2v2a1 1 0 1 0 2 0v-2h.812A6.012 6.012 0 0 0 11 21.907V12a1 1 0 0 1 2 0v9.907A6.011 6.011 0 0 0 17.188 19H18v2a1 1 0 0 0 2 0v-2a2 2 0 0 0-2-2Zm-4-8.65a5.922 5.922 0 0 0-.941-.251l-.111-.017a5.52 5.52 0 0 0-1.9 0l-.111.017A5.925 5.925 0 0 0 10 8.35V8a2 2 0 1 1 4 0v.35Z"/>
                                                            </svg>
                                                        @elseif ($task->type === 'Feature')
                                                            <svg title="Feature" class="w-6 h-6 text-success dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M5.005 10.19a1 1 0 0 1 1 1v.233l5.998 3.464L18 11.423v-.232a1 1 0 1 1 2 0V12a1 1 0 0 1-.5.866l-6.997 4.042a1 1 0 0 1-1 0l-6.998-4.042a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1ZM5 15.15a1 1 0 0 1 1 1v.232l5.997 3.464 5.998-3.464v-.232a1 1 0 1 1 2 0v.81a1 1 0 0 1-.5.865l-6.998 4.042a1 1 0 0 1-1 0L4.5 17.824a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1Z" clip-rule="evenodd"/>
                                                                <path d="M12.503 2.134a1 1 0 0 0-1 0L4.501 6.17A1 1 0 0 0 4.5 7.902l7.002 4.047a1 1 0 0 0 1 0l6.998-4.04a1 1 0 0 0 0-1.732l-6.997-4.042Z"/>
                                                            </svg>
                                                        @elseif ($task->type === 'Task')
                                                            <svg title="Task" class="w-6 h-6 text-primary dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @elseif ($task->type === 'Story')
                                                            <svg title="Story" class="w-6 h-6 text-success dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M7.833 2c-.507 0-.98.216-1.318.576A1.92 1.92 0 0 0 6 3.89V21a1 1 0 0 0 1.625.78L12 18.28l4.375 3.5A1 1 0 0 0 18 21V3.889c0-.481-.178-.954-.515-1.313A1.808 1.808 0 0 0 16.167 2H7.833Z"/>
                                                            </svg>
                                                        @elseif ($task->type === 'Request')
                                                            <svg title="Request" class="w-6 h-6 text-success dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @endif
                                                    </button>

                                                    <ul class="dropdown-menu" aria-labelledby="taskTypeDropdown">
                                                        <li>
                                                            <form method="POST" action="{{ route('tasks.updateType', $task->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="type" value="Bug">
                                                                <button type="submit" class="dropdown-item d-flex align-items-center">
                                                                    <svg title="Bug" class="w-6 h-6 me-2 text-danger dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path d="M18 17h-.09c.058-.33.088-.665.09-1v-1h1a1 1 0 0 0 0-2h-1.09a5.97 5.97 0 0 0-.26-1H17a2 2 0 0 0 2-2V8a1 1 0 1 0-2 0v2h-.54a6.239 6.239 0 0 0-.46-.46V8a3.963 3.963 0 0 0-.986-2.6l.693-.693A1 1 0 0 0 16 4V3a1 1 0 1 0-2 0v.586l-.661.661a3.753 3.753 0 0 0-2.678 0L10 3.586V3a1 1 0 1 0-2 0v1a1 1 0 0 0 .293.707l.693.693A3.963 3.963 0 0 0 8 8v1.54a6.239 6.239 0 0 0-.46.46H7V8a1 1 0 0 0-2 0v2a2 2 0 0 0 2 2h-.65a5.97 5.97 0 0 0-.26 1H5a1 1 0 0 0 0 2h1v1a6 6 0 0 0 .09 1H6a2 2 0 0 0-2 2v2a1 1 0 1 0 2 0v-2h.812A6.012 6.012 0 0 0 11 21.907V12a1 1 0 0 1 2 0v9.907A6.011 6.011 0 0 0 17.188 19H18v2a1 1 0 0 0 2 0v-2a2 2 0 0 0-2-2Zm-4-8.65a5.922 5.922 0 0 0-.941-.251l-.111-.017a5.52 5.52 0 0 0-1.9 0l-.111.017A5.925 5.925 0 0 0 10 8.35V8a2 2 0 1 1 4 0v.35Z"/>
                                                                    </svg>
                                                                    Bug
                                                                </button>
                                                            </form>
                                                        </li>

                                                        <li>
                                                            <form method="POST" action="{{ route('tasks.updateType', $task->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="type" value="Feature">
                                                                <button type="submit" class="dropdown-item d-flex align-items-center">
                                                                    <svg title="Feature" class="me-2 w-6 h-6 text-success dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path fill-rule="evenodd" d="M5.005 10.19a1 1 0 0 1 1 1v.233l5.998 3.464L18 11.423v-.232a1 1 0 1 1 2 0V12a1 1 0 0 1-.5.866l-6.997 4.042a1 1 0 0 1-1 0l-6.998-4.042a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1ZM5 15.15a1 1 0 0 1 1 1v.232l5.997 3.464 5.998-3.464v-.232a1 1 0 1 1 2 0v.81a1 1 0 0 1-.5.865l-6.998 4.042a1 1 0 0 1-1 0L4.5 17.824a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1Z" clip-rule="evenodd"/>
                                                                        <path d="M12.503 2.134a1 1 0 0 0-1 0L4.501 6.17A1 1 0 0 0 4.5 7.902l7.002 4.047a1 1 0 0 0 1 0l6.998-4.04a1 1 0 0 0 0-1.732l-6.997-4.042Z"/>
                                                                    </svg>
                                                                    Feature
                                                                </button>
                                                            </form>
                                                        </li>

                                                        <li>
                                                            <form method="POST" action="{{ route('tasks.updateType', $task->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="type" value="Task">
                                                                <button type="submit" class="dropdown-item d-flex align-items-center">
                                                                    <svg title="Task" class="me-2 w-6 h-6 text-primary dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Task
                                                                </button>
                                                            </form>
                                                        </li>

                                                        <li>
                                                            <form method="POST" action="{{ route('tasks.updateType', $task->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="type" value="Story">
                                                                <button type="submit" class="dropdown-item d-flex align-items-center">
                                                                    <svg title="Story" class="me-2 w-6 h-6 text-success dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path d="M7.833 2c-.507 0-.98.216-1.318.576A1.92 1.92 0 0 0 6 3.89V21a1 1 0 0 0 1.625.78L12 18.28l4.375 3.5A1 1 0 0 0 18 21V3.889c0-.481-.178-.954-.515-1.313A1.808 1.808 0 0 0 16.167 2H7.833Z"/>
                                                                    </svg>
                                                                    Story
                                                                </button>
                                                            </form>
                                                        </li>

                                                        <li>
                                                            <form method="POST" action="{{ route('tasks.updateType', $task->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="type" value="Request">
                                                                <button type="submit" class="dropdown-item d-flex align-items-center">
                                                                    <svg title="Request" class="me-2 w-6 h-6 text-success dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Request
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>

                                                </div>


                                                <!-- Task Name -->
                                                @php
                                                    $isOverdue = \Carbon\Carbon::parse($task->waktu_selesai)->lessThanOrEqualTo(now()) &&
                                                                in_array($task->status, ['TO DO', 'IN PROGRESS']);
                                                @endphp

                                                <div class="col-auto fw-semibold text-truncate {{ $isOverdue ? 'text-danger' : '' }}" 
                                                    id="task-display-{{ $task->id }}" 
                                                    style="max-width: 600px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer;" 
                                                    data-bs-toggle="offcanvas" 
                                                    data-bs-target="#taskDetail-{{ $task->id }}" 
                                                    aria-controls="taskDetail-{{ $task->id }}">

                                                    <span class="hover-underline"
                                                        @if($isOverdue)
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="top"
                                                            data-bs-custom-class="tooltip-danger"
                                                            title="Overdue"
                                                        @endif
                                                    >
                                                        T{{ $loop->iteration }} - {{ $task->name }}
                                                    </span>
                                                </div>




                                                <!-- Edit Button (SVG Icon) -->
                                                <div class="col-auto text-secondary editbtn d-flex align-items-center flex-shrink-0" id="edit-button-{{ $task->id }}">
                                                    <svg class="text-gray-800 cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                                        viewBox="0 0 24 24" onclick="toggleEdit({{ $task->id }})">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                                    </svg>
                                                </div>

                                                <!-- Edit Form - HIDDEN INITIALLY -->
                                                <form method="POST" id="edit-form-{{ $task->id }}" class="w-100 col-auto gap-2 " style="display: none; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" action="{{ url('/tasks/' . $task->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <input id="edit-input-{{ $task->id }}" name="name" class="form-control col" style="max-width: 400px" value="{{ $task->name }}" required>

                                                    <!-- Buttons -->
                                                    <div class="d-flex gap-1">
                                                        <button type="submit" class="btn btn-sm btn-success" title="Save">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m5 13 4 4L19 7" />
                                                            </svg>
                                                        </button>

                                                        <button type="button" class="btn btn-sm btn-outline-secondary" title="Cancel" onclick="cancelEdit({{ $task->id }})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M6 18 18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </form>

                                            
                                            </div>

                                            <div class="d-flex flex-wrap flex-md-nowrap gap-2 align-items-center ms-auto justify-content-end" style="min-width: 250px;">

                                                <!-- Status Dropdown -->
                                                <div class="d-flex align-items-center mt-1 flex-shrink-0">
                                                    <div class="dropdown">
                                                        <form method="POST" action="{{ url('/tasks/' . $task->id . '/update-status') }}" id="status-form-{{ $task->id }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <a class="badge dropdown-toggle
                                                                {{ $task->status === 'DONE' ? 'bg-success' : ($task->status === 'IN PROGRESS' ? 'bg-primary' : ($task->status === 'IN REVIEW' ? 'bg-info' : 'bg-secondary')) }}"
                                                                href="#" role="button" id="dropdownMenuLink-{{ $task->id }}" data-bs-toggle="dropdown" aria-expanded="false"
                                                                style="text-decoration: none;">
                                                                {{ strtoupper($task->status) }}
                                                            </a>

                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink-{{ $task->id }}">
                                                                <li><button class="dropdown-item" type="submit" name="status" value="TO DO">TO DO</button></li>
                                                                <li><button class="dropdown-item" type="submit" name="status" value="IN PROGRESS">IN PROGRESS</button></li>
                                                                <li><button class="dropdown-item" type="submit" name="status" value="IN REVIEW">IN REVIEW</button></li>
                                                                <li><button class="dropdown-item" type="submit" name="status" value="DONE">DONE</button></li>
                                                            </ul>
                                                        </form>
                                                    </div>



                                                </div>
                                                <form method="POST" action="{{ url('/tasks/' . $task->id . '/update-user') }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <!-- User Assign-->
                                                    <div class="dropdown custom-dropdown flex-shrink-0">
                                                        <button
                                                            class="btn btn-light btn-sm dropdown-toggle w-100 text-start d-flex align-items-center"
                                                            type="button"
                                                            id="userDropdown{{ $task->id }}"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            👤 {{ $task->user?->name ?? 'Unassigned' }}
                                                        </button>
                                                        
                                                        <ul class="dropdown-menu animate-dropdown w-100" aria-labelledby="userDropdown{{ $task->id }}">
                                                            <li>
                                                                <button type="submit" name="user_id" value="" class="dropdown-item {{ $task->user_id ? '' : 'active' }}">
                                                                    👤 Unassigned
                                                                </button>
                                                            </li>
                                                            @foreach($users as $user)
                                                                <li>
                                                                    <button type="submit" name="user_id" value="{{ $user->id }}" class="dropdown-item {{ $task->user_id == $user->id ? 'active' : '' }}">
                                                                        👤 {{ $user->name }}
                                                                    </button>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </form>

                                                <!-- Actions Dropdown -->
                                                <div class="dropdown flex-shrink-0">
                                                    <button class="btn btn-sm dots-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <svg class="w-6 h-6 text-gray-800" xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                                                d="M6 12h.01m6 0h.01m5.99 0h.01" />
                                                        </svg>
                                                    </button>
                                                    <ul class="dropdown-menu shadow-sm">
                                                        <li>
                                                            <!-- Delete Task -->
                                                            <button
                                                                type="button"
                                                                class="dropdown-item d-flex align-items-center"
                                                                onclick="confirmDelete(this)" data-id="{{ $task->id }}" data-name="{{ $task->name }}" data-type="task"
                                                            >
                                                                <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M3 15v3c0 .5523.44772 1 1 1h16c.5523 0 1-.4477 1-1v-3M3 15V6c0-.55228.44772-1 1-1h16c.5523 0 1 .44772 1 1v9M3 15h18M8 15v4m4-4v4m4-4v4M12 10l1.5-1.5M12 10l-1.5-1.5M12 10l1.5 1.5M12 10l-1.5 1.5" />
                                                                </svg>
                                                                <span>Delete</span>
                                                            </button>

                                                            <form id="delete-form-task-{{ $task->id }}" action="{{ url('/tasks/' . $task->id . '/delete') }}" method="POST" style="display: none;">
                                                                @csrf
                                                            </form>
                                                        </li>


                                                    </ul>
                                                </div>
                                            
                                            </div>
                                        </div>

                                    </li>

                                    <!-- Task Detail Offcanvas -->
                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="taskDetail-{{ $task->id }}" aria-labelledby="taskDetailLabel-{{ $task->id }}" style="--bs-offcanvas-width: 500px;">
                                        <div class="offcanvas-header border-bottom">
                                            <h5 class="offcanvas-title fw-semibold" id="taskDetailLabel-{{ $task->id }}">{{ $task->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>

                                        <div class="offcanvas-body d-flex flex-column">
                                            {{-- Description Section --}}
                                            <div class="mb-4">
                                                <div class="card border-0 shadow-sm bg-light">
                                                    <div class="card-body">
                                                        <h6 class="text-uppercase text-secondary fw-bold mb-2">
                                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Description
                                                        </h6>
                                                        <p class="mb-0 text-dark fst-italic">
                                                            {{ $task->description ?? 'No description available.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Task Details Section --}}
                                            <div class="mb-4">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-uppercase text-secondary fw-bold mb-3">
                                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9.408-5.5a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM10 10a1 1 0 1 0 0 2h1v3h-1a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-1v-4a1 1 0 0 0-1-1h-2Z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Task Details
                                                        </h6>
                                                        <div class="row gy-2">
                                                            <div class="col-12 col-md-6">
                                                                <div><strong>Assigned to:</strong> {{ $task->user->name ?? 'Unassigned' }}</div>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <div><strong>Type:</strong> {{ $task->type }}</div>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <div><strong>Status:</strong> {{ $task->status }}</div>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <div><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($task->start_date)->format('d M Y') }}</div>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <div><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            {{-- Comments Section --}}
                                            <div class="flex-grow-1 d-flex flex-column justify-content-between">
                                                <div>
                                                    <h6 class="text-uppercase text-secondary fw-bold mb-3">
                                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd" d="M3.559 4.544c.355-.35.834-.544 1.33-.544H19.11c.496 0 .975.194 1.33.544.356.35.559.829.559 1.331v9.25c0 .502-.203.981-.559 1.331-.355.35-.834.544-1.33.544H15.5l-2.7 3.6a1 1 0 0 1-1.6 0L8.5 17H4.889c-.496 0-.975-.194-1.33-.544A1.868 1.868 0 0 1 3 15.125v-9.25c0-.502.203-.981.559-1.331ZM7.556 7.5a1 1 0 1 0 0 2h8a1 1 0 0 0 0-2h-8Zm0 3.5a1 1 0 1 0 0 2H12a1 1 0 1 0 0-2H7.556Z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Comments
                                                    </h6>

                                                    @forelse ($task->attachments as $attachment)
                                                        <div class="mb-3 p-3 bg-light rounded shadow-sm">
                                                            {{-- Top Row: Icon and Name --}}
                                                            <div class="d-flex align-items-center mb-2">
                                                                {{-- Icon --}}
                                                                <div class="me-2">
                                                                    <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                </div>

                                                                {{-- Username and Time --}}
                                                                <div class="d-flex justify-content-between w-100">
                                                                    <span class="fw-semibold">{{ $attachment->user->name }}</span>
                                                                    <small class="text-muted">{{ $attachment->created_at->diffForHumans() }}</small>
                                                                </div>
                                                            </div>

                                                            {{-- Chat Bubble --}}
                                                            @php
                                                                if (!function_exists('linkify')) {
                                                                    function linkify($text) {
                                                                        $pattern = '/(https?:\/\/[^\s]+)/';
                                                                        return preg_replace_callback($pattern, function ($matches) {
                                                                            $url = htmlspecialchars($matches[0], ENT_QUOTES, 'UTF-8');
                                                                            return "<a href=\"{$url}\" target=\"_blank\" rel=\"noopener noreferrer\">{$url}</a>";
                                                                        }, nl2br(e($text)));
                                                                    }
                                                                }
                                                            @endphp


                                                        <div class="bg-white border rounded p-3">
                                                            <p class="mb-0 text-dark">{!! linkify($attachment->comment) !!}</p>
                                                        </div>


                                                        </div>
                                                    @empty
                                                        <p class="text-muted fst-italic">No comments yet.</p>
                                                    @endforelse

                                                </div>

                                                {{-- Add New Comment Form --}}
                                                    <form action="{{ route('tasks.attachments.store', $task->id) }}" method="POST" class="mt-3">
                                                        @csrf
                                                        <div class="mb-2">
                                                            <textarea name="comment" class="form-control" rows="2" placeholder="Add a comment..." required></textarea>
                                                            <!-- Hidden Inputs for User ID (STATIC). Modify to use auth -->
                                                            <input type="hidden" name="user_id" value="1">
                                                        </div>
                                                        <div class="d-grid d-md-flex justify-content-md-end">
                                                            <button type="submit" class="btn btn-sm btn-primary">Post</button>
                                                        </div>
                                                    </form>
                                            </div>

                                        </div>
                                    </div>



                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Add Task Modal -->
            <div class="modal fade" id="addTaskModal-{{ $sprint->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <form class="modal-content rounded-3 shadow-lg" method="POST" action="{{ url('/tasks') }}">
                        @csrf

                        <!-- Header -->
                        <div class="modal-header border-0 bg-primary text-white">
                            <h5 class="modal-title fw-bold">Add Task to {{ $sprint->nama }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Body -->
                        <div class="modal-body p-4">
                            <label for="name" class="form-label">Task Name</label>
                            <input name="name" class="form-control mb-3" placeholder="Design Framework" required>
                            <input type="hidden" name="sprint_id" value="{{ $sprint->id }}">

                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control mb-3" placeholder="Describe the task here..." required></textarea>

                            <label for="user_id" class="form-label">User</label>
                            <select name="user_id" class="form-select mb-3">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>

                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select mb-3" required>
                                <option value="TO DO">TO DO</option>
                                <option value="IN PROGRESS">IN PROGRESS</option>
                                <option value="IN REVIEW">IN REVIEW</option>
                                <option value="DONE">DONE</option>
                            </select>

                            <label for="type" class="form-label">Type</label>
                            <select name="type" class="form-select mb-3" required>
                                <option value="Bug">Bug</option>
                                <option value="Feature">Feature</option>
                                <option value="Task">Task</option>
                                <option value="Story">Story</option>
                                <option value="Request">Request</option>
                            </select>

                            <label for="waktu-mulai" class="form-label">Start Time</label>
                            <input type="date" name="waktu_mulai" id="waktu_mulai_task" class="form-control mb-3" required>

                            <label for="waktu-selesai" class="form-label">End Time</label>
                            <input type="date" name="waktu_selesai" id="waktu_selesai_task" class="form-control mb-3" required>
                        </div>

                        

                        <!-- Footer -->
                        <div class="modal-footer border-0">
                            <button type="submit" id="addTaskButton-{{ $sprint->id }}" class="btn btn-primary rounded-pill px-4">
                                Save Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                // Setup date constraints for task modal
                document.addEventListener("DOMContentLoaded", function () {
                    const modal2 = document.getElementById('addTaskModal-{{ $sprint->id }}');
                    if (modal2) {
                        modal2.addEventListener('shown.bs.modal', function () {
                            setupDateConstraintsTask();
                        });
                    }
                });
            </script>
        @endforeach
    @endif
</div>

<!-- Add Sprint Modal -->
<div class="modal fade" id="addSprintModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form class="modal-content rounded-3 shadow-lg" id="sprintForm" method="POST" action="{{ url('/sprints') }}">
            @csrf

            <!-- Header -->
            <div class="modal-header border-0 bg-primary text-white">
                <h5 class="modal-title fw-bold" id="sprintModalTitle">Add a Sprint</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">
                <input name="id_project" type="hidden" value="{{ $project->id }}">
                <label for="nama" class="form-label">Sprint Name</label>
                <input name="nama" id="sprintNama" class="form-control mb-3" placeholder="Sprint 1" required>
                <label for="waktu-mulai" class="form-label">Start Time</label>
                <input type="date" name="waktu_mulai" id="waktu_mulai" class="form-control mb-3" required>
                <label for="waktu-selesai" class="form-label">End Time</label>
                <input type="date" name="waktu_selesai" id="waktu_selesai" class="form-control mb-3" required>
                <input hidden name="status" value="IN PROGRESS" id="sprintStatus" class="form-control mb-3" required>
            </div>

            <!-- Footer -->
            <div class="modal-footer border-0">
                <button type="submit" id="addSprintButton" class="btn btn-primary rounded-pill px-4">
                    Save Sprint
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4 border-0 animate__animated animate__fadeInDown">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    <h5 class="modal-title mb-0" id="deleteConfirmModalLabel">Delete Confirmation</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-3 fs-6">Are you sure you want to delete this item?</p>
                <h6 id="delete-name" class="fw-bold text-danger text-uppercase"></h6>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger px-4 rounded-pill" id="btnDelete" onclick="submitDelete()">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>

    // Tooltip Initialization
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el, { delay: { "show": 0, "hide": 100 } });
    });
    // Submit type filter form
    function setTypeFilter(type) {
        document.getElementById('typeInput').value = type;
        document.getElementById('typeLabel').innerText = type || 'Filter by type';
        document.querySelector('form').submit();

    }
    
    // Handle form submission click
    document.addEventListener('DOMContentLoaded', function () {
        const allTaskBtns = document.querySelectorAll('[id^="addTaskButton-"]');
        allTaskBtns.forEach(taskBtn => {
            const form = taskBtn.closest('form');
            form.addEventListener('submit', function () {
                taskBtn.disabled = true;
                taskBtn.innerHTML = 'Saving...';
            });
        });

        const allSprintBtns = document.querySelectorAll('[id^="addSprintButton"]');
        allSprintBtns.forEach(sprintBtn => {
            const form = sprintBtn.closest('form');
            form.addEventListener('submit', function () {
                sprintBtn.disabled = true;
                sprintBtn.innerHTML = 'Saving...';
            });
        });
    });

    // Handle delete button click
    const deleteBtn = document.getElementById('btnDelete');
    deleteBtn.addEventListener('click', function () {
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = 'Deleting...';
    });
    
    // Handle edit sprint modal form
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('addSprintModal');
        const title = document.getElementById('sprintModalTitle');
        const form = document.getElementById('sprintForm');
        const inputNama = document.getElementById('sprintNama');
        const inputMulai = document.getElementById('waktu_mulai');
        const inputSelesai = document.getElementById('waktu_selesai');
        const selectStatus = document.getElementById('sprintStatus');

        document.querySelectorAll('.edit-sprint-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                // Change modal title
                title.textContent = this.dataset.title || 'Edit Sprint';

                // Update form action
                form.action = this.dataset.action || "{{ url('/sprints') }}";

                // Update input values
                inputNama.value = this.dataset.nama || '';
                inputMulai.value = this.dataset.start || '';
                inputSelesai.value = this.dataset.end || '';
                selectStatus.value = this.dataset.status || 'IN PROGRESS';
            });
        });

        // Optional: Reset form when modal hides
        modal.addEventListener('hidden.bs.modal', function () {
            title.textContent = 'Add a Sprint';
            form.action = "{{ url('/sprints') }}";
            inputNama.value = '';
            inputMulai.value = '';
            inputSelesai.value = '';
            selectStatus.value = 'IN PROGRESS';
        });
    });

    // Toggle card body visibility
    function toggleCard(header) {
        const icon = header.querySelector('.arrow-icon');
        const cardBody = header.closest('.card').querySelector('.card-body');

        const isExpanded = cardBody.classList.contains('expanded');

        if (isExpanded) {
            cardBody.style.maxHeight = null;
            cardBody.classList.remove('expanded');
            cardBody.style.overflow = 'hidden';
        } else {
            cardBody.style.maxHeight = 500 + 'px'; 
            cardBody.style.overflow = cardBody.scrollHeight > 300 ? 'auto' : 'visible';
            cardBody.classList.add('expanded');
        }

        icon?.classList.toggle('rotated', !isExpanded);
    }

    // Initialize card toggle functionality
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.card-body.card-toggle').forEach(cardBody => {
            const shouldOpen = true; 

            if (shouldOpen) {
                cardBody.classList.add('expanded');
                cardBody.style.maxHeight = 500 + 'px';
                cardBody.style.overflow = cardBody.scrollHeight > 300 ? 'auto' : 'visible';
                
                const icon = cardBody.closest('.card').querySelector('.arrow-icon');
                icon?.classList.add('rotated');
            } else {
                cardBody.classList.remove('expanded');
                cardBody.style.maxHeight = null;
                cardBody.style.overflow = 'hidden';
            }
        });
    });

    // Setup date constraints for sprint and task modals
    function setupDateConstraints() {
        const startInput = document.getElementById('waktu_mulai');
        const endInput = document.getElementById('waktu_selesai');

        if (!startInput || !endInput) return;

        function updateConstraints() {
            if (startInput.value) {
                endInput.min = startInput.value;
            } else {
                endInput.removeAttribute('min');
            }

            if (endInput.value) {
                startInput.max = endInput.value;
            } else {
                startInput.removeAttribute('max');
            }
        }

        startInput.addEventListener('change', updateConstraints);
        endInput.addEventListener('change', updateConstraints);

        updateConstraints();
    }

    function setupDateConstraintsTask() {
        const startInputTask = document.getElementById('waktu_mulai_task');
        const endInputTask = document.getElementById('waktu_selesai_task');

        if (!startInputTask || !endInputTask) return;

        function updateConstraintsTask() {
            if (startInputTask.value) {
                endInputTask.min = startInputTask.value;
            } else {
                endInputTask.removeAttribute('min');
            }

            if (endInputTask.value) {
                startInputTask.max = endInputTask.value;
            } else {
                startInputTask.removeAttribute('max');
            }
        }

        startInputTask.addEventListener('change', updateConstraintsTask);
        endInputTask.addEventListener('change', updateConstraintsTask);

        updateConstraintsTask();
    }


    // Initialize date constraints on modal show
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById('addSprintModal');
        if (modal) {
            modal.addEventListener('shown.bs.modal', function () {
                setupDateConstraints();
            });
        }
    });

    // Delete confirmation
    let deleteTarget = {
        id: null,
        type: null
    };

    function confirmDelete(button) {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const type = button.getAttribute('data-type');

        deleteTarget.id = id;
        deleteTarget.type = type;
        document.getElementById('delete-name').innerText = name;

        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }

    // Submit delete form
    function submitDelete() {
        if (deleteTarget.id && deleteTarget.type) {
            const formId = `delete-form-${deleteTarget.type}-${deleteTarget.id}`;
            const form = document.getElementById(formId);
            if (form) form.submit();
        }
    }

    // Toggle edit task functionality
    function toggleEdit(taskId) {
        const display = document.getElementById(`task-display-${taskId}`);
        const form = document.getElementById(`edit-form-${taskId}`);
        const button = document.getElementById(`edit-button-${taskId}`);
        const input = document.getElementById(`edit-input-${taskId}`);

        display.style.display = 'none';
        form.style.display = 'flex';
        form.classList.add('d-flex')
        button.style.display = 'none';

        setTimeout(() => input.focus(), 100);
    }

    // Cancel edit task
    function cancelEdit(taskId) {
        const display = document.getElementById(`task-display-${taskId}`);
        const form = document.getElementById(`edit-form-${taskId}`);
        const button = document.getElementById(`edit-button-${taskId}`);

        form.style.display = 'none';
        form.classList.remove('d-flex');
        display.style.display = 'block';
        button.style.display = 'block';
    }
</script>
@endpush