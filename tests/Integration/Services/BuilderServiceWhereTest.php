<?php

namespace Tests\Integration\Services;

use Tests\TestCase;

class BuilderServiceWhereTest extends TestCase
{

    /**
     * @test
     */
    public function it_uses_where_statement()
    {
        $this->iterateDrivers(function ($driver) {
            $result = $driver->table('users')->where('id', '=', 3)->first();
            $this->assertTrue((int) $result['id'] === 3);
        });
    }

    /**
     * @test
     */
    public function it_uses_orWhere_statement()
    {
        $this->iterateDrivers(function ($driver) {
            $idsSeen = [];
            $idsExpected = [2, 3];

            $driver->table('users')->where('id', '=', 2)->orWhere('id', '=', 3)->each(function ($row) use (&$idsSeen) {
                $idsSeen[] = $row['id'];
            });

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }

    /**
     * @test
     */
    public function it_uses_whereIn()
    {
        $this->iterateDrivers(function ($driver) {
            $idsSeen = [];
            $idsExpected = [2, 3];

            $driver->table('users')->whereIn('id', $idsExpected)->each(function ($row) use (&$idsSeen) {
                $idsSeen[] = $row['id'];
            });

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }

    /**
     * @test
     */
    public function it_uses_orWhereIn()
    {
        $this->iterateDrivers(function ($driver) {
            $idsSeen = [];
            $idsExpected = [2, 3];

            $driver->table('users')->whereIn('id', [$idsExpected[0]])->orWhereIn('id', [$idsExpected[1]])->each(function ($row) use (&$idsSeen) {
                $idsSeen[] = $row['id'];
            });

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }

    /**
     * @test
     */
    public function it_uses_whereNotIn()
    {
        $this->iterateDrivers(function ($driver) {
            $idsSeen = [];
            $idsExpected = [1, 4, 5, 6];

            $driver->table('users')->whereNotIn('id', [2, 3])->each(function ($row) use (&$idsSeen) {
                $idsSeen[] = $row['id'];
            });

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }

    /**
     * @test
     */
    public function it_uses_orWhereNotIn()
    {
        $this->iterateDrivers(function ($driver) {
            $idsSeen = [];
            $idsExpected = [1, 2, 4, 5, 6];

            $driver->table('users')->where('id', '=', 2)->orWhereNotIn('id', [3])->each(function ($row) use (&$idsSeen) {
                $idsSeen[] = $row['id'];
            });

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }

    /**
     * @test
     */
    public function it_gets_where_statements() {
        $this->iterateDrivers(function ($driver) {
            $driver->table('users')->where('id', '=', 2)->where('id', '=', 3);
            $this->assertTrue(count($driver->getWhere()) === 2);
        });
    }
}