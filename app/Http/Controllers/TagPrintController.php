<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderArticle;

class TagPrintController extends Controller
{
    public function printSingle(Order $order, OrderArticle $article)
    {
        abort_unless($article->order_id === $order->id, 404);

        return view('prints.tag-single', [
            'order' => $order,
            'article' => $article,
            'tagPosition' => OrderArticle::where('order_id', $order->id)
                ->where('status', '!=', OrderArticle::STATUS_CANCELLED)
                ->where('id', '<=', $article->id)
                ->count(),
            'totalTags' => OrderArticle::where('order_id', $order->id)
                ->where('status', '!=', OrderArticle::STATUS_CANCELLED)
                ->count(),
        ]);
    }

    public function printAllTags(Order $order)
    {
        $articles = OrderArticle::where('order_id', $order->id)
            ->orderBy('id')
            ->get();

        $totalTags = $articles->count();

        if ($articles->isEmpty()) {
            abort(404, 'No garments found for this order.');
        }

        if (!$order->tags_printed_at) {
            $order->update([
                'tags_printed_at' => now(),
            ]);
        }

        return view('prints.order-tags', compact('order', 'articles', 'totalTags'));
    }
}
