<?php

namespace App\Imports;

use App\Mail\StudentCredentialsMail;
use App\Models\ImportLog;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class StudentsImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    WithChunkReading,
    WithBatchInserts
{
    use Importable, SkipsFailures;

    private array $errors = [];

    public function chunkSize(): int { return 500; }
    public function batchSize(): int { return 500; }

    public function __construct(private int $importLogId) {}

    public function collection(Collection $rows): void
    {
        $log          = ImportLog::find($this->importLogId);
        $successCount = 0;

        DB::transaction(function () use ($rows, &$successCount) {
            foreach ($rows as $row) {
                $plainPassword = Str::random(10);

                $student = User::create([
                    'name'       => trim($row['name']),
                    'email'      => strtolower(trim($row['email'])),
                    'student_id' => trim($row['student_id']),
                    'password'   => Hash::make($plainPassword),
                    'role'       => 'student',
                ]);

                Mail::to($student->email)->queue(
                    new StudentCredentialsMail(
                        studentName:   $student->name,
                        email:         $student->email,
                        plainPassword: $plainPassword,
                        studentId:     $student->student_id,
                    )
                );

                $successCount++;
            }
        });

        if ($log && $successCount > 0) {
            $log->increment('success_count', $successCount);
        }
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'student_id' => ['required', 'string', 'unique:users,student_id'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'name.required'       => 'Student name is required.',
            'email.required'      => 'Email address is required.',
            'email.email'         => 'Email must be a valid address.',
            'email.unique'        => 'This email already exists in the system.',
            'student_id.required' => 'Student ID is required.',
            'student_id.unique'   => 'This student ID already exists.',
        ];
    }

    public function onFailure(Failure ...$failures): void
    {
        $log = ImportLog::find($this->importLogId);

        foreach ($failures as $failure) {
            $this->errors[] = [
                'row'    => $failure->row(),
                'field'  => $failure->attribute() ?? '',
                'errors' => implode(' | ', $failure->errors()),
                'values' => implode(', ', array_values((array) $failure->values())),
            ];
        }

        if ($log) {
            $log->increment('failure_count', count($failures));
        }

        $this->writeErrorReport();
    }

    private function writeErrorReport(): void
    {
        if (empty($this->errors)) return;

        $filename = "import_errors/import_{$this->importLogId}_errors.csv";
        $log      = ImportLog::find($this->importLogId);

        $existing = Storage::disk('local')->exists($filename)
            ? Storage::disk('local')->get($filename)
            : "Row,Field,Errors,Values\n";

        $lines = $existing;
        foreach ($this->errors as $e) {
            $lines .= "\"{$e['row']}\",\"{$e['field']}\",\"{$e['errors']}\",\"{$e['values']}\"\n";
        }

        Storage::disk('local')->put($filename, $lines);

        if ($log && !$log->error_file) {
            $log->update(['error_file' => $filename]);
        }

        $this->errors = [];
    }
}