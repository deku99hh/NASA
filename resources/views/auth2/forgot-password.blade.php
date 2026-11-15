<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pixel Plant Forgot Password</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">

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
      padding: 20px;
    }

    /* شكل بيكسل مكعب للبطاقة الكبيرة */
    .pixel-card-shape {
      clip-path: polygon(
        0px 24px, 8px 24px, 8px 16px, 16px 16px, 16px 8px, 24px 8px, 24px 0px,
        calc(100% - 24px) 0px, calc(100% - 24px) 8px, calc(100% - 16px) 8px, calc(100% - 16px) 16px, calc(100% - 8px) 16px, calc(100% - 8px) 24px, 100% 24px,
        100% calc(100% - 24px), calc(100% - 8px) calc(100% - 24px), calc(100% - 8px) calc(100% - 16px), calc(100% - 16px) calc(100% - 16px), calc(100% - 16px) calc(100% - 8px), calc(100% - 24px) calc(100% - 8px), calc(100% - 24px) 100%,
        24px 100%, 24px calc(100% - 8px), 16px calc(100% - 8px), 16px calc(100% - 16px), 8px calc(100% - 16px), 8px calc(100% - 24px), 0px calc(100% - 24px)
      );
    }

    /* شكل بيكسل مكعب متوسط (للحقول والأزرار) */
    .pixel-box-shape {
      clip-path: polygon(
        0px 12px, 4px 12px, 4px 8px, 8px 8px, 8px 4px, 12px 4px, 12px 0px,
        calc(100% - 12px) 0px, calc(100% - 12px) 4px, calc(100% - 8px) 4px, calc(100% - 8px) 8px, calc(100% - 4px) 8px, calc(100% - 4px) 12px, 100% 12px,
        100% calc(100% - 12px), calc(100% - 4px) calc(100% - 12px), calc(100% - 4px) calc(100% - 8px), calc(100% - 8px) calc(100% - 8px), calc(100% - 8px) calc(100% - 4px), calc(100% - 12px) calc(100% - 4px), calc(100% - 12px) 100%,
        12px 100%, 12px calc(100% - 4px), 8px calc(100% - 4px), 8px calc(100% - 8px), 4px calc(100% - 8px), 4px calc(100% - 12px), 0px calc(100% - 12px)
      );
    }

    /* البطاقة الرئيسية */
    .forgot-card {
      position: relative;
      width: 370px;
      background-color: #ffffff;
      padding: 36px 28px 32px;
      display: flex;
      flex-direction: column;
      align-items: center;
      filter: drop-shadow(0 12px 0 #000000);
      background-image: linear-gradient(127deg, rgb(237, 255, 201) 0%, rgb(245, 255, 226) 50%, rgb(224, 255, 166) 100%);
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

    /* إطار النبتة البيكسل */
    .avatar-pixel-frame {
      position: relative;
      z-index: 1;
      width: 132px;
      height: 132px;
      background-color: #1a1a1a;
      padding: 4px;
      margin-bottom: 18px;
      clip-path: polygon(
        0px 30px, 8px 30px, 8px 20px, 14px 20px, 14px 14px, 20px 14px, 20px 8px, 30px 8px, 30px 0px,
        calc(100% - 30px) 0px, calc(100% - 30px) 8px, calc(100% - 20px) 8px, calc(100% - 20px) 14px, calc(100% - 14px) 14px, calc(100% - 14px) 20px, calc(100% - 8px) 20px, calc(100% - 8px) 30px, 100% 30px,
        100% calc(100% - 30px), calc(100% - 8px) calc(100% - 30px), calc(100% - 8px) calc(100% - 20px), calc(100% - 14px) calc(100% - 20px), calc(100% - 14px) calc(100% - 14px), calc(100% - 20px) calc(100% - 14px), calc(100% - 20px) calc(100% - 8px), calc(100% - 30px) calc(100% - 8px), calc(100% - 30px) 100%,
        30px 100%, 30px calc(100% - 8px), 20px calc(100% - 8px), 20px calc(100% - 14px), 14px calc(100% - 14px), 14px calc(100% - 20px), 8px calc(100% - 20px), 8px calc(100% - 30px), 0px calc(100% - 30px)
      );
    }

    .avatar-inner {
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at 50% 45%, #ffffff 15%, #e3ffab 65%, #cff982 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      clip-path: polygon(
        0px 26px, 8px 26px, 8px 16px, 14px 16px, 14px 10px, 20px 10px, 20px 4px, 26px 4px, 26px 0px,
        calc(100% - 26px) 0px, calc(100% - 26px) 4px, calc(100% - 20px) 4px, calc(100% - 20px) 10px, calc(100% - 14px) 10px, calc(100% - 14px) 16px, calc(100% - 8px) 16px, calc(100% - 8px) 26px, 100% 26px,
        100% calc(100% - 26px), calc(100% - 8px) calc(100% - 26px), calc(100% - 8px) calc(100% - 16px), calc(100% - 14px) calc(100% - 16px), calc(100% - 14px) calc(100% - 10px), calc(100% - 20px) calc(100% - 10px), calc(100% - 20px) calc(100% - 4px), calc(100% - 26px) calc(100% - 4px), calc(100% - 26px) 100%,
        26px 100%, 26px calc(100% - 4px), 20px calc(100% - 4px), 20px calc(100% - 10px), 14px calc(100% - 10px), 14px calc(100% - 16px), 8px calc(100% - 16px), 8px calc(100% - 26px), 0px calc(100% - 26px)
      );
    }

    .pixel-plant {
      width: 84px;
      height: 84px;
      image-rendering: pixelated;
      animation: floatPlant 2.8s steps(2, end) infinite;
    }

    @keyframes floatPlant {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-4px); }
    }

    /* العنوان والوصف */
    .title {
      position: relative;
      z-index: 1;
      font-size: 24px;
      font-weight: 700;
      color: #0d0d0d;
      margin-bottom: 10px;
      letter-spacing: 0.5px;
      text-align: center;
    }

    .subtitle {
      position: relative;
      z-index: 1;
      font-size: 14px;
      color: #6e6e6e;
      text-align: center;
      line-height: 1.4;
      margin-bottom: 18px;
      padding: 0 6px;
    }

    /* الفورم */
    .forgot-form {
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
      color: #6e6e6e;
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

    /* زر الإرسال */
    .btn-submit {
      margin: 8px auto 0;
      width: 100%;
      height: 48px;
      background-color: #0d0d0d;
      color: #ffffff;
      border: none;
      font-size: 17px;
      font-weight: 700;
      cursor: pointer;
      letter-spacing: 0.5px;
      transition: transform 0.1s steps(2), background-color 0.15s steps(2);
    }

    .btn-submit:hover {
      background-color: #6b9654;
      transform: translateY(-2px);
    }

    .btn-submit:active {
      transform: translateY(2px);
    }

    /* رابط العودة لتسجيل الدخول */
    .footer-text {
      position: relative;
      z-index: 1;
      margin-top: 20px;
      font-size: 15px;
      color: #8c8c8c;
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

  <div class="forgot-card pixel-card-shape">
    <!-- خلفية المربعات البيكسل المتناثرة -->
    <div class="bg-pixels">
      <!-- أعلى اليسار -->
      <span class="px" style="top: 38px; left: 48px; width: 4px; height: 4px; background: #8c8c8c;"></span>
      <span class="px" style="top: 82px; left: 24px; width: 5px; height: 5px; background: #ffffff;"></span>
      <span class="px" style="top: 130px; left: 30px; width: 8px; height: 8px; background: #6b9654;"></span>
      <span class="px" style="top: 200px; left: 42px; width: 6px; height: 6px; background: #a8cf82;"></span>

      <!-- أعلى اليمين -->
      <span class="px" style="top: 30px; right: 68px; width: 6px; height: 6px; background: #ffffff;"></span>
      <span class="px" style="top: 70px; right: 30px; width: 8px; height: 8px; background: #ffffff;"></span>
      <span class="px" style="top: 74px; right: 24px; width: 7px; height: 7px; background: #6b9654;"></span>
      <span class="px" style="top: 150px; right: 48px; width: 6px; height: 6px; background: #ffffff;"></span>
      <span class="px" style="top: 215px; right: 24px; width: 5px; height: 5px; background: #6b9654;"></span>

      <!-- الأسفل -->
      <span class="px" style="bottom: 85px; left: 38px; width: 7px; height: 7px; background: #6b9654;"></span>
      <span class="px" style="bottom: 42px; left: 28px; width: 8px; height: 8px; background: #6b9654;"></span>
      <span class="px" style="bottom: 80px; right: 44px; width: 8px; height: 8px; background: #6b9654;"></span>
      <span class="px" style="bottom: 48px; right: 34px; width: 6px; height: 6px; background: #ffffff;"></span>
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

    <h1 class="title">Forgot Password?</h1>
    <p class="subtitle">
      Enter your email address and we will send you a password reset link.
    </p>

    <form class="forgot-form" method="POST" action="http://localhost:8000/forgot-password">
      <input type="hidden" name="_token" value="AAZUCDv0ZKOIQRftxuCm0G9UQ4rhQcE2ua5JRojG" autocomplete="off">

      <!-- Email Address -->
      <div class="input-group">
        <label for="email">Email</label>
        <div class="pixel-input-wrapper pixel-box-shape">
          <input id="email" type="email" name="email" placeholder="user@example.com" required autofocus class="pixel-box-shape" />
        </div>
      </div>

      <button type="submit" class="btn-submit pixel-box-shape">Email Password Reset Link</button>
    </form>

    <p class="footer-text">
      Remembered it? <a href="http://localhost:8000/login">Back to Log in</a>
    </p>
  </div>

</body>
</html>