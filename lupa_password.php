<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SIAKAD</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background-color: #e0e7ff; /* Tailwind primary light */
            color: #4f46e5; /* Tailwind primary */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .btn-custom {
            background-color: #4f46e5;
            border-color: #4f46e5;
            color: white;
        }
        .btn-custom:hover {
            background-color: #4338ca;
            border-color: #3730a3;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="card p-4 text-center">
                <div class="card-body">
                    <div class="icon-circle">
                        <i class="fas fa-lock fa-3x"></i>
                    </div>
                    <h3 class="card-title fw-bold mb-3">Lupa Password?</h3>
                    
                    <div class="alert alert-warning text-start d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <strong>Perhatian:</strong> Karena semua akun diatur secara otomatis oleh sistem, Anda tidak dapat mereset password sendiri (misalnya melalui email).
                        </div>
                    </div>
                    
                    <p class="text-muted mb-4 text-start">
                        Jika Anda lupa password, silakan hubungi <strong>Admin</strong> atau <strong>Wali Kelas</strong> untuk melakukan reset password Anda.
                    </p>
                    
                    <a href="index.php" class="btn btn-custom w-100 py-2 rounded-pill shadow-sm transition">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
