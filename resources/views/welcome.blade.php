<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Expense Management</title>
  <style>
    /* REPLACE ONLY STYLE - keeps your HTML intact */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

    :root{
      --bg: #f6f8fb;
      --panel: #ffffff;
      --muted: #6b7280;
      --primary: #2563eb;
      --green: #10b981;
      --orange: #f59e0b;
      --radius: 12px;
      --card-shadow: 0 10px 30px rgba(16,24,40,0.08);
      --soft-shadow: 0 6px 18px rgba(16,24,40,0.05);
      font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      color-scheme: light;
    }

    /* page */
    html,body{height:100%;margin:0}
    body{
      display:flex;
      align-items:center;
      justify-content:center;
      padding:28px;
      background:
        radial-gradient(800px 400px at 10% 10%, rgba(37,99,235,0.06), transparent 6%),
        radial-gradient(600px 300px at 95% 90%, rgba(16,185,129,0.04), transparent 6%),
        linear-gradient(180deg,#fbfdff 0%, var(--bg) 100%);
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }

    /* main container (matches your original HTML structure) */
    .container{
      width:100%;
      max-width:420px;
      background: var(--panel);
      border-radius: calc(var(--radius));
      padding:28px;
      box-shadow: var(--card-shadow);
      border: 1px solid rgba(15,23,42,0.04);
      box-sizing:border-box;
      display:block;
      text-align:center;
    }

    /* Heading */
    h1{
      margin:0 0 18px 0;
      font-size:20px;
      font-weight:700;
      color:#071033;
      letter-spacing:-0.01em;
    }

    /* Buttons wrapper remains anchors -> buttons; style buttons for clarity */
    a { text-decoration:none; display:block; border-radius:10px; }

    button{
      width:100%;
      padding:12px 14px;
      margin:10px 0;
      font-size:15px;
      font-weight:600;
      border-radius:10px;
      border:0;
      cursor:pointer;
      box-shadow: var(--soft-shadow);
      transition: transform .12s ease, box-shadow .12s ease, opacity .12s ease;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:10px;
      box-sizing:border-box;
    }

    /* Variant styles (keeps your classes: register, login, reset) */
    .register{
      background: linear-gradient(90deg,#14b8a6 0%, #06b6d4 100%);
      color: #042028;
      background-size:200% 200%;
      color: white;
      border: 1px solid rgba(0,0,0,0.04);
    }

    .register:hover{ transform: translateY(-3px); box-shadow: 0 14px 40px rgba(6,182,212,0.12); }

    .login{
      background: linear-gradient(90deg,#2563eb 0%, #06b6d4 100%);
      color: white;
    }
    .login:hover{ transform: translateY(-3px); box-shadow: 0 14px 40px rgba(37,99,235,0.12); }

    .reset{
      background: transparent;
      color: var(--muted);
      border: 1px dashed rgba(15,23,42,0.06);
    }
    .reset:hover{ transform: translateY(-2px); opacity:0.95; }

    /* subtle icon left inside button for human feel */
    button::before{
      content: "";
      display:inline-block;
      width:10px;
      height:10px;
      border-radius:3px;
      margin-right:8px;
      opacity:0.0; /* invisible by default so layout unchanged; can be made visible later */
    }

    /* microcopy under buttons */
    .micro{
      margin-top:12px;
      font-size:13px;
      color:var(--muted);
      line-height:1.3;
    }

    /* responsive */
    @media (max-width:420px){
      .container{ padding:20px; border-radius:10px }
      h1{ font-size:18px }
      button{ padding:11px; font-size:14px }
    }

    /* accessibility focus */
    button:focus{ outline: 3px solid rgba(37,99,235,0.12); outline-offset:3px }
    button:active{ transform: translateY(-1px) scale(0.998) }

  </style>
</head>
<body>
  <div class="container">
    <h1>Expense Management</h1>
    <a href="{{route('register')}}" class="">
        <button class="register">Register</button>
    </a>
    <a href="{{route('login')}}" class="">
        <button class="login">Login</button>
    </a>
    <a href="{{route('password.reset')}}" class="">
        <button class="reset">Reset Password</button>
    </a>
  </div>
</body>
</html>
