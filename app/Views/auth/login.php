<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMBETA Polsri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- 1. RESET & LAYOUT TENGAH --- */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6; /* Warna background halaman */
        }

        body {
            /* KUNCI AGAR POSISI BENAR-BENAR DI TENGAH */
            display: flex;
            align-items: center;      /* Tengah Vertikal */
            justify-content: center;  /* Tengah Horizontal */
        }

        /* --- 2. KARTU LOGIN --- */
        .login-card {
            width: 100%;
            max-width: 900px; /* Lebar maksimal kartu */
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: white;
            margin: 20px; /* Jarak aman di layar HP */
        }

        /* --- 3. SISI KIRI (GRADIENT) --- */
        .login-left-side {
            background: linear-gradient(135deg, #F4B8CA 0%, #fbc2eb 100%); /* Warna Ungu Soft ke Pink */
            /* Alternatif Warna sesuai gambar referensi: */
            background: linear-gradient(135deg, #C16C8A 0%, #e99c6e 100%);
            
            position: relative;
            min-height: 500px; /* Tinggi minimum */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            color: white;
        }
        
        /* Hiasan background abstrak (Opsional) */
        .login-left-side::before {
            content: "";
            position: absolute;
            width: 100%; height: 100%;
            background: url('https://www.transparenttextures.com/patterns/cubes.png'); /* Tekstur halus */
            opacity: 0.1;
        }

        .welcome-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 10px;
        }
        
        .brand-label {
            position: absolute;
            top: 25px;
            left: 30px;
            font-weight: 700;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* --- 4. SISI KANAN (FORM) --- */
        .login-right-side {
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .text-muted-custom {
            color: #aaa;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-control {
            height: 50px;
            border-radius: 8px;
            border: 1px solid #e1e1e1;
            padding-left: 15px;
            background-color: #fcfcfc;
        }

        .form-control:focus {
            border-color: #C16C8A;
            box-shadow: none;
            background-color: #fff;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
            color: #666;
        }

        .btn-primary-custom {
            background-color: #C16C8A; /* Tombol Ungu */
            border: none;
            height: 50px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 10px;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            background-color: #C16C8A;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(234, 113, 236, 0.3);
        }

        .forgot-pass {
            font-size: 0.85rem;
            color: #C16C8A;
            text-decoration: none;
        }

        .signup-text {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9rem;
            color: #888;
        }
        
        .signup-text a {
            color: #C16C8A  ;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="row g-0">
            
            <div class="col-md-6 login-left-side">
                <div class="brand-label">
                    <i class="bi bi-triangle-fill"></i> SIMBETA
                </div>
                <div class="welcome-text">
                    <h1>Welcome<br>Back!</h1>
                </div>
            </div>

            <div class="col-md-6 login-right-side">
                <h2 class="login-title">Login</h2>
                <p class="text-muted-custom">Welcome back! Please login to your account.</p>

                <?php if(session()->getFlashdata('msg')):?>
                    <div class="alert alert-danger py-2" style="font-size: 0.9rem;">
                        <?= session()->getFlashdata('msg') ?>
                    </div>
                <?php endif;?>

                <form action="<?= base_url('login/auth') ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">User Name</label>
                        <input type="text" name="username" class="form-control" placeholder="username@gmail.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="********" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe" style="font-size: 0.85rem; color: #666;">
                                Remember Me
                            </label>
                        </div>
                        <a href="#" class="forgot-pass">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-primary-custom">Login</button>
                    
                    <div class="signup-text">
                        New User? <a href="#">Signup</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</body>
</html>