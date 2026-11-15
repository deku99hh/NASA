<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pixel Plant Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Pixelify+Sans:wght@400..700&display=swap"
    rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Pixelify Sans', sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      /* background-color: #141414; */
      padding: 20px;
    }

    /* شكل بيكسل مكعب للبطاقة الكبيرة */
    .pixel-card-shape {
      clip-path: polygon(0px 24px, 8px 24px, 8px 16px, 16px 16px, 16px 8px, 24px 8px, 24px 0px,
          calc(100% - 24px) 0px, calc(100% - 24px) 8px, calc(100% - 16px) 8px, calc(100% - 16px) 16px, calc(100% - 8px) 16px, calc(100% - 8px) 24px, 100% 24px,
          100% calc(100% - 24px), calc(100% - 8px) calc(100% - 24px), calc(100% - 8px) calc(100% - 16px), calc(100% - 16px) calc(100% - 16px), calc(100% - 16px) calc(100% - 8px), calc(100% - 24px) calc(100% - 8px), calc(100% - 24px) 100%,
          24px 100%, 24px calc(100% - 8px), 16px calc(100% - 8px), 16px calc(100% - 16px), 8px calc(100% - 16px), 8px calc(100% - 24px), 0px calc(100% - 24px));
    }

    /* شكل بيكسل مكعب متوسط (للحقول والأزرار) */
    .pixel-box-shape {
      clip-path: polygon(0px 12px, 4px 12px, 4px 8px, 8px 8px, 8px 4px, 12px 4px, 12px 0px,
          calc(100% - 12px) 0px, calc(100% - 12px) 4px, calc(100% - 8px) 4px, calc(100% - 8px) 8px, calc(100% - 4px) 8px, calc(100% - 4px) 12px, 100% 12px,
          100% calc(100% - 12px), calc(100% - 4px) calc(100% - 12px), calc(100% - 4px) calc(100% - 8px), calc(100% - 8px) calc(100% - 8px), calc(100% - 8px) calc(100% - 4px), calc(100% - 12px) calc(100% - 4px), calc(100% - 12px) 100%,
          12px 100%, 12px calc(100% - 4px), 8px calc(100% - 4px), 8px calc(100% - 8px), 4px calc(100% - 8px), 4px calc(100% - 12px), 0px calc(100% - 12px));
    }

    /* البطاقة الرئيسية */
    .login-card {
      position: relative;
      width: 350px;
      background-color: #ffffff;
      padding: 38px 28px 34px;
      display: flex;
      flex-direction: column;
      align-items: center;
      filter: drop-shadow(0 12px 0 #000000);
      background-image: linear-gradient(127deg, rgb(237 255 201) 0%, rgb(245 255 226) 50%, rgb(224 255 166) 100%);
    }

    /* البكسلات المتناثرة في الخلفية */
    .bg-pixels {
      position: absolute;
      inset: 0;
      pointer-events: none;
      z-index: 0;
    }

    .px {
      position: absolute;
      display: block;
    }

    /* إطار النبتة البيكسل (بديل الدائرة) */
    .avatar-pixel-frame {
      position: relative;
      z-index: 1;
      width: 148px;
      height: 148px;
      background-color: #1a1a1a;
      /* لون الإطار الخارجي */
      padding: 4px;
      margin-bottom: 22px;
      clip-path: polygon(0px 36px, 8px 36px, 8px 24px, 16px 24px, 16px 16px, 24px 16px, 24px 8px, 36px 8px, 36px 0px,
          calc(100% - 36px) 0px, calc(100% - 36px) 8px, calc(100% - 24px) 8px, calc(100% - 24px) 16px, calc(100% - 16px) 16px, calc(100% - 16px) 24px, calc(100% - 8px) 24px, calc(100% - 8px) 36px, 100% 36px,
          100% calc(100% - 36px), calc(100% - 8px) calc(100% - 36px), calc(100% - 8px) calc(100% - 24px), calc(100% - 16px) calc(100% - 24px), calc(100% - 16px) calc(100% - 16px), calc(100% - 24px) calc(100% - 16px), calc(100% - 24px) calc(100% - 8px), calc(100% - 36px) calc(100% - 8px), calc(100% - 36px) 100%,
          36px 100%, 36px calc(100% - 8px), 24px calc(100% - 8px), 24px calc(100% - 16px), 16px calc(100% - 16px), 16px calc(100% - 24px), 8px calc(100% - 24px), 8px calc(100% - 36px), 0px calc(100% - 36px));
    }

    .avatar-inner {
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at 50% 45%, #ffffff 15%, #e3ffab 65%, #cff982 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      clip-path: polygon(0px 32px, 8px 32px, 8px 20px, 16px 20px, 16px 12px, 24px 12px, 24px 4px, 32px 4px, 32px 0px,
          calc(100% - 32px) 0px, calc(100% - 32px) 4px, calc(100% - 24px) 4px, calc(100% - 24px) 12px, calc(100% - 16px) 12px, calc(100% - 16px) 20px, calc(100% - 8px) 20px, calc(100% - 8px) 32px, 100% 32px,
          100% calc(100% - 32px), calc(100% - 8px) calc(100% - 32px), calc(100% - 8px) calc(100% - 20px), calc(100% - 16px) calc(100% - 20px), calc(100% - 16px) calc(100% - 12px), calc(100% - 24px) calc(100% - 12px), calc(100% - 24px) calc(100% - 4px), calc(100% - 32px) calc(100% - 4px), calc(100% - 32px) 100%,
          32px 100%, 32px calc(100% - 4px), 24px calc(100% - 4px), 24px calc(100% - 12px), 16px calc(100% - 12px), 16px calc(100% - 20px), 8px calc(100% - 20px), 8px calc(100% - 32px), 0px calc(100% - 32px));
    }

    .pixel-plant {
      width: 96px;
      height: 96px;
      image-rendering: pixelated;
      animation: floatPlant 2.8s steps(2, end) infinite;
    }

    @keyframes floatPlant {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-4px);
      }
    }

    /* عنوان Log in */
    .title {
      position: relative;
      z-index: 1;
      font-size: 28px;
      font-weight: 700;
      color: #0d0d0d;
      margin-bottom: 20px;
      letter-spacing: 1px;
    }

    /* الفورم */
    .login-form {
      position: relative;
      z-index: 1;
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .input-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .input-group label {
      font-size: 16px;
      font-weight: 600;
      color: #7a7a7a;
      padding-left: 8px;
    }

    /* حاوية البوردر البيكسل للحقل */
    .pixel-input-wrapper {
      background-color: #9a9a9a;
      padding: 2px;
      transition: background-color 0.15s steps(2);
    }

    .pixel-input-wrapper:focus-within {
      background-color: #6b9654;
    }

    .pixel-input-wrapper input {
      width: 100%;
      height: 42px;
      background-color: #ffffff;
      border: none;
      outline: none;
      padding: 0 16px;
      font-size: 18px;
      color: #1a1a1a;
    }

    .pixel-input-wrapper input::placeholder {
      color: #b5b5b5;
    }

    .pixel-input-wrapper input[type="password"] {
      letter-spacing: 2px;
    }

    /* Remember Me بيكسل */
    .remember-row {
      display: flex;
      align-items: center;
      padding-left: 6px;
      margin-top: 2px;
    }

    .pixel-checkbox-label {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      font-size: 15px;
      color: #6e6e6e;
      user-select: none;
    }

    .pixel-checkbox-label input[type="checkbox"] {
      appearance: none;
      -webkit-appearance: none;
      width: 18px;
      height: 18px;
      background-color: #ffffff;
      border: 2px solid #8c8c8c;
      display: grid;
      place-content: center;
      cursor: pointer;
    }

    .pixel-checkbox-label input[type="checkbox"]::before {
      content: "";
      width: 10px;
      height: 10px;
      background-color: #6b9654;
      transform: scale(0);
      transition: transform 0.1s steps(2);
    }

    .pixel-checkbox-label input[type="checkbox"]:checked {
      border-color: #1a1a1a;
    }

    .pixel-checkbox-label input[type="checkbox"]:checked::before {
      transform: scale(1);
    }

    /* زر الدخول */
    .btn-submit {
      margin: 10px auto 0;
      width: 82%;
      height: 48px;
      background-color: #0d0d0d;
      color: #ffffff;
      border: none;
      font-size: 22px;
      font-weight: 700;
      cursor: pointer;
      letter-spacing: 1px;
      transition: transform 0.1s steps(2), background-color 0.15s steps(2);
    }

    .btn-submit:hover {
      background-color: #6b9654;
      transform: translateY(-2px);
    }

    .btn-submit:active {
      transform: translateY(2px);
    }

    /* رابط التسجيل السفلي */
    .footer-text {
      position: relative;
      z-index: 1;
      margin-top: 22px;
      font-size: 16px;
      color: #9e9e9e;
    }

    .footer-text a {
      color: #6b9654;
      text-decoration: none;
      font-weight: 700;
      margin-left: 4px;
    }

    .footer-text a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <div class="login-card pixel-card-shape">
    <!-- خلفية المربعات البيكسل المتناثرة -->
    <div class="bg-pixels">
      <!-- أعلى اليسار -->
      <span class="px" style="top: 42px; left: 52px; width: 4px; height: 4px; background: #8c8c8c;"></span>
      <span class="px" style="top: 86px; left: 26px; width: 5px; height: 5px; background: #e2f8b5;"></span>
      <span class="px" style="top: 138px; left: 32px; width: 8px; height: 8px; background: #6b9654;"></span>
      <span class="px" style="top: 196px; left: 48px; width: 6px; height: 6px; background: #a8cf82;"></span>
      <span class="px" style="top: 215px; left: 54px; width: 6px; height: 6px; background: #6b9654;"></span>

      <!-- أعلى اليمين -->
      <span class="px" style="top: 32px; right: 72px; width: 6px; height: 6px; background: #e5f9ba;"></span>
      <span class="px" style="top: 72px; right: 34px; width: 8px; height: 8px; background: #e5f9ba;"></span>
      <span class="px" style="top: 76px; right: 28px; width: 7px; height: 7px; background: #6b9654;"></span>
      <span class="px" style="top: 152px; right: 54px; width: 6px; height: 6px; background: #e5f9ba;"></span>
      <span class="px" style="top: 185px; right: 38px; width: 8px; height: 8px; background: #6b9654;"></span>
      <span class="px" style="top: 212px; right: 64px; width: 6px; height: 6px; background: #e5f9ba;"></span>
      <span class="px" style="top: 234px; right: 26px; width: 5px; height: 5px; background: #6b9654;"></span>

      <!-- الأسفل -->
      <span class="px" style="bottom: 112px; left: 68px; width: 8px; height: 8px; background: #e5f9ba;"></span>
      <span class="px" style="bottom: 108px; left: 74px; width: 7px; height: 7px; background: #6b9654;"></span>
      <span class="px" style="bottom: 62px; left: 34px; width: 8px; height: 8px; background: #6b9654;"></span>

      <span class="px" style="bottom: 108px; right: 52px; width: 8px; height: 8px; background: #6b9654;"></span>
      <span class="px" style="bottom: 112px; right: 46px; width: 7px; height: 7px; background: #e5f9ba;"></span>
      <span class="px" style="bottom: 122px; right: 92px; width: 4px; height: 4px; background: #8c8c8c;"></span>
      <span class="px" style="bottom: 72px; right: 42px; width: 6px; height: 6px; background: #e5f9ba;"></span>
    </div>

    <!-- إطار النبتة البيكسل المكعب -->
    <div class="avatar-pixel-frame">
      <div class="avatar-inner">
        <svg class="pixel-plant" viewBox="0 0 16 16" shape-rendering="crispEdges" xmlns="http://www.w3.org/2000/svg">
          <!-- الورقة اليسرى -->
          <rect x="4" y="2" width="3" height="1" fill="#80ed99" />
          <rect x="3" y="3" width="1" height="2" fill="#80ed99" />
          <rect x="4" y="3" width="3" height="2" fill="#d8f3dc" />
          <rect x="7" y="3" width="1" height="2" fill="#52b788" />
          <rect x="4" y="5" width="3" height="1" fill="#52b788" />

          <!-- الورقة اليمنى -->
          <rect x="9" y="3" width="3" height="1" fill="#52b788" />
          <rect x="8" y="4" width="4" height="2" fill="#40916c" />
          <rect x="12" y="4" width="1" height="2" fill="#2d6a4f" />
          <rect x="9" y="6" width="3" height="1" fill="#2d6a4f" />

          <!-- الساق -->
          <rect x="7" y="5" width="1" height="4" fill="#74c69d" />
          <rect x="8" y="6" width="1" height="3" fill="#52b788" />

          <!-- حافة الأصيص العلوية -->
          <rect x="4" y="9" width="8" height="1" fill="#000000" />
          <rect x="4" y="10" width="1" height="2" fill="#000000" />
          <rect x="5" y="10" width="2" height="1" fill="#ffccd5" />
          <rect x="7" y="10" width="4" height="1" fill="#ff758f" />
          <rect x="11" y="10" width="1" height="2" fill="#000000" />
          <rect x="5" y="11" width="6" height="1" fill="#c9184a" />

          <!-- جسم الأصيص السفلي -->
          <rect x="5" y="12" width="1" height="3" fill="#000000" />
          <rect x="6" y="12" width="1" height="2" fill="#ff8fa3" />
          <rect x="7" y="12" width="3" height="2" fill="#ff4d6d" />
          <rect x="10" y="12" width="1" height="3" fill="#000000" />
          <rect x="6" y="14" width="4" height="1" fill="#a4133c" />
          <rect x="6" y="15" width="4" height="1" fill="#000000" />
        </svg>
      </div>
    </div>

    <h1 class="title">Log in</h1>

    <form class="login-form" action="http://localhost:8000/login" method="POST">
      <div class="input-group">
        <label for="email">Email</label>
        <div class="pixel-input-wrapper pixel-box-shape">
          <input type="email" id="email" name="email" placeholder="user@example.com" required class="pixel-box-shape" />
        </div>
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <div class="pixel-input-wrapper pixel-box-shape">
          <input type="password" id="password" name="password" placeholder="••••••••••" required
            class="pixel-box-shape" />
        </div>
      </div>

      <div class="remember-row">
        <label for="remember_me" class="pixel-checkbox-label">
          <input id="remember_me" type="checkbox" name="remember">
          <span>Remember me</span>
        </label>
      </div>

      <button type="submit" class="btn-submit pixel-box-shape">Log in</button>
    </form>

    <p class="footer-text">
      Don't have account? <a href="/register">Register</a>
    </p>
    @if ($errors->any())
      <div style="color: red;">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

  </div>

</body>

</html>