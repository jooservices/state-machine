# Events

When an `EventDispatcherInterface` is provided:

| Event | When |
|:---|:---|
| `TransitionStarting` | After guards + before-callbacks, before write |
| `TransitionCompleted` | After successful write + after-callbacks |
| `TransitionFailed` | When state writing throws |

Without a dispatcher, events are not dispatched.
