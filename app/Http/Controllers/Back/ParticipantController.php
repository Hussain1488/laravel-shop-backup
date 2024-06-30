<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Participant;
use App\Models\ParticipantPosts;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ParticipantController extends Controller
{


    public function index()
    {

        return view('back.participants.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function datatable()
    {
        $query = Participant::query();
        try {

            return DataTables::eloquent($query)
                ->addColumn('counter', function () {
                    return null;
                })->addColumn('post', function ($query) {
                    return $query->post->title;
                })->filterColumn('post', function ($query, $keyword) {
                    return $query->whereHas('post', function ($query) use ($keyword) {
                        $query->where('title', 'like', '%' . $keyword . '%');
                    });
                })
                ->addColumn('name', function ($query) {
                    return $query->name;
                })
                ->filterColumn('name', function ($query, $keyword) {
                    return $query->where('name', 'like', '%' . $keyword . '%');
                })
                ->addColumn('username', function ($query) {
                    return $query->username;
                })->filterColumn('username', function ($query, $keyword) {
                    return $query->where('username', 'like', '%' . $keyword . '%');
                })->addColumn('phone', function ($query) {
                    return $query->phone;
                })->addColumn('pending_count', function ($query) {
                    return $query->participantPost->where('state', 'pending')->count();
                })->addColumn('valid_count', function ($query) {
                    return $query->participantPost->where('state', 'valid')->count();
                })->addColumn('not_valid_count', function ($query) {
                    return $query->participantPost->where('state', 'not-valid')->count();
                })->filterColumn('phone', function ($query, $keyword) {
                    return $query->where('phone', 'like', '%' . $keyword . '%');
                })->addColumn('action', function ($query) {
                    return $query->id;
                })->make(true);
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function postIndex()
    {
        return view('back.participants.postIndex');
    }
    public function postDatatable(Request $request)
    {
        // Log::info($request->type);
        if ($request->state == 'all') {

            $query = ParticipantPosts::query();
        } else {
            $query = ParticipantPosts::where('state', $request->state);
        }
        try {

            return DataTables::eloquent($query)
                ->addColumn('counter', function () {
                    return null;
                })->addColumn('participant', function ($query) {
                    return $query->participant->name;
                })->filterColumn('name', function ($query, $keyword) {
                    return $query->whereHas('name', function ($query) use ($keyword) {
                        $query->where('title', 'like', '%' . $keyword . '%');
                    });
                })
                ->addColumn('like_count', function ($query) {
                    return $query->like_count;
                })
                ->addColumn('dislike_count', function ($query) {
                    return $query->dislike_count;
                })
                ->addColumn('comment_count', function ($query) {
                    return $query->commentCount();
                })
                ->addColumn('state', function ($query) {
                    return $query->state;
                })
                ->addColumn('action', function ($query) {
                    return $query->id;
                })->make(true);
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        $participant = $request->only(['name', 'username', 'phone']);
        $participant = $post->participant()->create($participant);
        return response()->json($participant);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Log::info($id);
        try {
            $participant = Participant::with('participantPost')->findOrFail($id);
            $post = $participant->participantPost;
            foreach ($post as $item) {
                $images = explode(',', $item->photo);

                // dd($images);
                foreach ($images as $image) {
                    Log::info($image);

                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                        // Log::info("File deleted: " . $image);
                    } else {
                        Log::warning("File not found: " . $image);
                    }
                }
            }
            $participant->delete();

            // return response('success');
        } catch (Exception $e) {
            Log::error($e);
            return response();
        }
    }

    public function stateDelete(Request $request)
    {
        // dd($request);
        // Log::info('sdfjhsdkl' . $request->id);
        $post = ParticipantPosts::findOrFail($request->id);
        if ($post) {
            $images = explode(',', $post->photo);

            // dd($images);
            foreach ($images as $image) {

                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                    // Log::info("File deleted: " . $image);
                } else {
                    Log::warning("File not found: " . $image);
                }
            }
            $post->delete();
            return response()->json(['status' => 'success', 'message' => 'پست با موفقیت حذف شد!']);
        } else {

            return response()->json(['status' => 'fail', 'message' => 'عملیات ناموفق بود!']);
        }
    }

    public function postStore(Request $request)
    {
        // dd($request->all());

        // Log::info($request->all());
        $image = implode(',', $this->updateParticipantImages($request));

        // Log::info($image);
        $participant = Participant::findOrFail($request->participant_id);
        $participant->participantPost()->create([
            'photo'         => $image,
            'video'         => $request->video,
            'name'          => $request->name,
            'state'         => 'valid',
            'caption'       => $request->caption,
        ]);
        return response()->json($participant);
    }
    public function postEdit($id)
    {
        // dd($id);
        $post = ParticipantPosts::findOrFail($id);
        return view('back.participants.participant-post-edit', compact('post'))->render();
    }
    public function postShow($id)
    {
        // dd($id);
        $post = ParticipantPosts::with('participant')->findOrFail($id);
        return view('back.participants.post_show', compact('post'))->render();
    }
    public function postUpdate(Request $request)
    {
        // Log::info($request->all());
        try {

            $image = $this->updateParticipantImages($request);

            // Find the participant state by id
            $post = ParticipantPosts::with('participant')->findOrFail($request->stat_id);

            // Get existing images and append new ones
            $images = explode(',', $post->photo);

            foreach ($image as $key) {
                $images[] = $key;
            }

            // Update the state with new images, video, and name
            $post->update([
                'photo'         =>   implode(',', $images), // Convert array to comma-separated string
                'video'         =>   $request->video,
                'name'          =>   $request->name,
                'state'         =>   $request->state,
                'caption'       =>   $request->caption,
            ]);

            return $this->more($post->participant_id);
        } catch (Exception $e) {
            // Log the exception and return an error response
            Log::error($e);

            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    private function updateParticipantImages(Request $request)
    {
        try {

            $images = explode(',', $request->images);

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

    public function more($id)
    {
        try {
            $post = ParticipantPosts::where('participant_id', $id)->get();
            $participant = Participant::select('name', 'id', 'created_at')->findOrFail($id);
            return view('back.participants.state', compact('post', 'participant'))->render();
        } catch (Exception $e) {
            // Log the exception for debugging
            Log::error('Error fetching participant data: ' . $e->getMessage());
            // Return an error response
            return response()->json(['error' => 'Failed to fetch participant data.'], 500);
        }
    }
    public function deletePhoto(Request $request, $id)
    {
        // Log::error($request->all());
        try {
            $post = ParticipantPosts::findOrFail($id);
            $images = explode(',', $post->photo);

            if (($key = array_search($request->img, $images)) !== false) {

                if (Storage::disk('public')->exists($request->img)) {
                    Storage::disk('public')->delete($request->img);
                }
                unset($images[$key]); // Remove photo from array

                if (!$images) {
                    $post->photo = null;
                } else {
                    $post->photo = implode(',', $images);
                }

                $post->save();

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
