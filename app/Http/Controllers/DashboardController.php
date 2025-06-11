<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class DashboardController extends Controller
{
    public function index()
    {
        // Get products near expiry
        $nearExpiryProducts = Product::whereDate('expiry_date', '<=', Carbon::now()->addDays(20))
            ->orderBy('expiry_date')
            ->get();

        // Create notifications for near expiry products if not already created
        foreach ($nearExpiryProducts as $product) {
            $existingNotification = Notification::where('product_id', $product->id)
                ->where('message', 'like', '%expiring soon%')
                ->first();

            if (!$existingNotification) {
                Notification::create([
                    'product_id' => $product->id,
                    'message' => "Product '{$product->name}' is expiring soon (in {$product->getDaysUntilExpiry()} days).",
                    'read' => false,
                ]);

                // Send email notification via Formspree for critical expiry (less than 7 days)
                if ($product->getDaysUntilExpiry() <= 7) {
                    $this->sendExpiryAlertViaFormspree($product);
                }
            }
        }

        // Get unread notifications
        $notifications = Notification::with('product')
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get counts for dashboard
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $lowStockProducts = Product::where('stock', '<', 10)->count();
        $recentStockMovements = StockMovement::with('product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'notifications',
            'totalProducts',
            'totalCategories',
            'lowStockProducts',
            'recentStockMovements',
            'nearExpiryProducts'
        ));
    }

    public function markNotificationAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read = true;
        $notification->save();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function exportProducts()
    {
        return Excel::download(new ProductsExport, 'products-' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }

    public function exportLowStock()
    {
        return Excel::download(new ProductsExport('low_stock'), 'low-stock-products-' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }

    private function sendExpiryAlertViaFormspree($product)
    {
        try {
            \Log::info("Attempting to send expiry alert for product: {$product->name}");

            $formData = [
                'email' => 'admin@itseey-store.com', // Your actual email
                '_subject' => "🚨 Peringatan Produk Kedaluwarsa - {$product->name}",
                'nama_produk' => $product->name,
                'stok_saat_ini' => $product->stock,
                'tanggal_kedaluwarsa' => $product->expiry_date->format('Y-m-d'),
                'hari_sebelum_kedaluwarsa' => $product->getDaysUntilExpiry(),
                '_template' => 'table',
                '_replyto' => 'noreply@itseey-store.com',
                'message' => "🚨 PERINGATAN PRODUK KEDALUWARSA\n\nProduk dalam inventori Anda akan segera kedaluwarsa!\n\n📋 DETAIL PRODUK:\n- Nama Produk: {$product->name}\n- Jumlah Stok: {$product->stock} unit\n- Tanggal Kedaluwarsa: {$product->expiry_date->format('d F Y')}\n- Hari Sebelum Kedaluwarsa: {$product->getDaysUntilExpiry()} hari\n\n⚠️ TINDAKAN YANG DIPERLUKAN:\n1. Tinjau produk yang akan kedaluwarsa\n2. Pertimbangkan penerapan harga diskon\n3. Promosikan produk untuk penjualan cepat\n4. Sesuaikan jadwal pengadaan inventori\n\nEmail ini dibuat secara otomatis oleh Sistem Manajemen Toko Itseey.\n\nHormat kami,\nSistem Manajemen Toko Itseey"
            ];

            \Log::info("Form data prepared:", $formData);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post('https://formspree.io/f/xpwrqpvj', $formData);

            \Log::info("Formspree response status: " . $response->status());
            \Log::info("Formspree response body: " . $response->body());

            if ($response->successful()) {
                \Log::info("✅ Expiry alert sent successfully via Formspree for product: {$product->name}");

                // Try alternative method as backup
                $this->sendExpiryAlertFallback($product);

                return true;
            } else {
                \Log::error("❌ Failed to send expiry alert via Formspree. Status: " . $response->status());
                \Log::error("Response: " . $response->body());

                // Try fallback method
                return $this->sendExpiryAlertFallback($product);
            }
        } catch (\Exception $e) {
            \Log::error('💥 Exception in sendExpiryAlertViaFormspree: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Try fallback method
            return $this->sendExpiryAlertFallback($product);
        }
    }

    private function sendExpiryAlertFallback($product)
    {
        try {
            \Log::info("Trying fallback email method for product: {$product->name}");

            // Method 1: Try with form-encoded data instead of JSON
            $response = Http::asForm()->post('https://formspree.io/f/xpwrqpvj', [
                'email' => 'admin@itseey-store.com',
                '_subject' => "🚨 Peringatan Produk Kedaluwarsa - {$product->name}",
                'message' => "PERINGATAN PRODUK KEDALUWARSA\n\nProduk: {$product->name}\nStok: {$product->stock}\nKedaluwarsa: {$product->expiry_date->format('d F Y')}\nSisa hari: {$product->getDaysUntilExpiry()} hari"
            ]);

            if ($response->successful()) {
                \Log::info("✅ Fallback method successful for product: {$product->name}");
                return true;
            }

            // Method 2: Try direct curl if HTTP client fails
            return $this->sendExpiryAlertCurl($product);

        } catch (\Exception $e) {
            \Log::error('💥 Fallback method failed: ' . $e->getMessage());
            return false;
        }
    }

    private function sendExpiryAlertCurl($product)
    {
        try {
            \Log::info("Trying CURL method for product: {$product->name}");

            $postData = http_build_query([
                'email' => 'admin@itseey-store.com',
                '_subject' => "🚨 Peringatan Produk Kedaluwarsa - {$product->name}",
                'message' => "PERINGATAN: Produk {$product->name} akan kedaluwarsa dalam {$product->getDaysUntilExpiry()} hari (Tanggal: {$product->expiry_date->format('d F Y')}). Stok saat ini: {$product->stock} unit."
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://formspree.io/f/xpwrqpvj');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            \Log::info("CURL response code: " . $httpCode);
            \Log::info("CURL response: " . $response);

            if ($error) {
                \Log::error("CURL error: " . $error);
                return false;
            }

            if ($httpCode >= 200 && $httpCode < 300) {
                \Log::info("✅ CURL method successful for product: {$product->name}");
                return true;
            }

            return false;

        } catch (\Exception $e) {
            \Log::error('💥 CURL method failed: ' . $e->getMessage());
            return false;
        }
    }

    public function testFormspree()
    {
        try {
            \Log::info("Testing Formspree connection...");

            $response = Http::asForm()->post('https://formspree.io/f/xpwrqpvj', [
                'email' => 'admin@itseey-store.com',
                '_subject' => 'Test Email dari Itseey Store',
                'message' => 'Ini adalah test email untuk memastikan Formspree berfungsi dengan baik.'
            ]);

            \Log::info("Test response status: " . $response->status());
            \Log::info("Test response body: " . $response->body());

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Test email sent successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Test email failed', 'details' => $response->body()]);
            }

        } catch (\Exception $e) {
            \Log::error('Test failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Test failed: ' . $e->getMessage()]);
        }
    }

    // ...existing code...

    public function showExpiryNotification($productId)
    {
        $product = Product::findOrFail($productId);
        $daysUntilExpiry = $product->getDaysUntilExpiry();

        return view('emails.expiry-notification-template', compact('product', 'daysUntilExpiry'));
    }

    public function expiryAlert()
    {
        // Get expired products
        $expiredProducts = Product::with('category')
            ->whereDate('expiry_date', '<', now())
            ->orderBy('expiry_date')
            ->get();

        // Get products expiring soon (within 20 days)
        $expiringProducts = Product::with('category')
            ->whereDate('expiry_date', '>=', now())
            ->whereDate('expiry_date', '<=', now()->addDays(20))
            ->orderBy('expiry_date')
            ->get();

        // Get total products count
        $totalProducts = Product::count();

        return view('emails.expiry-alert', compact(
            'expiredProducts',
            'expiringProducts',
            'totalProducts'
        ));
    }
}
