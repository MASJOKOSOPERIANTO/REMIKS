<!DOCTYPE html>
<html>
<head>
    <title>Edit Server MikroTik</title>
</head>
<body>

    <h1>Edit Server MikroTik</h1>

    <form action="{{ route('admin.servers.update', $server->id) }}" method="POST">
    @csrf
    @method('PUT')

        <div>
            <label>Nama Server</label><br>
            <input type="text" name="name" value="{{ $server->name }}">
        </div>

        <br>

        <div>
            <label>Host / IP</label><br>
            <input type="text" name="host" value="{{ $server->host }}">
        </div>

        <br>

        <div>
            <label>API Port</label><br>
            <input type="number" name="api_port" value="{{ $server->api_port }}">
        </div>

        <br>

        <div>
            <label>API User</label><br>
            <input type="text" name="api_user" value="{{ $server->api_user }}">
        </div>

        <br>

        <div>
            <label>API Password</label><br>
            <input type="text" name="api_password" value="{{ $server->api_password }}">
        </div>

        <br>

        <button type="submit">
            Update
        </button>

    </form>

</body>
</html>
