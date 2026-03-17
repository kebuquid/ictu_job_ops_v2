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
                <p class="text-m font-bold tracking-wide uppercase whitespace-nowrap">
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
                        <!-- I am a Student (opens sub-view) -->
                        <button type="button"
                           id="studentLoginBtn"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-700 active:scale-[0.98]">
                            <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-white/10 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 12.083V17.5a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 17.5v-5.417c0-.963.12-1.897.34-2.787L12 14z"/></svg>
                            </span>
                            <span id="studentLoginText">I am a Student</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-60 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>

                        <!-- I am an Employee (opens sub-view) -->
                        <button type="button"
                           id="employeeLoginBtn"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-white hover:bg-navy-50 text-navy-800 rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-200 active:scale-[0.98]">
                            <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-navy-100 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0H8m8 0a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2"/></svg>
                            </span>
                            <span>I am an Employee</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-40 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
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

                <!-- ===== VIEW 2: Employee Email Options ===== -->
                <div id="viewEmployeeOptions" class="panel-view space-y-6 sm:space-y-8 hidden">

                    <!-- Back button -->
                    <button type="button" id="backToRoleSelect" class="inline-flex items-center gap-2 text-sm text-navy-600 hover:text-navy-800 font-medium transition-colors group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                        Back
                    </button>

                    <!-- Heading -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-navy-100 mb-4">
                            <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0H8m8 0a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2"/></svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-navy-900 tracking-tight">Employee Sign In</h2>
                        <p class="mt-2 text-sm text-gray-500">Do you have a CSPC email account?</p>
                    </div>

                    <!-- Employee sub-options -->
                    <div class="space-y-3 sm:space-y-4">
                        <!-- I have a CSPC Email (Google OAuth) -->
                        <a href="<?= base_url('auth/google') ?>"
                           id="cspcEmailBtn"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-700 active:scale-[0.98]">
                            <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-white/10 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <span id="cspcEmailText">I have a CSPC Email</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-60 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>

                        <!-- I don't have a CSPC Email (opens form view) -->
                        <button type="button"
                           id="noCspcEmailBtn"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-white hover:bg-navy-50 text-navy-800 rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-200 active:scale-[0.98]">
                            <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-navy-100 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <span>I don&rsquo;t have a CSPC Email</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-40 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </div>

                    <!-- Helper note -->
                    <div class="bg-navy-50 rounded-xl px-4 py-3 border border-navy-100">
                        <p class="text-xs text-navy-600 leading-relaxed text-center">
                            <span class="font-semibold">Not sure?</span> If your office provided you with a <span class="font-medium">@cspc.edu.ph</span> email, choose the first option. Otherwise, select the second to sign in with a personal Google account.
                        </p>
                    </div>

                    <!-- Terms -->
                    <p class="text-center text-xs text-gray-400 leading-relaxed px-4">
                        By signing in, you agree to our
                        <a href="#" class="text-navy-600 hover:underline">Terms of Service</a> and
                        <a href="#" class="text-navy-600 hover:underline">Privacy Policy</a>.
                    </p>
                </div>

                <!-- ===== VIEW 3: Employee verification Form ===== -->
                <div id="viewEmployeeForm" class="panel-view space-y-5 sm:space-y-6 hidden">

                    <!-- Back button -->
                    <button type="button" id="backToEmployeeOptions" class="inline-flex items-center gap-2 text-sm text-navy-600 hover:text-navy-800 font-medium transition-colors group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                        Back
                    </button>

                    <!-- Heading -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-navy-100 mb-3">
                            <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-navy-900 tracking-tight">Employee Verification</h2>
                        <p id="verificationSubtitle" class="mt-1.5 text-sm text-gray-500">Enter your employee number to get started</p>
                    </div>

                    <!-- Form -->
                    <div class="space-y-4">

                        <!-- STEP 1: Employee Number -->
                        <div id="stepEmployeeNumber">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Employee Number <span class="text-red-500">*</span></label>
                            <input type="text" id="inputEmployeeNumber" name="employee_number" required
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all placeholder:text-gray-400"
                                placeholder="e.g. EMP-00123">
                        </div>

                        <!-- Verify Employee Button (Step 1) -->
                        <button type="button" id="btnVerifyEmployee"
                            class="w-full flex items-center justify-center gap-2 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-6 py-3.5 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-[0.98]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span id="btnVerifyEmployeeText">Verify Employee Number</span>
                        </button>

                        <!-- STEP 2: Identity Verification (hidden initially) -->
                        <div id="stepIdentityFields" class="space-y-4 hidden">
                            <!-- Success badge -->
                            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-2.5">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm text-green-700 font-medium">Employee found. Please confirm your identity.</span>
                            </div>

                            <!-- First Name -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">First Name <span class="text-red-500">*</span></label>
                                <input type="text" id="inputFirstName" name="first_name" required
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all placeholder:text-gray-400"
                                    placeholder="Juan">
                            </div>
                            <!-- Last Name -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" id="inputLastName" name="last_name" required
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all placeholder:text-gray-400"
                                    placeholder="Dela Cruz">
                            </div>

                            <!-- Birthdate -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Birthdate <span class="text-red-500">*</span></label>
                                <input type="date" id="inputBirthDate" name="birth_date" required
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all text-gray-700">
                            </div>

                            <!-- Verify Identity Button (Step 2) -->
                            <button type="button" id="btnVerifyIdentity"
                                class="w-full flex items-center justify-center gap-2 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-6 py-3.5 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span id="btnVerifyIdentityText">Verify Identity</span>
                            </button>
                        </div>
                    </div>

                    <!-- Terms -->
                    <p class="text-center text-xs text-gray-400 leading-relaxed px-4">
                        By signing in, you agree to our
                        <a href="#" class="text-navy-600 hover:underline">Terms of Service</a> and
                        <a href="#" class="text-navy-600 hover:underline">Privacy Policy</a>.
                    </p>
                </div>
                <!-- ===== VIEW 4: Employee Organizational Account Builder ===== -->
                <div id="viewEmployeeAccountBuilder" class="panel-view space-y-5 sm:space-y-6 hidden">

                    <!-- Back button -->
                    <button type="button" id="backToEmployeeVerification" class="inline-flex items-center gap-2 text-sm text-navy-600 hover:text-navy-800 font-medium transition-colors group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                        Back
                    </button>

                    <!-- Heading -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-navy-100 mb-3">
                            <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-navy-900 tracking-tight">Create Your CSPC Email</h2>
                        <p class="mt-1.5 text-sm text-gray-500">Choose an available email or type your own</p>
                    </div>

                    <!-- Email suggestions -->
                    <div id="emailSuggestionsContainer" class="hidden">
                        <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Suggested Emails</label>
                        <div id="emailSuggestionsLoading" class="flex items-center justify-center py-3">
                            <svg class="animate-spin h-5 w-5 text-navy-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 00-10 10h2z"></path></svg>
                            <span class="ml-2 text-sm text-gray-500">Loading suggestions...</span>
                        </div>
                        <div id="emailSuggestionsList" class="grid gap-2"></div>
                    </div>

                    <!-- Form -->
                    <form id="createEmailForm" class="space-y-4">
                        <?= csrf_field() ?>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="email-field-wrap flex items-center">
                                <div class="relative flex-1 min-w-0">
                                    <input type="text" name="email" id="newEmail" required
                                        class="w-full rounded-l-xl border border-gray-200 px-4 py-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:z-10 transition-all placeholder:text-gray-400"
                                        placeholder="juandelacruz" autocomplete="off">
                                    <!-- Availability indicator icon -->
                                    <span id="emailStatusIcon" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                    </span>
                                </div>
                                <span class="email-domain-suffix inline-flex items-center px-4 rounded-r-xl border border-l-0 border-gray-200 bg-gray-50 text-gray-500 text-sm h-[46px]">
                                    @cspc.edu.ph
                                </span>
                            </div>
                            <!-- Availability message -->
                            <p id="emailStatusMessage" class="mt-1.5 text-xs hidden"></p>
                        </div>
                        <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <input type="password" id="password" name="password" required
                                class="w-full rounded-xl border border-gray-200 pl-4 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all placeholder:text-gray-400"
                                placeholder="Enter your password">
                            
                            <button type="button" onclick="togglePassword('password', this)" 
                                class="absolute right-4 text-gray-400 hover:text-navy-400 transition-colors focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <input type="password" id="confirm_password" name="confirm_password" required
                                class="w-full rounded-xl border border-gray-200 pl-4 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all placeholder:text-gray-400"
                                placeholder="Re-Enter your password">
                            
                            <button type="button" onclick="togglePassword('confirm_password', this)" 
                                class="absolute right-4 text-gray-400 hover:text-navy-400 transition-colors focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                        <!-- Submit -->
                        <button type="submit" id="btnCreateAccount"
                            class="w-full flex items-center justify-center gap-2 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-6 py-3.5 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-[0.98] disabled:opacity-60 disabled:pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span id="btnCreateAccountText">Create Account</span>
                        </button>
                    </form>

                    <!-- Terms -->
                    <p class="text-center text-xs text-gray-400 leading-relaxed px-4">
                        By signing in, you agree to our
                        <a href="#" class="text-navy-600 hover:underline">Terms of Service</a> and
                        <a href="#" class="text-navy-600 hover:underline">Privacy Policy</a>.
                    </p>
                </div>

                <!-- ===== VIEW 5: Student Email Options ===== -->
                <div id="viewStudentOptions" class="panel-view space-y-6 sm:space-y-8 hidden">

                    <button type="button" id="backToRoleSelectFromStudent" class="inline-flex items-center gap-2 text-sm text-navy-600 hover:text-navy-800 font-medium transition-colors group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                        Back
                    </button>

                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-navy-100 mb-4">
                            <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 12.083V17.5a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 17.5v-5.417c0-.963.12-1.897.34-2.787L12 14z"/></svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-navy-900 tracking-tight">Student Sign In</h2>
                        <p class="mt-2 text-sm text-gray-500">Can you use your CSPC student email account?</p>
                    </div>

                    <div class="space-y-3 sm:space-y-4">
                        <a href="<?= base_url('auth/google') ?>"
                           id="studentCspcEmailBtn"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-700 active:scale-[0.98]">
                            <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-white/10 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <span id="studentCspcEmailText">Sign in with CSPC Email</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-60 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>

                        <button type="button"
                           id="studentNoCspcEmailBtn"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-white hover:bg-navy-50 text-navy-800 rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-200 active:scale-[0.98]">
                            <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-navy-100 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <span>I forgot my CSPC Email</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-40 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>

                        <a type="button"
                           href="https://profile.cspc.edu.ph/ClaimEmail"
                           class="login-btn group relative w-full flex items-center justify-center gap-3 bg-white hover:bg-navy-50 text-navy-800 rounded-2xl px-4 sm:px-6 py-3.5 sm:py-4 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 ring-1 ring-navy-200 active:scale-[0.98]">
                           <span class="absolute left-3 sm:left-5 flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-navy-100 rounded-xl">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <span>I don&rsquo;t have a CSPC Email</span>
                            <svg class="absolute right-3 sm:right-5 w-5 h-5 opacity-40 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>

                    <div class="bg-navy-50 rounded-xl px-4 py-3 border border-navy-100">
                        <p class="text-xs text-navy-600 leading-relaxed text-center">
                            <span class="font-semibold">Not sure?</span> If the school provided you with a <span class="font-medium">@my.cspc.edu.ph</span> email, choose the first option. Otherwise, select the second to create one now.
                        </p>
                    </div>

                    <p class="text-center text-xs text-gray-400 leading-relaxed px-4">
                        By signing in, you agree to our
                        <a href="#" class="text-navy-600 hover:underline">Terms of Service</a> and
                        <a href="#" class="text-navy-600 hover:underline">Privacy Policy</a>.
                    </p>
                </div>

                <!-- ===== VIEW 6: Student Verification Form ===== -->
                <div id="viewStudentForm" class="panel-view space-y-5 sm:space-y-6 hidden">

                    <button type="button" id="backToStudentOptions" class="inline-flex items-center gap-2 text-sm text-navy-600 hover:text-navy-800 font-medium transition-colors group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                        Back
                    </button>

                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-navy-100 mb-3">
                            <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-navy-900 tracking-tight">Student Verification</h2>
                        <p id="studentVerificationSubtitle" class="mt-1.5 text-sm text-gray-500">Enter your student number to get started</p>
                    </div>

                    <div class="space-y-4">

                        <!-- STEP 1: Student Number -->
                        <div id="stepStudentNumber">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Student Number <span class="text-red-500">*</span></label>
                            <input type="text" id="inputStudentNumber" name="student_number" required
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all placeholder:text-gray-400"
                                placeholder="e.g. 2021-00123">
                        </div>

                        <button type="button" id="btnVerifyStudent"
                            class="w-full flex items-center justify-center gap-2 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-6 py-3.5 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-[0.98]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span id="btnVerifyStudentText">Verify Student Number</span>
                        </button>

                        <!-- STEP 2: Identity Verification -->
                        <div id="stepStudentIdentityFields" class="space-y-4 hidden">
                            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-2.5">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm text-green-700 font-medium">Student found. Please confirm your identity.</span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">First Name <span class="text-red-500">*</span></label>
                                <input type="text" id="inputStudentFirstName" name="first_name" required
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all placeholder:text-gray-400"
                                    placeholder="Juan">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" id="inputStudentLastName" name="last_name" required
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all placeholder:text-gray-400"
                                    placeholder="Dela Cruz">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Birthdate <span class="text-red-500">*</span></label>
                                <input type="date" id="inputStudentBirthDate" name="birth_date" required
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all text-gray-700">
                            </div>

                            <button type="button" id="btnVerifyStudentIdentity"
                                class="w-full flex items-center justify-center gap-2 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-6 py-3.5 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span id="btnVerifyStudentIdentityText">Verify Identity</span>
                            </button>
                        </div>
                    </div>

                    <p class="text-center text-xs text-gray-400 leading-relaxed px-4">
                        By signing in, you agree to our
                        <a href="#" class="text-navy-600 hover:underline">Terms of Service</a> and
                        <a href="#" class="text-navy-600 hover:underline">Privacy Policy</a>.
                    </p>
                </div>

                <!-- ===== VIEW 7: Student Account Builder ===== -->
                <div id="viewStudentAccountBuilder" class="panel-view space-y-5 sm:space-y-6 hidden">

                    <button type="button" id="backToStudentVerification" class="inline-flex items-center gap-2 text-sm text-navy-600 hover:text-navy-800 font-medium transition-colors group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                        Back
                    </button>

                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-navy-100 mb-3">
                            <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-navy-900 tracking-tight">Create Your Student Email</h2>
                        <p class="mt-1.5 text-sm text-gray-500">Choose an available email or type your own</p>
                    </div>

                    <div id="studentEmailSuggestionsContainer" class="hidden">
                        <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Suggested Emails</label>
                        <div id="studentEmailSuggestionsLoading" class="flex items-center justify-center py-3">
                            <svg class="animate-spin h-5 w-5 text-navy-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 00-10 10h2z"></path></svg>
                            <span class="ml-2 text-sm text-gray-500">Loading suggestions...</span>
                        </div>
                        <div id="studentEmailSuggestionsList" class="grid gap-2"></div>
                    </div>

                    <form id="createStudentEmailForm" class="space-y-4">
                        <?= csrf_field() ?>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="email-field-wrap flex items-center">
                                <div class="relative flex-1 min-w-0">
                                    <input type="text" name="email" id="newStudentEmail" required
                                        class="w-full rounded-l-xl border border-gray-200 px-4 py-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:z-10 transition-all placeholder:text-gray-400"
                                        placeholder="juandelacruz" autocomplete="off">
                                    <span id="studentEmailStatusIcon" class="absolute right-3 top-1/2 -translate-y-1/2 hidden"></span>
                                </div>
                                <span class="email-domain-suffix inline-flex items-center px-4 rounded-r-xl border border-l-0 border-gray-200 bg-gray-50 text-gray-500 text-sm h-[46px]">
                                    @my.cspc.edu.ph
                                </span>
                            </div>
                            <p id="studentEmailStatusMessage" class="mt-1.5 text-xs hidden"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input type="password" id="studentPassword" name="password" required
                                    class="w-full rounded-xl border border-gray-200 pl-4 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all placeholder:text-gray-400"
                                    placeholder="Enter your password">
                                <button type="button" onclick="togglePassword('studentPassword', this)"
                                    class="absolute right-4 text-gray-400 hover:text-navy-400 transition-colors focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input type="password" id="studentConfirmPassword" name="confirm_password" required
                                    class="w-full rounded-xl border border-gray-200 pl-4 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-400 focus:border-transparent transition-all placeholder:text-gray-400"
                                    placeholder="Re-enter your password">
                                <button type="button" onclick="togglePassword('studentConfirmPassword', this)"
                                    class="absolute right-4 text-gray-400 hover:text-navy-400 transition-colors focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" id="btnCreateStudentAccount"
                            class="w-full flex items-center justify-center gap-2 bg-navy-800 hover:bg-navy-900 text-white rounded-2xl px-6 py-3.5 font-semibold text-sm sm:text-base transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-[0.98] disabled:opacity-60 disabled:pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span id="btnCreateStudentAccountText">Create Account</span>
                        </button>
                    </form>

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

            .email-field-wrap {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }

            .email-field-wrap input {
                border-radius: 0.75rem;
            }

            .email-domain-suffix {
                width: 100%;
                justify-content: center;
                border-left-width: 1px;
                border-radius: 0.75rem;
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
        const viewAccountBuilder = document.getElementById('viewEmployeeAccountBuilder');
        const btnEmployee  = document.getElementById('employeeLoginBtn');
        const btnBack      = document.getElementById('backToRoleSelect');
        const btnNoCspc    = document.getElementById('noCspcEmailBtn');
        const btnBackForm  = document.getElementById('backToEmployeeOptions');
        const btnBackVerification = document.getElementById('backToEmployeeVerification');
        const forgotAccountButton = document.getElementById('forgotAccountBtn');

        // View 3 step elements
        const stepEmployeeNumber   = document.getElementById('stepEmployeeNumber');
        const stepIdentityFields   = document.getElementById('stepIdentityFields');
        const btnVerifyEmployee    = document.getElementById('btnVerifyEmployee');
        const btnVerifyEmployeeText = document.getElementById('btnVerifyEmployeeText');
        const btnVerifyIdentity    = document.getElementById('btnVerifyIdentity');
        const btnVerifyIdentityText = document.getElementById('btnVerifyIdentityText');
        const inputEmployeeNumber  = document.getElementById('inputEmployeeNumber');
        const inputFirstName       = document.getElementById('inputFirstName');
        const inputLastName        = document.getElementById('inputLastName');
        const inputBirthDate       = document.getElementById('inputBirthDate');
        const verificationSubtitle = document.getElementById('verificationSubtitle');

        let forgotMode = false;

        const BASE_URL = '<?= base_url() ?>';
        const CSRF_NAME = '<?= csrf_token() ?>';
        const CSRF_HASH = '<?= csrf_hash() ?>';

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

        /** Reset View 3 back to Step 1 */
        function resetVerificationForm() {
            forgotMode = false;
            inputEmployeeNumber.value = '';
            inputEmployeeNumber.disabled = false;
            inputFirstName.value = '';
            inputLastName.value = '';
            inputBirthDate.value = '';
            stepIdentityFields.classList.add('hidden');
            btnVerifyEmployee.classList.remove('hidden');
            btnVerifyEmployee.disabled = false;
            btnVerifyEmployeeText.textContent = 'Verify Employee Number';
            verificationSubtitle.textContent = 'Enter your employee number to get started';
        }

        /** Show a spinner inside a button */
        function setLoading(btn, textEl, isLoading, originalText) {
            if (isLoading) {
                btn.disabled = true;
                btn.classList.add('opacity-80', 'pointer-events-none');
                textEl.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 00-10 10h2z"></path></svg>';
            } else {
                btn.disabled = false;
                btn.classList.remove('opacity-80', 'pointer-events-none');
                textEl.textContent = originalText;
            }
        }

        // ========================
        //  View navigation
        // ========================

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
            resetVerificationForm();
            switchView(viewEmployee, viewForm);
        });

        // View 3 -> View 2 (reset form on back)
        btnBackForm.addEventListener('click', function() {
            resetVerificationForm();
            switchView(viewForm, viewEmployee);
        });

        // View 4 -> View 3
        btnBackVerification.addEventListener('click', function() {
            resetVerificationForm();
            switchView(viewAccountBuilder, viewForm);
        });

        // ========================
        //  STEP 1: Verify Employee Number (AJAX)
        // ========================
        btnVerifyEmployee.addEventListener('click', async function() {
            const empNumber = inputEmployeeNumber.value.trim();
            if (!empNumber) {
                Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter your employee number.', confirmButtonColor: '#162557' });
                return;
            }

            setLoading(btnVerifyEmployee, btnVerifyEmployeeText, true, 'Verify Employee Number');

            try {
                const formData = new FormData();
                formData.append('employee_number', empNumber);
                formData.append(CSRF_NAME, CSRF_HASH);

                const res = await fetch(BASE_URL + '/employee/verify', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await res.json();

                if ((!res.ok || data.status !== 'success') && data.status !== 'has_email') {
                    Swal.fire({ icon: 'error', title: 'Verification Failed', text: data.message || 'Employee not found. Please check your employee number.', confirmButtonColor: '#162557' });
                    setLoading(btnVerifyEmployee, btnVerifyEmployeeText, false, 'Verify Employee Number');
                    return;
                }

                // Employee already has a CSPC email
                if (data.status === 'has_email') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Email Already Exists',
                        html: data.message,
                        confirmButtonColor: '#162557',
                        confirmButtonText: 'Use Google Sign-In',
                        showDenyButton: true,
                        denyButtonText: 'I forgot my CSPC Email / Password',
                        denyButtonColor: '#dc2626',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            // Go back to employee options to choose "I have a CSPC Email"
                            resetVerificationForm();
                            switchView(viewForm, viewEmployee);
                        } else if (result.isDenied) {
                            // Enter forgot-account mode: show identity fields directly
                            forgotMode = true;
                            inputEmployeeNumber.disabled = true;
                            btnVerifyEmployee.classList.add('hidden');
                            verificationSubtitle.textContent = 'Verify your identity to recover your account';
                            stepIdentityFields.classList.remove('hidden');
                            stepIdentityFields.style.animation = 'fadeIn 0.35s ease-out both';
                            inputFirstName.focus();
                        }
                    });
                    setLoading(btnVerifyEmployee, btnVerifyEmployeeText, false, 'Verify Employee Number');
                    return;
                }

                // Success — lock the employee number input and reveal Step 2
                inputEmployeeNumber.disabled = true;
                btnVerifyEmployee.classList.add('hidden');
                verificationSubtitle.textContent = 'Confirm your identity to continue';

                stepIdentityFields.classList.remove('hidden');
                stepIdentityFields.style.animation = 'fadeIn 0.35s ease-out both';
                inputLastName.focus();

            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach the server. Please try again.', confirmButtonColor: '#162557' });
                setLoading(btnVerifyEmployee, btnVerifyEmployeeText, false, 'Verify Employee Number');
            }
        });

        // ========================
        //  STEP 2: Verify Identity — first name, last name & birthdate (AJAX)
        // ========================
        btnVerifyIdentity.addEventListener('click', async function() {
            const firstName = inputFirstName.value.trim();
            const lastName  = inputLastName.value.trim();
            const birthDate = inputBirthDate.value;

            if (!firstName || !lastName || !birthDate) {
                Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter your first name, last name, and birthdate.', confirmButtonColor: '#162557' });
                return;
            }

            setLoading(btnVerifyIdentity, btnVerifyIdentityText, true, 'Verify Identity');

            try {
                const formData = new FormData();
                formData.append('first_name', firstName);
                formData.append('last_name', lastName);
                formData.append('birth_date', birthDate);
                formData.append(CSRF_NAME, CSRF_HASH);

                const endpoint = forgotMode
                    ? BASE_URL + '/employee/account-recovery'
                    : BASE_URL + '/employee/verify-data';

                const res = await fetch(endpoint, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await res.json();

                if (forgotMode) {
                    setLoading(btnVerifyIdentity, btnVerifyIdentityText, false, 'Verify Identity');

                    if (!res.ok && data.status === 'error') {
                        Swal.fire({ icon: 'error', title: 'Verification Failed', html: data.message || 'The information provided does not match our records.', confirmButtonColor: '#162557' });
                        return;
                    }

                    if (data.status === 'recovery_ticket_created') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Recovery Ticket Created',
                            html: data.message,
                            confirmButtonColor: '#162557',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            resetVerificationForm();
                            switchView(viewForm, viewRole);
                        });
                    } else {
                        // no_alt_email — advise to visit ICTU office
                        Swal.fire({
                            icon: 'info',
                            title: 'No Alternative Email Found',
                            html: data.message,
                            confirmButtonColor: '#162557',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            resetVerificationForm();
                            switchView(viewForm, viewRole);
                        });
                    }
                    return;
                }

                // Normal account-creation flow
                if (!res.ok || data.status !== 'success') {
                    Swal.fire({ icon: 'error', title: 'Verification Failed', text: data.message || 'The information provided does not match our records.', confirmButtonColor: '#162557' });
                    setLoading(btnVerifyIdentity, btnVerifyIdentityText, false, 'Verify Identity');
                    return;
                }

                // Success — identity verified, no email on file → go to View 4
                Swal.fire({
                    icon: 'success',
                    title: 'Identity Verified',
                    text: data.message,
                    confirmButtonColor: '#162557',
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    switchView(viewForm, viewAccountBuilder);
                    loadEmailSuggestions();
                });

            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach the server. Please try again.', confirmButtonColor: '#162557' });
                setLoading(btnVerifyIdentity, btnVerifyIdentityText, false, 'Verify Identity');
            }
        });

        // ========================
        //  VIEW 4: Email suggestions, availability check, account creation
        // ========================

        const newEmailInput         = document.getElementById('newEmail');
        const emailStatusIcon        = document.getElementById('emailStatusIcon');
        const emailStatusMessage     = document.getElementById('emailStatusMessage');
        const suggestionsContainer   = document.getElementById('emailSuggestionsContainer');
        const suggestionsLoading     = document.getElementById('emailSuggestionsLoading');
        const suggestionsList        = document.getElementById('emailSuggestionsList');
        const createEmailForm        = document.getElementById('createEmailForm');
        const btnCreateAccount       = document.getElementById('btnCreateAccount');
        const btnCreateAccountText   = document.getElementById('btnCreateAccountText');

        let emailCheckTimeout = null;
        let emailIsAvailable  = false;

        /** Fetch and render email suggestions */
        async function loadEmailSuggestions() {
            suggestionsContainer.classList.remove('hidden');
            suggestionsLoading.classList.remove('hidden');
            suggestionsList.innerHTML = '';

            try {
                const res = await fetch(BASE_URL + '/employee/email-suggestions', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                suggestionsLoading.classList.add('hidden');

                console.log(data);

                if (data.suggestions && data.suggestions.length > 0) {
                    data.suggestions.forEach(function(email) {
                        const localPart = email.replace('@cspc.edu.ph', '');
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'email-suggestion-btn flex items-center gap-2 w-full text-left px-3 py-2.5 rounded-xl border border-gray-200 hover:border-navy-400 hover:bg-navy-50 text-sm text-navy-800 transition-all duration-200 group';
                        btn.innerHTML = '<svg class="w-4 h-4 text-navy-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>'
                            + '<span class="truncate">' + email + '</span>'
                            + '<svg class="w-4 h-4 ml-auto text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';

                        btn.addEventListener('click', function() {
                            newEmailInput.value = localPart;
                            setEmailAvailable(true, 'Email is available');
                            // highlight selected
                            document.querySelectorAll('.email-suggestion-btn').forEach(function(b) {
                                b.classList.remove('ring-2', 'ring-navy-400', 'bg-navy-50');
                            });
                            btn.classList.add('ring-2', 'ring-navy-400', 'bg-navy-50');
                        });
                        suggestionsList.appendChild(btn);
                    });
                } else {
                    suggestionsList.innerHTML = '<p class="text-xs text-gray-400 text-center py-2">No suggestions available. Please type your preferred email.</p>';
                }
            } catch (err) {
                suggestionsLoading.classList.add('hidden');
                suggestionsList.innerHTML = '<p class="text-xs text-red-400 text-center py-2">Failed to load suggestions.</p>';
            }
        }

        /** Set the email availability indicator */
        function setEmailAvailable(available, message) {
            emailIsAvailable = available;
            emailStatusIcon.classList.remove('hidden');
            emailStatusMessage.classList.remove('hidden');

            if (available) {
                emailStatusIcon.innerHTML = '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                emailStatusMessage.className = 'mt-1.5 text-xs text-green-600';
                emailStatusMessage.textContent = 'Email is available';
                newEmailInput.classList.remove('border-red-400');
                newEmailInput.classList.add('border-green-400');
            } else {
                emailStatusIcon.innerHTML = '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                emailStatusMessage.className = 'mt-1.5 text-xs text-red-600';
                emailStatusMessage.textContent = 'Email is already taken';
                newEmailInput.classList.remove('border-green-400');
                newEmailInput.classList.add('border-red-400');
            }
        }

        /** Clear the email availability indicator */
        function clearEmailStatus() {
            emailIsAvailable = false;
            emailStatusIcon.classList.add('hidden');
            emailStatusIcon.innerHTML = '';
            emailStatusMessage.classList.add('hidden');
            emailStatusMessage.textContent = '';
            newEmailInput.classList.remove('border-green-400', 'border-red-400');
        }

        /** Check if email exists (debounced) */
        async function checkEmailAvailability(emailLocal) {
            if (!emailLocal) {
                clearEmailStatus();
                return;
            }

            const fullEmail = emailLocal.includes('@') ? emailLocal : emailLocal + '@cspc.edu.ph';

            // Show loading spinner while checking
            emailStatusIcon.classList.remove('hidden');
            emailStatusIcon.innerHTML = '<svg class="animate-spin h-5 w-5 text-navy-400" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 00-10 10h2z"></path></svg>';
            emailStatusMessage.classList.add('hidden');

            try {
                const res = await fetch(BASE_URL + '/employee/check-email/' + encodeURIComponent(fullEmail), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                if (res.ok) {
                    setEmailAvailable(true, data.message || 'Email is available');
                } else {
                    setEmailAvailable(false, data.message || 'Email is already taken');
                }
            } catch (err) {
                clearEmailStatus();
            }
        }

        // Debounced keyup + blur listener on email input
        newEmailInput.addEventListener('input', function() {
            clearTimeout(emailCheckTimeout);
            const val = newEmailInput.value.trim();
            if (!val) {
                clearEmailStatus();
                return;
            }
            emailCheckTimeout = setTimeout(function() {
                checkEmailAvailability(val);
            }, 600);
        });

        newEmailInput.addEventListener('blur', function() {
            clearTimeout(emailCheckTimeout);
            const val = newEmailInput.value.trim();
            if (val) {
                checkEmailAvailability(val);
            }
        });

        // ========================
        //  Create Email Form Submission (AJAX)
        // ========================
        createEmailForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const email           = newEmailInput.value.trim();
            const passwordVal     = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (!email) {
                Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter an email address.', confirmButtonColor: '#162557' });
                return;
            }

            if (!passwordVal || !confirmPassword) {
                Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter and confirm your password.', confirmButtonColor: '#162557' });
                return;
            }

            if (passwordVal !== confirmPassword) {
                Swal.fire({ icon: 'error', title: 'Mismatch', text: 'Password and confirm password do not match.', confirmButtonColor: '#162557' });
                return;
            }

            if (!emailIsAvailable) {
                Swal.fire({ icon: 'error', title: 'Unavailable', text: 'Please choose an available email before submitting.', confirmButtonColor: '#162557' });
                return;
            }

            setLoading(btnCreateAccount, btnCreateAccountText, true, 'Create Account');

            try {
                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', passwordVal);
                formData.append('confirm_password', confirmPassword);
                formData.append(CSRF_NAME, CSRF_HASH);

                const res = await fetch(BASE_URL + '/employee/create-email', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await res.json();

                if (res.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Account Created!',
                        text: data.message || 'You can now log in with your new email and password.',
                        confirmButtonColor: '#162557'
                    }).then(function() {
                        // Reset everything and go back to View 1
                        createEmailForm.reset();
                        clearEmailStatus();
                        suggestionsList.innerHTML = '';
                        suggestionsContainer.classList.add('hidden');
                        switchView(viewAccountBuilder, viewRole);
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to create account. Please try again.', confirmButtonColor: '#162557' });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach the server. Please try again.', confirmButtonColor: '#162557' });
            }

            setLoading(btnCreateAccount, btnCreateAccountText, false, 'Create Account');
        });

        // Loading spinner on actual link clicks (Google OAuth buttons)
        document.querySelectorAll('a.login-btn').forEach(function(btn) {
            btn.addEventListener('click', function () {
                const textEl = btn.querySelector('span[id]');
                if (textEl) {
                    textEl.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 00-10 10h2z"></path></svg>';
                }
                btn.classList.add('opacity-80', 'pointer-events-none');
            });
        });

        // ========================
        //  STUDENT FLOW
        // ========================

        const viewStudentOptions      = document.getElementById('viewStudentOptions');
        const viewStudentForm         = document.getElementById('viewStudentForm');
        const viewStudentAccountBuilder = document.getElementById('viewStudentAccountBuilder');

        const btnStudentLogin             = document.getElementById('studentLoginBtn');
        const btnBackToRoleFromStudent    = document.getElementById('backToRoleSelectFromStudent');
        const btnStudentNoCspc            = document.getElementById('studentNoCspcEmailBtn');
        const btnBackToStudentOptions     = document.getElementById('backToStudentOptions');
        const btnBackToStudentVerification = document.getElementById('backToStudentVerification');

        const inputStudentNumber          = document.getElementById('inputStudentNumber');
        const stepStudentIdentityFields   = document.getElementById('stepStudentIdentityFields');
        const btnVerifyStudent            = document.getElementById('btnVerifyStudent');
        const btnVerifyStudentText        = document.getElementById('btnVerifyStudentText');
        const btnVerifyStudentIdentity    = document.getElementById('btnVerifyStudentIdentity');
        const btnVerifyStudentIdentityText = document.getElementById('btnVerifyStudentIdentityText');
        const studentVerificationSubtitle = document.getElementById('studentVerificationSubtitle');

        let studentForgotMode = false;

        function resetStudentVerificationForm() {
            studentForgotMode = false;
            inputStudentNumber.value = '';
            inputStudentNumber.disabled = false;
            document.getElementById('inputStudentFirstName').value = '';
            document.getElementById('inputStudentLastName').value = '';
            document.getElementById('inputStudentBirthDate').value = '';
            stepStudentIdentityFields.classList.add('hidden');
            btnVerifyStudent.classList.remove('hidden');
            btnVerifyStudent.disabled = false;
            btnVerifyStudentText.textContent = 'Verify Student Number';
            studentVerificationSubtitle.textContent = 'Enter your student number to get started';
        }

        // View 1 -> View 5 (student options)
        btnStudentLogin.addEventListener('click', function() {
            switchView(viewRole, viewStudentOptions);
        });

        // View 5 -> View 1
        btnBackToRoleFromStudent.addEventListener('click', function() {
            switchView(viewStudentOptions, viewRole);
        });

        // View 5 -> View 6 (student form) — always in forgot mode
        btnStudentNoCspc.addEventListener('click', function() {
            resetStudentVerificationForm();
            studentForgotMode = true;
            switchView(viewStudentOptions, viewStudentForm);
        });

        // View 6 -> View 5
        btnBackToStudentOptions.addEventListener('click', function() {
            resetStudentVerificationForm();
            switchView(viewStudentForm, viewStudentOptions);
        });

        // View 7 -> View 6
        btnBackToStudentVerification.addEventListener('click', function() {
            resetStudentVerificationForm();
            switchView(viewStudentAccountBuilder, viewStudentForm);
        });

        // STEP 1: Verify Student Number
        btnVerifyStudent.addEventListener('click', async function() {
            const stuNumber = inputStudentNumber.value.trim();
            if (!stuNumber) {
                Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter your student number.', confirmButtonColor: '#162557' });
                return;
            }

            setLoading(btnVerifyStudent, btnVerifyStudentText, true, 'Verify Student Number');

            try {
                const formData = new FormData();
                formData.append('student_number', stuNumber);
                formData.append(CSRF_NAME, CSRF_HASH);

                const res = await fetch(BASE_URL + '/student/verify', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await res.json();

                if ((!res.ok || data.status !== 'success') && data.status !== 'has_email') {
                    Swal.fire({ icon: 'error', title: 'Verification Failed', text: data.message || 'Student not found. Please check your student number.', confirmButtonColor: '#162557' });
                    setLoading(btnVerifyStudent, btnVerifyStudentText, false, 'Verify Student Number');
                    return;
                }

                // Student found (success or has_email = student exists in API)
                inputStudentNumber.disabled = true;
                btnVerifyStudent.classList.add('hidden');
                studentVerificationSubtitle.textContent = studentForgotMode
                    ? 'Verify your identity to recover your account'
                    : 'Confirm your identity to continue';
                stepStudentIdentityFields.classList.remove('hidden');
                stepStudentIdentityFields.style.animation = 'fadeIn 0.35s ease-out both';
                document.getElementById('inputStudentFirstName').focus();

            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach the server. Please try again.', confirmButtonColor: '#162557' });
                setLoading(btnVerifyStudent, btnVerifyStudentText, false, 'Verify Student Number');
            }
        });

        // STEP 2: Verify Student Identity
        btnVerifyStudentIdentity.addEventListener('click', async function() {
            const firstName  = document.getElementById('inputStudentFirstName').value.trim();
            const lastName   = document.getElementById('inputStudentLastName').value.trim();
            const birthDate  = document.getElementById('inputStudentBirthDate').value;

            if (!firstName || !lastName || !birthDate) {
                Swal.fire({ icon: 'warning', title: 'Required', text: 'Please fill in all identity fields.', confirmButtonColor: '#162557' });
                return;
            }

            setLoading(btnVerifyStudentIdentity, btnVerifyStudentIdentityText, true, 'Verify Identity');

            try {
                const formData = new FormData();
                formData.append('first_name', firstName);
                formData.append('last_name', lastName);
                formData.append('birth_date', birthDate);
                formData.append(CSRF_NAME, CSRF_HASH);

                const endpoint = studentForgotMode
                    ? BASE_URL + '/student/account-recovery'
                    : BASE_URL + '/student/verify-data';

                const res = await fetch(endpoint, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await res.json();

                if (studentForgotMode) {
                    setLoading(btnVerifyStudentIdentity, btnVerifyStudentIdentityText, false, 'Verify Identity');

                    if (data.status === 'no_email' && data.redirect) {
                        Swal.fire({
                            icon: 'info',
                            title: 'No CSPC Email Found',
                            html: data.message,
                            confirmButtonColor: '#162557',
                            confirmButtonText: 'Go to Claim Portal'
                        }).then(function() {
                            window.location.href = data.redirect;
                        });
                        return;
                    }

                    if (!res.ok && data.status === 'error') {
                        Swal.fire({ icon: 'error', title: 'Verification Failed', html: data.message || 'The information provided does not match our records.', confirmButtonColor: '#162557' });
                        return;
                    }

                    if (data.status === 'recovery_ticket_created') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Recovery Ticket Created',
                            html: data.message,
                            confirmButtonColor: '#162557',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            resetStudentVerificationForm();
                            switchView(viewStudentForm, viewRole);
                        });
                    } else {
                        // no_alt_email — advise to visit ICTU office
                        Swal.fire({
                            icon: 'info',
                            title: 'No Alternative Email Saved',
                            html: data.message,
                            confirmButtonColor: '#162557',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            resetStudentVerificationForm();
                            switchView(viewStudentForm, viewRole);
                        });
                    }
                    return;
                }

                // Normal identity → redirect to claim email portal
                if (!res.ok || data.status !== 'success') {
                    Swal.fire({ icon: 'error', title: 'Verification Failed', text: data.message || 'The information provided does not match our records.', confirmButtonColor: '#162557' });
                    setLoading(btnVerifyStudentIdentity, btnVerifyStudentIdentityText, false, 'Verify Identity');
                    return;
                }

                if (data.status === 'no_email' && data.redirect) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No CSPC Email Found',
                        html: data.message,
                        confirmButtonColor: '#162557',
                        confirmButtonText: 'Go to Claim Portal'
                    }).then(function() {
                        window.location.href = data.redirect;
                    });
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Identity Verified',
                    text: data.message,
                    confirmButtonColor: '#162557',
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    window.location.href = 'https://profile.cspc.edu.ph/ClaimEmail';
                });

            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach the server. Please try again.', confirmButtonColor: '#162557' });
                setLoading(btnVerifyStudentIdentity, btnVerifyStudentIdentityText, false, 'Verify Identity');
            }
        });

        // ========================
        //  VIEW 7: Student Email suggestions & account creation
        // ========================

        const newStudentEmailInput          = document.getElementById('newStudentEmail');
        const studentEmailStatusIcon        = document.getElementById('studentEmailStatusIcon');
        const studentEmailStatusMessage     = document.getElementById('studentEmailStatusMessage');
        const studentSuggestionsContainer   = document.getElementById('studentEmailSuggestionsContainer');
        const studentSuggestionsLoading     = document.getElementById('studentEmailSuggestionsLoading');
        const studentSuggestionsList        = document.getElementById('studentEmailSuggestionsList');
        const createStudentEmailForm        = document.getElementById('createStudentEmailForm');
        const btnCreateStudentAccount       = document.getElementById('btnCreateStudentAccount');
        const btnCreateStudentAccountText   = document.getElementById('btnCreateStudentAccountText');

        let studentEmailCheckTimeout = null;
        let studentEmailIsAvailable  = false;

        async function loadStudentEmailSuggestions() {
            studentSuggestionsContainer.classList.remove('hidden');
            studentSuggestionsLoading.classList.remove('hidden');
            studentSuggestionsList.innerHTML = '';

            try {
                const res = await fetch(BASE_URL + '/student/email-suggestions', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                studentSuggestionsLoading.classList.add('hidden');

                if (data.suggestions && data.suggestions.length > 0) {
                    data.suggestions.forEach(function(email) {
                        const localPart = email.replace('@my.cspc.edu.ph', '');
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'student-email-suggestion-btn flex items-center gap-2 w-full text-left px-3 py-2.5 rounded-xl border border-gray-200 hover:border-navy-400 hover:bg-navy-50 text-sm text-navy-800 transition-all duration-200 group';
                        btn.innerHTML = '<svg class="w-4 h-4 text-navy-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>'
                            + '<span class="truncate">' + email + '</span>'
                            + '<svg class="w-4 h-4 ml-auto text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';

                        btn.addEventListener('click', function() {
                            newStudentEmailInput.value = localPart;
                            setStudentEmailAvailable(true, 'Email is available');
                            document.querySelectorAll('.student-email-suggestion-btn').forEach(function(b) {
                                b.classList.remove('ring-2', 'ring-navy-400', 'bg-navy-50');
                            });
                            btn.classList.add('ring-2', 'ring-navy-400', 'bg-navy-50');
                        });
                        studentSuggestionsList.appendChild(btn);
                    });
                } else {
                    studentSuggestionsList.innerHTML = '<p class="text-xs text-gray-400 text-center py-2">No suggestions available. Please type your preferred email.</p>';
                }
            } catch (err) {
                studentSuggestionsLoading.classList.add('hidden');
                studentSuggestionsList.innerHTML = '<p class="text-xs text-red-400 text-center py-2">Failed to load suggestions.</p>';
            }
        }

        function setStudentEmailAvailable(available, message) {
            studentEmailIsAvailable = available;
            studentEmailStatusIcon.classList.remove('hidden');
            studentEmailStatusMessage.classList.remove('hidden');

            if (available) {
                studentEmailStatusIcon.innerHTML = '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                studentEmailStatusMessage.className = 'mt-1.5 text-xs text-green-600';
                studentEmailStatusMessage.textContent = 'Email is available';
                newStudentEmailInput.classList.remove('border-red-400');
                newStudentEmailInput.classList.add('border-green-400');
            } else {
                studentEmailStatusIcon.innerHTML = '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                studentEmailStatusMessage.className = 'mt-1.5 text-xs text-red-600';
                studentEmailStatusMessage.textContent = 'Email is already taken';
                newStudentEmailInput.classList.remove('border-green-400');
                newStudentEmailInput.classList.add('border-red-400');
            }
        }

        function clearStudentEmailStatus() {
            studentEmailIsAvailable = false;
            studentEmailStatusIcon.classList.add('hidden');
            studentEmailStatusIcon.innerHTML = '';
            studentEmailStatusMessage.classList.add('hidden');
            studentEmailStatusMessage.textContent = '';
            newStudentEmailInput.classList.remove('border-green-400', 'border-red-400');
        }

        async function checkStudentEmailAvailability(emailLocal) {
            if (!emailLocal) { clearStudentEmailStatus(); return; }

            const fullEmail = emailLocal.includes('@') ? emailLocal : emailLocal + '@my.cspc.edu.ph';

            studentEmailStatusIcon.classList.remove('hidden');
            studentEmailStatusIcon.innerHTML = '<svg class="animate-spin h-5 w-5 text-navy-400" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 00-10 10h2z"></path></svg>';
            studentEmailStatusMessage.classList.add('hidden');

            try {
                const res = await fetch(BASE_URL + '/student/check-email/' + encodeURIComponent(fullEmail), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (res.ok) {
                    setStudentEmailAvailable(true, data.message || 'Email is available');
                } else {
                    setStudentEmailAvailable(false, data.message || 'Email is already taken');
                }
            } catch (err) {
                clearStudentEmailStatus();
            }
        }

        newStudentEmailInput.addEventListener('input', function() {
            clearTimeout(studentEmailCheckTimeout);
            const val = newStudentEmailInput.value.trim();
            if (!val) { clearStudentEmailStatus(); return; }
            studentEmailCheckTimeout = setTimeout(function() {
                checkStudentEmailAvailability(val);
            }, 600);
        });

        newStudentEmailInput.addEventListener('blur', function() {
            clearTimeout(studentEmailCheckTimeout);
            const val = newStudentEmailInput.value.trim();
            if (val) { checkStudentEmailAvailability(val); }
        });

        createStudentEmailForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const email           = newStudentEmailInput.value.trim();
            const passwordVal     = document.getElementById('studentPassword').value;
            const confirmPassword = document.getElementById('studentConfirmPassword').value;

            if (!email) {
                Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter an email address.', confirmButtonColor: '#162557' });
                return;
            }
            if (!passwordVal || !confirmPassword) {
                Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter and confirm your password.', confirmButtonColor: '#162557' });
                return;
            }
            if (passwordVal !== confirmPassword) {
                Swal.fire({ icon: 'error', title: 'Mismatch', text: 'Password and confirm password do not match.', confirmButtonColor: '#162557' });
                return;
            }
            if (!studentEmailIsAvailable) {
                Swal.fire({ icon: 'error', title: 'Unavailable', text: 'Please choose an available email before submitting.', confirmButtonColor: '#162557' });
                return;
            }

            setLoading(btnCreateStudentAccount, btnCreateStudentAccountText, true, 'Create Account');

            try {
                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', passwordVal);
                formData.append('confirm_password', confirmPassword);
                formData.append(CSRF_NAME, CSRF_HASH);

                const res = await fetch(BASE_URL + '/student/create-email', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await res.json();

                if (res.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Account Created!',
                        text: data.message || 'You can now log in with your new student email and password.',
                        confirmButtonColor: '#162557'
                    }).then(function() {
                        createStudentEmailForm.reset();
                        clearStudentEmailStatus();
                        studentSuggestionsList.innerHTML = '';
                        studentSuggestionsContainer.classList.add('hidden');
                        switchView(viewStudentAccountBuilder, viewRole);
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to create account. Please try again.', confirmButtonColor: '#162557' });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach the server. Please try again.', confirmButtonColor: '#162557' });
            }

            setLoading(btnCreateStudentAccount, btnCreateStudentAccountText, false, 'Create Account');
        });

    })();
    </script>
    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            
            // Toggle the type
            input.type = isPassword ? 'text' : 'password';

            // Optional: Toggle the icon appearance (Strike-through vs Eye)
            if (isPassword) {
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>`;
            } else {
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>`;
            }
        }
    </script>
</body>
</html> 