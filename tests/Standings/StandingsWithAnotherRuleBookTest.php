<?php
declare(strict_types=1);


namespace BallGame\Tests\Standings;


use BallGame\Domain\AnotherRuleBook;
use BallGame\Domain\Match\Match;
use BallGame\Domain\RuleBook;
use BallGame\Domain\Standings\Standings;
use BallGame\Domain\Team\Team;
use PHPUnit\Framework\TestCase;

class StandingsWithAnotherRuleBookTest extends TestCase
{
    /**
     * @var Standings
     */
    private $standings;

    public function setUp()
    {
        $this->standings = Standings::create('Seria A 2018', AnotherRuleBook::class);
    }

    public function testGetStandingsReturnsSortedLeagueStandingsWithAnotherRuleBook()
    {
        // Given
        $hellas = Team::create('Hellas Verona');
        $roma = Team::create('Roma');

        $match = Match::create($roma, $hellas, 1, 0);

        $this->standings->record($match);

        $match = Match::create($hellas, $roma, 3, 0);

        $this->standings->record($match);


        // When
        $standings = $this->standings->getStandings();

        // Then
        $this->assertEquals([
            ['Hellas Verona', 3, 1, 3],
            ['Roma', 1, 3, 3],
        ], $standings);
    }
}
