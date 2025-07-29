@extends('layouts.app')

@section('content')
<style>
    .dots-btn {
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .dots-btn:hover {
        border: 1px solid #d0d0d0; /* border on hover like the image */
        background-color: #f8f9fa;
    }

    .dropdown-item:hover {
        background-color: #f1f1f1; /* Button hover effect */
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
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0069d9;
    }

    .arrow-icon {
        transform: rotate(0deg);
        transition: transform 0.3s ease;
    }

    .arrow-icon.rotated {
        transform: rotate(90deg);
    }

    .card-toggle {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, padding 0.3s ease;
    }

    .card-toggle.expanded {
        max-height: 500px; /* set according to expected max content height */
    }

    .card-toggle p {
        margin: 0;
    }

    .arrow-icon {
        transform: rotate(0deg);
        transition: transform 0.3s ease;
    }

    .arrow-icon.rotated {
        transform: rotate(90deg);
    }

    .arrow-icon:hover {
        transform: rotate(45deg);
    }

    .arrow-icon:hover path {
        stroke: #1d4ed8; /* e.g. Tailwind's blue-700 */
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
        background-color: rgba(108, 117, 125, 0.05); /* subtle background on hover (optional) */
        cursor: pointer;
    }


</style>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Left side: Project Name & Timeline -->
        <div>
            <h2 class="mb-1">{{ $project->nama }}</h2>
            <div class="text-muted small d-flex align-items-center">
                <svg class="w-6 h-6 text-gray-800 dark:text-white me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/>
                </svg>

                {{ \Carbon\Carbon::parse($project->waktu_mulai)->format('d F') }} - {{ \Carbon\Carbon::parse($project->waktu_selesai)->format('d F') }}
            </div>
        </div>

        
    </div>
    <div>
        <p class="text-muted">{{ $project->deskripsi }}</p>
    </div>

    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <!-- Search & Filter Form -->
        <form method="GET" class="d-flex flex-column flex-md-row align-items-md-center gap-2 flex-grow-1" style="max-width: 100%;">
            <!-- Search bar -->
            <div class="search-bar d-flex align-items-center border border-1 border-secondary rounded px-2 w-100" style="max-width: 280px; height: 38px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="me-2 text-secondary">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
                <input type="text" name="search"
                    value="{{ request('search') }}"
                    placeholder="Search projects name or key..."
                    class="form-control border-0 shadow-none p-0"
                    style="font-size: 0.875rem; line-height: 1.5;" />
            </div>



            <!-- Dropdown Filter -->
            <div class="dropdown flex-shrink-0">
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

            <script>
                function setTypeFilter(type) {
                    document.getElementById('typeInput').value = type;
                    document.getElementById('typeLabel').innerText = type || 'Filter by type';
                    document.querySelector('form').submit();

                }
            </script>

            <!-- Search Button -->
            <button id="ibra" type="submit" class="btn btn-primary fw-semibold flex-shrink-0">Search</button>
        </form>

        <!-- Sticky Add Sprint Button -->
        <div class="flex-shrink-0">
            <button class="btn btn-success fw-semibold" data-bs-toggle="modal" data-bs-target="#addSprintModal">
                + Add Sprint
            </button>
        </div>
    </div>


    @foreach($project->sprints as $sprint)
        <div class="bg-light p-3 mb-3 rounded">
            <div class="card border-0">
                <div class="card-header bg-light d-flex bd-highlight align-items-center">
                    <div class=" toggle-header bd-highlight me-3" onclick="toggleCard(this)">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white arrow-icon transition" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                        </svg>

                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong class="d-block">{{ $sprint->nama }}</strong>
                            <small class="d-block">
                                {{ \Carbon\Carbon::parse($sprint->waktu_mulai)->format('d F') }} - 
                                {{ \Carbon\Carbon::parse($sprint->waktu_selesai)->format('d F') }}
                            </small>
                        </div>

                        <form method="POST" action="{{ url('/sprints/' . $sprint->id . '/toggle-status') }}" class="ms-4">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="btn btn-sm px-3 py-1 rounded-pill fw-semibold toggle-status-btn {{ $sprint->status == 'COMPLETED' ? 'btn-success' : 'btn-outline-secondary' }}"
                                onclick="this.querySelector('input[name=status]').value = '{{ $sprint->status == 'COMPLETED' ? 'IN PROGRESS' : 'COMPLETED' }}'">
                                {{ $sprint->status == 'COMPLETED' ? '☑️ Completed' : '⬜ In Progress' }}
                                <input type="hidden" name="status" value="">
                            </button>
                        </form>
                    </div>

                    <button class="btn btn-sm btn-outline-primary bd-highlight ms-auto" data-bs-toggle="modal" data-bs-target="#addTaskModal-{{ $sprint->id }}">
                        + Add Task
                    </button>
                    <div class="dropdown ms-2">
                        <button class="btn btn-sm dots-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="w-6 h-6 text-gray-800" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                    d="M6 12h.01m6 0h.01m5.99 0h.01" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu shadow-sm">
                            <li>
                                <button
                                    type="button"
                                    class="dropdown-item d-flex align-items-center edit-sprint-btn"
                                    data-bs-toggle="modal" data-bs-target="#addSprintModal"
                                    data-title="Edit Sprint"
                                    data-action="{{ url('/sprints/' . $sprint->id . '/update') }}"
                                    data-nama="{{ $sprint->nama }}"
                                    data-start="{{ $sprint->waktu_mulai }}"
                                    data-end="{{ $sprint->waktu_selesai }}"
                                    data-status="{{ $sprint->status }}"
                                >
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                    </svg>
                                    <span>Edit</span>
                                </button>
                            </li>
                            <li>
                                <button 
                                    type="button" 
                                    class="dropdown-item d-flex align-items-center "
                                    onclick="confirmDelete({{ $sprint->id }}, '{{ addslashes($sprint->name) }}')"
                                >
                                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 15v3c0 .5523.44772 1 1 1h16c.5523 0 1-.4477 1-1v-3M3 15V6c0-.55228.44772-1 1-1h16c.5523 0 1 .44772 1 1v9M3 15h18M8 15v4m4-4v4m4-4v4M12 10l1.5-1.5M12 10l-1.5-1.5M12 10l1.5 1.5M12 10l-1.5 1.5" />
                                    </svg>
                                    <span>Delete</span>
                                </button>

                                <!-- Hidden form to be submitted by JS -->
                                <form id="delete-form-{{ $sprint->id }}" action="{{ url('/sprints/' . $sprint->id . '/delete') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>


                        </ul>
                    </div>
                </div>

                <div class="card-body p-0 shadow-sm card-toggle collapsed " style="overflow:visible;">
                    <ul class="list-group list-group-flush">
                        @foreach($sprint->tasks as $task)
                            <li class="list-group-item hover-card d-flex justify-content-between align-items-center">
                                <div class="row align-items-center w-100">
                                    <!-- Display Task Title -->
                                    <div class="col-auto dropdown">
                                        <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="taskTypeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            @if ($task->type === 'Bug')
                                                <svg data-bs-toggle="tooltip" title="Bug" class="w-6 h-6 text-danger dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18 17h-.09c.058-.33.088-.665.09-1v-1h1a1 1 0 0 0 0-2h-1.09a5.97 5.97 0 0 0-.26-1H17a2 2 0 0 0 2-2V8a1 1 0 1 0-2 0v2h-.54a6.239 6.239 0 0 0-.46-.46V8a3.963 3.963 0 0 0-.986-2.6l.693-.693A1 1 0 0 0 16 4V3a1 1 0 1 0-2 0v.586l-.661.661a3.753 3.753 0 0 0-2.678 0L10 3.586V3a1 1 0 1 0-2 0v1a1 1 0 0 0 .293.707l.693.693A3.963 3.963 0 0 0 8 8v1.54a6.239 6.239 0 0 0-.46.46H7V8a1 1 0 0 0-2 0v2a2 2 0 0 0 2 2h-.65a5.97 5.97 0 0 0-.26 1H5a1 1 0 0 0 0 2h1v1a6 6 0 0 0 .09 1H6a2 2 0 0 0-2 2v2a1 1 0 1 0 2 0v-2h.812A6.012 6.012 0 0 0 11 21.907V12a1 1 0 0 1 2 0v9.907A6.011 6.011 0 0 0 17.188 19H18v2a1 1 0 0 0 2 0v-2a2 2 0 0 0-2-2Zm-4-8.65a5.922 5.922 0 0 0-.941-.251l-.111-.017a5.52 5.52 0 0 0-1.9 0l-.111.017A5.925 5.925 0 0 0 10 8.35V8a2 2 0 1 1 4 0v.35Z"/>
                                            </svg>
                                            @elseif ($task->type === 'Feature')
                                                <svg data-bs-toggle="tooltip" title="Feature" class="w-6 h-6 text-success dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M5.005 10.19a1 1 0 0 1 1 1v.233l5.998 3.464L18 11.423v-.232a1 1 0 1 1 2 0V12a1 1 0 0 1-.5.866l-6.997 4.042a1 1 0 0 1-1 0l-6.998-4.042a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1ZM5 15.15a1 1 0 0 1 1 1v.232l5.997 3.464 5.998-3.464v-.232a1 1 0 1 1 2 0v.81a1 1 0 0 1-.5.865l-6.998 4.042a1 1 0 0 1-1 0L4.5 17.824a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1Z" clip-rule="evenodd"/>
                                                <path d="M12.503 2.134a1 1 0 0 0-1 0L4.501 6.17A1 1 0 0 0 4.5 7.902l7.002 4.047a1 1 0 0 0 1 0l6.998-4.04a1 1 0 0 0 0-1.732l-6.997-4.042Z"/>
                                            </svg>
                                            @elseif ($task->type === 'Task')
                                                <svg data-bs-toggle="tooltip" title="Task" class="w-6 h-6 text-primary dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                                            </svg>
                                            @elseif ($task->type === 'Story')
                                                <svg data-bs-toggle="tooltip" title="Story" class="w-6 h-6 text-success dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M7.833 2c-.507 0-.98.216-1.318.576A1.92 1.92 0 0 0 6 3.89V21a1 1 0 0 0 1.625.78L12 18.28l4.375 3.5A1 1 0 0 0 18 21V3.889c0-.481-.178-.954-.515-1.313A1.808 1.808 0 0 0 16.167 2H7.833Z"/>
                                            </svg>
                                            @elseif ($task->type === 'Request')
                                                <svg data-bs-toggle="tooltip" title="Request" class="w-6 h-6 text-success dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
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
                                                        <svg class="w-6 h-6 text-danger dark:text-white me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="24" height="24" viewBox="0 0 24 24">
                                                            <path d="..."/>
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
                                                        <svg class="w-6 h-6 text-success dark:text-white me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="24" height="24" viewBox="0 0 24 24">
                                                            <path d="..."/>
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
                                                        <svg class="w-6 h-6 text-primary dark:text-white me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="24" height="24" viewBox="0 0 24 24">
                                                            <path d="..."/>
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
                                                        <svg class="w-6 h-6 text-success dark:text-white me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="24" height="24" viewBox="0 0 24 24">
                                                            <path d="..."/>
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
                                                        <svg class="w-6 h-6 text-success dark:text-white me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="24" height="24" viewBox="0 0 24 24">
                                                            <path d="..."/>
                                                        </svg>
                                                        Request
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>

                                    </div>


                                    <div class="col-auto fw-semibold" id="task-display-{{ $task->id }}" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        SCRUM-{{ $task->id }} - {{ $task->name }}
                                    </div>

                                    <!-- Edit Button (SVG Icon) -->
                                    <div class="col-auto text-secondary editbtn align-items-center" id="edit-button-{{ $task->id }}">
                                        <svg class="text-gray-800 cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                            viewBox="0 0 24 24" onclick="toggleEdit({{ $task->id }})">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                        </svg>
                                    </div>

                                    <!-- Edit Form - HIDDEN INITIALLY -->
                                    <form method="POST" id="edit-form-{{ $task->id }}" class="w-100 col-auto gap-2 " style="display: none;max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" action="{{ url('/tasks/' . $task->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <input style="max-width: 300px;" id="edit-input-{{ $task->id }}" name="name" class="form-control col" value="{{ $task->name }}" required>

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

                                <div class="d-flex gap-2 ms-3 align-items-center">
                                    <div class="d-flex gap-2 align-items-center mt-1">
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
                                    <!-- User Assign -->
                                    <form method="POST" action="{{ url('/tasks/' . $task->id . '/update-user') }}">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="dropdown custom-dropdown">
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


                                    <div class="dropdown">
                                        <button class="btn btn-sm dots-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg class="w-6 h-6 text-gray-800" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                                    d="M6 12h.01m6 0h.01m5.99 0h.01" />
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu shadow-sm">
                                            <li>
                                                <button 
                                                    type="button" 
                                                    class="dropdown-item d-flex align-items-center text-danger"
                                                    onclick="confirmDelete({{ $task->id }}, '{{ addslashes($task->name) }}')"
                                                >
                                                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 15v3c0 .5523.44772 1 1 1h16c.5523 0 1-.4477 1-1v-3M3 15V6c0-.55228.44772-1 1-1h16c.5523 0 1 .44772 1 1v9M3 15h18M8 15v4m4-4v4m4-4v4M12 10l1.5-1.5M12 10l-1.5-1.5M12 10l1.5 1.5M12 10l-1.5 1.5" />
                                                    </svg>
                                                    <span>Delete</span>
                                                </button>

                                                <!-- Hidden form to be submitted by JS -->
                                                <form id="delete-form-{{ $task->id }}" action="{{ url('/tasks/' . $task->id . '/delete') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </li>


                                        </ul>
                                    </div>
                                
                                </div>
                            </li>
                        @endforeach
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
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            Save Task
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @endforeach
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
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    Save Sprint
                </button>
            </div>
        </form>
    </div>
</div>


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
                <p class="mb-3 fs-6">Are you sure you want to delete this task?</p>
                <h6 id="delete-task-name" class="fw-bold text-danger text-uppercase"></h6>
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

    function toggleCard(header) {
        const icon = header.querySelector('.arrow-icon');
        const cardBody = document.querySelector('.card-body');

        const isExpanded = cardBody.classList.contains('expanded');

        cardBody.style.overflow = isExpanded ? 'hidden' : 'visible';
        icon.classList.toggle('rotated', !isExpanded);
        cardBody.classList.toggle('expanded', !isExpanded);
    }

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

    // Initialize date constraints on modal show
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById('addSprintModal');
        if (modal) {
            modal.addEventListener('shown.bs.modal', function () {
                setupDateConstraints();
            });
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.card-toggle').forEach(card => {
            card.classList.add('expanded');
        });

        document.querySelectorAll('.arrow-icon').forEach(icon => {
            icon.classList.add('rotated');
        });
    });

    let TaskIdToDelete = null;

    function confirmDelete(taskId, taskName) {
        TaskIdToDelete = taskId;
        document.getElementById('delete-task-name').innerText = taskName;

        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }

    function submitDelete() {
        if (TaskIdToDelete !== null) {
            const form = document.getElementById(`delete-form-${TaskIdToDelete}`);
            if (form) form.submit();
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    function toggleEdit(taskId) {
        const display = document.getElementById(`task-display-${taskId}`);
        const form = document.getElementById(`edit-form-${taskId}`);
        const button = document.getElementById(`edit-button-${taskId}`);
        const input = document.getElementById(`edit-input-${taskId}`);

        display.style.display = 'none';
        form.style.display = 'flex';
        form.classList.add('d-flex')
        button.style.display = 'none';

        // Focus input
        setTimeout(() => input.focus(), 100);
    }

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