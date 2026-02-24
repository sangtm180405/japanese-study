<?php

namespace App\Services;

use App\Models\MinnaLesson;
use App\Models\User;
use App\Models\UserProgress;
use Carbon\Carbon;

class UserProgressService
{
    /**
     * Cập nhật tiến độ khi user mở một bài Minna.
     */
    public function touchMinnaLesson(User $user, MinnaLesson $lesson): UserProgress
    {
        // Sử dụng khóa duy nhất theo schema mới: user_id + lesson_type + lesson_id
        $progress = UserProgress::firstOrNew([
            'user_id' => $user->id,
            'lesson_type' => UserProgress::TYPE_MINNA,
            'lesson_id' => $lesson->id,
        ]);

        $progress->last_accessed_at = Carbon::now();

        if ($progress->status !== UserProgress::STATUS_COMPLETED) {
            $progress->status = UserProgress::STATUS_IN_PROGRESS;
        }

        $progress->save();

        return $progress;
    }

    /**
     * Đánh dấu một bài Minna là đã hoàn thành.
     */
    public function markMinnaLessonCompleted(User $user, MinnaLesson $lesson): UserProgress
    {
        $progress = UserProgress::firstOrNew([
            'user_id' => $user->id,
            'lesson_type' => UserProgress::TYPE_MINNA,
            'lesson_id' => $lesson->id,
        ]);

        $progress->status = UserProgress::STATUS_COMPLETED;
        $progress->last_accessed_at = Carbon::now();
        $progress->completed_at = Carbon::now();

        $progress->save();

        return $progress;
    }
}

