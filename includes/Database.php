<?php
declare(strict_types=1);

namespace SmartX;

use PDO;

final class Database
{
    private static ?PDO $pdo = null;

    public static function connect(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $dataDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data';
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0775, true);
        }

        $dbPath = $dataDir . DIRECTORY_SEPARATOR . 'smartx.sqlite';
        $pdo = new PDO('sqlite:' . $dbPath, null, null, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $pdo->exec('PRAGMA journal_mode = WAL;');
        $pdo->exec('PRAGMA foreign_keys = ON;');

        self::$pdo = $pdo;
        self::migrate($pdo);

        return $pdo;
    }

    private static function migrate(PDO $pdo): void
    {
        $pdo->exec(<<<'SQL'
        CREATE TABLE IF NOT EXISTS settings (
            key TEXT PRIMARY KEY,
            value TEXT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS leads (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tracking_code TEXT NOT NULL UNIQUE,
            name TEXT NOT NULL,
            phone TEXT NOT NULL,
            district TEXT NOT NULL,
            place_type TEXT NOT NULL,
            cameras_count TEXT NOT NULL,
            resolution TEXT NOT NULL DEFAULT 'يحدد بعد المعاينة المجانية',
            preferred_date TEXT DEFAULT '',
            estimated_price TEXT DEFAULT 'تحدد بعد المعاينة المجانية',
            details TEXT DEFAULT '',
            status TEXT NOT NULL DEFAULT 'new',
            created_at TEXT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS cameras (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            code TEXT NOT NULL,
            name_en TEXT NOT NULL,
            name_ar TEXT NOT NULL,
            category TEXT NOT NULL,
            badge TEXT NOT NULL,
            resolution TEXT NOT NULL,
            night_vision TEXT NOT NULL,
            weatherproof TEXT NOT NULL,
            ai_features TEXT NOT NULL,
            fov TEXT NOT NULL,
            price_from INTEGER NOT NULL DEFAULT 0,
            description TEXT NOT NULL,
            image_url TEXT NOT NULL,
            sort_order INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tag TEXT NOT NULL,
            title TEXT NOT NULL,
            district TEXT NOT NULL,
            cameras_installed INTEGER NOT NULL,
            system_spec TEXT NOT NULL,
            summary TEXT NOT NULL,
            image_url TEXT NOT NULL,
            sort_order INTEGER NOT NULL DEFAULT 0
        );
        SQL);

        $count = (int) $pdo->query('SELECT COUNT(*) FROM settings')->fetchColumn();
        if ($count === 0) {
            $defaults = [
                'whatsapp_number' => '966500000000',
                'phone_display'   => '050 000 0000',
                'admin_pin'       => '2026',
                'city'            => 'جدة',
                'response_time'   => '45 دقيقة',
                'active_vans'     => 'سيارات الخدمة جاهزة وتغطي جميع أحياء جدة',
                'warranty_years'  => 'ضمان شامل لمدة سنتين',
            ];
            $stmt = $pdo->prepare('INSERT INTO settings (key, value) VALUES (:k, :v)');
            foreach ($defaults as $k => $v) {
                $stmt->execute([':k' => $k, ':v' => $v]);
            }
        }

        // Seed crisp, natural, persuasive Arabic copy
        $pdo->exec('DELETE FROM cameras; DELETE FROM projects;');

        $cameras = [
            [
                'code'         => '01',
                'name_en'      => 'Bullet',
                'name_ar'      => 'كاميرا بولت للواجهات والأسوار',
                'category'     => 'outdoor',
                'badge'        => 'للواجهات والأسوار',
                'resolution'   => 'تصوير فائق الوضوح 4K',
                'night_vision' => 'رؤية ليلية ملونة وواضحة حتى 60 متر',
                'weatherproof' => 'مصممة لتحمل شمس ورطوبة جدة العالية',
                'ai_features'  => 'تنبيه فوري على الجوال عند اقتراب أي شخص أو مركبة',
                'fov'          => 'زاوية عريضة تغطي السور والمدخل بالكامل',
                'price_from'   => 0,
                'description'  => 'خيارك الأول لحماية مداخل الفلل والأسوار والمواقف؛ هيكل معدني قوي يتحمل حرارة الصيف والرطوبة الساحلية مع صورة صافية ليل ونهار.',
                'image_url'    => 'https://images.pexels.com/photos/274895/pexels-photo-274895.jpeg?auto=compress&cs=tinysrgb&w=1400',
                'sort_order'   => 1,
            ],
            [
                'code'         => '02',
                'name_en'      => 'Dome',
                'name_ar'      => 'كاميرا قبة داخلية للأسقف',
                'category'     => 'indoor',
                'badge'        => 'أناقة داخلية',
                'resolution'   => 'وضوح عالٍ مع تسجيل صوتي نقي',
                'night_vision' => 'رؤية ليلية صافية حتى في الإضاءة الخافتة',
                'weatherproof' => 'تصميم مدمج وأنيق مقاوم للعبث',
                'ai_features'  => 'تغطية ناعمة للصالات والمكاتب والمعارض التجارية',
                'fov'          => 'زاوية واسعة 120 درجة تغطي كامل الغرفة',
                'price_from'   => 0,
                'description'  => 'تندمج بكل هدوء مع الأسقف الجبسية وديكور البيت أو المعرض، وتعطيك صوتاً وصورة بنقاء عالٍ بدون ما تلفت الانتباه.',
                'image_url'    => 'https://images.pexels.com/photos/29866272/pexels-photo-29866272/free-photo-of-outdoor-security-camera-on-concrete-wall.jpeg?auto=compress&cs=tinysrgb&w=1400',
                'sort_order'   => 2,
            ],
            [
                'code'         => '03',
                'name_en'      => 'PTZ 360',
                'name_ar'      => 'كاميرا متحركة شاملة مع تقريب بصري',
                'category'     => 'ptz',
                'badge'        => 'تحكم كامل من جوالك',
                'resolution'   => 'تقريب بصري حقيقي حتى 25 ضعف',
                'night_vision' => 'مدى ليلي طويل يصل إلى 150 متر',
                'weatherproof' => 'حماية كاملة من الغبار والأمطار والرياح',
                'ai_features'  => 'تلف وتتابع الحركة تلقائياً بضغطة زر من التطبيق',
                'fov'          => 'دوران كامل 360 درجة يغطي الساحات المفتوحة',
                'price_from'   => 0,
                'description'  => 'تتحكم في اتجاهها وتقرّب الصورة من جوالك وين ما كنت، مثالية للمساحات الكبيرة والقصور والمستودعات والمجمعات.',
                'image_url'    => 'https://images.pexels.com/photos/32516148/pexels-photo-32516148/free-photo-of-surveillance-camera-against-clear-blue-sky.jpeg?auto=compress&cs=tinysrgb&w=1400',
                'sort_order'   => 3,
            ],
            [
                'code'         => '04',
                'name_en'      => 'Panorama 180',
                'name_ar'      => 'كاميرا بانوراما عريضة 180 درجة',
                'category'     => 'outdoor',
                'badge'        => 'تغنيك عن كاميرتين',
                'resolution'   => 'عدستان مدمجتان في مشهد واحد فائق الدقة',
                'night_vision' => 'ألوان ليلية طبيعية بدون أي تشويه',
                'weatherproof' => 'مقاومة كاملة للعوامل الجوية القاسية',
                'ai_features'  => 'تغطية واجهة الفيلا والشارع بالكامل بكاميرا واحدة',
                'fov'          => 'مشهد عريض 180 درجة بدون أي نقطة عمياء',
                'price_from'   => 0,
                'description'  => 'بدل ما تركب أكثر من كاميرا على الواجهة، كاميرا واحدة بعدستين تجمع لك المشهد كامل بوضوح استثنائي ومنظر راقٍ.',
                'image_url'    => 'https://images.pexels.com/photos/18400292/pexels-photo-18400292.jpeg?auto=compress&cs=tinysrgb&w=1400',
                'sort_order'   => 4,
            ],
            [
                'code'         => '05',
                'name_en'      => 'Solar 4G',
                'name_ar'      => 'كاميرا طاقة شمسية لاسلكية',
                'category'     => 'solar',
                'badge'        => 'بدون أسلاك أو كهرباء',
                'resolution'   => 'وضوح عالٍ مع بطارية تدوم طويلاً',
                'night_vision' => 'رؤية ليلية ملونة مع كشاف إضاءة تلقائي',
                'weatherproof' => 'لوح شمسي عالي الكفاءة يعمل طوال العام',
                'ai_features'  => 'تعمل بشريحة بيانات مع ميزة التحدث والاستماع المباشر',
                'fov'          => 'عدسة متحركة تتحكم فيها بالكامل من جوالك',
                'price_from'   => 0,
                'description'  => 'أذكى حل للمواقع قيد الإنشاء، الاستراحات، والأحواش التي لا تتوفر فيها تمديدات كهرباء أو إنترنت أرضي.',
                'image_url'    => 'https://images.pexels.com/photos/12571569/pexels-photo-12571569.jpeg?auto=compress&cs=tinysrgb&w=1400',
                'sort_order'   => 5,
            ],
            [
                'code'         => '06',
                'name_en'      => 'ANPR',
                'name_ar'      => 'كاميرا قراءة لوحات السيارات',
                'category'     => 'anpr',
                'badge'        => 'للبوابات والمجمعات',
                'resolution'   => 'التقاط فائق السرعة للمركبات أثناء الحركة',
                'night_vision' => 'معالجة ذكية تمنع انعكاس أنوار السيارات ليلاً',
                'weatherproof' => 'هيكل خارجي مقوى للعمل المتواصل 24 ساعة',
                'ai_features'  => 'توثيق واضح لأرقام اللوحات وأوقات الدخول والخروج',
                'fov'          => 'عدسة مخصصة لمسارات البوابات والمواقف',
                'price_from'   => 0,
                'description'  => 'توثق لك كل سيارة تدخل أو تخرج من المجمع أو الشركة أو المستودع بصورة صافية تقرأ منها اللوحة بوضوح في الليل والنهار.',
                'image_url'    => 'https://images.pexels.com/photos/37591155/pexels-photo-37591155.jpeg?auto=compress&cs=tinysrgb&w=1400',
                'sort_order'   => 6,
            ],
        ];

        $stmt = $pdo->prepare(<<<'SQL'
            INSERT INTO cameras (
                code, name_en, name_ar, category, badge, resolution, night_vision,
                weatherproof, ai_features, fov, price_from, description, image_url, sort_order
            ) VALUES (
                :code, :name_en, :name_ar, :category, :badge, :resolution, :night_vision,
                :weatherproof, :ai_features, :fov, :price_from, :description, :image_url, :sort_order
            )
        SQL);
        foreach ($cameras as $cam) {
            $stmt->execute($cam);
        }

        $projects = [
            [
                'tag'               => 'المنازل والفلل',
                'title'             => 'حماية شاملة لفيلا حديثة بتمديد مخفي',
                'district'          => 'حي الشاطئ — جدة',
                'cameras_installed' => 12,
                'system_spec'       => 'تمديد مخفي بالكامل • رؤية ليلية ملونة 4K',
                'summary'           => 'رتبنا مسارات الكابلات بشكل مخفي تماماً بدون ما يبان سلك واحد على الواجهة، مع تغطية كاملة للأسوار والمداخل وربط فوري بجوالات الأسرة.',
                'image_url'         => 'https://images.pexels.com/photos/37591155/pexels-photo-37591155.jpeg?auto=compress&cs=tinysrgb&w=1400&h=1000&fit=crop',
                'sort_order'        => 1,
            ],
            [
                'tag'               => 'المعارض والمكاتب',
                'title'             => 'تجهيز معرض ومكاتب إدارية بمعايير التراخيص',
                'district'          => 'حي الروضة — طريق التحلية',
                'cameras_installed' => 16,
                'system_spec'       => 'كاميرات قبة أنيقة • صوت نقي • شاشة عرض',
                'summary'           => 'توزيع مدروس لصالة العرض، منطقة المحاسبة، والمكاتب بكاميرات داخلية هادئة وتسجيل طويل المدى يطابق كافة اشتراطات التراخيص التجارية.',
                'image_url'         => 'https://images.pexels.com/photos/18400292/pexels-photo-18400292.jpeg?auto=compress&cs=tinysrgb&w=1400&h=1000&fit=crop',
                'sort_order'        => 2,
            ],
            [
                'tag'               => 'المستودعات والمنشآت',
                'title'             => 'تأمين مستودع وساحات شحن وبوابات دخول',
                'district'          => 'منطقة الخمرة — جدة',
                'cameras_installed' => 24,
                'system_spec'       => 'كاميرات متحركة 360 • قراءة لوحات',
                'summary'           => 'تغطية الممرات العالية وساحات التحميل وبوابات الشاحنات مع تحكم كامل للإدارة ومراقبة حية على مدار الساعة.',
                'image_url'         => 'https://images.pexels.com/photos/12571569/pexels-photo-12571569.jpeg?auto=compress&cs=tinysrgb&w=1400&h=1000&fit=crop',
                'sort_order'        => 3,
            ],
        ];

        $stmt = $pdo->prepare(<<<'SQL'
            INSERT INTO projects (
                tag, title, district, cameras_installed, system_spec, summary, image_url, sort_order
            ) VALUES (
                :tag, :title, :district, :cameras_installed, :system_spec, :summary, :image_url, :sort_order
            )
        SQL);
        foreach ($projects as $proj) {
            $stmt->execute($proj);
        }
    }

    public static function getSettings(): array
    {
        $pdo = self::connect();
        $rows = $pdo->query('SELECT key, value FROM settings')->fetchAll();
        $out = [];
        foreach ($rows as $row) {
            $out[$row['key']] = $row['value'];
        }
        return $out;
    }

    public static function getCameras(): array
    {
        $pdo = self::connect();
        return $pdo->query('SELECT * FROM cameras ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public static function getProjects(): array
    {
        $pdo = self::connect();
        return $pdo->query('SELECT * FROM projects ORDER BY sort_order ASC, id ASC')->fetchAll();
    }
}
