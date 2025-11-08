import './bootstrap';

// Setup Echo listener for file uploads
if (window.Echo) {
    window.Echo.channel('file-uploads')
        .listen('FileUploadStatusChanged', (e) => {
            // Trigger a custom event that the page can listen to
            window.dispatchEvent(new CustomEvent('file-upload-updated', { detail: e }));
        });
}
