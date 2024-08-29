
<?php
/**

 *
 * @return response()
 */





if (!function_exists('responseWithSuccess')) {
    function responseWithSuccess($message = '', $code = 200)
    {
        return response()->json(
            [
                'status' => true,
                'message' => $message,
                //'data' => $data,
            ],
            $code
        );
    }
}
if (!function_exists('returnResponseAndStop')) {

    function returnResponseAndStop($message = '', $code = 401)
    {
        $response = response()->json(['message' => $message], $code);
        $response->send();
       // exit;
        return $response;
    }
}
if (!function_exists('responseWithError')) {
    function responseWithError($message = '', $code = 400)
    {
        return response()->json(
            [
                'status' => false,
                'message' => $message,

                //  'data' => $data,
            ],
            $code
        );
    }
}

if (!function_exists('customErrorValidatedResponse')) {
    function customErrorValidatedResponse($errors,   $statusCode = 409)
    {
        $errorMessage = 'An error occurred while processing the response.';
        try {
            $errorMessages = collect($errors)->flatten()->unique()->values()->toArray();

            $errorMessage = implode('. ', $errorMessages);
            if (!empty($errorMessage)) {
                $errorMessage .= '.';
            }

            $errorCount = count($errorMessages);
            if ($errorCount > 0) {
                $errorMessage = $errorMessages[0] . " (and {$errorCount} more error" . ($errorCount > 1 ? 's' : '') . ")";
            }


            $formattedErrors = collect($errors)->map(function ($error) {
                return [$error];
            });
        } catch (Exception $e) {
        }
        return response()->json([
            'status' => false,
            'message' =>   $errorMessage  ?? '',
            'errors' => $formattedErrors ?? $errors ?? 'N/A',
        ], $statusCode);
    }
}

function search_multidimensional($myarray, $mykey, $default = null)
{
    if (is_array($myarray) && !empty($myarray) && count($myarray) > 0) {
        foreach ($myarray as $key => $value) {
            if (is_array($value)) {
                $result = search_multidimensional($value, $mykey, $default);
                if ($result !== $default) {
                    return $result;
                }
            } elseif ($key == $mykey) {
                if ($value != null) {
                    return $value;
                } else {
                    return $default;
                }
            }
        }
    }
    return $default;
}











?>
