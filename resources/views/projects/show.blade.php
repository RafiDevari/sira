@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $project->nama }}</h2>

    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addSprintModal">+ Add Sprint</button>

    @foreach($project->sprints as $sprint)
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ $sprint->nama }}</strong> ({{ $sprint->status }})<br>
                {{ $sprint->waktu_mulai }} - {{ $sprint->waktu_selesai }}
            </div>
            <div class="card-body">
                @foreach($sprint->tasks as $task)
                    <div class="mb-2">
                        <strong>{{ $task->name }}</strong> - {{ $task->status }}<br>
                        Assigned to:
                        <form method="POST" action="{{ route('tasks.updateUser', $task->id) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <select name="user_id" onchange="this.form.submit()" class="form-select d-inline w-auto">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endforeach

                <!-- Add Task Form Inline Under Each Sprint -->
                <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addTaskModal-{{ $sprint->id }}">
                    + Add Task to {{ $sprint->nama }}
                </button>

                <!-- Task Modal for This Sprint -->
                <div class="modal fade" id="addTaskModal-{{ $sprint->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="{{ route('tasks.store') }}">
                            @csrf
                            <div class="modal-header"><h5 class="modal-title">Add Task to {{ $sprint->nama }}</h5></div>
                            <div class="modal-body">
                                <input name="name" class="form-control mb-2" placeholder="Task Name" required>
                                <input type="hidden" name="sprint_id" value="{{ $sprint->id }}">
                                <select name="user_id" class="form-control mb-2">
                                    <option value="">Unassigned</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <input name="status" class="form-control mb-2" placeholder="Status" required>
                            </div>
                            <div class="modal-footer"><button class="btn btn-primary">Save</button></div>
                        </form>
                    </div>
                </div>

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
