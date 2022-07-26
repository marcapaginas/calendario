<?php

namespace App\Http\Livewire;

use App\Models\Evento;
use App\Models\Persona;
use Livewire\Component;
use Illuminate\Support\Carbon;

class Personas extends Component
{
    public $persona;
    public $personaDatos;
    public $color;
    public $clave = 1234;
    public $claveAdmin = 4321;
    public $claveIntroducida;
    public $acceso = false;
    public $admin = false;

    //lanza funciones cuando se usa emit desde js
    protected $listeners = [
        'recalcular' => 'updatedPersona',
    ];

    public function boot()
    {
        $this->personas = Persona::all();
    }

    public function checkClave()
    {
        if ($this->claveIntroducida == $this->clave) {
            $this->acceso = true;
            $this->dispatchBrowserEvent('concedidoAcceso');
        } else if ($this->claveIntroducida == $this->claveAdmin) {
            $this->admin = true;
            $this->acceso = true;
            $this->dispatchBrowserEvent('concedidoAcceso');
            $this->dispatchBrowserEvent('concedidoAccesoAdmin');
        } else {
            $this->dispatchBrowserEvent('alerta', ['mensaje' => 'clave incorrecta']);
        }
    }

    public function updatedPersona()
    {
        $this->dispatchBrowserEvent('cambiadaPersona', ['persona' => $this->persona]);
        $this->recuperarDatos();
        $this->calcularVacaciones();
    }

    public function recuperarDatos()
    {
        $this->personaDatos = Persona::where('id', $this->persona)->first();
        if (!is_null($this->personaDatos)) {
            $this->color = $this->personaDatos->color;
            $this->personaDatos->totalDias = $this->personaDatos->diasAsuntos + $this->personaDatos->diasVacaciones + $this->personaDatos->diasExtra + $this->personaDatos->diasAcumulados;
        }
    }

    public function calcularVacaciones()
    {
        if (!is_null($this->personaDatos)) {
            $eventos = Evento::wherePersonaId($this->personaDatos->id)->get();
            $eventosDiasCompletos = Evento::wherePersonaId($this->personaDatos->id)->where('allDay', 1)->get();
            $eventosHorasSueltas = Evento::wherePersonaId($this->personaDatos->id)->where('allDay', 0)->get();
            $diasCompletos = 0;
            $horasSueltas = 0;
            $diasHoras = 0;
            $restoHoras = 0;
            foreach ($eventosDiasCompletos as $dias) {
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $dias->start);
                $end = Carbon::createFromFormat('Y-m-d H:i:s', $dias->end);
                $diff = $start->diffInDays($end, true);
                $diasCompletos += $diff;
            }
            foreach ($eventosHorasSueltas as $evento) {
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $evento->start);
                $end = Carbon::createFromFormat('Y-m-d H:i:s', $evento->end);
                $diff = $start->diffInMinutes($end, true);
                $horasSueltas += $diff / 60;
            }
            $diasHoras = intdiv($horasSueltas, 8);
            $restoHoras = $horasSueltas - ($diasHoras * 8);

            $this->personaDatos->eventos = $eventos;
            $this->personaDatos->diasCompletos = $diasCompletos; // eventos de dia completo
            $this->personaDatos->horasSueltas = $horasSueltas; //eventos de horas sueltas
            $this->personaDatos->diasHoras = $diasHoras; //horas sueltas convertidas en dias
            $this->personaDatos->totalDiasUsados = $diasCompletos + $diasHoras;
            $this->personaDatos->restoHoras = $restoHoras;
            $this->personaDatos->diasRestantes = $this->personaDatos->totalDias - ($diasHoras + $diasCompletos);
            $this->personaDatos->horasRestantes = 0;
            if ($restoHoras > 0) {
                $this->personaDatos->diasRestantes = $this->personaDatos->diasRestantes - 1;
                $this->personaDatos->horasRestantes = 8 - $restoHoras;
            }
        }
    }

    public function cambiarColor()
    {
        $this->personaDatos->color = $this->color;
        $updated = $this->personaDatos->update();
        $this->dispatchBrowserEvent('contentChanged', ['item' => $updated]);
    }

    public function render()
    {
        return view('livewire.personas');
    }
}
