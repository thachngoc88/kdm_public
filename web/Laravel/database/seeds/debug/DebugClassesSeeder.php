<?php

use App\Klass;
use App\User;
use Illuminate\Support\Facades\Hash;
class DebugClassesSeeder extends CommonSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create("ja_JP");

        $schools = \App\School::all('id');

        foreach ($schools as $school)
        {
            if($school->id == 1){
                for($i = 0; $i < 2; $i++){
                    $class = Klass::create([
                        "name" => $this->classesName()[$i],
                        "school_id" => $school->id,
                        "grade_id" => 1,
                    ]);
                    $class->save();

                    if($class->id == 1) {
                        for($userId = 6 ; $userId < 51; $userId++) {
                            \App\ChallengeUser::create([
                                "user_id" => $userId,
                                "class_id" => $class->id
                            ])->save();
                            $user = User::find($userId);
                            $user->login_id = $this->createLoginId1($user);
                            $password = $this->createPass1($user);
                            $user->password = Hash::make($password);
                            echo("id {$user->id}" . PHP_EOL);
                            echo("login_id {$user->login_id}" . PHP_EOL);
                            echo("password {$password}" . PHP_EOL);
                            $user->save();
                        }
                    }

                    if($class->id == 2) {
                        for($userId = 51 ; $userId < 96; $userId++) {
                            \App\ChallengeUser::create([
                                "user_id" => $userId,
                                "class_id" => $class->id
                            ])->save();
                            $user = User::find($userId);
                            $user->login_id = $this->createLoginId2($user);
                            $password = $this->createPass2($user);
                            $user->password = Hash::make($password);
                            echo("id {$user->id}" . PHP_EOL);
                            echo("login_id {$user->login_id}" . PHP_EOL);
                            echo("password {$password}" . PHP_EOL);
                            $user->save();
                        }
                    }


                }

            }
            if($school->id == 2){
                for($i = 2; $i < 4; $i++){
                    $class = Klass::create([
                        "name" => $this->classesName()[$i],
                        "school_id" => $school->id,
                        "grade_id" => 1,
                    ]);
                    $class->save();
                    if($class->id == 3) {
                        for($userId = 96 ; $userId < 141; $userId++) {
                            \App\ChallengeUser::create([
                                "user_id" => $userId,
                                "class_id" => $class->id
                            ])->save();
                            $user = User::find($userId);
                            $user->login_id = $this->createLoginId3($user);
                            $password = $this->createPass3($user);
                            $user->password = Hash::make($password);
                            echo("id {$user->id}" . PHP_EOL);
                            echo("login_id {$user->login_id}" . PHP_EOL);
                            echo("password {$password}" . PHP_EOL);
                            $user->save();
                        }

                    }
                    if($class->id == 4) {
                        for($userId = 141 ; $userId < 186; $userId++) {
                            \App\ChallengeUser::create([
                                "user_id" => $userId,
                                "class_id" => $class->id
                            ])->save();
                            $user = User::find($userId);
                            $user->login_id = $this->createLoginId4($user);
                            $password = $this->createPass4($user);
                            $user->password = Hash::make($password);
                            echo("id {$user->id}" . PHP_EOL);
                            echo("login_id {$user->login_id}" . PHP_EOL);
                            echo("password {$password}" . PHP_EOL);
                            $user->save();
                        }

                    }
                }

            }
        }
    }
    private function createLoginId(User $user){
       if(array_key_exists($user->id, $this->userClass()))
        {
            //echo("login_id have: {$this->userClass()[$user->id][0]}" . PHP_EOL);
            return $this->userClass()[$user->id][0];

        }
        $nameClass = $user->classes[0]->name;
        $nameGrade = $user->classes[0]->grade->number;
        $codeScholl = $user->classes[0]->school->code;
        $valid_fourDigit = "23456789";
        $valid_alphabetic ="abcdefghijkmnpqrstuvwxyz";
        do {
            $fourDigit = $this->randChars($valid_fourDigit, 4);
            $alphabetic = $this->randChars($valid_alphabetic, 1);
            $result = $codeScholl . '-' . $nameGrade . $nameClass . '-' . $fourDigit . $alphabetic;

            $input = array('login_id' => $result);
            $validator = Validator::make(array_filter($input), [
                'login_id' => 'unique:users,login_id'
            ]);
        }
        while($validator->fails());

        return $result;
    }
    private function createLoginId1(User $user){
        if(array_key_exists($user->id, $this->userClass1()))
        {
            //echo("login_id have: {$this->userClass()[$user->id][0]}" . PHP_EOL);
            return $this->userClass1()[$user->id][0];

        }
        $nameClass = $user->classes[0]->name;
        $nameGrade = $user->classes[0]->grade->number;
        $codeScholl = $user->classes[0]->school->code;
        $valid_fourDigit = "23456789";
        $valid_alphabetic ="abcdefghijkmnpqrstuvwxyz";
        do {
            $fourDigit = $this->randChars($valid_fourDigit, 4);
            $alphabetic = $this->randChars($valid_alphabetic, 1);
            $result = $codeScholl . '-' . $nameGrade . $nameClass . '-' . $fourDigit . $alphabetic;

            $input = array('login_id' => $result);
            $validator = Validator::make(array_filter($input), [
                'login_id' => 'unique:users,login_id'
            ]);
        }
        while($validator->fails());

        return $result;
    }
    private function createLoginId2(User $user){
        if(array_key_exists($user->id, $this->userClass2()))
        {
            //echo("login_id have: {$this->userClass()[$user->id][0]}" . PHP_EOL);
            return $this->userClass2()[$user->id][0];

        }
        $nameClass = $user->classes[0]->name;
        $nameGrade = $user->classes[0]->grade->number;
        $codeScholl = $user->classes[0]->school->code;
        $valid_fourDigit = "23456789";
        $valid_alphabetic ="abcdefghijkmnpqrstuvwxyz";
        do {
            $fourDigit = $this->randChars($valid_fourDigit, 4);
            $alphabetic = $this->randChars($valid_alphabetic, 1);
            $result = $codeScholl . '-' . $nameGrade . $nameClass . '-' . $fourDigit . $alphabetic;

            $input = array('login_id' => $result);
            $validator = Validator::make(array_filter($input), [
                'login_id' => 'unique:users,login_id'
            ]);
        }
        while($validator->fails());

        return $result;
    }
    private function createLoginId3(User $user){
        if(array_key_exists($user->id, $this->userClass3()))
        {
            //echo("login_id have: {$this->userClass()[$user->id][0]}" . PHP_EOL);
            return $this->userClass3()[$user->id][0];

        }
        $nameClass = $user->classes[0]->name;
        $nameGrade = $user->classes[0]->grade->number;
        $codeScholl = $user->classes[0]->school->code;
        $valid_fourDigit = "23456789";
        $valid_alphabetic ="abcdefghijkmnpqrstuvwxyz";
        do {
            $fourDigit = $this->randChars($valid_fourDigit, 4);
            $alphabetic = $this->randChars($valid_alphabetic, 1);
            $result = $codeScholl . '-' . $nameGrade . $nameClass . '-' . $fourDigit . $alphabetic;

            $input = array('login_id' => $result);
            $validator = Validator::make(array_filter($input), [
                'login_id' => 'unique:users,login_id'
            ]);
        }
        while($validator->fails());

        return $result;
    }
    private function createLoginId4(User $user){
        if(array_key_exists($user->id, $this->userClass4()))
        {
            //echo("login_id have: {$this->userClass()[$user->id][0]}" . PHP_EOL);
            return $this->userClass4()[$user->id][0];

        }
        $nameClass = $user->classes[0]->name;
        $nameGrade = $user->classes[0]->grade->number;
        $codeScholl = $user->classes[0]->school->code;
        $valid_fourDigit = "23456789";
        $valid_alphabetic ="abcdefghijkmnpqrstuvwxyz";
        do {
            $fourDigit = $this->randChars($valid_fourDigit, 4);
            $alphabetic = $this->randChars($valid_alphabetic, 1);
            $result = $codeScholl . '-' . $nameGrade . $nameClass . '-' . $fourDigit . $alphabetic;

            $input = array('login_id' => $result);
            $validator = Validator::make(array_filter($input), [
                'login_id' => 'unique:users,login_id'
            ]);
        }
        while($validator->fails());

        return $result;
    }
    private function createPass(User $user){

        if(array_key_exists($user->id, $this->userClass()))
        {
            //echo("password have: {$this->userClass()[$user->id][1]}" . PHP_EOL);
            return $this->userClass()[$user->id][1];

        }
        //
        $valid_Pass = "23456789abcdefghijkmnpqrstuvwxyz";
        $passUpdate = $this->randChars($valid_Pass, 4);

        return $passUpdate;
    }
    private function createPass1(User $user){

        if(array_key_exists($user->id, $this->userClass()))
        {
            //echo("password have: {$this->userClass()[$user->id][1]}" . PHP_EOL);
            return $this->userClass1()[$user->id][1];

        }
        //
        $valid_Pass = "23456789abcdefghijkmnpqrstuvwxyz";
        $passUpdate = $this->randChars($valid_Pass, 4);

        return $passUpdate;
    }
    private function createPass2(User $user){

        if(array_key_exists($user->id, $this->userClass()))
        {
            //echo("password have: {$this->userClass()[$user->id][1]}" . PHP_EOL);
            return $this->userClass2()[$user->id][1];

        }
        //
        $valid_Pass = "23456789abcdefghijkmnpqrstuvwxyz";
        $passUpdate = $this->randChars($valid_Pass, 4);

        return $passUpdate;
    }
    private function createPass3(User $user){

        if(array_key_exists($user->id, $this->userClass()))
        {
            //echo("password have: {$this->userClass()[$user->id][1]}" . PHP_EOL);
            return $this->userClass3()[$user->id][1];

        }
        //
        $valid_Pass = "23456789abcdefghijkmnpqrstuvwxyz";
        $passUpdate = $this->randChars($valid_Pass, 4);

        return $passUpdate;
    }
    private function createPass4(User $user){

        if(array_key_exists($user->id, $this->userClass()))
        {
            //echo("password have: {$this->userClass()[$user->id][1]}" . PHP_EOL);
            return $this->userClass4()[$user->id][1];

        }
        //
        $valid_Pass = "23456789abcdefghijkmnpqrstuvwxyz";
        $passUpdate = $this->randChars($valid_Pass, 4);

        return $passUpdate;
    }
    private function userClass(){
        return [
            6	=> ['a1-01w','nrz2'],
            7	=> ['a1-02p','8dyr'],
            8	=> ['a1-03h','885t'],
            9	=> ['a1-04z','bn6z'],
            10	=> ['a1-05b','mm25'],
            11	=> ['a1-06u','mynn'],
            12	=> ['a1-07m','xrwy'],
            13	=> ['a1-08a','pcwk'],
            14	=> ['a1-09x','b4nc'],
            15	=> ['a1-10w','b55n'],
            16	=> ['a1-11r','d7ue'],
            17	=> ['a1-12n','2ymu'],
            18	=> ['a1-13p','5c8w'],
            19	=> ['a1-14f','ftba'],
            20	=> ['a1-15f','3tjk'],
            21	=> ['a1-16p','878n'],
            22	=> ['a1-17k','7kn2'],
            23	=> ['a1-18b','vsb5'],
            24	=> ['a1-19z','vi73'],
            25	=> ['a1-20s','842d'],
            26	=> ['a1-21m','3bmk'],
            27	=> ['a1-22s','62k7'],
            28	=> ['a1-23n','it7r'],
            29	=> ['a1-24j','5yjt'],
            30	=> ['a1-25f','jh7v'],
            31	=> ['a1-26a','mmew'],
            32	=> ['a1-27t','nfex'],
            33	=> ['a1-28t','wdxt'],
            34	=> ['a1-29v','bvzs'],
            35	=> ['a1-30m','tb2c'],
            36	=> ['a1-31b','r7er'],
            37	=> ['a1-32u','nimh'],
            38	=> ['a1-33f','54p7'],
            39	=> ['a1-34f','rmud'],
            40	=> ['a1-35k','an73'],
            41	=> ['a1-36u','ewxs'],
            42	=> ['a1-37m','eh4d'],
            43	=> ['a1-38j','zepm'],
            44	=> ['a1-39u','8wfm'],
            45	=> ['a1-40z','j7xh'],
            46	=> ['a1-41v','345i'],
            47	=> ['a1-42c','dpm5'],
            48	=> ['a1-43p','8nhb'],
            49	=> ['a1-44e','hzxu'],
            50	=> ['a1-45c','xsy5'],
            51	=> ['a2-01e','dcdm'],
            52	=> ['a2-02c','xewu'],
            53	=> ['a2-03x','3isv'],
            54	=> ['a2-04j','zht2'],
            55	=> ['a2-05h','467n'],
            56	=> ['a2-06c','hb7h'],
            57	=> ['a2-07h','dkc2'],
            58	=> ['a2-08e','5aum'],
            59	=> ['a2-09i','hyw5'],
            60	=> ['a2-10i','s4w6'],
            61	=> ['a2-11z','pum3'],
            62	=> ['a2-12e','yh8n'],
            63	=> ['a2-13s','435j'],
            64	=> ['a2-14c','7i4i'],
            65	=> ['a2-15x','mwkh'],
            66	=> ['a2-16i','mzup'],
            67	=> ['a2-17a','zxy6'],
            68	=> ['a2-18p','jzbv'],
            69	=> ['a2-19z','nc7y'],
            70	=> ['a2-20v','6tyt'],
            71	=> ['a2-21c','anpx'],
            72	=> ['a2-22h','pusv'],
            73	=> ['a2-23h','xkfm'],
            74	=> ['a2-24j','icyv'],
            75	=> ['a2-25u','eben'],
            76	=> ['a2-26v','bzd4'],
            77	=> ['a2-27c','e6f8'],
            78	=> ['a2-28r','7zac'],
            79	=> ['a2-29p','6mih'],
            80	=> ['a2-30t','6hrm'],
            81	=> ['a2-31h','2ver'],
            82	=> ['a2-32w','jtek'],
            83	=> ['a2-33d','skvc'],
            84	=> ['a2-34s','7te4'],
            85	=> ['a2-35m','tit4'],
            86	=> ['a2-36k','68nx'],
            87	=> ['a2-37b','mfi6'],
            88	=> ['a2-38y','2h7j'],
            89	=> ['a2-39b','tnbh'],
            90	=> ['a2-40w','nme8'],
            91	=> ['a2-41p','ibdt'],
            92	=> ['a2-42i','37ns'],
            93	=> ['a2-43i','fuy8'],
            94	=> ['a2-44a','wvmh'],
            95	=> ['a2-45z','m2ev'],
            96	=> ['b1-01z','3jck'],
            97	=> ['b1-02d','4n53'],
            98	=> ['b1-03h','aity'],
            99	=> ['b1-04i','hzwt'],
            100	=> ['b1-05v','vrw6'],
            101	=> ['b1-06s','u7fh'],
            102	=> ['b1-07c','45jv'],
            103	=> ['b1-08p','xhst'],
            104	=> ['b1-09t','syx7'],
            105	=> ['b1-10t','6ck2'],
            106	=> ['b1-11c','j34b'],
            107	=> ['b1-12d','5rky'],
            108	=> ['b1-13n','ea5f'],
            109	=> ['b1-14j','88ef'],
            110	=> ['b1-15m','rfsx'],
            111	=> ['b1-16n','ada4'],
            112	=> ['b1-17n','xn53'],
            113	=> ['b1-18j','jy6w'],
            114	=> ['b1-19x','n7tw'],
            115	=> ['b1-20v','ws48'],
            116	=> ['b1-21h','yd4d'],
            117	=> ['b1-22r','83k5'],
            118	=> ['b1-23a','8b2d'],
            119	=> ['b1-24b','jv87'],
            120	=> ['b1-25f','br5a'],
            121	=> ['b1-26b','zx48'],
            122	=> ['b1-27y','f4ua'],
            123	=> ['b1-28k','45nc'],
            124	=> ['b1-29b','tvjd'],
            125	=> ['b1-30m','2ipc'],
            126	=> ['b1-31w','vviv'],
            127	=> ['b1-32z','6kn5'],
            128	=> ['b1-33e','fyrb'],
            129	=> ['b1-34z','ivzh'],
            130	=> ['b1-35c','4zxj'],
            131	=> ['b1-36t','2wtm'],
            132	=> ['b1-37n','cm3v'],
            133	=> ['b1-38e','t35p'],
            134	=> ['b1-39d','mupe'],
            135	=> ['b1-40j','kxh8'],
            136	=> ['b1-41v','6ut4'],
            137	=> ['b1-42b','vpei'],
            138	=> ['b1-43v','48xt'],
            139	=> ['b1-44h','f4uv'],
            140	=> ['b1-45u','nh4u'],
            141	=> ['b2-01e','uz3s'],
            142	=> ['b2-02f','nt2u'],
            143	=> ['b2-03n','pbxw'],
            144	=> ['b2-04u','28w5'],
            145	=> ['b2-05b','xp56'],
            146	=> ['b2-06a','6nss'],
            147	=> ['b2-07r','ubze'],
            148	=> ['b2-08a','6r4h'],
            149	=> ['b2-09s','kvse'],
            150	=> ['b2-10x','y6wj'],
            151	=> ['b2-11p','d2ai'],
            152	=> ['b2-12i','ybr6'],
            153	=> ['b2-13s','hi6r'],
            154	=> ['b2-14d','mi5y'],
            155	=> ['b2-15v','drj5'],
            156	=> ['b2-16a','cr37'],
            157	=> ['b2-17f','ymwd'],
            158	=> ['b2-18b','xaii'],
            159	=> ['b2-19w','ta5f'],
            160	=> ['b2-20a','jd27'],
            161	=> ['b2-21u','7dz7'],
            162	=> ['b2-22a','38ee'],
            163	=> ['b2-23w','m7x6'],
            164	=> ['b2-24e','nmze'],
            165	=> ['b2-25s','piym'],
            166	=> ['b2-26p','ueun'],
            167	=> ['b2-27a','2ia4'],
            168	=> ['b2-28m','36yc'],
            169	=> ['b2-29j','3fnp'],
            170	=> ['b2-30y','kwwv'],
            171	=> ['b2-31t','thr5'],
            172	=> ['b2-32p','7b2s'],
            173	=> ['b2-33y','y5u3'],
            174	=> ['b2-34a','u8rk'],
            175	=> ['b2-35c','ipps'],
            176	=> ['b2-36e','mer2'],
            177	=> ['b2-37v','thux'],
            178	=> ['b2-38p','24fn'],
            179	=> ['b2-39u','k383'],
            180	=> ['b2-40t','pz3h'],
            181	=> ['b2-41k','arts'],
            182	=> ['b2-42e','7sjp'],
            183	=> ['b2-43r','f84y'],
            184	=> ['b2-44e','mbky'],
            185	=> ['b2-45r','4rhf']
        ];

    }
    private function userClass1(){
        return [
            6	=> ['a1-01w','nrz2'],
            7	=> ['a1-02p','8dyr'],
            8	=> ['a1-03h','885t'],
            9	=> ['a1-04z','bn6z'],
            10	=> ['a1-05b','mm25'],
            11	=> ['a1-06u','mynn'],
            12	=> ['a1-07m','xrwy'],
            13	=> ['a1-08a','pcwk'],
            14	=> ['a1-09x','b4nc'],
            15	=> ['a1-10w','b55n'],
            16	=> ['a1-11r','d7ue'],
            17	=> ['a1-12n','2ymu'],
            18	=> ['a1-13p','5c8w'],
            19	=> ['a1-14f','ftba'],
            20	=> ['a1-15f','3tjk'],
            21	=> ['a1-16p','878n'],
            22	=> ['a1-17k','7kn2'],
            23	=> ['a1-18b','vsb5'],
            24	=> ['a1-19z','vi73'],
            25	=> ['a1-20s','842d'],
            26	=> ['a1-21m','3bmk'],
            27	=> ['a1-22s','62k7'],
            28	=> ['a1-23n','it7r'],
            29	=> ['a1-24j','5yjt'],
            30	=> ['a1-25f','jh7v'],
            31	=> ['a1-26a','mmew'],
            32	=> ['a1-27t','nfex'],
            33	=> ['a1-28t','wdxt'],
            34	=> ['a1-29v','bvzs'],
            35	=> ['a1-30m','tb2c'],
            36	=> ['a1-31b','r7er'],
            37	=> ['a1-32u','nimh'],
            38	=> ['a1-33f','54p7'],
            39	=> ['a1-34f','rmud'],
            40	=> ['a1-35k','an73'],
            41	=> ['a1-36u','ewxs'],
            42	=> ['a1-37m','eh4d'],
            43	=> ['a1-38j','zepm'],
            44	=> ['a1-39u','8wfm'],
            45	=> ['a1-40z','j7xh'],
            46	=> ['a1-41v','345i'],
            47	=> ['a1-42c','dpm5'],
            48	=> ['a1-43p','8nhb'],
            49	=> ['a1-44e','hzxu'],
            50	=> ['a1-45c','xsy5']
        ];
    }
    private function userClass2(){
        return [
            51	=> ['a2-01e','dcdm'],
            52	=> ['a2-02c','xewu'],
            53	=> ['a2-03x','3isv'],
            54	=> ['a2-04j','zht2'],
            55	=> ['a2-05h','467n'],
            56	=> ['a2-06c','hb7h'],
            57	=> ['a2-07h','dkc2'],
            58	=> ['a2-08e','5aum'],
            59	=> ['a2-09i','hyw5'],
            60	=> ['a2-10i','s4w6'],
            61	=> ['a2-11z','pum3'],
            62	=> ['a2-12e','yh8n'],
            63	=> ['a2-13s','435j'],
            64	=> ['a2-14c','7i4i'],
            65	=> ['a2-15x','mwkh'],
            66	=> ['a2-16i','mzup'],
            67	=> ['a2-17a','zxy6'],
            68	=> ['a2-18p','jzbv'],
            69	=> ['a2-19z','nc7y'],
            70	=> ['a2-20v','6tyt'],
            71	=> ['a2-21c','anpx'],
            72	=> ['a2-22h','pusv'],
            73	=> ['a2-23h','xkfm'],
            74	=> ['a2-24j','icyv'],
            75	=> ['a2-25u','eben'],
            76	=> ['a2-26v','bzd4'],
            77	=> ['a2-27c','e6f8'],
            78	=> ['a2-28r','7zac'],
            79	=> ['a2-29p','6mih'],
            80	=> ['a2-30t','6hrm'],
            81	=> ['a2-31h','2ver'],
            82	=> ['a2-32w','jtek'],
            83	=> ['a2-33d','skvc'],
            84	=> ['a2-34s','7te4'],
            85	=> ['a2-35m','tit4'],
            86	=> ['a2-36k','68nx'],
            87	=> ['a2-37b','mfi6'],
            88	=> ['a2-38y','2h7j'],
            89	=> ['a2-39b','tnbh'],
            90	=> ['a2-40w','nme8'],
            91	=> ['a2-41p','ibdt'],
            92	=> ['a2-42i','37ns'],
            93	=> ['a2-43i','fuy8'],
            94	=> ['a2-44a','wvmh'],
            95	=> ['a2-45z','m2ev']
        ];
    }
    private function userClass3(){
        return [
            96	=> ['b1-01z','3jck'],
            97	=> ['b1-02d','4n53'],
            98	=> ['b1-03h','aity'],
            99	=> ['b1-04i','hzwt'],
            100	=> ['b1-05v','vrw6'],
            101	=> ['b1-06s','u7fh'],
            102	=> ['b1-07c','45jv'],
            103	=> ['b1-08p','xhst'],
            104	=> ['b1-09t','syx7'],
            105	=> ['b1-10t','6ck2'],
            106	=> ['b1-11c','j34b'],
            107	=> ['b1-12d','5rky'],
            108	=> ['b1-13n','ea5f'],
            109	=> ['b1-14j','88ef'],
            110	=> ['b1-15m','rfsx'],
            111	=> ['b1-16n','ada4'],
            112	=> ['b1-17n','xn53'],
            113	=> ['b1-18j','jy6w'],
            114	=> ['b1-19x','n7tw'],
            115	=> ['b1-20v','ws48'],
            116	=> ['b1-21h','yd4d'],
            117	=> ['b1-22r','83k5'],
            118	=> ['b1-23a','8b2d'],
            119	=> ['b1-24b','jv87'],
            120	=> ['b1-25f','br5a'],
            121	=> ['b1-26b','zx48'],
            122	=> ['b1-27y','f4ua'],
            123	=> ['b1-28k','45nc'],
            124	=> ['b1-29b','tvjd'],
            125	=> ['b1-30m','2ipc'],
            126	=> ['b1-31w','vviv'],
            127	=> ['b1-32z','6kn5'],
            128	=> ['b1-33e','fyrb'],
            129	=> ['b1-34z','ivzh'],
            130	=> ['b1-35c','4zxj'],
            131	=> ['b1-36t','2wtm'],
            132	=> ['b1-37n','cm3v'],
            133	=> ['b1-38e','t35p'],
            134	=> ['b1-39d','mupe'],
            135	=> ['b1-40j','kxh8'],
            136	=> ['b1-41v','6ut4'],
            137	=> ['b1-42b','vpei'],
            138	=> ['b1-43v','48xt'],
            139	=> ['b1-44h','f4uv'],
            140	=> ['b1-45u','nh4u']
        ];
    }
    private function userClass4(){
        return [
            141	=> ['b2-01e','uz3s'],
            142	=> ['b2-02f','nt2u'],
            143	=> ['b2-03n','pbxw'],
            144	=> ['b2-04u','28w5'],
            145	=> ['b2-05b','xp56'],
            146	=> ['b2-06a','6nss'],
            147	=> ['b2-07r','ubze'],
            148	=> ['b2-08a','6r4h'],
            149	=> ['b2-09s','kvse'],
            150	=> ['b2-10x','y6wj'],
            151	=> ['b2-11p','d2ai'],
            152	=> ['b2-12i','ybr6'],
            153	=> ['b2-13s','hi6r'],
            154	=> ['b2-14d','mi5y'],
            155	=> ['b2-15v','drj5'],
            156	=> ['b2-16a','cr37'],
            157	=> ['b2-17f','ymwd'],
            158	=> ['b2-18b','xaii'],
            159	=> ['b2-19w','ta5f'],
            160	=> ['b2-20a','jd27'],
            161	=> ['b2-21u','7dz7'],
            162	=> ['b2-22a','38ee'],
            163	=> ['b2-23w','m7x6'],
            164	=> ['b2-24e','nmze'],
            165	=> ['b2-25s','piym'],
            166	=> ['b2-26p','ueun'],
            167	=> ['b2-27a','2ia4'],
            168	=> ['b2-28m','36yc'],
            169	=> ['b2-29j','3fnp'],
            170	=> ['b2-30y','kwwv'],
            171	=> ['b2-31t','thr5'],
            172	=> ['b2-32p','7b2s'],
            173	=> ['b2-33y','y5u3'],
            174	=> ['b2-34a','u8rk'],
            175	=> ['b2-35c','ipps'],
            176	=> ['b2-36e','mer2'],
            177	=> ['b2-37v','thux'],
            178	=> ['b2-38p','24fn'],
            179	=> ['b2-39u','k383'],
            180	=> ['b2-40t','pz3h'],
            181	=> ['b2-41k','arts'],
            182	=> ['b2-42e','7sjp'],
            183	=> ['b2-43r','f84y'],
            184	=> ['b2-44e','mbky'],
            185	=> ['b2-45r','4rhf']
        ];
    }
    private function classesName(){
        return [1,2,1,2];
    }
}
