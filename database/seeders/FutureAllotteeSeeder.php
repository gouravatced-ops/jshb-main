<?php

namespace Database\Seeders;

use App\Models\FutureAllottee;
use App\Models\FutureAllotteeContactDetail;
use App\Models\FutureAllotteeTransaction;
use App\Models\Scheme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FutureAllotteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidates = $this->getCandidates();
        $categoryPairs = $this->getCategoryPairs();
        $categoryCount = count($categoryPairs);

        // Fetch valid active schemes with financials from adms_jshb
        $schemes = Scheme::with(['financial:id,scheme_id,lottery_amount'])
            ->select('id', 'division_id', 'sub_division_id', 'pcategory_id', 'p_type_id', 'p_sub_type_id', 'quarter_type_id')
            ->get();
        $totalSchemes = $schemes->count();

        $now = now();
        $paymentDay = (int) $now->format('d');
        $paymentMonth = (int) $now->format('m');
        $paymentYear = (int) $now->format('Y');
        $txnDatePrefix = 'TXN' . $now->format('Ymd');

        // Fresh Seed: clear staging tables before inserting new mock data
        DB::connection('adms_allottees')->statement('SET FOREIGN_KEY_CHECKS=0;');
        FutureAllotteeTransaction::truncate();
        FutureAllotteeContactDetail::truncate();
        FutureAllottee::truncate();
        DB::connection('adms_allottees')->statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::connection('adms_allottees')->transaction(function () use (
            $candidates,
            $categoryPairs,
            $categoryCount,
            $schemes,
            $totalSchemes,
            $now,
            $paymentDay,
            $paymentMonth,
            $paymentYear,
            $txnDatePrefix
        ) {
            foreach ($candidates as $index => $item) {
                $refNumber = 'EXT-2026-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);
                $appNumber = 'JSHB' . (str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT) + $index);

                // Round-robin category assignment with matched English and Hindi values
                $category = $categoryPairs[$index % $categoryCount];

                // Assign from actual available schemes in round-robin fashion or fallback
                if ($totalSchemes > 0) {
                    $assignedScheme = $schemes[$index % $totalSchemes];
                    $schemeId = $assignedScheme->id;
                    $divisionId = $assignedScheme->division_id;
                    $subDivisionId = $assignedScheme->sub_division_id;
                    $pcategoryId = $assignedScheme->pcategory_id;
                    $propertyTypeId = $assignedScheme->p_type_id ?? 1;
                    $propertySubTypeId = $assignedScheme->p_sub_type_id ?? null;
                    $quarterId = $assignedScheme->quarter_type_id ?? 1;
                    $lotteryAmount = (float) ($assignedScheme->financial?->lottery_amount ?? 5000.00);
                } else {
                    $schemeId = 1;
                    $divisionId = 1;
                    $subDivisionId = 1;
                    $pcategoryId = 1;
                    $propertyTypeId = 1;
                    $propertySubTypeId = null;
                    $quarterId = 1;
                    $lotteryAmount = 5000.00;
                }

                // 1. Create FutureAllottee
                $futureAllottee = FutureAllottee::create([
                    'reference_portal_id' => $refNumber,
                    'application_no' => $appNumber,
                    'application_day' => $paymentDay,
                    'application_month' => $paymentMonth,
                    'application_year' => $paymentYear,
                    'scheme_id' => $schemeId,
                    'division_id' => $divisionId,
                    'subdivision_id' => $subDivisionId,
                    'pcategory_id' => $pcategoryId,
                    'property_type_id' => $propertyTypeId,
                    'p_sub_type_id' => $propertySubTypeId,
                    'quarter_id' => $quarterId,
                        'prefix' => $item['prefix'],
                        'allottee_name' => $item['name'],
                        'allottee_middle_name' => $item['middle'] ?: null,
                        'allottee_surname' => $item['surname'] ?: null,
                        'allottee_prefix_hindi' => $item['prefix_hi'],
                        'allottee_name_hindi' => $item['name_hi'],
                        'allottee_middle_hindi' => $item['middle_hi'] ?: null,
                        'allottee_surname_hindi' => $item['surname_hi'] ?: null,
                        'allottee_relation_type' => $item['relation_type'],
                        'relation_name' => $item['relation_name'],
                        'relation_prefix_hindi' => $item['relation_prefix_hi'],
                        'relation_name_hindi' => $item['relation_name_hi'],
                        'marital_status' => $item['marital_status'],
                        'allottee_gender' => $item['gender'],
                        'pan_card_number' => sprintf('ABC%c%04dF', 65 + $index, 1230 + $index),
                        'aadhar_card_number' => sprintf('65432109%04d', 8710 + $index),
                        'allottee_category' => $category['en'],
                        'allottee_category_hindi' => $category['hi'],
                        'allottee_religion' => ($index === 6) ? 'Muslim' : 'Hindu',
                        'allottee_nationality' => 'Indian',
                        'date_of_birth_day' => $item['dob_day'],
                        'date_of_birth_month' => $item['dob_month'],
                        'date_of_birth_year' => $item['dob_year'],
                        'email' => $item['email'],
                        'mobile' => $item['mobile'],
                        'remarks' => 'Online citizen lottery application via Citizen Portal',
                        'sync_status' => 'pending',
                        'synced_allottee_id' => null,
                        'synced_application_id' => null,
                        'synced_at' => null,
                        'error_log' => null,
                ]);

                // 2. Create or update FutureAllotteeContactDetail
                $address = $item['present_address'];
                $addressHindi = $item['present_address_hi'];
                $district = $item['district'];
                $pincode = $item['pincode'];
                $po = $item['po'];
                $poHindi = $item['po_hindi'];
                $ps = $item['ps'];
                $psHindi = $item['ps_hindi'];

                FutureAllotteeContactDetail::updateOrCreate(
                    ['future_allottee_id' => $futureAllottee->id],
                    [
                        'relation_type' => $item['relation_type'],
                        'prefix_relation_eng' => $item['prefix'],
                        'relation_name' => $item['relation_name'],
                        'prefix_relation_hindi' => $item['relation_prefix_hi'],
                        'relation_name_hindi' => $item['relation_name_hi'],

                        // Relation address
                        'relation_address' => $address,
                        'relation_address_hindi' => $addressHindi,
                        'relation_state' => 'Jharkhand',
                        'relation_district' => $district,
                        'relation_pincode' => $pincode,
                        'relation_post_office' => $po,
                        'relation_post_office_hindi' => $poHindi,
                        'relation_police_station' => $ps,
                        'relation_police_station_hindi' => $psHindi,
                        'same_as_relation_copy' => true,

                        // Present address
                        'present_address' => $address,
                        'present_address_hindi' => $addressHindi,
                        'present_state' => 'Jharkhand',
                        'present_district' => $district,
                        'present_pincode' => $pincode,
                        'present_post_office' => $po,
                        'present_post_office_hindi' => $poHindi,
                        'present_police_station' => $ps,
                        'present_police_station_hindi' => $psHindi,
                        'same_as_present_place_residance' => true,

                        // Permanent address
                        'permanent_address' => $address,
                        'permanent_address_hindi' => $addressHindi,
                        'permanent_state' => 'Jharkhand',
                        'permanent_district' => $district,
                        'permanent_pincode' => $pincode,
                        'permanent_post_office' => $po,
                        'permanent_post_office_hindi' => $poHindi,
                        'permanent_police_station' => $ps,
                        'permanent_police_station_hindi' => $psHindi,
                        'same_as_permanent_address' => true,
                    ]
                );

                // 3. Create or update FutureAllotteeTransaction
                FutureAllotteeTransaction::updateOrCreate(
                    ['future_allottee_id' => $futureAllottee->id],
                    [
                        'transaction_type' => 'lottery_payment',
                        'payment_stage' => 'application',
                        'amount' => $lotteryAmount,
                        'payment_mode' => ($index % 2 === 0) ? 'netbanking' : 'cheque',
                        'payment_status' => 'success',
                        'transaction_no' => $txnDatePrefix . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                        'utr_no' => 'UTR2026' . random_int(10000000, 99999999),
                        'receipt_file' => 'sample_lottery_receipt.pdf',
                        'receipt_path' => 'uploads/future_allottees/sample_lottery_receipt.pdf',
                        'payment_day' => $paymentDay,
                        'payment_month' => $paymentMonth,
                        'payment_year' => $paymentYear,
                        'paid_at' => $now,
                        'remarks' => 'Online lottery application fee payment',
                    ]
                );
            }
        });
    }

    /**
     * Paired English and Hindi Category definitions.
     */
    private function getCategoryPairs(): array
    {
        return [
            ['en' => 'General', 'hi' => 'lkekU;'],
            ['en' => 'General (PwD)', 'hi' => 'lkekU; (ih-Mh)'],
            ['en' => 'Scheduled Caste (SC)', 'hi' => 'vuqlwfpr tkfr (SC)'],
            ['en' => 'Scheduled Caste (SC) (PwD)', 'hi' => 'vuqlwfpr tkfr (SC) (ih-Mh)'],
            ['en' => 'Scheduled Tribe (ST)', 'hi' => 'vuqlwfpr tutkfr (ST)'],
            ['en' => 'Scheduled Tribe (ST) (PwD)', 'hi' => 'vuqlwfpr tutkfr (ST) (ih-Mh)'],
            ['en' => 'Other Backward Class (OBC)', 'hi' => 'vU; fiNM+k oxZ (OBC)'],
            ['en' => 'Other Backward Class (OBC) (PwD)', 'hi' => 'vU; fiNM+k oxZ (OBC) (ih-Mh)'],
            ['en' => 'Retired Government Servant', 'hi' => 'lsokfuo`r ljdkjh deZpkjh'],
            ['en' => 'Govt. Servant retiring within one year', 'hi' => ',dfn ds vUnj lsokfuo`r gksus okys ljdkjh deZpkjh'],
            ['en' => 'Armed Forces Personnel', 'hi' => 'l\'kL= cy deZpkjh'],
            ['en' => 'Ex-Servicemen', 'hi' => 'HkwriwoZ lSfud'],
            ['en' => 'Abandoned', 'hi' => 'ifjR;Dr'],
            ['en' => 'Destitute Widows', 'hi' => 'fu/kZu fo/kok'],
            ['en' => 'Vidhaanmandal', 'hi' => 'fo/kku eaMy'],
            ['en' => 'Vidhansabha', 'hi' => 'fo/kku lHkk'],
        ];
    }

    /**
     * Seed candidates data.
     */
    private function getCandidates(): array
    {
        return [
            [
                'prefix' => 'Mr.',
                'prefix_hi' => 'Jh-',
                'name' => 'Rajesh',
                'middle' => 'Kumar',
                'surname' => 'Sharma',
                'name_hi' => 'jkts\'k',
                'middle_hi' => 'dqekj',
                'surname_hi' => 'keZk',
                'relation_type' => 'Father',
                'relation_name' => 'Ramesh Sharma',
                'relation_prefix_hi' => 'firk',
                'relation_name_hi' => 'jes\'k \'kekZ',
                'gender' => 'Male',
                'marital_status' => 'Married',
                'dob_day' => 15,
                'dob_month' => 6,
                'dob_year' => 1988,
                'email' => 'rajesh.sharma.jshb@mailinator.com',
                'mobile' => '9835100001',
                'present_address' => 'Flat 402, Shanti Enclave, Harmu Housing Colony, Ranchi',
                'present_address_hi' => 'QySV 402] \'kkfUr ,UDysO] gjeq gkmlax dkysuh] jkaph',
                'district' => 'Ranchi',
                'pincode' => '834002',
                'po' => 'Harmu',
                'po_hindi' => 'gjeq',
                'ps' => 'Argora',
                'ps_hindi' => 'vkxksjk',
            ],
            [
                'prefix' => 'Mrs.',
                'prefix_hi' => 'Jherh',
                'name' => 'Anita',
                'middle' => '',
                'surname' => 'Devi',
                'name_hi' => 'vuhrk',
                'middle_hi' => '',
                'surname_hi' => 'nsoh',
                'relation_type' => 'Husband',
                'relation_name' => 'Manoj Kumar Verma',
                'relation_prefix_hi' => 'ifr',
                'relation_name_hi' => 'eukst dqekj oekZ',
                'gender' => 'Female',
                'marital_status' => 'Married',
                'dob_day' => 10,
                'dob_month' => 4,
                'dob_year' => 1991,
                'email' => 'anita.devi.jshb@mailinator.com',
                'mobile' => '9835100002',
                'present_address' => 'Plot 12, Adityapur Phase 2, Seraikela-Kharsawan',
                'present_address_hi' => 'IykV 12] vkfnR;kiqj Qst 2] ljk;dsyk&[kjlkok¡',
                'district' => 'Seraikela-Kharsawan',
                'pincode' => '831013',
                'po' => 'Adityapur',
                'po_hindi' => 'vkfnR;kiqj',
                'ps' => 'RIT Thana',
                'ps_hindi' => 'vkj vkbZ Vh Fkkuk',
            ],
            [
                'prefix' => 'Mr.',
                'prefix_hi' => 'Jh-',
                'name' => 'Sanjay',
                'middle' => 'Kumar',
                'surname' => 'Singh',
                'name_hi' => 'lat;',
                'middle_hi' => 'dqekj',
                'surname_hi' => 'flag',
                'relation_type' => 'Father',
                'relation_name' => 'Ramdev Singh',
                'relation_prefix_hi' => 'firk',
                'relation_name_hi' => 'jke nso flag',
                'gender' => 'Male',
                'marital_status' => 'Married',
                'dob_day' => 22,
                'dob_month' => 9,
                'dob_year' => 1985,
                'email' => 'sanjay.singh.jshb@mailinator.com',
                'mobile' => '9835100003',
                'present_address' => 'Quarter 35/B, Kadma Main Road, Jamshedpur',
                'present_address_hi' => 'DokVj 35@ch] dne eSu jksM] tee\'ksniqj',
                'district' => 'East Singhbhum',
                'pincode' => '831005',
                'po' => 'Kadma',
                'po_hindi' => 'dne',
                'ps' => 'Kadma',
                'ps_hindi' => 'dne',
            ],
            [
                'prefix' => 'Ms.',
                'prefix_hi' => 'lqJh',
                'name' => 'Sunita',
                'middle' => '',
                'surname' => 'Kumari',
                'name_hi' => 'lquhrk',
                'middle_hi' => '',
                'surname_hi' => 'dqekjh',
                'relation_type' => 'Father',
                'relation_name' => 'Vijay Prasad',
                'relation_prefix_hi' => 'firk',
                'relation_name_hi' => 'fot; izlkn',
                'gender' => 'Female',
                'marital_status' => 'Single',
                'dob_day' => 5,
                'dob_month' => 12,
                'dob_year' => 1995,
                'email' => 'sunita.kumari.jshb@mailinator.com',
                'mobile' => '9835100004',
                'present_address' => 'H.No 108, Ashok Nagar, Road No 3, Ranchi',
                'present_address_hi' => 'g- ua0 108] v\'kksd uxj] jksM ua0 3] jkaph',
                'district' => 'Ranchi',
                'pincode' => '834002',
                'po' => 'Ashok Nagar',
                'po_hindi' => 'v\'kksd uxj',
                'ps' => 'Argora',
                'ps_hindi' => 'vkxksjk',
            ],
            [
                'prefix' => 'Mr.',
                'prefix_hi' => 'Jh-',
                'name' => 'Amit',
                'middle' => 'Kumar',
                'surname' => 'Mahato',
                'name_hi' => 'vfer',
                'middle_hi' => 'dqekj',
                'surname_hi' => 'egRkks',
                'relation_type' => 'Father',
                'relation_name' => 'Ganesh Mahato',
                'relation_prefix_hi' => 'firk',
                'relation_name_hi' => 'x.ks\'k egRkks',
                'gender' => 'Male',
                'marital_status' => 'Married',
                'dob_day' => 18,
                'dob_month' => 1,
                'dob_year' => 1990,
                'email' => 'amit.mahato.jshb@mailinator.com',
                'mobile' => '9835100005',
                'present_address' => 'Village Dindli, Basti Ward 4, Adityapur',
                'present_address_hi' => 'xkao fnafy] cLrh okMZ 4] vkfnR;kiqj',
                'district' => 'Seraikela-Kharsawan',
                'pincode' => '831013',
                'po' => 'Adityapur',
                'po_hindi' => 'vkfnR;kiqj',
                'ps' => 'RIT Thana',
                'ps_hindi' => 'vkj vkbZ Vh Fkkuk',
            ],
            [
                'prefix' => 'Ms.',
                'prefix_hi' => 'lqJh',
                'name' => 'Priyanka',
                'middle' => '',
                'surname' => 'Soren',
                'name_hi' => 'fiz;kadk',
                'middle_hi' => '',
                'surname_hi' => 'lksjsu',
                'relation_type' => 'Father',
                'relation_name' => 'Shibu Soren',
                'relation_prefix_hi' => 'firk',
                'relation_name_hi' => 'f\'kCkq lksjsu',
                'gender' => 'Female',
                'marital_status' => 'Single',
                'dob_day' => 30,
                'dob_month' => 7,
                'dob_year' => 1994,
                'email' => 'priyanka.soren.jshb@mailinator.com',
                'mobile' => '9835100006',
                'present_address' => 'Birsa Chowk, Khunti Road, Ranchi',
                'present_address_hi' => 'fcUlk pksd] [kq.Vh jksM] jkaph',
                'district' => 'Ranchi',
                'pincode' => '834003',
                'po' => 'Hinoo',
                'po_hindi' => 'fgUuw',
                'ps' => 'Doranda',
                'ps_hindi' => 'nksj.Mk',
            ],
            [
                'prefix' => 'Mr.',
                'prefix_hi' => 'Jh-',
                'name' => 'Mohammad',
                'middle' => '',
                'surname' => 'Imran',
                'name_hi' => 'eksgEen',
                'middle_hi' => '',
                'surname_hi' => 'bejku',
                'relation_type' => 'Father',
                'relation_name' => 'Md Aslam',
                'relation_prefix_hi' => 'firk',
                'relation_name_hi' => 'eks- vlye',
                'gender' => 'Male',
                'marital_status' => 'Married',
                'dob_day' => 12,
                'dob_month' => 3,
                'dob_year' => 1987,
                'email' => 'md.imran.jshb@mailinator.com',
                'mobile' => '9835100007',
                'present_address' => 'Line No 4, Mango Main Road, Jamshedpur',
                'present_address_hi' => 'ykbu ua0 4] eakxks eSu jksM] tee\'ksniqj',
                'district' => 'East Singhbhum',
                'pincode' => '831012',
                'po' => 'Mango',
                'po_hindi' => 'eakxks',
                'ps' => 'Mango',
                'ps_hindi' => 'eakxks',
            ],
            [
                'prefix' => 'Mr.',
                'prefix_hi' => 'Jh-',
                'name' => 'Deepak',
                'middle' => 'Kumar',
                'surname' => 'Gupta',
                'name_hi' => 'nhipd',
                'middle_hi' => 'dqekj',
                'surname_hi' => 'xqIrk',
                'relation_type' => 'Father',
                'relation_name' => 'Ram Prasad Gupta',
                'relation_prefix_hi' => 'firk',
                'relation_name_hi' => 'jke izlkn xqIrk',
                'gender' => 'Male',
                'marital_status' => 'Married',
                'dob_day' => 25,
                'dob_month' => 11,
                'dob_year' => 1989,
                'email' => 'deepak.gupta.jshb@mailinator.com',
                'mobile' => '9835100008',
                'present_address' => 'Morabadi Housing Colony, Near Oxygen Park, Ranchi',
                'present_address_hi' => 'eksjkcknh gkmlax dkysuh] vkWDlhtu ikdZ ds ikl] jkaph',
                'district' => 'Ranchi',
                'pincode' => '834008',
                'po' => 'Bariatu',
                'po_hindi' => 'cfj;krq',
                'ps' => 'Bariatu',
                'ps_hindi' => 'cfj;krq',
            ],
            [
                'prefix' => 'Mrs.',
                'prefix_hi' => 'Jherh',
                'name' => 'Pooja',
                'middle' => '',
                'surname' => 'Pandey',
                'name_hi' => 'iwtk',
                'middle_hi' => '',
                'surname_hi' => 'ik.Ms;',
                'relation_type' => 'Husband',
                'relation_name' => 'Alok Pandey',
                'relation_prefix_hi' => 'ifr',
                'relation_name_hi' => 'vkyksd ik.Ms;',
                'gender' => 'Female',
                'marital_status' => 'Married',
                'dob_day' => 8,
                'dob_month' => 8,
                'dob_year' => 1992,
                'email' => 'pooja.pandey.jshb@mailinator.com',
                'mobile' => '9835100009',
                'present_address' => 'Sector 4, Bokaro Steel City, Bokaro',
                'present_address_hi' => 'lsDVj 4] cksdkjks LVhy flVh] cksdkjks',
                'district' => 'Bokaro',
                'pincode' => '827004',
                'po' => 'Bokaro Steel City',
                'po_hindi' => 'cksdkjks LVhy flVh',
                'ps' => 'Sector 4 Thana',
                'ps_hindi' => 'lsDVj 4 Fkkuk',
            ],
            [
                'prefix' => 'Mr.',
                'prefix_hi' => 'Jh-',
                'name' => 'Vikash',
                'middle' => 'Kumar',
                'surname' => 'Yadav',
                'name_hi' => 'fodk\'k',
                'middle_hi' => 'dqekj',
                'surname_hi' => ';kno',
                'relation_type' => 'Father',
                'relation_name' => 'Shivlal Yadav',
                'relation_prefix_hi' => 'firk',
                'relation_name_hi' => 'f\'kOkyky ;kno',
                'gender' => 'Male',
                'marital_status' => 'Single',
                'dob_day' => 14,
                'dob_month' => 2,
                'dob_year' => 1996,
                'email' => 'vikash.yadav.jshb@mailinator.com',
                'mobile' => '9835100010',
                'present_address' => 'Hinoo Bypass Road, Near Aerodrome, Ranchi',
                'present_address_hi' => 'fgUuw ckbil jksM] ,jksMªkse ds ikl] jkaph',
                'district' => 'Ranchi',
                'pincode' => '834002',
                'po' => 'Hinoo',
                'po_hindi' => 'fgUuw',
                'ps' => 'Doranda',
                'ps_hindi' => 'nksj.Mk',
            ],
        ];
    }
}
