<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
