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
                                    <span class="badge
                                        {{ $task->status === 'DONE' ? 'bg-success' : ($task->status === 'IN PROGRESS' ? 'bg-primary' : ($task->status === 'IN REVIEW' ? 'bg-info' : 'bg-secondary')) }}">
                                        {{ strtoupper($task->status) }}
                                    </span>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('tasks.updateUser', $task->id) }}" class="d-inline ms-3">
                                @csrf @method('PATCH')
                                <select name="user_id" onchange="this.form.submit()" class="form-select form-select-sm">
                                    <option value="">👤 Unassigned</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
                                            👤 {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal-{{ $sprint->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('tasks.store') }}">
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
        <form class="modal-content" method="POST" action="{{ route('sprints.store') }}">
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
