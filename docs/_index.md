# ahLogger

A PHP class for debug logging during a client request of a PHP based website.

With it you can

- measure any action 
- find bottlenecks in your code.

You don't need to write any print_r and var_dump anymore and remove it
afterwards. Add _GET and _POST, and put a $oLog->add() at start and end
of any action you want to measure.

Compatible to PHP 8.3

👤 Author: Axel Hahn \
📄 Source: <https://github.com/axelhahn/ahlogger> \
📜 License: GNU GPL 3.0 \
📗 Docs: <https://www.axel-hahn.de/docs/ahlogger>

![Output](images/ahlogger-html-ouput.png)