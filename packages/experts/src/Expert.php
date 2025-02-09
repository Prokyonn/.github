<?php

declare(strict_types=1);

/*
 * This file is part of the Modelflow AI package.
 *
 * (c) Johannes Wachter <johannes@sulu.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ModelflowAi\Experts;

use ModelflowAi\DecisionTree\Criteria\CriteriaInterface;
use ModelflowAi\Experts\ResponseFormat\ResponseFormatInterface;

class Expert implements ExpertInterface
{
    /**
     * @param CriteriaInterface[] $criteria
     */
    public function __construct(
        public string $name,
        public string $description,
        public string $instructions,
        public array $criteria,
        public ?ResponseFormatInterface $responseFormat = null,
    ) {
    }
}
