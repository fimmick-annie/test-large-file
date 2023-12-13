<?php

namespace App\Http\Controllers;

use App\Models\MemberEvent;
use Illuminate\Http\Request;
use App\Http\Resources\MemberEventsCollection;
use Illuminate\Support\Facades\Schema;

class MemberEventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        //
        $user_id = $request->query('user_id');
        $mobile = $request->query('mobile');
        $paginate = $request->query('paginate');

        if ($mobile != null) {
            if ($paginate != null) {

                return new MemberEventsCollection(MemberEvent::where('user_id', '=', "+$mobile")->paginate($paginate));
            } else {

                return new MemberEventsCollection(MemberEvent::where('user_id', '=', "+$mobile")->get());
            }
        } else if ($user_id != null) {
            if ($paginate != null) {
                return new MemberEventsCollection(MemberEvent::where('user_id', '=', "$user_id")->paginate($paginate));
            } else {
                return new MemberEventsCollection(MemberEvent::where('user_id', '=', "$user_id")->get());
            }
        } else {
            if ($paginate != null) {
                return new MemberEventsCollection(MemberEvent::all()->paginate($paginate));
            } else {
                return new MemberEventsCollection(MemberEvent::all());
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $columns = Schema::getColumnListing('member_events');
        return $columns;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            // 'title' => 'required',
            // 'description' => 'required',
            // 'author' => 'required',
        ]);


        $columns = Schema::getColumnListing('member_events');
        $json = $request->all();


        $event = MemberEvent::create();

        foreach ($columns as $column) {

            foreach ($json as $key => $value) {

                if ($key == 'id' || $key == 'updated_at' || $key == 'created_at') {;
                } else if ($key == $column) {
                    $event[$key] = $value;
                } else {;
                    // return;
                }
            }
        }

        $event->save();
        return MemberEvent::find($event->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MemberEvent  $memberEvent
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MemberEvent::find($id);
        // return new MemberEventsCollection(MemberEvent::where('id', '=', $id)->paginate(20));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MemberEvent  $memberEvent
     * @return \Illuminate\Http\Response
     */
    public function edit(MemberEvent $memberEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MemberEvent  $memberEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'title' => 'required',
            // 'description' => 'required',
            // 'author' => 'required',
        ]);


        $columns = Schema::getColumnListing('member_events');
        $json = $request->all();


        $event = MemberEvent::find($id);
        if ($event == null) {
            $event = MemberEvent::create();
        }
        foreach ($columns as $column) {

            foreach ($json as $key => $value) {

                if ($key == 'id' || $key == 'updated_at' || $key == 'created_at') {;
                } else if ($key == $column) {
                    $event[$key] = $value;
                } else {;
                    // return;
                }
            }
        }

        $event->save();
        return MemberEvent::find($event->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MemberEvent  $memberEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = MemberEvent::find($id);
        if ($record != null) {
            $record->delete();
        }

        return $record;
    }
}
