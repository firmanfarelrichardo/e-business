<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductBrand;
use App\Models\Service;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'type' => 'required|in:product,service',
            'id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        $type = $request->type;
        $id = $request->id;
        $qty = $request->quantity;

        // Bikin unique key utk item
        $cartKey = $type . '_' . $id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $qty;
        } else {
            if ($type === 'product') {
                $item = ProductBrand::with(['product', 'brand'])->find($id);
                if (!$item)
                    return back()->with('error', 'Produk tidak ditemukan');

                $cart[$cartKey] = [
                    'type' => 'product',
                    'id' => $id,
                    'name' => ($item->product->name ?? 'Unknown') . ' (' . ($item->brand->name ?? 'Unknown') . ')',
                    'price' => $item->selling_price,
                    'quantity' => $qty,
                    'image' => (isset($item->product->attachments) && count($item->product->attachments) > 0) ? asset('storage/' . $item->product->attachments[0]) : null,
                ];
            } else {
                $item = Service::find($id);
                if (!$item)
                    return back()->with('error', 'Jasa tidak ditemukan');

                $cart[$cartKey] = [
                    'type' => 'service',
                    'id' => $id,
                    'name' => '[JASA] ' . $item->name,
                    'price' => $item->piece_price,
                    'quantity' => $qty,
                    'image' => (isset($item->attachments) && count($item->attachments) > 0) ? asset('storage/' . $item->attachments[0]) : null,
                ];
            }
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $cartKey = $request->cart_key;

        if (isset($cart[$cartKey])) {
            if ($request->action == 'increase') {
                $cart[$cartKey]['quantity']++;
            } elseif ($request->action == 'decrease' && $cart[$cartKey]['quantity'] > 1) {
                $cart[$cartKey]['quantity']--;
            }
            session()->put('cart', $cart);
        }

        return back();
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $cartKey = $request->cart_key;

        if (isset($cart[$cartKey])) {
            // Delete file if exists
            if (isset($cart[$cartKey]['document_path']) && \Illuminate\Support\Facades\Storage::exists($cart[$cartKey]['document_path'])) {
                \Illuminate\Support\Facades\Storage::delete($cart[$cartKey]['document_path']);
            }
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item dihapus dari keranjang');
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'cart_key' => 'required',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240' // max 10MB
        ]);

        $cart = session()->get('cart', []);
        $cartKey = $request->cart_key;

        if (!isset($cart[$cartKey])) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
        }

        // Delete old file if exists
        if (isset($cart[$cartKey]['document_path']) && \Illuminate\Support\Facades\Storage::exists($cart[$cartKey]['document_path'])) {
            \Illuminate\Support\Facades\Storage::delete($cart[$cartKey]['document_path']);
        }

        $file = $request->file('document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents/cart', $filename, 'public');

        $cart[$cartKey]['document_path'] = $path;
        $cart[$cartKey]['document_filename'] = $file->getClientOriginalName();
        session()->put('cart', $cart);

        return response()->json([
            'success' => true, 
            'path' => asset('storage/' . $path),
            'filename' => $file->getClientOriginalName()
        ]);
    }
}
