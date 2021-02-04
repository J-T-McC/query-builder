<?php

namespace Tests\Integration\Services;

use Database\Exceptions\InvalidArgumentException;
use Tests\TestCase;

class BuilderServiceSelectTest extends TestCase
{

    /**
     * @test
     */
    public function it_selects_all_columns()
    {
        $this->iterateDrivers(function ($driver) {
            $result = $driver->table('users')->first();
            $this->assertTrue((int) $result['id'] === 1);
            $this->assertTrue(count(array_keys($result)) === 3);

            $result = $driver->table('users')->select('*')->first();
            $this->assertTrue((int) $result['id'] === 1);
            $this->assertTrue(count(array_keys($result)) === 3);
        });
    }

    /**
     * @test
     */
    public function it_selects_specified_columns()
    {
        $this->iterateDrivers(function ($driver) {
            $result = $driver->table('users')->select('id')->first();
            $this->assertTrue((int) $result['id'] === 1);
            $this->assertTrue(count(array_keys($result)) === 1);
        });
    }

    /**
     * @test
     */
    public function it_selects_raw()
    {
        $this->iterateDrivers(function ($driver) {
            $result = $driver->table('users')->selectRaw("'test_val' as test_col")->first();
            $this->assertTrue($result['test_col'] === 'test_val');
        });
    }

    /**
     * @test
     */
    public function it_groups_by()
    {
        $this->iterateDrivers(function ($driver) {
            $result = $driver->table('users')->selectRaw("'test_val' as test_col")->get();
            $this->assertTrue(count($result) === 6);

            $result = $driver->table('users')->selectRaw("'test_val' as test_col")->groupBy('test_col')->get();
            $this->assertTrue(count($result) === 1);
        });
    }

    /**
     * @test
     */
    public function it_orders_by()
    {
        $this->iterateDrivers(function ($driver) {
            $result = $driver->table('users')->orderBy('id', 'ASC')->first();
            $this->assertTrue((int) $result['id'] === 1);

            $result = $driver->table('users')->orderBy('id', 'DESC')->first();
            $this->assertTrue((int) $result['id'] === 6);
        });
    }

    /**
     * @test
     */
    public function it_ensures_unions_use_unique_builder_instance()
    {
        $this->iterateDrivers(function ($driver) {

            $this->expectException(InvalidArgumentException::class);

            $driver
                ->table('users')
                ->where('id', '=', 6)
                ->union($driver->table('users')->where('id', '=', 4))
                ->get();

        });
    }

    /**
     * @test
     */
    public function it_unions()
    {
        $this->iterateDrivers(function ($driver) {
            $idsSeen = [];
            $idsExpected = [4, 6];

            $forUnion = clone $driver;

            $driver
                ->table('users')
                ->where('id', '=', 6)
                ->union($forUnion->table('users')->where('id', '=', 4))
                ->each(function ($row) use (&$idsSeen) {
                    $idsSeen[] = (int) $row['id'];
                });

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }

}