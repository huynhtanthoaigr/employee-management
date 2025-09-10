<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\ScheduleRequest;

class NewScheduleRequest extends Notification
{
    use Queueable;

    protected $request;

    public function __construct(ScheduleRequest $request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['database']; // chỉ lưu database, có thể thêm 'mail'
    }

    public function toDatabase($notifiable)
    {
        return [
            'employee_name' => $this->request->employee->name,
            'date' => $this->request->date,
            'status' => $this->request->status,
            'request_id' => $this->request->id,
        ];
    }
}
