<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            ['name' => 'সভাপতি'],
            ['name' => 'সিনিয়র সহ-সভাপতি'],
            ['name' => 'সহ-সভাপতি'],
            ['name' => 'সহ-সভাপতি'],
            ['name' => 'সহ-সভাপতি'],
            ['name' => 'সহ-সভাপতি'],
            ['name' => 'সহ-সভাপতি'],
            ['name' => 'মহা-সচিব'],
            ['name' => 'সিনিয়র যুগ্ম-মহাসচিব'],
            ['name' => 'যুগ্ম-মহাসচিব'],
            ['name' => 'যুগ্ম-মহাসচিব'],
            ['name' => 'যুগ্ম-মহাসচিব'],
            ['name' => 'যুগ্ম-মহাসচিব'],
            ['name' => 'যুগ্ম-মহাসচিব'],
            ['name' => 'কোষাধ্যক্ষ'],
            ['name' => 'সহ কোষাধ্যক্ষ'],
            ['name' => 'সাংগঠনিক সম্পাদক'],
            ['name' => 'সহ-সাংগঠনিক সম্পাদক'],
            ['name' => 'সহ-সাংগঠনিক সম্পাদক'],
            ['name' => 'সহ-সাংগঠনিক সম্পাদক'],
            ['name' => 'সহ-সাংগঠনিক সম্পাদক'],
            ['name' => 'সহ-সাংগঠনিক সম্পাদক'],
            ['name' => 'দপ্তর সম্পাদক'],
            ['name' => 'সহ দপ্তর সম্পাদক'],
            ['name' => 'প্রচার ও প্রকাশনা সম্পাদক'],
            ['name' => 'সহ প্রচার ও প্রকাশনা সম্পাদক'],
            ['name' => 'আইন বিষয়ক সম্পাদক'],
            ['name' => 'সহ আইন বিষয়ক সম্পাদক'],
            ['name' => 'আন্তর্জাতিক বিষয়ক সম্পাদক'],
            ['name' => 'সহ আন্তর্জাতিক বিষয়ক সম্পাদক'],
            ['name' => 'তথ্যপ্রযুক্তি ও গবেষণা বিষয়ক সম্পাদক'],
            ['name' => 'সহ তথ্যপ্রযুক্তি ও গবেষণা বিষয়ক সম্পাদক'],
            ['name' => 'শিক্ষা, সংস্কৃতি ও কল্যাণ বিষয়ক সম্পাদক'],
            ['name' => 'সহ শিক্ষা, সংস্কৃতি ও কল্যাণ বিষয়ক সম্পাদক'],
            ['name' => 'ক্রীড়া সম্পাদক'],
            ['name' => 'সহ ক্রীড়া সম্পাদক'],
            ['name' => 'সদস্য-১'],
            ['name' => 'সদস্য-২'],
            ['name' => 'সদস্য-৩'],
            ['name' => 'সদস্য-৪'],
            ['name' => 'সদস্য-৫'],
            ['name' => 'সদস্য-৬'],
            ['name' => 'সদস্য-৭'],
            ['name' => 'সদস্য-৮'],
            ['name' => 'সদস্য-৯'],
            ['name' => 'সদস্য-১০'],
            ['name' => 'সদস্য-১১'],
            ['name' => 'সদস্য-১২'],
            ['name' => 'সদস্য-১৩'],
            ['name' => 'সদস্য-১৪'],
            ['name' => 'সদস্য-১৫'],
        ];

        $now = Carbon::now();

        $multiVoteCategories = ['যুগ্ম-মহাসচিব', 'সহ-সাংগঠনিক সম্পাদক', 'সহ-সভাপতি'];

        $categories = array_map(function ($category) use ($now, $multiVoteCategories) {
            $name = $category['name'];

            $isMultiVote = in_array($name, $multiVoteCategories);

            return array_merge($category, [
                'max_votes' => $isMultiVote ? 5 : 1,
                'max_pass'  => $isMultiVote ? 5 : 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $categories);

        DB::table('categories')->insert($categories);
    }
}
