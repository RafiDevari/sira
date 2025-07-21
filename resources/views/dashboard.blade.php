<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Applications</h1>
    <ul>
        @foreach($applications as $app)
            <li>{{ $app->name }}</li>
        @endforeach
    </ul>

    <h1>Projects</h1>
    <ul>
        @foreach($projects as $project)
            <li>
                <strong>{{ $project->nama }}</strong> ({{ $project->tipe }})<br>
                Lead: {{ $project->lead->name ?? 'N/A' }}<br>
                Application: {{ $project->application->name ?? 'N/A' }}<br>
                Start: {{ $project->waktu_mulai }} — End: {{ $project->waktu_selesai }}<br>
                <em>{{ $project->deskripsi }}</em>
            </li>
        @endforeach
    </ul>

    <h1>Sprints</h1>
    <ul>
        @foreach($sprints as $sprint)
            <li>
                {{ $sprint->nama }} ({{ $sprint->status }})<br>
                Project: {{ $sprint->project->nama ?? 'N/A' }}<br>
                Start: {{ $sprint->waktu_mulai }} — End: {{ $sprint->waktu_selesai }}
            </li>
        @endforeach
    </ul>
</body>
</html>
