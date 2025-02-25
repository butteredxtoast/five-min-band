<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Five Minute Band - Sign Up</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Success Banner (hidden by default) -->
    <div id="successBanner"
         class="fixed top-0 left-0 right-0 bg-green-500 text-white py-4 text-center transform -translate-y-full transition-transform duration-50">
        Thanks for signing up! 🎸
    </div>

    <!-- Home Button -->
    <div class="absolute top-4 left-4">
        <a href="{{ route('welcome') }}"
           class="inline-block bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
            ← Home
        </a>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Show any error messages -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Join Five Minute Band</h1>
                <p class="text-gray-600">Tell us about yourself!</p>
            </div>

            <!-- Form Container -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <form id="signupForm" action="{{ route('signup.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <!-- Instruments Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instrument(s) you play</label>
                        <div class="space-y-2">
                            @foreach(['Vocals', 'Guitar', 'Bass', 'Drums', 'Keys', 'Other'] as $instrument)
                                <div class="flex items-center">
                                    <input type="checkbox" name="instruments[]" id="{{ $instrument }}" value="{{ $instrument }}"
                                           class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                    <label for="{{ $instrument }}" class="ml-2 text-sm text-gray-700">{{ $instrument }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Other Field -->
                    <div>
                        <label for="other" class="block text-sm font-medium text-gray-700 mb-1">What's the other thing?</label>
                        <textarea name="other" id="other" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full bg-emerald-600 text-white font-semibold px-6 py-3 rounded-lg text-lg hover:bg-emerald-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const form = document.getElementById('signupForm');

                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();

                        try {
                            // Send the form data
                            const formData = new FormData(e.target);
                            const response = await fetch('{{ route('signup.store') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                }
                            });

                            if (!response.ok) {
                                const errorData = await response.json();
                                throw new Error(errorData.message || 'Submission failed');
                            }

                            // Show success banner
                            const banner = document.getElementById('successBanner');
                            banner.style.transform = 'translateY(0)';

                            // Clear the form
                            e.target.reset();

                            // Hide banner after 10 seconds
                            setTimeout(() => {
                                banner.style.transform = 'translateY(-100%)';
                            }, 10000);

                        } catch (error) {
                            console.error('Error:', error);
                            // Optional: Show error message to user
                            alert('Failed to submit form. Please try again.');
                        }
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>
