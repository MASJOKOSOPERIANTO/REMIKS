<!DOCTYPE html>
<html>
<head>
    <title>Tambah Server MikroTik</title>
</head>
<body>

    <h1>Tambah Server MikroTik</h1>

   <form action="{{ route('admin.servers.store') }}" method="POST">
    @csrf

        <div>
            <label>Nama Server</label><br>
            <input type="text" name="name">
        </div>

        <br>

        <div>
            <label>Host / IP</label><br>
            <input type="text" name="host">
        </div>

        <br>

        <div>
            <label>API Port</label><br>
            <input type="number" name="api_port" value="8728">
        </div>

        <br>

        <div>
            <label>API User</label><br>
            <input type="text" name="api_user">
        </div>

        <br>

        <div>
            <label>API Password</label><br>
            <input type="password" name="api_password">
        </div>

        <br>

        <button type="submit">
            Simpan
        </button>

    </form>

</body>
</html>
