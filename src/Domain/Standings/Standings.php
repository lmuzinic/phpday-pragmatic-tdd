<?php
declare(strict_types=1);


namespace BallGame\Domain\Standings;


use BallGame\Domain\Match\Match;

class Standings
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Match[]
     */
    private $matches;

    /**
     * @var TeamStanding[]
     */
    private $teamStandings;

    /**
     * @var string
     */
    private $rulebook;

    /**
     * @param string $name
     */
    private function __construct(string $name, $rulebook)
    {
        $this->name = $name;
        $this->rulebook = $rulebook;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Standings
     */
    public static function create(string $name, $rulebook): Standings
    {
        return new self($name, $rulebook);
    }

    /**
     * @param $match Match
     */
    public function record(Match $match)
    {
        $this->matches[] = $match;
    }

    public function getStandings()
    {
        foreach ($this->matches as $match) {
            $homeTeamStanding = $this->getHomeTeamStanding($match);
            $awayTeamStanding = $this->getAwayTeamStanding($match);

            // Home team won
            if ($match->getHomeTeamPoints() > $match->getAwayTeamPoints()) {
                $homeTeamStanding->recordWin();
                $awayTeamStanding->recordLost();
            }

            // Away team won
            if ($match->getAwayTeamPoints() > $match->getHomeTeamPoints()) {
                $awayTeamStanding->recordWin();
                $homeTeamStanding->recordLost();
            }

            $homeTeamStanding->recordPointsScored($match->getHomeTeamPoints());
            $homeTeamStanding->recordPointsAgainst($match->getAwayTeamPoints());

            $awayTeamStanding->recordPointsScored($match->getAwayTeamPoints());
            $awayTeamStanding->recordPointsAgainst($match->getHomeTeamPoints());
        }

        $rulebook = new $this->rulebook;
        uasort($this->teamStandings, function (TeamStanding $teamA, TeamStanding $teamB) use ($rulebook) {
            return $rulebook($teamA, $teamB);
        });

        $finalStandings = [];
        foreach ($this->teamStandings as $teamStanding) {
            $finalStandings[] = [
                $teamStanding->getTeam()->getName(),
                $teamStanding->getPointsScored(),
                $teamStanding->getPointsAgainst(),
                $teamStanding->getPoints()
            ];
        }

        return $finalStandings;
    }

    private function getHomeTeamStanding(Match $match)
    {
        if (!isset($this->teamStandings[spl_object_hash($match->getHomeTeam())])) {
            $this->teamStandings[spl_object_hash($match->getHomeTeam())] = TeamStanding::create($match->getHomeTeam());
        }

        return $this->teamStandings[spl_object_hash($match->getHomeTeam())];
    }

    private function getAwayTeamStanding(Match $match)
    {
        if (!isset($this->teamStandings[spl_object_hash($match->getAwayTeam())])) {
            $this->teamStandings[spl_object_hash($match->getAwayTeam())] = TeamStanding::create($match->getAwayTeam());
        }

        return $this->teamStandings[spl_object_hash($match->getAwayTeam())];
    }
}
