<!doctype html>

<title>Laravel From Scratch Blog</title>
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

<body style="font-family: Open Sans, sans-serif">
    <section class="px-6 py-8">
        <nav class="md:flex md:justify-between md:items-center">
            <div>
                <a href="/">
                    <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="Laracasts Logo" width="25" height="25">
                </a>
            </div>

            <div class="mt-8 md:mt-0 flex item-center">
                @auth
                <span class="ml-8 mt-3 text-xs font-bold uppercase">Welcome Back!
                </span> <span class="ml-2 mt-3 text-xs font-bold uppercase">{{ Auth()->user()->name }}</span>

                <form action="/logout" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="ml-4 mt-3 text-xs font-bold uppercase">Logout</button>
                </form>
                @else
                <a href="/register" class="text-xs mt-3 font-bold uppercase">Register</a>
                <a href="/login" class="text-xs mt-3 font-bold uppercase ml-3">Login</a>
                @endauth
                <a href="#" class="bg-blue-500 ml-3 rounded-full text-xs font-semibold text-white uppercase py-3 px-5">
                    Subscribe for Updates
                </a>
            </div>
        </nav>

        {{ $slot }}

        <footer class="bg-gray-100 border border-black border-opacity-5 rounded-xl text-center py-16 px-10 mt-16">
            <img src="/images/lary-newsletter-icon.svg" alt="" class="mx-auto -mb-6" style="width: 145px;">
            <h5 class="text-3xl">Stay in touch with the latest posts</h5>
            <p class="text-sm mt-3">Promise to keep the inbox clean. No bugs.</p>

            <div class="mt-10">
                <div class="relative inline-block mx-auto lg:bg-gray-200 rounded-full">

                    <form method="POST" action="#" class="lg:flex text-sm">
                        <div class="lg:py-3 lg:px-5 flex items-center">
                            <label for="email" class="hidden lg:inline-block">
                                <img src="/images/mailbox-icon.svg" alt="mailbox letter">
                            </label>

                            <input id="email" type="text" placeholder="Your email address"
                                class="lg:bg-transparent py-2 lg:py-0 pl-4 focus-within:outline-none">
                        </div>

                        <button type="submit"
                            class="transition-colors duration-300 bg-blue-500 hover:bg-blue-600 mt-4 lg:mt-0 lg:ml-3 rounded-full text-xs font-semibold text-white uppercase py-3 px-8">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </footer>
    </section>
    @if (session()->has('success'))
    {{-- <div class="fixed buttom-3 right-2 bg-blue-500 text-white py-2 px-4 rounded-xl test-sm"> --}}
        <p class="success">{{ session('success') }}</p>
        {{--
    </div> --}}
    @endif
</body>