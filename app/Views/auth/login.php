<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMBETA Polsri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* --- 1. Reset & Variabel Warna --- */
    :root {
        --simbeta-raisin: #211F21;
        --simbeta-white: #F1F1F1;
        --simbeta-blush: #C16C8A;
        --simbeta-orchid: #F4B8CA;
    }

    html, body {
        height: 100%; /* Penting untuk centering */
        margin: 0;
        padding: 0;
    }

    /* --- 2. Background Gambar & Pengaturan Tengah --- */
    body {
        /* Saya tambahkan gambar background kantor agar mirip referensimu */
        background: url('https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=2070&auto=format&fit=crop') no-repeat center center fixed;
        background-size: cover;
        
        /* Teknik Centering Absolut */
        display: flex;
        align-items: center;     /* Tengah Vertikal */
        justify-content: center; /* Tengah Horizontal */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Overlay gelap di atas gambar background agar tidak terlalu silau */
    body::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.4); /* Gelapkan background 40% */
        z-index: -1;
    }

    /* --- 3. Kartu Login (Perbaikan Transparansi) --- */
    .login-card {
        width: 100%;
        max-width: 400px;
        /* Hapus margin bawaan bootstrap agar flexbox body bekerja */
        margin: 0 auto !important; 
        
        /* WARNA BACKGROUND: Putih dengan opasitas 85% (0.85). 
           Tidak terlalu bening, tulisan jadi jelas. */
        background-color: rgba(255, 255, 255, 0.85) !important;
        
        /* Efek Blur di belakang kaca (Frosted Glass) */
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);

        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px !important;
        box-shadow: 0 20px 40px rgba(0,0,0, 0.3) !important;
    }

    .card-body {
        padding: 2.5rem 2rem !important;
    }

    /* --- 4. Teks & Input --- */
    .login-card h4 {
        color: var(--simbeta-raisin);
        font-weight: 800;
    }

    .login-card .text-muted {
        color: var(--simbeta-blush) !important;
        font-weight: 600;
    }

    .form-label {
        color: var(--simbeta-raisin);
        font-weight: 600;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 12px;
    }

    .form-control:focus {
        background: #fff;
        border-color: var(--simbeta-blush);
        box-shadow: 0 0 0 4px rgba(193, 108, 138, 0.2);
    }

    /* --- 5. Tombol --- */
    .btn-primary {
        /* Gradient agar lebih mewah */
        background: linear-gradient(45deg, var(--simbeta-raisin), #3a363a);
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, var(--simbeta-blush), var(--simbeta-orchid));
        box-shadow: 0 5px 15px rgba(193, 108, 138, 0.4);
        transform: translateY(-2px);
    }
    
    /* Override Container Bootstrap agar tidak mengganggu centering */
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }
</style>
</head>
<body>

<div class="container">
    <div class="card shadow-lg login-card">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h4>Login SIMBETA</h4>
                <p class="text-muted">Manajemen Informatika</p>
            </div>

            <?php if(session()->getFlashdata('msg')):?>
                <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
            <?php endif;?>

            <form action="<?= base_url('login/auth') ?>" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>