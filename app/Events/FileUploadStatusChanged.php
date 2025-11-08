<?php

namespace App\Events;

use App\Models\FileUpload;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileUploadStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public FileUpload $fileUpload
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('file-uploads'),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->fileUpload->id,
            'original_filename' => $this->fileUpload->original_filename,
            'status' => $this->fileUpload->status,
            'total_rows' => $this->fileUpload->total_rows,
            'processed_rows' => $this->fileUpload->processed_rows,
            'error_message' => $this->fileUpload->error_message,
            'created_at' => $this->fileUpload->created_at->toISOString(),
        ];
    }
}
