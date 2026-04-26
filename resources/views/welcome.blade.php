@php
    $currentPage = $page ?? 'home';

    $pages = [
        'home' => [
            'route' => url('/'),
            'title' => 'KinaJ&aacute; - Delivery moderno em Angola',
            'bodyClass' => 'page-home',
            'heroImage' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=1100&q=85',
            'heroAlt' => 'Hamburger artesanal com batatas fritas',
            'badgeKey' => 'home.badge',
            'titleKey' => 'home.title',
            'leadKey' => 'home.lead',
            'primaryKey' => 'home.primary',
            'secondaryKey' => 'home.secondary',
        ],
        'partner' => [
            'route' => url('/seja-parceiro'),
            'title' => 'KinaJ&aacute; - Seja parceiro',
            'bodyClass' => 'page-partner',
            'heroImage' => 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?auto=format&fit=crop&w=1100&q=85',
            'heroAlt' => 'Pizza quente preparada para entrega',
            'badgeKey' => 'partner.badge',
            'titleKey' => 'partner.title',
            'leadKey' => 'partner.lead',
            'primaryKey' => 'partner.primary',
            'secondaryKey' => 'partner.secondary',
        ],
        'careers' => [
            'route' => url('/carreiras'),
            'title' => 'KinaJ&aacute; - Carreiras',
            'bodyClass' => 'page-careers',
            'heroImage' => 'https://images.unsplash.com/photo-1526367790999-0150786686a2?auto=format&fit=crop&w=1100&q=85',
            'heroAlt' => 'Entrega urbana de comida',
            'badgeKey' => 'careers.badge',
            'titleKey' => 'careers.title',
            'leadKey' => 'careers.lead',
            'primaryKey' => 'careers.primary',
            'secondaryKey' => 'careers.secondary',
        ],
    ];

    $content = $pages[$currentPage] ?? $pages['home'];
@endphp
<!DOCTYPE html>
<html lang="pt" data-language="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="KinaJ&aacute; entrega comida, mercado e essenciais em Luanda com rapidez, cuidado e tecnologia.">
    <title>{!! $content['title'] !!}</title>
    <link rel="preconnect" href="https://images.unsplash.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            /* CORES PRINCIPAIS (extraídas do logo) */
            --primary: #FF4D00;
            /* laranja forte */
            --primary-dark: #D93A00;
            /* vermelho escuro */
            --primary-light: #FF7A1A;
            /* laranja claro */

            /* ACCENT (amarelo do brilho/estrela) */
            --accent: #FFD166;

            /* BACKGROUNDS */
            --background: #FFF7F2;
            /* leve tom quente */
            --background-gray: #F5F5F5;
            --surface: #FFFFFF;

            /* TEXTO */
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --text-muted: #9CA3AF;

            /* BORDAS */
            --border: #F1F1F1;
            --border-medium: #E5E7EB;

            /* DARK */
            --dark: #111827;

            /* STATUS */
            --success: #10B981;
            --warning: #F59E0B;
            --info: #3B82F6;

            /* SOMBRAS */
            --shadow-light: rgba(0, 0, 0, 0.06);
            --shadow-primary: rgba(255, 77, 0, 0.25);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-width: 320px;
            margin: 0;
            background: var(--background);
            color: var(--text-primary);
            font-family: "Instrument Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            letter-spacing: 0;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .announce {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            min-height: 44px;
            padding: 8px 20px;
            background: #fff3f4;
            color: var(--primary);
            border-bottom: 1px solid #ffd7db;
            font-size: 0.92rem;
            font-weight: 600;
            text-align: center;
        }

        .announce a {
            min-height: 28px;
            display: inline-flex;
            align-items: center;
            border: 1px solid rgba(235, 40, 53, 0.18);
            border-radius: 6px;
            padding: 0 12px;
            background: var(--surface);
            color: var(--dark);
            font-size: 0.82rem;
        }

        .site-header {
            border-bottom: 1px solid var(--border-medium);
            background: var(--surface);
        }

        .nav {
            width: min(1180px, calc(100% - 40px));
            min-height: 76px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
            font-size: 1.42rem;
            font-weight: 900;
        }

        .brand-logo {
            height: 48px;
            /* Adjust as needed */
            width: auto;
            display: block;
        }

        .nav-links,
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .nav-links a {
            color: var(--dark);
            font-size: 0.96rem;
            font-weight: 700;
        }

        .nav-links a[aria-current="page"] {
            color: var(--primary);
        }

        .menu-toggle {
            display: none;
            width: 46px;
            height: 46px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            border: 1px solid var(--border-medium);
            border-radius: 12px;
            background: var(--surface);
            cursor: pointer;
            box-shadow: 0 12px 28px var(--shadow-light);
        }

        .menu-toggle span {
            width: 20px;
            height: 2px;
            border-radius: 999px;
            background: var(--dark);
            transition: transform 180ms ease, opacity 180ms ease;
        }

        .menu-toggle.is-open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .menu-toggle.is-open span:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle.is-open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        .mobile-menu {
            display: none;
        }

        .lang-toggle {
            display: inline-grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px;
            padding: 3px;
            border: 1px solid var(--border-medium);
            border-radius: 999px;
            background: var(--background-gray);
        }

        .lang-toggle button {
            min-width: 42px;
            min-height: 32px;
            border: 0;
            border-radius: 999px;
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            font: inherit;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .lang-toggle button.is-active {
            background: var(--primary);
            color: var(--surface);
            box-shadow: 0 8px 22px rgba(235, 40, 53, 0.22);
        }

        .btn {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            border: 1px solid var(--border-medium);
            border-radius: 8px;
            padding: 0 22px;
            background: var(--surface);
            color: var(--text-primary);
            font-weight: 800;
            transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 36px var(--shadow-light);
        }

        .btn-primary {
            border-color: var(--primary);
            background: var(--primary);
            color: var(--surface);
            box-shadow: 0 18px 38px var(--shadow-red);
        }

        .hero-shell {
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid var(--border-medium);
            background:
                radial-gradient(circle at 80% 18%, rgba(235, 40, 53, 0.12), transparent 26%),
                linear-gradient(30deg, rgba(229, 231, 235, 0.5) 1px, transparent 1px),
                linear-gradient(150deg, rgba(229, 231, 235, 0.42) 1px, transparent 1px),
                var(--background);
            background-size: auto, 44px 44px, 44px 44px, auto;
        }

        .hero {
            width: min(1180px, calc(100% - 40px));
            min-height: 680px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(0, 0.9fr) minmax(420px, 1fr);
            align-items: center;
            gap: 58px;
            padding: 66px 0 72px;
        }

        .hero-copy {
            animation: fade-up 700ms ease both;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
            font-size: 0.9rem;
            font-weight: 900;
        }

        .eyebrow::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--primary);
            box-shadow: 0 0 0 8px rgba(235, 40, 53, 0.12);
            animation: pulse-dot 1500ms ease-in-out infinite;
        }

        .hero h1 {
            max-width: 680px;
            margin: 24px 0 22px;
            color: #151515;
            font-size: clamp(3.6rem, 6vw, 6.25rem);
            font-weight: 600;
            line-height: 0.95;
        }

        .hero h1 span {
            color: var(--primary);
        }

        .lead {
            max-width: 600px;
            margin: 0;
            color: var(--text-secondary);
            font-size: clamp(1.1rem, 1.8vw, 1.36rem);
            font-weight: 600;
            line-height: 1.5;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
            margin-top: 34px;
        }

        .hero-points {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            max-width: 610px;
            margin-top: 40px;
            border: 1px solid var(--border-medium);
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(14px);
        }

        .hero-point {
            min-height: 104px;
            padding: 20px;
            border-right: 1px solid var(--border-medium);
        }

        .hero-point:last-child {
            border-right: 0;
        }

        .hero-point strong {
            display: block;
            color: var(--dark);
            font-size: 1.5rem;
            font-weight: 900;
            line-height: 1;
        }

        .hero-point span {
            display: block;
            margin-top: 10px;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1.35;
        }

        .hero-media {
            position: relative;
            min-height: 560px;
            animation: float-in 900ms ease both 120ms;
        }

        .food-card {
            position: absolute;
            inset: 20px 0 auto auto;
            width: min(520px, 100%);
            overflow: hidden;
            border: 10px solid var(--surface);
            border-radius: 36px;
            background: var(--surface);
            box-shadow: 0 32px 80px rgba(17, 24, 39, 0.18);
            transform: rotate(2deg);
            animation: float-card 4200ms ease-in-out infinite;
        }

        .food-card img {
            width: 100%;
            height: 570px;
            object-fit: cover;
        }

        .food-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 42%, rgba(17, 24, 39, 0.46));
        }

        .order-badge {
            position: absolute;
            left: 8px;
            bottom: 58px;
            z-index: 2;
            width: min(280px, 62%);
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 24px 60px rgba(17, 24, 39, 0.16);
            animation: badge-pop 2800ms ease-in-out infinite;
        }

        .order-badge strong {
            display: block;
            margin-bottom: 6px;
            color: var(--primary);
            font-size: 1rem;
            font-weight: 900;
        }

        .order-badge span {
            display: block;
            color: var(--text-secondary);
            font-weight: 700;
            line-height: 1.35;
        }

        .spice-orbit {
            position: absolute;
            right: 8px;
            top: 36px;
            z-index: 3;
            display: grid;
            place-items: center;
            width: 116px;
            height: 116px;
            border-radius: 50%;
            background: var(--accent);
            color: var(--primary-dark);
            box-shadow: 0 20px 52px rgba(235, 40, 53, 0.16);
            font-size: 0.82rem;
            font-weight: 900;
            text-align: center;
            animation: spin-soft 11500ms linear infinite;
        }

        .section {
            padding: 86px 0;
        }

        .section.white {
            background: var(--surface);
        }

        .section-inner {
            width: min(1180px, calc(100% - 40px));
            margin: 0 auto;
        }

        .section-heading {
            display: grid;
            grid-template-columns: minmax(0, 0.92fr) minmax(280px, 0.58fr);
            gap: 48px;
            align-items: end;
            margin-bottom: 36px;
        }

        .section-heading h2 {
            margin: 0;
            color: #151515;
            font-size: clamp(2.3rem, 4vw, 4.45rem);
            font-weight: 600;
            line-height: 1;
        }

        .section-heading p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 1.05rem;
            font-weight: 600;
            line-height: 1.58;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            border-top: 1px solid var(--border-medium);
            border-left: 1px solid var(--border-medium);
        }

        .feature-card {
            min-height: 264px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 24px;
            padding: 28px;
            border-right: 1px solid var(--border-medium);
            border-bottom: 1px solid var(--border-medium);
            background: var(--surface);
            transition: transform 180ms ease, background 180ms ease, box-shadow 180ms ease;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            background: #fff7f8;
            box-shadow: 0 22px 60px var(--shadow-light);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: #fff0f1;
            color: var(--primary);
            font-weight: 900;
        }

        .feature-card h3 {
            margin: 0 0 10px;
            color: var(--dark);
            font-size: 1.28rem;
            font-weight: 900;
        }

        .feature-card p {
            margin: 0;
            color: var(--text-secondary);
            font-weight: 600;
            line-height: 1.55;
        }

        .feature-card a {
            color: var(--primary);
            font-weight: 900;
        }

        .page-panel {
            display: grid;
            grid-template-columns: minmax(0, 0.7fr) minmax(320px, 0.55fr);
            gap: 28px;
            align-items: stretch;
        }

        .impact-panel,
        .signup-panel {
            border: 1px solid var(--border-medium);
            background: var(--surface);
            padding: 34px;
        }

        .impact-panel {
            min-height: 430px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: var(--surface);
            background:
                linear-gradient(135deg, rgba(235, 40, 53, 0.92), rgba(209, 29, 41, 0.88)),
                url("https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1100&q=82");
            background-size: cover;
            background-position: center;
        }

        .impact-panel h2 {
            max-width: 640px;
            margin: 0;
            font-size: clamp(2.6rem, 5vw, 5.2rem);
            font-weight: 900;
            line-height: 0.95;
        }

        .impact-panel p {
            max-width: 560px;
            margin: 18px 0 0;
            color: rgba(255, 255, 255, 0.84);
            font-size: 1.08rem;
            font-weight: 700;
            line-height: 1.55;
        }

        .signup-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 28px;
        }

        .signup-panel h2 {
            margin: 0 0 20px;
            color: var(--dark);
            font-size: 1.5rem;
            font-weight: 900;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 700;
        }

        .form-input {
            width: 100%;
            height: 44px;
            padding: 0 14px;
            border: 1px solid var(--border-medium);
            border-radius: 8px;
            background: var(--background);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.95rem;
            transition: border-color 150ms ease, box-shadow 150ms ease;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(235, 40, 53, 0.15);
        }

        .form-message {
            margin-top: -8px;
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            display: none;
        }

        .form-message.is-error {
            display: block;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .form-message.is-success {
            display: block;
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .app-download {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 18% 20%, rgba(235, 40, 53, 0.12), transparent 28%),
                radial-gradient(circle at 82% 72%, rgba(251, 239, 184, 0.76), transparent 24%),
                var(--background);
        }

        .app-download-card {
            display: grid;
            grid-template-columns: minmax(0, 0.92fr) minmax(300px, 0.58fr);
            gap: 42px;
            align-items: center;
            border: 1px solid var(--border-medium);
            background: rgba(255, 255, 255, 0.84);
            box-shadow: 0 30px 80px var(--shadow-light);
            padding: clamp(28px, 5vw, 54px);
        }

        .app-download-copy {
            animation: fade-up 700ms ease both;
        }

        .app-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
            font-size: 0.86rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        .app-kicker::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary);
        }

        .app-download h2 {
            max-width: 680px;
            margin: 18px 0 16px;
            color: #151515;
            font-size: clamp(2.35rem, 4.5vw, 5rem);
            font-weight: 900;
            line-height: 0.96;
        }

        .app-download p {
            max-width: 620px;
            margin: 0;
            color: var(--text-secondary);
            font-size: 1.08rem;
            font-weight: 700;
            line-height: 1.58;
        }

        .store-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 30px;
        }

        .store-button {
            min-width: 188px;
            min-height: 64px;
            display: inline-flex;
            align-items: center;
            gap: 13px;
            border: 1px solid rgba(17, 24, 39, 0.14);
            border-radius: 12px;
            padding: 10px 16px;
            background: var(--dark);
            color: var(--surface);
            box-shadow: 0 18px 40px rgba(17, 24, 39, 0.16);
            transition: transform 180ms ease, box-shadow 180ms ease;
        }

        .store-button:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 54px rgba(17, 24, 39, 0.22);
        }

        .store-icon {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 1.15rem;
            font-weight: 900;
        }

        .store-button small,
        .store-button strong {
            display: block;
            line-height: 1.05;
        }

        .store-button small {
            color: rgba(255, 255, 255, 0.74);
            font-size: 0.75rem;
            font-weight: 700;
        }

        .store-button strong {
            margin-top: 4px;
            color: var(--surface);
            font-size: 1.2rem;
            font-weight: 900;
        }

        .phone-preview {
            position: relative;
            width: min(280px, 100%);
            min-height: 520px;
            margin: 0 auto;
            border: 10px solid var(--dark);
            border-radius: 38px;
            background: var(--surface);
            box-shadow: 0 28px 70px rgba(17, 24, 39, 0.2);
            overflow: hidden;
            animation: float-card 4600ms ease-in-out infinite;
        }

        .phone-notch {
            width: 104px;
            height: 24px;
            margin: 10px auto 0;
            border-radius: 999px;
            background: var(--dark);
        }

        .phone-screen {
            padding: 18px;
        }

        .phone-screen h3 {
            margin: 16px 0 12px;
            color: var(--dark);
            font-size: 1.6rem;
            line-height: 1;
        }

        .phone-food {
            height: 190px;
            overflow: hidden;
            border-radius: 24px;
            background: var(--primary);
        }

        .phone-food img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .phone-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 12px;
            padding: 12px;
            border-radius: 16px;
            background: var(--background-gray);
            color: var(--text-secondary);
            font-size: 0.86rem;
            font-weight: 800;
        }

        .phone-row strong {
            color: var(--primary);
        }

        .site-footer {
            background: var(--primary);
            color: var(--surface);
        }

        .footer-promo {
            display: grid;
            grid-template-columns: minmax(0, 0.42fr) minmax(320px, 1fr);
            min-height: 210px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.18);
            background: var(--primary);
        }

        .footer-promo-image {
            min-height: 210px;
            background:
                radial-gradient(circle at 28% 34%, rgba(251, 239, 184, 0.95) 0 18px, transparent 19px),
                radial-gradient(circle at 68% 55%, rgba(255, 255, 255, 0.9) 0 13px, transparent 14px),
                repeating-linear-gradient(135deg, rgba(255, 255, 255, 0.14) 0 2px, transparent 2px 18px),
                var(--primary);
            background-size: auto;
            background-position: center;
        }

        .footer-promo-copy {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
            padding: 34px min(6vw, 72px);
            background: var(--primary);
        }

        .footer-promo-copy h2 {
            max-width: 620px;
            margin: 0;
            font-size: clamp(2rem, 4vw, 4rem);
            font-weight: 900;
            line-height: 0.95;
            text-transform: uppercase;
        }

        .footer-promo-copy span {
            display: inline-flex;
            min-width: 142px;
            min-height: 142px;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.62);
            border-radius: 50%;
            color: var(--accent);
            text-align: center;
            font-size: 0.88rem;
            font-weight: 900;
            text-transform: uppercase;
            animation: spin-soft 14000ms linear infinite;
        }

        .footer-main {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(240px, 0.65fr);
            min-height: 300px;
        }

        .footer-links {
            width: min(100%, 980px);
            display: grid;
            grid-template-columns: repeat(3, minmax(150px, 1fr));
            gap: 40px;
            padding: 60px min(6vw, 96px);
        }

        .footer-col strong {
            display: block;
            margin-bottom: 14px;
            color: var(--surface);
            font-size: 1.1rem;
            font-weight: 900;
        }

        .footer-col a,
        .footer-col span {
            display: block;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.84);
            font-weight: 600;
        }

        .footer-brand-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 24px;
            padding: 54px min(6vw, 72px);
            background: var(--primary-dark);
        }

        .footer-logo {
            font-size: clamp(2.5rem, 5vw, 4.8rem);
            font-weight: 900;
            line-height: 0.92;
        }

        .footer-tagline {
            max-width: 260px;
            color: var(--accent);
            font-size: 2rem;
            font-weight: 900;
            line-height: 0.9;
            text-transform: uppercase;
        }

        .footer-bottom {
            padding: 0 min(6vw, 96px) 38px;
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.86rem;
            font-weight: 600;
        }

        @keyframes fade-up {
            from {
                opacity: 0;
                transform: translateY(22px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float-in {
            from {
                opacity: 0;
                transform: translateY(34px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes float-card {

            0%,
            100% {
                transform: rotate(2deg) translateY(0);
            }

            50% {
                transform: rotate(-1deg) translateY(-14px);
            }
        }

        @keyframes badge-pop {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-8px) scale(1.03);
            }
        }

        @keyframes pulse-dot {

            0%,
            100% {
                box-shadow: 0 0 0 8px rgba(235, 40, 53, 0.12);
            }

            50% {
                box-shadow: 0 0 0 14px rgba(235, 40, 53, 0.04);
            }
        }

        @keyframes spin-soft {
            to {
                transform: rotate(360deg);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 1ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 1ms !important;
            }
        }

        @media (max-width: 980px) {
            .nav {
                min-height: 72px;
                align-items: center;
                flex-direction: row;
                padding: 0;
            }

            .nav-links,
            .nav-actions {
                display: none;
            }

            .menu-toggle {
                display: inline-flex;
            }

            .mobile-menu {
                width: min(1180px, calc(100% - 40px));
                max-height: 0;
                display: grid;
                gap: 0;
                margin: 0 auto;
                overflow: hidden;
                opacity: 0;
                pointer-events: none;
                visibility: hidden;
                transition: max-height 220ms ease, opacity 180ms ease, padding 220ms ease, visibility 220ms ease;
            }

            .mobile-menu.is-open {
                max-height: 430px;
                padding: 0 0 22px;
                opacity: 1;
                pointer-events: auto;
                visibility: visible;
            }

            .mobile-menu a {
                min-height: 52px;
                display: flex;
                align-items: center;
                border-bottom: 1px solid var(--border-medium);
                color: var(--dark);
                font-size: 1.02rem;
                font-weight: 900;
            }

            .mobile-menu a[aria-current="page"] {
                color: var(--primary);
            }

            .mobile-language {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding-top: 18px;
            }

            .mobile-language>span {
                color: var(--text-secondary);
                font-weight: 900;
            }

            .hero,
            .section-heading,
            .page-panel,
            .app-download-card,
            .footer-promo,
            .footer-main {
                grid-template-columns: 1fr;
            }

            .hero {
                min-height: auto;
                padding-top: 52px;
            }

            .hero-media {
                min-height: 500px;
            }

            .food-card {
                left: 0;
                right: auto;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

            .footer-links {
                width: 100%;
            }
        }

        @media (max-width: 660px) {
            .announce {
                align-items: flex-start;
                flex-direction: column;
                text-align: left;
            }

            .nav,
            .mobile-menu,
            .hero,
            .section-inner {
                width: min(100% - 28px, 1180px);
            }

            .hero h1 {
                font-size: clamp(2.9rem, 13vw, 4rem);
            }

            .hero-points {
                grid-template-columns: 1fr;
            }

            .hero-point {
                border-right: 0;
                border-bottom: 1px solid var(--border-medium);
            }

            .hero-point:last-child {
                border-bottom: 0;
            }

            .hero-media {
                min-height: 420px;
            }

            .food-card img {
                height: 420px;
            }

            .spice-orbit {
                width: 96px;
                height: 96px;
            }

            .section {
                padding: 62px 0;
            }

            .page-panel {
                gap: 16px;
            }

            .app-download-card {
                padding: 24px;
            }

            .store-button {
                width: 100%;
            }

            .phone-preview {
                min-height: 470px;
            }

            .impact-panel,
            .signup-panel {
                padding: 24px;
            }

            .footer-promo-copy {
                align-items: flex-start;
                flex-direction: column;
                padding: 30px 24px;
            }

            .footer-links {
                grid-template-columns: 1fr;
                padding: 40px 24px;
            }

            .footer-brand-panel {
                padding: 40px 24px;
            }

            .footer-bottom {
                padding: 0 24px 34px;
            }
        }
    </style>
</head>

<body class="{{ $content['bodyClass'] }}">


    <header class="site-header">
        <nav class="nav" aria-label="Navega&ccedil;&atilde;o principal">
            <a href="{{ url('/') }}" class="brand" aria-label="KinaJ&aacute;">
                <img src="{{ asset('images/logo.png') }}" alt="KinaJ&aacute; Logo" class="brand-logo">
            </a>

            <div class="nav-links">
                <a href="{{ url('/') }}" data-i18n="nav.home" @if($currentPage === 'home') aria-current="page"
                @endif>HOME</a>
                <a href="{{ url('/seja-parceiro') }}" data-i18n="nav.partner" @if($currentPage === 'partner')
                aria-current="page" @endif>SEJA PARCEIRO</a>
                <a href="{{ url('/carreiras') }}" data-i18n="nav.careers" @if($currentPage === 'careers')
                aria-current="page" @endif>CARREIRAS</a>
            </div>

            <div class="nav-actions">
                <div class="lang-toggle" aria-label="Selecionar idioma">
                    <button type="button" class="is-active" data-lang="pt">PT</button>
                    <button type="button" data-lang="en">EN</button>
                </div>
            </div>

            <button class="menu-toggle" type="button" aria-label="Abrir menu" aria-controls="mobile-menu"
                aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>

        <div class="mobile-menu" id="mobile-menu" aria-hidden="true">
            <a href="{{ url('/') }}" data-i18n="nav.home" @if($currentPage === 'home') aria-current="page" @endif>HOME</a>
            <a href="{{ url('/seja-parceiro') }}" data-i18n="nav.partner" @if($currentPage === 'partner')
            aria-current="page" @endif>SEJA PARCEIRO</a>
            <a href="{{ url('/carreiras') }}" data-i18n="nav.careers" @if($currentPage === 'careers') aria-current="page"
            @endif>CARREIRAS</a>
            <div class="mobile-language">
                <span data-i18n="mobile.language">Idioma</span>
                <div class="lang-toggle" aria-label="Selecionar idioma">
                    <button type="button" class="is-active" data-lang="pt">PT</button>
                    <button type="button" data-lang="en">EN</button>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="hero-shell">
            <div class="hero">
                <div class="hero-copy">
                    <span class="eyebrow" data-i18n="{{ $content['badgeKey'] }}">Delivery em Angola</span>
                    <h1 data-i18n-html="{{ $content['titleKey'] }}">Se est&aacute;s com fome pe&ccedil;a um <span>Kina
                            J&aacute;</span></h1>
                    <p class="lead" data-i18n="{{ $content['leadKey'] }}">
                        Comida, mercado e essenciais entregues com rapidez, cuidado e acompanhamento simples.
                    </p>

                    <div class="hero-actions">
                        <a class="btn btn-primary" href="{{ $currentPage === 'home' ? '#baixar-app' : '#contacto' }}"
                            data-i18n="{{ $content['primaryKey'] }}">Baixar App</a>
                        <a class="btn"
                            href="{{ $currentPage === 'careers' ? url('/seja-parceiro') : url('/carreiras') }}"
                            data-i18n="{{ $content['secondaryKey'] }}">Ver carreiras</a>
                    </div>

                    <div class="hero-points" aria-label="Destaques">
                        <div class="hero-point">
                            <strong>25m</strong>
                            <span data-i18n="stats.delivery">tempo m&eacute;dio de entrega</span>
                        </div>
                        <div class="hero-point">
                            <strong>80+</strong>
                            <span data-i18n="stats.partners">parceiros ativos</span>
                        </div>
                        <div class="hero-point">
                            <strong>15k</strong>
                            <span data-i18n="stats.orders">pedidos preparados</span>
                        </div>
                    </div>
                </div>

                <div class="hero-media" aria-label="Comida KinaJ&aacute;">
                    <div class="spice-orbit" data-i18n-html="hero.orbit">quente<br>rápido<br>saboroso</div>
                    <figure class="food-card">
                        <img src="{{ $content['heroImage'] }}" alt="{{ $content['heroAlt'] }}">
                    </figure>
                    <div class="order-badge">
                        <strong data-i18n="hero.badgeTitle">Pedido confirmado</strong>
                        <span data-i18n="hero.badgeText">A sua comida est&aacute; a caminho.</span>
                    </div>
                </div>
            </div>
        </section>

        @if ($currentPage === 'home')
            <section class="section white" id="servicos">
                <div class="section-inner">
                    <div class="section-heading">
                        <h2 data-i18n="home.sectionTitle">Tudo o que precisas numa s&oacute; plataforma.</h2>
                        <p data-i18n="home.sectionLead">KinaJ&aacute; liga clientes, restaurantes e estafetas numa
                            experi&ecirc;ncia simples, moderna e preparada para crescer.</p>
                    </div>

                    <div class="feature-grid">
                        <article class="feature-card">
                            <div>
                                <div class="feature-icon">01</div>
                                <h3 data-i18n="home.card1.title">Restaurantes</h3>
                                <p data-i18n="home.card1.text">Descobre pratos quentes, snacks e menus completos perto de
                                    ti.</p>
                            </div>
                            <a href="{{ url('/seja-parceiro') }}" data-i18n="home.cardLink">Explorar &rarr;</a>
                        </article>

                        <article class="feature-card">
                            <div>
                                <div class="feature-icon">02</div>
                                <h3 data-i18n="home.card3.title">Entrega expressa</h3>
                                <p data-i18n="home.card3.text">Acompanha cada pedido desde a prepara&ccedil;&atilde;o
                                    at&eacute; &agrave; porta.</p>
                            </div>
                            <a href="{{ url('/carreiras') }}" data-i18n="home.card3.link">Juntar &agrave; equipa &rarr;</a>
                        </article>
                    </div>
                </div>
            </section>
        @elseif ($currentPage === 'partner')
            <section class="section white" id="contacto">
                <div class="section-inner page-panel">
                    <div class="impact-panel">
                        <div>
                            <h2 data-i18n="partner.panelTitle">Venda mais sem complicar a opera&ccedil;&atilde;o.</h2>
                            <p data-i18n="partner.panelLead">Publique o seu cat&aacute;logo, receba pedidos e alcance
                                clientes que querem comprar agora.</p>
                        </div>
                    </div>
                    <aside class="signup-panel">
                        <form id="partnerForm" class="registration-form"
                            onsubmit="submitRegistration(event, 'restaurant_owner')">
                            <h2 data-i18n="partner.formTitle">Crie a sua conta de parceiro</h2>
                            <div class="form-group">
                                <label data-i18n="form.name">Nome do Estabelecimento</label>
                                <input type="text" name="name" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label data-i18n="form.phone">Telefone</label>
                                <input type="text" name="phone" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label data-i18n="form.email">E-mail</label>
                                <input type="email" name="email" class="form-input">
                            </div>
                            <div class="form-group">
                                <label data-i18n="form.address">Endereço</label>
                                <input type="text" name="address" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label data-i18n="form.nif">NIF</label>
                                <input type="text" name="nif" class="form-input" required>
                            </div>

                            <div class="form-message" id="partnerMessage"></div>

                            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 8px;"
                                data-i18n="partner.submitBtn">Registar Parceiro</button>
                        </form>
                    </aside>
                </div>
            </section>
        @else
            <section class="section white" id="contacto">
                <div class="section-inner page-panel">
                    <div class="impact-panel">
                        <div>
                            <h2 data-i18n="careers.panelTitle">Constr&oacute;i a entrega que move a cidade.</h2>
                            <p data-i18n="careers.panelLead">Estamos a juntar operadores, suporte, tecnologia e estafetas
                                para criar uma experi&ecirc;ncia melhor em Angola.</p>
                        </div>
                    </div>
                    <aside class="signup-panel">
                        <form id="driverForm" class="registration-form" onsubmit="submitRegistration(event, 'driver')">
                            <h2 data-i18n="careers.formTitle">Registe-se como Entregador</h2>
                            <div class="form-group">
                                <label data-i18n="form.nameFull">Nome Completo</label>
                                <input type="text" name="name" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label data-i18n="form.phone">Telefone</label>
                                <input type="text" name="phone" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label data-i18n="form.emailOpt">E-mail (Opcional)</label>
                                <input type="email" name="email" class="form-input">
                            </div>
                            <div class="form-group">
                                <label data-i18n="form.ownsMotorcycle">Possui mota própria?</label>
                                <select name="owns_motorcycle" class="form-input" required style="appearance: auto;">
                                    <option value="1" data-i18n="form.yes">Sim</option>
                                    <option value="0" data-i18n="form.no">Não</option>
                                </select>
                            </div>

                            <div class="form-message" id="driverMessage"></div>

                            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 8px;"
                                data-i18n="careers.submitBtn">Candidatar-se</button>
                        </form>
                    </aside>
                </div>
            </section>
        @endif

        <section class="section app-download" id="baixar-app">
            <div class="section-inner">
                <div class="app-download-card">
                    <div class="app-download-copy">
                        <span class="app-kicker" data-i18n="app.badge">Apps KinaJ&aacute;</span>
                        <h2 data-i18n="app.title">Baixe o app e pe&ccedil;a em segundos.</h2>
                        <p data-i18n="app.lead">Tenha restaurantes, mercado e entregas expressas no bolso.
                            Dispon&iacute;vel para iPhone e Android.</p>

                        <div class="store-buttons" aria-label="Baixar apps">
                            <a class="store-button" href="https://apps.apple.com/" target="_blank" rel="noopener"
                                aria-label="Baixar na App Store">
                                <span class="store-icon">A</span>
                                <span>
                                    <small data-i18n="app.appleSmall">Baixar na</small>
                                    <strong data-i18n="app.apple">App Store</strong>
                                </span>
                            </a>
                            <a class="store-button" href="https://play.google.com/store/apps" target="_blank"
                                rel="noopener" aria-label="Baixar no Google Play">
                                <span class="store-icon">G</span>
                                <span>
                                    <small data-i18n="app.googleSmall">Dispon&iacute;vel no</small>
                                    <strong data-i18n="app.google">Google Play</strong>
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="phone-preview" aria-label="Previsualiza&ccedil;&atilde;o do app KinaJ&aacute;">
                        <div class="phone-notch"></div>
                        <div class="phone-screen">
                            <div class="phone-food">
                                <img src="https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?auto=format&fit=crop&w=700&q=82"
                                    alt="Pizza no app KinaJ&aacute;">
                            </div>
                            <h3>KinaJ&aacute;</h3>
                            <div class="phone-row">
                                <span data-i18n="app.preview1">Pizza favorita</span>
                                <strong>18m</strong>
                            </div>
                            <div class="phone-row">
                                <span data-i18n="app.preview2">Hamburger duplo</span>
                                <strong>25m</strong>
                            </div>
                            <div class="phone-row">
                                <span data-i18n="app.preview3">Mercado express</span>
                                <strong>30m</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer" id="contacto">
        <div class="footer-promo">
            <div class="footer-promo-image" aria-hidden="true"></div>
            <div class="footer-promo-copy">
                <h2 data-i18n="footer.promo">Bons momentos come&ccedil;am com fome.</h2>
                <span data-i18n="footer.seal">Feed good times</span>
            </div>
        </div>

        <div class="footer-main">
            <div class="footer-links">
                <div class="footer-col">
                    <strong data-i18n="footer.about">Sobre o KinaJ&aacute;</strong>
                    <a href="{{ url('/') }}" data-i18n="nav.home">Home</a>
                    <a href="{{ url('/seja-parceiro') }}" data-i18n="nav.partner">Seja parceiro</a>
                    <a href="{{ url('/carreiras') }}" data-i18n="nav.careers">Carreiras</a>
                </div>
                <div class="footer-col">
                    <strong data-i18n="footer.services">Servi&ccedil;os</strong>
                    <span data-i18n="footer.service1">Restaurantes</span>
                    <span data-i18n="footer.service3">Entregas expressas</span>
                </div>
                <div class="footer-col">
                    <strong data-i18n="footer.contacts">Contactos</strong>
                    <a href="mailto:hello@kinaja.ao">hello@kinaja.ao</a>
                    <a href="mailto:parceiros@kinaja.ao">parceiros@kinaja.ao</a>
                    <span>Luanda, Angola</span>
                </div>
            </div>

            <div class="footer-brand-panel">
                <div class="footer-logo">Kina<br>Já</div>
                <div class="footer-tagline" data-i18n="footer.tagline">Pede bom. Recebe rápido.</div>
            </div>
        </div>

        <div class="footer-bottom">&copy; 2026 KinaJ&aacute;. <span data-i18n="footer.rights">Todos os direitos
                reservados.</span></div>
    </footer>

    <script>
        const translations = {
            pt: {
                'announce.text': 'KinaJá está a chegar com entregas mais rápidas em Luanda.',
                'announce.cta': 'Junte-se',
                'nav.home': 'HOME',
                'nav.partner': 'SEJA PARCEIRO',
                'nav.careers': 'CARREIRAS',
                'nav.signin': 'ENTRAR',
                'mobile.language': 'Idioma',
                'home.badge': 'Delivery em Angola',
                'home.title': 'Se estás com fome peça um <span>Kina Já</span>',
                'home.lead': 'A sua comida favorita entregue com rapidez, cuidado e acompanhamento simples.',
                'home.primary': 'Baixar App',
                'home.secondary': 'Ver carreiras',
                'partner.badge': 'Venda com o KinaJá',
                'partner.title': 'Transforma pedidos em <span>crescimento.</span>',
                'partner.lead': 'Leve o seu restaurante para mais clientes com uma operação simples e moderna.',
                'partner.primary': 'Quero ser parceiro',
                'partner.secondary': 'Ver carreiras',
                'careers.badge': 'Carreiras KinaJá',
                'careers.title': 'Trabalha numa equipa que <span>entrega futuro.</span>',
                'careers.lead': 'Junta-te a uma equipa que está a criar tecnologia, operação e logística para Angola.',
                'careers.primary': 'Enviar candidatura',
                'careers.secondary': 'Seja parceiro',
                'stats.delivery': 'tempo médio de entrega',
                'stats.partners': 'parceiros ativos',
                'stats.orders': 'pedidos preparados',
                'hero.orbit': 'quente<br>rápido<br>saboroso',
                'hero.badgeTitle': 'Pedido confirmado',
                'hero.badgeText': 'A sua comida está a caminho.',
                'app.badge': 'Apps KinaJá',
                'app.title': 'Baixe o app e peça em segundos.',
                'app.lead': 'Tenha os melhores restaurantes e entregas expressas no bolso. Disponível para iPhone e Android.',
                'app.appleSmall': 'Baixar na',
                'app.apple': 'App Store',
                'app.googleSmall': 'Disponível no',
                'app.google': 'Google Play',
                'app.preview1': 'Pizza favorita',
                'app.preview2': 'Hamburger duplo',
                'app.preview3': 'Pratos locais',
                'home.sectionTitle': 'Tudo o que precisas numa só plataforma.',
                'home.sectionLead': 'KinaJá liga clientes, restaurantes e estafetas numa experiência simples, moderna e preparada para crescer.',
                'home.card1.title': 'Restaurantes',
                'home.card1.text': 'Descobre pratos quentes, snacks e menus completos perto de ti.',
                'home.card3.title': 'Entrega expressa',
                'home.card3.text': 'Acompanha cada pedido desde a preparação até à porta.',
                'home.cardLink': 'Explorar →',
                'home.card3.link': 'Juntar à equipa →',
                'partner.formTitle': 'Crie a sua conta de parceiro',
                'form.name': 'Nome do Estabelecimento',
                'form.nameFull': 'Nome Completo',
                'form.phone': 'Telefone',
                'form.email': 'E-mail',
                'form.emailOpt': 'E-mail (Opcional)',
                'form.address': 'Endereço',
                'form.nif': 'NIF',
                'form.ownsMotorcycle': 'Possui mota própria?',
                'form.yes': 'Sim',
                'form.no': 'Não',
                'partner.submitBtn': 'Registar Parceiro',
                'partner.panelTitle': 'Venda mais sem complicar a operação.',
                'partner.panelLead': 'Publique o seu catálogo, receba pedidos e alcance clientes que querem comprar agora.',
                'partner.sideTitle': 'Como funciona',
                'partner.step1': 'Registamos o seu restaurante.',
                'partner.step2': 'Configuramos menu, produtos e zonas de entrega.',
                'partner.step3': 'Começa a receber pedidos com acompanhamento.',
                'partner.email': 'Contactar equipa',
                'careers.formTitle': 'Registe-se como Entregador',
                'careers.submitBtn': 'Candidatar-se',
                'careers.panelTitle': 'Constrói a entrega que move a cidade.',
                'careers.panelLead': 'Estamos a juntar operadores, suporte, tecnologia e estafetas para criar uma experiência melhor em Angola.',
                'careers.sideTitle': 'Vagas abertas',
                'careers.role1': 'Estafetas parceiros',
                'careers.role2': 'Suporte ao cliente',
                'careers.role3': 'Operações e expansão',
                'careers.email': 'Enviar candidatura',
                'footer.promo': 'Bons momentos começam com fome.',
                'footer.seal': 'Feed good times',
                'footer.about': 'Sobre o KinaJá',
                'footer.services': 'Serviços',
                'footer.service1': 'Restaurantes',
                'footer.service3': 'Entregas expressas',
                'footer.contacts': 'Contactos',
                'footer.tagline': 'Pede bom. Recebe rápido.',
                'footer.rights': 'Todos os direitos reservados.'
            },
            en: {
                'announce.text': 'KinaJá is bringing faster delivery to Luanda.',
                'announce.cta': 'Join us',
                'nav.home': 'HOME',
                'nav.partner': 'BECOME A PARTNER',
                'nav.careers': 'CAREERS',
                'nav.signin': 'Sign in',
                'mobile.language': 'Language',
                'home.badge': 'Delivery in Angola',
                'home.title': 'If you are hungry, order <span>Kina Já</span>',
                'home.lead': 'Your favorite food delivered fast with care and simple tracking.',
                'home.primary': 'Download App',
                'home.secondary': 'View careers',
                'partner.badge': 'Sell with KinaJá',
                'partner.title': 'Turn orders into <span>growth.</span>',
                'partner.lead': 'Bring your restaurant to more customers with a simple modern operation.',
                'partner.primary': 'Become a partner',
                'partner.secondary': 'View careers',
                'careers.badge': 'KinaJá careers',
                'careers.title': 'Work with a team that <span>delivers the future.</span>',
                'careers.lead': 'Join a team building technology, operations, and logistics for Angola.',
                'careers.primary': 'Apply now',
                'careers.secondary': 'Become a partner',
                'stats.delivery': 'average delivery time',
                'stats.partners': 'active partners',
                'stats.orders': 'orders prepared',
                'hero.orbit': 'hot<br>fast<br>tasty',
                'hero.badgeTitle': 'Order confirmed',
                'hero.badgeText': 'Your food is on the way.',
                'app.badge': 'KinaJá apps',
                'app.title': 'Download the app and order in seconds.',
                'app.lead': 'Keep the best restaurants and express delivery in your pocket. Available for iPhone and Android.',
                'app.appleSmall': 'Download on the',
                'app.apple': 'App Store',
                'app.googleSmall': 'Get it on',
                'app.google': 'Google Play',
                'app.preview1': 'Favorite pizza',
                'app.preview2': 'Double burger',
                'app.preview3': 'Local dishes',
                'home.sectionTitle': 'Everything you need on one platform.',
                'home.sectionLead': 'KinaJá connects customers, restaurants, and couriers in a simple, modern experience built to grow.',
                'home.card1.title': 'Restaurants',
                'home.card1.text': 'Discover hot meals, snacks, and full menus near you.',
                'home.card3.title': 'Express delivery',
                'home.card3.text': 'Track every order from preparation to your door.',
                'home.cardLink': 'Explore →',
                'home.card3.link': 'Join the team →',
                'partner.formTitle': 'Create your partner account',
                'form.name': 'Establishment Name',
                'form.nameFull': 'Full Name',
                'form.phone': 'Phone Number',
                'form.email': 'Email',
                'form.emailOpt': 'Email (Optional)',
                'form.address': 'Address',
                'form.nif': 'Tax ID (NIF)',
                'form.ownsMotorcycle': 'Do you own a motorcycle?',
                'form.yes': 'Yes',
                'form.no': 'No',
                'partner.submitBtn': 'Register Partner',
                'partner.panelTitle': 'Sell more without complicating operations.',
                'partner.panelLead': 'Publish your catalog, receive orders, and reach customers ready to buy now.',
                'partner.sideTitle': 'How it works',
                'partner.step1': 'We register your restaurant.',
                'partner.step2': 'We configure menu, products, and delivery zones.',
                'partner.step3': 'You start receiving tracked orders.',
                'partner.email': 'Contact the team',
                'careers.formTitle': 'Register as a Courier',
                'careers.submitBtn': 'Apply now',
                'careers.panelTitle': 'Build the delivery network that moves the city.',
                'careers.panelLead': 'We are bringing together operations, support, technology, and couriers to build a better experience in Angola.',
                'careers.sideTitle': 'Open roles',
                'careers.role1': 'Courier partners',
                'careers.role2': 'Customer support',
                'careers.role3': 'Operations and expansion',
                'careers.email': 'Send application',
                'footer.promo': 'Good times start with hunger.',
                'footer.seal': 'Feed good times',
                'footer.about': 'About KinaJá',
                'footer.services': 'Services',
                'footer.service1': 'Restaurants',
                'footer.service3': 'Express delivery',
                'footer.contacts': 'Contacts',
                'footer.tagline': 'Order good. Get it fast.',
                'footer.rights': 'All rights reserved.'
            }
        };

        const root = document.documentElement;
        const langButtons = document.querySelectorAll('[data-lang]');
        const menuToggle = document.querySelector('.menu-toggle');
        const mobileMenu = document.querySelector('#mobile-menu');

        function setLanguage(language) {
            const dictionary = translations[language] || translations.pt;
            root.lang = language;
            root.dataset.language = language;

            document.querySelectorAll('[data-i18n]').forEach((element) => {
                const key = element.dataset.i18n;
                if (dictionary[key]) {
                    element.textContent = dictionary[key];
                }
            });

            document.querySelectorAll('[data-i18n-html]').forEach((element) => {
                const key = element.dataset.i18nHtml;
                if (dictionary[key]) {
                    element.innerHTML = dictionary[key];
                }
            });

            langButtons.forEach((button) => {
                button.classList.toggle('is-active', button.dataset.lang === language);
            });

            try {
                localStorage.setItem('kinaja-language', language);
            } catch (error) {
                // Ignore storage failures in private browsing contexts.
            }
        }

        document.addEventListener('click', (event) => {
            const languageButton = event.target.closest('[data-lang]');
            if (!languageButton) {
                return;
            }

            setLanguage(languageButton.dataset.lang);
        });

        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', () => {
                const isOpen = mobileMenu.classList.toggle('is-open');
                menuToggle.classList.toggle('is-open', isOpen);
                menuToggle.setAttribute('aria-expanded', String(isOpen));
                menuToggle.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
                mobileMenu.setAttribute('aria-hidden', String(!isOpen));
            });

            mobileMenu.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.remove('is-open');
                    menuToggle.classList.remove('is-open');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    menuToggle.setAttribute('aria-label', 'Abrir menu');
                    mobileMenu.setAttribute('aria-hidden', 'true');
                });
            });
        }

        let savedLanguage = 'pt';
        try {
            savedLanguage = localStorage.getItem('kinaja-language') || 'pt';
        } catch (error) {
            savedLanguage = 'pt';
        }

        setLanguage(savedLanguage);

        async function submitRegistration(event, role) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            data.role = role;

            const msgEl = form.querySelector('.form-message');
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.textContent;

            msgEl.className = 'form-message';
            msgEl.textContent = '';
            submitBtn.disabled = true;
            submitBtn.textContent = 'A processar...';

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok || response.status === 201) {
                    form.reset();
                    msgEl.textContent = result.message || 'Registo efetuado com sucesso! Aguarde validação.';
                    msgEl.classList.add('is-success');
                } else {
                    let errorMsg = result.message || 'Erro ao efetuar registo.';
                    if (result.errors) {
                        const firstErrorKey = Object.keys(result.errors)[0];
                        errorMsg = result.errors[firstErrorKey][0];
                    }
                    msgEl.textContent = errorMsg;
                    msgEl.classList.add('is-error');
                }
            } catch (err) {
                msgEl.textContent = 'Erro de conexão. Tente novamente.';
                msgEl.classList.add('is-error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
        }
    </script>
</body>

</html>