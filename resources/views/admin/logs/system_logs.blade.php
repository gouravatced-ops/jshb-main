<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>System Logs Console | JSHB Super Admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('panel.faviconIcon', 'images/favicon.ico')) }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font/font.css') }}">

    <style>
        :root {
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --bg-hover: #334155;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --accent-primary: #10b981;
            --accent-info: #38bdf8;
            --accent-warning: #f59e0b;
            --accent-error: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Top Bar */
        .console-topbar {
            height: 60px;
            background-color: #0b1120;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            flex-shrink: 0;
            z-index: 100;
        }

        .console-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
        }

        .console-brand i {
            color: var(--accent-primary);
            font-size: 20px;
        }

        .console-brand .badge-admin {
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 999px;
            background: #4338ca;
            color: #e0e7ff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Layout Grid */
        .console-body {
            display: flex;
            flex: 1;
            height: calc(100vh - 60px);
            overflow: hidden;
        }

        /* Sidebar: Files & Dates */
        .console-sidebar {
            width: 320px;
            background-color: var(--bg-card);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            overflow: hidden;
        }

        .sidebar-section {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .date-picker-wrap select {
            background-color: #0f172a;
            color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 8px 12px;
            width: 100%;
            font-size: 13px;
            outline: none;
        }

        .sidebar-files-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px 0;
        }

        .log-file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            border-left: 3px solid transparent;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .log-file-item:hover {
            background-color: var(--bg-hover);
            color: #fff;
        }

        .log-file-item.active {
            background-color: #1e3a5f;
            color: #38bdf8;
            border-left-color: #38bdf8;
            font-weight: 600;
        }

        .file-cat-badge {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            background: #334155;
            color: #cbd5e1;
            font-weight: 600;
        }

        /* Main Log Content Panel */
        .console-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #0b1120;
            overflow: hidden;
        }

        .main-toolbar {
            padding: 12px 20px;
            background-color: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            flex-shrink: 0;
        }

        .file-meta-info {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 13px;
        }

        .file-meta-info strong {
            color: #fff;
            font-size: 15px;
        }

        .meta-pill {
            background: #0f172a;
            border: 1px solid var(--border-color);
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .filter-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-input-wrap {
            position: relative;
        }

        .search-input-wrap i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 12px;
        }

        .search-input-wrap input {
            background: #0f172a;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 6px 12px 6px 30px;
            font-size: 12px;
            color: #fff;
            outline: none;
            width: 200px;
        }

        .search-input-wrap input:focus {
            border-color: var(--accent-info);
            width: 250px;
            transition: width 0.2s ease;
        }

        /* Log Content Area */
        .log-entries-wrap {
            flex: 1;
            overflow-y: auto;
            padding: 16px 20px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12.5px;
            line-height: 1.6;
        }

        .log-entry-card {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 10px;
            transition: border-color 0.15s ease;
        }

        .log-entry-card:hover {
            border-color: #374151;
        }

        .log-entry-card.level-error {
            border-left: 4px solid var(--accent-error);
            background: #180d11;
        }

        .log-entry-card.level-warning {
            border-left: 4px solid var(--accent-warning);
            background: #1c1507;
        }

        .log-entry-card.level-info {
            border-left: 4px solid var(--accent-info);
        }

        .log-entry-card.level-debug {
            border-left: 4px solid #a855f7;
        }

        .log-entry-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .badge-level {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-level.badge-error { background: #dc2626; color: #fff; }
        .badge-level.badge-warning { background: #d97706; color: #fff; }
        .badge-level.badge-info { background: #0284c7; color: #fff; }
        .badge-level.badge-debug { background: #7c3aed; color: #fff; }
        .badge-level.badge-secondary { background: #475569; color: #fff; }

        .log-timestamp {
            color: var(--text-muted);
            font-size: 11px;
        }

        .log-msg-text {
            color: #e2e8f0;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* Raw Text Mode */
        .raw-text-viewer {
            white-space: pre-wrap;
            word-break: break-word;
            color: #a5f3fc;
            line-height: 1.5;
            font-size: 12px;
            display: none;
        }

        /* Buttons */
        .btn-console {
            background: #0f172a;
            border: 1px solid var(--border-color);
            color: #cbd5e1;
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-console:hover {
            background: var(--bg-hover);
            color: #fff;
            border-color: #475569;
        }

        .btn-console.btn-danger-console:hover {
            background: #7f1d1d;
            border-color: #991b1b;
            color: #fecaca;
        }

        /* Scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>
<body>

    <!-- Top Bar -->
    <header class="console-topbar">
        <a href="{{ route('admin.dashboard') }}" class="console-brand" title="Back to Admin Dashboard">
            <i class="fa-solid fa-server"></i>
            <span>JSHB System Logs Console</span>
            <span class="badge-admin"><i class="fa-solid fa-shield-halved me-1"></i> Super Admin</span>
        </a>

        <div class="topbar-actions">
            <!-- Auto Refresh Toggle -->
            <button type="button" class="btn-console" id="autoRefreshBtn" onclick="toggleAutoRefresh()">
                <i class="fa-solid fa-arrows-rotate" id="autoRefreshIcon"></i>
                <span id="autoRefreshText">Live Polling: Off</span>
            </button>

            <!-- Manual Refresh -->
            <button type="button" class="btn-console" onclick="refreshCurrentLog()" title="Refresh Log File">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>

            <!-- Download -->
            <a href="{{ route('admin.system-logs.download', ['date' => $selectedDate, 'file' => $selectedFile]) }}" 
               class="btn-console" id="downloadBtn" title="Download Log File">
                <i class="fa-solid fa-download"></i> Download
            </a>

            <!-- Clear Log -->
            <button type="button" class="btn-console btn-danger-console" onclick="clearCurrentLog()" title="Truncate Log File">
                <i class="fa-solid fa-trash"></i> Clear
            </button>

            <a href="{{ route('admin.dashboard') }}" class="btn-console" title="Exit to Main Dashboard">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Exit
            </a>
        </div>
    </header>

    <!-- Main Console Body -->
    <div class="console-body">
        
        <!-- Sidebar: Date Picker & Files List -->
        <aside class="console-sidebar">
            <div class="sidebar-section">
                <label class="text-muted small fw-bold mb-2 d-block text-uppercase" style="font-size:11px; letter-spacing:0.5px;">
                    <i class="fa-solid fa-calendar-days text-primary me-1"></i> Date Folder (storage/logs/):
                </label>
                <div class="date-picker-wrap">
                    <select id="dateSelect" onchange="changeDate(this.value)">
                        @foreach($dates as $d)
                            <option value="{{ $d }}" {{ $d === $selectedDate ? 'selected' : '' }}>
                                @if($d === 'root')
                                    📁 Root / Legacy Logs (/storage/logs)
                                @elseif($d === date('Y-m-d'))
                                    📅 Today ({{ $d }})
                                @else
                                    📅 {{ $d }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Log Files in selected date -->
            <div class="sidebar-files-list" id="filesList">
                <div class="px-3 py-2 text-muted small fw-bold text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">
                    Available Logs ({{ count($files) }})
                </div>

                @forelse($files as $f)
                    <div class="log-file-item {{ $f['name'] === $selectedFile ? 'active' : '' }}" 
                         onclick="selectFile('{{ $f['name'] }}')" id="file-item-{{ str_replace('.', '_', $f['name']) }}">
                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                            <i class="fa-regular fa-file-lines text-muted"></i>
                            <span class="text-truncate" style="max-width: 170px;">{{ $f['name'] }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="file-cat-badge">{{ $f['category'] }}</span>
                            <small class="text-muted" style="font-size:11px;">{{ $f['size_human'] }}</small>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-center text-muted small">
                        <i class="fa-regular fa-folder-open mb-2" style="font-size: 20px; opacity: 0.5;"></i>
                        <div>No log files found in this date folder.</div>
                    </div>
                @endforelse
            </div>
        </aside>

        <!-- Main Content -->
        <main class="console-main">
            <!-- Toolbar -->
            <div class="main-toolbar">
                <div class="file-meta-info">
                    <strong id="activeFileName">{{ $selectedFile ?: 'No file selected' }}</strong>
                    <span class="meta-pill" id="activeFileSize"><i class="fa-solid fa-database me-1"></i> {{ $logData['size_human'] ?? '0 B' }}</span>
                    <span class="meta-pill" id="activeFileDate"><i class="fa-regular fa-calendar me-1"></i> {{ $selectedDate }}</span>
                    <span class="meta-pill" id="activeEntriesCount"><i class="fa-solid fa-list-ol me-1"></i> {{ $logData['total_entries'] ?? 0 }} entries</span>
                </div>

                <div class="filter-controls">
                    <!-- Search Filter -->
                    <div class="search-input-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="logSearch" placeholder="Search logs..." oninput="filterEntries()">
                    </div>

                    <!-- Level Filter Dropdown -->
                    <select class="btn-console" id="levelFilter" onchange="filterEntries()" style="outline:none;">
                        <option value="ALL">All Levels</option>
                        <option value="ERROR">Errors Only</option>
                        <option value="WARNING">Warnings Only</option>
                        <option value="INFO">Info Only</option>
                        <option value="DEBUG">Debug Only</option>
                    </select>

                    <!-- View Toggle -->
                    <button type="button" class="btn-console" id="viewToggleBtn" onclick="toggleViewMode()">
                        <i class="fa-solid fa-code"></i> <span id="viewToggleText">Raw View</span>
                    </button>
                </div>
            </div>

            <!-- Log Entries List -->
            <div class="log-entries-wrap" id="logEntriesContainer">
                <div id="parsedEntriesView">
                    @if($logData && count($logData['entries']) > 0)
                        @foreach($logData['entries'] as $entry)
                            <div class="log-entry-card level-{{ strtolower($entry['level']) }}" data-level="{{ $entry['level'] }}">
                                <div class="log-entry-header">
                                    <span class="badge-level {{ $entry['level_class'] }}">{{ $entry['level'] }}</span>
                                    <span class="log-timestamp"><i class="fa-regular fa-clock me-1"></i> {{ $entry['timestamp'] }}</span>
                                </div>
                                <div class="log-msg-text">{{ $entry['message'] }}</div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fa-regular fa-circle-check text-success mb-3" style="font-size: 32px; opacity: 0.7;"></i>
                            <div class="h6 text-light mb-1">No Log Entries Found</div>
                            <div class="small">This log file is currently empty or has no recent activity recorded.</div>
                        </div>
                    @endif
                </div>

                <!-- Raw Monospace View -->
                <pre class="raw-text-viewer" id="rawTextView">{{ $logData['raw_text'] ?? 'No content.' }}</pre>
            </div>
        </main>
    </div>

    <script>
        let currentDate = "{{ $selectedDate }}";
        let currentFile = "{{ $selectedFile }}";
        let rawMode = false;
        let autoRefreshInterval = null;

        function changeDate(date) {
            window.location.href = "{{ route('admin.system-logs') }}?date=" + date;
        }

        function selectFile(filename) {
            currentFile = filename;
            document.querySelectorAll('.log-file-item').forEach(el => el.classList.remove('active'));
            const activeEl = document.getElementById('file-item-' + filename.replace(/\./g, '_'));
            if (activeEl) activeEl.classList.add('active');

            // Update download button
            const downloadBtn = document.getElementById('downloadBtn');
            if (downloadBtn) {
                downloadBtn.href = "{{ route('admin.system-logs.download') }}?date=" + currentDate + "&file=" + filename;
            }

            refreshCurrentLog();
        }

        function refreshCurrentLog() {
            if (!currentFile) return;

            fetch("{{ route('admin.system-logs.content') }}?date=" + currentDate + "&file=" + currentFile, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    renderLogData(res.data);
                }
            })
            .catch(err => console.error('Error refreshing log:', err));
        }

        function renderLogData(data) {
            document.getElementById('activeFileName').innerText = data.file_name;
            document.getElementById('activeFileSize').innerHTML = '<i class="fa-solid fa-database me-1"></i> ' + data.size_human;
            document.getElementById('activeEntriesCount').innerHTML = '<i class="fa-solid fa-list-ol me-1"></i> ' + data.total_entries + ' entries';
            document.getElementById('rawTextView').innerText = data.raw_text || 'No content.';

            const container = document.getElementById('parsedEntriesView');
            if (data.entries && data.entries.length > 0) {
                let html = '';
                data.entries.forEach(entry => {
                    html += `
                        <div class="log-entry-card level-${entry.level.toLowerCase()}" data-level="${entry.level}">
                            <div class="log-entry-header">
                                <span class="badge-level ${entry.level_class}">${entry.level}</span>
                                <span class="log-timestamp"><i class="fa-regular fa-clock me-1"></i> ${entry.timestamp}</span>
                            </div>
                            <div class="log-msg-text">${escapeHtml(entry.message)}</div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="fa-regular fa-circle-check text-success mb-3" style="font-size: 32px; opacity: 0.7;"></i>
                        <div class="h6 text-light mb-1">No Log Entries Found</div>
                        <div class="small">This log file is currently empty or has no recent activity recorded.</div>
                    </div>
                `;
            }

            filterEntries();
        }

        function filterEntries() {
            const query = (document.getElementById('logSearch').value || '').toLowerCase();
            const level = document.getElementById('levelFilter').value;

            document.querySelectorAll('.log-entry-card').forEach(card => {
                const cardLevel = (card.getAttribute('data-level') || '').toUpperCase();
                const cardText = card.innerText.toLowerCase();

                let matchLevel = (level === 'ALL') || (cardLevel === level) || (level === 'ERROR' && ['EMERGENCY','ALERT','CRITICAL','ERROR','FAILED'].includes(cardLevel));
                let matchQuery = query === '' || cardText.includes(query);

                card.style.display = (matchLevel && matchQuery) ? 'block' : 'none';
            });
        }

        function toggleViewMode() {
            rawMode = !rawMode;
            const parsed = document.getElementById('parsedEntriesView');
            const raw = document.getElementById('rawTextView');
            const text = document.getElementById('viewToggleText');

            if (rawMode) {
                parsed.style.display = 'none';
                raw.style.display = 'block';
                text.innerText = 'Card View';
            } else {
                parsed.style.display = 'block';
                raw.style.display = 'none';
                text.innerText = 'Raw View';
            }
        }

        function toggleAutoRefresh() {
            const btn = document.getElementById('autoRefreshBtn');
            const icon = document.getElementById('autoRefreshIcon');
            const text = document.getElementById('autoRefreshText');

            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
                btn.classList.remove('btn-success');
                icon.classList.remove('fa-spin');
                text.innerText = 'Live Polling: Off';
            } else {
                autoRefreshInterval = setInterval(refreshCurrentLog, 4000);
                btn.classList.add('btn-success');
                icon.classList.add('fa-spin');
                text.innerText = 'Live Polling: 4s';
            }
        }

        function clearCurrentLog() {
            if (!confirm(`Are you sure you want to completely clear/truncate '${currentFile}'?`)) {
                return;
            }

            fetch("{{ route('admin.system-logs.clear') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    date: currentDate,
                    file: currentFile
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    refreshCurrentLog();
                } else {
                    alert(data.message || 'Error clearing log');
                }
            })
            .catch(err => console.error(err));
        }

        function escapeHtml(str) {
            if (!str) return '';
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return str.replace(/[&<>"']/g, m => map[m]);
        }
    </script>
</body>
</html>
