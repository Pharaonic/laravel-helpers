<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Create a new JSON response instance
 *
 * @param boolean $success
 * @param null|string $message
 * @param null|string|array|object $data
 * @param null|array $extra
 * @param null|array $errors
 * @param integer $status
 * @param array $headers
 * @param integer $options
 * @return \Illuminate\Http\JsonResponse
 */
function json(bool $success, string $message = null, $data = null, array $extra = null, array $errors = null, $status = 200, array $headers = null, $options = 0)
{
    // Casting Errors to Array of Objects
    if ($errors) {
        $errList = [];
        foreach ($errors as $key => $err) {
            $errList[] = (object)[
                'key'   => $key,
                'value' => implode(PHP_EOL, $err)
            ];
        }
        $errors = $errList;
        unset($errList);
    }

    // Response
    return response()->json([
        'success'   => $success,
        'message'   => $message,
        'errors'    => $errors,
        'data'      => $data
    ] + ($extra ?? []), $status, $headers ?? [], $options);
}

/**
 * Request Inputs Validation
 *
 * @param Request $request
 * @param array $rules
 * @param null||string $defaultMessage
 * @param null||array $messages
 * @param null||array $fields
 * @return null||\Illuminate\Http\JsonResponse
 */
function validate(Request $request, array $rules, string $defaultMessage = null, array $messages = null, array $fields = null, bool $redirectToRoute = false, string $redirectTo = null, array $redirectParams = null)
{
    $validator = Validator::make($request->all(), $rules, $messages ?? [], $fields ?? []);

    if ($validator->fails()) {
        if ($request->expectsJson()) {
            // JSON Request
            return json(false, $defaultMessage, null, null, $validator->errors()->toArray());
        } else {
            // Web Request
            if ($redirectToRoute) {
                return redirect()->route($redirectTo, $redirectParams)->withErrors($validator)->withInput();
            } else {
                return redirect()->to($redirectTo)->withErrors($validator)->withInput();
            };
        }
    }

    return null;
}
