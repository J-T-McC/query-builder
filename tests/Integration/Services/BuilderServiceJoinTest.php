<?php

namespace Tests\Integration\Services;

use Database\Drivers\SQLite;
use Database\Exceptions\InvalidArgumentException;
use Tests\TestCase;

class BuilderServiceJoinTest extends TestCase
{

    /**
     * @test
     */
    public function it_inner_joins()
    {
        $this->iterateDrivers(function ($driver) {
            $idsSeen = [];
            $idsExpected = [1, 2, 3, 4];

            $driver
                ->table('users')
                ->select('users.*', 'projects.project_name')
                ->innerJoin('projects', 'id', 'user_id')
                ->each(function ($row) use (&$idsSeen) {
                    $idsSeen[] = $row['id'];
                });

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }

    /**
     * @test
     */
    public function it_defaults_inner_join()
    {
        $this->iterateDrivers(function ($driver) {
            $idsSeen = [];
            $idsExpected = [1, 2, 3, 4];

            $driver
                ->table('users')
                ->select('users.*', 'projects.project_name')
                ->join('projects', 'id', 'user_id')
                ->each(function ($row) use (&$idsSeen) {
                    $idsSeen[] = $row['id'];
                });

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }

    /**
     * @test
     */
    public function it_left_joins()
    {
        $this->iterateDrivers(function ($driver) {
            $idsSeen = [];
            $idsExpected = [1, 2, 3, 4, 5, 6];

            $driver
                ->table('users')
                ->select('users.*', 'projects.project_name')
                ->leftJoin('projects', 'id', 'user_id')
                ->each(function ($row) use (&$idsSeen) {
                    $idsSeen[] = $row['id'];
                });

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }

    /**
     * @test
     */
    public function it_right_joins()
    {
        $this->iterateDrivers(function ($driver, $type) {

            if($type === SQLite::class) {
                //not available for sqlite
                return;
            }

            $idsSeen = [];
            $idsExpected = [1, 2, 3, 4];

            $driver
                ->table('users')
                ->select('users.*', 'projects.project_name')
                ->rightJoin('projects', 'id', 'user_id')
                ->each(function ($row) use (&$idsSeen) {
                    $idsSeen[] = $row['id'];
                });

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }


    /**
     * @test
     */
    public function it_cross_joins()
    {
        $this->iterateDrivers(function ($driver, $type) {

            $results = $driver
                ->table('users')
                ->select('users.*', 'projects.project_name')
                ->crossJoin('projects')
                ->get();

            //16 projects by 6 users should see 96 rows
            $this->assertTrue(count($results) === 96);
        });
    }


    /**
     * @test
     */
    public function it_restricts_specific_join_types()
    {
        $this->iterateDrivers(function ($driver) {
            $this->expectException(InvalidArgumentException::class);
            $driver
                ->table('users')
                ->select('users.*', 'projects.project_name')
                ->join('projects', 'id', 'user_id', 'SOME JOIN')
                ->get();
        });
    }

}