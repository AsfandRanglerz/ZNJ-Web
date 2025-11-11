<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Introvideo;

class IntrovideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=Introvideo::first();
        return view('admin.pages.introVideo.index',['data'=>$data]);
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
        $request->validate([
            'video'=>'required',

        ]);
        $fileName = $request->video->getClientOriginalName();
        $filePath = 'video/'. $fileName;
        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->video));
        // File URL to access the video in frontend
        if ($isFileUploaded) {
            $video=new Introvideo();
            $video->video = $filePath;
            $video->save();
            return redirect()->route('intro-video.index')
            ->with(['status' => true, 'message' => 'Video created successfully']);
        }
            return back()
            ->with(['status' => false, 'message'=>'Unexpected error occured']);
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // public function update(Request $request, $id)
    // {
    //     $this->validate($request, [
    //         'video' => 'required|file',
    //     ]);
    //     // $fileName = $request->video->getClientOriginalName();
    //      // Sanitize file name: replace spaces and special characters
    //     $originalName = $request->video->getClientOriginalName();
    //     $fileName = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $originalName);
    //     $filePath = 'video/'. $fileName;
    //     $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->video));
    //     // File URL to access the video in frontend
    //     if ($isFileUploaded) {
    //         $video=Introvideo::find($id);
    //         $video->video = $filePath;
    //         $video->update();
    //         return redirect("/admin/pages/intro-video")
    //         ->with(['status' => true, 'message' => 'Video updated successfully']);
    //     }
    //         return back()
    //         ->with(['status' => false, 'message'=>'Unexpected error occured']);
    // }
    public function update(Request $request, $id)
    {
        // ✅ Validate video file
        $request->validate([
            'video' => 'required|file', // max 50MB
        ]);

        // ✅ Find existing record
        $video = Introvideo::findOrFail($id);

        // ✅ Delete old video if exists
        if ($video->video && file_exists(public_path($video->video))) {
            unlink(public_path($video->video));
        }

        // ✅ Sanitize file name and create directory if missing
        $originalName = $request->video->getClientOriginalName();
        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $originalName);
        $destinationPath = public_path('videos');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }

        // ✅ Move file to public/videos
        $request->video->move($destinationPath, $fileName);

        // ✅ Save relative path in DB (no "public/" prefix)
        $filePath = 'videos/' . $fileName;

        // ✅ Update DB record
        $video->video = $filePath;
        $video->save();

        return redirect()->route('intro-video.index')
            ->with(['status' => true, 'message' => 'Video updated successfully']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
