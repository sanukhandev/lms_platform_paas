<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSession;

class InstructorRepository
{
    public function all()
    {
        return User::where('role', 'instructor')->get();
    }

    public function find($id)
    {
        return User::where('role', 'instructor')->findOrFail($id);
    }

    public function create(array $data)
    {
        $data['role'] = 'instructor';
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        return $user->update($data);
    }

    public function delete(User $user)
    {
        return $user->delete();
    }

    public function getOverview()
    {

        return [
            'total_assigned_courses' => $this->getTotalAssignedCourses(),
            'total_active_batches' => $this->getTotalActiveBatches(),
            'total_enrolled_students' => $this->getTotalEnrolledStudents(),
            'upcoming_classes' => $this->getUpcomingClasses(),
            'coursesGrowth' => $this->getCoursesGrowth(),
            'batchesGrowth' => $this->getBatchesGrowth(),
            'studentsGrowth' => $this->getStudentsGrowth(),
            'classesGrowth' => $this->getClassesGrowth(),
        ];
    }

    public function getTotalAssignedCourses()
    {
        return Course::where('instructor_id', Auth::id())->count();
    }
    public function getTotalActiveBatches()
    {
        return Course::where('instructor_id', Auth::id())->withCount('batches')->get()->sum('batches_count');
    }
    public function getTotalEnrolledStudents()
    {
        return Course::where('instructor_id', Auth::id())
            ->with('batches.students')
            ->get()
            ->flatMap(function ($course) {
                return $course->batches->flatMap->students;
            })
            ->unique('id') // In case students are enrolled in multiple batches
            ->count();
    }

    public function getUpcomingClasses()
    {
        return Course::where('instructor_id', Auth::id())
            ->with(['batches.classSessions' => function ($query) {
                $query->where('start_time', '>', now());
            }])
            ->get()
            ->flatMap(function ($course) {
                return $course->batches->flatMap->classSessions;
            })
            ->unique('id') // In case classes are duplicated
            ->count();
    }
    public function getCoursesGrowth()
    {
        return 0;
    }
    public function getBatchesGrowth()
    {
        return 0;
    }
    public function getStudentsGrowth()
    {
        return 0;
    }
    public function getClassesGrowth()
    {
        return 0;
    }

    public function getClasses($filter = null)
    {
        $query = ClassSession::with([
            'batch.course.instructor' => function ($q) {
                $q->select('id', 'name', 'email');
            },
            'batch' => function ($q) {
                $q->select('id', 'name', 'session_start_time', 'session_end_time', 'course_id');
            },
        ])
            ->whereHas('batch.course', function ($q) {
                $q->where('instructor_id', Auth::id());
            });

        // Apply filter to class session's start_time
        if ($filter === 'today') {
            $query->whereDate('start_time', now());
        } elseif ($filter === 'upcoming') {
            $query->where('start_time', '>', now());
        } elseif ($filter === 'past') {
            $query->where('start_time', '<', now());
        } elseif ($filter !== 'all' && $filter !== null) {
            $query->where(function ($q) {
                $q->whereDate('start_time', now())
                    ->orWhere('start_time', '>', now());
            });
        }

        $sessions = $query->get();

        // Transform data to match the expected ClassSession format
        $formatted = $sessions->map(function ($session) {
            $course = $session->batch->course;
            $instructor = $course->instructor;
            return [
                'id' => $session->id,
                'date' => $session->date,
                'class_status' => $session->class_status,
                'start_time' => $session->start_time,
                'end_time' => $session->end_time,
                'meeting_link' => $session->meeting_link,
                'batch' => [
                    'id' => $session->batch->id,
                    'name' => $session->batch->name,
                    'session_start_time' => $session->batch->session_start_time,
                    'session_end_time' => $session->batch->session_end_time,
                    'course' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'duration_weeks' => $course->duration_weeks,
                        'instructor' => [
                            'id' => $instructor->id,
                            'name' => $instructor->name,
                            'email' => $instructor->email,
                        ]
                    ]
                ]
            ];
        });

        return $formatted;
    }
}
