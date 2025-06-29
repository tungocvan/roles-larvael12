<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Add') }}
        </h2>
    </x-slot>

    <div class="flex min-h-screen">

        {{-- Sidebar bên trái --}}
        <livewire:sidebar />

        {{-- Nội dung chính dịch sang phải --}}

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <h2 class="text-xl font-bold mb-4">Thêm user</h2>
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Thêm mới</a>

                {{-- Nội dung bảng user ở đây --}}
                <p class="mt-4 text-gray-600">Đây là nơi hiển thị Thêm user.</p>
            </div>
        </div>
    </div>
</x-app-layout>
