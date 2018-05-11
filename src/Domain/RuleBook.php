<?php
declare(strict_types=1);


namespace BallGame\Domain;


use BallGame\Domain\Standings\TeamStanding;

class RuleBook
{
    public function __invoke(TeamStanding $teamA, TeamStanding $teamB)
    {
        if ($teamA->getPoints() > $teamB->getPoints()) {
            return -1;
        }

        if ($teamB->getPoints() > $teamA->getPoints()) {
            return 1;
        }

        return 0;
    }
}
