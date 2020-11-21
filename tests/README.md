# Tests

This directory contains all [PHPUnit](https://phpunit.de/) tests for the php functions.

NOTES:
Every test with header() must be run as a separat process
The same applies to tests that include SQL queries
  
--> WHY:
The fixtures are set before every test that runs in a separat process.
And also deleted afterwards. So, if a test (A) runs normally (in the main process)
after a test (B) that ran in a separat process, the fixtures will be deleted 
after B and NOT set up again before A runs. Solution: A needs to run in a 
separat process as well.
 