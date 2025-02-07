@extends('app')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-semibold text-center mb-6">刪除短網址</h1>

        <div class="space-y-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-medium mb-2">短網址資訊</h2>
                <p class="text-sm text-gray-600 mb-1">
                    短網址：<a href="{{ url($shortUrl->short_code) }}" class="text-blue-600 hover:underline" target="_blank">
                        {{ url($shortUrl->short_code) }}
                    </a>
                </p>
                <p class="text-sm text-gray-600 mb-1">原始網址：{{ $shortUrl->original_url }}</p>
                <p class="text-sm text-gray-600">建立時間：{{ $shortUrl->created_at->format('Y-m-d H:i:s') }}</p>
                @if($shortUrl->expired_at)
                    <p class="text-sm text-gray-600">過期時間：{{ $shortUrl->expired_at->format('Y-m-d H:i:s') }}</p>
                @endif
            </div>

            <div class="bg-red-50 p-4 rounded-lg">
                <p class="text-red-600 text-sm mb-4">確定要刪除這個短網址嗎？此操作無法復原。</p>
                <div class="flex justify-end space-x-3">
                    <a href="{{ url('/') }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200">
                        取消
                    </a>
                    <button onclick="deleteShortUrl()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                        確認刪除
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
      async function deleteShortUrl() {
        if (!confirm('確定要刪除這個短網址嗎？')) {
          return;
        }

        try {
          const response = await fetch('{{ route("short-url.delete", $deleteCode) }}', {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            }
          });

          const data = await response.json();

          if (data.success) {
            alert('短網址已成功刪除');
            window.location.href = '{{ url("/") }}';
          } else {
            throw new Error(data.message);
          }
        } catch (error) {
          alert(error.message || '刪除失敗，請稍後再試');
        }
      }
    </script>
@endsection
