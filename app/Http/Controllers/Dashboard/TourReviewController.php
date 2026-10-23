<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\TourReview;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\TourReviewRequest;
use App\DataTables\TourReviewDataTable;

class TourReviewController extends Controller
{

    public function index(TourReviewDataTable $dataTable)
    {
        return $dataTable->render('dashboard.tour-reviews.index');
    }
}
