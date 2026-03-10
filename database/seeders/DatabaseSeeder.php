<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Certificate;
use App\Models\Transaction;
use App\Models\Attendance;
use App\Models\Resource;
use App\Models\News;
use App\Models\ContactMessage;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === USERS ===
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@insep.net',
            'password' => Hash::make('654321@'),
            'role' => 'admin',
            'phone' => '+20 10 33330027',
            'status' => 'active',
        ]);

        $instructor = User::create([
            'name' => 'د. سارة الأحمد',
            'email' => 'instructor@insep.net',
            'password' => Hash::make('instructor123'),
            'role' => 'instructor',
            'phone' => '+966501112222',
            'status' => 'active',
            'specialty' => 'التدريب الرياضي',
            'rating' => 4.9,
        ]);

        $instructor2 = User::create([
            'name' => 'د. عمر الخالد',
            'email' => 'omar@insep.net',
            'password' => Hash::make('instructor123'),
            'role' => 'instructor',
            'phone' => '+966503334444',
            'status' => 'active',
            'specialty' => 'التغذية الرياضية',
            'rating' => 4.7,
        ]);

        $student = User::create([
            'name' => 'خالد العلي',
            'email' => 'student@insep.net',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'phone' => '+966501234567',
            'status' => 'active',
        ]);

        $student2 = User::create([
            'name' => 'محمد الأحمد',
            'email' => 'mohammed@insep.net',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'phone' => '+966509876543',
            'status' => 'active',
        ]);

        $student3 = User::create([
            'name' => 'فاطمة السعيد',
            'email' => 'fatima@insep.net',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'phone' => '+966507654321',
            'status' => 'active',
        ]);

        $student4 = User::create([
            'name' => 'أحمد المنصور',
            'email' => 'ahmed.m@insep.net',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'phone' => '+966502345678',
            'status' => 'active',
        ]);

        $student5 = User::create([
            'name' => 'ليلى العمر',
            'email' => 'layla@insep.net',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'phone' => '+966506789012',
            'status' => 'inactive',
        ]);

        // === COURSES ===
        $courses = [];
        $coursesData = [
            ['title' => 'دبلوم التدريب الرياضي المتقدم', 'description' => 'برنامج شامل يغطي جميع جوانب التدريب الرياضي من التخطيط إلى التنفيذ والتقييم', 'category' => 'التدريب الرياضي', 'price' => 2500, 'duration' => '120 ساعة', 'level' => 'متقدم', 'image' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&q=80', 'status' => 'active', 'rating' => 4.9, 'student_count' => 450],
            ['title' => 'أساسيات العلاج الطبيعي الرياضي', 'description' => 'تعرف على أساسيات العلاج الطبيعي وتطبيقاته في المجال الرياضي', 'category' => 'العلاج الطبيعي', 'price' => 1800, 'duration' => '80 ساعة', 'level' => 'مبتدئ', 'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&q=80', 'status' => 'active', 'rating' => 4.8, 'student_count' => 320],
            ['title' => 'التغذية الرياضية للمحترفين', 'description' => 'تعلم كيفية تصميم برامج غذائية متكاملة للرياضيين المحترفين', 'category' => 'التغذية الرياضية', 'price' => 1500, 'duration' => '60 ساعة', 'level' => 'متوسط', 'image' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&q=80', 'status' => 'active', 'rating' => 4.7, 'student_count' => 280],
            ['title' => 'إدارة الأندية والمنشآت الرياضية', 'description' => 'برنامج متخصص في إدارة المؤسسات والأندية الرياضية وفق أحدث المعايير', 'category' => 'الإدارة الرياضية', 'price' => 2000, 'duration' => '90 ساعة', 'level' => 'متقدم', 'image' => 'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?auto=format&fit=crop&q=80', 'status' => 'active', 'rating' => 4.6, 'student_count' => 200],
            ['title' => 'علم وظائف الأعضاء في الرياضة', 'description' => 'فهم الأسس الفسيولوجية للأداء الرياضي وتأثير التدريب على الجسم', 'category' => 'التدريب الرياضي', 'price' => 1200, 'duration' => '45 ساعة', 'level' => 'متوسط', 'image' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&q=80', 'status' => 'active', 'rating' => 4.8, 'student_count' => 180],
            ['title' => 'الإصابات الرياضية والتأهيل', 'description' => 'تعرف على أنواع الإصابات الرياضية وطرق الوقاية والعلاج والتأهيل', 'category' => 'العلاج الطبيعي', 'price' => 2200, 'duration' => '100 ساعة', 'level' => 'متقدم', 'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80', 'status' => 'active', 'rating' => 4.9, 'student_count' => 350],
            ['title' => 'التحليل الرياضي باستخدام التقنية', 'description' => 'استخدام التقنيات الحديثة في تحليل الأداء الرياضي واتخاذ القرارات', 'category' => 'التدريب الرياضي', 'price' => 1800, 'duration' => '70 ساعة', 'level' => 'متوسط', 'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80', 'status' => 'active', 'rating' => 4.5, 'student_count' => 150],
            ['title' => 'سيكولوجية الرياضة والتحفيز', 'description' => 'فهم الجوانب النفسية للرياضيين وتقنيات التحفيز والإعداد الذهني', 'category' => 'التدريب الرياضي', 'price' => 1000, 'duration' => '40 ساعة', 'level' => 'مبتدئ', 'image' => 'https://images.unsplash.com/photo-1544367563-12123d8965cd?auto=format&fit=crop&q=80', 'status' => 'active', 'rating' => 4.7, 'student_count' => 220],
            ['title' => 'المكملات الغذائية للرياضيين', 'description' => 'دليل شامل عن المكملات الغذائية المناسبة للرياضيين وطرق استخدامها', 'category' => 'التغذية الرياضية', 'price' => 900, 'duration' => '30 ساعة', 'level' => 'مبتدئ', 'image' => 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?auto=format&fit=crop&q=80', 'status' => 'active', 'rating' => 4.4, 'student_count' => 190],
        ];

        foreach ($coursesData as $c) {
            $courses[] = Course::create($c);
        }

        // === BATCHES ===
        $batches = [];
        $batchesData = [
            ['name' => 'الدفعة الأولى - التدريب الرياضي', 'course_id' => $courses[0]->id, 'instructor_id' => $instructor->id, 'start_date' => '2025-01-15', 'end_date' => '2025-06-15', 'status' => 'active', 'max_students' => 25],
            ['name' => 'الدفعة الثانية - التدريب الرياضي', 'course_id' => $courses[0]->id, 'instructor_id' => $instructor->id, 'start_date' => '2025-03-01', 'end_date' => '2025-08-01', 'status' => 'active', 'max_students' => 30],
            ['name' => 'الدفعة الأولى - العلاج الطبيعي', 'course_id' => $courses[1]->id, 'instructor_id' => $instructor2->id, 'start_date' => '2025-02-01', 'end_date' => '2025-06-01', 'status' => 'active', 'max_students' => 20],
            ['name' => 'الدفعة الأولى - التغذية', 'course_id' => $courses[2]->id, 'instructor_id' => $instructor2->id, 'start_date' => '2025-01-20', 'end_date' => '2025-05-20', 'status' => 'completed', 'max_students' => 25],
            ['name' => 'الدفعة الأولى - الإدارة', 'course_id' => $courses[3]->id, 'instructor_id' => $instructor->id, 'start_date' => '2025-04-01', 'end_date' => '2025-09-01', 'status' => 'upcoming', 'max_students' => 30],
        ];

        foreach ($batchesData as $b) {
            $batches[] = Batch::create($b);
        }

        // === ENROLLMENTS ===
        $enrollmentsData = [
            ['student_id' => $student->id, 'course_id' => $courses[0]->id, 'batch_id' => $batches[0]->id, 'progress' => 75, 'grade' => 'A', 'status' => 'active'],
            ['student_id' => $student->id, 'course_id' => $courses[2]->id, 'batch_id' => $batches[3]->id, 'progress' => 100, 'grade' => 'A+', 'status' => 'completed'],
            ['student_id' => $student->id, 'course_id' => $courses[1]->id, 'batch_id' => $batches[2]->id, 'progress' => 45, 'grade' => null, 'status' => 'active'],
            ['student_id' => $student2->id, 'course_id' => $courses[0]->id, 'batch_id' => $batches[0]->id, 'progress' => 60, 'grade' => 'B+', 'status' => 'active'],
            ['student_id' => $student2->id, 'course_id' => $courses[1]->id, 'batch_id' => $batches[2]->id, 'progress' => 30, 'grade' => null, 'status' => 'active'],
            ['student_id' => $student3->id, 'course_id' => $courses[0]->id, 'batch_id' => $batches[1]->id, 'progress' => 80, 'grade' => 'A', 'status' => 'active'],
            ['student_id' => $student3->id, 'course_id' => $courses[2]->id, 'batch_id' => $batches[3]->id, 'progress' => 100, 'grade' => 'B+', 'status' => 'completed'],
            ['student_id' => $student4->id, 'course_id' => $courses[1]->id, 'batch_id' => $batches[2]->id, 'progress' => 55, 'grade' => null, 'status' => 'active'],
            ['student_id' => $student4->id, 'course_id' => $courses[3]->id, 'batch_id' => $batches[4]->id, 'progress' => 0, 'grade' => null, 'status' => 'active'],
        ];

        foreach ($enrollmentsData as $e) {
            Enrollment::create($e);
        }

        // === EXAMS ===
        $exams = [];
        $examsData = [
            ['title' => 'اختبار منتصف الفصل - التدريب الرياضي', 'course_id' => $courses[0]->id, 'type' => 'midterm', 'questions' => 30, 'duration' => '60 دقيقة', 'attempts' => 2, 'status' => 'active', 'avg_score' => '78%'],
            ['title' => 'الاختبار النهائي - التدريب الرياضي', 'course_id' => $courses[0]->id, 'type' => 'final', 'questions' => 50, 'duration' => '90 دقيقة', 'attempts' => 1, 'status' => 'active', 'avg_score' => '82%'],
            ['title' => 'واجب 1 - العلاج الطبيعي', 'course_id' => $courses[1]->id, 'type' => 'assignment', 'questions' => 15, 'duration' => '45 دقيقة', 'attempts' => 3, 'status' => 'active', 'avg_score' => '85%'],
            ['title' => 'اختبار نهائي - التغذية', 'course_id' => $courses[2]->id, 'type' => 'final', 'questions' => 40, 'duration' => '75 دقيقة', 'attempts' => 1, 'status' => 'completed', 'avg_score' => '88%'],
        ];

        foreach ($examsData as $ex) {
            $exams[] = Exam::create($ex);
        }

        // === EXAM RESULTS ===
        $resultsData = [
            ['exam_id' => $exams[0]->id, 'student_id' => $student->id, 'score' => 85, 'attempt_number' => 1],
            ['exam_id' => $exams[0]->id, 'student_id' => $student2->id, 'score' => 72, 'attempt_number' => 1],
            ['exam_id' => $exams[1]->id, 'student_id' => $student->id, 'score' => 90, 'attempt_number' => 1],
            ['exam_id' => $exams[2]->id, 'student_id' => $student->id, 'score' => 88, 'attempt_number' => 1],
            ['exam_id' => $exams[3]->id, 'student_id' => $student->id, 'score' => 92, 'attempt_number' => 1],
            ['exam_id' => $exams[3]->id, 'student_id' => $student3->id, 'score' => 78, 'attempt_number' => 1],
        ];

        foreach ($resultsData as $r) {
            ExamResult::create($r);
        }

        // === CERTIFICATES ===
        Certificate::create(['serial_number' => 'INSEP-2025-1234', 'student_id' => $student->id, 'course_id' => $courses[2]->id, 'title' => 'شهادة إتمام دورة التغذية الرياضية', 'issue_date' => '2025-05-20', 'grade' => 'A+', 'status' => 'active']);
        Certificate::create(['serial_number' => 'INSEP-2025-5678', 'student_id' => $student3->id, 'course_id' => $courses[2]->id, 'title' => 'شهادة إتمام دورة التغذية الرياضية', 'issue_date' => '2025-05-20', 'grade' => 'B+', 'status' => 'active']);
        Certificate::create(['serial_number' => 'INSEP-2024-9012', 'student_id' => $student->id, 'course_id' => $courses[7]->id, 'title' => 'شهادة إتمام دورة سيكولوجية الرياضة', 'issue_date' => '2024-12-15', 'grade' => 'A', 'status' => 'active']);

        // === TRANSACTIONS ===
        $transData = [
            ['description' => 'رسوم تسجيل - دبلوم التدريب الرياضي', 'amount' => 2500, 'type' => 'revenue', 'method' => 'بطاقة ائتمان', 'user_id' => $student->id, 'status' => 'completed'],
            ['description' => 'رسوم تسجيل - العلاج الطبيعي', 'amount' => 1800, 'type' => 'revenue', 'method' => 'تحويل بنكي', 'user_id' => $student->id, 'status' => 'completed'],
            ['description' => 'رسوم تسجيل - التغذية', 'amount' => 1500, 'type' => 'revenue', 'method' => 'بطاقة ائتمان', 'user_id' => $student->id, 'status' => 'completed'],
            ['description' => 'رسوم تسجيل - التدريب الرياضي', 'amount' => 2500, 'type' => 'revenue', 'method' => 'نقدي', 'user_id' => $student2->id, 'status' => 'completed'],
            ['description' => 'رسوم تسجيل - العلاج الطبيعي', 'amount' => 1800, 'type' => 'revenue', 'method' => 'بطاقة ائتمان', 'user_id' => $student2->id, 'status' => 'pending'],
            ['description' => 'رواتب المدربين - يناير', 'amount' => 15000, 'type' => 'expense', 'method' => 'تحويل بنكي', 'user_id' => null, 'status' => 'completed'],
            ['description' => 'صيانة المعدات', 'amount' => 3000, 'type' => 'expense', 'method' => 'نقدي', 'user_id' => null, 'status' => 'completed'],
            ['description' => 'إيجار القاعات', 'amount' => 8000, 'type' => 'expense', 'method' => 'تحويل بنكي', 'user_id' => null, 'status' => 'completed'],
            ['description' => 'رسوم تسجيل - التدريب الرياضي', 'amount' => 2500, 'type' => 'revenue', 'method' => 'بطاقة ائتمان', 'user_id' => $student3->id, 'status' => 'completed'],
            ['description' => 'رسوم تسجيل - التغذية', 'amount' => 1500, 'type' => 'revenue', 'method' => 'تحويل بنكي', 'user_id' => $student3->id, 'status' => 'completed'],
        ];

        foreach ($transData as $t) {
            Transaction::create($t);
        }

        // === ATTENDANCE ===
        $dates = ['2025-01-20', '2025-01-22', '2025-01-24', '2025-01-27', '2025-01-29'];
        foreach ($dates as $date) {
            Attendance::create(['batch_id' => $batches[0]->id, 'student_id' => $student->id, 'date' => $date, 'status' => 'present']);
            Attendance::create(['batch_id' => $batches[0]->id, 'student_id' => $student2->id, 'date' => $date, 'status' => $date === '2025-01-24' ? 'absent' : 'present']);
        }
        foreach (array_slice($dates, 0, 3) as $date) {
            Attendance::create(['batch_id' => $batches[2]->id, 'student_id' => $student->id, 'date' => $date, 'status' => 'present']);
            Attendance::create(['batch_id' => $batches[2]->id, 'student_id' => $student4->id, 'date' => $date, 'status' => 'present']);
        }

        // === RESOURCES ===
        Resource::create(['title' => 'مبادئ التدريب الرياضي - PDF', 'type' => 'PDF', 'file_url' => '/files/training-principles.pdf', 'size' => '2.5 MB', 'instructor_id' => $instructor->id, 'course_id' => $courses[0]->id, 'downloads' => 125]);
        Resource::create(['title' => 'فيديو: تحليل الأداء الحركي', 'type' => 'Video', 'file_url' => '/files/movement-analysis.mp4', 'size' => '45 MB', 'instructor_id' => $instructor->id, 'course_id' => $courses[0]->id, 'downloads' => 89]);
        Resource::create(['title' => 'عرض تقديمي: التغذية الرياضية', 'type' => 'PPT', 'file_url' => '/files/nutrition.pptx', 'size' => '8 MB', 'instructor_id' => $instructor2->id, 'course_id' => $courses[2]->id, 'downloads' => 67]);
        Resource::create(['title' => 'دليل العلاج الطبيعي', 'type' => 'PDF', 'file_url' => '/files/physio-guide.pdf', 'size' => '3.2 MB', 'instructor_id' => $instructor2->id, 'course_id' => $courses[1]->id, 'downloads' => 98]);

        // === NEWS ===
        News::create(['title' => 'افتتاح فرع جديد في جدة', 'description' => 'يسر معهد INSEP الإعلان عن افتتاح فرعه الجديد في مدينة جدة لخدمة المنطقة الغربية', 'tag' => 'أخبار', 'date' => '2025-01-15']);
        News::create(['title' => 'شراكة مع الاتحاد السعودي للرياضة', 'description' => 'وقع معهد INSEP اتفاقية شراكة استراتيجية مع الاتحاد السعودي للرياضة لتطوير الكوادر الرياضية', 'tag' => 'شراكات', 'date' => '2025-01-10']);
        News::create(['title' => 'تخريج الدفعة الخامسة من دبلوم التدريب', 'description' => 'احتفل المعهد بتخريج 120 متدرباً من الدفعة الخامسة من دبلوم التدريب الرياضي المتقدم', 'tag' => 'فعاليات', 'date' => '2025-01-05']);

        // === NOTIFICATIONS ===
        Notification::create(['user_id' => $student->id, 'text' => 'تم تسجيلك بنجاح في دورة التدريب الرياضي', 'type' => 'enrollment', 'is_read' => true]);
        Notification::create(['user_id' => $student->id, 'text' => 'موعد الاختبار النهائي بعد أسبوع', 'type' => 'exam', 'is_read' => false]);
        Notification::create(['user_id' => $student->id, 'text' => 'تم إصدار شهادتك لدورة التغذية الرياضية', 'type' => 'certificate', 'is_read' => false]);
        Notification::create(['user_id' => $instructor->id, 'text' => 'تم إسناد مجموعة جديدة إليك', 'type' => 'batch', 'is_read' => true]);
        Notification::create(['user_id' => $instructor->id, 'text' => 'تذكير: موعد المحاضرة غداً', 'type' => 'reminder', 'is_read' => false]);
        Notification::create(['user_id' => $admin->id, 'text' => 'طالب جديد قام بالتسجيل', 'type' => 'registration', 'is_read' => false]);
        Notification::create(['user_id' => $admin->id, 'text' => 'تم استلام رسالة جديدة عبر نموذج التواصل', 'type' => 'contact', 'is_read' => false]);

        // === CONTACT MESSAGES ===
        ContactMessage::create(['name' => 'سعد المالكي', 'email' => 'saad@email.com', 'phone' => '+966505551111', 'subject' => 'استفسار عن الدورات', 'message' => 'أود الاستفسار عن مواعيد دورة التدريب الرياضي القادمة', 'is_read' => false]);
        ContactMessage::create(['name' => 'نوره الحربي', 'email' => 'nourah@email.com', 'phone' => '+966505552222', 'subject' => 'شراكات وتعاون', 'message' => 'نود التعاون مع المعهد لتقديم دورات لمنسوبي الشركة', 'is_read' => true]);

        $this->command->info('✅ Seed completed successfully!');
        $this->command->info('   👤 Users: 8 (1 admin, 2 instructors, 5 students)');
        $this->command->info('   📚 Courses: ' . count($courses));
        $this->command->info('   📋 Batches: ' . count($batches));
    }
}
