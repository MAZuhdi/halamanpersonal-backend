<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{

    /**
     * Return Boolean which username is exist or not, if exist return false
     *
     * @param  str $username
     * @return bool
     */
    public function invalidUsername($username)
    {
        $user = User::where('username', $username)->get();
        if ($user->isEmpty()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return random number of with pre-defined length.
     *
     * @param  int $length
     * @return \Illuminate\Http\Response
     */
    public function randomNumber($length)
    {
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }

        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($username)
    {

        if ($this->invalidUsername($username)) {
            return response('Username not Found', 404);
        }

        $contents = Content::where('username', $username)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'get data contents berhasil',
            'data' => $contents,
        ]);
    }

    /**
     * Display a listing of the Content by Type of an Username.
     *
     * @param  str $username
     * @return \Illuminate\Http\Response
     */
    public function listing($username, $type)
    {
        if ($this->invalidUsername($username)) {
            return response('Username not Found', 404);
        }

        $contents = Content::where('username', $username)
            ->where('type', $type)->get();

        return response()->json([
            'status' => 'success',
            'message' => "get data $type berhasil",
            'data' => $contents,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required',
            'username' => 'required',
            'title' => 'required',
            'subtitle' => 'string',
            'desc' => 'string',
            'img'   => 'string',
        ]);

        $slug = Str::of($validated['title'])->slug('-');
        // $randomnum = random_int(1000,9999);
        $randomnum = $this->randomNumber(4);

        $slug = $slug . "-" . $randomnum;

        $content = Content::create([
            'type' => $validated['type'],
            'username' => $validated['username'],
            'title' => $validated['title'],
            'slug' => $slug,
            'subtitle' => $validated['subtitle'],
            'desc' => $validated['desc'],
            'img' => $validated['img'],
        ]);

        return response($content, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function show($username, $type, $slug)
    {
        if ($this->invalidUsername($username)) {
            return response('Username not Found', 404);
        }

        $content = Content::where('username', $username)
            ->where('type', $type)
            ->where('slug', $slug)->first();

        return response()->json([
            'status' => 'success',
            'message' => "Konten $slug berhasil didapat",
            'data' => $content
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function edit(Content $content)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $content = Content::where('slug', $slug)->first();

        if (is_null($content)) {
            return response()->json([
                'status' => 'error',
                'message' => "$slug content tidak ditemukan"
            ]);
        }

        $validated = $request->validate([
            'type' => 'string',
            'username' => 'string',
            'title' => 'string',
            'subtitle' => 'string',
            'desc' => 'string',
            'img'   => 'string',
        ]);

        $newSlug = Str::of($validated['title'])->slug('-');
        $randomnum = $this->randomNumber(4);

        $newSlug = $newSlug . "-" . $randomnum;

        $updatedContent = [
            'type' => $validated['type'],
            'username' => $validated['username'],
            'title' => $validated['title'],
            'slug' => $newSlug,
            'subtitle' => $validated['subtitle'],
            'desc' => $validated['desc'],
            'img' => $validated['img'],
        ];
        // dd(Content::where('slug', $slug)->first());

        Content::where('slug', $slug)
            ->update($updatedContent);


        return response($updatedContent, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        Content::where('slug', $slug)->delete();

        return response()->json([
            'status' => 'success',
            'message' => "$slug has been deleted"
        ]);
    }
}
