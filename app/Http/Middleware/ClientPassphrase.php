<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use MR\MachineGroup;

/**
 * Client Passphrase middleware
 *
 * The client passphrase allows you to set a shared secret for every MunkiReport client, which is also listed
 * in the config, under `_munkireport.client_passphrases`.
 *
 * There may be more than one passphrase in the local config, and clients may use one of those pass phrases.
 *
 * A passphrase may be associated with a Machine Group, if the Business Unit feature is enabled. This means that if you
 * set the machine group passphrase on a client, it will be automatically placed into that Machine Group when it registers
 * with the MunkiReport server.
 *
 * @package App\Http\Middleware
 */
class ClientPassphrase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('passphrase')) {
            $machineGroup = MachineGroup::where('property', 'key')
                ->where('value', $request->post('passphrase'))
                ->first();

            if ($machineGroup) {
                Log::debug(
                    $request->getClientIp() . " provided passphrase for machine group ID " . $machineGroup->groupid
                );
            } else {
                Log::debug($request->getClientIp() . " provided a passphrase which matches no group IDs");
            }
        }

        $clientPassphrases = config('_munkireport.client_passphrases');
        if ($clientPassphrases) {
            if (!$request->has('passphrase')) {
                return abort(403, "passphrase is required but missing");
            }

            if (!in_array($request->post('passphrase'), $clientPassphrases)) {
                return abort(403, "passphrase '" . $request->post('passphrase') . "' not accepted");
            }
        }

        return $next($request);
    }

    // ClientPassphrase middleware takes care of this
//        if ($request->has('passphrase')) {
//            $this->group = passphrase_to_group($request->post('passphrase'));
//        }
//
//        if ($auth_list = config('_munkireport.client_passphrases')) {
//            if (! is_array($auth_list)) {
//                $this->error("conf['client_passphrases'] should be an array");
//            }
//
//            if (! $request->has('passphrase')) {
//                $this->error("passphrase is required but missing");
//            }
//
//            if (! in_array($request->post('passphrase'), $auth_list)) {
//                $this->error('passphrase "'.$request->post('passphrase').'" not accepted');
//            }
//        }
}
