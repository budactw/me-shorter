@extends('app')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-sm p-6">
        <form id="urlForm" class="space-y-4">
            @csrf

            <div class="mb-3">
                <input type="url"
                       name="original_url"
                       id="original_url"
                       placeholder="請輸入原始網址"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

{{--            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">--}}
{{--                <div>--}}
{{--                    <input type="datetime-local"--}}
{{--                           name="expired_at"--}}
{{--                           id="expired_at"--}}
{{--                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"--}}
{{--                           min="{{ now()->format('Y-m-d\TH:i') }}">--}}
{{--                </div>--}}
{{--            </div>--}}

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                生成短網址
            </button>
        </form>

        <!-- 錯誤訊息 -->
        <div id="error-message" class="mt-4 hidden">
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm">
                <p id="error-text"></p>
            </div>
        </div>

        <!-- 結果區域 -->
        <div id="results" class="mt-6 space-y-4 hidden">
            <h2 class="text-lg font-semibold mb-2">最近生成的短網址</h2>
            <div id="url-list" class="space-y-4">
                <!-- 動態插入的短網址會出現在這裡 -->
            </div>
        </div>
    </div>

    <!-- 短網址項目模板 -->
    <template id="url-item-template">
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="flex items-center gap-2 mb-2">
                <input type="text" readonly class="short-url-input w-full px-3 py-2 bg-white border border-gray-300 rounded-lg">
                <button onclick="copyToClipboard(this)"
                        class="copy-btn px-3 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-all duration-200">
                    複製
                </button>
            </div>
            <div class="text-sm text-gray-600">
                <p class="original-url truncate"></p>
                <div class="mt-2 bg-yellow-50 p-3 rounded-lg">
                    <p class="text-sm text-yellow-800 mb-2">請保存此刪除連結：</p>
                    <div class="flex items-center gap-2">
                        <input type="text" readonly class="delete-url-input w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm">
                        <button onclick="copyToClipboard(this)" class="px-3 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200 text-sm">
                            複製
                        </button>
                    </div>
                </div>

                <p class="created-at"></p>
                <p class="expired-at"></p>
            </div>
        </div>
    </template>

@endsection

@section('scripts')
    <script>
      document.getElementById('urlForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const errorDiv = document.getElementById('error-message');
        const resultsDiv = document.getElementById('results');

        try {
          const response = await fetch('{{ route("short-url.store") }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
          });

          const data = await response.json();

          if (data.success) {
            // 隱藏錯誤訊息
            errorDiv.classList.add('hidden');

            // 顯示結果區域
            resultsDiv.classList.remove('hidden');

            // 創建新的短網址項目
            const template = document.getElementById('url-item-template');
            const clone = template.content.cloneNode(true);

            // 填充數據
            clone.querySelector('.short-url-input').value = data.fullShortUrl;
            clone.querySelector('.original-url').textContent = `原始網址: ${data.shortUrl.original_url}`;
            clone.querySelector('.created-at').textContent = `創建時間: ${new Date().toLocaleString()}`;
            clone.querySelector('.delete-url-input').value = data.deleteUrl;


            if (data.shortUrl.expired_at) {
              clone.querySelector('.expired-at').textContent = `過期時間: ${new Date(data.shortUrl.expired_at).toLocaleString()}`;
            }

            // 插入到列表最前面
            const urlList = document.getElementById('url-list');
            urlList.insertBefore(clone, urlList.firstChild);

            // 重置表單
            form.reset();
          } else {
            throw new Error(data.message);
          }
        } catch (error) {
          errorDiv.classList.remove('hidden');
          document.getElementById('error-text').textContent = error.message || '生成短網址時發生錯誤';
        }
      });

      // 修改 copyToClipboard 函數
      async function copyToClipboard(button) {
        const input = button.parentElement.querySelector('input');
        const textToCopy = input.value;

        try {
          // 使用新的 Clipboard API
          await navigator.clipboard.writeText(textToCopy);

          // 視覺反饋
          const originalText = button.textContent;
          const originalBg = button.className;

          button.textContent = '已複製！';
          button.className = `${originalBg} bg-green-500 text-white`;

          setTimeout(() => {
            button.textContent = originalText;
            button.className = originalBg;
          }, 2000);
        } catch (err) {
          // 如果 Clipboard API 失敗，使用傳統方法
          try {
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');

            // 取消選取
            window.getSelection().removeAllRanges();

            // 視覺反饋
            const originalText = button.textContent;
            const originalBg = button.className;

            button.textContent = '已複製！';
            button.className = `${originalBg} bg-green-500 text-white`;

            setTimeout(() => {
              button.textContent = originalText;
              button.className = originalBg;
            }, 2000);
          } catch (err) {
            console.error('複製失敗:', err);
            alert('複製失敗，請手動複製');
          }
        }
      }
    </script>
@endsection
