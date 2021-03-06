<?php
declare(strict_types=1);


namespace BallGame\Domain;


use BallGame\Domain\Standings\TeamStanding;

class AnotherRuleBook
{
    public function __invoke(TeamStanding $teamA, TeamStanding $teamB)
    {
        if ($teamA->getPoints() > $teamB->getPoints()) {
            return -1;
        }

        if ($teamB->getPoints() > $teamA->getPoints()) {
            return 1;
        }

        if ($teamA->getPoints() === $teamB->getPoints()) {
            if ($teamA->getPointsScored() > $teamB->getPointsScored()) {
                return -1;
            }

            if ($teamB->getPointsScored() > $teamA->getPointsScored()) {
                return 1;
            }
        }

        return 0;
    }
}
