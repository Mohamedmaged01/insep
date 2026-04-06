<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        $defaults = [
            // Statistics
            'stat_students'     => '20000',
            'stat_trainers'     => '50',
            'stat_courses'      => '5000',
            'stat_certificates' => '2000',

            // Social links
            'social_facebook'   => 'https://www.facebook.com/insep.eg/',
            'social_instagram'  => 'https://www.instagram.com/insep_pro/',
            'social_youtube'    => 'https://www.youtube.com/@inseppro',
            'social_linkedin'   => 'https://www.linkedin.com/company/insep-pro',
            'social_twitter'    => 'https://twitter.com/insep_pro',
            'social_telegram'   => 'https://t.me/insep_pro',

            // Contact
            'contact_phone1'    => '01033330027',
            'contact_phone2'    => '01030033090',
            'contact_phone3'    => '0222900951',
            'contact_email'     => 'info@insep.net',
            'contact_address_ar'=> '١٣ الخليفة المأمون، روكسي، مصر الجديدة، القاهرة 11757',
            'contact_address_en'=> '13 El-Khalifa El-Maamoun, Roxy, Heliopolis, Cairo 11757',

            // About
            'about_ar'          => 'معهد INSEP هو الاسم الذي يعرفه الجميع في عالم علوم الرياضة بمنطقة الشرق الأوسط وشمال أفريقيا. تأسس المعهد منذ أكثر من 20 عامًا برؤية واضحة: تطوير الكوادر الرياضية العربية وفق أحدث المعايير العلمية والعالمية. حاصل على اعتماد ERASMUS ومسجل في السجل الأوروبي للمهنيين الرياضيين EREPS، ويعمل في أكثر من 15 دولة حول العالم.',
            'about_en'          => 'INSEP Institute is the recognized name in sports science across the Middle East and North Africa. Founded over 20 years ago with a clear vision: to develop Arab sports professionals according to the latest scientific and global standards. The institute holds ERASMUS accreditation and is registered in the European Register of Exercise Professionals (EREPS), operating in more than 15 countries worldwide.',

            // Privacy & Terms
            'privacy_ar'        => 'نحن في معهد INSEP نلتزم بحماية خصوصيتك وبياناتك الشخصية وفقاً للقوانين واللوائح المعمول بها.',
            'privacy_en'        => 'At INSEP Institute, we are committed to protecting your privacy and personal data in accordance with applicable laws and regulations.',
            'terms_ar'          => 'باستخدامك لموقع ومنصة معهد INSEP، فإنك توافق على الشروط والأحكام المنصوص عليها في هذه الوثيقة.',
            'terms_en'          => 'By using the INSEP Institute website and platform, you agree to the terms and conditions set forth in this document.',
        ];

        foreach ($defaults as $key => $value) {
            \DB::table('site_settings')->insert(['key' => $key, 'value' => $value, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
