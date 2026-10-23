<?php

use App\Models\Translations\TourTranslation;
use App\Models\Translations\TourDayTranslation;
use App\Models\Translations\TourOptionTranslation;
use App\Models\Translations\SeoTranslation;
use App\Models\Translations\FaqTranslation;
use App\Models\Translations\PageTranslation;
use App\Models\Translations\DestinationTranslation;
use App\Models\Translations\BlogCategoryTranslation;
use App\Models\Translations\BlogTranslation;
use App\Models\Translations\CustomizedTripCategoryTranslation;
use App\Models\Translations\CategoryTranslation;
use App\Models\Translations\LocationTranslation;
use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;

return new class extends OneTimeOperation
{
    /**
     * Determine if the operation is being processed asyncronously.
     */
    protected bool $async = false;

    /**
     * The queue that the job will be dispatched to.
     */
    protected string $queue = 'default';

    /**
     * A tag name, that this operation can be filtered by.
     */
    protected ?string $tag = null;

    /**
     * Process the operation.
     */
    public function process(): void
    {
        TourTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(TourTranslation $translation) => $translation->update(['title' => htmlspecialchars_decode($translation->title)]));
        });

        DestinationTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(DestinationTranslation $translation) => $translation->update(['title' => htmlspecialchars_decode($translation->title)]));
        });


        CustomizedTripCategoryTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(CustomizedTripCategoryTranslation $translation) => $translation->update(['title' => htmlspecialchars_decode($translation->title)]));
        });

        CategoryTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(CategoryTranslation $translation) => $translation->update(['title' => htmlspecialchars_decode($translation->title)]));
        });

        BlogTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(BlogTranslation $translation) => $translation->update(['title' => htmlspecialchars_decode($translation->title)]));
        });

        BlogCategoryTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(BlogCategoryTranslation $translation) => $translation->update(['title' => htmlspecialchars_decode($translation->title)]));
        });

        TourDayTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(TourDayTranslation $translation) => $translation->update(['title' => htmlspecialchars_decode($translation->title)]));
        });

        PageTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(PageTranslation $translation) => $translation->update(['title' => htmlspecialchars_decode($translation->title)]));
        });

        LocationTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(LocationTranslation $translation) => $translation->update(['name' => htmlspecialchars_decode($translation->name)]));
        });

        FaqTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(FaqTranslation $translation) => $translation->update(['question' => htmlspecialchars_decode($translation->question)]));
        });

        SeoTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(SeoTranslation $translation) => $translation->update([
                'meta_title' => htmlspecialchars_decode($translation->meta_title),
                'og_title' => htmlspecialchars_decode($translation->og_title),
                'twitter_title' => htmlspecialchars_decode($translation->twitter_title),
            ]));
        });

        TourOptionTranslation::where('locale', '!=', 'en')->chunkById(10, function ($items) {
            $items->each(fn(TourOptionTranslation $translation) => $translation->update(['name' => htmlspecialchars_decode($translation->name)]));
        });
    }
};
