<?php

namespace App\Http\Controllers;

use Helge\Client\SimpleWhoisClient;
use Helge\Loader\JsonLoader;
use Helge\Service\DomainAvailability;
use Illuminate\Http\Request;

class ParserController extends Controller
{

    public function index()
    {
        $ltds = json_decode(file_get_contents(public_path('servers.json')), true);
        return view('welcome', compact('ltds'));

    }

    public function getDomainStatus(Request $request)
    {
//        return $request;
        $domain = $request['domain'];
        $whoisClient = new SimpleWhoisClient();
        $dataLoader = new JsonLoader(public_path('servers.json'));
        $service = new DomainAvailability($whoisClient, $dataLoader);
        $result = [];
        if ($service->isAvailable($domain)) {
            $result[] = [
                'status' => 'free',
                'domain' => $domain
            ];
        } else {
            $result[] = [
                'status' => 'taked',
                'domain' => $domain
            ];
        }
        return $result;

    }
}
