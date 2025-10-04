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
                            <option value="AF" {{ old('country') == 'AF' ? 'selected' : '' }}>Afghanistan</option>
                            <option value="AL" {{ old('country') == 'AL' ? 'selected' : '' }}>Albania</option>
                            <option value="DZ" {{ old('country') == 'DZ' ? 'selected' : '' }}>Algeria</option>
                            <option value="AD" {{ old('country') == 'AD' ? 'selected' : '' }}>Andorra</option>
                            <option value="AO" {{ old('country') == 'AO' ? 'selected' : '' }}>Angola</option>
                            <option value="AG" {{ old('country') == 'AG' ? 'selected' : '' }}>Antigua and Barbuda</option>
                            <option value="AR" {{ old('country') == 'AR' ? 'selected' : '' }}>Argentina</option>
                            <option value="AM" {{ old('country') == 'AM' ? 'selected' : '' }}>Armenia</option>
                            <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>Australia</option>
                            <option value="AT" {{ old('country') == 'AT' ? 'selected' : '' }}>Austria</option>
                            <option value="AZ" {{ old('country') == 'AZ' ? 'selected' : '' }}>Azerbaijan</option>
                            <option value="BS" {{ old('country') == 'BS' ? 'selected' : '' }}>Bahamas</option>
                            <option value="BH" {{ old('country') == 'BH' ? 'selected' : '' }}>Bahrain</option>
                            <option value="BD" {{ old('country') == 'BD' ? 'selected' : '' }}>Bangladesh</option>
                            <option value="BB" {{ old('country') == 'BB' ? 'selected' : '' }}>Barbados</option>
                            <option value="BY" {{ old('country') == 'BY' ? 'selected' : '' }}>Belarus</option>
                            <option value="BE" {{ old('country') == 'BE' ? 'selected' : '' }}>Belgium</option>
                            <option value="BZ" {{ old('country') == 'BZ' ? 'selected' : '' }}>Belize</option>
                            <option value="BJ" {{ old('country') == 'BJ' ? 'selected' : '' }}>Benin</option>
                            <option value="BT" {{ old('country') == 'BT' ? 'selected' : '' }}>Bhutan</option>
                            <option value="BO" {{ old('country') == 'BO' ? 'selected' : '' }}>Bolivia</option>
                            <option value="BA" {{ old('country') == 'BA' ? 'selected' : '' }}>Bosnia and Herzegovina</option>
                            <option value="BW" {{ old('country') == 'BW' ? 'selected' : '' }}>Botswana</option>
                            <option value="BR" {{ old('country') == 'BR' ? 'selected' : '' }}>Brazil</option>
                            <option value="BN" {{ old('country') == 'BN' ? 'selected' : '' }}>Brunei</option>
                            <option value="BG" {{ old('country') == 'BG' ? 'selected' : '' }}>Bulgaria</option>
                            <option value="BF" {{ old('country') == 'BF' ? 'selected' : '' }}>Burkina Faso</option>
                            <option value="BI" {{ old('country') == 'BI' ? 'selected' : '' }}>Burundi</option>
                            <option value="KH" {{ old('country') == 'KH' ? 'selected' : '' }}>Cambodia</option>
                            <option value="CM" {{ old('country') == 'CM' ? 'selected' : '' }}>Cameroon</option>
                            <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                            <option value="CV" {{ old('country') == 'CV' ? 'selected' : '' }}>Cape Verde</option>
                            <option value="CF" {{ old('country') == 'CF' ? 'selected' : '' }}>Central African Republic</option>
                            <option value="TD" {{ old('country') == 'TD' ? 'selected' : '' }}>Chad</option>
                            <option value="CL" {{ old('country') == 'CL' ? 'selected' : '' }}>Chile</option>
                            <option value="CN" {{ old('country') == 'CN' ? 'selected' : '' }}>China</option>
                            <option value="CO" {{ old('country') == 'CO' ? 'selected' : '' }}>Colombia</option>
                            <option value="KM" {{ old('country') == 'KM' ? 'selected' : '' }}>Comoros</option>
                            <option value="CG" {{ old('country') == 'CG' ? 'selected' : '' }}>Congo</option>
                            <option value="CR" {{ old('country') == 'CR' ? 'selected' : '' }}>Costa Rica</option>
                            <option value="HR" {{ old('country') == 'HR' ? 'selected' : '' }}>Croatia</option>
                            <option value="CU" {{ old('country') == 'CU' ? 'selected' : '' }}>Cuba</option>
                            <option value="CY" {{ old('country') == 'CY' ? 'selected' : '' }}>Cyprus</option>
                            <option value="CZ" {{ old('country') == 'CZ' ? 'selected' : '' }}>Czech Republic</option>
                            <option value="DK" {{ old('country') == 'DK' ? 'selected' : '' }}>Denmark</option>
                            <option value="DJ" {{ old('country') == 'DJ' ? 'selected' : '' }}>Djibouti</option>
                            <option value="DM" {{ old('country') == 'DM' ? 'selected' : '' }}>Dominica</option>
                            <option value="DO" {{ old('country') == 'DO' ? 'selected' : '' }}>Dominican Republic</option>
                            <option value="EC" {{ old('country') == 'EC' ? 'selected' : '' }}>Ecuador</option>
                            <option value="EG" {{ old('country') == 'EG' ? 'selected' : '' }}>Egypt</option>
                            <option value="SV" {{ old('country') == 'SV' ? 'selected' : '' }}>El Salvador</option>
                            <option value="GQ" {{ old('country') == 'GQ' ? 'selected' : '' }}>Equatorial Guinea</option>
                            <option value="ER" {{ old('country') == 'ER' ? 'selected' : '' }}>Eritrea</option>
                            <option value="EE" {{ old('country') == 'EE' ? 'selected' : '' }}>Estonia</option>
                            <option value="ET" {{ old('country') == 'ET' ? 'selected' : '' }}>Ethiopia</option>
                            <option value="FJ" {{ old('country') == 'FJ' ? 'selected' : '' }}>Fiji</option>
                            <option value="FI" {{ old('country') == 'FI' ? 'selected' : '' }}>Finland</option>
                            <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                            <option value="GA" {{ old('country') == 'GA' ? 'selected' : '' }}>Gabon</option>
                            <option value="GM" {{ old('country') == 'GM' ? 'selected' : '' }}>Gambia</option>
                            <option value="GE" {{ old('country') == 'GE' ? 'selected' : '' }}>Georgia</option>
                            <option value="DE" {{ old('country') == 'DE' ? 'selected' : '' }}>Germany</option>
                            <option value="GH" {{ old('country') == 'GH' ? 'selected' : '' }}>Ghana</option>
                            <option value="GR" {{ old('country') == 'GR' ? 'selected' : '' }}>Greece</option>
                            <option value="GD" {{ old('country') == 'GD' ? 'selected' : '' }}>Grenada</option>
                            <option value="GT" {{ old('country') == 'GT' ? 'selected' : '' }}>Guatemala</option>
                            <option value="GN" {{ old('country') == 'GN' ? 'selected' : '' }}>Guinea</option>
                            <option value="GW" {{ old('country') == 'GW' ? 'selected' : '' }}>Guinea-Bissau</option>
                            <option value="GY" {{ old('country') == 'GY' ? 'selected' : '' }}>Guyana</option>
                            <option value="HT" {{ old('country') == 'HT' ? 'selected' : '' }}>Haiti</option>
                            <option value="HN" {{ old('country') == 'HN' ? 'selected' : '' }}>Honduras</option>
                            <option value="HU" {{ old('country') == 'HU' ? 'selected' : '' }}>Hungary</option>
                            <option value="IS" {{ old('country') == 'IS' ? 'selected' : '' }}>Iceland</option>
                            <option value="IN" {{ old('country') == 'IN' ? 'selected' : '' }}>India</option>
                            <option value="ID" {{ old('country') == 'ID' ? 'selected' : '' }}>Indonesia</option>
                            <option value="IR" {{ old('country') == 'IR' ? 'selected' : '' }}>Iran</option>
                            <option value="IQ" {{ old('country') == 'IQ' ? 'selected' : '' }}>Iraq</option>
                            <option value="IE" {{ old('country') == 'IE' ? 'selected' : '' }}>Ireland</option>
                            <option value="IL" {{ old('country') == 'IL' ? 'selected' : '' }}>Israel</option>
                            <option value="IT" {{ old('country') == 'IT' ? 'selected' : '' }}>Italy</option>
                            <option value="JM" {{ old('country') == 'JM' ? 'selected' : '' }}>Jamaica</option>
                            <option value="JP" {{ old('country') == 'JP' ? 'selected' : '' }}>Japan</option>
                            <option value="JO" {{ old('country') == 'JO' ? 'selected' : '' }}>Jordan</option>
                            <option value="KZ" {{ old('country') == 'KZ' ? 'selected' : '' }}>Kazakhstan</option>
                            <option value="KE" {{ old('country') == 'KE' ? 'selected' : '' }}>Kenya</option>
                            <option value="KI" {{ old('country') == 'KI' ? 'selected' : '' }}>Kiribati</option>
                            <option value="KP" {{ old('country') == 'KP' ? 'selected' : '' }}>North Korea</option>
                            <option value="KR" {{ old('country') == 'KR' ? 'selected' : '' }}>South Korea</option>
                            <option value="KW" {{ old('country') == 'KW' ? 'selected' : '' }}>Kuwait</option>
                            <option value="KG" {{ old('country') == 'KG' ? 'selected' : '' }}>Kyrgyzstan</option>
                            <option value="LA" {{ old('country') == 'LA' ? 'selected' : '' }}>Laos</option>
                            <option value="LV" {{ old('country') == 'LV' ? 'selected' : '' }}>Latvia</option>
                            <option value="LB" {{ old('country') == 'LB' ? 'selected' : '' }}>Lebanon</option>
                            <option value="LS" {{ old('country') == 'LS' ? 'selected' : '' }}>Lesotho</option>
                            <option value="LR" {{ old('country') == 'LR' ? 'selected' : '' }}>Liberia</option>
                            <option value="LY" {{ old('country') == 'LY' ? 'selected' : '' }}>Libya</option>
                            <option value="LI" {{ old('country') == 'LI' ? 'selected' : '' }}>Liechtenstein</option>
                            <option value="LT" {{ old('country') == 'LT' ? 'selected' : '' }}>Lithuania</option>
                            <option value="LU" {{ old('country') == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                            <option value="MK" {{ old('country') == 'MK' ? 'selected' : '' }}>North Macedonia</option>
                            <option value="MG" {{ old('country') == 'MG' ? 'selected' : '' }}>Madagascar</option>
                            <option value="MW" {{ old('country') == 'MW' ? 'selected' : '' }}>Malawi</option>
                            <option value="MY" {{ old('country') == 'MY' ? 'selected' : '' }}>Malaysia</option>
                            <option value="MV" {{ old('country') == 'MV' ? 'selected' : '' }}>Maldives</option>
                            <option value="ML" {{ old('country') == 'ML' ? 'selected' : '' }}>Mali</option>
                            <option value="MT" {{ old('country') == 'MT' ? 'selected' : '' }}>Malta</option>
                            <option value="MH" {{ old('country') == 'MH' ? 'selected' : '' }}>Marshall Islands</option>
                            <option value="MR" {{ old('country') == 'MR' ? 'selected' : '' }}>Mauritania</option>
                            <option value="MU" {{ old('country') == 'MU' ? 'selected' : '' }}>Mauritius</option>
                            <option value="MX" {{ old('country') == 'MX' ? 'selected' : '' }}>Mexico</option>
                            <option value="FM" {{ old('country') == 'FM' ? 'selected' : '' }}>Micronesia</option>
                            <option value="MD" {{ old('country') == 'MD' ? 'selected' : '' }}>Moldova</option>
                            <option value="MC" {{ old('country') == 'MC' ? 'selected' : '' }}>Monaco</option>
                            <option value="MN" {{ old('country') == 'MN' ? 'selected' : '' }}>Mongolia</option>
                            <option value="ME" {{ old('country') == 'ME' ? 'selected' : '' }}>Montenegro</option>
                            <option value="MA" {{ old('country') == 'MA' ? 'selected' : '' }}>Morocco</option>
                            <option value="MZ" {{ old('country') == 'MZ' ? 'selected' : '' }}>Mozambique</option>
                            <option value="MM" {{ old('country') == 'MM' ? 'selected' : '' }}>Myanmar</option>
                            <option value="NA" {{ old('country') == 'NA' ? 'selected' : '' }}>Namibia</option>
                            <option value="NR" {{ old('country') == 'NR' ? 'selected' : '' }}>Nauru</option>
                            <option value="NP" {{ old('country') == 'NP' ? 'selected' : '' }}>Nepal</option>
                            <option value="NL" {{ old('country') == 'NL' ? 'selected' : '' }}>Netherlands</option>
                            <option value="NZ" {{ old('country') == 'NZ' ? 'selected' : '' }}>New Zealand</option>
                            <option value="NI" {{ old('country') == 'NI' ? 'selected' : '' }}>Nicaragua</option>
                            <option value="NE" {{ old('country') == 'NE' ? 'selected' : '' }}>Niger</option>
                            <option value="NG" {{ old('country') == 'NG' ? 'selected' : '' }}>Nigeria</option>
                            <option value="NO" {{ old('country') == 'NO' ? 'selected' : '' }}>Norway</option>
                            <option value="OM" {{ old('country') == 'OM' ? 'selected' : '' }}>Oman</option>
                            <option value="PK" {{ old('country') == 'PK' ? 'selected' : '' }}>Pakistan</option>
                            <option value="PW" {{ old('country') == 'PW' ? 'selected' : '' }}>Palau</option>
                            <option value="PA" {{ old('country') == 'PA' ? 'selected' : '' }}>Panama</option>
                            <option value="PG" {{ old('country') == 'PG' ? 'selected' : '' }}>Papua New Guinea</option>
                            <option value="PY" {{ old('country') == 'PY' ? 'selected' : '' }}>Paraguay</option>
                            <option value="PE" {{ old('country') == 'PE' ? 'selected' : '' }}>Peru</option>
                            <option value="PH" {{ old('country') == 'PH' ? 'selected' : '' }}>Philippines</option>
                            <option value="PL" {{ old('country') == 'PL' ? 'selected' : '' }}>Poland</option>
                            <option value="PT" {{ old('country') == 'PT' ? 'selected' : '' }}>Portugal</option>
                            <option value="QA" {{ old('country') == 'QA' ? 'selected' : '' }}>Qatar</option>
                            <option value="RO" {{ old('country') == 'RO' ? 'selected' : '' }}>Romania</option>
                            <option value="RU" {{ old('country') == 'RU' ? 'selected' : '' }}>Russia</option>
                            <option value="RW" {{ old('country') == 'RW' ? 'selected' : '' }}>Rwanda</option>
                            <option value="KN" {{ old('country') == 'KN' ? 'selected' : '' }}>Saint Kitts and Nevis</option>
                            <option value="LC" {{ old('country') == 'LC' ? 'selected' : '' }}>Saint Lucia</option>
                            <option value="VC" {{ old('country') == 'VC' ? 'selected' : '' }}>Saint Vincent and the Grenadines</option>
                            <option value="WS" {{ old('country') == 'WS' ? 'selected' : '' }}>Samoa</option>
                            <option value="SM" {{ old('country') == 'SM' ? 'selected' : '' }}>San Marino</option>
                            <option value="ST" {{ old('country') == 'ST' ? 'selected' : '' }}>Sao Tome and Principe</option>
                            <option value="SA" {{ old('country') == 'SA' ? 'selected' : '' }}>Saudi Arabia</option>
                            <option value="SN" {{ old('country') == 'SN' ? 'selected' : '' }}>Senegal</option>
                            <option value="RS" {{ old('country') == 'RS' ? 'selected' : '' }}>Serbia</option>
                            <option value="SC" {{ old('country') == 'SC' ? 'selected' : '' }}>Seychelles</option>
                            <option value="SL" {{ old('country') == 'SL' ? 'selected' : '' }}>Sierra Leone</option>
                            <option value="SG" {{ old('country') == 'SG' ? 'selected' : '' }}>Singapore</option>
                            <option value="SK" {{ old('country') == 'SK' ? 'selected' : '' }}>Slovakia</option>
                            <option value="SI" {{ old('country') == 'SI' ? 'selected' : '' }}>Slovenia</option>
                            <option value="SB" {{ old('country') == 'SB' ? 'selected' : '' }}>Solomon Islands</option>
                            <option value="SO" {{ old('country') == 'SO' ? 'selected' : '' }}>Somalia</option>
                            <option value="ZA" {{ old('country') == 'ZA' ? 'selected' : '' }}>South Africa</option>
                            <option value="SS" {{ old('country') == 'SS' ? 'selected' : '' }}>South Sudan</option>
                            <option value="ES" {{ old('country') == 'ES' ? 'selected' : '' }}>Spain</option>
                            <option value="LK" {{ old('country') == 'LK' ? 'selected' : '' }}>Sri Lanka</option>
                            <option value="SD" {{ old('country') == 'SD' ? 'selected' : '' }}>Sudan</option>
                            <option value="SR" {{ old('country') == 'SR' ? 'selected' : '' }}>Suriname</option>
                            <option value="SZ" {{ old('country') == 'SZ' ? 'selected' : '' }}>Swaziland</option>
                            <option value="SE" {{ old('country') == 'SE' ? 'selected' : '' }}>Sweden</option>
                            <option value="CH" {{ old('country') == 'CH' ? 'selected' : '' }}>Switzerland</option>
                            <option value="SY" {{ old('country') == 'SY' ? 'selected' : '' }}>Syria</option>
                            <option value="TW" {{ old('country') == 'TW' ? 'selected' : '' }}>Taiwan</option>
                            <option value="TJ" {{ old('country') == 'TJ' ? 'selected' : '' }}>Tajikistan</option>
                            <option value="TZ" {{ old('country') == 'TZ' ? 'selected' : '' }}>Tanzania</option>
                            <option value="TH" {{ old('country') == 'TH' ? 'selected' : '' }}>Thailand</option>
                            <option value="TL" {{ old('country') == 'TL' ? 'selected' : '' }}>Timor-Leste</option>
                            <option value="TG" {{ old('country') == 'TG' ? 'selected' : '' }}>Togo</option>
                            <option value="TO" {{ old('country') == 'TO' ? 'selected' : '' }}>Tonga</option>
                            <option value="TT" {{ old('country') == 'TT' ? 'selected' : '' }}>Trinidad and Tobago</option>
                            <option value="TN" {{ old('country') == 'TN' ? 'selected' : '' }}>Tunisia</option>
                            <option value="TR" {{ old('country') == 'TR' ? 'selected' : '' }}>Turkey</option>
                            <option value="TM" {{ old('country') == 'TM' ? 'selected' : '' }}>Turkmenistan</option>
                            <option value="TV" {{ old('country') == 'TV' ? 'selected' : '' }}>Tuvalu</option>
                            <option value="UG" {{ old('country') == 'UG' ? 'selected' : '' }}>Uganda</option>
                            <option value="UA" {{ old('country') == 'UA' ? 'selected' : '' }}>Ukraine</option>
                            <option value="AE" {{ old('country') == 'AE' ? 'selected' : '' }}>United Arab Emirates</option>
                            <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                            <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States</option>
                            <option value="UY" {{ old('country') == 'UY' ? 'selected' : '' }}>Uruguay</option>
                            <option value="UZ" {{ old('country') == 'UZ' ? 'selected' : '' }}>Uzbekistan</option>
                            <option value="VU" {{ old('country') == 'VU' ? 'selected' : '' }}>Vanuatu</option>
                            <option value="VA" {{ old('country') == 'VA' ? 'selected' : '' }}>Vatican City</option>
                            <option value="VE" {{ old('country') == 'VE' ? 'selected' : '' }}>Venezuela</option>
                            <option value="VN" {{ old('country') == 'VN' ? 'selected' : '' }}>Vietnam</option>
                            <option value="YE" {{ old('country') == 'YE' ? 'selected' : '' }}>Yemen</option>
                            <option value="ZM" {{ old('country') == 'ZM' ? 'selected' : '' }}>Zambia</option>
                            <option value="ZW" {{ old('country') == 'ZW' ? 'selected' : '' }}>Zimbabwe</option>
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