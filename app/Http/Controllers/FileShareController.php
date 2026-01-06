<?php

namespace App\Http\Controllers;

use App\Models\FileShare;
use App\Models\UploadedFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FileShareController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'file_ids' => ['required', 'array', 'min:1'],
            'file_ids.*' => ['required', 'integer', 'exists:uploaded_files,id'],
        ]);

        $share = FileShare::create([
            'created_by' => $request->user()->id,
        ]);

        $share->files()->attach($request->input('file_ids'));

        $qrCode = QrCode::format('svg')
                        ->size(300)
                        ->errorCorrection('H')
                        ->generate($share->public_url);

        return response()->json([
            'success' => true,
            'share_url' => $share->public_url,
            'qr_code' => base64_encode($qrCode),
            'token' => $share->token,
        ]);
    }

    public function show(string $token): View
    {
        $share = FileShare::where('token', $token)
                          ->with('files')
                          ->firstOrFail();

        if ($share->isExpired()) {
            abort(410, 'This share link has expired.');
        }

        return view('share.show', compact('share'));
    }

    public function download(string $token, UploadedFile $file)
    {
        $share = FileShare::where('token', $token)->firstOrFail();

        if ($share->isExpired()) {
            abort(410, 'This share link has expired.');
        }

        if (!$share->files()->where('uploaded_file_id', $file->id)->exists()) {
            abort(404);
        }

        $path = Storage::disk('local')->path($file->storage_path);

        return response()->download(
            $path,
            $file->original_filename,
            ['Content-Type' => $file->mime_type]
        );
    }
}
