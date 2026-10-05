<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk / Daftar - Sweet Dreams</title>
    <meta name="description" content="Masuk ke akun Sweet Dreams Anda untuk pengalaman belanja pakaian tidur premium yang personal.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300;1,9..40,400&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --ink:        #2a1f22;
            --ink-muted:  #6e5a60;
            --ink-faint:  #a8939a;
            --blush:      #c97a8c;
            --blush-dark: #a85e72;
            --blush-pale: #f2dce3;
            --bg:         #faf8f6;
            --bg-warm:    #f4ede9;
            --white:      #ffffff;
            --border:     rgba(180,140,150,0.18);
            --shadow-sm:  0 1px 4px rgba(42,31,34,0.06);
            --shadow-md:  0 4px 20px rgba(42,31,34,0.09);
            --shadow-lg:  0 12px 48px rgba(42,31,34,0.12);
            --radius:     12px;
            --radius-lg:  20px;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f4ecee;
            background-image: 
                radial-gradient(at 0% 0%, rgba(244, 141, 168, 0.18) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(201,122,140, 0.12) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
            color: var(--ink);
        }

        /* Top Bar Navigation */
        .auth-top-bar {
            width: 100%;
            max-width: 960px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-back-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: var(--ink-muted);
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid rgba(244, 141, 168, 0.2);
        }

        .btn-back-home:hover {
            color: var(--blush);
            background: #ffffff;
            transform: translateX(-3px);
            box-shadow: 0 4px 12px rgba(201,122,140, 0.1);
        }

        /* Main Auth Card */
        .auth-card-container {
            width: 100%;
            max-width: 960px;
            background: #ffffff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(42,31,34, 0.12), 0 4px 20px rgba(201,122,140, 0.06);
            display: grid;
            grid-template-columns: 1fr 1.08fr;
            min-height: 590px;
            transition: all 0.3s ease;
        }

        /* ===== LEFT COLUMN: HERO AMBIENCE & QUOTE ===== */
        .auth-hero-col {
            position: relative;
            background: url('{{ asset("images/auth-hero.jpg") }}') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem 2.5rem;
            color: #ffffff;
            overflow: hidden;
        }

        .auth-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                180deg,
                rgba(35, 20, 26, 0.1) 0%,
                rgba(35, 20, 26, 0.45) 45%,
                rgba(35, 20, 26, 0.88) 100%
            );
            z-index: 1;
        }

        .auth-hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-tag {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.92);
            margin-bottom: 0.75rem;
        }

        .hero-quote {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.65rem;
            font-weight: 500;
            line-height: 1.35;
            color: #ffffff;
            margin-bottom: 1.25rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
        }

        .hero-club {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.75);
            letter-spacing: 0.04em;
        }

        /* ===== RIGHT COLUMN: FORM BOX ===== */
        .auth-form-col {
            padding: 3.25rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-brand-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.15rem;
            font-weight: 700;
            color: #8B263E;
            margin: 0 0 0.35rem 0;
            letter-spacing: -0.01em;
        }

        .auth-subtitle {
            font-size: 0.88rem;
            color: #7a5f67;
            margin: 0;
        }

        /* Form Inputs */
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 1.15rem;
        }

        .auth-field-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .auth-field-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--ink);
        }

        .auth-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .auth-input-icon {
            position: absolute;
            left: 1rem;
            color: var(--ink-muted);
            width: 18px;
            height: 18px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .auth-input-field {
            width: 100%;
            height: 48px;
            padding: 0 1rem 0 2.85rem;
            border: 1.5px solid #e8d5da;
            border-radius: 12px;
            background: #ffffff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            color: var(--ink);
            outline: none;
            transition: all 0.25s ease;
        }

        .auth-input-field::placeholder {
            color: #b49aa2;
        }

        .auth-input-field:focus {
            border-color: var(--blush);
            box-shadow: 0 0 0 3px rgba(201,122,140, 0.1);
        }

        .auth-input-field:focus + .auth-input-icon,
        .auth-input-wrapper:focus-within .auth-input-icon {
            color: var(--blush);
        }

        .auth-input-field.is-invalid {
            border-color: #f43f5e !important;
            background: #fff8f9;
        }

        /* Password Toggle Button */
        .btn-toggle-pwd {
            position: absolute;
            right: 0.85rem;
            background: transparent;
            border: none;
            color: var(--ink-muted);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .btn-toggle-pwd:hover {
            color: var(--blush);
        }

        /* Meta Row: Remember me & Forgot Password */
        .auth-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.25rem;
            font-size: 0.82rem;
        }

        .remember-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--ink-muted);
            cursor: pointer;
            user-select: none;
        }

        .remember-label input[type="checkbox"] {
            accent-color: #eb708e;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .link-forgot {
            color: #eb708e;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .link-forgot:hover {
            color: #ba3b5d;
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-auth-submit {
            width: 100%;
            height: 48px;
            margin-top: 0.5rem;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #f28da5 0%, #eb708e 100%);
            color: #ffffff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(235, 112, 142, 0.35);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-auth-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 26px rgba(235, 112, 142, 0.45);
            background: linear-gradient(135deg, #eb708e 0%, var(--blush) 100%);
        }

        .btn-auth-submit:active {
            transform: translateY(0);
        }

        /* Admin Shortcut Link */
        .admin-link-box {
            text-align: center;
            margin-top: 0.85rem;
        }

        .link-admin {
            color: var(--ink-muted);
            font-size: 0.82rem;
            text-decoration: underline;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.2s;
            background: none;
            border: none;
        }

        .link-admin:hover {
            color: var(--blush);
        }

        /* Switch Mode (Register / Login) */
        .auth-switch-box {
            text-align: center;
            margin-top: 1.75rem;
            font-size: 0.85rem;
            color: #6a4a52;
        }

        .link-switch {
            color: #eb708e;
            font-weight: 600;
            text-decoration: none;
            margin-left: 0.25rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .link-switch:hover {
            color: #ba3b5d;
            text-decoration: underline;
        }

        /* Registration Form (Initially Hidden if in login mode) */
        .auth-section-view {
            display: none;
            animation: fadeInView 0.3s ease;
        }

        .auth-section-view.active {
            display: block;
        }

        @keyframes fadeInView {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Floating Alert Toast */
        .auth-toast-alert {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 1200;
            background: #ffffff;
            border-left: 4px solid #f43f5e;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(42,31,34, 0.16);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            max-width: 400px;
            transform: translateX(120%);
            opacity: 0;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }

        .auth-toast-alert.show {
            transform: translateX(0);
            opacity: 1;
            pointer-events: auto;
        }

        .auth-toast-alert.success {
            border-left-color: #10b981;
        }

        .auth-toast-alert.success .auth-toast-icon {
            background: #ecfdf5;
            color: #10b981;
        }

        .auth-toast-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #fff1f2;
            color: #f43f5e;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .auth-toast-content h4 {
            margin: 0 0 0.2rem 0;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--ink);
        }

        .auth-toast-content p {
            margin: 0;
            font-size: 0.82rem;
            color: #6a4a52;
            line-height: 1.4;
        }

        /* Register Avatar Selector */
        .reg-avatar-section {
            margin-bottom: 1.35rem;
            background: #fff8fa;
            border: 1.5px dashed var(--blush-pale);
            border-radius: var(--radius);
            padding: 1rem 1.15rem;
            text-align: center;
        }
        .reg-avatar-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            margin-bottom: 0.25rem;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--ink-muted);
        }
        .reg-avatar-subtitle {
            font-size: 0.76rem;
            color: var(--ink-muted);
            margin-bottom: 0.85rem;
        }
        .reg-avatar-preview-wrapper {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 0.85rem;
            position: relative;
        }
        .reg-avatar-preview-img {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 3px solid var(--blush);
            box-shadow: 0 4px 14px rgba(201,122,140, 0.22);
            object-fit: cover;
            background: #fff;
            transition: transform 0.25s ease;
        }
        .reg-avatar-preview-img:hover {
            transform: scale(1.05);
        }
        .reg-avatar-badge {
            margin-top: 0.35rem;
            background: var(--blush);
            color: #fff;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 8px;
            letter-spacing: 0.02em;
        }
        .reg-avatar-options-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.55rem;
            justify-content: center;
        }
        .reg-avatar-option-btn {
            background: #ffffff;
            border: 2px solid #fed7e2;
            border-radius: 14px;
            padding: 6px 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
        .reg-avatar-option-btn img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
        }
        .reg-avatar-option-btn span {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--ink-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }
        .reg-avatar-option-btn:hover {
            border-color: var(--blush);
            transform: translateY(-2px);
            background: #fff;
        }
        .reg-avatar-option-btn.selected {
            border-color: var(--blush);
            background: #fdf0f4;
            box-shadow: 0 0 0 3px rgba(201,122,140, 0.2);
            transform: translateY(-2px);
        }
        .reg-avatar-option-btn.selected span {
            color: var(--blush);
            font-weight: 700;
        }

        /* ===== FORGOT PASSWORD MODAL ===== */
        .fp-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 2000;
            background: rgba(35, 20, 26, 0.55);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .fp-modal-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .fp-modal-card {
            background: #ffffff;
            border-radius: 24px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 24px 80px rgba(42,31,34, 0.22), 0 4px 24px rgba(201,122,140, 0.1);
            overflow: hidden;
            transform: scale(0.92) translateY(20px);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .fp-modal-overlay.open .fp-modal-card {
            transform: scale(1) translateY(0);
        }

        .fp-modal-header {
            background: linear-gradient(135deg, #f9e6ec 0%, #fdf0f4 100%);
            padding: 2rem 2rem 1.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .fp-modal-close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(201,122,140, 0.15);
            color: var(--ink-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .fp-modal-close-btn:hover {
            background: #fff;
            color: var(--blush);
            transform: rotate(90deg);
        }

        .fp-modal-icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f28da5 0%, #eb708e 100%);
            margin: 0 auto 1rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(235, 112, 142, 0.3);
        }

        .fp-modal-icon-circle svg,
        .fp-modal-icon-circle i {
            color: #fff;
            width: 28px;
            height: 28px;
        }

        .fp-modal-header h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--ink);
            margin: 0 0 0.3rem 0;
        }

        .fp-modal-header p {
            font-size: 0.82rem;
            color: #7a5f67;
            margin: 0;
            line-height: 1.5;
        }

        /* Step Indicator */
        .fp-steps-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1.25rem 2rem 0.25rem 2rem;
        }

        .fp-step-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #e8d5da;
            transition: all 0.3s ease;
        }

        .fp-step-dot.active {
            background: linear-gradient(135deg, #f28da5, #eb708e);
            width: 28px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(235, 112, 142, 0.35);
        }

        .fp-step-dot.completed {
            background: #10b981;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .fp-step-connector {
            width: 24px;
            height: 2px;
            background: #e8d5da;
            border-radius: 2px;
            transition: background 0.3s ease;
        }

        .fp-step-connector.active {
            background: linear-gradient(90deg, #10b981, #eb708e);
        }

        /* Modal Body */
        .fp-modal-body {
            padding: 1.5rem 2rem 2rem 2rem;
        }

        .fp-step-panel {
            display: none;
            animation: fpFadeIn 0.35s ease;
        }

        .fp-step-panel.active {
            display: block;
        }

        @keyframes fpFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fp-field-group {
            margin-bottom: 1.15rem;
        }

        .fp-field-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.4rem;
        }

        .fp-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .fp-input-wrapper .fp-input-icon {
            position: absolute;
            left: 1rem;
            color: var(--ink-muted);
            width: 18px;
            height: 18px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .fp-input-field {
            width: 100%;
            height: 48px;
            padding: 0 1rem 0 2.85rem;
            border: 1.5px solid #e8d5da;
            border-radius: 12px;
            background: #ffffff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            color: var(--ink);
            outline: none;
            transition: all 0.25s ease;
        }

        .fp-input-field::placeholder {
            color: #b49aa2;
        }

        .fp-input-field:focus {
            border-color: var(--blush);
            box-shadow: 0 0 0 3px rgba(201,122,140, 0.1);
        }

        .fp-input-field:focus ~ .fp-input-icon,
        .fp-input-wrapper:focus-within .fp-input-icon {
            color: var(--blush);
        }

        .fp-input-field.is-invalid {
            border-color: #f43f5e !important;
            background: #fff8f9;
        }

        /* OTP / Verification Code Inputs */
        .fp-otp-group {
            display: flex;
            gap: 0.65rem;
            justify-content: center;
            margin: 1.25rem 0 1rem 0;
        }

        .fp-otp-input {
            width: 52px;
            height: 58px;
            border: 2px solid #e8d5da;
            border-radius: 14px;
            text-align: center;
            font-family: 'DM Sans', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--ink);
            outline: none;
            transition: all 0.25s ease;
            background: #fdfbfc;
        }

        .fp-otp-input:focus {
            border-color: var(--blush);
            box-shadow: 0 0 0 3px rgba(201,122,140, 0.15);
            background: #fff;
        }

        .fp-otp-input.filled {
            border-color: #10b981;
            background: #f0fdf4;
            color: #059669;
        }

        .fp-otp-info {
            text-align: center;
            font-size: 0.78rem;
            color: #7a5f67;
            margin-bottom: 0.75rem;
            line-height: 1.5;
        }

        .fp-otp-info strong {
            color: var(--blush);
            font-weight: 600;
        }

        .fp-otp-resend {
            text-align: center;
            margin-top: 0.5rem;
        }

        .fp-otp-resend button {
            background: none;
            border: none;
            color: var(--ink-muted);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.78rem;
            cursor: pointer;
            text-decoration: underline;
            transition: color 0.2s;
        }

        .fp-otp-resend button:hover {
            color: var(--blush);
        }

        .fp-otp-resend button:disabled {
            color: #c5b0b8;
            cursor: not-allowed;
            text-decoration: none;
        }

        /* Password Strength Meter */
        .fp-pwd-strength {
            display: flex;
            gap: 4px;
            margin-top: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .fp-pwd-strength-bar {
            height: 4px;
            flex: 1;
            border-radius: 4px;
            background: #e8d5da;
            transition: all 0.3s ease;
        }

        .fp-pwd-strength-bar.weak { background: #f43f5e; }
        .fp-pwd-strength-bar.medium { background: #f59e0b; }
        .fp-pwd-strength-bar.strong { background: #10b981; }

        .fp-pwd-strength-text {
            font-size: 0.72rem;
            font-weight: 500;
            margin-top: 0.15rem;
        }

        .fp-pwd-strength-text.weak { color: #f43f5e; }
        .fp-pwd-strength-text.medium { color: #f59e0b; }
        .fp-pwd-strength-text.strong { color: #10b981; }

        /* Buttons in Modal */
        .fp-btn-primary {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #f28da5 0%, #eb708e 100%);
            color: #ffffff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.92rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(235, 112, 142, 0.35);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .fp-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 26px rgba(235, 112, 142, 0.45);
            background: linear-gradient(135deg, #eb708e 0%, var(--blush) 100%);
        }

        .fp-btn-primary:active {
            transform: translateY(0);
        }

        .fp-btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .fp-btn-primary .fp-spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2.5px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: fpSpin 0.7s linear infinite;
        }

        .fp-btn-primary.loading .fp-spinner {
            display: block;
        }

        .fp-btn-primary.loading .fp-btn-text {
            display: none;
        }

        @keyframes fpSpin {
            to { transform: rotate(360deg); }
        }

        .fp-btn-secondary {
            width: 100%;
            height: 44px;
            border: 1.5px solid #e8d5da;
            border-radius: 12px;
            background: transparent;
            color: var(--ink-muted);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .fp-btn-secondary:hover {
            border-color: var(--blush);
            color: var(--blush);
            background: #fdf0f4;
        }

        /* Success Checkmark Animation */
        .fp-success-anim {
            text-align: center;
            padding: 1.5rem 0 0.5rem 0;
        }

        .fp-success-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #34d399, #10b981);
            margin: 0 auto 1rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
            animation: fpSuccessPop 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .fp-success-circle svg,
        .fp-success-circle i {
            color: #fff;
            width: 32px;
            height: 32px;
        }

        @keyframes fpSuccessPop {
            0% { transform: scale(0); opacity: 0; }
            60% { transform: scale(1.15); }
            100% { transform: scale(1); opacity: 1; }
        }

        .fp-success-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 0.4rem;
        }

        .fp-success-text {
            font-size: 0.82rem;
            color: #7a5f67;
            line-height: 1.5;
        }

        /* Toggle Password in Modal */
        .fp-toggle-pwd {
            position: absolute;
            right: 0.85rem;
            background: transparent;
            border: none;
            color: var(--ink-muted);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .fp-toggle-pwd:hover {
            color: var(--blush);
        }

        /* Responsive */
        @media (max-width: 860px) {
            .auth-card-container {
                grid-template-columns: 1fr;
                max-width: 480px;
            }
            .auth-hero-col {
                min-height: 240px;
                padding: 2rem;
            }
            .hero-quote {
                font-size: 1.35rem;
            }
            .auth-form-col {
                padding: 2.25rem 2rem;
            }
        }
    </style>
</head>
<body>

    {{-- TOP NAVIGATION --}}
    <div class="auth-top-bar">
        <a href="/" class="btn-back-home" id="btn-back-home">
            <i data-lucide="arrow-left" style="width:16px;height:16px;"></i>
            <span>Kembali ke Beranda</span>
        </a>
        <a href="/" style="font-family:'Playfair Display', serif; font-size:1.3rem; font-weight:700; color:var(--blush); text-decoration:none;">
            Sweet Dreams
        </a>
    </div>

    {{-- MAIN CARD CONTAINER --}}
    <div class="auth-card-container">
        
        {{-- LEFT COLUMN: HERO AMBIENCE & QUOTE --}}
        <div class="auth-hero-col">
            <div class="auth-hero-overlay"></div>
            <div class="auth-hero-content">
                <span class="hero-tag">SWEET DREAM HOMEWEAR</span>
                <blockquote class="hero-quote">
                    “Tidur nyenyak berawal dari kenyamanan yang sempurna.”
                </blockquote>
                <span class="hero-club">Sweet Dream Club</span>
            </div>
        </div>

        {{-- RIGHT COLUMN: FORM BOX --}}
        <div class="auth-form-col">
            
            {{-- ===== 1. VIEW LOGIN ===== --}}
            <div class="auth-section-view {{ ($initialMode ?? 'login') === 'login' ? 'active' : '' }}" id="view-login">
                <div class="auth-header">
                    <h1 class="auth-brand-title">Sweet Dream</h1>
                    <p class="auth-subtitle">Silakan masuk ke dalam akun Anda</p>
                </div>

                <form class="auth-form" id="form-login" onsubmit="return false;">
                    {{-- Email / Username --}}
                    <div class="auth-field-group">
                        <label class="auth-field-label" for="login-identifier">Email / Username</label>
                        <div class="auth-input-wrapper">
                            <i data-lucide="mail" class="auth-input-icon"></i>
                            <input type="text" id="login-identifier" class="auth-input-field" placeholder="Masukkan email atau username" autocomplete="username" required>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="auth-field-group">
                        <label class="auth-field-label" for="login-password">Password</label>
                        <div class="auth-input-wrapper">
                            <i data-lucide="lock" class="auth-input-icon"></i>
                            <input type="password" id="login-password" class="auth-input-field" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
                            <button type="button" class="btn-toggle-pwd" data-target="login-password" aria-label="Lihat kata sandi">
                                <i data-lucide="eye" style="width:18px;height:18px;"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember & Forgot password --}}
                    <div class="auth-meta-row">
                        <label class="remember-label">
                            <input type="checkbox" id="login-remember" checked>
                            <span>Ingat Saya</span>
                        </label>
                        <a href="javascript:void(0)" class="link-forgot" id="link-forgot-pwd">Lupa Password?</a>
                    </div>

                    {{-- Submit CTA --}}
                    <button type="submit" class="btn-auth-submit" id="btn-login-submit">
                        <span>Masuk</span>
                    </button>


                    {{-- Switch to Register --}}
                    <div class="auth-switch-box">
                        <span>Belum punya akun?</span>
                        <a href="javascript:void(0)" class="link-switch" id="btn-goto-register">Daftar di sini</a>
                    </div>
                </form>
            </div>

            {{-- ===== 2. VIEW REGISTER ===== --}}
            <div class="auth-section-view {{ ($initialMode ?? 'login') === 'register' ? 'active' : '' }}" id="view-register">
                <div class="auth-header">
                    <h1 class="auth-brand-title">Sweet Dream</h1>
                    <p class="auth-subtitle">Daftar akun baru untuk menikmati kenyamanan</p>
                </div>

                <form class="auth-form" id="form-register" onsubmit="return false;">
                    {{-- Avatar Selection for Registration --}}
                    <div class="reg-avatar-section">
                        <div class="reg-avatar-header">
                            <i data-lucide="sparkles" style="width:16px;height:16px;color:var(--blush);"></i>
                            <span>Pilih Avatar Akun Anda</span>
                        </div>
                        <p class="reg-avatar-subtitle">Pilih avatar manis untuk foto profil akun Anda</p>

                        <div class="reg-avatar-preview-wrapper">
                            <img id="reg-avatar-preview" class="reg-avatar-preview-img" src="{{ asset('images/avatars/avatar-1.svg') }}" alt="Selected Avatar">
                            <span class="reg-avatar-badge" id="reg-avatar-name-badge">Sweet Bunny</span>
                            <input type="hidden" id="reg-selected-avatar" value="images/avatars/avatar-1.svg">
                        </div>

                        <div class="reg-avatar-options-grid">
                            <button type="button" class="reg-avatar-option-btn selected" data-path="images/avatars/avatar-1.svg" data-name="Sweet Bunny" title="Sweet Bunny">
                                <img src="{{ asset('images/avatars/avatar-1.svg') }}" alt="Sweet Bunny">
                                <span>Bunny</span>
                            </button>
                            <button type="button" class="reg-avatar-option-btn" data-path="images/avatars/avatar-2.svg" data-name="Dreamy Cat" title="Dreamy Cat">
                                <img src="{{ asset('images/avatars/avatar-2.svg') }}" alt="Dreamy Cat">
                                <span>Cat</span>
                            </button>
                            <button type="button" class="reg-avatar-option-btn" data-path="images/avatars/avatar-3.svg" data-name="Cloud Princess" title="Cloud Princess">
                                <img src="{{ asset('images/avatars/avatar-3.svg') }}" alt="Cloud Princess">
                                <span>Cloud</span>
                            </button>
                            <button type="button" class="reg-avatar-option-btn" data-path="images/avatars/avatar-4.svg" data-name="Teddy Slumber" title="Teddy Slumber">
                                <img src="{{ asset('images/avatars/avatar-4.svg') }}" alt="Teddy Slumber">
                                <span>Teddy</span>
                            </button>
                            <button type="button" class="reg-avatar-option-btn" data-path="images/avatars/avatar-5.svg" data-name="Velvet Swan" title="Velvet Swan">
                                <img src="{{ asset('images/avatars/avatar-5.svg') }}" alt="Velvet Swan">
                                <span>Swan</span>
                            </button>
                            <button type="button" class="reg-avatar-option-btn" data-path="images/avatars/avatar-6.svg" data-name="Moon Dreamer" title="Moon Dreamer">
                                <img src="{{ asset('images/avatars/avatar-6.svg') }}" alt="Moon Dreamer">
                                <span>Moon</span>
                            </button>
                            <button type="button" class="reg-avatar-option-btn" data-path="images/avatars/avatar-7.svg" data-name="Pastel Girl" title="Pastel Girl">
                                <img src="{{ asset('images/avatars/avatar-7.svg') }}" alt="Pastel Girl">
                                <span>Girl</span>
                            </button>
                            <button type="button" class="reg-avatar-option-btn" data-path="images/avatars/avatar-8.svg" data-name="Silk Panda" title="Silk Panda">
                                <img src="{{ asset('images/avatars/avatar-8.svg') }}" alt="Silk Panda">
                                <span>Panda</span>
                            </button>
                        </div>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="auth-field-group">
                        <label class="auth-field-label" for="reg-name">Nama Lengkap</label>
                        <div class="auth-input-wrapper">
                            <i data-lucide="user" class="auth-input-icon"></i>
                            <input type="text" id="reg-name" class="auth-input-field" placeholder="Masukkan nama lengkap Anda" required>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="auth-field-group">
                        <label class="auth-field-label" for="reg-email">Email</label>
                        <div class="auth-input-wrapper">
                            <i data-lucide="mail" class="auth-input-icon"></i>
                            <input type="email" id="reg-email" class="auth-input-field" placeholder="Masukkan alamat email" required>
                        </div>
                    </div>

                    {{-- Username --}}
                    <div class="auth-field-group">
                        <label class="auth-field-label" for="reg-username">Username</label>
                        <div class="auth-input-wrapper">
                            <i data-lucide="at-sign" class="auth-input-icon"></i>
                            <input type="text" id="reg-username" class="auth-input-field" placeholder="Buat username unik" required>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="auth-field-group">
                        <label class="auth-field-label" for="reg-password">Password</label>
                        <div class="auth-input-wrapper">
                            <i data-lucide="lock" class="auth-input-icon"></i>
                            <input type="password" id="reg-password" class="auth-input-field" placeholder="Minimal 8 karakter" required>
                            <button type="button" class="btn-toggle-pwd" data-target="reg-password" aria-label="Lihat kata sandi">
                                <i data-lucide="eye" style="width:18px;height:18px;"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="auth-field-group">
                        <label class="auth-field-label" for="reg-password-confirm">Konfirmasi Password</label>
                        <div class="auth-input-wrapper">
                            <i data-lucide="shield-check" class="auth-input-icon"></i>
                            <input type="password" id="reg-password-confirm" class="auth-input-field" placeholder="Ulangi kata sandi" required>
                            <button type="button" class="btn-toggle-pwd" data-target="reg-password-confirm" aria-label="Lihat kata sandi">
                                <i data-lucide="eye" style="width:18px;height:18px;"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Terms Checkbox --}}
                    <div class="auth-meta-row" style="margin-top:0.1rem;">
                        <label class="remember-label" style="font-size:0.78rem;">
                            <input type="checkbox" id="reg-terms" checked required>
                            <span>Saya menyetujui Syarat & Ketentuan Sweet Dreams</span>
                        </label>
                    </div>

                    {{-- Submit CTA --}}
                    <button type="submit" class="btn-auth-submit" id="btn-register-submit">
                        <span>Daftar Sekarang</span>
                    </button>

                    {{-- Switch to Login --}}
                    <div class="auth-switch-box">
                        <span>Sudah punya akun?</span>
                        <a href="javascript:void(0)" class="link-switch" id="btn-goto-login">Masuk di sini</a>
                    </div>
                </form>
            </div>

        </div>

    </div>

    {{-- FORGOT PASSWORD MODAL --}}
    <div class="fp-modal-overlay" id="fp-modal-overlay">
        <div class="fp-modal-card" id="fp-modal-card">
            {{-- Header --}}
            <div class="fp-modal-header">
                <button type="button" class="fp-modal-close-btn" id="fp-btn-close" aria-label="Tutup modal">
                    <i data-lucide="x" style="width:18px;height:18px;"></i>
                </button>
                <div class="fp-modal-icon-circle" id="fp-header-icon">
                    <i data-lucide="key-round" style="width:28px;height:28px;"></i>
                </div>
                <h2 id="fp-modal-title">Lupa Kata Sandi?</h2>
                <p id="fp-modal-subtitle">Masukkan email Anda untuk menerima instruksi pemulihan kata sandi.</p>
            </div>

            {{-- Step Indicators --}}
            <div class="fp-steps-indicator" id="fp-steps-indicator">
                <div class="fp-step-dot active" id="fp-dot-1" title="Email"></div>
                <div class="fp-step-connector" id="fp-conn-1"></div>
                <div class="fp-step-dot" id="fp-dot-2" title="Verifikasi"></div>
                <div class="fp-step-connector" id="fp-conn-2"></div>
                <div class="fp-step-dot" id="fp-dot-3" title="Sandi Baru"></div>
            </div>

            {{-- Modal Body --}}
            <div class="fp-modal-body">

                {{-- STEP 1: INPUT EMAIL --}}
                <div class="fp-step-panel active" id="fp-step-1">
                    <form id="fp-form-email" onsubmit="return false;">
                        <div class="fp-field-group">
                            <label class="fp-field-label" for="fp-input-email">Alamat Email Terdaftar</label>
                            <div class="fp-input-wrapper">
                                <i data-lucide="mail" class="fp-input-icon"></i>
                                <input type="email" id="fp-input-email" class="fp-input-field" placeholder="contoh: alya.putri@email.com" required autocomplete="email">
                            </div>
                        </div>

                        <div style="background:#fff7f9; border:1px dashed var(--blush-pale); border-radius:12px; padding:10px 12px; margin-bottom:1rem; font-size:0.78rem; color:#7a5f67; display:flex; align-items:flex-start; gap:8px;">
                            <i data-lucide="info" style="width:16px;height:16px;color:var(--blush);flex-shrink:0;margin-top:2px;"></i>
                            <div>
                                <strong>Tips Demo:</strong> Gunakan akun demo <code>alya.putri@email.com</code> atau akun yang telah Anda daftarkan.
                            </div>
                        </div>

                        <button type="submit" class="fp-btn-primary" id="fp-btn-submit-email">
                            <span class="fp-spinner"></span>
                            <span class="fp-btn-text" style="display:flex;align-items:center;gap:6px;">
                                <span>Kirim Kode Verifikasi</span>
                                <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
                            </span>
                        </button>

                        <button type="button" class="fp-btn-secondary" id="fp-btn-cancel-1">
                            Kembali ke Halaman Masuk
                        </button>
                    </form>
                </div>

                {{-- STEP 2: VERIFIKASI KODE OTP --}}
                <div class="fp-step-panel" id="fp-step-2">
                    <form id="fp-form-code" onsubmit="return false;">
                        <div class="fp-otp-info">
                            Kode 6 digit keamanan telah dikirim ke: <br>
                            <strong id="fp-target-email-display">email@example.com</strong>
                        </div>

                        {{-- Simulation Quick Helper Banner --}}
                        <div id="fp-simulated-box" style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:10px 12px; margin-bottom:1rem; text-align:center; font-size:0.8rem; color:#1e40af;">
                            <div style="font-weight:600; margin-bottom:4px; display:flex; align-items:center; justify-content:center; gap:5px;">
                                <i data-lucide="mail-check" style="width:16px;height:16px;color:#2563eb;"></i>
                                <span>[Simulasi Email Masuk]</span>
                            </div>
                            <div>Kode Verifikasi Anda: <strong id="fp-simulated-code-text" style="font-size:1.05rem; letter-spacing:2px; color:#1d4ed8;">123456</strong></div>
                            <button type="button" id="fp-btn-quick-fill-code" style="margin-top:6px; background:#2563eb; color:#fff; border:none; border-radius:20px; padding:4px 12px; font-size:0.72rem; font-weight:600; cursor:pointer;">
                                Tempel Kode Otomatis
                            </button>
                        </div>

                        <label class="fp-field-label" style="text-align:center;">Masukkan 6 Digit Kode</label>
                        <div class="fp-otp-group">
                            <input type="text" maxlength="1" class="fp-otp-input" inputmode="numeric" data-index="0" autofocus>
                            <input type="text" maxlength="1" class="fp-otp-input" inputmode="numeric" data-index="1">
                            <input type="text" maxlength="1" class="fp-otp-input" inputmode="numeric" data-index="2">
                            <input type="text" maxlength="1" class="fp-otp-input" inputmode="numeric" data-index="3">
                            <input type="text" maxlength="1" class="fp-otp-input" inputmode="numeric" data-index="4">
                            <input type="text" maxlength="1" class="fp-otp-input" inputmode="numeric" data-index="5">
                        </div>

                        <div class="fp-otp-resend">
                            <span id="fp-resend-countdown" style="font-size:0.78rem; color:#8a6a72;">Kirim ulang kode dalam <strong id="fp-resend-timer">45</strong>s</span>
                            <button type="button" id="fp-btn-resend-code" style="display:none;">Kirim Ulang Kode Sekarang</button>
                        </div>

                        <button type="submit" class="fp-btn-primary" id="fp-btn-verify-code">
                            <span class="fp-spinner"></span>
                            <span class="fp-btn-text" style="display:flex;align-items:center;gap:6px;">
                                <span>Verifikasi Kode</span>
                                <i data-lucide="check" style="width:16px;height:16px;"></i>
                            </span>
                        </button>

                        <button type="button" class="fp-btn-secondary" id="fp-btn-back-step1">
                            <i data-lucide="arrow-left" style="width:16px;height:16px;"></i>
                            <span>Ganti Alamat Email</span>
                        </button>
                    </form>
                </div>

                {{-- STEP 3: BUAT PASSWORD BARU --}}
                <div class="fp-step-panel" id="fp-step-3">
                    <form id="fp-form-reset-pwd" onsubmit="return false;">
                        <div class="fp-field-group">
                            <label class="fp-field-label" for="fp-new-password">Kata Sandi Baru</label>
                            <div class="fp-input-wrapper">
                                <i data-lucide="lock" class="fp-input-icon"></i>
                                <input type="password" id="fp-new-password" class="fp-input-field" placeholder="Minimal 8 karakter" required autocomplete="new-password">
                                <button type="button" class="fp-toggle-pwd" data-target="fp-new-password" aria-label="Lihat kata sandi">
                                    <i data-lucide="eye" style="width:18px;height:18px;"></i>
                                </button>
                            </div>
                            <div class="fp-pwd-strength">
                                <div class="fp-pwd-strength-bar" id="fp-bar-1"></div>
                                <div class="fp-pwd-strength-bar" id="fp-bar-2"></div>
                                <div class="fp-pwd-strength-bar" id="fp-bar-3"></div>
                            </div>
                            <div class="fp-pwd-strength-text" id="fp-pwd-strength-text">Kekuatan kata sandi</div>
                        </div>

                        <div class="fp-field-group">
                            <label class="fp-field-label" for="fp-confirm-password">Konfirmasi Kata Sandi Baru</label>
                            <div class="fp-input-wrapper">
                                <i data-lucide="shield-check" class="fp-input-icon"></i>
                                <input type="password" id="fp-confirm-password" class="fp-input-field" placeholder="Ulangi kata sandi baru" required autocomplete="new-password">
                                <button type="button" class="fp-toggle-pwd" data-target="fp-confirm-password" aria-label="Lihat kata sandi">
                                    <i data-lucide="eye" style="width:18px;height:18px;"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="fp-btn-primary" id="fp-btn-save-pwd">
                            <span class="fp-spinner"></span>
                            <span class="fp-btn-text" style="display:flex;align-items:center;gap:6px;">
                                <span>Simpan Kata Sandi Baru</span>
                                <i data-lucide="check-circle" style="width:16px;height:16px;"></i>
                            </span>
                        </button>
                    </form>
                </div>

                {{-- STEP 4: SUCCESS VIEW --}}
                <div class="fp-step-panel" id="fp-step-4">
                    <div class="fp-success-anim">
                        <div class="fp-success-circle">
                            <i data-lucide="check" style="width:36px;height:36px;"></i>
                        </div>
                        <h3 class="fp-success-title">Kata Sandi Berhasil Direset!</h3>
                        <p class="fp-success-text">
                            Kata sandi akun Anda telah berhasil diperbarui. Silakan masuk menggunakan kata sandi baru Anda.
                        </p>
                    </div>

                    <button type="button" class="fp-btn-primary" id="fp-btn-finish-login">
                        <span>Masuk ke Akun Sekarang</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- FLOATING TOAST NOTIFICATION --}}
    <div class="auth-toast-alert" id="auth-toast">
        <div class="auth-toast-icon" id="auth-toast-icon">
            <i data-lucide="alert-circle" style="width:18px;height:18px;"></i>
        </div>
        <div class="auth-toast-content">
            <h4 id="auth-toast-title">Perhatian</h4>
            <p id="auth-toast-msg">Pesan notifikasi.</p>
        </div>
    </div>

    <script>
        // Init Lucide Icons
        lucide.createIcons();

        // ===== AUTH STATE HELPER =====
        window.SweetDreamsAuth = {
            USER_KEY: 'sweetdreams_auth_user',
            DB_KEY: 'sweetdreams_registered_users',

            getRegisteredUsers: function() {
                try {
                    const raw = localStorage.getItem(this.DB_KEY);
                    return raw ? JSON.parse(raw) : [];
                } catch(e) {
                    return [];
                }
            },

            saveRegisteredUsers: function(users) {
                try {
                    localStorage.setItem(this.DB_KEY, JSON.stringify(users));
                } catch(e) {
                    console.error('Error saving registered users:', e);
                }
            },

            getCurrentUser: function() {
                try {
                    const raw = localStorage.getItem(this.USER_KEY);
                    return raw ? JSON.parse(raw) : null;
                } catch(e) {
                    return null;
                }
            },

            login: function(identifier, password) {
                identifier = identifier.trim().toLowerCase();
                
                // 1. Check Demo Customer: Alya Putri
                const alyaPwd = localStorage.getItem('sweetdreams_demo_pwd_alya') || 'password123';
                if ((identifier === 'alya.putri@email.com' || identifier === 'alya') && password === alyaPwd) {
                    const user = {
                        name: 'Alya Putri',
                        email: 'alya.putri@email.com',
                        username: 'alya',
                        phone: '0812 3456 7890',
                        birthdate: '17 Mei 1997',
                        city: 'Purwokerto Timur',
                        role: 'customer',
                        avatar: 'images/alya-avatar.jpg',
                        addresses: [
                            {
                                id: 'addr-alya-1',
                                label: 'Alamat Utama',
                                name: 'Alya Putri',
                                phone: '0812 3456 7890',
                                address: 'Jl. Kemang Raya No. 45, RT.2/RW.2, Bangka, Kec. Mampang Prapatan',
                                city: 'Jakarta Selatan',
                                province: 'DKI Jakarta',
                                postal_code: '12730',
                                is_primary: true
                            },
                            {
                                id: 'addr-alya-2',
                                label: 'Kantor',
                                name: 'Alya Putri',
                                phone: '0812 3456 7890',
                                address: 'Gedung Menara Sudirman Lt. 14, Jl. Jend. Sudirman Kav. 60',
                                city: 'Jakarta Selatan',
                                province: 'DKI Jakarta',
                                postal_code: '12190',
                                is_primary: false
                            }
                        ]
                    };
                    localStorage.setItem(this.USER_KEY, JSON.stringify(user));
                    return { success: true, user: user };
                }

                // 2. Check Demo Admin
                const adminPwd = localStorage.getItem('sweetdreams_demo_pwd_admin') || 'admin123';
                if ((identifier === 'admin@sweetdreams.com' || identifier === 'admin') && password === adminPwd) {
                    const admin = {
                        name: 'Administrator',
                        email: 'admin@sweetdreams.com',
                        username: 'admin',
                        phone: '0811 0000 9999',
                        birthdate: '01 Januari 1990',
                        city: 'Jakarta Pusat',
                        role: 'admin',
                        avatar: 'images/alya-avatar.jpg',
                        addresses: [
                            {
                                id: 'addr-admin-1',
                                label: 'Kantor Pusat Sweet Dreams',
                                name: 'Administrator',
                                phone: '0811 0000 9999',
                                address: 'HQ Tower Lt. 21, Jl. Sudirman Kav. 10',
                                city: 'Jakarta Pusat',
                                province: 'DKI Jakarta',
                                postal_code: '10220',
                                is_primary: true
                            }
                        ]
                    };
                    localStorage.setItem(this.USER_KEY, JSON.stringify(admin));
                    return { success: true, user: admin };
                }

                // 3. Check Local Database of Registered Users
                const users = this.getRegisteredUsers();
                const matched = users.find(u => 
                    (u.email.toLowerCase() === identifier || u.username.toLowerCase() === identifier) && 
                    u.password === password
                );

                if (matched) {
                    const user = {
                        name: matched.name,
                        email: matched.email,
                        username: matched.username,
                        phone: matched.phone || '',
                        birthdate: matched.birthdate || '',
                        city: matched.city || '',
                        role: matched.role || 'customer',
                        avatar: matched.avatar || 'images/alya-avatar.jpg',
                        addresses: Array.isArray(matched.addresses) && matched.addresses.length > 0 ? matched.addresses : [
                            {
                                id: 'addr-' + Date.now(),
                                label: 'Alamat Utama',
                                name: matched.name,
                                phone: matched.phone || '',
                                address: '',
                                city: matched.city || '',
                                province: '',
                                postal_code: '',
                                is_primary: true
                            }
                        ]
                    };
                    localStorage.setItem(this.USER_KEY, JSON.stringify(user));
                    return { success: true, user: user };
                }

                return { success: false, message: 'Email/Username atau password yang Anda masukkan salah.' };
            },

            register: function(userData) {
                const users = this.getRegisteredUsers();
                const emailLower = userData.email.trim().toLowerCase();
                const userLower = userData.username.trim().toLowerCase();

                // Check uniqueness
                const exists = users.some(u => 
                    u.email.toLowerCase() === emailLower || u.username.toLowerCase() === userLower
                );

                if (exists || emailLower === 'alya.putri@email.com' || userLower === 'alya' || userLower === 'admin') {
                    return { success: false, message: 'Email atau Username sudah terdaftar. Silakan gunakan yang lain.' };
                }

                const newUser = {
                    name: userData.name.trim(),
                    email: emailLower,
                    username: userLower,
                    password: userData.password,
                    phone: userData.phone || '',
                    birthdate: '',
                    city: '',
                    role: 'customer',
                    avatar: userData.avatar || 'images/avatars/avatar-1.svg',
                    addresses: [
                        {
                            id: 'addr-' + Date.now(),
                            label: 'Alamat Utama',
                            name: userData.name.trim(),
                            phone: userData.phone || '',
                            address: '',
                            city: '',
                            province: '',
                            postal_code: '',
                            is_primary: true
                        }
                    ]
                };

                users.push(newUser);
                this.saveRegisteredUsers(users);

                // Auto login
                localStorage.setItem(this.USER_KEY, JSON.stringify(newUser));

                return { success: true, user: newUser };
            },

            checkEmailExists: function(email) {
                email = email.trim().toLowerCase();
                if (email === 'alya.putri@email.com' || email === 'alya') return { exists: true, email: 'alya.putri@email.com', name: 'Alya Putri' };
                if (email === 'admin@sweetdreams.com' || email === 'admin') return { exists: true, email: 'admin@sweetdreams.com', name: 'Administrator' };
                const users = this.getRegisteredUsers();
                const found = users.find(u => u.email.toLowerCase() === email || u.username.toLowerCase() === email);
                if (found) return { exists: true, email: found.email, name: found.name };
                return { exists: false };
            },

            resetPassword: function(email, newPassword) {
                email = email.trim().toLowerCase();
                if (email === 'alya.putri@email.com' || email === 'alya') {
                    localStorage.setItem('sweetdreams_demo_pwd_alya', newPassword);
                    return { success: true, name: 'Alya Putri' };
                }
                if (email === 'admin@sweetdreams.com' || email === 'admin') {
                    localStorage.setItem('sweetdreams_demo_pwd_admin', newPassword);
                    return { success: true, name: 'Administrator' };
                }
                const users = this.getRegisteredUsers();
                const idx = users.findIndex(u => u.email.toLowerCase() === email || u.username.toLowerCase() === email);
                if (idx !== -1) {
                    users[idx].password = newPassword;
                    this.saveRegisteredUsers(users);
                    return { success: true, name: users[idx].name };
                }
                return { success: false, message: 'Email tidak ditemukan.' };
            }
        };

        // Toast Notification Helper
        function showAuthToast(title, msg, isSuccess = false) {
            const toast = document.getElementById('auth-toast');
            const titleEl = document.getElementById('auth-toast-title');
            const msgEl = document.getElementById('auth-toast-msg');
            const iconEl = document.getElementById('auth-toast-icon');

            if (!toast) return;

            toast.className = 'auth-toast-alert' + (isSuccess ? ' success' : '');
            if (iconEl) {
                iconEl.innerHTML = isSuccess ? 
                    '<i data-lucide="check-circle" style="width:18px;height:18px;"></i>' : 
                    '<i data-lucide="alert-circle" style="width:18px;height:18px;"></i>';
                lucide.createIcons();
            }

            if (titleEl) titleEl.textContent = title;
            if (msgEl) msgEl.textContent = msg;

            toast.classList.add('show');

            if (window.authToastTimeout) clearTimeout(window.authToastTimeout);
            window.authToastTimeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 4500);
        }

        // View Mode Switching (Login <-> Register)
        const viewLogin = document.getElementById('view-login');
        const viewRegister = document.getElementById('view-register');
        const btnGotoRegister = document.getElementById('btn-goto-register');
        const btnGotoLogin = document.getElementById('btn-goto-login');

        function setAuthMode(mode) {
            if (mode === 'register') {
                viewLogin.classList.remove('active');
                viewRegister.classList.add('active');
                history.replaceState(null, '', '/register');
                document.title = 'Daftar Akun - Sweet Dreams';
            } else {
                viewRegister.classList.remove('active');
                viewLogin.classList.add('active');
                history.replaceState(null, '', '/login');
                document.title = 'Masuk Akun - Sweet Dreams';
            }
        }

        if (btnGotoRegister) {
            btnGotoRegister.addEventListener('click', () => setAuthMode('register'));
        }
        if (btnGotoLogin) {
            btnGotoLogin.addEventListener('click', () => setAuthMode('login'));
        }

        // Show/Hide Password Toggle
        document.querySelectorAll('.btn-toggle-pwd').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (input) {
                    const isPwd = input.type === 'password';
                    input.type = isPwd ? 'text' : 'password';
                    this.innerHTML = isPwd ? 
                        '<i data-lucide="eye-off" style="width:18px;height:18px;"></i>' : 
                        '<i data-lucide="eye" style="width:18px;height:18px;"></i>';
                    lucide.createIcons();
                }
            });
        });

        // 1-Click "Masuk sebagai Admin"
        const btnAdminFill = document.getElementById('btn-admin-fill');
        if (btnAdminFill) {
            btnAdminFill.addEventListener('click', function() {
                document.getElementById('login-identifier').value = 'admin@sweetdreams.com';
                document.getElementById('login-password').value = 'admin123';
                showAuthToast('Akun Admin Terisi', 'Kredensial admin otomatis dimasukkan. Silakan klik Masuk!', true);
            });
        }

        // ==========================================
        // ===== FORGOT PASSWORD MODAL CONTROLLER ===
        // ==========================================
        const fpModalOverlay = document.getElementById('fp-modal-overlay');
        const fpModalCard = document.getElementById('fp-modal-card');
        const fpBtnClose = document.getElementById('fp-btn-close');
        const fpBtnCancel1 = document.getElementById('fp-btn-cancel-1');
        const linkForgot = document.getElementById('link-forgot-pwd');
        const fpModalTitle = document.getElementById('fp-modal-title');
        const fpModalSubtitle = document.getElementById('fp-modal-subtitle');
        const fpHeaderIcon = document.getElementById('fp-header-icon');

        // Step Panels & Indicators
        const fpStep1 = document.getElementById('fp-step-1');
        const fpStep2 = document.getElementById('fp-step-2');
        const fpStep3 = document.getElementById('fp-step-3');
        const fpStep4 = document.getElementById('fp-step-4');

        const fpDot1 = document.getElementById('fp-dot-1');
        const fpDot2 = document.getElementById('fp-dot-2');
        const fpDot3 = document.getElementById('fp-dot-3');
        const fpConn1 = document.getElementById('fp-conn-1');
        const fpConn2 = document.getElementById('fp-conn-2');

        // Session State
        window.fpSession = {
            email: '',
            code: '',
            name: '',
            newPassword: ''
        };
        let fpResendInterval = null;

        function setFpStep(step) {
            // Panels
            [fpStep1, fpStep2, fpStep3, fpStep4].forEach(p => p.classList.remove('active'));
            const targetPanel = document.getElementById(`fp-step-${step}`);
            if (targetPanel) targetPanel.classList.add('active');

            // Indicators
            fpDot1.className = 'fp-step-dot' + (step === 1 ? ' active' : (step > 1 ? ' completed' : ''));
            fpDot2.className = 'fp-step-dot' + (step === 2 ? ' active' : (step > 2 ? ' completed' : ''));
            fpDot3.className = 'fp-step-dot' + (step === 3 ? ' active' : (step > 3 ? ' completed' : ''));
            fpConn1.className = 'fp-step-connector' + (step >= 2 ? ' active' : '');
            fpConn2.className = 'fp-step-connector' + (step >= 3 ? ' active' : '');

            // Titles & Icons
            if (step === 1) {
                fpModalTitle.textContent = 'Lupa Kata Sandi?';
                fpModalSubtitle.textContent = 'Masukkan email Anda untuk menerima instruksi pemulihan kata sandi.';
                fpHeaderIcon.innerHTML = '<i data-lucide="key-round" style="width:28px;height:28px;"></i>';
                document.getElementById('fp-steps-indicator').style.display = 'flex';
            } else if (step === 2) {
                fpModalTitle.textContent = 'Verifikasi Keamanan';
                fpModalSubtitle.textContent = 'Periksa kotak masuk email Anda dan masukkan kode 6 digit.';
                fpHeaderIcon.innerHTML = '<i data-lucide="shield-check" style="width:28px;height:28px;"></i>';
                document.getElementById('fp-steps-indicator').style.display = 'flex';
            } else if (step === 3) {
                fpModalTitle.textContent = 'Buat Kata Sandi Baru';
                fpModalSubtitle.textContent = 'Buat kata sandi baru yang kuat untuk melindungi akun Anda.';
                fpHeaderIcon.innerHTML = '<i data-lucide="lock" style="width:28px;height:28px;"></i>';
                document.getElementById('fp-steps-indicator').style.display = 'flex';
            } else if (step === 4) {
                fpModalTitle.textContent = 'Pemulihan Selesai';
                fpModalSubtitle.textContent = 'Akun Anda telah diamankan dengan kata sandi yang baru.';
                fpHeaderIcon.innerHTML = '<i data-lucide="check" style="width:28px;height:28px;"></i>';
                document.getElementById('fp-steps-indicator').style.display = 'none';
            }
            lucide.createIcons();
        }

        function openForgotPasswordModal() {
            if (!fpModalOverlay) return;
            setFpStep(1);

            // Pre-fill email if user already entered an email in the login box
            const currentLoginId = document.getElementById('login-identifier')?.value.trim();
            const emailInput = document.getElementById('fp-input-email');
            if (emailInput) {
                if (currentLoginId && currentLoginId.includes('@')) {
                    emailInput.value = currentLoginId;
                } else if (!emailInput.value) {
                    emailInput.value = 'alya.putri@email.com'; // Helpful demo default
                }
                setTimeout(() => emailInput.focus(), 300);
            }

            fpModalOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeForgotPasswordModal() {
            if (!fpModalOverlay) return;
            fpModalOverlay.classList.remove('open');
            document.body.style.overflow = '';
            if (fpResendInterval) clearInterval(fpResendInterval);
        }

        if (linkForgot) {
            linkForgot.addEventListener('click', function(e) {
                e.preventDefault();
                openForgotPasswordModal();
            });
        }

        if (fpBtnClose) fpBtnClose.addEventListener('click', closeForgotPasswordModal);
        if (fpBtnCancel1) fpBtnCancel1.addEventListener('click', closeForgotPasswordModal);

        if (fpModalOverlay) {
            fpModalOverlay.addEventListener('click', function(e) {
                if (e.target === fpModalOverlay) {
                    closeForgotPasswordModal();
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && fpModalOverlay && fpModalOverlay.classList.contains('open')) {
                closeForgotPasswordModal();
            }
        });

        // Toggle Password Visibility in Modal
        document.querySelectorAll('.fp-toggle-pwd').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (input) {
                    const isPwd = input.type === 'password';
                    input.type = isPwd ? 'text' : 'password';
                    this.innerHTML = isPwd ? 
                        '<i data-lucide="eye-off" style="width:18px;height:18px;"></i>' : 
                        '<i data-lucide="eye" style="width:18px;height:18px;"></i>';
                    lucide.createIcons();
                }
            });
        });

        // STEP 1: Handle Email Submit
        const fpFormEmail = document.getElementById('fp-form-email');
        const fpBtnSubmitEmail = document.getElementById('fp-btn-submit-email');

        function startOtpCountdown() {
            let seconds = 45;
            const timerEl = document.getElementById('fp-resend-timer');
            const countdownEl = document.getElementById('fp-resend-countdown');
            const resendBtn = document.getElementById('fp-btn-resend-code');

            if (timerEl) timerEl.textContent = seconds;
            if (countdownEl) countdownEl.style.display = 'inline';
            if (resendBtn) resendBtn.style.display = 'none';

            if (fpResendInterval) clearInterval(fpResendInterval);
            fpResendInterval = setInterval(() => {
                seconds--;
                if (timerEl) timerEl.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(fpResendInterval);
                    if (countdownEl) countdownEl.style.display = 'none';
                    if (resendBtn) resendBtn.style.display = 'inline';
                }
            }, 1000);
        }

        if (fpFormEmail) {
            fpFormEmail.addEventListener('submit', function(e) {
                e.preventDefault();
                const emailInput = document.getElementById('fp-input-email');
                const email = emailInput.value.trim();

                emailInput.classList.remove('is-invalid');
                if (!email || !email.includes('@')) {
                    emailInput.classList.add('is-invalid');
                    showAuthToast('Email Tidak Valid', 'Silakan masukkan alamat email yang valid.');
                    emailInput.focus();
                    return;
                }

                // Check user exists
                const userCheck = window.SweetDreamsAuth.checkEmailExists(email);
                if (!userCheck.exists) {
                    emailInput.classList.add('is-invalid');
                    showAuthToast('Email Tidak Ditemukan', 'Email tersebut belum terdaftar. Silakan periksa kembali atau gunakan akun demo alya.putri@email.com');
                    return;
                }

                // Show spinner
                fpBtnSubmitEmail.classList.add('loading');

                setTimeout(() => {
                    fpBtnSubmitEmail.classList.remove('loading');

                    // Generate 6 digit code
                    const generatedCode = Math.floor(100000 + Math.random() * 900000).toString();
                    window.fpSession = {
                        email: userCheck.email,
                        code: generatedCode,
                        name: userCheck.name
                    };

                    // Update UI in Step 2
                    document.getElementById('fp-target-email-display').textContent = userCheck.email;
                    document.getElementById('fp-simulated-code-text').textContent = generatedCode;

                    // Clear OTP inputs
                    document.querySelectorAll('.fp-otp-input').forEach(inp => {
                        inp.value = '';
                        inp.classList.remove('filled', 'is-invalid');
                    });

                    setFpStep(2);
                    startOtpCountdown();

                    showAuthToast('Kode Terkirim!', `Kode verifikasi pemulihan telah dikirim ke ${userCheck.email}`, true);

                    // Focus first OTP field
                    setTimeout(() => {
                        const firstOtp = document.querySelector('.fp-otp-input[data-index="0"]');
                        if (firstOtp) firstOtp.focus();
                    }, 350);
                }, 600);
            });
        }

        // STEP 2: OTP Inputs Handling
        const otpInputs = document.querySelectorAll('.fp-otp-input');
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                const val = this.value.replace(/\D/g, '');
                this.value = val ? val.slice(-1) : '';

                if (this.value) {
                    this.classList.add('filled');
                    this.classList.remove('is-invalid');
                    // Focus next
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                } else {
                    this.classList.remove('filled');
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim().replace(/\D/g, '');
                if (pasteData) {
                    const digits = pasteData.slice(0, 6).split('');
                    digits.forEach((digit, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = digit;
                            otpInputs[i].classList.add('filled');
                            otpInputs[i].classList.remove('is-invalid');
                        }
                    });
                    if (digits.length >= 6) {
                        otpInputs[5].focus();
                    } else if (otpInputs[digits.length]) {
                        otpInputs[digits.length].focus();
                    }
                }
            });
        });

        // Button: Quick Fill Simulated Code
        const btnQuickFillCode = document.getElementById('fp-btn-quick-fill-code');
        if (btnQuickFillCode) {
            btnQuickFillCode.addEventListener('click', function() {
                const code = window.fpSession.code || '123456';
                const digits = code.split('');
                digits.forEach((d, i) => {
                    if (otpInputs[i]) {
                        otpInputs[i].value = d;
                        otpInputs[i].classList.add('filled');
                        otpInputs[i].classList.remove('is-invalid');
                    }
                });
                showAuthToast('Kode Ditempel', 'Kode verifikasi telah diisikan ke dalam formulir.', true);
            });
        }

        // Button: Resend Code
        const btnResendCode = document.getElementById('fp-btn-resend-code');
        if (btnResendCode) {
            btnResendCode.addEventListener('click', function() {
                const newCode = Math.floor(100000 + Math.random() * 900000).toString();
                window.fpSession.code = newCode;
                document.getElementById('fp-simulated-code-text').textContent = newCode;
                
                // Clear OTP inputs
                otpInputs.forEach(inp => {
                    inp.value = '';
                    inp.classList.remove('filled', 'is-invalid');
                });
                if (otpInputs[0]) otpInputs[0].focus();

                startOtpCountdown();
                showAuthToast('Kode Baru Terkirim!', `Kode verifikasi baru telah dikirim ke ${window.fpSession.email}`, true);
            });
        }

        // Button: Back to Step 1
        const btnBackStep1 = document.getElementById('fp-btn-back-step1');
        if (btnBackStep1) {
            btnBackStep1.addEventListener('click', function() {
                if (fpResendInterval) clearInterval(fpResendInterval);
                setFpStep(1);
            });
        }

        // STEP 2: Verify Code Form Submit
        const fpFormCode = document.getElementById('fp-form-code');
        const fpBtnVerifyCode = document.getElementById('fp-btn-verify-code');

        if (fpFormCode) {
            fpFormCode.addEventListener('submit', function(e) {
                e.preventDefault();
                let enteredCode = '';
                otpInputs.forEach(inp => enteredCode += inp.value.trim());

                if (enteredCode.length < 6) {
                    otpInputs.forEach(inp => {
                        if (!inp.value) inp.classList.add('is-invalid');
                    });
                    showAuthToast('Kode Belum Lengkap', 'Harap masukkan seluruh 6 digit kode verifikasi.');
                    return;
                }

                if (enteredCode !== window.fpSession.code) {
                    otpInputs.forEach(inp => inp.classList.add('is-invalid'));
                    showAuthToast('Kode Salah', 'Kode verifikasi yang Anda masukkan tidak sesuai. Silakan coba lagi.');
                    return;
                }

                // Verified!
                fpBtnVerifyCode.classList.add('loading');
                setTimeout(() => {
                    fpBtnVerifyCode.classList.remove('loading');
                    if (fpResendInterval) clearInterval(fpResendInterval);
                    setFpStep(3);
                    showAuthToast('Verifikasi Berhasil!', 'Silakan tentukan kata sandi baru Anda.', true);

                    const newPwdInput = document.getElementById('fp-new-password');
                    if (newPwdInput) {
                        newPwdInput.value = '';
                        setTimeout(() => newPwdInput.focus(), 300);
                    }
                    const confPwdInput = document.getElementById('fp-confirm-password');
                    if (confPwdInput) confPwdInput.value = '';
                }, 500);
            });
        }

        // STEP 3: Password Strength & Reset Password Form
        const fpNewPwd = document.getElementById('fp-new-password');
        const bar1 = document.getElementById('fp-bar-1');
        const bar2 = document.getElementById('fp-bar-2');
        const bar3 = document.getElementById('fp-bar-3');
        const strengthText = document.getElementById('fp-pwd-strength-text');

        if (fpNewPwd) {
            fpNewPwd.addEventListener('input', function() {
                const val = this.value;
                let score = 0;
                if (val.length >= 8) score++;
                if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
                if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val)) score++;

                // Reset bars
                [bar1, bar2, bar3].forEach(b => b.className = 'fp-pwd-strength-bar');
                strengthText.className = 'fp-pwd-strength-text';

                if (!val) {
                    strengthText.textContent = 'Kekuatan kata sandi';
                } else if (score === 1 || val.length < 8) {
                    bar1.classList.add('weak');
                    strengthText.classList.add('weak');
                    strengthText.textContent = 'Kekuatan: Lemah (minimal 8 karakter)';
                } else if (score === 2) {
                    bar1.classList.add('medium');
                    bar2.classList.add('medium');
                    strengthText.classList.add('medium');
                    strengthText.textContent = 'Kekuatan: Sedang (tambahkan simbol/angka)';
                } else {
                    bar1.classList.add('strong');
                    bar2.classList.add('strong');
                    bar3.classList.add('strong');
                    strengthText.classList.add('strong');
                    strengthText.textContent = 'Kekuatan: Sangat Kuat & Aman!';
                }
            });
        }

        const fpFormResetPwd = document.getElementById('fp-form-reset-pwd');
        const fpBtnSavePwd = document.getElementById('fp-btn-save-pwd');

        if (fpFormResetPwd) {
            fpFormResetPwd.addEventListener('submit', function(e) {
                e.preventDefault();
                const pwd = document.getElementById('fp-new-password').value;
                const confirm = document.getElementById('fp-confirm-password').value;

                if (pwd.length < 8) {
                    showAuthToast('Kata Sandi Terlalu Pendek', 'Kata sandi baru harus memiliki panjang minimal 8 karakter.');
                    document.getElementById('fp-new-password').focus();
                    return;
                }

                if (pwd !== confirm) {
                    showAuthToast('Kata Sandi Tidak Cocok', 'Konfirmasi kata sandi tidak sama dengan kata sandi baru.');
                    document.getElementById('fp-confirm-password').focus();
                    return;
                }

                fpBtnSavePwd.classList.add('loading');
                setTimeout(() => {
                    fpBtnSavePwd.classList.remove('loading');

                    // Save to SweetDreamsAuth
                    const res = window.SweetDreamsAuth.resetPassword(window.fpSession.email, pwd);
                    if (res.success) {
                        window.fpSession.newPassword = pwd;
                        setFpStep(4);
                        showAuthToast('Berhasil Diperbarui!', 'Kata sandi akun Anda telah berhasil direset.', true);
                    } else {
                        showAuthToast('Gagal Reset', res.message || 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                }, 600);
            });
        }

        // STEP 4: Finish and Return to Login
        const btnFinishLogin = document.getElementById('fp-btn-finish-login');
        if (btnFinishLogin) {
            btnFinishLogin.addEventListener('click', function() {
                closeForgotPasswordModal();
                setAuthMode('login');

                // Pre-populate login form with email & new password
                const loginId = document.getElementById('login-identifier');
                const loginPwd = document.getElementById('login-password');
                if (loginId) loginId.value = window.fpSession.email;
                if (loginPwd) loginPwd.value = window.fpSession.newPassword;

                showAuthToast('Kata Sandi Siap Digunakan', 'Kredensial baru telah terisi. Silakan klik tombol Masuk!', true);
                
                setTimeout(() => {
                    const submitBtn = document.getElementById('btn-login-submit');
                    if (submitBtn) submitBtn.focus();
                }, 400);
            });
        }

        // Handle Login Submission
        const formLogin = document.getElementById('form-login');
        if (formLogin) {
            formLogin.addEventListener('submit', function(e) {
                e.preventDefault();
                const idEl = document.getElementById('login-identifier');
                const pwdEl = document.getElementById('login-password');
                const identifier = idEl.value.trim();
                const password = pwdEl.value;

                idEl.classList.remove('is-invalid');
                pwdEl.classList.remove('is-invalid');

                if (!identifier) {
                    idEl.classList.add('is-invalid');
                    showAuthToast('Input Kosong', 'Harap masukkan email atau username Anda.');
                    idEl.focus();
                    return;
                }

                if (!password) {
                    pwdEl.classList.add('is-invalid');
                    showAuthToast('Input Kosong', 'Harap masukkan kata sandi Anda.');
                    pwdEl.focus();
                    return;
                }

                                fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email: identifier, password: password })
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200) {
                        try {
                            localStorage.setItem('sweetdreams_auth_user', JSON.stringify(body.user));
                        } catch(e) {}
                        showAuthToast('Masuk Berhasil!', `Selamat datang kembali, ${body.user.name}!`, true);
                        setTimeout(() => {
                            window.location.href = body.user.role === 'admin' ? '/admin/dashboard' : '/profil';
                        }, 1000);
                    } else {
                        idEl.classList.add('is-invalid');
                        pwdEl.classList.add('is-invalid');
                        showAuthToast('Gagal Masuk', body.message || 'Email atau kata sandi salah.');
                    }
                })
                .catch(() => {
                    showAuthToast('Terjadi Kesalahan', 'Tidak bisa menghubungi server, coba lagi.');
                });
            });
        }

        // Handle Register Submission
        const formRegister = document.getElementById('form-register');
        if (formRegister) {
            formRegister.addEventListener('submit', function(e) {
                e.preventDefault();
                const name = document.getElementById('reg-name').value.trim();
                const email = document.getElementById('reg-email').value.trim();
                const username = document.getElementById('reg-username').value.trim();
                const password = document.getElementById('reg-password').value;
                const confirmPassword = document.getElementById('reg-password-confirm').value;
                const terms = document.getElementById('reg-terms').checked;

                if (name.length < 3) {
                    showAuthToast('Validasi Gagal', 'Nama lengkap minimal 3 karakter.');
                    document.getElementById('reg-name').focus();
                    return;
                }

                if (!email.includes('@') || !email.includes('.')) {
                    showAuthToast('Validasi Gagal', 'Format email tidak valid.');
                    document.getElementById('reg-email').focus();
                    return;
                }

                if (username.length < 3) {
                    showAuthToast('Validasi Gagal', 'Username minimal 3 karakter tanpa spasi.');
                    document.getElementById('reg-username').focus();
                    return;
                }

                if (password.length < 8) {
                    showAuthToast('Validasi Gagal', 'Kata sandi minimal 8 karakter demi keamanan akun.');
                    document.getElementById('reg-password').focus();
                    return;
                }

                if (password !== confirmPassword) {
                    showAuthToast('Validasi Gagal', 'Konfirmasi kata sandi tidak cocok.');
                    document.getElementById('reg-password-confirm').focus();
                    return;
                }

                if (!terms) {
                    showAuthToast('Persetujuan Diperlukan', 'Harap centang persetujuan syarat dan ketentuan.');
                    return;
                }

                const selectedAvatar = document.getElementById('reg-selected-avatar')?.value || 'images/avatars/avatar-1.svg';
                fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        avatar: selectedAvatar
                    })
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 201) {
                        try {
                            localStorage.setItem('sweetdreams_auth_user', JSON.stringify(body.user));
                        } catch(e) {}
                        showAuthToast('Pendaftaran Berhasil!', `Selamat datang di Sweet Dreams, ${body.user.name}!`, true);
                        setTimeout(() => {
                            window.location.href = '/profil';
                        }, 1200);
                    } else {
                        const msg = body.message || (body.errors ? Object.values(body.errors)[0][0] : 'Pendaftaran gagal, coba lagi.');
                        showAuthToast('Gagal Mendaftar', msg);
                    }
                })
                .catch(() => {
                    showAuthToast('Terjadi Kesalahan', 'Tidak bisa menghubungi server, coba lagi.');
                });
            });
        }

        // Handle Avatar Picker Clicks in Register Form
        document.querySelectorAll('.reg-avatar-option-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.reg-avatar-option-btn').forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
                const avatarPath = this.getAttribute('data-path');
                const avatarName = this.getAttribute('data-name');
                const previewImg = document.getElementById('reg-avatar-preview');
                const nameBadge = document.getElementById('reg-avatar-name-badge');
                const hiddenInput = document.getElementById('reg-selected-avatar');

                if (previewImg) previewImg.src = '/' + avatarPath;
                if (nameBadge) nameBadge.textContent = avatarName;
                if (hiddenInput) hiddenInput.value = avatarPath;
            });
        });

        // Check if redirected from logout
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'logout') {
            try {
                localStorage.removeItem(window.SweetDreamsAuth ? window.SweetDreamsAuth.USER_KEY : 'sweetdreams_auth_user');
            } catch(e) {}
            showAuthToast('Berhasil Keluar', 'Anda telah berhasil keluar dari akun Sweet Dreams.', true);
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>
</html>
