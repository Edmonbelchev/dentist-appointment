<x-app-layout>
    <div class="py-16 bg-gradient-to-b from-white to-blue-50">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-6">
                🦷 {{ __('Welcome to the Dentist Appointments Platform') }}
            </h1>

            <p class="text-lg text-gray-700 mb-8">
                {{ __('This is your all-in-one system to manage and organize appointments with your dental patients.') }}
                <br>
                {{ __('Easily schedule, view, and manage your patients’ appointments in one place.') }}
            </p>

            @auth
                <a href="{{ url('/appointments') }}"
                    class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition shadow-md text-lg">
                    📅 {{ __('Go to My Appointments') }}
                </a>
            @else
                <div class="space-x-4">
                    <a href="{{ route('login') }}"
                        class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition shadow-md text-lg">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}"
                        class="inline-block bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition shadow-md text-lg">
                        {{ __('Register') }}
                    </a>
                </div>
            @endauth
        </div>

        <div class="mt-16 max-w-5xl mx-auto px-4 grid md:grid-cols-3 gap-8 text-left">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="text-3xl mb-2">📅</div>
                <h3 class="text-xl font-semibold mb-2">{{ __('Manage Appointments') }}</h3>
                <p class="text-gray-600">
                    {{ __('View, update or delete scheduled appointments with your patients easily and intuitively.') }}
                </p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="text-3xl mb-2">👤</div>
                <h3 class="text-xl font-semibold mb-2">{{ __('Patient Overview') }}</h3>
                <p class="text-gray-600">
                    {{ __('Quickly access all upcoming visits of each patient and review their history.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="text-3xl mb-2">🔔</div>
                <h3 class="text-xl font-semibold mb-2">{{ __('Notification Options') }}</h3>
                <p class="text-gray-600">
                    {{ __('Send automatic email or SMS notifications to remind patients about their appointments.') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
