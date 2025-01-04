<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class CourseController extends Controller
{
    // Get all courses with pagination
    public function index(Request $request)
    {
        try {
            $page = $request->get('page', 1); // Default to page 1
            $limit = $request->get('limit', 10); // Default to 10 per page

            // Get the courses with pagination
            $courses = Course::paginate($limit);

            return response()->json([
                'success' => true,
                'message' => 'Course search results',
                'data' => $courses->items(), // Data from current page
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'total' => $courses->total(),
                'per_page' => $courses->perPage(),
                'hasMore' => $courses->hasMorePages(),
            ]);
        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error("Error fetching courses: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch courses',
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Get course by ID
    public function show($id)
    {
        try {
            $course = Course::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Course details',
                'data' => $course,
            ]);
        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error("Error fetching course by ID: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Course not found',
                'data' => [],
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    // Search courses with pagination
    public function search(Request $request)
    {
        try {
            $page = $request->get('page', 1); // Default to page 1
            $limit = $request->get('limit', 10); // Default to 10 per page
            $searchTerm = $request->get('search', ''); // Default to empty string if no search term

            // Search courses by name or code
            $courses = Course::where('course_name', 'LIKE', "%$searchTerm%")
                             ->orWhere('course_code', 'LIKE', "%$searchTerm%")
                             ->paginate($limit);

            return response()->json([
                'success' => true,
                'message' => 'Course search results',
                'data' => $courses->items(),
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'total' => $courses->total(),
                'per_page' => $courses->perPage(),
                'hasMore' => $courses->hasMorePages(),
            ]);
        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error("Error searching courses: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to search courses',
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
