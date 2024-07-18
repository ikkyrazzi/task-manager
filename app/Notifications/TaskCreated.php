<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use GuzzleHttp\Client;

class TaskCreated extends Notification
{
    use Queueable;

    protected $task;
    protected $discordWebhookUrl;

    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->discordWebhookUrl = env('DISCORD_WEBHOOK_URL');
    }

    public function via($notifiable)
    {
        return ['discord'];
    }

    public function toDiscord($notifiable)
    {
        $message = "A new task has been created: " . $this->task->title;

        $client = new Client();
        
        $response = $client->post($this->discordWebhookUrl, [
            'json' => [
                'content' => $message,
            ],
        ]);
        
        return $response;
    }
}
