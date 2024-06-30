<?php

namespace App\Jobs;

use App\Models\Gallery;
use App\Models\VirtualFitting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PollFaceSwapResultJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobId;
    protected $user;
    protected $image;

    public function __construct($jobId, $user, $image)
    {
        $this->jobId = $jobId;
        $this->user = $user;
        $this->image = $image;
    }

    public function handle()
    {
        $attempts = 10; // Number of attempts to poll for the result
        $delay = 10; // Delay in seconds between attempts

        for ($i = 0; $i < $attempts; $i++) {
            $result = $this->getFaceSwapResult($this->jobId);
            if (!isset($result['error'])) {
                $savedPath = $this->storeImage($result);
                $gallery = Gallery::find($this->image);
                VirtualFitting::create([
                    'user_id'          =>   $this->user->id,
                    'gallery_id'       =>   $this->image,
                    'photo'            =>   $savedPath,
                    'product_id'       =>   $gallery->galleryable_id,
                ]);
                if ($this->user->fittingCount && $this->user->fittingCount->count >= 3 && $this->user->fittingCount->date == now()->format('Y-m-d')) {
                    Log::info('true');
                    $this->user->fittingCount()->update(
                        [
                            'count' => ++$this->user->fittingCount->count,
                            'date' => now(),
                        ]
                    );
                } else {
                    Log::info('false');
                    $this->user->fittingCount()->updateOrCreate(
                        [
                            'user_id' => $this->user->id,  // Assuming you have a user_id column to match the user
                        ],
                        [
                            'count' => 1,
                            'date' => now(),
                        ]
                    );
                }
                Log::info('Image stored at: ' . $savedPath);
                return;
            }
            if ($result['error'] !== 'Image generation in progress.') {
                Log::error('Error: ' . $result['error']);
                return;
            }
            sleep($delay);
        }

        Log::error('Image generation timed out.');
    }

    private function getFaceSwapResult($jobId)
    {
        $url = 'https://developer.remaker.ai/api/remaker/v1/face-swap/' . $jobId;
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6NzczMDQ1NiwicHJvZHVjdF9jb2RlIjoiMDY3MDAzIiwidGltZSI6MTcxNjkyMzczN30.hhAhTkgziD1JH-vi1QztZCAaxZ3NX1rhw4hG3elehxM',
        ];

        $client = new Client();

        try {
            $response = $client->request('GET', $url, [
                'headers' => $headers,
            ]);

            $responseData = json_decode($response->getBody(), true);
            Log::info($responseData);

            if (isset($responseData['result']['output_image_url']) && is_array($responseData['result']['output_image_url'])) {
                return $responseData['result']['output_image_url'][0];
            } else {
                $errorMessage = $responseData['message']['en'] ?? 'Output image URL not found in the response.';
                Log::error($errorMessage);
                return ['error' => $errorMessage];
            }
        } catch (\Exception $e) {
            Log::error('Error getting face swap result: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    private function storeImage($imageUrl)
    {
        Log::info('Storing image from URL: ' . $imageUrl);
        try {
            $client = new Client();
            $response = $client->get($imageUrl);

            if ($response->getStatusCode() == 200) {
                $filename = basename($imageUrl);
                $fileContent = $response->getBody()->getContents();

                $path = 'uploads/fitting/' . $filename;
                Storage::disk('public')->put($path, $fileContent);


                return $path;
            } else {
                Log::error('Failed to download image from URL: ' . $imageUrl);
                return ['error' => 'Failed to download image'];
            }
        } catch (\Exception $e) {
            Log::error('An error occurred while downloading the image: ' . $e->getMessage());
            return ['error' => 'An error occurred: ' . $e->getMessage()];
        }
    }
}
