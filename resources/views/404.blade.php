@extends('app')

@section('content')
    <div class="min-h-[400px] flex items-center justify-center">
        <div class="max-w-md w-full mx-auto text-center p-6">
            <div class="mb-8">
                <h1 class="text-6xl font-bold text-gray-300 mb-3">404</h1>
                <p class="text-xl text-gray-600 mb-6">找不到頁面</p>

                @if(isset($exception) && $exception->getMessage() !== 'Not Found')
                    <p class="text-gray-600 mb-6">{{ $exception->getMessage() }}</p>
                @else
                    <p class="text-gray-600 mb-6">您要找的頁面不存在或已經被移除</p>
                @endif
            </div>

            <div class="space-y-4">
                <a href="{{ url('/') }}"
                   class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    返回首頁
                </a>

                <div class="text-sm text-gray-500">
                    <p>如果您是從其他網站連結過來的，</p>
                    <p>可能是該連結已經失效或過期</p>
                </div>
            </div>
        </div>
    </div>
@endsection
