<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Translate\V2\TranslateClient;

class TranslatorController extends Controller
{

    public function translator(Request $request)
    {
        try {

            // Initialization
                $api_key = env('GOOGLE_API_KEY');
                $translation_text = $request->get('translate');
            // End Initialization
                
            if ( is_null($api_key) || !$api_key ) {
                
                // No Api Key response
                return Response()->json([
                    'success' => false,
                    'message' => "Permission denied due to missing api key!",
                    'translation' => null,
                ], 401);
            }

            if ( isset($translation_text) && !is_null($translation_text ) ) {
                $translate = new TranslateClient([
                    'key' => $api_key
                ]);

                // Translate text from english to french.
                $result = $translate->translate($translation_text, [
                    'target' => 'fr'
                ]);

                // Success response
                return Response()->json([
                    'success' => true,
                    'message' => "Data successfully translated to French",
                    'translation' => $result['text'],
                ], 200);
            }
            else {
                // Failure response
                return Response()->json([
                    'success' => false,
                    'message' => "Nothing to translate",
                    'translation' => null,
                ], 422);
            }
            
            
        } 
        catch (\Exception $e) {

            // Error response
            return Response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'translation' => null,
            ], 422);
        }
    }
}
