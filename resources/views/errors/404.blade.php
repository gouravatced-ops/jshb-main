<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 - Page Not Found | Jharkhand State Housing Board</title>
<style>
  :root{
    --navy: #0B2A4A;
    --navy-deep: #071C33;
    --green: #1E7A46;
    --green-dark: #145934;
    --yellow: #F4B400;
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
    background:var(--yellow);
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
    border-bottom:5px solid var(--green);
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
    max-width:480px;
    margin-left:auto;
    margin-right:auto;
  }

  p.sub b{color:var(--green-dark);}

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
    background:var(--green);
    color:var(--white);
    box-shadow:0 6px 16px rgba(30,122,70,0.3);
  }
  .btn-primary:hover{
    background:var(--green-dark);
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(30,122,70,0.35);
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
  .sign-post{
    transform-origin: 130px 70px;
    animation: swing 3.4s ease-in-out infinite;
  }
  @keyframes swing{
    0%,100%{ transform: rotate(-2.2deg); }
    50%{ transform: rotate(2.2deg); }
  }

  .tape{
    animation: tapeShift 6s linear infinite;
  }
  @keyframes tapeShift{
    from{ stroke-dashoffset:0; }
    to{ stroke-dashoffset:-140; }
  }

  .crane-arm{
    transform-origin: 300px 40px;
    animation: craneMove 5s ease-in-out infinite;
  }
  @keyframes craneMove{
    0%,100%{ transform: rotate(0deg); }
    50%{ transform: rotate(3.5deg); }
  }

  .brick{
    animation: drop 2.6s ease-in-out infinite;
  }
  @keyframes drop{
    0%{ transform: translateY(-6px); opacity:.5; }
    30%{ transform: translateY(0); opacity:1; }
    70%{ transform: translateY(0); opacity:1; }
    100%{ transform: translateY(-6px); opacity:.5; }
  }

  .cable{
    stroke-dasharray:4 3;
    animation: cableDraw 1.2s linear infinite;
  }
  @keyframes cableDraw{
    to{ stroke-dashoffset:-14; }
  }

  @media (prefers-reduced-motion: reduce){
    .sign-post, .tape, .crane-arm, .brick, .cable{
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

        <!-- house under construction -->
        <g>
          <!-- house base -->
          <rect x="70" y="140" width="140" height="85" fill="#0B2A4A"/>
          <!-- roof (incomplete / scaffolded look) -->
          <polygon points="65,140 140,90 215,140" fill="#145934"/>
          <!-- windows -->
          <rect x="90" y="165" width="26" height="26" fill="#EEF1F4" opacity="0.85"/>
          <rect x="165" y="165" width="26" height="26" fill="#EEF1F4" opacity="0.85"/>
          <!-- door -->
          <rect x="128" y="185" width="24" height="40" fill="#F4B400"/>

          <!-- scaffolding lines -->
          <g stroke="#8A94A3" stroke-width="2">
            <line x1="60" y1="140" x2="60" y2="225"/>
            <line x1="220" y1="140" x2="220" y2="225"/>
            <line x1="60" y1="180" x2="220" y2="180"/>
          </g>

          <!-- animated bricks stacking -->
          <rect class="brick" x="66" y="205" width="14" height="10" fill="#F4B400"/>
          <rect class="brick" x="82" y="205" width="14" height="10" fill="#F4B400" style="animation-delay:.3s"/>
          <rect class="brick" x="200" y="205" width="14" height="10" fill="#F4B400" style="animation-delay:.6s"/>
        </g>

        <!-- crane -->
        <g>
          <rect x="297" y="40" width="6" height="185" fill="#47505C"/>
          <g class="crane-arm">
            <line x1="300" y1="40" x2="380" y2="40" stroke="#47505C" stroke-width="6" stroke-linecap="round"/>
            <line x1="300" y1="40" x2="255" y2="40" stroke="#47505C" stroke-width="6" stroke-linecap="round"/>
            <line class="cable" x1="365" y1="40" x2="365" y2="90" stroke="#8A94A3" stroke-width="2"/>
            <rect x="356" y="90" width="18" height="14" fill="#1E7A46"/>
          </g>
        </g>

        <!-- warning sign post -->
        <g class="sign-post">
          <line x1="130" y1="70" x2="130" y2="225" stroke="#47505C" stroke-width="5"/>
          <g>
            <rect x="90" y="35" width="80" height="46" rx="6" fill="#FFFFFF" stroke="#F4B400" stroke-width="4"/>
            <text x="130" y="66" text-anchor="middle" font-size="26" font-weight="800" fill="#0B2A4A" font-family="Segoe UI, Arial, sans-serif">404</text>
          </g>
        </g>

        <!-- caution tape across the scene -->
        <line class="tape" x1="20" y1="205" x2="400" y2="150" stroke="#F4B400" stroke-width="16" stroke-dasharray="18 12"/>
        <line x1="20" y1="205" x2="400" y2="150" stroke="#0B2A4A" stroke-width="16" stroke-dasharray="18 12" stroke-dashoffset="18" opacity="0.9"/>
      </svg>
    </div>

    <div class="code-row">
      <span class="num">4</span>
      <span class="num">0</span>
      <span class="num">4</span>
    </div>

    <h1>यह पृष्ठ उपलब्ध नहीं है / Page Not Found</h1>
    <p class="sub">
      आपके द्वारा खोजा गया पृष्ठ स्थानांतरित, हटाया गया हो सकता है, या अभी <b>निर्माणाधीन</b> है।
      <br>
      The page you're looking for may have been moved, removed, or is currently <b>under construction</b>.
    </p>

    <div class="actions">
      <button class="btn btn-primary" onclick="goBack()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 12H5"/>
          <path d="M12 19l-7-7 7-7"/>
        </svg>
        पिछले पेज पर जाएं / Go Back
      </button>
      <a class="btn btn-secondary" href="/">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 12l9-9 9 9"/>
          <path d="M9 21V12h6v9"/>
        </svg>
        होम पेज / Home
      </a>
    </div>

    <div class="foot-note">
      Error Code: 404 · Jharkhand State Housing Board (JSHB) Portal · सहायता के लिए हेल्पडेस्क से संपर्क करें
    </div>
  </div>
</div>

<script>
  function goBack(){
    // Agar previous page isi site ka hai to history back, warna home pe fallback
    if (document.referrer && document.referrer !== window.location.href && window.history.length > 1) {
      window.history.back();
    } else {
      window.location.href = "/";
    }
  }
</script>

</body>
</html>
