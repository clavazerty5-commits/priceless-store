<div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md mt-10">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-white">تسجيل الدخول</h2>
    
    <form wire:submit.prevent="login">
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 mb-2">البريد الإلكتروني</label>
            <input type="email" wire:model="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 dark:text-gray-300 mb-2">كلمة المرور</label>
            <input type="password" wire:model="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        
        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
            دخول
        </button>
        
        <p class="mt-4 text-center text-gray-600 dark:text-gray-400">
            ليس لديك حساب؟ <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">سجل الآن</a>
        </p>
    </form>
</div>
