<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Task;

use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required|string|max:5000',
        ]);

        $attachment = new Attachment();
        $attachment->task_id = $task->id;
        $attachment->user_id = $request->input('user_id');
        $attachment->comment = $request->comment;
        $attachment->save();

        return back()->with('success', 'Comment added.');
    }
}
