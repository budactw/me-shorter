<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Util\ShortUrlMaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ShortUrlController extends Controller
{

    public function create()
    {
        return view('create');
    }

    public function redirect(ShortUrl $shortUrl)
    {
        if ($shortUrl->expired_at && $shortUrl->expired_at < Carbon::now()) {
            $shortUrl->delete();

            abort(404);
        }

        $shortUrl->recordClick();

        return redirect($shortUrl->original_url, Response::HTTP_MOVED_PERMANENTLY);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'original_url' => ['required', 'url', 'max:2048'],
            'expired_at'   => ['nullable', 'date', 'after:now'],
        ]);

        // 檢查是否為本站網址
        if ($this->isSelfUrl($validated['original_url'])) {
            return response()->json([
                'success' => false,
                'message' => '不能縮短本站網址',
            ], 422);
        }

        try {
            $shortUrl = DB::transaction(function () use ($validated) {
                do {
                    $shortCode = ShortUrlMaker::generate();
                } while (ShortUrl::whereRaw('BINARY short_code = ?', [$shortCode])->exists());

                return ShortUrl::create([
                    'original_url' => $validated['original_url'],
                    'short_code'   => $shortCode,
                    'delete_code'  => ShortUrlMaker::generate(20),
                    'expired_at'   => $validated['expired_at'] ?? null,
                    'clicks'       => 0,
                ]);
            });

            return response()->json([
                'success'      => true,
                'shortUrl'     => $shortUrl,
                'fullShortUrl' => url($shortUrl->short_code),
                'deleteUrl'    => url('delete', $shortUrl->delete_code),
                'message'      => '短網址已成功生成',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '生成短網址失敗，請重試',
            ], 422);
        }
    }

    /**
     * 刪除短網址
     */
    public function delete($deleteCode)
    {
        $shortUrl = ShortUrl::where('delete_code', $deleteCode)->first();

        if (!$shortUrl) {
            return response()->json([
                'success' => false,
                'message' => '無效的刪除碼或短網址已被刪除'
            ], 404);
        }

        try {
            $shortUrl->delete();

            return response()->json([
                'success' => true,
                'message' => '短網址已成功刪除'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '刪除失敗，請稍後再試'
            ], 500);
        }
    }

    /**
     * 顯示刪除確認頁面
     */
    public function showDelete($deleteCode)
    {
        $shortUrl = ShortUrl::where('delete_code', $deleteCode)->firstOrFail();

        return view('delete', [
            'shortUrl' => $shortUrl,
            'deleteCode' => $deleteCode
        ]);
    }

    /**
     * 檢查 URL 是否為本站網址
     */
    private function isSelfUrl($url)
    {
        $appUrl = config('app.url');
        // 移除 protocol (http:// 或 https://) 再比較
        $normalizedAppUrl = preg_replace('#^https?://#', '', $appUrl);
        $normalizedUrl = preg_replace('#^https?://#', '', $url);

        return Str::startsWith($normalizedUrl, $normalizedAppUrl);
    }

}
