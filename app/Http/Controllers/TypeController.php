<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();

        return response()->json([
            'status' => 'success',
            'message' => 'here is the list of all available usable type for content',
            'data' => $types
        ]);
    }

    public function indexweb()
    {
        $types = Type::orderBy("created_at", "desc")->get();
        return view('type.show-type', compact("types"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('type.create-type');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $type = new Type;

        $type->name = $request['name'];
        $type->headline = $request['headline'];
        $type->desc = $request['desc'];
        $type->icon = $request['icon'];

        $type->save();

        return response()->json([
            'status' => 'success',
            'message' => "$type->name added"
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = Type::find($id);
        return view('type.edit-type', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $type = Type::find($id);

        if ($request['name']) {
            $type->name = $request['name'];
        }
        if ($request['headline']) {
            $type->headline = $request['headline'];
        }
        if ($request['desc']) {
            $type->desc = $request['desc'];
        }
        if ($request['icon']) {
            $type->icon = $request['icon'];
        }


        $type->save();

        return response()->json([
            'status' => 'success',
            'message' => "$type->name updated"
        ]);
    }

    /**
     * Update the specified resource in storage. (for web)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function updateweb(Request $request, $id)
    {
        $type = Type::find($id);

        if ($request['name']) {
            $type->name = $request['name'];
        }
        if ($request['headline']) {
            $type->headline = $request['headline'];
        }
        if ($request['desc']) {
            $type->desc = $request['desc'];
        }
        if ($request['icon']) {
            $type->icon = $request['icon'];
        }


        $type->save();

        return redirect(route('type.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = Type::find($id);
        if (!$type) {
            return response()->json([
                'status' => 'error',
                'message' => 'type not found'
            ], 404);
        }
        $type->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'type deleted'
        ]);
    }

    public function userIndex($username)
    {
        $user = $this->isValidUsername($username);
        if (!$user) {
            return response()->json([
                'status' => 'not found',
                'message' => "$username not found"
            ], 404);
        };

        $types = DB::table('contents')
            ->where('username', $username)
            ->distinct('type')
            ->pluck('type');

        if (count($types) == 0) {
            return response()->json([
                'status' => 'success',
                'message' => "types for $username not found",
                'data' => strval('NULL')
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => "here is the types used by $username ",
                'data' => $types
            ]);
        }
    }
}
