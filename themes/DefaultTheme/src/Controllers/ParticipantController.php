<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Participant;
use App\Models\ParticipantPosts;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{

    public function index()
    {
        $participant =    Participant::where('user_id', Auth::user()->id)->with('participantPost')->get();
        $post        =    post::where('type', 'match')->where('status', 'pending')->get();

        return view('front::participants.index', compact('participant', 'post'));
    }
    public function comment(Request $request)
    {

        if (Auth()->check()) {

            Log::info($request->all());
            Comment::create([
                'user_id'             =>    Auth::user()->id,
                'commentable_id'      =>    $request->state_id,
                'comment_id'          =>    $request->comment_id,
                'body'                =>    $request->comment,
                'commentable_type'    =>    'App\Models\ParticipantPosts',
            ]);
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'برای ثبت نظر باید لاگین باشید!']);
        }
    }
    public function like(Request $request, $id)
    {
        // Log::info($request->all());\
        $state = ParticipantPosts::findOrFail($id);
        $state->like()->updateOrCreate(
            [
                'user_id'        => Auth::user()->id,
                'likeable_id'    => $id,
                'likeable_type'  => 'App\Models\ParticipantPosts',
            ],
            [
                'type' => $request->type,
            ]
        );

        $state->refreshLikesCount();


        return response()->json(['like' => $state->like_count, 'dislike' => $state->dislike_count]);
    }

    public function comments($id)
    {
        // Log::info($id);
        $state = ParticipantPosts::with(['comments' => function ($query) {
            $query->accepted()->latest()->with(['comments' => function ($query) {
                $query->accepted();
            }]);
        }])->findOrFail($id);

        // Get the comments collection
        $comments = $state->comments;

        return view('front::posts.partials.comment', compact('comments'))->render();
    }

    public function participate()
    {
        // Log::info('success');
        // $participant =
        // $photo = Auth::user()->fitting();
        $post = Post::select('id', 'title')->get();

        return view('front::partials.match.match-modal', compact('post'))->render();
    }
    public function participateGetdata(Request $request)
    {
        $user = Auth::user();

        try {
            Log::info('Request received', $request->all());

            if ($request->id == 2) {
                $participant = Participant::where('post_id', $request->post_id)->where('user_id', $user->id)->first();
                return view('front::partials.match.participant-form', compact('participant', 'user'))->render();
            } else if ($request->id == 3) {
                $fitting = $user->fitting()->get();
                return view('front::partials.match.state-form', compact('fitting'))->render();
            } else if ($request->id == 4) {
                // Log::info('Processing id 4', $request->all());

                $data = $request->all();
                Log::info($data['photos']);
                if ($data['photos'] == '') {
                    $data['photos'] = null;
                    // Log::info('the photos: ' . $data['photos']);
                } else if (!$data['photos'] == '') {

                    $data['photos'] = explode(',', $request->photos);
                }
                // Log::info('Processed data 2', $data['photos']);

                return view('front::partials.match.participate-swaper', compact('data'))->render();
            } else if ($request->id == 5) {
                // Log::info($request->all());
                $data = (object) $request->all();
                $final = $this->participatStore($data, $user);

                return response()->json($final);
            }
        } catch (\Exception $e) {
            Log::error('Error processing request', ['exception' => $e]);
            return response()->json(['message' => 'خطایی رخ داده است!', 'result' => false]);
        }
    }

    public function participatStore($data, $user)
    {


        try {
            $post = Post::findOrFail($data->post_id);

            $participate = $post->participant()->updateOrCreate(
                [
                    'user_id' => Auth::user()->id,
                    'post_id' => $data->post_id
                ],
                [
                    'name'     => $user->fullname,
                    'username' => $data->username,
                    'phone'    => $user->username
                ]
            );


            // $photos = explode(',', $data->photos);
            $photos = $this->updateParticipantPhotos($data->photos);
            $images = $this->updateParticipantImages($data->images);
            $finalImage = '';
            if ($images && $photos) {
                // Log::info('both have value');
                $finalImage = array_merge($photos, $images);
                $finalImage = implode(',', $finalImage);
            } else if ($images) {
                // Log::info('images have value');
                $finalImage = implode(',', $images);
            } else if ($photos) {
                $finalImage = implode(',', $photos);
                // Log::info('photos have value');
            }
            // Log::info($finalImage);
            // Log::info($$data->images);


            $participate->participantPost()->create([
                'photo'         => $finalImage,
                'video'         => $data->video,
                'name'          => null,
                'state'         => 'pending',
                'caption'       => $data->caption,
            ]);

            return ['message' => 'عملیات با موفقیت انجام شد!', 'result' => true];
        } catch (Exception $e) {
            Log::error($e);
            return ['message' => 'خطایی رخ داده است!', 'result' => false];
        }
    }

    private function updateParticipantImages($images)
    {
        try {

            $images = explode(',', $images);

            array_shift($images); // Remove the first element if it's not empty

            // dd($images);
            $updatedImages = [];

            foreach ($images as $imageName) {
                if (Storage::exists('tmp/' . $imageName)) {
                    Storage::move('tmp/' . $imageName, 'participant/' . $imageName);
                    $updatedImages[] = '/uploads/participant/' . $imageName;
                }
            }

            return $updatedImages;
        } catch (Exception $e) {
            Log::error($e);
        }
    }
    private function updateParticipantPhotos($images)
    {
        try {

            $images = explode(',', $images);

            // dd($images);
            $updatedImages = [];
            foreach ($images as $imageName) {
                $image = basename($imageName);
                if (Storage::exists('fitting/' . $image)) {
                    $extension = pathinfo($image, PATHINFO_EXTENSION);

                    // Generate a unique name
                    $newImageName = (string) Str::uuid() . '.' . $extension;
                    Storage::copy('fitting/' . $image, 'participant/' . $newImageName);
                    $updatedImages[] = '/uploads/participant/' . $newImageName;
                }
            }

            return $updatedImages;
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function more($id)
    {
        try {
            $state = ParticipantPosts::where('participant_id', $id)->get();
            $participant = Participant::select('name', 'id', 'created_at')->findOrFail($id);
            return view('front::partials.match.state', compact('state', 'participant'))->render();
        } catch (Exception $e) {
            // Log the exception for debugging
            Log::error('Error fetching participant data: ' . $e->getMessage());
            // Return an error response
            return response()->json(['error' => 'Failed to fetch participant data.'], 500);
        }
    }

    public function stateForm($id)
    {
        $user = Auth::user();

        try {
            $fitting = $user->fitting()->get();
            $state = ParticipantPosts::findOrFail($id);
            return view('front::partials.match.state-edit-form', compact('fitting', 'state'))->render();
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function postUpdate(Request $request, $id)
    {

        try {
            $state = ParticipantPosts::findOrFail($id);

            $statePhoto = explode(',', $state->photo);

            $photos = $this->updateParticipantPhotos($request->photos);
            $images = $this->updateParticipantImages($request->images);
            $Image = [];
            if ($images && $photos) {
                $Image = array_merge($photos, $images);
            } else if ($images) {
                $Image = $images;
            } else if ($photos) {
                $Image = $photos;
            }
            $finalImage = array_merge($statePhoto, $Image);
            if ($finalImage[0] == '') {
                Log::info('true');
                array_shift($finalImage);
            }
            $finalImage = implode(',', $finalImage);

            $state->update([
                'name'          => $request->name,
                'video'         => $request->video,
                'caption'       => $request->caption,
                'state'         => 'pending',
                'photo'         => $finalImage,

            ]);
            return response('success');
        } catch (Exception $e) {
            Log::error($e);
            return response('failed');
        }
    }
    public function deletePhoto(Request $request, $id)
    {
        // Log::error($request->all());
        try {
            $state = ParticipantPosts::findOrFail($id);
            $images = explode(',', $state->photo);

            if (($key = array_search($request->img, $images)) !== false) {

                if (Storage::disk('public')->exists($request->img)) {
                    Storage::disk('public')->delete($request->img);
                }
                unset($images[$key]); // Remove photo from array

                if (!$images) {
                    $state->photo = null;
                } else {
                    $state->photo = implode(',', $images);
                }

                $state->save();

                return response()->json(['deleted_photo' => $request->img], 200);
            } else {
                return response()->json(['error' => 'Photo not found'], 404);
            }
        } catch (Exception $e) {
            // Handle errors during file deletion or database saving
            Log::error('Error deleting photo: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete photo'], 500);
        }
    }
}
