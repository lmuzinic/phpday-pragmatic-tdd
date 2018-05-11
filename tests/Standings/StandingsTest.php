<?php
declare(strict_types=1);


namespace BallGame\Tests\Standings;


use BallGame\Domain\Match\Match;
use BallGame\Domain\RuleBook;
use BallGame\Domain\Standings\Standings;
use BallGame\Domain\Team\Team;
use PHPUnit\Framework\TestCase;

class StandingsTest extends TestCase
{
    /**
     * @var Standings
     */
    private $standings;

    public function setUp()
    {
        $this->standings = Standings::create('Seria A 2018', RuleBook::class);
    }

    public function testGetStandingsReturnsSortedLeagueStandings()
    {
        // Given
        $hellas = Team::create('Hellas Verona');
        $roma = Team::create('Roma');

        $match = Match::create($hellas, $roma, 1, 0);

        $this->standings->record($match);

        // When
        $standings = $this->standings->getStandings();

        // Then
        $this->assertEquals([
            ['Hellas Verona', 1, 0, 3],
            ['Roma', 0, 1, 0],
        ], $standings);
    }

    public function testGetStandingsReturnsSortedLeagueStandingsWhenSecondTeamIsFirst()
    {
        // Given
        $hellas = Team::create('Hellas Verona');
        $roma = Team::create('Roma');

        $match = Match::create($hellas, $roma, 0, 3);

        $this->standings->record($match);

        // When
        $standings = $this->standings->getStandings();

        // Then
        $this->assertEquals([
            ['Roma', 3, 0, 3],
            ['Hellas Verona', 0, 3, 0],
        ], $standings);
    }

    public function testGetStandingsReturnsSortedLeagueStandingsWithAnotherRuleBook()
    {
        // Given
        $hellas = Team::create('Hellas Verona');
        $roma = Team::create('Roma');

        $match = Match::create($hellas, $roma, 0, 3);

        $this->standings->record($match);

        // When
        $standings = $this->standings->getStandings();

        // Then
        $this->assertEquals([
            ['Roma', 3, 0, 3],
            ['Hellas Verona', 0, 3, 0],
        ], $standings);
    }
}
