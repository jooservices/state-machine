# Installation

## Requirements

- PHP `>=8.5`
- Composer 2.x

## Install the package

```bash
composer require jooservices/state-machine
```

## Optional events

Lifecycle event classes are always available. To dispatch them, pass a PSR-14 `EventDispatcherInterface` implementation into `StateMachine` / `StateMachineFactory`.
