<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>403 - Permission Denied | Jharkhand State Housing Board</title>
<style>
  :root{
    --navy: #0B2A4A;
    --navy-deep: #071C33;
    --green: #1E7A46;
    --green-dark: #145934;
    --yellow: #F4B400;
    --red: #D32F2F;
    --red-dark: #B71C1C;
    --gray-bg: #EEF1F4;
    --gray-mid: #8A94A3;
    --gray-dark: #47505C;
    --white: #FFFFFF;
  }

  *{margin:0;padding:0;box-sizing:border-box;}

  body{
    min-height:100vh;
    background:
      radial-gradient(1200px 600px at 50% -10%, rgba(11,42,74,0.06), transparent 60%),
      var(--gray-bg);
    font-family: 'Segoe UI', 'Noto Sans', Arial, sans-serif;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
    overflow-x:hidden;
  }

  .page{
    width:100%;
    max-width:920px;
  }

  /* top government strip */
  .gov-strip{
    display:flex;
    align-items:center;
    gap:10px;
    background:var(--navy);
    color:var(--white);
    padding:10px 18px;
    border-radius:8px 8px 0 0;
    font-size:12.5px;
    letter-spacing:.3px;
  }
  .gov-strip .dot{
    width:8px;height:8px;border-radius:50%;
    background:var(--red);
    flex-shrink:0;
  }
  .gov-strip b{font-weight:600;}
  .gov-strip .divider{opacity:.35;margin:0 4px;}

  .card{
    background:var(--white);
    border-radius:0 0 12px 12px;
    box-shadow:0 12px 40px rgba(11,42,74,0.12);
    padding:48px 40px 40px;
    text-align:center;
    position:relative;
    border-bottom:5px solid var(--red);
  }

  /* scene */
  .scene{
    width:100%;
    max-width:420px;
    margin:0 auto 8px;
  }

  .code-row{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:6px;
    margin-top:6px;
  }
  .code-row .num{
    font-size:64px;
    font-weight:800;
    color:var(--navy);
    line-height:1;
    letter-spacing:-2px;
  }

  h1{
    margin-top:18px;
    font-size:24px;
    color:var(--navy-deep);
    font-weight:700;
  }

  p.sub{
    margin-top:10px;
    color:var(--gray-dark);
    font-size:15px;
    line-height:1.6;
    max-width:520px;
    margin-left:auto;
    margin-right:auto;
  }

  p.sub b{color:var(--red);}

  .actions{
    margin-top:30px;
    display:flex;
    gap:14px;
    justify-content:center;
    flex-wrap:wrap;
  }

  .btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:12px 24px;
    border-radius:8px;
    font-size:14.5px;
    font-weight:600;
    text-decoration:none;
    cursor:pointer;
    border:none;
    transition:transform .15s ease, box-shadow .15s ease, background .15s ease;
  }

  .btn-primary{
    background:var(--navy);
    color:var(--white);
    box-shadow:0 6px 16px rgba(11,42,74,0.3);
  }
  .btn-primary:hover{
    background:var(--navy-deep);
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(11,42,74,0.35);
  }

  .btn-secondary{
    background:var(--gray-bg);
    color:var(--navy-deep);
    border:1px solid #DCE1E7;
  }
  .btn-secondary:hover{
    background:#E3E7EC;
    transform:translateY(-2px);
  }

  .btn svg{width:16px;height:16px;flex-shrink:0;}

  .foot-note{
    margin-top:26px;
    font-size:12px;
    color:var(--gray-mid);
  }

  @media (max-width:520px){
    .card{padding:36px 20px 30px;}
    .code-row .num{font-size:48px;}
    h1{font-size:20px;}
  }

  /* ---------- SVG animations ---------- */
  .shield{
    transform-origin: 210px 135px;
    animation: float 4s ease-in-out infinite;
  }
  @keyframes float{
    0%,100%{ transform: translateY(0); }
    50%{ transform: translateY(-8px); }
  }

  .lock{
    transform-origin: 30px 45px;
    animation: lockShake 4s ease-in-out infinite;
  }
  @keyframes lockShake{
    0%, 85%, 100%{ transform: rotate(0); }
    90%{ transform: rotate(-8deg); }
    95%{ transform: rotate(8deg); }
  }

  .laser{
    animation: scan 3s infinite linear;
  }
  @keyframes scan{
    0%{ transform: translateY(-60px); opacity: 0; }
    15%{ opacity: 1; }
    85%{ opacity: 1; }
    100%{ transform: translateY(60px); opacity: 0; }
  }

  .server-light{
    animation: blink 2s infinite;
  }
  @keyframes blink{
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; fill: var(--red); }
  }

  @media (prefers-reduced-motion: reduce){
    .shield, .lock, .laser, .server-light{
      animation:none !important;
    }
  }
</style>
</head>
<body>

<div class="page">
  <div class="gov-strip">
    <span class="dot"></span>
    <b>झारखंड राज्य आवास बोर्ड</b>
    <span class="divider">|</span>
    <span>Jharkhand State Housing Board</span>
    <span class="divider">|</span>
    <span>Government of Jharkhand</span>
  </div>

  <div class="card">

    <div class="scene">
      <svg viewBox="0 0 420 260" xmlns="http://www.w3.org/2000/svg">
        <!-- ground -->
        <line x1="10" y1="225" x2="410" y2="225" stroke="#D7DCE2" stroke-width="2"/>
        
        <!-- Background Server Rack (System) -->
        <g stroke="#8A94A3" stroke-width="2" fill="none">
           <rect x="80" y="80" width="60" height="145" rx="4" />
           <line x1="80" y1="100" x2="140" y2="100" />
           <line x1="80" y1="120" x2="140" y2="120" />
           <line x1="80" y1="140" x2="140" y2="140" />
           <circle class="server-light" cx="130" cy="90" r="3" fill="#8A94A3" stroke="none" />
           <circle class="server-light" cx="130" cy="110" r="3" fill="#8A94A3" stroke="none" style="animation-delay: 0.5s;" />
           
           <rect x="280" y="100" width="60" height="125" rx="4" />
           <line x1="280" y1="120" x2="340" y2="120" />
           <line x1="280" y1="140" x2="340" y2="140" />
           <circle class="server-light" cx="330" cy="110" r="3" fill="#8A94A3" stroke="none" style="animation-delay: 0.8s;" />
        </g>

        <!-- Animated Shield -->
        <g class="shield">
          <!-- Outer Shield -->
          <path d="M 210 30 C 280 30 310 55 310 55 L 310 135 C 310 200 210 245 210 245 C 210 245 110 200 110 135 L 110 55 C 110 55 140 30 210 30 Z" fill="#0B2A4A"/>
          <!-- Inner Shield Depth -->
          <path d="M 210 42 C 265 42 292 63 292 63 L 292 135 C 292 190 210 228 210 228 C 210 228 128 190 128 135 L 128 63 C 128 63 155 42 210 42 Z" fill="#071C33"/>
          
          <!-- Animated Lock on Shield -->
          <g class="lock" transform="translate(180, 95)">
             <!-- Shackle -->
             <path d="M 12 25 L 12 15 C 12 0 48 0 48 15 L 48 25" fill="none" stroke="#F4B400" stroke-width="8" stroke-linecap="round"/>
             <!-- Body -->
             <rect x="2" y="25" width="56" height="44" rx="8" fill="#F4B400"/>
             <!-- Inner detail -->
             <rect x="6" y="29" width="48" height="36" rx="6" fill="#fbc531"/>
             <!-- Keyhole -->
             <circle cx="30" cy="42" r="5" fill="#0B2A4A"/>
             <path d="M 27 45 L 33 45 L 35 55 L 25 55 Z" fill="#0B2A4A"/>
          </g>

          <!-- Laser Scanner -->
          <line class="laser" x1="120" y1="135" x2="300" y2="135" stroke="#D32F2F" stroke-width="3" stroke-linecap="round" />
        </g>
      </svg>
    </div>

    <div class="code-row">
      <span class="num">4</span>
      <span class="num">0</span>
      <span class="num">3</span>
    </div>

    <h1>अनुमति अस्वीकृत / Permission Denied</h1>
    <p class="sub">
      आपके पास इस पृष्ठ या संसाधन तक पहुँचने की <b>अनुमति नहीं</b> है। कृपया सही रोल के साथ लॉगिन करें।
      <br>
      You <b>do not have permission</b> to access this page or resource. Please log in with the correct role.
    </p>

    <div class="actions">
      <!-- Logic from the original 403 for determining dashboard route based on user auth -->
      <a class="btn btn-primary" href="{{ auth()->check() ? route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'dashboard') : route('login') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
          <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        डैशबोर्ड पर जाएं / Go to Dashboard
      </a>
      <a class="btn btn-secondary" href="javascript:history.back()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 12H5"/>
          <path d="M12 19l-7-7 7-7"/>
        </svg>
        वापस जाएं / Go Back
      </a>
    </div>

    <div class="foot-note">
      Error Code: 403 · Jharkhand State Housing Board (JSHB) Portal · सहायता के लिए हेल्पडेस्क से संपर्क करें
    </div>
  </div>
</div>

</body>
</html>