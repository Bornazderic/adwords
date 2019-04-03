<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        
        $user = User::create([
            'name' => 'nile',
            'email' => 'manuel@tempusmedia.hr',
            'password' => bcrypt('1q2w3e4r5t')
        ]);


        $site = new \App\Site([
            'name' => 'Nikas Staging',
            'store_url' => 'http://staging.tempus.media',
            'consumer_key' => encrypt('ck_fec70ccc608dffab43ac375587183f999d3a9122'),
            'consumer_secret' => encrypt('cs_6608e2a6f5451b168cd1c64ca4d781c338831427'),
        ]);
 
 
         $site2 = new \App\Site([
             'name' => 'Nikas Production',
             'store_url' => 'https://shop.nikas.hr',
             'consumer_key' => encrypt('ck_174d206a6a676220e512e7136016dd1ac91db93e'),
             'consumer_secret' => encrypt('cs_e2badc5d25370cdecb59aea407217ed9993e9897'),
         ]);
 
         $site3 = new \App\Site([
             'name' => 'Nemec pharmacia',
             'store_url' => 'https://nemecpharmacia.hr',
             'consumer_key' => encrypt('ck_c03317301cbef1805b9a99f2b858f30060d611f4'),
             'consumer_secret' => encrypt('cs_25181ca7a7083b75717d6a7cfd4d4ce47249fe97'),
         ]);
 
         $site4 = new \App\Site([
             'name' => 'Feroflam',
             'store_url' => 'http://feroflam.hr',
             'consumer_key' => encrypt('ck_af3224ae1a44cabc172c280598af14fcd9c2be3a'),
             'consumer_secret' => encrypt('cs_6f9c648cb11e21b914363b7d3c82e0db83d3b80c'),
         ]);
 
         $site5 = new \App\Site([
             'name' => 'Laval',
             'store_url' => 'http://laval.hr',
             'consumer_key' => encrypt('ck_df886836c26c333c60bf31b7e21c3ebf2fddc320'),
             'consumer_secret' => encrypt('cs_687cd1e021dc08ff5327cd818ecdde336906925d'),
         ]);

        $user->site()->save($site);
        $user->site()->save($site2);
        $user->site()->save($site3);
        $user->site()->save($site4);
        $user->site()->save($site5);
    }
}
