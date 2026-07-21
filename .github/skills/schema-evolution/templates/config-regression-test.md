# Template: Config Regression Test

1. Capture a valid baseline config array used in fixtures.
2. Assert success path still builds a machine and applies a transition.
3. Assert invalid variants throw `InvalidConfigurationException` with expected key names.
