@extends('app')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-semibold text-center mb-6">
            {{ $isExisting ? '已存在的短網址' : '成功創建短網址' }}
        </h1>

        <div class="space-y-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">短網址：</p>
                <div class="flex items-center gap-2">
                    <input type="text"
                           value="{{ url($shortUrl->short_code) }}"
                           class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg"
                           readonly>
                    <button onclick="copyToClipboard('{{ url($shortUrl->short_code) }}')"
                            class="px-3 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200">
                        複製
                    </button>
                </div>
            </div>

            <div class="text-sm text-gray-600">
                <p>原始網址：{{ Str::limit($shortUrl->original_url, 50) }}</p>
                @if($shortUrl->expired_at)
                    <p>過期時間：{{ $shortUrl->expired_at->format('Y-m-d H:i') }}</p>
                @endif
            </div>

            <a href="{{ route('short-url.create') }}"
               class="block w-full text-center bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                建立新的短網址
            </a>
        </div>
    </div>

    <div class="mt-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} URL Shortener
    </div>

    <script>
      function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
          alert('已複製到剪貼板');
        }).catch(err => {
          console.error('複製失敗:', err);
        });
      }
    </script>
@endsection
