<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUploadedFileRequest;
use App\Http\Requests\UpdateUploadedFileRequest;
use App\Models\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UploadedFileController extends Controller
{
    public function index(): View
    {
        $files = UploadedFile::with('uploader')
                             ->latest()
                             ->paginate(15);

        return view('files.index', compact('files'));
    }

    public function create(): View
    {
        return view('files.create');
    }

    public function store(StoreUploadedFileRequest $request): RedirectResponse
    {
        $file = $request->file('file');

        $path = $file->store('uploads', 'local');

        $uploadedFile = UploadedFile::create([
            'name' => $request->validated('name'),
            'original_filename' => $file->getClientOriginalName(),
            'storage_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
            'uploaded_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('files.show', $uploadedFile)
            ->with('status', 'file-uploaded');
    }

    public function show(UploadedFile $file): View
    {
        return view('files.show', compact('file'));
    }

    public function edit(UploadedFile $file): View
    {
        return view('files.edit', compact('file'));
    }

    public function update(UpdateUploadedFileRequest $request, UploadedFile $file): RedirectResponse
    {
        $file->update($request->validated());

        return redirect()
            ->route('files.show', $file)
            ->with('status', 'file-updated');
    }

    public function destroy(UploadedFile $file): RedirectResponse
    {
        Storage::disk('local')->delete($file->storage_path);

        $file->delete();

        return redirect()
            ->route('files.index')
            ->with('status', 'file-deleted');
    }

    public function download(UploadedFile $file)
    {
        $path = Storage::disk('local')->path($file->storage_path);

        return response()->download(
            $path,
            $file->original_filename,
            ['Content-Type' => $file->mime_type]
        );
    }
}
