@extends('layouts.app')

@section('content')
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

    <!-- Right side: Project label -->
    <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#addSprintModal">
        + Add Sprint
    </button>
</div>
    <div>
        <p class="text-muted">{{ $project->deskripsi }}</p>
    </div>



    @foreach($project->sprints as $sprint)
        <div class="bg-light p-3 mb-3 rounded">
            <div class="card border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $sprint->nama }}</strong>
                        <span class="text-muted">({{ $sprint->status }})</span><br>
                        <small>{{ $sprint->waktu_mulai }} – {{ $sprint->waktu_selesai }}</small>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal-{{ $sprint->id }}">
                        + Add Task
                    </button>
                </div>

                <div class="card-body p-0 shadow-sm">
                    <ul class="list-group list-group-flush">
                        @foreach($sprint->tasks as $task)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="row">
                                    <!-- Display Task Title -->
                                    <div class="col-sm fw-semibold text-primary" id="task-display-{{ $task->id }}">
                                        SCRUM-{{ $task->id }} - {{ $task->name }}
                                    </div>

                                    <!-- Edit Button (SVG Icon) -->
                                    <div class="col-auto text-secondary" id="edit-button-{{ $task->id }}">
                                        <svg class="w-6 h-6 text-gray-800 cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                            viewBox="0 0 24 24" onclick="toggleEdit({{ $task->id }})">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                        </svg>
                                    </div>

                                    <!-- Edit Form - HIDDEN INITIALLY -->
                                    <form method="POST" id="edit-form-{{ $task->id }}" class="w-100 align-items-start gap-2" style="display: none;" action="{{ url('/tasks/' . $task->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <input style="max-width: 300px;" id="edit-input-{{ $task->id }}" name="name" class="form-control" value="{{ $task->name }}" required>

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
                                        <select name="user_id" onchange="this.form.submit()" class="form-select form-select-sm">
                                            <option value="">👤 Unassigned</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
                                                    👤 {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>

                                    <!-- Delete Form (POST only) -->
                                    <form action="{{ url('/tasks/' . $task->id . '/delete') }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">🗑️</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Add Task Modal -->
        <div class="modal fade" id="addTaskModal-{{ $sprint->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form class="modal-content" method="POST" action="{{ url('/tasks') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Task to {{ $sprint->nama }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input name="name" class="form-control mb-2" placeholder="Task Name" required>
                        <input type="hidden" name="sprint_id" value="{{ $sprint->id }}">
                        <select name="user_id" class="form-control mb-2">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="form-control mb-2" required>
                            <option value="TO DO">TO DO</option>
                            <option value="IN PROGRESS">IN PROGRESS</option>
                            <option value="IN REVIEW">IN REVIEW</option>
                            <option value="DONE">DONE</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>

<!-- Add Sprint Modal -->
<div class="modal fade" id="addSprintModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ url('/sprints') }}">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Add Sprint</h5></div>
            <div class="modal-body">
                <input name="id_project" type="hidden" value="{{ $project->id }}">
                <input name="nama" class="form-control mb-2" placeholder="Sprint Name" required>
                <input type="date" name="waktu_mulai" class="form-control mb-2" required>
                <input type="date" name="waktu_selesai" class="form-control mb-2" required>
                <input name="status" class="form-control mb-2" placeholder="Status" required>
            </div>
            <div class="modal-footer"><button class="btn btn-primary">Save</button></div>
        </form>
    </div>
</div>



@endsection

@push('scripts')
<script>
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