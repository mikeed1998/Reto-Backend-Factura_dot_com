@extends('layouts.app')

@section('titulo', 'Home')

@section('extraCSS')
    <style>
        
        @media(max-width: 992px) { 
            .container-fluid {
                font-size: 0.7rem;
            } 
        }

        @media(min-width: 576px) and (max-width: 992px) { 
            .container-fluid {
                font-size: 0.8rem;
            } 
        }

        @media(min-width: 0px) and (max-width: 576px) { 
            .container-fluid {
                font-size: 1rem;
            } 
        }

        .content-container {
            display: none;
        }

    </style>
@endsection

@section('contenido')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 border border-dark">
                <div class="row">
                    <div class="col"></div>
                </div>

                {{-- Fila estática --}}
                <div class="row bg-dark text-white">
                    <div class="col-lg-4 col-12 text-lg-start text-center border py-2">Razón Social</div>
                    <div class="col-lg-1 col-4 text-lg-start text-center border py-2">Folio</div>
                    <div class="col-lg-1 col-4 text-lg-start text-center border py-2">Serie</div>
                    <div class="col-lg-1 col-4 text-lg-start text-center border py-2">Total</div>
                    <div class="col-lg-1 col-12 text-lg-start text-center border py-2">Fecha</div>
                    <div class="col-lg-1 col-12 text-lg-start text-center border py-2">Estatus</div>
                    <div class="col-lg-3 col-12 text-center border py-2">Opciones</div>
                </div>
           
                {{-- Fila dinámica --}}
                @forelse ($lista_array as $li)
                    <div class="row border border-bottom">
                        <div class="col-lg-4 border border-bottom col-12 py-2 text-lg-start text-center border-end border-dark">
                            {{ $li['RazonSocialReceptor' ?? 'Razón social no disponible'] }}
                        </div>
                        <div class="col-lg-1 border border-bottom col-4  d-flex align-items-center justify-content-center border-end border-dark">
                            {{ $li['Folio'] ?? 'Folio no disponible' }}
                        </div>
                        <div class="col-lg-1 border border-bottom col-4  d-flex align-items-center justify-content-center border-end border-dark">
                            {{ $li['UID'] ?? 'UID no disponible' }}
                        </div>
                        <div class="col-lg-1 border border-bottom col-4  d-flex align-items-center justify-content-center border-end border-dark">
                            {{ $li['Total'] ?? 'Total no disponible' }}
                        </div>
                        <div class="col-lg-1 border border-bottom col-12 d-flex align-items-center justify-content-center border-end border-dark">
                            {{ $li['FechaTimbrado'] ?? 'Fecha Timbrado no disponible' }}
                        </div>
                        {{-- Uso de operadores ternarios para definir los colores de los "Status" --}}
                        <div class="col-lg-1 border border-bottom col-12 d-flex align-items-center justify-content-center {{ ($li['Status'] == 'cancelada') ? 'bg-danger' : (($li['Status'] == 'eliminada') ? 'bg-white' : 'bg-info') }}">
                            {{ $li['Status'] ?? 'Status no disponible' }}
                        </div>
                        <div class="col-lg-3 border border-bottom col-12">
                            <div class="row">
                                <div class="col-6 px-0"> {{-- Para cancelar --}}
                                    <button class="btn btn-danger w-100 rounded-0 h-100" data-bs-toggle="modal" data-bs-target="#staticBackdrop-{{ $li['UID'] }}" @if($li['Status'] == 'cancelada') disabled @endif>Cancelar</button>
                                </div>
                                <div class="col-6 px-0">    {{-- Envía por Email y lanza una pequeña notificación --}}
                                    <button type="button" class="btn btn-dark w-100 rounded-0" onclick="sendEmail('{{ $li['UUID'] }}', '{{ $li['UID'] }}')">Enviar por Email</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Para la cancelación de CDFI -->
                    {{-- 
                        En mi caso, me decidí por evitar la creación de varias funciones o metodos dentro del controlador para caada 
                        motivo diferente (01, 02, 03, 04), utilize 4 formularios con el mismo ID para que la recepción de los datos 
                        sea la misma (sacrificando el folio reemplazable para los casos 02, 03 y 04) ya que este solo se necesita 
                        para el motivo 01, para evitar errores lógicos, implemente el uso de data-motivo, esto ya que por la forma 
                        en que implemente los formularios, utilizar name, id o clases no sirve de nada ya que siempre se enviará el
                        motivo 01, por ende, con un data-motivo para cada form tengo un mayor control de que tipo de motivo quiero 
                        enviar mientras envió los mismos datos para todos, hay otras alternativas como utilizar un solo botón y definir
                        todo dese javascript, sin embargo, consideró que si bien, utilizr varios formularios no es la mejor práctica, 
                        consideró que es más sencillo en cuestiones de mantenimiento. 
                    --}}
                    <div class="modal fade" id="staticBackdrop-{{ $li['UID'] }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel-{{ $li['UID'] }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content p-5">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel-{{ $li['UID'] }}">Cancelación de CDFI</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                {{--
                                    Aquí implemente un select dinámico, el cual dependiendo del option que eliga (aprovechando el 
                                    evento change), se mostrará cada una de las opciones para cancelar el CDFI 
                                --}}
                                <div class="modal-body">
                                    {{-- Select para controlar cada uno de los contenedores para cada caso de cancelación --}}
                                    <select id="contentSelect-{{ $li['UID'] }}" class="form-select contentSelect">
                                        <option value="">Seleccione una opción para cancelar</option>
                                        <option value="cont1">01 - Comprobante emitido con errores con relación</option>
                                        <option value="cont2">02 - Comprobante emitido con errores sin relación</option>
                                        <option value="cont3">03 - No se llevó a cabo la operación</option>
                                        <option value="cont4">04 - Operación nominiativa relacionada en una factura global</option>
                                    </select>
                                    {{-- Contenedor 1: para eliminar mediante un folió de reemplazo (y debe existir una relación) --}}
                                    <div id="cont1" class="content-container py-3">
                                        <h2>Comprobante emitido con errores con relación</h2>
                                        <form id="form-cancel-{{ $li['UUID'] }}" data-motivo="01">
                                            @csrf
                                            <input type="hidden" name="uuid" value="{{ $li['UUID'] }}">
                                            <input type="hidden" name="uid" value="{{ $li['UID'] }}">
                                            <label for="folioR" class="fs-5 mb-1">Folio por el que deseas reemplazar el CDFI que deseas cancelar</label>
                                            <select name="folioR" id="folioR-{{ $li['UID'] }}" class="form-control py-2 mb-3">
                                                @foreach ($lista_array as $subli)
                                                    @if($subli['Status'] != 'cancelada')
                                                        <option value="{{ $subli['UUID'] }}">{{ $subli['Folio'] }} - {{ $subli['RazonSocialReceptor'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-primary w-100" onclick="cancelCdfi('{{ $li['UUID'] }}', '{{ $li['UID'] }}')">Cancelar CFDI</button>
                                        </form>
                                    </div>
                                    <div id="cont2" class="content-container py-2">
                                        <h2>Comprobante emitido con errores sin relación</h2>
                                        <form id="form-cancel-{{ $li['UUID'] }}" data-motivo="02" class="py-2">
                                            @csrf
                                            <input type="hidden" name="uuid" value="{{ $li['UUID'] }}">
                                            <input type="hidden" name="uid" value="{{ $li['UID'] }}">
                                            <button type="button" class="btn btn-primary w-100" onclick="cancelCdfi('{{ $li['UUID'] }}', '{{ $li['UID'] }}')">Cancelar CFDI</button>
                                        </form>
                                    </div>
                                    <div id="cont3" class="content-container py-2">
                                        <h2>No se llevó a cabo la operación</h2>
                                        <form id="form-cancel-{{ $li['UUID'] }}" data-motivo="03" class="py-2">
                                            @csrf
                                            <input type="hidden" name="uuid" value="{{ $li['UUID'] }}">
                                            <input type="hidden" name="uid" value="{{ $li['UID'] }}">
                                            <button type="button" class="btn btn-primary w-100" onclick="cancelCdfi('{{ $li['UUID'] }}', '{{ $li['UID'] }}')">Cancelar CFDI</button>
                                        </form>
                                    </div>  
                                    <div id="cont4" class="content-container py-2">
                                        <h2>Comprobante emitido con errores sin relación</h2>
                                        <form id="form-cancel-{{ $li['UUID'] }}" data-motivo="04" class="py-2">
                                            @csrf
                                            <input type="hidden" name="uuid" value="{{ $li['UUID'] }}">
                                            <input type="hidden" name="uid" value="{{ $li['UID'] }}">
                                            <button type="button" class="btn btn-primary w-100" onclick="cancelCdfi('{{ $li['UUID'] }}', '{{ $li['UID'] }}')">Cancelar CFDI</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cerrar ventana</button>
                                </div>                    
                            </div>
                        </div>
                    </div>
                @empty
                    <div>No se han capturado los datos</div>
                @endforelse
            </div>
        </div>
    </div>
    
@endsection


@section('scripts')

    {{-- 
        ==================================================================
        Javascript: la prueba no permite el uso de JQuery por lo que decidí utilizar la peticiones asincronas
        nativas de Javascript, me refiero a la API Fecth (que básicamente me permitirá realizar peticiones AJAX) 
        ==================================================================
    --}}

    <script>

        // Hábilito permisos para las notificaciones, no es necesario ponerlo 
        document.addEventListener('DOMContentLoaded', (event) => {
            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.contentSelect').forEach(function(selectElement) {
                selectElement.addEventListener('change', function() {
                    var modalBody = this.closest('.modal-body');
                    var contentContainers = modalBody.querySelectorAll('.content-container');

                    // Escondemos todos los contenedores para cancelar
                    contentContainers.forEach(function(container) {
                        container.style.display = 'none';
                    });

                    var selectedValue = this.value;
                    var selectedOption = this.options[this.selectedIndex];

                    if (selectedValue) {
                        var selectedContainer = modalBody.querySelector('#' + selectedValue);
                        if (selectedContainer) {
                            selectedContainer.style.display = 'block';
                        }
                    }
                });
            });
        });

        // Las librerías que normalmente uso dependen de JQuery pero como no se puede utilizar, hice una alternativa bastante simple
        // Esta notificación sirve para los casos en los que se envía un Email
        function showNotification(message, type) {
            if (Notification.permission === "granted") {
                const options = {
                    body: message,
                    icon: type === "success" ? "path/to/success-icon.png" : "path/to/error-icon.png"
                };
                new Notification("Notificación", options);
            } else {
                swal(message);
            }
        }

        // Envíamos un email mediante la API fetch de javascript
        async function sendEmail(uuid, uid) {
            try {
                let response = await fetch("{{ route('api.sendEmail') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ uuid, uid })
                });

                let data = await response.json();

                if (response.ok) {
                    showNotification("Correo enviado exitosamente", "success");
                } else {
                    showNotification(data.error || "Error al enviar el correo", "error");
                }
            } catch (error) {
                showNotification("Error al enviar el correo: " + error.message, "error");
            }
        }

        async function cancelCdfi(uuid, uid) {
            const formId = event.target.closest('form').id;
            const form = event.target.closest('form');
            const motivo = form.getAttribute('data-motivo');
            const folioR = form.querySelector(`#folioR-${uid}`) ? form.querySelector(`#folioR-${uid}`).value : '';

            console.log('UUID:', uuid);
            console.log('UID:', uid);
            console.log('Motivo:', motivo);
            console.log('FolioR:', folioR);

            const modal = bootstrap.Modal.getInstance(document.querySelector(`#staticBackdrop-${uid}`));
            modal.hide();

            try {
                let response = await fetch("{{ route('api.cancelCdfi') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ uuid, uid, folioR, motivo })
                });

                let data = await response.json();
                console.log(data);
                if (response.ok && !data.error) {
                    swal(data.message).then(() => {
                        location.reload();
                    });
                } else {
                    swal(data.error || "Error al cancelar el CFDI");
                }
            } catch (error) {
                swal("Error al cancelar el CFDI: " + error.message);
            }
        }
    
    </script>

@endsection

