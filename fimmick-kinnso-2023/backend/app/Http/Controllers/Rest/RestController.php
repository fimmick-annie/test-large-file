<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $model = null;
    protected $hidden = [];

    protected $meta = [
        'disable' => [
            'id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at'
        ],
        'select' => [],
        'textarea' => [],
    ];


    public function apiRoot()
    {
        // auth permission mddile ware is missing
        return response([
        ], 200);
    }

    public function index(Request $request)
    {
        if ($this->model == null) {
            return;
        }

        $model = $this->model;


        // $columns = Schema::getColumnListing($model->getTable());

        // $selected = [];
        // foreach ($columns as $column) {
        //     $found = false;
        //     foreach ($this->hidden as $hidden_column) {
        //         if ($hidden_column == $column) {
        //             $found = true;
        //         }
        //     }
        //     if ($found == false) {
        //         // if column not found in hidden_column, default show = true
        //         $selected[] = $column;
        //     }
        // }

        $query = $model::query()->select('*');
        
        return $query->latest('id')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->meta;
        // $model = $this->model;
        // return Schema::getColumnListing($model->getTable());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        return [];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($this->model == null) {
            return;
        }

        $model = $this->model;

        return $model::find($id);
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
        // return [];


        if ($this->model == null) {
            return;
        }

        $model = $this->model;

        $item = $model::find($id);

        if ($item == null) {
            return [];
            // $event = $model::create();
        }

        $this->validate($request, [
            // 'title' => 'required',
            // 'description' => 'required',
            // 'author' => 'required',
        ]);

        $columns = Schema::getColumnListing($model->getTable());
        $json = $request->all();

        foreach ($columns as $column) {

            foreach ($json as $key => $value) {

                if ($key == 'id' || $key == 'updated_at' || $key == 'created_at' || $key == 'deleted_at') {
                    // return;
                } else if ($key == $column) {
                    $item[$key] = $value;
                } else {;
                    // return;
                }
            }
        }

        $item->save();
        return $model::find($item->id);
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
