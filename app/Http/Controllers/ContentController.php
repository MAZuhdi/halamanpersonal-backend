<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
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
        $user = $this->isValidUsername($username);
        if (!$user) {
            return response()->json([
                'status' => 'not found',
                'message' => "$username not found"
            ], 404);
        };

        $contents = Content::where('username', $username)->get();

        return response()->json([
            'status' => 'success',
            'message' => "All content of $username",
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
        $user = $this->isValidUsername($username);
        if (!$user) {
            return response()->json([
                'status' => 'not found',
                'message' => "$username not found"
            ], 404);
        };

        $queried_type = $this->isValidType($type);
        if (!$queried_type) {
            return response()->json([
                'status' => 'not found',
                'message' => "invalid $type"
            ], 404);
        }

        $contents = Content::where('username', $username)
            ->where('type', $type)->get();

        return response()->json([
            'status' => 'success',
            'message' => "All content of $username under $type type",
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
            'title' => 'required',
            'subtitle' => 'string',
            'desc' => 'string',
        ]);

        $user = $request->user();

        $existing = "true";

        while ($existing) {
            $slug = Str::of($validated['title'])->slug('-');
            $randomnum = $this->randomNumber(4);
            $slug = $slug . "-" . $randomnum;
            $existing = Content::where('slug', $slug)->first();
        }

        $content = new Content;

        $content->type = $validated['type'];
        $content->username = $user->username;
        $content->title = $validated['title'];
        $content->slug = $slug;
        $content->subtitle = $validated['subtitle'];
        $content->desc = $validated['desc'];
        if ($request['img']) {
            $content->img = $request['img'];
        } else {
            $content->img = "https://dummyimage.com/852x480/15748f/ffffff&text=$slug";
        }

        $content->save();

        return response()->json($content, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function show($username, $type, $slug)
    {
        $user = $this->isValidUsername($username);
        if (!$user) {
            return response()->json([
                'status' => 'not found',
                'message' => "$username not found"
            ], 404);
        };

        $queried_type = $this->isValidType($type);
        if (!$queried_type) {
            return response()->json([
                'status' => 'not found',
                'message' => "invalid $type"
            ], 404);
        }

        $content = Content::where('username', $username)
            ->where('type', $type)
            ->where('slug', $slug)->first();

        if (!$content) {
            return response()->json([
                'status' => 'not found',
                'message' => "$slug not found"
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => "$slug content detail",
            'data' => $content
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $content = Content::where('slug', $slug)->first();

        return response()->json([
            'status' => 'Ok',
            'message' => 'Here is content detail for edit purpose',
            'data' => "$content"
        ]);
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
                'message' => "$slug content not found"
            ], 404);
        }

        $validated = $request->validate([
            'type' => 'string',
            'username' => 'string',
            'title' => 'string',
            'subtitle' => 'string',
            'desc' => 'string',
            'img'   => 'string',
        ]);

        $content->type = $validated['type'];
        $content->username = $validated['username'];
        $content->title = $validated['title'];
        $content->subtitle = $validated['subtitle'];
        $content->desc = $validated['desc'];

        if ($request['img']) {
            $content->img = $request['img'];
        }

        $content->save();

        return response()->json($content, 200);
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
            'message' => "$slug content has been deleted"
        ]);
    }
}
