@extends('app')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <form id="urlForm" class="space-y-4">
            @csrf

            <!-- 原始網址輸入框 -->
            <!-- 輸入和貼上按鈕區塊 -->
            <div class="space-y-2">
                <!-- 輸入框 -->
                <input type="url"
                       name="original_url"
                       id="original_url"
                       placeholder="請輸入原始網址"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-base"
                       required>

                <!-- 貼上按鈕 - 只在手機版顯示 -->
                <button type="button"
                        id="pasteButton"
                        class="hidden md:hidden w-full flex items-center justify-center gap-2 py-3 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 rounded-lg transition text-base font-medium"
                        onclick="pasteUrl()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    從剪貼簿貼上
                </button>
            </div>

            <!-- 是否設定有效期限 -->
            <div class="flex items-center gap-2">
                <input type="checkbox" id="set_expiry"
                       class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="set_expiry" class="text-sm font-medium text-gray-700">設定有效期限</label>
            </div>

            <!-- 有效期限輸入框 -->
            <div id="expiry_field" class="hidden">
                <label for="expired_at" class="block text-sm font-medium text-gray-700">有效期限 (最多設定 1 年)</label>
                <input type="datetime-local"
                       name="expired_at"
                       id="expired_at"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- 生成短網址按鈕 -->
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
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
        <div id="results" class="mt-6 hidden">
            <h2 class="text-lg font-semibold mb-4">最近生成的短網址</h2>
            <div id="url-list" class="space-y-4">
                <!-- 動態插入的短網址會出現在這裡 -->
            </div>
        </div>
    </div>

    <!-- 短網址項目模板 -->
    <template id="url-item-template">
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="space-y-3">
                <!-- 短網址輸入框獨立一行 -->
                <div class="w-full">
                    <input type="text" readonly
                           class="short-url-input w-full px-3 py-2 bg-white border border-gray-300 rounded-lg">
                </div>

                <!-- 按鈕群組獨立一行 -->
                <div class="flex items-center gap-2">
                    <button onclick="copyShortUrl(this)"
                            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition text-sm">
                        複製
                    </button>
                    <button onclick="generateQRCode(this)"
                            class="px-4 py-2 bg-blue-200 rounded-lg hover:bg-blue-300 transition text-sm">
                        QR Code
                    </button>
                    <button onclick="shareUrl(this)"
                            class="px-4 py-2 bg-green-200 rounded-lg hover:bg-green-300 transition text-sm">
                        分享
                    </button>
                </div>

                <div class="qr-code-container hidden flex justify-center mt-4"></div>
                <div class="text-sm text-gray-600">
                    <p class="original-url truncate"></p>
                    <p class="created-at"></p>
                    <p class="expired-at"></p>
                    <div class="mt-2 bg-yellow-50 p-3 rounded-lg">
                        <p class="text-sm text-yellow-800 mb-2">請保存此刪除連結：</p>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly
                                   class="delete-url-input w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm">
                            <button onclick="copyDeleteUrl(this)"
                                    class="px-3 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-200 text-sm">
                                複製
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>


@endsection

@section('scripts')

    <script>
      // 動態顯示或隱藏有效期限欄位
      document.getElementById('set_expiry').addEventListener('change', function () {
        const expiryField = document.getElementById('expiry_field');
        expiryField.classList.toggle('hidden', !this.checked);
        if (!this.checked) document.getElementById('expired_at').value = null;
      });

      // 頁面載入時檢查是否為手機
      document.addEventListener('DOMContentLoaded', function() {
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        const pasteButton = document.getElementById('pasteButton');

        if (isMobile) {
          pasteButton.classList.remove('hidden');
        }
      });

      // 表單提交邏輯
      document.getElementById('urlForm').addEventListener('submit', async function (e) {
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
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData)),
          });

          const data = await response.json();

          if (data.success) {
            errorDiv.classList.add('hidden');
            resultsDiv.classList.remove('hidden');

            const template = document.getElementById('url-item-template');
            const clone = template.content.cloneNode(true);

            clone.querySelector('.short-url-input').value = data.fullChineseShortUrl;
            clone.querySelector('.original-url').textContent = `原始網址: ${data.shortUrl.original_url}`;
            clone.querySelector('.created-at').textContent = `創建時間: ${new Date().toLocaleString()}`;
            if (data.shortUrl.expired_at) {
              clone.querySelector('.expired-at').textContent = `過期時間: ${new Date(data.shortUrl.expired_at).toLocaleString()}`;
            }

            clone.querySelector('.delete-url-input').value = data.deleteChineseUrl;

            document.getElementById('url-list').prepend(clone);
            form.reset();
            document.getElementById('expiry_field').classList.add('hidden');
            document.getElementById('set_expiry').checked = false;
          } else {
            throw new Error(data.message);
          }
        } catch (error) {
          errorDiv.classList.remove('hidden');
          document.getElementById('error-text').textContent = error.message || '生成短網址時發生錯誤';
        }
      });

      // 複製短網址
      async function copyShortUrl(button) {
        const input = button.closest('.space-y-3').querySelector('.short-url-input');
        await copyToClipboard(input, button);
      }

      // 複製刪除網址
      async function copyDeleteUrl(button) {
        const input = button.parentElement.querySelector('.delete-url-input');
        await copyToClipboard(input, button);
      }

      // 通用複製功能
      async function copyToClipboard(input, button) {
        const textToCopy = input.value;

        try {
          await navigator.clipboard.writeText(textToCopy);
          showCopySuccess(button);
        } catch (err) {
          try {
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            showCopySuccess(button);
          } catch (err) {
            console.error('複製失敗:', err);
            alert('複製失敗，請手動複製');
          }
        }
      }

      // 顯示複製成功的視覺反饋
      function showCopySuccess(button) {
        const originalText = button.textContent;
        const originalBg = button.className;

        button.textContent = '已複製！';
        button.className = `${originalBg} bg-green-500 text-white`;

        setTimeout(() => {
          button.textContent = originalText;
          button.className = originalBg;
        }, 2000);
      }

      // 生成 QR Code
      function generateQRCode(button) {
        const container = button.closest('.space-y-3').querySelector('.qr-code-container');
        const shortUrl = button.closest('.space-y-3').querySelector('.short-url-input').value;

        if (container.classList.contains('hidden')) {
          container.classList.remove('hidden');
          container.innerHTML = '';
          const qrCode = new QRCodeStyling({ width: 150, height: 150, data: shortUrl });
          qrCode.append(container);
        } else {
          container.classList.add('hidden');
        }
      }

      // 分享短網址
      function shareUrl(button) {
        const shortUrl = button.closest('.space-y-3').querySelector('.short-url-input').value;
        if (navigator.share) {
          navigator.share({ title: '分享短網址', url: shortUrl }).catch(() => alert('分享失敗'));
        } else {
          alert('您的瀏覽器不支援分享功能');
        }
      }

      // 貼上功能
      async function pasteUrl() {
        const urlInput = document.getElementById('original_url');
        const pasteButton = document.getElementById('pasteButton');

        try {
          // 優先使用 navigator.share API
          if (navigator.clipboard && navigator.clipboard.readText) {
            const text = await navigator.clipboard.readText();
            urlInput.value = text;
            showPasteSuccess();
          } else {
            // 回退方案：請求剪貼簿權限
            const permissionResult = await navigator.permissions.query({
              name: 'clipboard-read'
            });

            if (permissionResult.state === 'granted' || permissionResult.state === 'prompt') {
              const text = await navigator.clipboard.readText();
              urlInput.value = text;
              showPasteSuccess();
            } else {
              throw new Error('需要剪貼簿存取權限');
            }
          }
        } catch (err) {
          console.error('貼上失敗:', err);

          // 顯示錯誤提示
          const originalText = pasteButton.innerHTML;
          pasteButton.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    請手動貼上網址
                `;
          pasteButton.classList.add('bg-red-100', 'text-red-700');

          setTimeout(() => {
            pasteButton.innerHTML = originalText;
            pasteButton.classList.remove('bg-red-100', 'text-red-700');
          }, 3000);
        }
      }

      // 顯示貼上成功的視覺反饋
      function showPasteSuccess() {
        const pasteButton = document.getElementById('pasteButton');
        const originalText = pasteButton.innerHTML;

        pasteButton.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
                已貼上
            `;
        pasteButton.classList.add('bg-green-100', 'text-green-700');

        setTimeout(() => {
          pasteButton.innerHTML = originalText;
          pasteButton.classList.remove('bg-green-100', 'text-green-700');
        }, 2000);
      }

      // 監聽輸入框的點擊事件
      document.getElementById('original_url').addEventListener('click', function() {
        // 在移動設備上，點擊輸入框時自動觸發貼上功能
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
          pasteUrl();
        }
      });

    </script>
@endsection
