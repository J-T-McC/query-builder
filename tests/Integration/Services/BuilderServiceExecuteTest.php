<?php

namespace Tests\Integration\Services;

use Database\Exceptions\InvalidArgumentException;
use Tests\TestCase;

class BuilderServiceExecuteTest extends TestCase
{

    /**
     * @test
     */
    public function it_selects_all()
    {
        $this->iterateDrivers(function ($driver) {
            $this->assertTrue(count($driver->table('users')->get()) === 6);
        });
    }

    /**
     * @test
     */
    public function it_selects_first()
    {
        $this->iterateDrivers(function ($driver) {
            $this->assertTrue((int) $driver->table('users')->first()['id'] === 1);
        });
    }

    /**
     * @test
     */
    public function it_iterates_over_each_via_closure()
    {
        $this->iterateDrivers(function ($driver) {
            $idsSeen = [];
            $idsExpected = [1, 2, 3, 4, 5, 6];

            $driver->table('users')->each(function ($row) use (&$idsSeen) {
                $idsSeen[] = $row['id'];
            });

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }

    /**
     * @test
     */
    public function it_iterates_over_each_via_generator()
    {
        $this->iterateDrivers(function ($driver) {
            $idsSeen = [];
            $idsExpected = [1, 2, 3, 4, 5, 6];

            foreach($driver->table('users')->each() as $row) {
                $idsSeen[] = $row['id'];
            }

            $this->assertTrue(empty(array_diff($idsSeen, $idsExpected)));
        });
    }

    /**
     * @test
     */
    public function it_counts_rows()
    {
        $this->iterateDrivers(function ($driver) {
            $this->assertTrue($driver->table('users')->count() === 6);
        });
    }

    /**
     * @test
     */
    public function it_creates_row()
    {
        $this->iterateDrivers(function ($driver) {
            $driver->table('users')->create([
                'name' => 'New user!',
                'email' => 'new_user@example.com',
            ]);

            $this->assertTrue($driver->table('users')->count() === 7);
        });
    }

    /**
     * @test
     */
    public function it_updates_row()
    {
        $this->iterateDrivers(function ($driver) {
            $driver
                ->table('users')
                ->where('id', '=', 3)
                ->update([
                    'email' => 'updated-email@example.com'
                ]);

            $this->assertTrue($driver->table('users')->where('email', '=', 'updated-email@example.com')->count() === 1);
        });
    }

    /**
     * @test
     */
    public function it_restricts_characters_for_table()
    {
        $this->iterateDrivers(function ($driver) {
            $this->expectException(InvalidArgumentException::class);
            $driver->table('users;;=')->count();
        });
    }

    /**
     * @test
     */
    public function it_restricts_characters_for_column()
    {
        $this->iterateDrivers(function ($driver) {
            $this->expectException(InvalidArgumentException::class);
            $driver->table('users')->where('id;;=', '=', '1')->count();
        });
    }
}