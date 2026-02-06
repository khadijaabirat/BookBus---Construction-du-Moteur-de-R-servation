<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class serchcontroller extends Controller
{
    public function search(Request $request)
    {
         $v_depart = $request->input('ville_depart');
        $v_arrivee = $request->input('ville_arrivee');
        $date = $request->input('date_voyage');

         $results = DB::table('segments')
            ->join('etapes as e_dep', 'segments.depart_etape_id', '=', 'e_dep.id')
            ->join('etapes as e_arr', 'segments.arrivee_etape_id', '=', 'e_arr.id')
            ->join('gares as g_dep', 'e_dep.gare_id', '=', 'g_dep.id')
            ->join('gares as g_arr', 'e_arr.gare_id', '=', 'g_arr.id')
            ->join('buses', 'segments.bus_id', '=', 'buses.id')
            ->select(
                'g_dep.nom as gare_depart',
                'g_arr.nom as gare_arrivee',
                'segments.tarif',
                'segments.duree_estimee',
                'buses.type as bus_type',
                'e_dep.heure_passage as heure_depart'
            )
             ->where('g_dep.ville_id', $v_depart)
            ->where('g_arr.ville_id', $v_arrivee)
            ->get();

         return view('search-results', compact('results'));
    }
}
}
