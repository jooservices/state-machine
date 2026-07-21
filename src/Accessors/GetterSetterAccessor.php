<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Accessors;

use JOOservices\StateMachine\Contracts\StateAccessorInterface;

/**
 * Reads and writes state via conventional getter/setter methods.
 *
 * For a property named "status", this accessor calls:
 * - `getStatus()` or `status()` for reading
 * - `setStatus($value)` for writing
 */
class GetterSetterAccessor implements StateAccessorInterface
{
    public function getState(object $subject, string $property): ?string
    {
        $getter = 'get'.ucfirst($property);

        if (method_exists($subject, $getter)) {
            $value = $this->invokeSubjectMethod($subject, $getter);

            return is_string($value) ? $value : null;
        }

        if (method_exists($subject, $property)) {
            $value = $this->invokeSubjectMethod($subject, $property);

            return is_string($value) ? $value : null;
        }

        return null;
    }

    public function setState(object $subject, string $property, string $state): void
    {
        $setter = 'set'.ucfirst($property);

        if (method_exists($subject, $setter)) {
            $this->invokeSubjectMethod($subject, $setter, [$state]);
        }
    }

    /**
     * @param  list<mixed>  $arguments
     */
    private function invokeSubjectMethod(object $subject, string $method, array $arguments = []): mixed
    {
        /** @var callable $callable */
        $callable = [$subject, $method];

        return $callable(...$arguments);
    }
}
