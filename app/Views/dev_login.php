<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICTU Job Ticketing - Sign In</title>
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
</head>
<body class="min-h-screen bg-navy-950 overflow-x-hidden">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ===================== LEFT PANEL — Branding ===================== -->
        <div class="relative w-full lg:w-1/2 flex flex-col items-center justify-center px-4 sm:px-6 lg:px-12 py-4 lg:py-0 min-h-[88px] lg:min-h-screen bg-gradient-to-br from-navy-900 via-navy-800 to-navy-950 overflow-hidden">

            <!-- Decorative background shapes - adjusted for mobile visibility -->
            <div class="hidden lg:block absolute top-0 left-0 w-48 h-48 sm:w-72 sm:h-72 bg-navy-600 rounded-full mix-blend-screen opacity-10 -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
            <div class="hidden lg:block absolute bottom-0 right-0 w-64 h-64 sm:w-96 sm:h-96 bg-navy-500 rounded-full mix-blend-screen opacity-10 translate-x-1/3 translate-y-1/3 blur-3xl"></div>
            <div class="hidden lg:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] border border-white/5 rounded-full"></div>
            <div class="hidden lg:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[450px] h-[450px] sm:w-[700px] sm:h-[700px] border border-white/[0.03] rounded-full"></div>

            <!-- Mobile top bar -->
            <div class="relative z-10 flex lg:hidden w-full items-center justify-center gap-3">
                <img src="<?= base_url('ictu_alt_logo.png') ?>" alt="ICTU Logo" class="w-9 h-9 object-contain">
                <img src="<?= base_url('cspc_logo.png') ?>" alt="CSPC Logo" class="w-9 h-9 object-contain">
                <p class="text-m font-bold uppercase whitespace-nowrap">
                    <span class="text-white">CSPC</span>
                    <span class="text-navy-300"> - ICTU</span>
                </p>
            </div>

            <!-- Content -->
            <div class="hidden lg:block relative z-10 max-w-lg text-center lg:text-left space-y-6 sm:space-y-8">
                <!-- Logo / Icon - smaller on mobile -->
                <div class="inline-flex items-center justify-center w-24 h-24 sm:w-16 sm:h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10 shadow-lg">
                    <img src="<?= base_url('ictu_alt_logo.png') ?>" alt="ICTU Logo" class="w-24 h-24 sm:w-8 sm:h-8 object-contain">
                </div>
                <div class="inline-flex items-center justify-center w-24 h-24 sm:w-16 sm:h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10 shadow-lg">
                    <img src="<?= base_url('cspc_logo.png') ?>" alt="CSPC Logo" class="w-24 h-24 sm:w-8 sm:h-8 object-contain">
                </div>


                <div>
                    <!-- Responsive typography -->
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight">
                        Job Operations<br>Central Management<br>
                        <span class="text-navy-300">System</span>
                    </h1>
                    <p class="mt-3 sm:mt-4 text-sm sm:text-base lg:text-lg text-navy-300/80 leading-relaxed max-w-md mx-auto lg:mx-0">
                        CSPC &mdash; ICTU Job Ticketing System. Streamline service requests, track resolutions, and manage your team&rsquo;s workflow — all in one place.
                    </p>
                </div>

                <!-- Feature highlights - hidden on small mobile, visible on sm+ -->
                <div class="hidden sm:block space-y-3 sm:space-y-4 pt-2">
                    <div class="flex items-center justify-center lg:justify-start gap-3 text-navy-200/70">
                        <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-navy-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-sm">Submit and track job tickets in real-time</span>
                    </div>
                    <div class="flex items-center justify-center lg:justify-start gap-3 text-navy-200/70">
                        <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-navy-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-sm">Automated routing to the right technicians</span>
                    </div>
                    <div class="flex items-center justify-center lg:justify-start gap-3 text-navy-200/70">
                        <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-navy-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-sm">Transparent status updates & reporting</span>
                    </div>
                </div>

                <!-- Version label -->
                <p class="text-xs text-navy-400/50 pt-2 sm:pt-4 tracking-widest uppercase">Version 2.0 &middot; JobIgniter</p>
            </div>

            <!-- Wave separator (visible on mobile between panels) -->
            <div class="absolute -bottom-1 left-0 w-full lg:hidden">
                <svg viewBox="0 0 1440 80" class="w-full h-auto" preserveAspectRatio="none"><path d="M0,48 C360,80 720,0 1080,48 C1260,72 1380,56 1440,48 L1440,80 L0,80 Z" fill="#ffffff"/></svg>
            </div>
        </div>

        <!-- ===================== RIGHT PANEL — Login Form ===================== -->
        <div class="auth-form-panel relative w-full flex-1 lg:w-1/2 flex items-center justify-center bg-white px-4 sm:px-6 lg:px-8 py-6 sm:py-16 lg:py-0">

            <!-- Subtle background pattern -->
            <div class="absolute inset-0 opacity-[0.03]" style="background-image:url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23162557&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>

            <div class="auth-form-shell relative z-10 w-full max-w-sm sm:max-w-md py-2 sm:py-0">

                <!-- ===== VIEW 1: Role Selection ===== -->
                <div id="viewRoleSelect" class="panel-view space-y-6 sm:space-y-8">
                    <!-- Heading -->
                    <div class="text-center">
                        <p class="lg:hidden mb-1 text-xl font-bold uppercase">
                            <span class="text-navy-900">Job</span><span class="text-navy-500">Ops</span>
                        </p>
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-navy-900 tracking-tight">Sign in to your account</h2>
                        <p class="mt-2 text-sm text-gray-500">Choose how you&rsquo;d like to continue</p>
                    </div>

                    <!-- Buttons -->
                    <div class="space-y-3 sm:space-y-4">
                        <!-- I am a Student (Google OAuth) -->
                        <a href="<?= base_url('dev-auth/login/reycortez') ?>"
                           id="studentLoginBtn"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-700 active:scale-[0.98]">
                            <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-white/10 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 12.083V17.5a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 17.5v-5.417c0-.963.12-1.897.34-2.787L12 14z"/></svg>
                            </span>
                            <span id="studentLoginText">ICTU Head</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-60 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="<?= base_url('dev-auth/login/jonieberina') ?>"
                           id="studentLoginBtn"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-700 active:scale-[0.98]">
                            <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-white/10 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 12.083V17.5a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 17.5v-5.417c0-.963.12-1.897.34-2.787L12 14z"/></svg>
                            </span>
                            <span id="studentLoginText">MIS Head</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-60 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="<?= base_url('dev-auth/login/danjhoorbita') ?>"
                           id="studentLoginBtn"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-700 active:scale-[0.98]">
                            <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-white/10 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 12.083V17.5a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 17.5v-5.417c0-.963.12-1.897.34-2.787L12 14z"/></svg>
                            </span>
                            <span id="studentLoginText">NICM Head</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-60 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="<?= base_url('dev-auth/login/sirjam') ?>"
                           id="studentLoginBtn"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-700 active:scale-[0.98]">
                            <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-white/10 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 12.083V17.5a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 17.5v-5.417c0-.963.12-1.897.34-2.787L12 14z"/></svg>
                            </span>
                            <span id="studentLoginText">ICTRAM Head</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-60 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="<?= base_url('dev-auth/login/jucruz') ?>"
                           id="studentLoginBtn"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-700 active:scale-[0.98]">
                            <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-white/10 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 12.083V17.5a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 17.5v-5.417c0-.963.12-1.897.34-2.787L12 14z"/></svg>
                            </span>
                            <span id="studentLoginText">User</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-60 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="relative flex items-center gap-4">
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <span class="text-xs text-gray-400 font-medium tracking-wider uppercase whitespace-nowrap">Secured with Google</span>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    <!-- Google badge -->
                    <div class="flex items-center justify-center gap-2 text-gray-400 text-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span>Sign in with your CSPC Mail</span>
                    </div>

                    <!-- Terms -->
                    <p class="text-center text-xs text-gray-400 leading-relaxed px-4">
                        By signing in, you agree to our
                        <a href="#" class="text-navy-600 hover:underline">Terms of Service</a> and
                        <a href="#" class="text-navy-600 hover:underline">Privacy Policy</a>.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(30px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(-30px); }
        }
        .login-btn {
            animation: fadeUp 0.6s ease-out both;
        }
        .login-btn:nth-child(2) {
            animation-delay: 0.1s;
        }
        .panel-view {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .panel-view.slide-in {
            animation: fadeIn 0.35s ease-out both;
        }
        .panel-view.slide-out {
            animation: fadeOut 0.25s ease-in both;
        }
        .panel-view.hidden {
            display: none;
        }
        
        /* Prevent horizontal overflow on mobile */
        body {
            overflow-x: hidden;
        }
        
        /* Improve tap targets on mobile */
        @media (max-width: 640px) {
            .login-btn {
                min-height: 48px;
                padding-left: 3rem;
                padding-right: 3rem;
            }
        }

        @media (max-width: 1023px) {
            .auth-form-panel {
                min-height: calc(100vh - 88px);
            }

            .auth-form-shell {
                min-height: 100%;
                display: flex;
                align-items: center;
            }

            .panel-view:not(.hidden) {
                width: 100%;
                min-height: 70vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }
    </style>

    <script>
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonColor: '#162557'
            });
        <?php endif; ?>
    </script>

    <script>
    (function() {
        const viewRole     = document.getElementById('viewRoleSelect');
        const viewEmployee = document.getElementById('viewEmployeeOptions');
        const viewForm     = document.getElementById('viewEmployeeForm');
        const btnEmployee  = document.getElementById('employeeLoginBtn');
        const btnBack      = document.getElementById('backToRoleSelect');
        const btnNoCspc    = document.getElementById('noCspcEmailBtn');
        const btnBackForm  = document.getElementById('backToEmployeeOptions');

        function switchView(hideEl, showEl) {
            hideEl.classList.add('slide-out');
            hideEl.addEventListener('animationend', function handler() {
                hideEl.removeEventListener('animationend', handler);
                hideEl.classList.remove('slide-out');
                hideEl.classList.add('hidden');
                showEl.classList.remove('hidden');
                showEl.classList.add('slide-in');
                showEl.addEventListener('animationend', function h2() {
                    showEl.removeEventListener('animationend', h2);
                    showEl.classList.remove('slide-in');
                });
            });
        }

        // View 1 -> View 2
        btnEmployee.addEventListener('click', function() {
            switchView(viewRole, viewEmployee);
        });

        // View 2 -> View 1
        btnBack.addEventListener('click', function() {
            switchView(viewEmployee, viewRole);
        });

        // View 2 -> View 3 (employee form)
        btnNoCspc.addEventListener('click', function() {
            switchView(viewEmployee, viewForm);
        });

        // View 3 -> View 2
        btnBackForm.addEventListener('click', function() {
            switchView(viewForm, viewEmployee);
        });

        // Loading spinner on actual link clicks
        document.querySelectorAll('a.login-btn').forEach(function(btn) {
            btn.addEventListener('click', function () {
                const textEl = btn.querySelector('span[id]');
                if (textEl) {
                    textEl.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 00-10 10h2z"></path></svg>';
                }
                btn.classList.add('opacity-80', 'pointer-events-none');
            });
        });
    })();
    </script>
</body>
</html> 