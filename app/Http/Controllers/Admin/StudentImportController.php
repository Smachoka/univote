<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Models\ImportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class StudentImportController extends Controller
{
    public function index()
    {
        $logs = ImportLog::where('admin_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('admin.students.import', compact('logs'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,xlsx,xls', 'max:20480'],
        ]);

        $path = $request->file('file')->store('imports/temp', 'local');

        $rows    = Excel::toArray(new HeadingRowImport, Storage::disk('local')->path($path));
        $preview = collect($rows[0] ?? [])->take(10);

        session([
            'import_temp_path' => $path,
            'import_filename'  => $request->file('file')->getClientOriginalName(),
        ]);

        return response()->json([
            'preview'  => $preview,
            'filename' => $request->file('file')->getClientOriginalName(),
            'path'     => $path,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'temp_path' => ['required', 'string'],
            'filename'  => ['required', 'string'],
        ]);

        $tempPath = $request->input('temp_path');
        $filename = $request->input('filename');

        if (session('import_temp_path') !== $tempPath) {
            return back()->withErrors(['file' => 'Invalid import session. Please re-upload.']);
        }

        // Count rows
        $rows  = Excel::toArray(new HeadingRowImport, Storage::disk('local')->path($tempPath));
        $total = max(0, count($rows[0] ?? []) - 1);

        // Create log
        $log = ImportLog::create([
            'admin_id'   => auth()->id(),
            'filename'   => $filename,
            'status'     => 'processing',
            'total_rows' => $total,
            'started_at' => now(),
        ]);

        session()->forget(['import_temp_path', 'import_filename']);

        // Run import synchronously (chunked internally for memory safety)
        Excel::import(
            new StudentsImport($log->id),
            Storage::disk('local')->path($tempPath)
        );

        // Mark completed
        $log->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()
            ->route('admin.students.import.index')
            ->with('success', "Import complete! {$log->success_count} students imported, {$log->failure_count} failed.");
    }

    public function status(ImportLog $log)
    {
        abort_unless($log->admin_id === auth()->id(), 403);

        return response()->json([
            'status'        => $log->status,
            'total_rows'    => $log->total_rows,
            'success_count' => $log->success_count,
            'failure_count' => $log->failure_count,
            'progress'      => $log->total_rows > 0
                ? round((($log->success_count + $log->failure_count) / $log->total_rows) * 100)
                : 0,
            'completed_at'  => $log->completed_at?->format('M d, Y H:i'),
        ]);
    }

    public function downloadErrors(ImportLog $log)
    {
        abort_unless($log->admin_id === auth()->id(), 403);
        abort_unless($log->error_file && Storage::disk('local')->exists($log->error_file), 404);

        return Storage::disk('local')->download(
            $log->error_file,
            "import_{$log->id}_errors.csv"
        );
    }

    /**
     * Delete an import log and its associated error file from storage.
     */
    public function destroy(ImportLog $log)
    {
        abort_unless($log->admin_id === auth()->id(), 403);

        // Delete error CSV from storage if it exists
        if ($log->error_file && Storage::disk('local')->exists($log->error_file)) {
            Storage::disk('local')->delete($log->error_file);
        }

        $filename = $log->filename;
        $log->delete();

        return back()->with('success', "Import log \"{$filename}\" deleted successfully.");
    }
}