<?php
declare(strict_types=1);

namespace App\Twitter\Domain\Publication\Repository;

use App\Twitter\Infrastructure\Api\Entity\Aggregate;
use App\Twitter\Domain\Publication\StatusInterface;

interface TimelyStatusRepositoryInterface
{
    public function fromAggregatedStatus(
        StatusInterface $status,
        ?Aggregate $aggregaste = null
    );
}