<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ApiController extends Controller
{
    public function getCfdiList()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.factura.com/api/v4/cfdi/list',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'F-PLUGIN: 9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
                'F-Api-Key: JDJ5JDEwJHNITDlpZ0ZwMzdyd0RCTzFHVXlUOS5XVnlvaFFjd3ZWcnRBZHBIV0Q5QU5xM1Jqc2lpNlVD',
                'F-Secret-Key: JDJ5JDEwJHRXbFROTHNiYzRzTXBkRHNPUVA3WU83Y2hxTHdpZHltOFo5UEdoMXVoakNKWTl5aDQwdTFT'
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }

        curl_close($curl);

        if (isset($error_msg)) {
            return response()->json(['error' => $error_msg], 500);
        }

        return response()->json(json_decode($response, true));
    }

    public function cancelCdfi(Request $request) {
        // dd($request);
        // Capturar y validar los inputs
        $cfdi_uid = $request->input('uid');
        $cfdi_uuid = $request->input('uuid');
        $motivo = $request->input('motivo', '01');
        $folio_sustituto = $request->input('folioR');

        // dd($cfdi_uid, $cfdi_uuid);
    
        // Validar que los campos necesarios están presentes
        if (!$cfdi_uid || !$cfdi_uuid) {
            return response()->json(['error' => 'cfdi_uid y cfdi_uuid son obligatorios'], 400);
        }
    
        // Inicializar cURL
        $curl = curl_init();
    
        // Configurar cURL
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.factura.com/api/v4/cfdi40/$cfdi_uid/cancel",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'motivo' => $motivo,
                'folioSustituto' => $folio_sustituto
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'F-PLUGIN: 9d4095c8f7ed5785cb14c0e3b033eeb8252416ed', // Verificar este valor
                'F-Api-Key: JDJ5JDEwJHNITDlpZ0ZwMzdyd0RCTzFHVXlUOS5XVnlvaFFjd3ZWcnRBZHBIV0Q5QU5xM1Jqc2lpNlVD', // Reemplaza con tu API Key real
                'F-Secret-Key: JDJ5JDEwJHRXbFROTHNiYzRzTXBkRHNPUVA3WU83Y2hxTHdpZHltOFo5UEdoMXVoakNKWTl5aDQwdTFT' // Reemplaza con tu Secret Key real
            ),
        ));
    
        // Ejecutar cURL y capturar la respuesta
        $response = curl_exec($curl);
    
        // Verificar errores en cURL
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return response()->json(['error' => $error_msg], 500);
        }
    
        curl_close($curl);
    
        // Decodificar la respuesta JSON
        $response_data = json_decode($response, true);
    
        // Verificar si la respuesta contiene un error
        if (isset($response_data['error'])) {
            return response()->json(['error' => $response_data['error']], 500);
        }
    
        // Devolver la respuesta exitosa
        return response()->json($response_data);
    }

    public function sendEmail(Request $request)
    {
        // Validar inputs
        $cfdi_uid = $request->input('uid');
        
        if (!$cfdi_uid) {
            return response()->json(['error' => 'El UID del CFDI es obligatorio'], 400);
        }

        // Inicializar cURL
        $curl = curl_init();

        // Configurar cURL
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.factura.com/api/v4/cfdi40/$cfdi_uid/email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'F-PLUGIN: 9d4095c8f7ed5785cb14c0e3b033eeb8252416ed', // Verificar este valor
                'F-Api-Key: JDJ5JDEwJHNITDlpZ0ZwMzdyd0RCTzFHVXlUOS5XVnlvaFFjd3ZWcnRBZHBIV0Q5QU5xM1Jqc2lpNlVD', // Reemplaza con tu API Key real
                'F-Secret-Key: JDJ5JDEwJHRXbFROTHNiYzRzTXBkRHNPUVA3WU83Y2hxTHdpZHltOFo5UEdoMXVoakNKWTl5aDQwdTFT' 
            ),
        ));

        // Ejecutar cURL y capturar la respuesta
        $response = curl_exec($curl);

        // Verificar errores en cURL
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return response()->json(['error' => $error_msg], 500);
        }

        curl_close($curl);

        // Decodificar la respuesta JSON
        $response_data = json_decode($response, true);

        // Verificar si la respuesta contiene un error
        if (isset($response_data['error'])) {
            return response()->json(['error' => $response_data['error']], 500);
        }

        // Devolver la respuesta exitosa
        // return response()->json($response_data);
        return redirect()->back();
    }
    
}


// curl_setopt_array($curl, array(
//     CURLOPT_URL => 'https://sandbox.factura.com/api/v4/cfdi/list',
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => '',
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 0,
//     CURLOPT_FOLLOWLOCATION => true,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => 'POST',
//     CURLOPT_POSTFIELDS => json_encode([
//         "month" => "01",
//         "year" => "2024",
//         "rfc" => "WERX631016S30",
//         "page" => 1,
//         "per_page" => 15
//     ]),
//     CURLOPT_HTTPHEADER => array(
//         'Content-Type: application/json',
//         'F-PLUGIN: 9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
//         'F-Api-Key: JDJ5JDEwJHNITDlpZ0ZwMzdyd0RCTzFHVXlUOS5XVnlvaFFjd3ZWcnRBZHBIV0Q5QU5xM1Jqc2lpNlVD',
//         'F-Secret-Key: JDJ5JDEwJHRXbFROTHNiYzRzTXBkRHNPUVA3WU83Y2hxTHdpZHltOFo5UEdoMXVoakNKWTl5aDQwdTFT'
//     ),
// ));
