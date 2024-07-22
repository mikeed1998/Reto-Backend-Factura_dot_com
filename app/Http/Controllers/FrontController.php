<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FrontController extends Controller
{
    public function index() : View
    {
        $curl = curl_init();        // Inicio de una sesión cURL

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.factura.com/api/v4/cfdi/list',  // URL para la solicitud HTTP
            CURLOPT_RETURNTRANSFER => true,                                 // Para devolver el resultado como una cadena
            CURLOPT_ENCODING => '',                                         // Tipo de codificación, como lo deje en blanco, entonces acepta todos los tipos
            CURLOPT_MAXREDIRS => 10,                                        // El número máximo de redirecciones que se seguirán si hay alguna redirección HTTP
            CURLOPT_TIMEOUT => 0,                                           // El tiempo máximo en segundos que cURL debe esperar por una respuesta. 0 = indefinidamente
            CURLOPT_FOLLOWLOCATION => true,                                 // Para seguir las redirecciones, no lo estoy utilizando
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,                  // Versión de HTTP a utilizar (1.1)
            CURLOPT_CUSTOMREQUEST => 'GET',                                 // Tipo de solicitud, como quiero consultar datos uso GET
            CURLOPT_POSTFIELDS => '',                                       // Cuerpo de la solicitud, como estoy usando GET no enviaré nada
            CURLOPT_HTTPHEADER => array(                                    // Cabezeras personalizadas, aquí se especifican los accesos para poder conectarma la API
                'Content-Type: application/json',
                'F-PLUGIN: 9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
                'F-Api-Key: JDJ5JDEwJHNITDlpZ0ZwMzdyd0RCTzFHVXlUOS5XVnlvaFFjd3ZWcnRBZHBIV0Q5QU5xM1Jqc2lpNlVD',
                'F-Secret-Key: JDJ5JDEwJHRXbFROTHNiYzRzTXBkRHNPUVA3WU83Y2hxTHdpZHltOFo5UEdoMXVoakNKWTl5aDQwdTFT'
            ),
        ));

        $lista = curl_exec($curl);      // Ejecuto la solicitud HTTP y la guardo en $lista

        // Manejo de errores
        if (curl_errno($curl)) {        
            $error_msg = curl_error($curl);
        }

        // Cierro la sesión, (ya una vez que guarde los datos)
        curl_close($curl);

        // En caso de un error, regreso a la página anterior
        if (isset($error_msg)) {
            return redirect()->back();
        }

        // dd($lista);
        $lista_d = json_decode($lista, true);   // Decodifico mi $lista
        // dd($lista_d);
        $lista_array = $lista_d['data'] ?? [];  // Estraigo los datos asociados a la clave "data" de mi $lista (en donde están los datos que me interesan)

        // dd($lista_array);
        
        return view('front.index', compact('lista_array'));
    }

}
