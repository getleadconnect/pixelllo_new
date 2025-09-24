<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Register - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <style>
        :root {
            --primary: #ffdd00;
            --secondary: #ff9900;
            --accent: #ff5500;
            --dark: #333333;
            --light: #f8f9fa;
            --gray: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --info: #17a2b8;
            --warning: #ffc107;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            width: 100%;
            max-width: 1368px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header Styles */
        .header {
            background-color: var(--primary);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            text-decoration: none;
        }
        
        /* Form Styles */
        .auth-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .auth-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .form-group {
            margin-bottom: 10px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 2px rgba(255, 153, 0, 0.2);
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: var(--secondary);
            color: white;
            width: 100%;
            padding: 12px;
            font-size: 1rem;
        }
        
        .btn-primary:hover {
            background-color: var(--accent);
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 20px;
        }
        
        .auth-footer a {
            color: var(--secondary);
            text-decoration: none;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            color: var(--danger);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        /* Profile Image Upload Styles */
        .profile-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-image-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
            margin-bottom: 10px;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .profile-image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-image-preview .placeholder-icon {
            font-size: 48px;
            color: #999;
        }

        .profile-upload-btn {
            display: inline-block;
            padding: 8px 16px;
            background: var(--secondary);
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.3s;
        }

        .profile-upload-btn:hover {
            background: var(--accent);
        }

        .profile-upload-input {
            display: none;
        }

        .image-size-note {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-container {
                margin: 30px 15px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="{{ route('home') }}" class="logo">Pixelllo</a>
            </div>
        </div>
    </header>
    
    <!-- Registration Form -->
    <div class="container">
        <div class="auth-container">
            <h1 class="auth-title">Create Your Account</h1>
            
            @if ($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ url('/register') }}" enctype="multipart/form-data">
                @csrf
                <!-- Using url() helper to include the base path -->

                <!-- Profile Image Upload -->
                <div class="profile-upload-container" style="display:flex;">
                    <div class="profile-image-preview" id="imagePreview">
                        <i class="fas fa-user placeholder-icon"></i>
                    </div>

                    <div>
                    <label for="profile_image" class="profile-upload-btn">
                        <i class="fas fa-camera"></i> Choose Profile Picture
                    </label>
                    
                        <input type="file" id="profile_image" name="profile_image" class="profile-upload-input" accept="image/jpeg,image/jpg,image/png,image/gif">
                        <div class="image-size-note">Max size: 2MB (JPG, PNG, GIF)</div>
                        @error('profile_image')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" type="text" class="form-input" name="name" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-input" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number (Optional)</label>
                    <input id="phone" type="text" class="form-input" name="phone" value="{{ old('phone') }}">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city" class="form-label">City</label>
                        <input id="city" type="text" class="form-input" name="city" value="{{ old('city') }}" placeholder="e.g., New York">
                    </div>
                    <div class="form-group">
                        <label for="country" class="form-label">Country</label>
                        <select id="country" class="form-input" name="country">
                            <option value="">Select Country</option>
                            <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States</option>
                            <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                            <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                            <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>Australia</option>
                            <option value="DE" {{ old('country') == 'DE' ? 'selected' : '' }}>Germany</option>
                            <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                            <option value="IT" {{ old('country') == 'IT' ? 'selected' : '' }}>Italy</option>
                            <option value="ES" {{ old('country') == 'ES' ? 'selected' : '' }}>Spain</option>
                            <option value="NL" {{ old('country') == 'NL' ? 'selected' : '' }}>Netherlands</option>
                            <option value="JP" {{ old('country') == 'JP' ? 'selected' : '' }}>Japan</option>
                            <option value="CN" {{ old('country') == 'CN' ? 'selected' : '' }}>China</option>
                            <option value="IN" {{ old('country') == 'IN' ? 'selected' : '' }}>India</option>
                            <option value="BR" {{ old('country') == 'BR' ? 'selected' : '' }}>Brazil</option>
                            <option value="MX" {{ old('country') == 'MX' ? 'selected' : '' }}>Mexico</option>
                            <option value="ZA" {{ old('country') == 'ZA' ? 'selected' : '' }}>South Africa</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-input" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" type="password" class="form-input" name="password_confirmation" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        Register
                    </button>
                </div>
            </form>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </div>
    </div>

    <script>
        // Profile Image Preview
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');

            if (file) {
                // Check file size (2MB = 2097152 bytes)
                if (file.size > 2097152) {
                    alert('File size must be less than 2MB');
                    e.target.value = '';
                    return;
                }

                // Check file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, PNG, or GIF)');
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Profile Preview">';
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<i class="fas fa-user placeholder-icon"></i>';
            }
        });
    </script>
</body>
</html>