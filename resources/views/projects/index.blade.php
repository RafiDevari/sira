@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <form method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" value="{{ $search }}" placeholder="Search projects...">
            <button class="btn btn-primary">Search</button>
        </form>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createProjectModal">+ Create Project</button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr><th>Name</th><th>Type</th><th>Lead</th></tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>{{ $project->nama }}</td>
                    <td>{{ $project->tipe }}</td>
                    <td>{{ $project->lead->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('projects.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Create Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input name="nama" class="form-control mb-2" placeholder="Project Name" required>
                <div class="mb-3">
                    <label for="key" class="form-label">Project Key</label>
                    <input type="text" class="form-control" name="key" id="key" placeholder="Enter unique key (e.g. PROJ-001)" required>
                </div>

                <textarea name="deskripsi" class="form-control mb-2" placeholder="Description"></textarea>

                <select name="id_lead" class="form-control mb-2" required>
                    <option value="">Select Lead</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>

                <select name="id_aplikasi" class="form-control mb-2" required>
                    <option value="">Select Application</option>
                    @foreach($applications as $app)
                        <option value="{{ $app->id }}">{{ $app->name }}</option>
                    @endforeach
                </select>

                <input type="date" name="waktu_mulai" class="form-control mb-2" required>
                <input type="date" name="waktu_selesai" class="form-control mb-2" required>
                <input name="tipe" class="form-control mb-2" placeholder="Type" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
