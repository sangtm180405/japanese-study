<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\N5CourseData;

class CourseController extends Controller
{
    /**
     * Hiển thị trang tổng hợp các khóa học N5-N1
     */
    public function index()
    {
        return view('course.index');
    }

    /**
     * Hiển thị thông tin khóa học theo level JLPT
     */
    public function show($level)
    {
        // Validate level
        $validLevels = ['N5', 'N4', 'N3', 'N2', 'N1'];
        if (!in_array(strtoupper($level), $validLevels)) {
            abort(404, 'Khóa học không tồn tại');
        }

        $level = strtoupper($level);

        // Dữ liệu cứng cho từng level (metadata)
        $courseData = $this->getCourseData($level);

        if (!$courseData) {
            abort(404, 'Khóa học không tồn tại');
        }

        // Lấy dữ liệu từ database nếu là N5
        if ($level === 'N5') {
            $courseData['sections'] = $this->getN5Sections();
        }

        return view('course.show', compact('courseData', 'level'));
    }

    /**
     * Hiển thị chi tiết một section của khóa học
     */
    public function showSection($level, $sectionType)
    {
        $level = strtoupper($level);
        
        if ($level !== 'N5') {
            abort(404, 'Khóa học chưa có dữ liệu');
        }

        // Validate section type
        $validSectionTypes = ['speed_master_n5', 'luyen_doc', 'marugoto_n5'];
        if (!in_array($sectionType, $validSectionTypes)) {
            abort(404, 'Phần học không tồn tại');
        }

        // Nếu là luyện đọc, hiển thị danh sách bài
        if ($sectionType === 'luyen_doc') {
            return $this->showLuyenDocList($level);
        }

        // Nếu là marugoto_n5, hiển thị danh sách bài
        if ($sectionType === 'marugoto_n5') {
            return $this->showMarugotoList($level);
        }

        // Nếu là speed_master_n5, hiển thị danh sách bài
        if ($sectionType === 'speed_master_n5') {
            return $this->showSpeedMasterList($level);
        }

        // Lấy dữ liệu từ database
        $data = N5CourseData::where('section_type', $sectionType)
            ->orderBy('order')
            ->get();

        if ($data->isEmpty()) {
            abort(404, 'Chưa có dữ liệu cho phần học này');
        }

        // Lấy metadata cho level
        $courseData = $this->getCourseData($level);
        
        // Nhóm dữ liệu theo section_key nếu có
        $groupedData = $data->groupBy('section_key');

        return view('course.section', compact('data', 'groupedData', 'sectionType', 'level', 'courseData'));
    }

    /**
     * Hiển thị danh sách bài luyện đọc
     */
    public function showLuyenDocList($level)
    {
        // Lấy danh sách bài luyện đọc từ database
        $lessons = N5CourseData::where('section_type', 'luyen_doc')
            ->select('id', 'bai', 'title', 'order')
            ->orderBy('order')
            ->get();

        if ($lessons->isEmpty()) {
            abort(404, 'Chưa có dữ liệu luyện đọc');
        }

        // Lấy metadata cho level
        $courseData = $this->getCourseData($level);

        return view('course.sections.luyen_doc_list', compact('lessons', 'level', 'courseData'));
    }

    /**
     * Hiển thị chi tiết một bài luyện đọc
     */
    public function showLuyenDocDetail($level, $id)
    {
        $level = strtoupper($level);
        
        // Lấy chi tiết bài luyện đọc
        $item = N5CourseData::where('section_type', 'luyen_doc')
            ->where('id', $id)
            ->firstOrFail();

        // Lấy metadata cho level
        $courseData = $this->getCourseData($level);

        return view('course.sections.luyen_doc_detail', compact('item', 'level', 'courseData'));
    }

    /**
     * Hiển thị danh sách bài Marugoto N5
     */
    public function showMarugotoList($level)
    {
        // Lấy danh sách bài Marugoto N5 từ database
        $lessons = N5CourseData::where('section_type', 'marugoto_n5')
            ->select('id', 'bai', 'title', 'order')
            ->orderBy('order')
            ->get();

        if ($lessons->isEmpty()) {
            abort(404, 'Chưa có dữ liệu Marugoto N5');
        }

        // Lấy metadata cho level
        $courseData = $this->getCourseData($level);

        return view('course.sections.marugoto_n5_list', compact('lessons', 'level', 'courseData'));
    }

    /**
     * Hiển thị chi tiết một bài Marugoto N5
     */
    public function showMarugotoDetail($level, $id)
    {
        $level = strtoupper($level);
        
        // Lấy chi tiết bài Marugoto N5
        $item = N5CourseData::where('section_type', 'marugoto_n5')
            ->where('id', $id)
            ->firstOrFail();

        // Lấy metadata cho level
        $courseData = $this->getCourseData($level);

        return view('course.sections.marugoto_n5_detail', compact('item', 'level', 'courseData'));
    }

    /**
     * Hiển thị danh sách bài Speed Master N5
     */
    public function showSpeedMasterList($level)
    {
        // Lấy tất cả các bài unique từ Speed Master N5
        // Lấy từ tuVung để có title đầy đủ
        $allLessons = N5CourseData::where('section_type', 'speed_master_n5')
            ->where('section_key', 'tuVung')
            ->select('bai', 'title', 'order')
            ->orderBy('order')
            ->get();

        if ($allLessons->isEmpty()) {
            abort(404, 'Chưa có dữ liệu Speed Master N5');
        }

        $lessons = $allLessons->map(function($lesson) {
            return [
                'bai' => $lesson->bai,
                'title' => $lesson->title,
            ];
        })->toArray();

        // Lấy metadata cho level
        $courseData = $this->getCourseData($level);

        return view('course.sections.speed_master_n5_list', compact('lessons', 'level', 'courseData'));
    }

    /**
     * Hiển thị chi tiết một bài Speed Master N5
     */
    public function showSpeedMasterDetail($level, $bai)
    {
        $level = strtoupper($level);
        
        // Lấy tất cả các phần của bài (tuVung, nguPhap, docHieu, ngheHieu)
        $allData = N5CourseData::where('section_type', 'speed_master_n5')
            ->where('bai', $bai)
            ->orderBy('order')
            ->get();

        if ($allData->isEmpty()) {
            abort(404, 'Không tìm thấy bài học');
        }

        // Nhóm dữ liệu theo section_key
        $groupedData = $allData->groupBy('section_key');
        
        // Lấy title từ bài đầu tiên
        $title = $allData->first()->title ?? '';

        // Lấy metadata cho level
        $courseData = $this->getCourseData($level);

        return view('course.sections.speed_master_n5_detail', compact('groupedData', 'bai', 'title', 'level', 'courseData'));
    }

    /**
     * Lấy danh sách sections cho N5 từ database
     */
    private function getN5Sections()
    {
        $sections = [];
        
        // Speed Master N5
        $speedMasterCount = N5CourseData::where('section_type', 'speed_master_n5')->count();
        if ($speedMasterCount > 0) {
            $sections[] = [
                'title' => 'Speed Master N5',
                'description' => 'Giáo trình Speed Master N5 - Học nhanh và hiệu quả',
                'icon' => '⚡',
                'type' => 'speed_master_n5'
            ];
        }

        // Luyện đọc
        $luyenDocCount = N5CourseData::where('section_type', 'luyen_doc')->count();
        if ($luyenDocCount > 0) {
            $sections[] = [
                'title' => 'Luyện đọc',
                'description' => 'Rèn luyện kỹ năng đọc hiểu qua các bài đọc đa dạng',
                'icon' => '📖',
                'type' => 'luyen_doc'
            ];
        }

        // Marugoto N5
        $marugotoCount = N5CourseData::where('section_type', 'marugoto_n5')->count();
        if ($marugotoCount > 0) {
            $sections[] = [
                'title' => 'Marugoto N5',
                'description' => 'Giáo trình Marugoto N5 - Học tiếng Nhật giao tiếp thực tế',
                'icon' => '🇯🇵',
                'type' => 'marugoto_n5'
            ];
        }

        // Korede Daijoubu (bỏ qua - nghe rồi trả lời)
        $sections[] = [
            'title' => 'Korede Daijoubu N4 & N5',
            'description' => 'Sách luyện thi Korede Daijoubu - Chuẩn bị cho kỳ thi JLPT',
            'icon' => '📚',
            'type' => null,
            'disabled' => true
        ];

        // Gokaku Dekiru (chưa có dữ liệu)
        $sections[] = [
            'title' => 'Gokaku Dekiru N4 & N5',
            'description' => 'Sách luyện thi Gokaku Dekiru - Luyện đề thi thử mới nhất',
            'icon' => '✅',
            'type' => null,
            'disabled' => true
        ];

        // Tanki Master N5 (chưa có dữ liệu)
        $sections[] = [
            'title' => 'Tanki Master N5',
            'description' => 'Sách luyện thi Tanki Master N5 - Tổng hợp kiến thức và đề thi',
            'icon' => '🎯',
            'type' => null,
            'disabled' => true
        ];

        return $sections;
    }

    /**
     * Lấy dữ liệu metadata khóa học theo level
     */
    private function getCourseData($level)
    {
        $courses = [
            'N5' => [
                'title' => 'N5 - Sơ cấp (Beginner)',
                'subtitle' => 'Khóa học dành cho người mới bắt đầu',
                'icon' => '🌱',
                'color' => 'red',
                'bgColor' => 'bg-red-50',
                'borderColor' => 'border-red-200',
                'buttonColor' => 'bg-red-600 hover:bg-red-700',
                'textColor' => 'text-red-600',
                'description' => 'Khóa học N5 phù hợp cho người mới bắt đầu học tiếng Nhật. Bạn sẽ được học từ những kiến thức cơ bản nhất như bảng chữ cái, cách đọc, cách viết, và những câu giao tiếp đơn giản trong cuộc sống hàng ngày.',
            ]
        ];

        return $courses[$level] ?? null;
    }
}

