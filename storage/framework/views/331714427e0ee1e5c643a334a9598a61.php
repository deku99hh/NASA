<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Visual Novel - Farm Setup</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800&family=Pixelify+Sans:wght@500;700&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      user-select: none;
    }

    body {
      min-height: 100vh;
      background-color: #141414;
      font-family: 'Cairo', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px;
      overflow: hidden;
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

    /* شكل بيكسل مكعب متوسط للأزرار والحقول */
    .pixel-box-shape {
      clip-path: polygon(
        0px 12px, 4px 12px, 4px 8px, 8px 8px, 8px 4px, 12px 4px, 12px 0px,
        calc(100% - 12px) 0px, calc(100% - 12px) 4px, calc(100% - 8px) 4px, calc(100% - 8px) 8px, calc(100% - 4px) 8px, calc(100% - 4px) 12px, 100% 12px,
        100% calc(100% - 12px), calc(100% - 4px) calc(100% - 12px), calc(100% - 4px) calc(100% - 8px), calc(100% - 8px) calc(100% - 8px), calc(100% - 8px) calc(100% - 4px), calc(100% - 12px) calc(100% - 4px), calc(100% - 12px) 100%,
        12px 100%, 12px calc(100% - 4px), 8px calc(100% - 4px), 8px calc(100% - 8px), 4px calc(100% - 8px), 4px calc(100% - 12px), 0px calc(100% - 12px)
      );
    }

    /* مسرح الفيجوال نوفيل بنفس ألوان الكروت السابقة */
    .vn-stage {
      position: relative;
      width: 100%;
      max-width: 820px;
      height: 92vh;
      max-height: 620px;
      background-image: linear-gradient(127deg, rgb(237, 255, 201) 0%, rgb(245, 255, 226) 50%, rgb(224, 255, 166) 100%);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 24px;
      cursor: pointer;
      filter: drop-shadow(0 12px 0 #000000);
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

    /* منطقة الشخصية (Sprite) */
    .character-container {
      position: absolute;
      bottom: 165px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 1;
      pointer-events: none;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .character-container.speaking {
      animation: bounceTalk 0.4s steps(2) infinite;
    }

    @keyframes bounceTalk {
      0%, 100% { transform: translateX(-50%) translateY(0); }
      50% { transform: translateX(-50%) translateY(-6px); }
    }

    .character-sprite {
      width: 240px;
      height: 260px;
      object-fit: contain;
      image-rendering: pixelated;
      filter: drop-shadow(0 8px 0 rgba(0, 0, 0, 0.25));
    }

    /* منطقة التفاعل (الاختيارات، المقالي، والسبميت النهائي) */
    .interaction-overlay {
      position: relative;
      z-index: 3;
      display: none;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      margin-bottom: 16px;
      cursor: default;
    }

    .interaction-overlay.active {
      display: flex;
    }

    /* غلاف البوردر البيكسل للأزرار والحقول */
    .pixel-border-wrap {
      width: 100%;
      max-width: 480px;
      background-color: #1a1a1a;
      padding: 3px;
      transition: transform 0.1s steps(2), background-color 0.15s steps(2);
    }

    .pixel-border-wrap:hover {
      background-color: #6b9654;
      transform: translateY(-2px);
    }

    /* أزرار الاختيارات */
    .choice-btn {
      width: 100%;
      padding: 12px 20px;
      background-color: #ffffff;
      color: #0d0d0d;
      border: none;
      font-family: 'Cairo', sans-serif;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: background-color 0.1s;
    }

    .choice-btn:hover {
      background-color: #f3ffe0;
    }

    /* صندوق الإدخال المقالي */
    .essay-wrap {
      width: 100%;
      max-width: 520px;
      background-color: #1a1a1a;
      padding: 3px;
    }

    .essay-box {
      width: 100%;
      background-color: #ffffff;
      padding: 8px;
      display: flex;
      gap: 8px;
    }

    .essay-box input {
      flex: 1;
      border: none;
      padding: 6px 12px;
      font-family: 'Cairo', sans-serif;
      font-size: 16px;
      outline: none;
      user-select: text;
      color: #1a1a1a;
    }

    .essay-submit {
      background-color: #0d0d0d;
      color: #ffffff;
      border: none;
      padding: 8px 22px;
      font-family: 'Cairo', sans-serif;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: background-color 0.15s steps(2);
    }

    .essay-submit:hover {
      background-color: #6b9654;
    }

    /* زر السبميت النهائي للفورم */
    .final-submit-btn {
      width: 100%;
      padding: 14px 24px;
      background-color: #0d0d0d;
      color: #ffffff;
      border: none;
      font-family: 'Cairo', sans-serif;
      font-size: 18px;
      font-weight: 800;
      cursor: pointer;
      letter-spacing: 0.5px;
      transition: background-color 0.15s steps(2);
    }

    .final-submit-btn:hover {
      background-color: #6b9654;
    }

    /* صندوق الحوار السفلي (Dialogue Box) */
    .dialogue-wrap {
      position: relative;
      z-index: 2;
      width: 100%;
      background-color: #1a1a1a;
      padding: 4px;
    }

    .dialogue-box {
      position: relative;
      width: 100%;
      min-height: 155px;
      background-color: #ffffff;
      padding: 26px 24px 22px;
    }

    /* شارة اسم المتحدث */
    .speaker-badge-wrap {
      position: absolute;
      top: -18px;
      right: 28px;
      z-index: 3;
      background-color: #1a1a1a;
      padding: 3px;
    }

    .speaker-badge {
      background-color: #cff982;
      color: #0d0d0d;
      padding: 2px 20px;
      font-size: 17px;
      font-weight: 800;
    }

    /* نص الحوار */
    .dialogue-text {
      font-size: 19px;
      font-weight: 700;
      color: #141414;
      line-height: 1.6;
    }

    /* سهم الانتقال للجملة التالية */
    .next-indicator {
      position: absolute;
      bottom: 12px;
      left: 22px;
      font-family: 'Cairo', sans-serif;
      font-size: 14px;
      font-weight: 800;
      color: #6b9654;
      display: none;
      animation: blinkArrow 0.8s steps(2) infinite;
    }

    .next-indicator.visible {
      display: block;
    }

    @keyframes blinkArrow {
      0%, 100% { opacity: 1; transform: translateY(0); }
      50% { opacity: 0.3; transform: translateY(3px); }
    }
  </style>
</head>

<body>

  <div class="vn-stage pixel-card-shape" id="vnStage">

    <!-- خلفية المربعات البيكسل المتناثرة (نفس ستايل اللوجن والريجستر) -->
    <div class="bg-pixels">
      <span class="px" style="top: 38px; left: 48px; width: 6px; height: 6px; background: #8c8c8c;"></span>
      <span class="px" style="top: 82px; left: 24px; width: 8px; height: 8px; background: #ffffff;"></span>
      <span class="px" style="top: 130px; left: 60px; width: 8px; height: 8px; background: #6b9654;"></span>
      <span class="px" style="top: 210px; left: 42px; width: 6px; height: 6px; background: #a8cf82;"></span>
      <span class="px" style="top: 30px; right: 68px; width: 8px; height: 8px; background: #ffffff;"></span>
      <span class="px" style="top: 70px; right: 30px; width: 8px; height: 8px; background: #ffffff;"></span>
      <span class="px" style="top: 74px; right: 24px; width: 7px; height: 7px; background: #6b9654;"></span>
      <span class="px" style="top: 150px; right: 58px; width: 6px; height: 6px; background: #ffffff;"></span>
      <span class="px" style="top: 225px; right: 34px; width: 7px; height: 7px; background: #6b9654;"></span>
    </div>

    <!-- الشخصية (صورة Placeholder) -->
    <div class="character-container" id="charContainer">
      <img
        id="charSprite"
        class="character-sprite"
        src="fernn.webp"
        alt="Character Sprite"
      />
    </div>

    <!-- منطقة الأسئلة والفورم النهائي -->
    <div class="interaction-overlay" id="interactionOverlay"></div>

    <!-- صندوق الحوار -->
    <div style="position: relative; z-index: 2;">
      <div class="speaker-badge-wrap pixel-box-shape">
        <div class="speaker-badge pixel-box-shape" id="speakerName">فيرن</div>
      </div>

      <div class="dialogue-wrap pixel-box-shape">
        <div class="dialogue-box pixel-box-shape">
          <p class="dialogue-text" id="dialogueText"></p>
          <span class="next-indicator" id="nextIndicator">▼ اضغط للمتابعة</span>
        </div>
      </div>
    </div>

  </div>

  <script>
    const script = [
      {
        speaker: "فيرن",
        text: "أهلاً بك في أرضك الزراعية! أنا هنا لمساعدتك في تحليل التربة والمحاصيل."
      },
      {
        speaker: "فيرن",
        text: "قبل ما نفتح لوحة التحكم المتقدمة، محتاج أعرف منك شوية بيانات سريعة... اضغط عشان نبدأ."
      },
      {
        speaker: "فيرن",
        text: "أول سؤال: ما هو نوع الري المستخدم في أرضك حالياً؟",
        type: "choice",
        key: "irrigation_type",
        options: [
          "ري بالتنقيط (Drip Irrigation)",
          "ري بالرش (Sprinkler)",
          "ري بالغمر (Flood Irrigation)"
        ]
      },
      {
        speaker: "فيرن",
        text: "ممتاز! ده هيساعدني جداً في حساب رطوبة التربة."
      },
      {
        speaker: "فيرن",
        text: "السؤال الثاني: اكتبلي اسم آخر محصول زرعته في الأرض دي؟",
        type: "essay",
        key: "last_crop",
        placeholder: "مثلاً: قمح، ذرة، بطاطس..."
      },
      {
        speaker: "فيرن",
        text: "تمام أوي! تسلم بجد يسطا."
      },
      {
        speaker: "فيرن",
        text: "معلش بس ممكن تديني خط الطول ودائرة العرض اللي عليها الأرض؟",
        type: "essay",
        key: "long__lat",
        placeholder: "مثال: 31.0364, 30.4682"
      },
      {
        speaker: "فيرن",
        text: "كده كل البيانات جاهزة! اضغط على الزرار اللي قدامك عشان نبعتها ونبدأ."
      }
    ];

    const userAnswers = {};

    let currentIndex = 0;
    let isTyping = false;
    let isWaitingForInput = false;
    let typingTimer = null;

    const vnStage = document.getElementById("vnStage");
    const speakerNameEl = document.getElementById("speakerName");
    const dialogueTextEl = document.getElementById("dialogueText");
    const nextIndicatorEl = document.getElementById("nextIndicator");
    const interactionOverlay = document.getElementById("interactionOverlay");
    const charContainer = document.getElementById("charContainer");

    function renderStep(index) {
      const step = script[index];
      if (!step) {
        onScriptEnd();
        return;
      }

      speakerNameEl.textContent = step.speaker;
      interactionOverlay.innerHTML = "";
      interactionOverlay.classList.remove("active");
      nextIndicatorEl.classList.remove("visible");
      isWaitingForInput = false;

      typeText(step.text, () => {
        if (step.type === "choice") {
          showChoices(step);
        } else if (step.type === "essay") {
          showEssayInput(step);
        } else if (index === script.length - 1) {
          // لو دي آخر جملة في السيناريو، أظهر فورم السبميت النهائي فوراً
          onScriptEnd();
        } else {
          nextIndicatorEl.classList.add("visible");
        }
      });
    }

    function typeText(text, onComplete) {
      isTyping = true;
      charContainer.classList.add("speaking");
      dialogueTextEl.textContent = "";
      let charIndex = 0;

      clearInterval(typingTimer);
      typingTimer = setInterval(() => {
        dialogueTextEl.textContent += text[charIndex];
        charIndex++;

        if (charIndex >= text.length) {
          finishTyping(text, onComplete);
        }
      }, 30);
    }

    function finishTyping(fullText, onComplete) {
      clearInterval(typingTimer);
      dialogueTextEl.textContent = fullText;
      isTyping = false;
      charContainer.classList.remove("speaking");
      if (onComplete) onComplete();
    }

    function showChoices(step) {
      isWaitingForInput = true;
      interactionOverlay.classList.add("active");

      step.options.forEach(optionText => {
        const wrap = document.createElement("div");
        wrap.className = "pixel-border-wrap pixel-box-shape";

        const btn = document.createElement("button");
        btn.type = "button";
        btn.className = "choice-btn pixel-box-shape";
        btn.textContent = optionText;

        btn.addEventListener("click", (e) => {
          e.stopPropagation();
          userAnswers[step.key] = optionText;
          nextStep();
        });

        wrap.appendChild(btn);
        interactionOverlay.appendChild(wrap);
      });
    }

    function showEssayInput(step) {
      isWaitingForInput = true;
      interactionOverlay.classList.add("active");

      const wrap = document.createElement("div");
      wrap.className = "essay-wrap pixel-box-shape";
      wrap.addEventListener("click", (e) => e.stopPropagation());

      const box = document.createElement("div");
      box.className = "essay-box pixel-box-shape";

      const input = document.createElement("input");
      input.type = "text";
      input.placeholder = step.placeholder || "اكتب إجابتك هنا...";

      const submitBtn = document.createElement("button");
      submitBtn.type = "button";
      submitBtn.className = "essay-submit pixel-box-shape";
      submitBtn.textContent = "تأكيد";

      const submitAnswer = () => {
        const val = input.value.trim();
        if (!val) {
          input.focus();
          return;
        }
        userAnswers[step.key] = val;
        nextStep();
      };

      submitBtn.addEventListener("click", submitAnswer);
      input.addEventListener("keydown", (e) => {
        if (e.key === "Enter") submitAnswer();
      });

      box.appendChild(input);
      box.appendChild(submitBtn);
      wrap.appendChild(box);
      interactionOverlay.appendChild(wrap);
      setTimeout(() => input.focus(), 50);
    }

    function nextStep() {
      currentIndex++;
      renderStep(currentIndex);
    }

    // إنشاء فورم حقيقي بكل الإجابات وإظهار زرار السبميت النهائي
    function onScriptEnd() {
      isWaitingForInput = true;
      nextIndicatorEl.classList.remove("visible");
      interactionOverlay.innerHTML = "";
      interactionOverlay.classList.add("active");

      console.log("جميع إجابات المستخدم:", userAnswers);

      const form = document.createElement("form");
      form.method = "post";
      form.action = "/dashboard"; // غير المسار ده للـ Route بتاعك في لارافيل
      form.style.width = "100%";
      form.style.maxWidth = "480px";
      form.addEventListener("click", (e) => e.stopPropagation());

      // إضافة الـ CSRF Token تلقائياً لو موجود في الصفحة (لما تنقله لارافيل)
      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      if (csrfMeta) {
        const csrfInput = document.createElement("input");
        csrfInput.type = "hidden";
        csrfInput.name = "_token";
        csrfInput.value = csrfMeta.getAttribute("content");
        form.appendChild(csrfInput);
      }

      // تحويل كل إجابة في userAnswers إلى Hidden Input داخل الفورم
      Object.entries(userAnswers).forEach(([key, value]) => {
        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = key;
        hiddenInput.value = value;
        form.appendChild(hiddenInput);
      });

      const btnWrap = document.createElement("div");
      btnWrap.className = "pixel-border-wrap pixel-box-shape";

      const finalBtn = document.createElement("button");
      finalBtn.type = "submit";
      finalBtn.className = "final-submit-btn pixel-box-shape";
      finalBtn.textContent = "حفظ البيانات والانتقال للداشبورد 🚀";

      btnWrap.appendChild(finalBtn);
      form.appendChild(btnWrap);
      interactionOverlay.appendChild(form);
    }

    vnStage.addEventListener("click", () => {
      if (isWaitingForInput) return;

      const currentStep = script[currentIndex];
      if (!currentStep) return;

      if (isTyping) {
        finishTyping(currentStep.text, () => {
          if (currentStep.type === "choice") showChoices(currentStep);
          else if (currentStep.type === "essay") showEssayInput(currentStep);
          else if (currentIndex === script.length - 1) onScriptEnd();
          else nextIndicatorEl.classList.add("visible");
        });
        return;
      }

      nextStep();
    });

    renderStep(0);
  </script>

</body>
</html><?php /**PATH C:\xampp\htdocs\NASA\resources\views/chat-fern-1/fern1.blade.php ENDPATH**/ ?>