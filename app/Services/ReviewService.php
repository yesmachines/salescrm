<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewService
{
    public function createFeedback(array $userData): Review
    {
        return Review::create([
            'visitorid'     => $userData['visitorid'],
            'review_type'   => $userData['review_type'],
            'comments'      => $userData['comments']
        ]);
    }

    public function getFeedback($id): Review
    {
        return Review::find($id);
    }

    public function feedbacksByCustomer($data = []): Object
    {
        $sql = Review::orderBy('id', 'desc');
        if (isset($data['customer_id']) && !empty($data['customer_id'])) {
            $sql->where('customer_id', $data['customer_id']);
        }
        $reviews = $sql->get();

        return $reviews;
    }
}
