<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class Home extends Controller
{
    /**
     * Display the home page
     *
     * @return Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Retrieves the results of a search and sends it back
     * to populate the grid
     *
     * @param Request $request
     *
     * @return Response
     */
    public function search(Request $request)
    {
        if (!$request->has('topic') && !$request->has('applicant') && !$request->has('status')) {
            return back()->withInput()->with('error', 'Parece que una de las opciones de busqueda no se ha completado!.');
        } // end if

        $searchQuery = DB::table('events_junction');
        $searchQuery->select(
            'grid_community.name AS community',
            'grid_events.name',
            'grid_events.price',
            'grid_events.end_date AS date',
            'grid_sponsor.name AS sponsor',
            'grid_sponsor.description AS description',
            'grid_range.name AS scope',
            'grid_application.name AS application'
        );
        $searchQuery->join('grid_community', 'events_junction.community_id', '=', 'grid_community.community_id');
        $searchQuery->join('grid_events', 'events_junction.event_id', '=', 'grid_events.number');
        $searchQuery->join('grid_sponsor', 'events_junction.sponsor_id', '=', 'grid_sponsor.sponsor_id');
        $searchQuery->join('grid_range', 'events_junction.range_id', '=', 'grid_range.range_id');
        $searchQuery->join('grid_application', 'events_junction.application_id', '=', 'grid_application.application_id');
        $searchQuery->join('grid_topic', 'events_junction.topic_id', '=', 'grid_topic.topic_id');
        $searchQuery->join('grid_status', 'events_junction.status_id', '=', 'grid_status.status_id');
        $searchQuery->join('grid_participants', 'events_junction.participant_id', '=', 'grid_participants.participant_id');
        $searchQuery->where('grid_topic.topic_id', $request->query('topic'));
        $searchQuery->where('grid_participants.participant_id', $request->query('applicant'));
        $searchQuery->where('grid_status.status_id', $request->query('status'));
        if ($request->has('community')) {
            $searchQuery->where('grid_community.community_id', $request->query('community'));
        } // end if
        if ($request->has('range')) {
            $searchQuery->where('grid_range.range_id', $request->query('range'));
        } // end if

        $result = $searchQuery->get();

        return \response()->json($result);
    }

    /**
     * Receives an async request and provides the data necessary to populate
     * the select boxes in the form
     *
     * @param Request $request
     *
     * @return Response
     */
    public function asyncForSelects(Request $request)
    {
        $field = $request->input('field', '');
        $selectOptionData = [];

        switch ($field) {
            case 'topic':
                $selectOptionData = DB::select('SELECT topic_id AS id, topic_name AS name FROM grid_topic');
                break;
            case 'applicant':
                $selectOptionData = DB::select('SELECT participant_id as id, name FROM grid_participants');
                break;
            case 'status':
                $selectOptionData = DB::select('SELECT status_id AS id, status_name AS name FROM grid_status');
                break;
            case 'range':
                $selectOptionData = DB::select('SELECT range_id AS id, name FROM grid_range');
                break;
            case 'community':
                $selectOptionData = DB::select('SELECT community_id AS id, name FROM grid_community');
                break;
            case 'application':
                $selectOptionData = DB::select('SELECT application_id AS id, name FROM grid_application');
            default:
                break;
        } // end switch

        return view('chunks.select', ['select' => $selectOptionData]);
    }
}
