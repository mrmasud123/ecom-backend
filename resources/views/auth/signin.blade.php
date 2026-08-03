@extends('layouts.fullscreen-layout')

@section('content')
    <div class="relative z-1 min-h-screen w-full flex items-center justify-center overflow-hidden bg-gradient-to-br from-[#FFF3E6] via-[#FFE8D6] to-[#FFDDD2] dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 px-4 py-10 font-sans">

        <!-- Decorative blurred blobs -->
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 rounded-full bg-[#FF7A1A]/40 blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 rounded-full bg-[#FF4D6D]/40 blur-[100px]"></div>
        <div class="absolute top-1/3 right-1/4 w-72 h-72 rounded-full bg-[#FFC65C]/30 blur-[90px]"></div>

        <div class="relative w-full max-w-md">

            <!-- Animated shop mark -->
            <div class="flex justify-center mb-3">
                <div class="relative flex items-center justify-center w-20 h-20">
                    <span class="absolute inline-flex h-full w-full rounded-full bg-[#FF7A1A]/20 animate-ping"></span>
                    <span class="absolute inline-flex h-[88%] w-[88%] rounded-full bg-[#FF7A1A]/10 animate-pulse"></span>
                    <div class="relative z-10 flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-[#FF7A1A] to-[#FF4D6D] shadow-lg shadow-[#FF7A1A]/30">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 8L7 20H17L18 8" stroke="#FFF7ED" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 8V6C9 4.34315 10.3431 3 12 3C13.6569 3 15 4.34315 15 6V8" stroke="#FFF7ED" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M4.5 8H19.5" stroke="#FFF7ED" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="text-center mb-2">
                <span class="text-[11px] font-semibold tracking-widest text-[#FF7A1A] uppercase drop-shadow-sm">Welcome Back · Sign In</span>
            </div>

            <!-- Glassmorphic card -->
            <div class="relative bg-white/30 dark:bg-white/5 backdrop-blur-2xl rounded-2xl shadow-2xl shadow-orange-900/10 border border-white/50 dark:border-white/10 p-7 sm:p-8 ring-1 ring-white/40 dark:ring-white/5">

                <!-- inner top highlight sheen -->
                <div class="pointer-events-none absolute inset-x-0 top-0 h-24 rounded-t-2xl bg-gradient-to-b from-white/40 to-transparent"></div>

                <div class="relative">

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-5">
                        <a href="{{ route('auth.google.redirect') }}"
                           class="inline-flex items-center justify-center gap-2 h-11 rounded-lg border border-white/60 dark:border-white/10 bg-white/40 dark:bg-white/5 backdrop-blur-md text-sm font-medium text-gray-700 dark:text-white/90 hover:border-[#FF7A1A] hover:bg-white/60 dark:hover:bg-white/10 transition-colors">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.7511 10.1944C18.7511 9.47495 18.6915 8.94995 18.5626 8.40552H10.1797V11.6527H15.1003C15.0011 12.4597 14.4654 13.675 13.2749 14.4916L13.2582 14.6003L15.9087 16.6126L16.0924 16.6305C17.7788 15.1041 18.7511 12.8583 18.7511 10.1944Z" fill="#4285F4" />
                                <path d="M10.1788 18.75C12.5895 18.75 14.6133 17.9722 16.0915 16.6305L13.274 14.4916C12.5201 15.0068 11.5081 15.3666 10.1788 15.3666C7.81773 15.3666 5.81379 13.8402 5.09944 11.7305L4.99473 11.7392L2.23868 13.8295L2.20264 13.9277C3.67087 16.786 6.68674 18.75 10.1788 18.75Z" fill="#34A853" />
                                <path d="M5.10014 11.7305C4.91165 11.186 4.80257 10.6027 4.80257 9.99992C4.80257 9.3971 4.91165 8.81379 5.09022 8.26935L5.08523 8.1534L2.29464 6.02954L2.20333 6.0721C1.5982 7.25823 1.25098 8.5902 1.25098 9.99992C1.25098 11.4096 1.5982 12.7415 2.20333 13.9277L5.10014 11.7305Z" fill="#FBBC05" />
                                <path d="M10.1789 4.63331C11.8554 4.63331 12.9864 5.34303 13.6312 5.93612L16.1511 3.525C14.6035 2.11528 12.5895 1.25 10.1789 1.25C6.68676 1.25 3.67088 3.21387 2.20264 6.07218L5.08953 8.26943C5.81381 6.15972 7.81776 4.63331 10.1789 4.63331Z" fill="#EB4335" />
                            </svg>
                            Google
                        </a>
                        <button type="button"
                                class="inline-flex items-center justify-center gap-2 h-11 rounded-lg border border-white/60 dark:border-white/10 bg-white/40 dark:bg-white/5 backdrop-blur-md text-sm font-medium text-gray-700 dark:text-white/90 hover:border-[#FF7A1A] hover:bg-white/60 dark:hover:bg-white/10 transition-colors">
                            <svg width="15" height="15" class="fill-current" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.6705 1.875H18.4272L12.4047 8.75833L19.4897 18.125H13.9422L9.59717 12.4442L4.62554 18.125H1.86721L8.30887 10.7625L1.51221 1.875H7.20054L11.128 7.0675L15.6705 1.875ZM14.703 16.475H16.2305L6.37054 3.43833H4.73137L14.703 16.475Z" />
                            </svg>
                            X
                        </button>
                    </div>

                    <div class="relative flex items-center justify-center mb-5">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-white/40 dark:border-white/10"></div>
                        </div>
                        <span class="relative bg-white/0 backdrop-blur-sm px-3 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">or sign in with email</span>
                    </div>

                    <form action="{{ route('login') }}" method="post" id="loginForm">
                        @csrf
                        <div class="space-y-5">

                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Email<span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" placeholder="you@example.com"
                                       class="h-11 w-full rounded-lg border border-white/60 dark:border-white/10 bg-white/40 dark:bg-white/5 backdrop-blur-md px-4 text-sm text-gray-800 dark:text-white/90 placeholder:text-gray-500 focus:outline-none focus:ring-3 focus:ring-[#FF7A1A]/20 focus:border-[#FF7A1A] focus:bg-white/60" />
                            </div>

                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Password<span class="text-red-500">*</span>
                                </label>
                                <div x-data="{ showPassword: false }" class="relative">
                                    <input id="password" name="password" :type="showPassword ? 'text' : 'password'"
                                           placeholder="Enter your password"
                                           class="h-11 w-full rounded-lg border border-white/60 dark:border-white/10 bg-white/40 dark:bg-white/5 backdrop-blur-md py-2.5 pr-11 pl-4 text-sm text-gray-800 dark:text-white/90 placeholder:text-gray-500 focus:outline-none focus:ring-3 focus:ring-[#FF7A1A]/20 focus:border-[#FF7A1A] focus:bg-white/60" />
                                    <span @click="showPassword = !showPassword"
                                          class="absolute top-1/2 right-4 z-30 -translate-y-1/2 cursor-pointer text-gray-500 hover:text-[#FF7A1A] transition-colors">
                                        <svg x-show="!showPassword" width="19" height="19" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z" fill="currentColor" />
                                        </svg>
                                        <svg x-show="showPassword" width="19" height="19" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div x-data="{ checkboxToggle: false }">
                                    <label for="checkboxLabelOne" class="flex cursor-pointer items-center text-sm font-normal text-gray-700 dark:text-gray-300 select-none">
                                        <div class="relative">
                                            <input type="checkbox" id="checkboxLabelOne" class="sr-only" @change="checkboxToggle = !checkboxToggle" />
                                            <div :class="checkboxToggle ? 'border-[#FF7A1A] bg-gradient-to-br from-[#FF7A1A] to-[#FF4D6D]' : 'bg-white/30 border-white/60 dark:border-white/10'"
                                                 class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.5px] backdrop-blur-sm transition-colors">
                                                <span :class="checkboxToggle ? '' : 'opacity-0'">
                                                    <svg width="13" height="13" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        Keep me logged in
                                    </label>
                                </div>
                                <a href="/reset-password" class="text-[#FF7A1A] hover:text-[#E9660A] text-sm font-medium">Forgot password?</a>
                            </div>

                            <button class="w-full h-11 rounded-lg bg-gradient-to-r from-[#FF7A1A] to-[#FF4D6D] hover:opacity-90 text-white text-sm font-semibold transition-opacity shadow-lg shadow-[#FF7A1A]/30">
                                Sign In
                            </button>

                        </div>
                    </form>

{{--                    <p class="mt-6 text-center text-sm font-normal text-gray-600 dark:text-gray-400">--}}
{{--                        Don't have an account?--}}
{{--                        <a href="/signup" class="text-[#FF7A1A] hover:text-[#E9660A] font-medium">Sign Up</a>--}}
{{--                    </p>--}}

                </div>
            </div>
        </div>

        <!-- Theme Toggler -->
        <div class="fixed right-6 bottom-6 z-50">
            <button class="bg-gradient-to-br from-[#FF7A1A] to-[#FF4D6D] hover:opacity-90 inline-flex size-14 items-center justify-center rounded-full text-white shadow-lg backdrop-blur-md transition-opacity"
                    @click.prevent="$store.theme.toggle()">
                <svg class="hidden fill-current dark:block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M9.99998 1.5415C10.4142 1.5415 10.75 1.87729 10.75 2.2915V3.5415C10.75 3.95572 10.4142 4.2915 9.99998 4.2915C9.58577 4.2915 9.24998 3.95572 9.24998 3.5415V2.2915C9.24998 1.87729 9.58577 1.5415 9.99998 1.5415ZM10.0009 6.79327C8.22978 6.79327 6.79402 8.22904 6.79402 10.0001C6.79402 11.7712 8.22978 13.207 10.0009 13.207C11.772 13.207 13.2078 11.7712 13.2078 10.0001C13.2078 8.22904 11.772 6.79327 10.0009 6.79327ZM5.29402 10.0001C5.29402 7.40061 7.40135 5.29327 10.0009 5.29327C12.6004 5.29327 14.7078 7.40061 14.7078 10.0001C14.7078 12.5997 12.6004 14.707 10.0009 14.707C7.40135 14.707 5.29402 12.5997 5.29402 10.0001ZM15.9813 5.08035C16.2742 4.78746 16.2742 4.31258 15.9813 4.01969C15.6884 3.7268 15.2135 3.7268 14.9207 4.01969L14.0368 4.90357C13.7439 5.19647 13.7439 5.67134 14.0368 5.96423C14.3297 6.25713 14.8045 6.25713 15.0974 5.96423L15.9813 5.08035ZM18.4577 10.0001C18.4577 10.4143 18.1219 10.7501 17.7077 10.7501H16.4577C16.0435 10.7501 15.7077 10.4143 15.7077 10.0001C15.7077 9.58592 16.0435 9.25013 16.4577 9.25013H17.7077C18.1219 9.25013 18.4577 9.58592 18.4577 10.0001ZM14.9207 15.9806C15.2135 16.2735 15.6884 16.2735 15.9813 15.9806C16.2742 15.6877 16.2742 15.2128 15.9813 14.9199L15.0974 14.036C14.8045 13.7431 14.3297 13.7431 14.0368 14.036C13.7439 14.3289 13.7439 14.8038 14.0368 15.0967L14.9207 15.9806ZM9.99998 15.7088C10.4142 15.7088 10.75 16.0445 10.75 16.4588V17.7088C10.75 18.123 10.4142 18.4588 9.99998 18.4588C9.58577 18.4588 9.24998 18.123 9.24998 17.7088V16.4588C9.24998 16.0445 9.58577 15.7088 9.99998 15.7088ZM5.96356 15.0972C6.25646 14.8043 6.25646 14.3295 5.96356 14.0366C5.67067 13.7437 5.1958 13.7437 4.9029 14.0366L4.01902 14.9204C3.72613 15.2133 3.72613 15.6882 4.01902 15.9811C4.31191 16.274 4.78679 16.274 5.07968 15.9811L5.96356 15.0972ZM4.29224 10.0001C4.29224 10.4143 3.95645 10.7501 3.54224 10.7501H2.29224C1.87802 10.7501 1.54224 10.4143 1.54224 10.0001C1.54224 9.58592 1.87802 9.25013 2.29224 9.25013H3.54224C3.95645 9.25013 4.29224 9.58592 4.29224 10.0001ZM4.9029 5.9637C5.1958 6.25659 5.67067 6.25659 5.96356 5.9637C6.25646 5.6708 6.25646 5.19593 5.96356 4.90303L5.07968 4.01915C4.78679 3.72626 4.31191 3.72626 4.01902 4.01915C3.72613 4.31204 3.72613 4.78692 4.01902 5.07981L4.9029 5.9637Z" fill="" />
                </svg>
                <svg class="fill-current dark:hidden" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.4547 11.97L18.1799 12.1611C18.265 11.8383 18.1265 11.4982 17.8401 11.3266C17.5538 11.1551 17.1885 11.1934 16.944 11.4207L17.4547 11.97ZM8.0306 2.5459L8.57989 3.05657C8.80718 2.81209 8.84554 2.44682 8.67398 2.16046C8.50243 1.8741 8.16227 1.73559 7.83948 1.82066L8.0306 2.5459ZM12.9154 13.0035C9.64678 13.0035 6.99707 10.3538 6.99707 7.08524H5.49707C5.49707 11.1823 8.81835 14.5035 12.9154 14.5035V13.0035ZM16.944 11.4207C15.8869 12.4035 14.4721 13.0035 12.9154 13.0035V14.5035C14.8657 14.5035 16.6418 13.7499 17.9654 12.5193L16.944 11.4207ZM16.7295 11.7789C15.9437 14.7607 13.2277 16.9586 10.0003 16.9586V18.4586C13.9257 18.4586 17.2249 15.7853 18.1799 12.1611L16.7295 11.7789ZM10.0003 16.9586C6.15734 16.9586 3.04199 13.8433 3.04199 10.0003H1.54199C1.54199 14.6717 5.32892 18.4586 10.0003 18.4586V16.9586ZM3.04199 10.0003C3.04199 6.77289 5.23988 4.05695 8.22173 3.27114L7.83948 1.82066C4.21532 2.77574 1.54199 6.07486 1.54199 10.0003H3.04199ZM6.99707 7.08524C6.99707 5.52854 7.5971 4.11366 8.57989 3.05657L7.48132 2.03522C6.25073 3.35885 5.49707 5.13487 5.49707 7.08524H6.99707Z" fill="" />
                </svg>
            </button>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $(function () {

                var form = $("#loginForm");

                form.on("submit", function (e) {
                    e.preventDefault();

                    let email = $("#email").val();
                    let password = $("#password").val();

                    if (!email || !password) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Missing Fields',
                            text: 'Please enter email and password'
                        });
                        return;
                    }

                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr("method"),
                        data: form.serialize(),
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Logging in...',
                                text: 'Please wait',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            setTimeout(() => {
                                window.location.href = "/";
                            }, 1500);
                        },
                        error: function (xhr) {
                            let message = "Login failed";

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message
                            });
                        }
                    });

                });

            });

        });
    </script>
@endpush
