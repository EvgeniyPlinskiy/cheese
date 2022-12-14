<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Topics/Index', [
            'topics' => Topic::all()->map(function($topic) {
                return [
                    'id' => $topic->id,
                    'name' => $topic->name,
                    'image' => asset('storage/'.$topic->image)
                ];
            })
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Topics/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)

    {
       
        $fileName = $request->image->store('topics');
        
        Topic::create([
            'name' => $request->input('name'),
            'image' =>$fileName
        ]);
        return redirect()->route('topics.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function edit(Topic $topic)
    {
        return Inertia::render('Topics/Edit', [
            'topic' => $topic,
            'image' => asset('uploads/'.$topic->image)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {
        $fileName = $topic->image;
        if ($request->file('image')) {
            $image = $request->file('image');
            $fileName = time().'_'.$image->getClientOriginalName();
            $destinationPath = 'uploads';
            $image->move($destinationPath,$fileName);
           
        }
        $topic->update([
            'name' => $request->input('name'),
            'image' => $fileName
            
        ]);
        return redirect()->route('topics.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
        
        Storage::delete($topic->image);
        $topic->delete();
        return redirect()->route('topics.index');

    }
}
