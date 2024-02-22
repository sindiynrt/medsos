<?php

namespace App\Http\Controllers;

use App\Models\video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $video = video::latest()->paginate(1);
        return view('video.index', compact ('video'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('video.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'video' =>['required', 'mimes:mp4', 'max:10204'],
            'caption' =>['nullable', 'string', 'max:100']    
        ]);
    $user = auth()->user();
    $video = new video;
    $video->created_by = $user->id;
    $video->video =$request->file('video')->store('video');
    $video->caption = $request->caption;
    $video->save();

    //redirect ke halaman index dan tampilkan alert
    return redirect(route('video.index'))->with('success','Video berhasil di tampilkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(video $video)
    {
        if($video->video){
            Storage::delete($video->video);
        }
        if($video->delete()) {
            return redirect(route('video.index'))->with('success','Video berhasil dihapus');
        }
        return redirect(route('video.index'))->with('error', 'Video gagal dihapus');
    }
}