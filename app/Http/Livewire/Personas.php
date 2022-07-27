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
    public $colorSeleccionado;
    public $clave = 1234;
    public $claveAdmin = 4321;
    public $claveIntroducida;
    public $acceso = false;
    public $admin = false;
    public $nombre;
    public $color;
    public $diasVacaciones = 21;
    public $diasAsuntos = 1;
    public $diasAcumulados = 0;
    public $diasExtra = 0;

    protected $rules = [
        'personaDatos.diasVacaciones' => 'int',
        'personaDatos.diasAsuntos' => 'int',
        'personaDatos.diasAcumulados' => 'int',
        'personaDatos.diasExtra' => 'int',

    ];

    //lanza funciones cuando se usa emit desde js
    protected $listeners = [
        'recalcular' => 'updatedPersona',
        'borrarPersona' => 'borrarPersona'
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
            $this->colorSeleccionado = $this->personaDatos->color;
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
            $minutosSueltos = 0;
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
                $minutosSueltos += $diff;
            }
            $minutosUsados = ($diasCompletos * 8 * 60) + $minutosSueltos;
            $minutosVacaciones = $this->personaDatos->totalDias * 8 * 60;
            $minutosRestantes = $minutosVacaciones - $minutosUsados;
            $diasRestantes = intdiv(intdiv($minutosRestantes, 60), 8);
            $horasRestantes = ($minutosRestantes - ($diasRestantes * 8 * 60)) / 60;

            $this->personaDatos->totalDiasUsados = $diasCompletos;
            $this->personaDatos->restoHoras = $minutosSueltos / 60;
            $this->personaDatos->diasRestantes = $diasRestantes;
            $this->personaDatos->horasRestantes = $horasRestantes;

            $this->personaDatos->eventos = $eventos;
        }
    }

    public function updatedColorSeleccionado()
    {
        $this->personaDatos->color = $this->colorSeleccionado;
        $this->personaDatos->update();
        $this->recuperarDatos();
        $this->calcularVacaciones();
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function updatedPersonaDatos()
    {
        $this->personaDatos->update();
        $this->recuperarDatos();
        $this->calcularVacaciones();
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function crearPersona()
    {
        $validatedData = $this->validate([
            'nombre' => 'required',
            'color' => 'required',
            'diasVacaciones' => 'required',
            'diasAsuntos' => 'required',
            'diasAcumulados' => 'required',
            'diasExtra' => 'required',
        ]);

        Persona::create($validatedData);

        $this->personas = Persona::all();

        $this->dispatchBrowserEvent('creadaPersona');
    }

    public function borrarPersona()
    {
        if ($this->persona) {
            $personaBorrar = Persona::find($this->personaDatos->id);
            $personaBorrar->delete();
            $this->persona = null;
            $this->personaDatos = null;
            $this->personas = Persona::all();
            $this->dispatchBrowserEvent('borradaPersona');
            $this->dispatchBrowserEvent('contentChanged');
        }
    }

    public function render()
    {
        return view('livewire.personas');
    }
}
