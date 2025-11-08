<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CSV File Upload System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: white;
            margin-bottom: 2rem;
            text-align: center;
            font-size: 2.5rem;
        }

        .upload-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .drop-zone {
            border: 3px dashed #667eea;
            border-radius: 12px;
            padding: 3rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9ff;
        }

        .drop-zone.drag-over {
            border-color: #764ba2;
            background: #ede7f6;
            transform: scale(1.02);
        }

        .drop-zone-text {
            color: #667eea;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .drop-zone-subtext {
            color: #666;
            font-size: 0.9rem;
        }

        .upload-button {
            margin-top: 1rem;
            padding: 0.75rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .upload-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .upload-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .history-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .history-header {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .upload-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .upload-item {
            display: grid;
            grid-template-columns: 2fr 2fr 1fr;
            gap: 1rem;
            padding: 1.25rem;
            background: #f8f9ff;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .upload-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .upload-info {
            display: flex;
            flex-direction: column;
        }

        .upload-filename {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .upload-time {
            font-size: 0.875rem;
            color: #666;
        }

        .status {
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            text-align: center;
            display: inline-block;
            align-self: flex-start;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }

        .progress-info {
            font-size: 0.875rem;
            color: #666;
            margin-top: 0.5rem;
        }

        .error-message {
            font-size: 0.875rem;
            color: #721c24;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #f8d7da;
            border-radius: 4px;
        }

        #file-input {
            display: none;
        }

        .empty-state {
            text-align: center;
            color: #666;
            padding: 3rem;
        }

        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid #004085;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.6s linear infinite;
            margin-right: 0.5rem;
            vertical-align: middle;
        }

        @keyframes spinner {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>CSV File Upload System</h1>

        <!-- Upload Section -->
        <div class="upload-section">
            <div class="drop-zone" id="drop-zone">
                <div class="drop-zone-text">📁 Drop your CSV file here</div>
                <div class="drop-zone-subtext">or</div>
                <button class="upload-button" type="button" onclick="document.getElementById('file-input').click()">
                    Browse Files
                </button>
                <input type="file" id="file-input" accept=".csv" />
            </div>
        </div>

        <!-- History Section -->
        <div class="history-section">
            <h2 class="history-header">Upload History</h2>
            <div id="upload-list" class="upload-list">
                <div class="empty-state">No uploads yet. Upload a CSV file to get started!</div>
            </div>
        </div>
    </div>

    <script>
        // API endpoint
        const API_URL = '/file-uploads';
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        // Elements
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const uploadList = document.getElementById('upload-list');

        // Drag and drop handlers
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('drag-over');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFile(files[0]);
            }
        });

        // File input handler
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        // Upload file
        async function handleFile(file) {
            if (!file.name.endsWith('.csv')) {
                alert('Please select a CSV file');
                return;
            }

            const formData = new FormData();
            formData.append('file', file);

            try {
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                const result = await response.json();

                if (response.ok) {
                    fileInput.value = '';
                    fetchUploads(); // Refresh list
                } else {
                    alert('Upload failed: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Upload failed: ' + error.message);
            }
        }

        // Fetch all uploads
        async function fetchUploads() {
            try {
                const response = await fetch(API_URL, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                const result = await response.json();

                if (result.data && result.data.length > 0) {
                    renderUploads(result.data);
                } else {
                    uploadList.innerHTML = '<div class="empty-state">No uploads yet. Upload a CSV file to get started!</div>';
                }
            } catch (error) {
                console.error('Fetch error:', error);
            }
        }

        // Render uploads
        function renderUploads(uploads) {
            uploadList.innerHTML = uploads.map(upload => `
                <div class="upload-item">
                    <div class="upload-info">
                        <div class="upload-filename">${escapeHtml(upload.original_filename)}</div>
                        <div class="upload-time">${formatDate(upload.created_at)}</div>
                        ${upload.total_rows ? `<div class="progress-info">Processed: ${upload.processed_rows || 0} / ${upload.total_rows} rows</div>` : ''}
                        ${upload.error_message ? `<div class="error-message">${escapeHtml(upload.error_message)}</div>` : ''}
                    </div>
                    <div class="upload-info">
                        <div style="font-weight: 600; color: #333; margin-bottom: 0.25rem;">File Name</div>
                        <div style="font-size: 0.875rem; color: #666;">${escapeHtml(upload.original_filename)}</div>
                    </div>
                    <div class="upload-info">
                        <span class="status status-${upload.status}">
                            ${upload.status === 'processing' ? '<span class="spinner"></span>' : ''}
                            ${upload.status}
                        </span>
                    </div>
                </div>
            `).join('');
        }

        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);

            if (diffMins < 1) return 'Just now';
            if (diffMins < 60) return `${diffMins} minute${diffMins !== 1 ? 's' : ''} ago`;
            
            const diffHours = Math.floor(diffMins / 60);
            if (diffHours < 24) return `${diffHours} hour${diffHours !== 1 ? 's' : ''} ago`;
            
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        }

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Initial fetch
        fetchUploads();

        // Listen for real-time updates from Echo (via custom event)
        window.addEventListener('file-upload-updated', (event) => {
            const upload = event.detail;
            
            // Update the specific upload item in the DOM
            const uploadItems = document.querySelectorAll('.upload-item');
            let found = false;
            
            uploadItems.forEach(item => {
                const filename = item.querySelector('.upload-filename');
                if (filename && filename.textContent === upload.original_filename) {
                    // Update the existing item with new data
                    const statusSpan = item.querySelector('.status');
                    if (statusSpan) {
                        statusSpan.className = `status status-${upload.status}`;
                        statusSpan.innerHTML = `${upload.status === 'processing' ? '<span class="spinner"></span>' : ''}${upload.status}`;
                    }
                    
                    // Update progress info if it exists
                    const progressInfo = item.querySelector('.progress-info');
                    if (upload.total_rows) {
                        if (progressInfo) {
                            progressInfo.textContent = `Processed: ${upload.processed_rows || 0} / ${upload.total_rows} rows`;
                        } else {
                            const uploadInfo = item.querySelector('.upload-info');
                            const newProgressInfo = document.createElement('div');
                            newProgressInfo.className = 'progress-info';
                            newProgressInfo.textContent = `Processed: ${upload.processed_rows || 0} / ${upload.total_rows} rows`;
                            uploadInfo.appendChild(newProgressInfo);
                        }
                    }
                    
                    found = true;
                }
            });
            
            // If not found, add as new item at the top
            if (!found) {
                const newItem = document.createElement('div');
                newItem.className = 'upload-item';
                newItem.innerHTML = `
                    <div class="upload-info">
                        <div class="upload-filename">${escapeHtml(upload.original_filename)}</div>
                        <div class="upload-time">${formatDate(upload.created_at)}</div>
                        ${upload.total_rows ? `<div class="progress-info">Processed: ${upload.processed_rows || 0} / ${upload.total_rows} rows</div>` : ''}
                        ${upload.error_message ? `<div class="error-message">${escapeHtml(upload.error_message)}</div>` : ''}
                    </div>
                    <div class="upload-info">
                        <div style="font-weight: 600; color: #333; margin-bottom: 0.25rem;">File Name</div>
                        <div style="font-size: 0.875rem; color: #666;">${escapeHtml(upload.original_filename)}</div>
                    </div>
                    <div class="upload-info">
                        <span class="status status-${upload.status}">
                            ${upload.status === 'processing' ? '<span class="spinner"></span>' : ''}
                            ${upload.status}
                        </span>
                    </div>
                `;
                
                // Remove empty state if it exists
                const emptyState = uploadList.querySelector('.empty-state');
                if (emptyState) {
                    emptyState.remove();
                }
                
                // Add new item at the top
                uploadList.insertBefore(newItem, uploadList.firstChild);
            }
        });
    </script>
</body>
</html>
