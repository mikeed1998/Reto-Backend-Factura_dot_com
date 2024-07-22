<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCfdiList() : JsonResponse
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

    public function cancelCdfi(Request $request) : JsonResponse
    {

        $cfdi_uid = $request->input('uid');
        $cfdi_uuid = $request->input('uuid');
        $folio_sustituto = $request->input('folioR');
        $motivo = $request->input('motivo');
    
        if (!$cfdi_uid || !$cfdi_uuid) {
            return response()->json(['error' => 'cfdi_uid y cfdi_uuid son obligatorios'], 400);
        }

        // Carga util condicional, con esto evito repetir demasiado código, unicamente determino que campos enviaré a la API
        $postData = [
            'motivo' => $motivo,
        ];

        if ($motivo == '01') {
            $postData['folioSustituto'] = $folio_sustituto;
        }

        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.factura.com/api/v4/cfdi40/$cfdi_uid/cancel",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
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
            curl_close($curl);
            return response()->json(['error' => $error_msg], 500);
        }
    
        curl_close($curl);
    
        $response_data = json_decode($response, true);
    
        if (isset($response_data['error'])) {
            return response()->json(['error' => $response_data['error']], 500);
        }
    
        return response()->json($response_data);
    }

    public function sendEmail(Request $request) : JsonResponse
    {
        $cfdi_uid = $request->input('uid');
        $cfdi_uuid = $request->input('uuid');
        
        if (!$cfdi_uid || !$cfdi_uuid) {
            return response()->json(['error' => 'El UID y UUID del CFDI son obligatorios'], 400);
        }

        $curl = curl_init();

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
                'F-PLUGIN: 9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
                'F-Api-Key: JDJ5JDEwJHNITDlpZ0ZwMzdyd0RCTzFHVXlUOS5XVnlvaFFjd3ZWcnRBZHBIV0Q5QU5xM1Jqc2lpNlVD',
                'F-Secret-Key: JDJ5JDEwJHRXbFROTHNiYzRzTXBkRHNPUVA3WU83Y2hxTHdpZHltOFo5UEdoMXVoakNKWTl5aDQwdTFT'
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return response()->json(['error' => $error_msg], 500);
        }

        curl_close($curl);

        $response_data = json_decode($response, true);

        if (isset($response_data['error'])) {
            return response()->json(['error' => $response_data['error']], 500);
        }

        return response()->json($response_data);
    }

}

