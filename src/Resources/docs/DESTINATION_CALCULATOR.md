
[Read task description](https://open.kattis.com/problems/alldifferentdirections)

## General info
- implemented with symfony 4.1 (webserver can be launched with 'bin/console --dev=env server:start')
- run console command 'bin/console --dev=env app:calculate-best-paths INPUT_FILE_FULL_PATH OUTPUT_FILE_FULL_PATH'

## Main models:
- Destination (test case) - place where person want to get at
- Path - directions how to get to target place by 1 person
- Parser - parses inputs and returns corresponding objects
- DestinationCalculator - calculates all required data for output (average destination, distance to worst path endpoint)
- EndPointCalculator - calculates target coordinates (x, y) from Path


