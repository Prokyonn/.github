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

use ModelflowAi\Chat\AIChatRequestHandlerInterface;
use ModelflowAi\Chat\Request\Message\AIChatMessage;
use ModelflowAi\Chat\Response\AIChatResponse;

class Thread implements ThreadInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $context = [];

    /**
     * @var AIChatMessage[]
     */
    private array $messages = [];

    public function __construct(
        private readonly AIChatRequestHandlerInterface $requestHandler,
        private readonly ExpertInterface $expert,
    ) {
    }

    public function addContext(string $key, mixed $data): self
    {
        $this->context[$key] = $data;

        return $this;
    }

    public function addMessage(AIChatMessage $message): self
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * @param AIChatMessage[] $messages
     */
    public function addMessages(array $messages): self
    {
        $this->messages = \array_merge($this->messages, $messages);

        return $this;
    }

    public function run(): AIChatResponse
    {
        $builder = $this->requestHandler->createRequest()
            ->addSystemMessage($this->expert->instructions)
            ->addCriteria($this->expert->criteria)
            ->asJson();

        if ($this->expert->responseFormat instanceof ResponseFormat\ResponseFormatInterface) {
            $builder->addSystemMessage($this->expert->responseFormat->format());
        }

        if ([] !== $this->context) {
            $builder->addUserMessage('Context: ' . \json_encode($this->context));
        }

        foreach ($this->messages as $message) {
            $builder->addMessage($message);
        }

        return $builder->build()
            ->execute();
    }
}
