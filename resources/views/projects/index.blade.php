@extends('layouts.app')

@section('content')
<style>
    .search-bar {
        display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        background-color: white;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%;
    }

    .search-bar:focus-within {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .search-bar svg {
        margin-right: 0.5rem;
        color: #6c757d;
        flex-shrink: 0;
    }

    .search-bar input {
        border: none;
        outline: none;
        flex: 1;
        padding: 0;
        background: transparent;
    }
    th.sortable:hover {
        background-color: #f1f1f1;
        cursor: pointer;
        color: #0d6efd;
    }

    /* Link hover effect */
    a.text-primary:hover {
        text-decoration: underline !important;
    }

    /* Zebra striping for table rows */
    tr:nth-child(even),
    .table > tbody > tr:nth-child(even) > td {
        background-color: #f9fafb !important;
    }

    tr:hover,
    .table > tbody > tr:hover > td {
        background-color: #f1f5f9 !important;
    }

    /* Search bar styling */
    .search-bar {
        display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        background-color: white;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%;
    }

    .search-bar:focus-within {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .search-bar svg {
        margin-right: 0.5rem;
        color: #6c757d;
        flex-shrink: 0;
    }

    .search-bar input {
        border: none;
        outline: none;
        flex: 1;
        padding: 0;
        background: transparent;
    }

    /* Table styling */
    table {
        min-width: 800px;
        width: max-content;
        border-collapse: collapse;
        table-layout: auto;
    }

    th, td {
        white-space: normal;
        word-break: break-word;
        min-width: 20px;
    }

    thead th {
        position: sticky;
        top: 0;
        z-index: 2;
    }

    /* Dropdown styling */
    .dropdown-item:hover,
    .dropdown-item:focus {
        background-color: #f0f0f0;
        color: #333;
    }

    .dropdown-menu {
        display: block !important;
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.1s ease;
        pointer-events: none;
    }

    .dropdown-menu.show {
        visibility: visible;
        opacity: 1 !important;
        pointer-events: auto;
    }

    .form-control-sm,
    .form-select-sm {
        border-radius: 0.5rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
    }   

</style>

<div class="container py-5">
    <!-- Title -->
    <h1 class="h3 mb-4 fw-bold text-dark">Projects</h1>

    <!-- Search Section -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3 mb-4">
        <form method="GET" class="d-flex flex-column flex-sm-row gap-2 flex-fill" style="max-width: 600px;">
            <!-- Search Bar -->
            <div class="search-bar d-flex align-items-center border border-1 border-secondary rounded px-2 flex-grow-1 w-100">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="me-2 text-secondary">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Search projects name or key..."
                    class="form-control border-0 shadow-none p-0" />
            </div>

            <!-- Hidden Filters -->
            <input type="hidden" name="type" id="selectedTypeInput" value="{{ request('type') }}">
            <input type="hidden" name="sort" value="{{ request('sort') }}">

            <!-- Filter + Search Button -->
            <div class="d-flex flex-row gap-2 mt-2 mt-sm-0 w-100 w-sm-auto">
                <!-- Dropdown Filter -->
                <div class="dropdown">
                    <button id="typeDropdownBtn"
                        class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center w-100"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg class="me-2" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
                        </svg>
                        <span id="typeLabel">{{ request('type') ?: 'Filter by type' }}</span>
                    </button>
                    <ul class="dropdown-menu w-100" style="opacity: 0; transition: opacity 0.2s ease;">
                        <li><button class="dropdown-item" type="button"
                                onclick="setTypeFilter('', 'Filter by type')">All Types</button></li>
                        <li><button class="dropdown-item" type="button"
                                onclick="setTypeFilter('Software', 'Software')">Software</button></li>
                        <li><button class="dropdown-item" type="button"
                                onclick="setTypeFilter('Hardware', 'Hardware')">Hardware</button></li>
                        <li><button class="dropdown-item" type="button"
                                onclick="setTypeFilter('Network', 'Network')">Network</button></li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary fw-semibold">Search</button>
            </div>
        </form>

        <!-- Create Button -->
        <button type="button" onclick="openModal()" class="btn btn-success d-flex align-items-center gap-2 fw-semibold">
            <svg class="w-2 h-2 text-gray-800 dark:text-white" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Create Project
        </button>
    </div>


    <!-- Projects Table -->
    <div class="table-responsive" style="overflow: auto; max-height: 320px;">
        <table class="table mb-0" id="project-table">
            <thead class="table-light">
                <tr>
                    <th style="width: 20px;" class="text-center align-middle">★</th>
                    <th style="width: 80px;" class="sortable align-middle" data-key="name" data-bs-toggle="tooltip">
                        Name <span class="sort-indicator"></span>
                    </th>
                    <th style="width: 33px;" class="sortable align-middle" data-key="key" data-bs-toggle="tooltip">
                        Key <span class="sort-indicator"></span>
                    </th>
                    <th style="width: 50px;" class="sortable align-middle" data-key="type" data-bs-toggle="tooltip">
                        Type <span class="sort-indicator"></span>
                    </th>
                    <th style="width: 60px;" class="sortable align-middle" data-key="lead" data-bs-toggle="tooltip">
                        Lead <span class="sort-indicator"></span>
                    </th>
                    <th style="width: 40px;" class="sortable align-middle" data-key="created_at" data-bs-toggle="tooltip">
                        Finish Line <span class="sort-indicator"></span>
                    </th>
                    <th style="width: 10px;" class="text-center align-middle col-actions">Project</th>
                </tr>
            </thead>
            <tbody style="max-height: 240px;">
                @forelse($projects as $project)
                    <tr>
                        <td class="text-center align-middle star-cell" style="cursor: pointer;">☆</td>
                        <td class="align-middle col-name">
                            <a href="{{ route('projects.show', $project->id) }}" class="text-primary text-decoration-none">
                                {{ $project->nama }}
                            </a>
                        </td>
                        <td class="align-middle">{{ $project->key }}</td>
                        <td class="align-middle">{{ $project->tipe }}</td>
                        <td class="align-middle">
                            <svg class="w-6 h-6 text-gray-800" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M12 20a7.966 7.966 0 0 1-5.002-1.756v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $project->lead->name ?? '-' }}
                        </td>
                        @php
                            $deadline = \Carbon\Carbon::parse($project->waktu_selesai);
                            $now = \Carbon\Carbon::now();
                            $diffInDays = $now->diffInDays($deadline, false);
                        @endphp
                        <td class="align-middle">
                            @if ($diffInDays <= 0)
                                <span class="text-success">Finished</span>
                            @elseif ($diffInDays <= 31)
                                <span class="text-danger">{{ $diffInDays }} day{{ $diffInDays !== 1 ? 's' : '' }} left</span>
                            @else
                                {{ $now->diffInMonths($deadline) }} month{{ $now->diffInMonths($deadline) !== 1 ? 's' : '' }} left
                            @endif
                        </td>
                        <td class="align-middle text-center" x-data="{ open: false }" @click.outside="open = false" @scroll.window="open = false">
                            <div style="position: relative;" x-init="
                                $nextTick(() => {
                                    let container = document.querySelector('.table-responsive');
                                    if (container) {
                                        container.addEventListener('scroll', () => open = false);
                                    }
                                })
                            ">
                                <button @click="open = !open"
                                    style="background: none; border: 1px solid transparent; cursor: pointer; padding: 4px; border-radius: 6px; transition: border-color 0.2s;"
                                    onmouseover="this.style.borderColor='#999'"
                                    onmouseout="this.style.borderColor='transparent'"
                                    onfocus="this.style.borderColor='#555'"
                                    onblur="this.style.borderColor='transparent'">
                                    <svg class="w-6 h-6 text-gray-800" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                            d="M6 12h.01m6 0h.01m5.99 0h.01" />
                                    </svg>
                                </button>

                                <div x-show="open" x-transition style="
                                    position: absolute;
                                    right: 0;
                                    margin-top: 5px;
                                    background: white;
                                    border: 1px solid #ccc;
                                    border-radius: 6px;
                                    z-index: 20;
                                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                                    min-width: 120px;
                                    padding: 4px;
                                ">
                                    <div style="
                                        position: absolute;
                                        top: -6px;
                                        right: 6px;
                                        width: 0;
                                        height: 0;
                                        border-left: 6px solid transparent;
                                        border-right: 6px solid transparent;
                                        border-bottom: 6px solid white;
                                        filter: drop-shadow(0 -1px 1px rgba(0,0,0,0.1));
                                    "></div>

                                    <!-- Edit Button -->
                                    <button style="width: 100%; padding: 8px 12px; background: none; border: none; text-align: left; cursor: pointer; font-size: 14px; color: #333; transition: background 0.2s;"
                                        onmouseover="this.style.background='#f5f5f5'" 
                                        onmouseout="this.style.background='none'"
                                        onclick="openModal(
                                            {{ $project->id }},
                                            '{{ $project->nama }}',
                                            '{{ $project->key }}',
                                            '{{ $project->deskripsi }}',
                                            '{{ $project->id_lead }}',
                                            '{{ $project->id_aplikasi }}',
                                            '{{ $project->waktu_mulai }}',
                                            '{{ $project->waktu_selesai }}',
                                            '{{ $project->tipe }}'
                                        )">
                                        <div class="align-items-center d-flex gap-2">
                                            <svg class="w-6 h-6 text-gray-800" xmlns="http://www.w3.org/2000/svg" width="18"
                                                height="18" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                            </svg>
                                            <div>
                                                Edit
                                            </div>
                                        </div>
                                        
                                    </button>

                                    <!-- Delete Button -->
                                    <button style="width: 100%; padding: 8px 12px; background: none; border: none; text-align: left; cursor: pointer; font-size: 14px; color: #333; transition: background 0.2s;"
                                        onmouseover="this.style.background='#f5f5f5'" 
                                        onmouseout="this.style.background='none'"
                                        onclick="confirmDelete({{ $project->id }}, '{{ addslashes($project->nama) }}')">
                                        <div class="align-items-center d-flex gap-2">
                                            <svg class="w-6 h-6 text-gray-800" xmlns="http://www.w3.org/2000/svg" width="18"
                                                height="18" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 15v3c0 .5523.44772 1 1 1h16c.5523 0 1-.4477 1-1v-3M3 15V6c0-.55228.44772-1 1-1h16c.5523 0 1 .44772 1 1v9M3 15h18M8 15v4m4-4v4m4-4v4m-5.5061-7.4939L12 10m0 0 1.5061-1.50614M12 10l1.5061 1.5061M12 10l-1.5061-1.50614" />
                                            </svg>
                                            <div>
                                                Delete

                                            </div>
                                        </div>
                                        
                                    </button>

                                    <form id="delete-form-{{ $project->id }}" method="POST"
                                        action="{{ route('projects.destroy', $project->id) }}" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
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
                    <p class="mb-3 fs-6">Are you sure you want to delete this project?</p>
                    <h6 id="delete-project-name" class="fw-bold text-danger text-uppercase"></h6>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4 rounded-pill" id="btnDelete" onclick="submitDelete()">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Project Modal -->
    <div id="projectModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4 border-0">
                <form method="POST" id="projectForm" action="{{ route('projects.store') }}">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="form-method">
                    <input type="hidden" name="project_id" id="project-id" />

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <h5 class="modal-title fw-semibold" id="projectModalLabel">New Project</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body px-4">
                        <!-- Basic Info -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 text-primary">Basic Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Project Name</label>
                                    <input name="nama" class="form-control form-control-sm" placeholder="Palm Disease Detection" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="key" class="form-label">Project Key</label>
                                    <input name="key" id="key" class="form-control form-control-sm" placeholder="PDD" required />
                                </div>
                                <div class="col-12">
                                    <label for="deskripsi" class="form-label">Description</label>
                                    <textarea name="deskripsi" class="form-control form-control-sm" rows="3" placeholder="Project description" required></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Project Details -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 text-primary">Project Details</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="id_lead" class="form-label">Project Lead</label>
                                    <select name="id_lead" class="form-select form-select-sm" required>
                                        <option value="">Select Lead</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="id_aplikasi" class="form-label">Application</label>
                                    <select name="id_aplikasi" class="form-select form-select-sm" required>
                                        <option value="">Select Application</option>
                                        @foreach($applications as $app)
                                            <option value="{{ $app->id }}">{{ $app->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule & Type -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 text-primary">Schedule & Type</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="waktu_mulai" class="form-label">Start Date</label>
                                    <input id="waktu_mulai" type="date" name="waktu_mulai" class="form-control form-control-sm" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="waktu_selesai" class="form-label">End Date</label>
                                    <input id="waktu_selesai" type="date" name="waktu_selesai" class="form-control form-control-sm" required />
                                </div>
                                <div class="col-12">
                                    <label for="tipe" class="form-label">Project Type</label>
                                    <select name="tipe" class="form-select form-select-sm" required>
                                        <option value="">Select Type</option>
                                        <option value="Software">Software</option>
                                        <option value="Hardware">Hardware</option>
                                        <option value="Network">Network</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="submit" id="submitBtn" class="btn btn-primary px-4 rounded-pill">Save Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Global Dropdown for Project Actions -->
    <div id="global-dropdown" 
        style="position: fixed; display: none;  padding: 4px; min-width: 100px;">
    </div>


@endsection

@push('scripts')
<script>

    // Confirm delete function
    let projectIdToDelete = null;
    function confirmDelete(projectId, projectName) {
        projectIdToDelete = projectId;
        document.getElementById('delete-project-name').innerText = projectName;

        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }

    // Submit delete form
    function submitDelete() {
        if (projectIdToDelete !== null) {
            const form = document.getElementById(`delete-form-${projectIdToDelete}`);
            if (form) form.submit();
        }
    }

    // Handle global dropdown for project actions
    document.addEventListener('DOMContentLoaded', function () {
        const globalDropdown = document.getElementById('global-dropdown');
        let activeDropdown = null;

        document.querySelectorAll('[x-data]').forEach(container => {
            const button = container.querySelector('button');
            const dropdown = container.querySelector('div[x-show]');

            if (!button || !dropdown) return;

            button.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Close previous dropdown if open
                if (activeDropdown && activeDropdown !== dropdown) {
                    activeDropdown.style.display = 'none';
                }

                // Move dropdown to global container
                globalDropdown.innerHTML = '';
                globalDropdown.appendChild(dropdown);
                dropdown.style.display = 'block';
                activeDropdown = dropdown;

                // Position the dropdown
                const rect = button.getBoundingClientRect();
                globalDropdown.style.display = 'block';
                globalDropdown.style.top = rect.bottom + 'px';
                globalDropdown.style.left = (rect.left - 80) + 'px';
            });

            dropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        });

        // Close dropdown on outside click
        document.addEventListener('click', function () {
            globalDropdown.style.display = 'none';
            if (activeDropdown) {
                activeDropdown.style.display = 'none';
                document.body.appendChild(activeDropdown);
                activeDropdown = null;
            }
        });

        // Close dropdown on scroll
        window.addEventListener('scroll', function () {
            globalDropdown.style.display = 'none';
            if (activeDropdown) {
                activeDropdown.style.display = 'none';
                document.body.appendChild(activeDropdown);
                activeDropdown = null;
            }
        }, true); // Use capture phase to catch scroll from nested containers
    });

    // Handle form submission click
    const submitBtn = document.getElementById('submitBtn');
    const form = submitBtn.closest('form');
    form.addEventListener('submit', function (e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Saving...';
    });

    // Handle delete button click
    const deleteBtn = document.getElementById('btnDelete');
    deleteBtn.addEventListener('click', function () {
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = 'Deleting...';
    });

    // Setup date constraints for start and end dates
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
        const modal = document.getElementById('projectModal');
        if (modal) {
            modal.addEventListener('shown.bs.modal', function () {
                setupDateConstraints();
            });
        }
    });

    // Sortable table headers
    const currentSort = new URLSearchParams(window.location.search).get('sort') || '';
    const [currentKey, currentDirection] = currentSort.split(':');
    let editState = false;
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

    // Open modal for creating or editing projects
    function openModal(id, nama, key, deskripsi, id_lead, id_aplikasi, waktu_mulai, waktu_selesai, tipe) {
        const modal = new bootstrap.Modal(document.getElementById('projectModal'), {
            backdrop: 'static',
            keyboard: false
        });
        const form = document.getElementById('projectForm');

        if (!id) {
            document.querySelector('#projectModal .modal-title').innerText = "Create New Project";
            document.querySelector('#projectModal button[type="submit"]').innerText = "Save Project";
            document.getElementById('form-method').value = 'POST';
            form.action = "/projects";
            form.reset();
        } else {
            form.action = `/projects/${id}`;
            document.getElementById('form-method').value = 'PUT';

            document.querySelector('input[name="nama"]').value = nama;
            document.querySelector('input[name="key"]').value = key;
            document.querySelector('textarea[name="deskripsi"]').value = deskripsi;
            document.querySelector('select[name="id_lead"]').value = id_lead;
            document.querySelector('select[name="id_aplikasi"]').value = id_aplikasi;
            document.querySelector('input[name="waktu_mulai"]').value = waktu_mulai;
            document.querySelector('input[name="waktu_selesai"]').value = waktu_selesai;
            document.querySelector('select[name="tipe"]').value = tipe;

            document.querySelector('#projectModal .modal-title').innerText = "Edit Project";
            document.querySelector('#projectModal button[type="submit"]').innerText = "Update Project";
        }

        modal.show();
    }

    // Set type filter and submit form
    function setTypeFilter(value, label) {
        document.getElementById('selectedTypeInput').value = value;
        document.getElementById('typeLabel').textContent = label;
        document.querySelector('form').submit();
    }

    // Handle star cell click to toggle starred state
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
                    tbody.appendChild(row);
                } else {
                    cell.textContent = "★";
                    tbody.prepend(row);
                }
            }
        });
    });



</script>
@endpush
