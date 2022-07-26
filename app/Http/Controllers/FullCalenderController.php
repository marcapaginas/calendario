<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FullCalenderController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
        return view('index');
    }

    public function listarEventos(Request $request)
    {
        $data = Evento::whereDate('start', '>=', $request->start)
            ->whereDate('end', '<=', $request->end)
            ->leftJoin('personas', 'eventos.persona_id', '=', 'personas.id')
            ->get(['eventos.id', 'eventos.title', 'personas.color', 'personas.nombre', 'eventos.allDay', 'eventos.start', 'eventos.end']);

        // foreach ($data as $e) {
        //     $e->title = $e->nombre . ' - ' . $e->title;
        // }

        return response()->json($data);
    }

    public function crearEvento(Request $request, $comienzo = null, $fin = null, $todoElDia = null)
    {
        return "hola crear evento " . $comienzo;
    }

    public function actualizarEvento(Request $request, $comienzo = null, $fin = null, $todoElDia = null)
    {
        return "hola actualizar evento";
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                //$persona = Persona::where('id', $request->persona)->first();
                $event = Evento::create([
                    'title' => $request->title,
                    'tipo' => $request->tipo,
                    'persona_id' => $request->persona,
                    'allDay' => (bool) $request->allDay,
                    'start' => $request->start,
                    'end' => $request->end,
                ]);

                return response()->json($event);
                break;

            case 'update':
                $event = Evento::find($request->id)->update([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                ]);

                return response()->json($event);
                break;

            case 'delete':
                $event = Evento::find($request->id)->delete();

                return response()->json($event);
                break;

            default:
                # code...
                break;
        }
    }

    public function calcular()
    {

        $eventos = Evento::whereColor('green')->get();
        $DiasCompletos = Evento::whereColor('green')->where('allDay', 1)->count();
        $horasSueltas = 0;
        $eventosHorasSueltas = Evento::whereColor('green')->where('allDay', 0)->get();

        foreach ($eventosHorasSueltas as $evento) {
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $evento->start);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $evento->end);
            $diff = $start->diffInMinutes($end, true);

            $horasSueltas += $diff / 60;

            $diasHoras = $horasSueltas / 8;
        }

        return view('calcular')->with([
            'eventos' => $eventos,
            'DiasCompletos' => $DiasCompletos,
            'horasSueltas' => $horasSueltas,
            'diasHoras' => $diasHoras,
        ]);
    }
}
