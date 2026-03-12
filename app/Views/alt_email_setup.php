<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICTU Job Ticketing — Set Recovery Email</title>
    <link rel="icon" href="<?= base_url('ictu.ico') ?>" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              navy: {
                50:  '#eef2ff',
                100: '#dce4fd',
                200: '#bccafb',
                300: '#8da6f7',
                400: '#5b7cf0',
                500: '#3b5ce6',
                600: '#2641d4',
                700: '#1e33b0',
                800: '#162557',
                900: '#0f1b3d',
                950: '#0a1128',
              }
            }
          }
        }
      }
    </script>
    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(24px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(-24px); }
        }
        .step-view { animation: fadeUp 0.45s ease-out both; }
        .step-view.slide-in { animation: fadeIn 0.35s ease-out both; }
        .step-view.slide-out { animation: fadeOut 0.25s ease-in both; }
        .step-view.hidden { display: none; }
    </style>
</head>
<body class="min-h-screen bg-navy-950 flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl px-8 py-10 space-y-6">

            <!-- Logo -->
            <div class="flex items-center justify-center gap-3">
                <img src="<?= base_url('ictu_alt_logo.png') ?>" alt="ICTU" class="w-10 h-10 object-contain">
                <img src="<?= base_url('cspc_logo.png') ?>"     alt="CSPC" class="w-10 h-10 object-contain">
            </div>

            <!-- ===== STEP 1: Email input ===== -->
            <div id="stepEmail" class="step-view space-y-5">
                <div class="text-center space-y-1">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-100 mb-2">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-navy-900 tracking-tight">Set Recovery Email</h1>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Hi, <strong><?= esc(session()->get('user')['name'] ?? 'there') ?></strong>!<br>
                        Please provide an alternative email address. It will be used for account recovery if you ever lose access to your primary email.
                    </p>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex gap-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <p class="text-xs text-amber-700 leading-relaxed">
                        Use a personal or secondary email that you will always have access to — not your CSPC email.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">
                        Alternative Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="inputAltEmail" autocomplete="email" required
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all placeholder:text-gray-400"
                        placeholder="you@gmail.com">
                    <p id="altEmailError" class="mt-1.5 text-xs text-red-500 hidden"></p>
                </div>

                <button type="button" id="btnSendOtp"
                    class="w-full flex items-center justify-center gap-2 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-6 py-3.5 font-semibold text-sm transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span id="btnSendOtpText">Send Verification Code</span>
                </button>

                <p class="text-center text-xs text-gray-400">
                    <a href="<?= base_url('logout') ?>" class="text-navy-600 hover:underline">Sign out instead</a>
                </p>
            </div>

            <!-- ===== STEP 2: OTP input ===== -->
            <div id="stepOtp" class="step-view space-y-5 hidden">
                <div class="text-center space-y-1">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-green-100 mb-2">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-navy-900 tracking-tight">Enter Verification Code</h1>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        A 6-digit code was sent to<br>
                        <strong id="sentToEmail" class="text-navy-800"></strong>
                    </p>
                </div>

                <!-- OTP boxes -->
                <div class="flex justify-center gap-2 sm:gap-3" id="otpBoxes">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                    <input type="text" inputmode="numeric" maxlength="1"
                        class="otp-digit w-11 h-14 sm:w-12 sm:h-14 text-center text-xl font-bold border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-navy-400 transition-all"
                        autocomplete="off">
                    <?php endfor; ?>
                </div>

                <p id="otpError" class="text-xs text-red-500 text-center hidden"></p>

                <!-- Countdown + resend -->
                <div class="text-center text-xs text-gray-500 space-y-1">
                    <p id="countdownMsg">Code expires in <strong id="countdownTimer" class="text-navy-700">10:00</strong></p>
                    <p id="resendRow" class="hidden">
                        Didn't receive it?
                        <button type="button" id="btnResend" class="text-navy-600 font-semibold hover:underline">Resend code</button>
                    </p>
                </div>

                <button type="button" id="btnVerifyOtp"
                    class="w-full flex items-center justify-center gap-2 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-6 py-3.5 font-semibold text-sm transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="btnVerifyOtpText">Verify &amp; Save</span>
                </button>

                <button type="button" id="btnChangeEmail" class="w-full text-sm text-navy-600 hover:text-navy-800 font-medium transition-colors">
                    ← Use a different email
                </button>
            </div>

        </div>

        <p class="text-center text-xs text-navy-400/60 mt-6">
            ICTU Job Operations System &middot; Version 2.0
        </p>
    </div>

    <script>
    (function () {
        const BASE_URL  = '<?= base_url() ?>';
        const CSRF_NAME = '<?= csrf_token() ?>';
        let   CSRF_HASH = '<?= csrf_hash() ?>';

        const stepEmail  = document.getElementById('stepEmail');
        const stepOtp    = document.getElementById('stepOtp');

        const inputAltEmail   = document.getElementById('inputAltEmail');
        const altEmailError   = document.getElementById('altEmailError');
        const btnSendOtp      = document.getElementById('btnSendOtp');
        const btnSendOtpText  = document.getElementById('btnSendOtpText');
        const sentToEmail     = document.getElementById('sentToEmail');

        const otpDigits       = document.querySelectorAll('.otp-digit');
        const otpError        = document.getElementById('otpError');
        const btnVerifyOtp    = document.getElementById('btnVerifyOtp');
        const btnVerifyOtpText = document.getElementById('btnVerifyOtpText');
        const btnChangeEmail  = document.getElementById('btnChangeEmail');
        const btnResend       = document.getElementById('btnResend');
        const resendRow       = document.getElementById('resendRow');
        const countdownMsg    = document.getElementById('countdownMsg');
        const countdownTimer  = document.getElementById('countdownTimer');

        let countdownInterval = null;

        // ── Helpers ──────────────────────────────────────────────────────────

        function setLoading(btn, textEl, loading, original) {
            const spinner = '<svg class="animate-spin h-5 w-5 inline-block" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 00-10 10h2z"></path></svg>';
            if (loading) {
                btn.disabled = true;
                btn.classList.add('opacity-70', 'pointer-events-none');
                textEl.innerHTML = spinner;
            } else {
                btn.disabled = false;
                btn.classList.remove('opacity-70', 'pointer-events-none');
                textEl.textContent = original;
            }
        }

        function switchToOtp() {
            stepEmail.classList.add('slide-out');
            stepEmail.addEventListener('animationend', function h() {
                stepEmail.removeEventListener('animationend', h);
                stepEmail.classList.remove('slide-out');
                stepEmail.classList.add('hidden');
                stepOtp.classList.remove('hidden');
                stepOtp.classList.add('slide-in');
                stepOtp.addEventListener('animationend', function h2() {
                    stepOtp.removeEventListener('animationend', h2);
                    stepOtp.classList.remove('slide-in');
                });
                otpDigits[0].focus();
            });
        }

        function switchToEmail() {
            stepOtp.classList.add('slide-out');
            stepOtp.addEventListener('animationend', function h() {
                stepOtp.removeEventListener('animationend', h);
                stepOtp.classList.remove('slide-out');
                stepOtp.classList.add('hidden');
                stepEmail.classList.remove('hidden');
                stepEmail.classList.add('slide-in');
                stepEmail.addEventListener('animationend', function h2() {
                    stepEmail.removeEventListener('animationend', h2);
                    stepEmail.classList.remove('slide-in');
                });
                stopCountdown();
                clearOtpBoxes();
            });
        }

        // ── Countdown ────────────────────────────────────────────────────────

        function startCountdown(seconds) {
            stopCountdown();
            resendRow.classList.add('hidden');
            countdownMsg.classList.remove('hidden');

            countdownInterval = setInterval(function () {
                if (seconds <= 0) {
                    stopCountdown();
                    countdownMsg.classList.add('hidden');
                    resendRow.classList.remove('hidden');
                    return;
                }
                seconds--;
                const m = String(Math.floor(seconds / 60)).padStart(2, '0');
                const s = String(seconds % 60).padStart(2, '0');
                countdownTimer.textContent = m + ':' + s;
            }, 1000);
        }

        function stopCountdown() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
        }

        // ── OTP box keyboard behaviour ────────────────────────────────────────

        function clearOtpBoxes() {
            otpDigits.forEach(function (d) { d.value = ''; d.classList.remove('border-red-400', 'border-green-400'); });
        }

        otpDigits.forEach(function (digit, idx) {
            digit.addEventListener('input', function () {
                // allow only digits
                digit.value = digit.value.replace(/\D/g, '').slice(-1);
                if (digit.value && idx < otpDigits.length - 1) {
                    otpDigits[idx + 1].focus();
                }
                otpError.classList.add('hidden');
            });

            digit.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && !digit.value && idx > 0) {
                    otpDigits[idx - 1].focus();
                }
            });

            digit.addEventListener('paste', function (e) {
                e.preventDefault();
                const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                text.split('').slice(0, 6).forEach(function (ch, i) {
                    if (otpDigits[idx + i]) otpDigits[idx + i].value = ch;
                });
                const next = Math.min(idx + text.length, otpDigits.length - 1);
                otpDigits[next].focus();
            });
        });

        // ── Send OTP ──────────────────────────────────────────────────────────

        async function sendOtp() {
            const email = inputAltEmail.value.trim();

            altEmailError.classList.add('hidden');

            if (!email) {
                altEmailError.textContent = 'Please enter an email address.';
                altEmailError.classList.remove('hidden');
                return;
            }

            setLoading(btnSendOtp, btnSendOtpText, true, 'Send Verification Code');

            try {
                const formData = new FormData();
                formData.append('alt_email', email);
                formData.append(CSRF_NAME, CSRF_HASH);

                const res  = await fetch(BASE_URL + '/auth/send-alt-email-otp', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                // Refresh CSRF token from response header if available
                const newCsrf = res.headers.get('X-CSRF-TOKEN');
                if (newCsrf) CSRF_HASH = newCsrf;

                if (!res.ok) {
                    setLoading(btnSendOtp, btnSendOtpText, false, 'Send Verification Code');

                    if (data.status === 'invalid_address') {
                        // Highlight the input field red and show inline error
                        inputAltEmail.classList.add('border-red-400', 'ring-2', 'ring-red-300');
                        altEmailError.textContent = data.message || 'Email address not found.';
                        altEmailError.classList.remove('hidden');

                        Swal.fire({
                            icon: 'warning',
                            title: 'Address Not Found',
                            html: '<p class="text-sm text-gray-600">' + (data.message || 'The email address could not be found.') + '</p>',
                            confirmButtonColor: '#162557',
                            confirmButtonText: 'Try Again'
                        });
                    } else {
                        altEmailError.textContent = data.message || 'Failed to send OTP.';
                        altEmailError.classList.remove('hidden');
                    }
                    return;
                }

                // Clear any previous error styling on success
                inputAltEmail.classList.remove('border-red-400', 'ring-2', 'ring-red-300');

                sentToEmail.textContent = email;
                switchToOtp();
                startCountdown(600);

                Swal.fire({
                    icon: 'success',
                    title: 'Code Sent!',
                    text: data.message,
                    timer: 2500,
                    showConfirmButton: false,
                    confirmButtonColor: '#162557'
                });

            } catch (err) {
                altEmailError.textContent = 'Network error. Please try again.';
                altEmailError.classList.remove('hidden');
            }

            setLoading(btnSendOtp, btnSendOtpText, false, 'Send Verification Code');
        }

        btnSendOtp.addEventListener('click', sendOtp);
        inputAltEmail.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') sendOtp();
        });
        inputAltEmail.addEventListener('input', function () {
            inputAltEmail.classList.remove('border-red-400', 'ring-2', 'ring-red-300');
            altEmailError.classList.add('hidden');
        });

        // ── Resend ────────────────────────────────────────────────────────────

        btnResend.addEventListener('click', async function () {
            const email = inputAltEmail.value.trim();
            if (!email) { switchToEmail(); return; }

            btnResend.disabled = true;
            btnResend.textContent = 'Sending…';

            try {
                const formData = new FormData();
                formData.append('alt_email', email);
                formData.append(CSRF_NAME, CSRF_HASH);

                const res  = await fetch(BASE_URL + '/auth/send-alt-email-otp', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                const newCsrf = res.headers.get('X-CSRF-TOKEN');
                if (newCsrf) CSRF_HASH = newCsrf;

                clearOtpBoxes();
                startCountdown(600);
                otpDigits[0].focus();

                Swal.fire({
                    icon: res.ok ? 'success' : 'error',
                    title: res.ok ? 'Code Resent!' : 'Error',
                    text: data.message,
                    timer: 2500,
                    showConfirmButton: false,
                    confirmButtonColor: '#162557'
                });
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Network Error', text: 'Please try again.', confirmButtonColor: '#162557' });
            }

            btnResend.disabled = false;
            btnResend.textContent = 'Resend code';
        });

        // ── Change email ──────────────────────────────────────────────────────

        btnChangeEmail.addEventListener('click', switchToEmail);

        // ── Verify OTP ────────────────────────────────────────────────────────

        btnVerifyOtp.addEventListener('click', async function () {
            const otp = Array.from(otpDigits).map(function (d) { return d.value; }).join('');

            otpError.classList.add('hidden');

            if (otp.length < 6) {
                otpError.textContent = 'Please enter all 6 digits.';
                otpError.classList.remove('hidden');
                return;
            }

            setLoading(btnVerifyOtp, btnVerifyOtpText, true, 'Verify & Save');

            try {
                const formData = new FormData();
                formData.append('otp', otp);
                formData.append(CSRF_NAME, CSRF_HASH);

                const res  = await fetch(BASE_URL + '/auth/verify-alt-email-otp', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                const newCsrf = res.headers.get('X-CSRF-TOKEN');
                if (newCsrf) CSRF_HASH = newCsrf;

                if (!res.ok) {
                    if (data.status === 'expired') {
                        stopCountdown();
                        countdownMsg.classList.add('hidden');
                        resendRow.classList.remove('hidden');
                    }
                    otpDigits.forEach(function (d) { d.classList.add('border-red-400'); });
                    otpError.textContent = data.message || 'Verification failed.';
                    otpError.classList.remove('hidden');
                    setLoading(btnVerifyOtp, btnVerifyOtpText, false, 'Verify & Save');
                    return;
                }

                stopCountdown();
                otpDigits.forEach(function (d) { d.classList.add('border-green-400'); });

                Swal.fire({
                    icon: 'success',
                    title: 'Email Verified!',
                    text: 'Your recovery email has been saved. Redirecting you to your dashboard…',
                    timer: 2000,
                    showConfirmButton: false,
                    confirmButtonColor: '#162557'
                }).then(function () {
                    window.location.href = data.redirect || BASE_URL;
                });

            } catch (err) {
                otpError.textContent = 'Network error. Please try again.';
                otpError.classList.remove('hidden');
                setLoading(btnVerifyOtp, btnVerifyOtpText, false, 'Verify & Save');
            }
        });

    })();
    </script>
</body>
</html>
