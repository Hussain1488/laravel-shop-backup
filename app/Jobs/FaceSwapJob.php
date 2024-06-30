<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class FaceSwapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $targetImagePath;
    protected $swapImagePath;
    protected $user;
    protected $image;

    public function __construct($targetImagePath, $swapImagePath, $user, $image)
    {
        $this->targetImagePath = $targetImagePath;
        $this->swapImagePath = $swapImagePath;
        $this->user = $user;
        $this->image = $image;
    }

    public function handle()
    {
        Log::info('Target Image Path job: ');
        Log::info('Swap Image Path job: ');

        $url = 'https://developer.remaker.ai/api/remaker/v1/face-swap/create-job';
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6NzczMDQ1NiwicHJvZHVjdF9jb2RlIjoiMDY3MDAzIiwidGltZSI6MTcxNjkyMzczN30.hhAhTkgziD1JH-vi1QztZCAaxZ3NX1rhw4hG3elehxM',
        ];

        $files = [
            [
                'name' => 'target_image',
                'contents' => fopen($this->targetImagePath, 'r'),
                'filename' => basename($this->targetImagePath)
            ],
            [
                'name' => 'swap_image',
                'contents' => fopen($this->swapImagePath, 'r'),
                'filename' => basename($this->swapImagePath)
            ]
        ];

        $client = new Client();

        try {
            $response = $client->request('POST', $url, [
                'headers' => $headers,
                'multipart' => $files,
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['result']['job_id'])) {
                $jobId = $responseData['result']['job_id'];
                Session::put('job_id', $responseData);
                Log::info($responseData);

                // Dispatch a new job for polling the result
                PollFaceSwapResultJob::dispatch($jobId, $this->user, $this->image);
            } else {
                Log::error('Job ID not found in the response.');
            }
        } catch (\Exception $e) {
            Log::error('Error creating job: ' . $e->getMessage());
        }
    }
}
