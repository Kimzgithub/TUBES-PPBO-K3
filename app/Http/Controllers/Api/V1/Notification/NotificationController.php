<?php

namespace App\Http\Controllers\Api\V1\Notification;

use App\Contracts\Repositories\NotificationRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Notification\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notifications
    ) {}

    public function index(Request $request)
    {
        $notifications = $this->notifications->forRecipient(
            $request->user(),
            $request->boolean('unread'),
            $request->integer('per_page', 15)
        );

        return NotificationResource::collection($notifications);
    }

    public function unreadCount(Request $request)
    {
        return response()->json([
            'unread_count' => $this->notifications->unreadCount(
                $request->user()
            ),
        ]);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        abort_unless(
            $notification->recipient_id === $request->user()->id,
            404
        );

        return new NotificationResource(
            $this->notifications->markAsRead($notification)
        );
    }

    public function markAllAsRead(Request $request)
    {
        $count = $this->notifications->markAllAsRead(
            $request->user()
        );

        return response()->json([
            'message' => 'Semua notifikasi berhasil dibaca.',
            'updated_count' => $count,
        ]);
    }

    public function destroy(Request $request, Notification $notification)
    {
        abort_unless(
            $notification->recipient_id === $request->user()->id,
            404
        );

        $this->notifications->delete($notification);

        return response()->json([
            'message' => 'Notifikasi berhasil dihapus.',
        ]);
    }
}