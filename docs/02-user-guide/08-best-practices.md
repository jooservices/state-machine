# Best Practices

- Keep graphs explicit and small; prefer multiple graphs over one mega-graph.
- Put domain rules in guards/callbacks owned by the application.
- Call `can()` before presenting actions in UI; still handle exceptions on `apply()`.
- Pass metadata for actor/context instead of relying on globals.
- Keep package free of framework service containers; resolve dependencies outside class-string hooks if you need DI.
- Cover every transition edge with unit or integration tests.
