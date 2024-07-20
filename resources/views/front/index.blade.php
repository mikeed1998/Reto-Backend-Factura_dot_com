@extends('layouts.app')

@section('titulo', 'Home')

@section('extraCSS')
    
@endsection

@section('contenido')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 border border-dark">
                <div class="row">
                    <div class="col">

                    </div>
                </div>

                <div class="row bg-dark py-2 text-white">
                    <div class="col-3">Razón Social</div>
                    <div class="col-2">Folio</div>
                    <div class="col-1">Serie</div>
                    <div class="col-1">Total</div>
                    <div class="col-1">Fecha</div>
                    <div class="col-1">Estatus</div>
                    <div class="col-2">Opciones</div>
                </div>
           
                @forelse ($lista_array as $li)
                    <div class="row border-dark">
                        <div class="col-3 border-end border-dark">{{ $li['RazonSocialReceptor' ?? 'Razón social no disponible'] }}</div>
                        <div class="col-2 border-end border-dark">{{ $li['Folio'] ?? 'Folio no disponible' }}</div>
                        <div class="col-1 border-end border-dark">{{ $li['UID'] ?? 'UID no disponible' }}</div>
                        <div class="col-1 border-end border-dark">{{ $li['Total'] ?? 'Total no disponible' }}</div>
                        <div class="col-1 border-end border-dark">{{ $li['FechaTimbrado'] ?? 'Fecha Timbrado no disponible' }}</div>
                        <div class="col-1 {{ ($li['Status'] == 'eliminada') ? 'bg-white' : 'bg-info' }}">{{ $li['Status'] ?? 'Status no disponible' }}</div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-6 px-0">
                                    <form action="{{ route('api.cancelCdfi') }}" method="POST" id="form-cancel-{{ $li['UUID'] }}">
                                        @csrf
                                        <input type="hidden" name="uuid" value="{{ $li['UUID'] }}">
                                        <input type="hidden" name="uid" value="{{ $li['UID'] }}">
                                        <button type="submit" class="btn btn-danger w-100 rounded-0">Cancelar</button>
                                    </form>
                                </div>
                                <div class="col-6 px-0">
                                    <form action="{{ route('api.sendEmail') }}" method="POST" id="form-email-{{ $li['UUID'] }}">
                                        @csrf
                                        <input type="hidden" name="uuid" value="{{ $li['UUID'] }}">
                                        <input type="hidden" name="uid" value="{{ $li['UID'] }}">
                                        <button type="submit" class="btn btn-dark w-100 rounded-0">Enviar por Email</button>
                                    </form>
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
    
@endsection