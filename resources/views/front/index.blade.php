@extends('layouts.app')

@section('titulo', 'Home')

@section('extraCSS')
    
@endsection

@section('contenido')

    <div class="container-fluid">
        <div class="row">
            @forelse ($lista_array as $li)
                <div class="col-4 border">
                    <p>UUID: {{ $li['UUID'] ?? 'UUID no disponible' }}</p>
                    <p>UID: {{ $li['UID'] ?? 'UID no disponible' }}</p>
                    <p>Folio: {{ $li['Folio'] ?? 'Folio no disponible' }}</p>
                    <p>Fecha Timbrado: {{ $li['FechaTimbrado'] ?? 'Fecha Timbrado no disponible' }}</p>
                    <p>Receptor: {{ $li['Receptor'] ?? 'Receptor no disponible' }}</p>
                    <p>Razon Social Receptor: {{ $li['RazonSocialReceptor'] ?? 'Razon Social Receptor no disponible' }}</p>
                    <p>Total: {{ $li['Total'] ?? 'Total no disponible' }}</p>
                    <p>Subtotal: {{ $li['Subtotal'] ?? 'Subtotal no disponible' }}</p>
                    <p>Num Order: {{ $li['NumOrder'] ?? 'Num Order no disponible' }}</p>
                    <p>Status: {{ $li['Status'] ?? 'Status no disponible' }}</p>
                    <p>Version: {{ $li['Version'] ?? 'Version no disponible' }}</p>
                </div>
            @empty  
                <div>No se han capturado los datos</div>
            @endforelse
        </div>
    </div>
    
@endsection

@section('scripts')
    
@endsection