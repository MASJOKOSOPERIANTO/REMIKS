<?php

namespace App\Http\Controllers;

use App\Models\Server;
use RouterOS\Client;
use RouterOS\Query;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servers = Server::all();

        return view('admin.servers.index', compact('servers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.servers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Server::create([
            'name'         => $request->name,
            'host'         => $request->host,
            'api_port'     => $request->api_port,
            'api_user'     => $request->api_user,
            'api_password' => $request->api_password,
            'status'       => true,
        ]);

        return redirect('/admin/servers');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $server = Server::findOrFail($id);

        return view('admin.servers.edit', compact('server'));
    }

    /**
     * Test RouterOS connection.
     */
    public function test($id)
    {
        $server = Server::findOrFail($id);

        try {
            $client = new Client([
                'host' => $server->host,
                'user' => $server->api_user,
                'pass' => $server->api_password,
                'port' => $server->api_port,
            ]);

            $query = new Query('/system/identity/print');

            $response = $client->query($query)->read();

            return view('admin.servers.test', [
                'server'   => $server,
                'response' => $response,
            ]);

        } catch (\Exception $e) {

            dd($e->getMessage());
        }
    }

    /**
     * Monitoring koneksi + informasi server MikroTik.
     */
    public function monitor($id)
    {
        $server = Server::findOrFail($id);

        try {
            $client = new Client([
                'host' => $server->host,
                'user' => $server->api_user,
                'pass' => $server->api_password,
                'port' => $server->api_port,
            ]);

            // Identity MikroTik.
            $identityResponse = $client->query(
                new Query('/system/identity/print')
            )->read();

            // Resource MikroTik.
            $resourceResponse = $client->query(
                new Query('/system/resource/print')
            )->read();

            $identity = $identityResponse[0] ?? [];
            $resource = $resourceResponse[0] ?? [];

            return response()->json([
                'success' => true,
                'status'  => 'online',

                'server' => [
                    'name' => $server->name,
                    'host' => $server->host,
                    'api_port' => $server->api_port,
                ],

                'mikrotik' => [
                    'identity' => $identity['name'] ?? '-',
                    'version' => $resource['version'] ?? '-',
                    'uptime' => $resource['uptime'] ?? '-',
                    'cpu_load' => isset($resource['cpu-load'])
                        ? (int) $resource['cpu-load']
                        : null,
                    'free_memory' => isset($resource['free-memory'])
                        ? (int) $resource['free-memory']
                        : null,
                    'total_memory' => isset($resource['total-memory'])
                        ? (int) $resource['total-memory']
                        : null,
                    'board_name' => $resource['board-name'] ?? '-',
                    'architecture_name' => $resource['architecture-name'] ?? '-',
                    'cpu' => $resource['cpu'] ?? '-',
                    'cpu_count' => $resource['cpu-count'] ?? '-',
                ],
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'status'  => 'offline',
                'message' => 'Server tidak dapat terhubung.',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Server $server)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $server = Server::findOrFail($id);

        $server->update([
            'name'         => $request->name,
            'host'         => $request->host,
            'api_port'     => $request->api_port,
            'api_user'     => $request->api_user,
            'api_password' => $request->api_password,
        ]);

        return redirect('/admin/servers');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $server = Server::findOrFail($id);

        $server->delete();

        return redirect('/admin/servers');
    }
}
