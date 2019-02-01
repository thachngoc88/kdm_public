<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\MarkingLog;
use App\Record;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->deleteTablesForUsers();

        if(env('DUMMY_DATA')){
           $this->call(DummyUsersSeeder::class);
            $this->call(DummyPrefecturesSeeder::class);
            $this->call(DummyCitiesSeeder::class);
            $this->call(DummySchoolsSeeder::class);
            $this->call(DummyClassesSeeder::class);
            $this->call(DummyWorkbooksSeeder::class);
            $this->call(DummyQuestionsSeeder::class);
            $this->call(DummyChallengesSeeder::class);
            $this->call(DummySupplementsSeeder::class);
            $this->call(DummyChallengeQuestionSupplementSeeder::class);
            $this->call(DummyRecordsSeeder::class);
            $this->call(DummyChallengeUserUnitStatusSeeder::class);
            $this->call(DummyChallengeUserWorkbookStatusSeeder::class);
            $this->call(DummyExistingPdfSeeder::class);


        }
        else{
            $this->call(UsersSeeder::class);
            $this->call(PrefecturesSeeder::class);
            $this->call(CitiesSeeder::class);
            $this->call(SchoolsSeeder::class);
            $this->call(GradesSeeder::class);
            $this->call(SubjectsSeeder::class);
            $this->call(CurriculumsSeeder::class);
            $this->call(ClassesSeeder::class);
            $this->call(UnitsSeeder::class);
            $this->call(WorkbooksSeeder::class);
            $this->call(QuestionsSeeder::class);
            $this->call(AnswersSeeder::class);
            $this->call(RecordsSeeder::class);
            $this->call(TimingsSeeder::class);
            $this->call(ConditionsSeeder::class);
            $this->call(MessagesSeeder::class);
            $this->call(ChallengesSeeder::class);
            $this->call(SupplementsSeeder::class);
            $this->call(ChallengeQuestionSupplementSeeder::class);
            $this->call(ChallengeUserUnitStatusSeeder::class);
            $this->call(ChallengeUserWorkbookStatusSeeder::class);
            $this->call(ExistingPdfSeeder::class);
        }

        Model::reguard();
    }

    private function deleteTablesForUsers(){
        DB::statement('delete FROM records;');
        DB::statement('delete FROM prefecture_users;');
        DB::statement('delete FROM city_users;');
        DB::statement('delete FROM school_users;');
        DB::statement('delete FROM challenge_user_unit_statuses;');
        DB::statement('delete FROM challenge_user_workbook_statuses;');
        DB::statement('delete FROM challenge_user_mapsheet_accesses;');
        DB::statement('delete FROM challenge_user_workbook_update_counts;');
        DB::statement('delete FROM challenge_users;');
        DB::statement('delete FROM users;');
        DB::statement('delete FROM class_curriculum_passing_rates;');
        DB::statement('delete FROM class_passing_rates;');
        DB::statement('delete FROM class_unit_passing_rates;');
        DB::statement('delete FROM city_passing_rates;');
        DB::statement('delete FROM city_curriculum_passing_rates;');
        DB::statement('delete FROM city_unit_passing_rates;');
        DB::statement('delete FROM school_passing_rates;');
        DB::statement('delete FROM school_curriculum_passing_rates;');
        DB::statement('delete FROM school_unit_passing_rates;');
        DB::statement('delete FROM prefecture_passing_rates;');
        DB::statement('delete FROM prefecture_unit_passing_rates;');
        DB::statement('delete FROM prefecture_curriculum_passing_rates;');
        DB::statement('delete FROM existing_pdfs;');
        DB::statement('delete FROM classes;');
        DB::statement('delete FROM schools;');
        DB::statement('delete FROM cities;');
        DB::statement('delete FROM prefectures;');
        DB::statement('delete FROM challenge_questions_supplements;');
        DB::statement('delete FROM questions;');
        DB::statement('delete FROM challenges;');
        DB::statement('delete FROM supplements;');
        DB::statement('delete FROM workbooks;');
    }
}
