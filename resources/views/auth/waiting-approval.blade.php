<h1>Menunggu Verifikasi</h1>
<p>Akun Anda sedang menunggu verifikasi dari SPAdmin.</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>