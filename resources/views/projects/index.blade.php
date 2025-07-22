@extends('layouts.app')

@section('content')
<style>
    th.sortable:hover {
        background-color: #f1f1f1; /* Light gray */
        cursor: pointer;
        color: #0d6efd; /* Bootstrap primary */
    }

    a.text-primary:hover {
        text-decoration: underline !important;
    }

    tr:nth-child(even) {
        background-color: #f9fafb !important; /* Light gray */
    }
    tr:hover {
        background-color: #f1f5f9 !important; /* Slightly darker gray */
    }
    .search-bar {
        display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da; /* Bootstrap border color */
        border-radius: 0.375rem;
        background-color: white;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%;
    }

    .search-bar:focus-within {
        border-color: #86b7fe; /* Bootstrap primary */
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .search-bar svg {
        margin-right: 0.5rem;
        color: #6c757d; /* Bootstrap secondary */
        flex-shrink: 0;
    }

    .search-bar input {
        border: none;
        outline: none;
        flex: 1;
        padding: 0;
        background: transparent;
    }
    .table > tbody > tr:nth-child(even) > td {
        background-color: #f9fafb !important;
    }

    .table > tbody > tr:hover > td {
        background-color: #f1f5f9 !important;
    }
    

    
</style>

<div class="container py-5">
    <!-- Title -->
    <h1 class="h3 mb-4 fw-bold text-dark">Projects</h1>

    <!-- Search Section -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3 mb-4">
        <form method="GET" class="d-flex flex-column flex-sm-row gap-2 flex-fill" style="max-width: 600px;">

            <div class="search-bar d-flex align-items-center border rounded px-2 flex-grow-1 w-100">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="me-2 text-secondary">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Search projects name or key..."
                    class="form-control border-0 shadow-none p-0" />
            </div>

            <!-- Hidden input for filter -->
            <input type="hidden" name="type" id="selectedTypeInput" value="{{ request('type') }}">

            <div class="d-flex flex-row gap-2 mt-2 mt-sm-0 w-100 w-sm-auto">
                <div class="dropdown flex-grow-1">
                    <button id="typeDropdownBtn"
                        class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center w-100"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg class="me-2" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
                        </svg>
                        <span id="typeLabel">{{ request('type') ?: 'Filter by type' }}</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item" type="button"
                                onclick="setTypeFilter('', 'Filter by type')">All Types</button></li>
                        @foreach($allTypes as $tipe)
                            <li><button class="dropdown-item" type="button"
                                    onclick="setTypeFilter('{{ $tipe }}', '{{ $tipe }}')">{{ $tipe }}</button></li>
                        @endforeach
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">Search</button>
            </div>
        </form>

        <button type="button" onclick="openModal()" class="btn btn-success d-flex align-items-center gap-2 fw-bold  ">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            Create Project
        </button>
    </div>

    <!-- Projects Table -->
    <div class="  shadow-sm table-responsive rounded">
        <table class="table mb-0" id="project-table">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 40px;">★</th>
                    <th class="sortable" data-key="name" data-bs-toggle="tooltip" title="">Name <span class="sort-indicator"></span></th>
                    <th class="sortable" data-key="key" data-bs-toggle="tooltip" title="">Key <span class="sort-indicator"></span></th>
                    <th class="sortable" data-key="type" data-bs-toggle="tooltip" title="">Type <span class="sort-indicator"></span></th>
                    <th class="sortable" data-key="lead" data-bs-toggle="tooltip" title="">Lead <span class="sort-indicator"></span></th>
                    <th class="sortable" data-key="created_at" data-bs-toggle="tooltip" title="">Deadline <span class="sort-indicator"></span></th>
                    <th class="text-center" style="width: 40px;" >Project</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td class="text-center align-middle star-cell" style="cursor: pointer;">☆</td>
                        <td class="w-25">
                            <a href="{{ route('projects.show', $project->id) }}" class="text-primary text-decoration-none">
                                {{ $project->nama }}
                            </a>
                        </td>
                        <td class="w-15">{{ $project->key }}</td>
                        <td class="w-15">{{ $project->tipe }}</td>
                        <td class="w-25">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                            </svg>
                            {{ $project->lead->name ?? '-' }}
                        </td>

                        @php
                            $deadline = \Carbon\Carbon::parse($project->waktu_selesai);
                            $now = \Carbon\Carbon::now();
                            $diffInDays = $now->diffInDays($deadline, false); // negative if expired
                        @endphp

                        <td class="w-25">
                            @if ($diffInDays <= 0)
                                <span class="text-success">Finished</span>
                            @elseif ($diffInDays <= 31)
                                <span class="text-danger">{{ $diffInDays }} day{{ $diffInDays !== 1 ? 's' : '' }} left</span>
                            @else
                                {{ $now->diffInMonths($deadline) }} month{{ $now->diffInMonths($deadline) !== 1 ? 's' : '' }} left
                            @endif
                        </td>
                        <td>
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 12h.01m6 0h.01m5.99 0h.01"/>
                            </svg>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

<!-- Create Project Modal -->
<div id="createProjectModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('projects.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Basic Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Project Name</label>
                                <input name="nama" class="form-control" placeholder="Palm Disease Detection" required />
                            </div>
                            <div class="col-md-6">
                                <label for="key" class="form-label">Project Key</label>
                                <input name="key" id="key" class="form-control" placeholder="PDD-001" required />
                            </div>
                            <div class="col-12">
                                <label for="deskripsi" class="form-label">Description</label>
                                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Project description" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Project Details</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="id_lead" class="form-label">Project Lead</label>
                                <select name="id_lead" class="form-select" required>
                                    <option value="">Select Lead</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="id_aplikasi" class="form-label">Application</label>
                                <select name="id_aplikasi" class="form-select" required>
                                    <option value="">Select Application</option>
                                    @foreach($applications as $app)
                                        <option value="{{ $app->id }}">{{ $app->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Schedule & Type</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="waktu_mulai" class="form-label">Start Date</label>
                                <input type="date" name="waktu_mulai" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label for="waktu_selesai" class="form-label">End Date</label>
                                <input type="date" name="waktu_selesai" class="form-control" required />
                            </div>
                            <div class="col-12">
                                <label for="tipe" class="form-label">Project Type</label>
                                <select name="tipe" class="form-select" required>
                                    <option value="">Select Type</option>
                                    @foreach($allTypes as $tipe)
                                        <option value="{{ $tipe }}" {{ request('tipe') == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const currentSort = new URLSearchParams(window.location.search).get('sort') || '';
    const [currentKey, currentDirection] = currentSort.split(':');

    const tooltipTriggerList = [];

    document.querySelectorAll('.sortable').forEach(th => {
        const key = th.dataset.key;
        const indicator = th.querySelector('.sort-indicator');

        let nextDirection = 'asc';

        if (key === currentKey) {
            if (currentDirection === 'asc') {
                nextDirection = 'desc';
                indicator.textContent = '↑';
            } else if (currentDirection === 'desc') {
                nextDirection = null;
                indicator.textContent = '↓';
            }
        }

        let tooltipText = 'Sort asc ↑';
        if (key === currentKey) {
            tooltipText = currentDirection === 'asc' ? 'Sort desc ↓' :
                          currentDirection === 'desc' ? 'Remove sort' : 'Sort as ↑';
        }

        th.setAttribute('title', tooltipText);

        const tooltip = new bootstrap.Tooltip(th);
        tooltipTriggerList.push(tooltip);

        th.style.cursor = 'pointer';
        th.classList.add('hover-shadow');

        th.addEventListener('click', () => {
            let nextDir = 'asc';
            if (key === currentKey) {
                if (currentDirection === 'asc') nextDir = 'desc';
                else if (currentDirection === 'desc') nextDir = null;
            }

            const url = new URL(window.location.href);
            if (!nextDir) {
                url.searchParams.delete('sort');
            } else {
                url.searchParams.set('sort', `${key}:${nextDir}`);
            }

            window.location.href = url.toString();
        });
    });

    function openModal() {
        const modalElement = document.getElementById('createProjectModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            console.error("Modal element not found");
        }
    }

    function setTypeFilter(value, label) {
        document.getElementById('selectedTypeInput').value = value;
        document.getElementById('typeLabel').textContent = label;
    }

    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("project-table");
        const tbody = table.querySelector("tbody");

        tbody.addEventListener("click", function (e) {
            if (e.target && e.target.classList.contains("star-cell")) {
                const cell = e.target;
                const row = cell.closest("tr");

                const isStarred = cell.textContent.trim() === "★";

                if (isStarred) {
                    cell.textContent = "☆";
                    tbody.appendChild(row); // Move to bottom
                } else {
                    cell.textContent = "★";
                    tbody.prepend(row); // Move to top
                }
            }
        });
    });
</script>
@endpush
