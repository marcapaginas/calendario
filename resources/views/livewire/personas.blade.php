<div class="w-3/12 pr-3">
    @if (!$acceso)
        <div class="w-full mr-3 h-full">
            <form class="p-3" wire:submit.prevent='checkClave'>
                <label for="clave" class="font-bold text-xl">Clave</label>
                <input class="border border-gray-500 rounded p-2 w-full" type="password" name="clave" id="clave"
                    wire:model='claveIntroducida'>
                <button
                    class="w-full rounded bg-gray-500 text-white font-bold hover:bg-gray-700 px-3 py-2 my-2">ENVIAR</button>
            </form>
        </div>
    @else
        <div class="w-full mr-3 h-full">
            <div class="bg-gray-200 rounded-xl h-full w-full">
                @if ($admin)
                    <div class="p-3">
                        <select class="p-2 w-full rounded" name="persona" id="persona" wire:model="persona">
                            <option value="">Selecciona Persona</option>
                            @foreach ($personas as $e)
                                <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (!$personaDatos)
                        <div class="p-2" x-data="{ open: false }">

                            <button class="w-full rounded px-3 py-2 text-white bg-gray-500 hover:bg-gray-700"
                                @click="open = !open">CREAR
                                PERSONA</button>

                            <div x-show="open" class="bg-white p-3 w-full" x-cloak>
                                <form wire:submit.prevent="crearPersona">
                                    <label for="nombre">Nombre
                                        <input class="border rounded w-full px-2 py-1 w-full" type="text"
                                            name="nombre" id="nombre" wire:model="nombre">
                                        @error('nombre')
                                            <span class="text-red-500">{{ $message }}</span><br>
                                        @enderror
                                    </label>

                                    <label for="color">Color
                                        <input class="w-full h-10" type="color" name="color" id="color"
                                            wire:model="color">
                                        @error('color')
                                            <span class="text-red-500">{{ $message }}</span><br>
                                        @enderror
                                    </label>
                                    <label for="diasVacaciones">Días Vacaciones:
                                        <input class="border rounded px-2 py-1 w-full" type="number"
                                            name="diasVacaciones" id="diasVacaciones" value="21"
                                            wire:model="diasVacaciones">
                                        @error('diasVacaciones')
                                            <span class="text-red-500">{{ $message }}</span><br>
                                        @enderror
                                    </label>
                                    <label for="diasAsuntos">Días Asuntos Propios:
                                        <input class="border rounded px-2 py-1 w-full" type="number" name="diasAsuntos"
                                            id="diasAsuntos" value="1" wire:model="diasAsuntos">
                                        @error('diasAsuntos')
                                            <span class="text-red-500">{{ $message }}</span><br>
                                        @enderror
                                    </label>
                                    <label for="diasAcumulados">Días Acumulados:
                                        <input class="border rounded px-2 py-1 w-full" type="number"
                                            name="diasAcumulados" id="diasAcumulados" value="0"
                                            wire:model='diasAcumulados'>
                                        @error('diasAcumulados')
                                            <span class="text-red-500">{{ $message }}</span><br>
                                        @enderror
                                    </label>
                                    <label for="diasExtra">Días Extra:
                                        <input class="border rounded px-2 py-1 w-full" type="number" name="diasExtra"
                                            id="diasExtra" value="0" wire:model="diasExtra">
                                        @error('diasExtra')
                                            <span class="text-red-500">{{ $message }}</span><br>
                                        @enderror
                                    </label>
                                    <button
                                        class="w-full rounded bg-blue-500 hover:bg-blue-700 font-bold text-white my-2 px-3 py-2">Crear</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
                @if ($personaDatos)
                    <div class="px-3">
                        <p class="border-b border-gray-700 border-dashed py-2">Total Días: <span
                                class="font-bold">{{ $personaDatos->totalDias }}</span></p>
                        <p class="border-b border-gray-700 border-dashed py-2">Días Usados: <span
                                class=" block font-bold">{{ $personaDatos->totalDiasUsados }} días
                                @if ($personaDatos->restoHoras != 0)
                                    y {{ $personaDatos->restoHoras }} horas
                                @endif
                            </span></p>
                        <p class="py-2">Días Restantes: <span
                                class=" block font-bold">{{ $personaDatos->diasRestantes }} días
                                @if ($personaDatos->horasRestantes != 0)
                                    y {{ $personaDatos->horasRestantes }}
                                    horas
                                @endif
                            </span></p>
                        <div x-data="{ open: false }">
                            <button class="w-full rounded bg-gray-500 hover:bg-gray-700 py-2 font-bold text-white"
                                @click="open = !open">EDITAR</button>
                            <div x-show="open" class="bg-white p-3 rounded" x-cloak>
                                <label for="color">Color
                                    <input class="w-full h-10" type="color" name="color" id="color"
                                        wire:model="colorSeleccionado">
                                </label>
                                <label for="diasVacaciones">Días Vacaciones:
                                    <input class="border rounded px-2 py-1 w-full" type="number" name="diasVacaciones"
                                        id="diasVacaciones" wire:model="personaDatos.diasVacaciones">
                                </label>
                                <label for="diasAsuntos">Días Asuntos Propios:
                                    <input class="border rounded px-2 py-1 w-full" type="number" name="diasAsuntos"
                                        id="diasAsuntos" wire:model="personaDatos.diasAsuntos">
                                </label>
                                <label for="diasAcumulados">Días Acumulados:
                                    <input class="border rounded px-2 py-1 w-full" type="number"
                                        name="diasAcumulados" id="diasAcumulados"
                                        wire:model="personaDatos.diasAcumulados">
                                </label>
                                <label for="diasExtra">Días Extra:
                                    <input class="border rounded px-2 py-1 w-full" type="number" name="diasExtra"
                                        id="diasExtra" wire:model="personaDatos.diasExtra">
                                </label>
                                <button class="bg-red-500 text-white rounded px-3 py-2"
                                    onclick="borrarPersona()">BORRAR</button>
                            </div>
                        </div>

                    </div>
                    {{-- <div class="mb-5">
                        <input type="color" name="color" id="color" wire:model="color">
                        <button class="btn btn-primary" wire:click="cambiarColor">Actualizar Color</button>
                        <div>
                            <ul>
                                <li>Días de Asuntos propios
                                    <span>{{ $personaDatos->diasAsuntos }}</span>
                                </li>
                                <li>Días de Vacaciones
                                    <span>{{ $personaDatos->diasVacaciones }}</span>
                                </li>
                                <li>Días Acumulados
                                    <span>{{ $personaDatos->diasAcumulados }}</span>
                                </li>
                                <li>Días Extra
                                    <span>{{ $personaDatos->diasExtra }}</span>
                                </li>
                                <li>Total días
                                    <span>{{ $personaDatos->totalDias }}</span>
                                </li>
                            </ul>
                            <hr>
                            <ul>
                                <li>Dias Completos usados:
                                    <span>{{ $personaDatos->diasCompletos }}</span>
                                </li>
                                <li>Horas sueltas:
                                    <span>{{ $personaDatos->horasSueltas }}</span>
                                </li>
                                <li>Horas sueltas en dias:
                                    <span>{{ $personaDatos->diasHoras }}</span>
                                </li>
                                <li>Total Días Usados:
                                    <span>{{ $personaDatos->totalDiasUsados }} días @if ($personaDatos->restoHoras > 0)
                                            y {{ $personaDatos->restoHoras }} horas
                                        @endif
                                    </span>
                                </li>
                                <li>DIAS QUE LE QUEDAN:
                                    <span>{{ $personaDatos->diasRestantes }} días @if ($personaDatos->horasRestantes > 0)
                                            y {{ $personaDatos->horasRestantes }}
                                            horas
                                        @endif
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                @endif
            </div>
        </div>
    @endif
</div>
