@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $project->nama }}</h2>

    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addSprintModal">+ Add Sprint</button>

    @foreach($project->sprints as $sprint)
        <div class="bg-light p-3 mb-3 rounded">
            <div class="card mb-4 border-0">
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

                <div class="card-body px-0 shadow-sm">
                    <ul class="list-group list-group-flush">
                        @foreach($sprint->tasks as $task)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold text-primary">SCRUM-{{ $task->id }} - {{ $task->name }}</div>
                                    <div class="d-flex gap-2 align-items-center mt-1">
                                        <span class="badge bg-purple text-dark text-uppercase small">
                                            (Sample) {{ explode(' ', $task->name)[1] ?? 'Feature' }}
                                        </span>
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
                                </div>

                                <div class="d-flex gap-2 ms-3 align-items-center">
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

                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTaskModal-{{ $task->id }}">✏️</button>

                                    <!-- Delete Form (POST only) -->
                                    <form action="{{ url('/tasks/' . $task->id . '/delete') }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">🗑️</button>
                                    </form>
                                </div>
                            </li>

                            <!-- Edit Task Modal -->
                            <div class="modal fade" id="editTaskModal-{{ $task->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form class="modal-content" method="POST" action="{{ url('/tasks/' . $task->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Task</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input name="name" class="form-control mb-2" value="{{ $task->name }}" required>
                                            <select name="user_id" class="form-control mb-2">
                                                <option value="">Unassigned</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <select name="status" class="form-control mb-2" required>
                                                <option value="TO DO" {{ $task->status === 'TO DO' ? 'selected' : '' }}>TO DO</option>
                                                <option value="IN PROGRESS" {{ $task->status === 'IN PROGRESS' ? 'selected' : '' }}>IN PROGRESS</option>
                                                <option value="IN REVIEW" {{ $task->status === 'IN REVIEW' ? 'selected' : '' }}>IN REVIEW</option>
                                                <option value="DONE" {{ $task->status === 'DONE' ? 'selected' : '' }}>DONE</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
