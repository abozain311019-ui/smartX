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
            resolution TEXT NOT NULL DEFAULT 'طلب عبر متجر سيارة الخدمة',
            preferred_date TEXT DEFAULT '',
            estimated_price TEXT DEFAULT 'تحدد وتؤكد عند المعاينة',
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
                'active_vans'     => 'سيارات SMART X المتنقلة جاهزة في جميع أحياء جدة',
                'warranty_years'  => 'ضمان شامل لمدة سنتين',
            ];
            $stmt = $pdo->prepare('INSERT INTO settings (key, value) VALUES (:k, :v)');
            foreach ($defaults as $k => $v) {
                $stmt->execute([':k' => $k, ':v' => $v]);
            }
        }

        // Seed realistic store catalog (no forced hardcoded prices — "اطلب مع التوصيل والتركيب بسيارة الخدمة")
        $pdo->exec('DELETE FROM cameras; DELETE FROM projects;');

        $products = [
            [
                'code'         => 'SX-OUT-01',
                'name_en'      => 'Bullet 4K',
                'name_ar'      => 'كاميرا بولت خارجية 4K للواجهات والأسوار',
                'category'     => 'outdoor',
                'badge'        => 'الأكثر طلباً في جدة',
                'resolution'   => 'دقة فائقة الوضوح 4K (8 ميجابكسل)',
                'night_vision' => 'رؤية ليلية ملونة صافية حتى 60 متر',
                'weatherproof' => 'هيكل معدني يتحمل شمس ورطوبة جدة (IP67)',
                'ai_features'  => 'تنبيه فوري على الجوال عند رصد حركة أشخاص أو سيارات',
                'fov'          => 'زاوية عريضة 112 درجة تغطي السور والمدخل',
                'price_from'   => 0,
                'description'  => 'تصلك مباشرة عبر سيارة SMART X المتنقلة مع تمديد مخفي كامل على الواجهة، ومصممة خصيصاً لتحمل حرارة ورطوبة جدة العالية.',
                'image_url'    => 'assets/images/cam-bullet.jpg',
                'sort_order'   => 1,
            ],
            [
                'code'         => 'SX-IN-02',
                'name_en'      => 'Dome 4K',
                'name_ar'      => 'كاميرا قبة داخلية مدمجة للأسقف والصالات',
                'category'     => 'indoor',
                'badge'        => 'للأسقف والديكورات',
                'resolution'   => 'وضوح عالٍ مع مايكروفون مدمج عالي النقاء',
                'night_vision' => 'رؤية ليلية واضحة حتى في الإضاءة الخافتة',
                'weatherproof' => 'تصميم معماري انسيابي مقاوم للعبث',
                'ai_features'  => 'تغطية مريحة للمنازل، المكاتب، والمعارض التجارية',
                'fov'          => 'زاوية واسعة 120 درجة تشمل كامل المساحة',
                'price_from'   => 0,
                'description'  => 'تندمج بكل هدوء داخل الأسقف الجبسية بدون ما تلفت الانتباه، مع صوت وصورة صافية ومتابعة مباشرة من جوالك.',
                'image_url'    => 'assets/images/cam-dome.jpg',
                'sort_order'   => 2,
            ],
            [
                'code'         => 'SX-PTZ-03',
                'name_en'      => 'PTZ 360',
                'name_ar'      => 'كاميرا متحركة شاملة 360 درجة بتقريب بصري',
                'category'     => 'ptz',
                'badge'        => 'تحكم كامل من الجوال',
                'resolution'   => 'دقة 4K مع تقريب بصري حقيقي حتى 25 ضعف',
                'night_vision' => 'مدى رؤية ليلية يصل إلى 150 متر',
                'weatherproof' => 'حماية كاملة ضد الغبار والأمطار والرياح',
                'ai_features'  => 'تتبع تلقائي للأجسام المتحركة وتحكم فوري بالاتجاه',
                'fov'          => 'دوران كامل 360 درجة للساحات والمستودعات',
                'price_from'   => 0,
                'description'  => 'مثالية للفلل الكبيرة، الساحات المفتوحة، والمستودعات؛ تتحكم في اتجاهها وتقرّب التفاصيل البعيدة مباشرة من تطبيق الجوال.',
                'image_url'    => 'assets/images/cam-ptz.jpg',
                'sort_order'   => 3,
            ],
            [
                'code'         => 'SX-PANO-04',
                'name_en'      => 'Panorama 180',
                'name_ar'      => 'كاميرا بانوراما مزدوجة العدسة 180 درجة',
                'category'     => 'outdoor',
                'badge'        => 'تغنيك عن كاميرتين',
                'resolution'   => 'عدستان مدمجتان في مشهد عريض واحد بدقة 4K',
                'night_vision' => 'ألوان ليلية طبيعية ووضوح فائق للواجهة والشارع',
                'weatherproof' => 'مقاومة كاملة للعوامل الجوية القاسية',
                'ai_features'  => 'تغطية واجهة الفيلا والشارع بالكامل بدون نقاط عمياء',
                'fov'          => 'مشهد بانورامي 180 درجة بدون أي تشوه',
                'price_from'   => 0,
                'description'  => 'حل راقٍ يقلل عدد الكاميرات الظاهرة على الواجهة، ويجمع لك كامل الشارع والمدخل في لقطة واحدة فائقة الوضوح.',
                'image_url'    => 'assets/images/cam-panorama-dusk.jpg',
                'sort_order'   => 4,
            ],
            [
                'code'         => 'SX-SLR-05',
                'name_en'      => 'Solar 4G',
                'name_ar'      => 'كاميرا طاقة شمسية لاسلكية بشريحة بيانات 4G',
                'category'     => 'solar',
                'badge'        => 'بدون أسلاك أو كهرباء',
                'resolution'   => 'وضوح عالٍ مع بطارية ليثيوم تدوم طويلاً',
                'night_vision' => 'رؤية ليلية ملونة مع كشاف إضاءة تفاعلي',
                'weatherproof' => 'لوح شمسي عالي الكفاءة يعمل طوال العام',
                'ai_features'  => 'تحدث واستماع مباشر عبر التطبيق بدون إنترنت أرضي',
                'fov'          => 'عدسة متحركة بزاوية 355 درجة عبر الجوال',
                'price_from'   => 0,
                'description'  => 'الحل العملي للمشاريع قيد الإنشاء، الاستراحات، والأحواش في جدة التي يصعب إيصال تمديدات الكهرباء أو الإنترنت إليها.',
                'image_url'    => 'assets/images/cam-solar.jpg',
                'sort_order'   => 5,
            ],
            [
                'code'         => 'SX-ANPR-06',
                'name_en'      => 'ANPR Gate',
                'name_ar'      => 'كاميرا قراءة وتوثيق لوحات المركبات للبوابات',
                'category'     => 'anpr',
                'badge'        => 'للبوابات والمستودعات',
                'resolution'   => 'التقاط فائق السرعة للمركبات أثناء الحركة',
                'night_vision' => 'فلتر خاص يمنع انعكاس أنوار السيارات ليلاً',
                'weatherproof' => 'هيكل خارجي مقوى للعمل المتواصل 24 ساعة',
                'ai_features'  => 'توثيق دقيق لأرقام اللوحات وأوقات الدخول والخروج',
                'fov'          => 'عدسة متغيرة البعد البؤري لمسارات الدخول',
                'price_from'   => 0,
                'description'  => 'مخصصة لمداخل المجمعات السكنية والشركات والمستودعات لتوثيق كل مركبة تدخل أو تخرج بصورة واضحة ليلاً ونهاراً.',
                'image_url'    => 'assets/images/cam-anpr-gate.jpg',
                'sort_order'   => 6,
            ],
            [
                'code'         => 'SX-PKG-04',
                'name_en'      => 'Home Kit 4',
                'name_ar'      => 'منظومة منزلية متكاملة (4 كاميرات + جهاز تسجيل)',
                'category'     => 'bundles',
                'badge'        => 'شامل التوصيل والتركيب',
                'resolution'   => '4 كاميرات 4K ملونة + جهاز تسجيل شبكي + قرص تخزين',
                'night_vision' => 'رؤية ليلية ملونة للمداخل والأسوار',
                'weatherproof' => 'كابلات نحاسية أصلية ومواسير حماية ضد الشمس',
                'ai_features'  => 'ربط مباشر على جوالات العائلة وتنبيهات حركة فورية',
                'fov'          => 'تغطية مثالية للشقق والفلل والاستراحات',
                'price_from'   => 0,
                'description'  => 'تصلك المنظومة كاملة داخل سيارة SMART X المتنقلة، نعاين الزوايا ونركبها بتمديد مخفي ونسلمك النظام شغال في نفس اليوم.',
                'image_url'    => 'assets/images/hero-van-jeddah.jpg',
                'sort_order'   => 7,
            ],
            [
                'code'         => 'SX-PKG-08',
                'name_en'      => 'Business Kit 8',
                'name_ar'      => 'منظومة الفلل الكبرى والمعارض (8 كاميرات 4K)',
                'category'     => 'bundles',
                'badge'        => 'مطابق لاشتراطات التراخيص',
                'resolution'   => '8 كاميرات 4K + جهاز تسجيل 8 قنوات + تخزين طويل',
                'night_vision' => 'تغطية ليلية ملونة خارجية وداخلية مع صوت نقي',
                'weatherproof' => 'صندوق حماية أنيق للأجهزة وتمديد معماري مخفي',
                'ai_features'  => 'تجهيز معتمد للمحلات والمعارض والمكاتب التجارية',
                'fov'          => 'تغطية شاملة بدون نقاط عمياء للداخل والخارج',
                'price_from'   => 0,
                'description'  => 'الخيار المفضل للمعارض التجارية والفلل الكبيرة في جدة، مع تركيب نظيف وضمان شامل لمدة سنتين ودعم ميداني سريع.',
                'image_url'    => 'assets/images/cam-panorama-dusk.jpg',
                'sort_order'   => 8,
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
        foreach ($products as $prod) {
            $stmt->execute($prod);
        }

        $projects = [
            [
                'tag'               => 'المنازل والفلل',
                'title'             => 'تأمين واجهة ومداخل فيلا حديثة بتمديد مخفي',
                'district'          => 'حي الشاطئ — جدة',
                'cameras_installed' => 12,
                'system_spec'       => 'تمديد مخفي بالكامل • رؤية ليلية ملونة 4K',
                'summary'           => 'رتبنا مسارات الكابلات بشكل مخفي تماماً دون أي أثر على الواجهة الحجرية، مع تغطية محيطية كاملة للأسوار والمداخل والارتدادات.',
                'image_url'         => 'assets/images/cam-panorama-dusk.jpg',
                'sort_order'        => 1,
            ],
            [
                'tag'               => 'المعارض والمكاتب',
                'title'             => 'تجهيز معرض ومكاتب إدارية بمعايير التراخيص',
                'district'          => 'حي الروضة — طريق التحلية',
                'cameras_installed' => 16,
                'system_spec'       => 'كاميرات قبة مدمجة • تسجيل صوتي • تغطية شاملة',
                'summary'           => 'توزيع متقن لصالة العرض والمكاتب ومنطقة المحاسبة بكاميرات داخلية هادئة مدمجة في السقف ومطابقة لاشتراطات التراخيص التجارية.',
                'image_url'         => 'assets/images/cam-dome.jpg',
                'sort_order'        => 2,
            ],
            [
                'tag'               => 'المستودعات والبوابات',
                'title'             => 'تأمين بوابة مستودع وساحات شحن لوجستية',
                'district'          => 'منطقة الخمرة — جدة',
                'cameras_installed' => 24,
                'system_spec'       => 'توثيق لوحات المركبات • كاميرات متحركة 360',
                'summary'           => 'حماية بوابات دخول الشاحنات وساحات التحميل والممرات العالية برؤية واضحة على مدار الساعة وسجل كامل لحركة المركبات.',
                'image_url'         => 'assets/images/cam-anpr-gate.jpg',
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
