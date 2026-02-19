@extends('layouts.app') {{-- adjust to your admin layout --}}

@section('title', 'Import Students')

@section('content')
<style>
.import-wrap { max-width: 1100px; margin: 0 auto; padding: 32px 24px; }
.page-head { margin-bottom: 28px; }
.page-head h1 { font-size: 1.5rem; font-weight: 800; color: #111; margin-bottom: 4px; }
.page-head p  { font-size: 0.88rem; color: #666; }

/* Upload card */
.card { background: white; border-radius: 10px; border: 1px solid #e8e8e8; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 28px; }
.card-head { padding: 18px 24px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; gap: 10px; }
.card-head h2 { font-size: 1rem; font-weight: 700; color: #111; margin: 0; }
.card-body { padding: 24px; }

/* Drop zone */
.drop-zone {
    border: 2px dashed #d1d5db; border-radius: 8px;
    padding: 48px 24px; text-align: center;
    cursor: pointer; transition: all 0.2s;
    background: #fafafa;
}
.drop-zone:hover, .drop-zone.drag-over { border-color: #d42b2b; background: #fff5f5; }
.drop-zone i { font-size: 2.5rem; color: #ccc; margin-bottom: 12px; display: block; }
.drop-zone p { font-size: 0.9rem; color: #666; margin: 0; }
.drop-zone span { font-size: 0.78rem; color: #999; margin-top: 6px; display: block; }
#fileInput { display: none; }

/* Preview table */
.preview-section { display: none; margin-top: 20px; }
.preview-section.show { display: block; }
.preview-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
.preview-table th { background: #f8f8f8; padding: 10px 12px; text-align: left; font-weight: 600; color: #555; border-bottom: 2px solid #e8e8e8; }
.preview-table td { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; color: #333; }
.preview-table tr:last-child td { border-bottom: none; }
.preview-badge { display: inline-block; background: #f0fdf4; color: #166534; font-size: 0.7rem; font-weight: 600; padding: 2px 8px; border-radius: 50px; }

/* Buttons */
.btn-red { background: #d42b2b; color: white; padding: 10px 24px; border-radius: 6px; font-size: 0.88rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 7px; }
.btn-red:hover { background: #a81f1f; }
.btn-red:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-outline { background: white; color: #555; padding: 10px 20px; border-radius: 6px; font-size: 0.88rem; font-weight: 500; border: 1px solid #d1d5db; cursor: pointer; transition: all 0.2s; }
.btn-outline:hover { border-color: #999; color: #111; }

/* Progress */
.progress-wrap { display: none; margin-top: 16px; }
.progress-wrap.show { display: block; }
.progress-bar-outer { background: #f0f0f0; border-radius: 50px; height: 8px; overflow: hidden; }
.progress-bar-inner { height: 100%; background: #d42b2b; border-radius: 50px; transition: width 0.4s ease; width: 0%; }
.progress-label { font-size: 0.78rem; color: #666; margin-top: 6px; }

/* Template download */
.template-hint { display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #888; margin-top: 14px; }
.template-hint a { color: #d42b2b; text-decoration: none; font-weight: 600; }

/* Log table */
.log-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
.log-table th { padding: 11px 14px; text-align: left; font-weight: 600; color: #666; background: #f8f8f8; border-bottom: 2px solid #e8e8e8; }
.log-table td { padding: 11px 14px; border-bottom: 1px solid #f5f5f5; color: #333; vertical-align: middle; }
.log-table tr:hover td { background: #fafafa; }
.badge { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
.badge-success  { background: #f0fdf4; color: #166534; }
.badge-warning  { background: #fffbeb; color: #92400e; }
.badge-danger   { background: #fef2f2; color: #991b1b; }
.badge-secondary{ background: #f3f4f6; color: #6b7280; }
.progress-mini { width: 80px; background: #f0f0f0; border-radius: 50px; height: 5px; display: inline-block; vertical-align: middle; margin-right: 6px; overflow: hidden; }
.progress-mini-inner { height: 100%; background: #d42b2b; border-radius: 50px; }
.dl-btn { font-size: 0.75rem; color: #d42b2b; text-decoration: none; font-weight: 600; }
.dl-btn:hover { text-decoration: underline; }

/* Alert */
.alert { padding: 12px 16px; border-radius: 7px; font-size: 0.88rem; margin-bottom: 20px; }
.alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
</style>

<div class="import-wrap">

    <div class="page-head">
        <h1><i class="fa-solid fa-file-import" style="color:#d42b2b;margin-right:8px;"></i>Import Students</h1>
        <p>Upload a CSV or XLSX file to bulk-import students. Credentials are emailed automatically.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}</div>
    @endif

    {{-- ── UPLOAD CARD ── --}}
    <div class="card">
        <div class="card-head">
            <i class="fa-solid fa-upload" style="color:#d42b2b;"></i>
            <h2>Upload File</h2>
        </div>
        <div class="card-body">

            {{-- Drop zone --}}
            <div class="drop-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <p>Drag & drop your CSV or XLSX file here, or <strong style="color:#d42b2b;">click to browse</strong></p>
                <span>Supported formats: .csv, .xlsx, .xls · Max 20MB</span>
            </div>
            <input type="file" id="fileInput" accept=".csv,.xlsx,.xls">

            <div class="template-hint">
                <i class="fa-solid fa-circle-info"></i>
                Required columns: <code>name</code>, <code>email</code>, <code>student_id</code>
                &nbsp;·&nbsp;
                <a href="{{ route('admin.students.import.template') }}">Download CSV template</a>
            </div>

            {{-- Preview section --}}
            <div class="preview-section" id="previewSection">
                <div style="display:flex;align-items:center;justify-content:space-between;margin:20px 0 12px;">
                    <div>
                        <strong id="previewFilename" style="font-size:0.9rem;color:#111;"></strong>
                        <span class="preview-badge" id="previewCount"></span>
                    </div>
                    <button class="btn-outline" onclick="resetUpload()">
                        <i class="fa-solid fa-rotate-left"></i> Change File
                    </button>
                </div>

                <div style="overflow-x:auto;border:1px solid #e8e8e8;border-radius:8px;">
                    <table class="preview-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Student ID</th>
                            </tr>
                        </thead>
                        <tbody id="previewBody"></tbody>
                    </table>
                </div>
                <p style="font-size:0.75rem;color:#999;margin-top:8px;">Showing first 10 rows only.</p>

                {{-- Confirm form --}}
                <form method="POST" action="{{ route('admin.students.import.store') }}" id="confirmForm">
                    @csrf
                    <input type="hidden" name="temp_path" id="tempPath">
                    <input type="hidden" name="filename"  id="importFilename">
                    <div style="margin-top:18px;display:flex;gap:10px;align-items:center;">
                        <button type="submit" class="btn-red" id="confirmBtn">
                            <i class="fa-solid fa-play"></i> Start Import
                        </button>
                        <span style="font-size:0.78rem;color:#999;">Import will run in the background. You can leave this page.</span>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- ── IMPORT LOGS ── --}}
    <div class="card">
        <div class="card-head">
            <i class="fa-solid fa-clock-rotate-left" style="color:#d42b2b;"></i>
            <h2>Import History</h2>
        </div>
        <div class="card-body" style="padding:0;">
            @if($logs->isEmpty())
                <div style="text-align:center;padding:40px;color:#bbb;">
                    <i class="fa-solid fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
                    No imports yet.
                </div>
            @else
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Success</th>
                            <th>Failed</th>
                            <th>Progress</th>
                            <th>Date</th>
                            <th>Errors</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr data-log-id="{{ $log->id }}" data-status="{{ $log->status }}" id="log-row-{{ $log->id }}">
                            <td>{{ $log->id }}</td>
                            <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ $log->filename }}
                            </td>
                            <td>
                                <span class="badge {{ $log->statusBadgeClass() }}">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td>{{ number_format($log->total_rows) }}</td>
                            <td style="color:#166534;font-weight:600;">{{ number_format($log->success_count) }}</td>
                            <td style="color:#991b1b;font-weight:600;">{{ number_format($log->failure_count) }}</td>
                            <td>
                                @php
                                    $pct = $log->total_rows > 0
                                        ? round((($log->success_count + $log->failure_count) / $log->total_rows) * 100)
                                        : 0;
                                @endphp
                                <span class="progress-mini">
                                    <span class="progress-mini-inner" style="width:{{ $pct }}%"></span>
                                </span>
                                {{ $pct }}%
                            </td>
                            <td style="font-size:0.78rem;color:#888;">
                                {{ $log->created_at->format('M d, H:i') }}
                            </td>
                            <td>
                                @if($log->error_file)
                                    <a href="{{ route('admin.students.import.errors', $log) }}" class="dl-btn">
                                        <i class="fa-solid fa-download"></i> Download
                                    </a>
                                @else
                                    <span style="color:#ccc;font-size:0.75rem;">—</span>
                                @endif
                            </td>
                            <td>
                                <button onclick="confirmDelete({{ $log->id }}, '{{ addslashes($log->filename) }}')"
                                        style="background:none;border:none;cursor:pointer;color:#ccc;font-size:0.85rem;padding:4px 8px;border-radius:4px;transition:all 0.2s;"
                                        onmouseover="this.style.color='#991b1b';this.style.background='#fef2f2';"
                                        onmouseout="this.style.color='#ccc';this.style.background='none';"
                                        title="Delete this import log">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $log->id }}"
                                      method="POST"
                                      action="{{ route('admin.students.import.destroy', $log) }}"
                                      style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding:16px 24px;">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

<script>
const dropZone   = document.getElementById('dropZone');
const fileInput  = document.getElementById('fileInput');

// Drag & drop
['dragenter','dragover'].forEach(e => dropZone.addEventListener(e, ev => { ev.preventDefault(); dropZone.classList.add('drag-over'); }));
['dragleave','drop'].forEach(e => dropZone.addEventListener(e, ev => { ev.preventDefault(); dropZone.classList.remove('drag-over'); }));
dropZone.addEventListener('drop', ev => handleFile(ev.dataTransfer.files[0]));
fileInput.addEventListener('change', () => handleFile(fileInput.files[0]));

function handleFile(file) {
    if (!file) return;
    const allowed = ['text/csv','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel'];
    if (!allowed.includes(file.type) && !file.name.match(/\.(csv|xlsx|xls)$/i)) {
        alert('Please upload a CSV or XLSX file.');
        return;
    }

    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', '{{ csrf_token() }}');

    dropZone.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:2rem;color:#d42b2b;display:block;margin-bottom:12px;"></i><p>Reading file...</p>';

    fetch('{{ route('admin.students.import.preview') }}', { method:'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.errors) { alert(Object.values(data.errors).flat().join('\n')); resetUpload(); return; }
            showPreview(data);
        })
        .catch(() => { alert('Upload failed. Please try again.'); resetUpload(); });
}

function showPreview(data) {
    document.getElementById('previewFilename').textContent = data.filename;
    document.getElementById('previewCount').textContent    = data.preview.length + ' rows shown';
    document.getElementById('tempPath').value              = data.path;
    document.getElementById('importFilename').value        = data.filename;

    const tbody = document.getElementById('previewBody');
    tbody.innerHTML = '';
    data.preview.forEach((row, i) => {
        tbody.innerHTML += `<tr>
            <td style="color:#999;">${i + 1}</td>
            <td>${esc(row.name ?? '')}</td>
            <td>${esc(row.email ?? '')}</td>
            <td>${esc(row.student_id ?? '')}</td>
        </tr>`;
    });

    dropZone.style.display = 'none';
    document.getElementById('previewSection').classList.add('show');
}

function resetUpload() {
    dropZone.style.display = '';
    dropZone.innerHTML = `
        <i class="fa-solid fa-cloud-arrow-up"></i>
        <p>Drag & drop your CSV or XLSX file here, or <strong style="color:#d42b2b;">click to browse</strong></p>
        <span>Supported formats: .csv, .xlsx, .xls · Max 20MB</span>`;
    document.getElementById('previewSection').classList.remove('show');
    fileInput.value = '';
}

function esc(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// ── Delete confirmation ───────────────────────────────────────────────────────
function confirmDelete(logId, filename) {
    if (confirm('Delete import log "' + filename + '"?\n\nThis will remove the log record and any error report. It will NOT remove students that were already imported.')) {
        document.getElementById('delete-form-' + logId).submit();
    }
}

// ── Live progress polling for processing imports ──────────────────────────────
document.querySelectorAll('tr[data-status="processing"]').forEach(row => {
    const logId = row.dataset.logId;
    const poll  = setInterval(() => {
        fetch(`{{ url('admin/students/import') }}/${logId}/status`)
            .then(r => r.json())
            .then(data => {
                row.querySelector('.badge').textContent  = data.status;
                row.cells[4].textContent = data.success_count.toLocaleString();
                row.cells[5].textContent = data.failure_count.toLocaleString();
                row.cells[6].innerHTML   = `<span class="progress-mini"><span class="progress-mini-inner" style="width:${data.progress}%"></span></span>${data.progress}%`;
                if (data.status !== 'processing') clearInterval(poll);
            });
    }, 3000); // poll every 3 seconds
});
</script>
@endsection