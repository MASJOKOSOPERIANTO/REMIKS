<x-app-layout>

    <div class="p-6">

        <h2 class="text-xl font-bold mb-4">
            Test Connection
        </h2>

        <div class="bg-white p-4 rounded shadow">

            <p>
                <strong>Server :</strong>
                {{ $server->name }}
            </p>

            <p>
                <strong>Host :</strong>
                {{ $server->host }}
            </p>

            <hr class="my-4">

            <pre>
{{ print_r($response, true) }}
            </pre>

        </div>

    </div>

</x-app-layout>
