<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $cfdi_uid = $request->input('cfdi_uid');
        $motivo = $request->input('motivo', '01');
        // $folioSustituto = $request->input('folioSustituto');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.factura.com/v4/cfdi40/$cfdi_uid/cancel",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'status' => 'eliminada',
                'motivo' => $motivo,
                'folioSustituto' => '3336cbb9-ebd4-45e8-b60b-e7bfa6f6b5e0'
            ]),
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
