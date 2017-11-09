<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class Admin extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin');
    }

    /**
     * Resolves ajax request to populate the grid on the
     * index page
     *
     * @return Response
     */
    public function asyncIndex(Request $request)
    {
        $searchQuery = DB::table('events_junction');
        $searchQuery->select(
            'events_junction.junction_id AS id',
            'grid_events.number AS event',
            'grid_sponsor.sponsor_id AS sponsor',
            'grid_events.name',
            'grid_events.price',
            'grid_participants.name AS participants',
            'grid_topic.topic_name AS topic'
        );
        $searchQuery->join('grid_events', 'events_junction.event_id', '=', 'grid_events.number');
        $searchQuery->join('grid_participants', 'events_junction.participant_id', '=', 'grid_participants.participant_id');
        $searchQuery->join('grid_topic', 'events_junction.topic_id', '=', 'grid_topic.topic_id');
        $searchQuery->join('grid_sponsor', 'events_junction.sponsor_id', '=', 'grid_sponsor.sponsor_id');
        if ($request->has('name')) {
            $searchQuery->where('grid_events.name', 'like', $request->query('name') .'%');
        } // end if
        if ($request->has('participants')) {
            $searchQuery->where('grid_participants.name', 'like', $request->query('participants') .'%');
        } // end if
        if ($request->has('topic')) {
            $searchQuery->where('grid_topic.topic_name', 'like', $request->query('topic') .'%');
        } // end if
        $searchQuery->orderBy('grid_events.number', 'desc');
        $result = $searchQuery->get();

        return \response()->json($result);
    }

    /**
     * Creates the display for the form that add / edit
     * events
     *
     * @param integer $id
     *
     * @return View
     */
    public function eventsForm($id = null)
    {
        $result = [];
        if (!empty($id)) {
            $searchQuery = DB::table('events_junction');
            $searchQuery->select(
                'events_junction.junction_id AS id',
                'grid_events.name AS event',
                'grid_events.price',
                'grid_events.web_page',
                'grid_events.end_date',
                'grid_sponsor.name AS sponsor',
                'grid_sponsor.description AS sponsor_description',
                'grid_participants.participant_id',
                'grid_participants.name AS participant_name',
                'grid_topic.topic_id',
                'grid_topic.topic_name',
                'grid_status.status_id',
                'grid_status.status_name',
                'grid_range.range_id AS range_id',
                'grid_range.name AS range_name',
                'grid_community.community_id',
                'grid_community.name AS community_name',
                'grid_application.application_id',
                'grid_application.name AS application_name'
            );
            $searchQuery->join('grid_community', 'events_junction.community_id', '=', 'grid_community.community_id');
            $searchQuery->join('grid_events', 'events_junction.event_id', '=', 'grid_events.number');
            $searchQuery->join('grid_sponsor', 'events_junction.sponsor_id', '=', 'grid_sponsor.sponsor_id');
            $searchQuery->join('grid_range', 'events_junction.range_id', '=', 'grid_range.range_id');
            $searchQuery->join('grid_application', 'events_junction.application_id', '=', 'grid_application.application_id');
            $searchQuery->join('grid_topic', 'events_junction.topic_id', '=', 'grid_topic.topic_id');
            $searchQuery->join('grid_status', 'events_junction.status_id', '=', 'grid_status.status_id');
            $searchQuery->join('grid_participants', 'events_junction.participant_id', '=', 'grid_participants.participant_id');
            $searchQuery->where('events_junction.junction_id', '=', $id);

            $result = $searchQuery->get();
        } // end if

        return view('form', ['data' => $result]);
    }

    /**
     * Adds / update an event
     *
     * @param Request $request
     *
     * @return Response
     */
    public function save(Request $request)
    {
        $isUpdate = false;

        try {
            DB::beginTransaction();

            if ($request->has('id')) {

                $id = $request->input('id');
                $isUpdate = true;
                $record = DB::select('SELECT event_id, sponsor_id FROM events_junction WHERE junction_id = :id', ['id' => $id]);

                DB::table('grid_events')->where('number', $record[0]->event_id)
                    ->update([
                    'name' => $request->input('name'),
                    'price' => $request->input('price'),
                    'web_page' => $request->input('link'),
                    'end_date' => $request->input('date')
                ]);

                DB::table('grid_sponsor')->where('sponsor_id', $record[0]->sponsor_id)
                    ->update([
                    'name' => $request->input('sponsor'),
                    'description' => $request->input('description')
                ]);

                DB::table('events_junction')->where('junction_id', $id)
                    ->update([
                    'event_id' => $record[0]->event_id,
                    'sponsor_id' => $record[0]->sponsor_id,
                    'community_id' => $request->input('community'),
                    'participant_id' => $request->input('applicant'),
                    'status_id' => $request->input('status'),
                    'topic_id' => $request->input('topic'),
                    'range_id' => $request->input('range'),
                    'application_id' => $request->input('application')
                ]);

            } else {

                $id = DB::table('grid_events')->insertGetId([
                    'name' => $request->input('name'),
                    'price' => $request->input('price'),
                    'web_page' => $request->input('link'),
                    'end_date' => $request->input('date')
                ]);

                $sponsorId = DB::table('grid_sponsor')->insertGetId([
                    'name' => $request->input('sponsor'),
                    'description' => $request->input('description')
                ]);

                DB::table('events_junction')->insert([
                    'event_id' => $id,
                    'sponsor_id' => $sponsorId,
                    'community_id' => $request->input('community'),
                    'participant_id' => $request->input('applicant'),
                    'status_id' => $request->input('status'),
                    'topic_id' => $request->input('topic'),
                    'range_id' => $request->input('range'),
                    'application_id' => $request->input('application')
                ]);
            } // end if - else

            DB::commit();
            return $isUpdate ?
                redirect('admin')->with('status', 'El registro se actualizo correctamente.') :
                redirect('admin')->with('status', 'Nuevo registro se creo correctamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect('admin')->with('error', $e->getMessage());
        } // end try - catch

    }

    /**
     * Deletes an event, here's the logic for this deletion process:
     * - If there's only one coincidence of the event then we delete
     * the content from the events table
     * - If the event has more than one coincidence then only the record
     * from the junction table is deleted
     *
     * @param Request $request
     *
     * @return Response
     */
    public function delete(Request $request)
    {
        if (!$request->has('id', 'event')) {
            abort(404, 'Unable to delete without a valid ID.');
        } // end if

        $id = $request->query('id');
        $event = $request->query('event');
        $sponsor = $request->query('sponsor');

        try {
            $eventExists = DB::select('SELECT number FROM grid_events WHERE number = :id', ['id' => $event]);
            if (empty($eventExists)) {
                abort('404', 'Event id doesn\'t exist. Nothing to do');
            } // end if

            DB::beginTransaction();

            $howManyEvents = DB::table('events_junction')->where('event_id', $event)->count();
            if ($howManyEvents > 1) {
                DB::table('events_junction')->where('junction_id', $id)->delete();
            } else {
                DB::table('events_junction')->where('junction_id', $id)->delete();
                DB::table('grid_sponsor')->where('sponsor_id', $sponsor)->delete();
                DB::table('grid_events')->where('number', $event)->delete();
            } // end if - else

            DB::commit();
            return \response()->json(['code' => 200, 'message' => 'El registro se elimino correctamente.']);
        } catch(Exception $e) {
            DB::rollBack();
            return \response()->json(['code' => 500, 'message' => $e->getMessage()]);
        } // end try catch
    }
}
