# Modules and Domains

| Module | Responsibility |
|:---|:---|
| `StateMachine` | Transition orchestration |
| `StateMachineFactory` | Config array → machine |
| `Config/*` | Eager validation of graphs/transitions |
| `Accessors/*` | Read/write subject state |
| `Contracts/*` | Extension points |
| `Events/*` | Lifecycle notifications |
| `Exceptions/*` | Typed failure modes |

Domain rules belong in application guard/callback classes, not package core.
