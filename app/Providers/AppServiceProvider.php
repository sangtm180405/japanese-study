<?php

namespace App\Providers;

use App\Models\Alphabet;
use App\Models\Kanji;
use App\Models\MinnaLesson;
use App\Models\MinnaSection;
use App\Models\N5CourseData;
use App\Observers\AlphabetObserver;
use App\Observers\KanjiObserver;
use App\Observers\MinnaLessonObserver;
use App\Observers\MinnaSectionObserver;
use App\Observers\N5CourseDataObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Kanji::observe(KanjiObserver::class);
        Alphabet::observe(AlphabetObserver::class);
        N5CourseData::observe(N5CourseDataObserver::class);
        MinnaLesson::observe(MinnaLessonObserver::class);
        MinnaSection::observe(MinnaSectionObserver::class);
    }
}
