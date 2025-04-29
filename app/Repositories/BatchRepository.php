<?php

namespace App\Repositories;

use App\Models\Batch;
use App\Models\ClassSession;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BatchRepository
{
    // 🔹 Batch: Basic CRUD

    public function all(): Collection
    {
        return Batch::with('course', 'classSessions')->get();
    }

    public function paginated(int $perPage = 10): LengthAwarePaginator
    {
        return Batch::with('course', 'classSessions')->paginate($perPage);
    }

    public function store(array $data): Batch
    {
        return Batch::create($data);
    }

    public function update(Batch $batch, array $data): Batch
    {
        $batch->update($data);
        return $batch;
    }

    public function destroy(Batch $batch): bool
    {
        return $batch->delete();
    }

    public function find(int $id): Batch
    {
        return Batch::with('course', 'classSessions')->findOrFail($id);
    }

    public function findByCourse(int $courseId): Collection
    {
        return Batch::where('course_id', $courseId)->with('classSessions')->get();
    }

    public function searchByName(string $name): Collection
    {
        return Batch::where('name', 'LIKE', "%{$name}%")->get();
    }

    // 🔹 ClassSession: CRUD

    public function createClassSession(Batch $batch, array $data): ClassSession
    {
        return $batch->classSessions()->create($data);
    }

    public function createMultipleClassSessions(Batch $batch, array $sessions): bool
    {
        return $batch->classSessions()->createMany($sessions) ? true : false;
    }

    public function updateClassSession(ClassSession $classSession, array $data): ClassSession
    {
        $classSession->update($data);
        return $classSession;
    }

    public function destroyClassSession(ClassSession $classSession): bool
    {
        return $classSession->delete();
    }

    public function destroyClassSessionsByBatch(Batch $batch): int
    {
        return $batch->classSessions()->delete();
    }

    public function findClassSession(int $id): ClassSession
    {
        return ClassSession::findOrFail($id);
    }

    public function getClassSessionsByBatchId(int $batchId): Collection
    {
        return ClassSession::where('batch_id', $batchId)->get();
    }

    public function getClassSessionsByBatch(Batch $batch): Collection
    {
        return $batch->classSessions;
    }

    public function getClassSessionByBatchAndDate(Batch $batch, string $date): Collection
    {
        return $batch->classSessions()->whereDate('date', $date)->get();
    }

    public function getClassSessionByBatchAndDateRange(Batch $batch, string $startDate, string $endDate): Collection
    {
        return $batch->classSessions()
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
    }

    public function getClassSessionByBatchAndTime(Batch $batch, string $startTime, string $endTime): Collection
    {
        return $batch->classSessions()
            ->whereBetween('start_time', [$startTime, $endTime])
            ->get();
    }

    // 🔹 Advanced / Optional

    public function getUpcomingClassSessions(Batch $batch): Collection
    {
        return $batch->classSessions()
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->get();
    }

    public function getTodayClassSessions(Batch $batch): Collection
    {
        return $batch->classSessions()
            ->whereDate('date', now()->toDateString())
            ->get();
    }

    public function getClassSessionsGroupedByDate(Batch $batch): Collection
    {
        return $batch->classSessions()
            ->orderBy('date')
            ->get()
            ->groupBy('date');
    }
}
